<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(){
        return Banner::where('ativo', 'S')->orderBy('id')->get();
        // return view('home', compact('banner'));
    }
}
