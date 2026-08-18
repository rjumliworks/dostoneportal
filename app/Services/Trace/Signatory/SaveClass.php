<?php

namespace App\Services\Trace\Signatory;

use App\Models\OrgChart;
use App\Models\OrgSignatory;
use App\Models\OrgSignatorySchedule;
use App\Models\Post;

class SaveClass
{
    const POST_TYPE_SPECIAL_ORDER = 203;
    const POST_VISIBILITY_ID = 202;

    public function signatory($request)
    {
        if (now()->greaterThanOrEqualTo($request->start_at)) {
            $data = OrgSignatorySchedule::create($request->all());
            $signatory = OrgSignatory::find($request->signatory_id);
            $signatory->update([
                'oic_id' => $request->user_id,
                'is_oic' => 1,
            ]);
            $data->update(['is_ongoing' => 1]);
        } else {
            $data = OrgSignatorySchedule::create(
                array_merge($request->all(), ['is_ongoing' => 0])
            );
        }

        if ($request->boolean('create_post')) {
            $this->createDesignationPost($request);
        }

        return [
            'data' => $data,
            'message' => 'Signatory assigned successfully',
            'info' => $request->boolean('create_post')
                ? 'The officer-in-charge has been assigned and a post has been published for this designation.'
                : 'The officer-in-charge has been assigned for this designation.',
        ];
    }

    private function createDesignationPost($request)
    {
        $number = $this->generateSpecialOrderNumber(self::POST_TYPE_SPECIAL_ORDER);

        return Post::create([
            'code' => $this->generatePostCode(),
            'number' => $number,
            'title' => "DOST Special Order No. {$number}, Series of " . now()->year,
            'content' => $request->content,
            'is_commentable' => true,
            'type_id' => self::POST_TYPE_SPECIAL_ORDER,
            'visibility_id' => self::POST_VISIBILITY_ID,
            'user_id' => \Auth::id(),
        ]);
    }

    private function generatePostCode()
    {
        return \DB::transaction(function () {
            $prefix = 'SO-' . now()->format('mY') . '-';

            $latest = Post::withTrashed()
                ->where('code', 'like', $prefix . '%')
                ->lockForUpdate()
                ->max('code');

            $next = $latest ? ((int) substr($latest, strlen($prefix)) + 1) : 1;

            return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
        });
    }

    private function generateSpecialOrderNumber($typeId)
    {
        return \DB::transaction(function () use ($typeId) {
            $max = Post::withTrashed()
                ->where('type_id', $typeId)
                ->whereYear('created_at', now()->year)
                ->lockForUpdate()
                ->max('number');

            return (string) ((int) $max + 1);
        });
    }

    public function designate($request)
    {
        $userId = $request->user_id;
        if($request->is_oic){
            $data = OrgChart::find($request->signatory_id);
            $data->update(['oic_id' => $request->user_id, 'is_oic' => 1]);
            if($data){
                $signatory = $data->designationable;
                $signatory->update([
                    'user_id' => $userId
                ]);
                $signatory->schedules()->update([
                    'is_ongoing' => 0
                ]); 
                $signatory->schedules()->create([
                    'start_at' => $request->start_at,
                    'end_at' => $request->end_at,
                    'user_id' => $userId,
                    'is_designated' => 1,
                    'is_ongoing' => 1,
                ]); 
            }
        }else{
            $data = OrgChart::find($request->signatory_id);
            $data->update(['user_id' => $request->user_id]);

            if($data){
                $signatory = $data->designationable;
                $signatory->update([
                    'user_id' => $userId
                ]);
                $signatory->schedules()->create([
                    'start_at' => now(),
                    'user_id' => $userId,
                    'is_designated' => 1,
                    'is_ongoing' => 1,
                ]); 
            }
        }
        return [
            'data' => $data,
            'message' => 'Employee created successfully',
            'info' => 'You can now manage this employee’s details in the system',
        ];
    }
}
