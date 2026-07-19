<?php

namespace App\Http\Controllers\Api\Events;

use App\Models\Hotel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\HotelResource;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $participantId = $request->id;

        $data = Hotel::with('location','rates')->where('is_active',1)->get();
        return HotelResource::collection($data);
    }
}
