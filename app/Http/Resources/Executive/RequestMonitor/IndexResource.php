<?php

namespace App\Http\Resources\Executive\RequestMonitor;

use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Portal\Request\TagResource;

class IndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hashids = new Hashids('krad',10);
        $key = $hashids->encode($this->id);

        switch($this->type->name){
            case 'Travel Order':
                $subtype = optional($this->travel?->mode)->name;
            break;
            case 'Leave Form':
                $subtype = optional($this->leave?->type)->name;
            break;
            case 'Render Overtime Service':
                $subtype = $this->type->name;
            break;
            case 'Training':
                $subtype = $this->type->name;
            break;
            default:
                $subtype = optional($this->reservation?->vehicle)->name;
        }

        $link = Str::slug($this->type->name) . 'krad' . $key;

        return [
            'id' => $this->id,
            'key' => $key,
            'code' => $this->code,
            'type' => $this->type->name,
            'subtype' => $subtype,
            'is_completed' => $this->is_completed,
            'link' => Crypt::encryptString($link),
            'purpose' => optional($this->detail)->purpose,
            'remarks' => optional($this->detail)->remarks,
            'start' => optional($this->dates->first())->start ?? '-',
            'end'   => optional($this->dates->first())->end ?? '-',
            'requested_by' => optional($this->user->profile) ? $this->user->profile->firstname.' '.$this->user->profile->lastname : 'n/a',
            'requested_by_avatar' => ($this->user->profile && $this->user->profile->avatar && $this->user->profile->avatar !== 'noavatar.jpg')
                ? asset('storage/' . $this->user->profile->avatar)
                : asset('images/avatars/avatar.jpg'),
            'tags' => TagResource::collection($this->tags),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
