<?php

namespace App\Http\Resources\Event\Exhibitor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class ViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $qrResult = (new Builder(
            writer: new PngWriter(),
            data: Crypt::encryptString($this->code),
            size: 800,
            margin: 5,
            logoPath: public_path('images/qrlogo.png'),
            logoResizeToWidth: 80,
            labelText: $this->title
        ))->build();

        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'reference' => $this->reference,
            'institution' => $this->institution,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'contact' => $this->contact,
            'qr' => 'data:image/png;base64,' . base64_encode($qrResult->getString()),
            'event' => $this->event,
            'visitors' => VisitorResource::collection($this->visitors),
            'voters' => VoterResource::collection(
                $this->visitors->where('has_voted', true)->values()
            ),
            'feedbacks' => FeedbackResource::collection($this->feedbackable),
        ];
    }
}
