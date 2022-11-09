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

        $linhas   = new LinhaController;
        $produtos = new ProdutoController;

        $linhas   = $linhas->index();
        $produtos = $produtos->index($order);

        $sub_linhas = new SubLinhaController;

        $sub_linhas = $sub_linhas->index();

        $total_produtos = Produto::where('ativo', 'S')->where('estoque','>',0)->count();

        $linha_id     = -10;
        $sub_linha_id = -10;

        $pesquisa = '';

        return view('shop', compact('order', 'linha_id', 'sub_linha_id', 'pesquisa', 'linhas', 'sub_linhas', 'total_produtos', 'produtos'));
    }

    public function categoria($linha_id, $sub_linha_id)
    {
        $linhas   = new LinhaController;
        $produtos = new ProdutoController;

        $linhas   = $linhas->index();

        $sub_linhas = new SubLinhaController;

        $sub_linhas = $sub_linhas->index();

        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'nome';

        if($sub_linha_id != -1)
        {
            $total_produtos = Produto::where('ativo', 'S')->where('estoque','>',0)->where('linha_id', $linha_id)->where('sub_linha_id', $sub_linha_id)->count();
            $produtos = $produtos->sub_linha($linha_id, $sub_linha_id, $order);
        }
        else
        {
            $total_produtos = Produto::where('ativo', 'S')->where('estoque','>',0)->where('linha_id', $linha_id)->count();
            $produtos = $produtos->linha($linha_id, $order);
        }

        $pesquisa = '';

        return view('shop', compact('order', 'linha_id', 'sub_linha_id', 'pesquisa', 'linhas', 'sub_linhas', 'total_produtos', 'produtos'));
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
