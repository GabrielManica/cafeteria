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
        $linhas   = new LinhaController;
        $produtos = new ProdutoController;

        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'nome';

        $linhas   = $linhas->index();
        $produtos = $produtos->pesquisa($request->pesquisa, $order);

        $sub_linhas = new SubLinhaController;

        $sub_linhas = $sub_linhas->index();

        $total_produtos = Produto::where('ativo', 'S')->where('estoque','>',0)->where('nome', 'ilike' , "%{$request->pesquisa}%")->count();

        $linha_id     = -10;
        $sub_linha_id = -10;

        $pesquisa = $request->pesquisa;

        return view('shop', compact('order', 'linha_id', 'sub_linha_id', 'pesquisa', 'linhas', 'sub_linhas', 'total_produtos', 'produtos'));
    }
}
