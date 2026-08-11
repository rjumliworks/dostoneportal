<?php

namespace App\Console\Commands;

use App\Models\ParticipantDetail;
use App\Models\ParticipantFace;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Console\Command;

class DedupeParticipantFaces extends Command
{
    protected $signature = 'participants:dedupe-faces {--force : Skip the confirmation prompt}';
    protected $description = 'Collapse participants with more than one indexed Rekognition face down to a single face, re-indexed from their current participant_details.avatar.';

    public function handle()
    {
        $detailIds = ParticipantFace::where('status', 'active')
            ->select('participant_id')
            ->groupBy('participant_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('participant_id');

        if ($detailIds->isEmpty()) {
            $this->info('No participants with duplicate faces found.');
            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm(
            "Found {$detailIds->count()} participant(s) with more than one indexed face. For each, this will re-index a single face from their current avatar and delete every other face on file (AWS + local rows). Continue?"
        )) {
            $this->info('Aborted. No changes were made.');
            return self::SUCCESS;
        }

        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => config('services.rekognition.region'),
            'credentials' => [
                'key' => config('services.rekognition.key'),
                'secret' => config('services.rekognition.secret'),
            ],
        ]);
        $collectionId = config('services.rekognition.participant_id');
        $bucket = config('services.rekognition.bucket');

        $fixed = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($detailIds->count());
        $bar->start();

        foreach ($detailIds as $detailId) {
            $detail = ParticipantDetail::find($detailId);
            $bar->advance();

            if (! $detail) {
                $skipped++;
                continue;
            }

            // getRawOriginal() bypasses ParticipantDetail::getAvatarAttribute(), which
            // rewrites the stored key into a full cache-busted S3 URL for display.
            $avatarKey = $detail->getRawOriginal('avatar');
            if (! $avatarKey || in_array($avatarKey, ['avatar.jpg', 'noavatar.jpg'], true)) {
                $this->newLine();
                $this->warn("Skipped participant_detail #{$detailId}: no real avatar on file ({$avatarKey}).");
                $skipped++;
                continue;
            }

            $participantId = (string) $detail->participant_id;
            $existingFaces = ParticipantFace::where('participant_id', $detail->id)->get();

            try {
                // Index from the current avatar first, before touching any existing
                // face — AWS has no rollback, so confirming the new face works before
                // deleting the old ones means a failure here never leaves the
                // participant with zero indexed faces.
                $result = $rekognition->indexFaces([
                    'CollectionId' => $collectionId,
                    'Image' => [
                        'S3Object' => [
                            'Bucket' => $bucket,
                            'Name' => $avatarKey,
                        ],
                    ],
                    'ExternalImageId' => $participantId,
                    'DetectionAttributes' => ['DEFAULT'],
                ]);

                if (empty($result['FaceRecords'])) {
                    $this->newLine();
                    $this->error("Participant #{$participantId}: no face detected in current avatar, left duplicates untouched.");
                    $failed++;
                    continue;
                }

                $newFaceIds = collect($result['FaceRecords'])->pluck('Face.FaceId')->all();

                // Exclude any FaceId AWS handed back that we already had on file -
                // it dedupes against near-identical images instead of indexing a new
                // one, and that face must not be deleted out from under itself.
                $staleFaces = $existingFaces->reject(fn ($f) => in_array($f->face_id, $newFaceIds, true));

                if ($staleFaces->isNotEmpty()) {
                    $rekognition->deleteFaces([
                        'CollectionId' => $collectionId,
                        'FaceIds' => $staleFaces->pluck('face_id')->all(),
                    ]);
                    ParticipantFace::whereIn('id', $staleFaces->pluck('id'))->delete();
                }

                foreach ($result['FaceRecords'] as $record) {
                    ParticipantFace::firstOrCreate(
                        ['face_id' => $record['Face']['FaceId']],
                        [
                            'participant_id' => $detail->id,
                            'image_id' => $record['Face']['ImageId'],
                            'status' => 'active',
                        ]
                    );
                }

                $fixed++;
            } catch (\Throwable $e) {
                \Log::error("participants:dedupe-faces failed for participant #{$participantId}: ".$e->getMessage());
                $this->newLine();
                $this->error("Participant #{$participantId}: {$e->getMessage()}");
                $failed++;
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Fixed: {$fixed}, skipped (no avatar): {$skipped}, failed: {$failed}.");

        return self::SUCCESS;
    }
}
