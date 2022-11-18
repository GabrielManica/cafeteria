<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SubLinhaController;

class LojaController extends Controller
{
    public function index(){
        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'nome';

        $produtos = new ProdutoController;

        $produtos = $produtos->index($order);

        $total_produtos = Produto::count();

        $pesquisa = '';

        $total_carrinho = isset($_SESSION['total_carrinho'])?$_SESSION['total_carrinho']:0;

        return view('shop', compact('order', 'pesquisa', 'total_produtos', 'produtos', 'total_carrinho'));
    }

    public function pesquisa(Request $request)
    {
        $produtos = new ProdutoController;

        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'nome';

        $produtos = $produtos->pesquisa($request->pesquisa, $order);

        $total_produtos = Produto::where('nome', 'ilike' , "%{$request->pesquisa}%")->count();

        $pesquisa = $request->pesquisa;

        $total_carrinho = isset($_SESSION['total_carrinho'])?$_SESSION['total_carrinho']:0;

        return view('shop', compact('order', 'pesquisa', 'total_produtos', 'produtos', 'total_carrinho'));
    }
}
