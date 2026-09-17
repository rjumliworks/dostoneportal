<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            default:
                return inertia('Modules/Portal/Files/Index');
        }   
    }
}
