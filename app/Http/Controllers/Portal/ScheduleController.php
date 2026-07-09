<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
     public function index(Request $request){
        switch($request->option){
            default:
                return inertia('Modules/Portal/Schedules/Index'); 
        }   
    }
}
