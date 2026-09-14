<?php

namespace App\Services\Executive\Maintenance;

use Aws\CommandPool;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class S3Class
{
    protected const MAX_ZIP_FILES = 2000;
    protected const MAX_ZIP_BYTES = 300 * 1024 * 1024;

    protected S3Client $client;
    protected string $bucket;

    public function __construct()
    {
        $config = config('filesystems.disks.s3');

        $this->bucket = $config['bucket'];
        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $config['region'],
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        ]);
    }

    public function browse(string $prefix = '', ?string $token = null): array
    {
        $prefix = $this->normalizePrefix($prefix);

        $result = $this->client->listObjectsV2(array_filter([
            'Bucket' => $this->bucket,
            'Prefix' => $prefix,
            'Delimiter' => '/',
            'MaxKeys' => 200,
            'ContinuationToken' => $token,
        ]));

        $folders = collect($result['CommonPrefixes'] ?? [])
            ->map(fn ($item) => [
                'name' => rtrim(Str::after($item['Prefix'], $prefix), '/'),
                'prefix' => $item['Prefix'],
            ])
            ->values()
            ->all();

        $files = collect($result['Contents'] ?? [])
            ->filter(fn ($item) => $item['Key'] !== $prefix)
            ->map(fn ($item) => [
                'name' => Str::after($item['Key'], $prefix),
                'key' => $item['Key'],
                'size' => $item['Size'],
                'last_modified' => $item['LastModified']->format('Y-m-d H:i:s'),
            ])
            ->values()
            ->all();

        return [
            'prefix' => $prefix,
            'breadcrumb' => $this->breadcrumb($prefix),
            'folders' => $folders,
            'files' => $files,
            'next_token' => $result['NextContinuationToken'] ?? null,
        ];
    }

    protected function normalizePrefix(string $prefix): string
    {
        $prefix = trim($prefix, '/');

        return $prefix === '' ? '' : $prefix . '/';
    }

    protected function breadcrumb(string $prefix): array
    {
        $parts = array_filter(explode('/', rtrim($prefix, '/')));
        $crumbs = [];
        $current = '';

        foreach ($parts as $part) {
            $current .= $part . '/';
            $crumbs[] = ['name' => $part, 'prefix' => $current];
        }

        return $crumbs;
    }

    public function downloadUrl(string $key): string
    {
        return Storage::disk('s3')->temporaryUrl($key, now()->addMinutes(5));
    }

    public function downloadFolder(string $prefix)
    {
        $prefix = $this->normalizePrefix($prefix);

        abort_if($prefix === '', 422, 'Select a specific folder before downloading.');

        $objects = [];
        $totalSize = 0;
        $token = null;

        do {
            $result = $this->client->listObjectsV2(array_filter([
                'Bucket' => $this->bucket,
                'Prefix' => $prefix,
                'ContinuationToken' => $token,
            ]));

            foreach ($result['Contents'] ?? [] as $object) {
                if (Str::endsWith($object['Key'], '/')) {
                    continue;
                }

                $objects[] = $object;
                $totalSize += $object['Size'];

                abort_if(
                    count($objects) > self::MAX_ZIP_FILES || $totalSize > self::MAX_ZIP_BYTES,
                    422,
                    'This folder is too large to download as a zip (limit: ' . self::MAX_ZIP_FILES . ' files / ' . round(self::MAX_ZIP_BYTES / 1024 / 1024) . 'MB). Download a narrower subfolder or individual files instead.'
                );
            }

            $token = $result['NextContinuationToken'] ?? null;
        } while ($token);

        abort_if(empty($objects), 404, 'No files found in this folder.');

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $folderName = str_replace('/', '-', rtrim($prefix, '/'));
        $zipName = $folderName . '-' . now()->format('Ymd_His') . '.zip';
        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipName;

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $commands = array_map(fn ($object) => $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $object['Key'],
        ]), $objects);

        $pool = new CommandPool($this->client, $commands, [
            'concurrency' => 10,
            'fulfilled' => function ($result, $index) use ($zip, $objects, $prefix) {
                $relative = Str::after($objects[$index]['Key'], $prefix);
                $zip->addFromString($relative, $result['Body']->getContents());
            },
        ]);

        $pool->promise()->wait();

        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }
}
