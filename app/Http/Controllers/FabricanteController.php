<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fabricante;
use Illuminate\Http\Request;

class FabricanteController extends Controller
{
    public function index(){
        return Fabricante::where('preview_site', 'S')->orderBy('id')->get();
        // return view('home', compact('banner'));
    }
}
