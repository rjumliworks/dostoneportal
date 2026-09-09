<?php

namespace App\Http\Resources\Portal\Schedules;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $official = $this['official'];
        $date = $this['date'];

        $start = $date->start;
        $end = $date->end;
        $isAllDay = $date->time_of_day === 'Whole Day';

        $startDay = date("M d, Y", strtotime($start));
        $endDay = date("M d, Y", strtotime($end));
        $time = $date->time;

        if ($isAllDay) {
            $displayDate = $startDay == $endDay
                ? $startDay
                : "$startDay to $endDay";
        } else {
            $displayDate = $startDay == $endDay
                ? "$startDay ($time)"
                : "$startDay to $endDay ($time)";
        }

        $firstType = $official->types->first();

        return [
            'id' => $date->id, // unique event id
            'title' => $official->title,
            'start' => Carbon::parse($date->start)->format('Y-m-d'),
            'end' => Carbon::parse($date->end)->format('Y-m-d'),
            'allDay' => $isAllDay,

            'type' => $official->types->pluck('name')->implode(', '),
            'className' => $firstType ? trim($firstType->bg.' '.$firstType->color) : 'bg-primary text-white',

            'full_title' => $official->title,
            'datee' => $displayDate,

            'request_event_id' => $official->id,
            'start_date' => date("M d, Y g:i a", strtotime($start)),
            'end_date' => date("M d, Y g:i a", strtotime($end)),

            's_date' => date("Y-m-d H:i", strtotime($start)),
            'e_date' => date("Y-m-d H:i", strtotime($end)),

            'ss_date' => date("M d, Y", strtotime($start)),
            'ee_date' => date("M d, Y", strtotime($end)),

            'day' => date("d", strtotime($start)),
            'day_name' => date("D", strtotime($start)),

            'mode' => optional($official->mode)->name,
            'audience' => optional($official->audience)->name,
            'types' => $official->types->pluck('name'),
            'participants' => $this->participants($official),
        ];
    }

    /**
     * Gathers everyone tied to this event: the person who originally
     * tagged/registered it, its companions (if any), and everyone from
     * every Travel Order that separately referenced this same event.
     */
    private function participants($official): array
    {
        $users = collect();

        $collect = function ($req) use (&$users) {
            if (!$req) {
                return;
            }
            if ($req->user) {
                $users->push($req->user);
            }
            foreach ($req->tags as $tag) {
                if ($tag->user) {
                    $users->push($tag->user);
                }
            }
        };

        $collect($official->request);
        foreach ($official->travels as $travel) {
            $collect($travel->request);
        }

        return $users->unique('id')->values()->map(function ($user) {
            return [
                'user_id' => $user->id,
                'name' => optional($user->profile) ? $user->profile->firstname.' '.$user->profile->lastname : 'n/a',
                'avatar' => $this->avatarUrl(optional($user->profile)->avatar),
            ];
        })->toArray();
    }

    private function avatarUrl($avatar): string
    {
        if (!$avatar || $avatar === 'noavatar.jpg') {
            return asset('images/avatars/avatar.jpg');
        }
        return (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://'))
            ? $avatar
            : asset('storage/' . $avatar);
    }
}
