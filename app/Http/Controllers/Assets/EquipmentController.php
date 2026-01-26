<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            default:
                return inertia('Modules/Assets/Equipments/Index'); 
        }   
    }
}
