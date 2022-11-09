<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $banner     = new BannerController;
        $fabricante = new FabricanteController;
        $produto    = new ProdutoController;

        $banner     = $banner->index();
        $fabricante = $fabricante->index();
        $produto    = $produto->destaque();

        return view('home', compact('banner', 'fabricante', 'produto'));
    }
}
