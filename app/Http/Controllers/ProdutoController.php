<?php

namespace App\Http\Controllers;

use App\Models\Linha;
use App\Models\Produto;
use App\Models\SubLinha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdutoController extends Controller
{
    public function index($order){
        $explode = explode(' ', $order);
        $explode[1] = isset($explode[1]) ? $explode[1] : 'asc';
        $produto = Produto::orderBy($explode[0], $explode[1])->get();

        return $produto;
    }

    public function pesquisa($pesquisa, $order){
        $explode = explode(' ', $order);
        $explode[1] = isset($explode[1]) ? $explode[1] : 'asc';
        $produto = Produto::where('ativo', 'S')->where('estoque', '>', 0)->where('nome', 'ilike' , "%{$pesquisa}%")->orderBy($explode[0], $explode[1])->get();
        foreach ($produto as $p) {
            $p->linha     = Linha::find($p->linha_id);
            $p->sub_linha = SubLinha::find($p->sub_linha_id);
        }
        return $produto;
    }

    public function linha($linha_id, $order){
        $explode = explode(' ', $order);
        $explode[1] = isset($explode[1]) ? $explode[1] : 'asc';
        $produto = Produto::where('ativo', 'S')->where('estoque', '>', 0)->where('linha_id', $linha_id)->orderBy($explode[0], $explode[1])->get();
        foreach ($produto as $p) {
            $p->linha     = Linha::find($p->linha_id);
            $p->sub_linha = SubLinha::find($p->sub_linha_id);
        }
        return $produto;
    }

    public function sub_linha($linha_id, $sub_linha_id, $order){
        $explode = explode(' ', $order);
        $explode[1] = isset($explode[1]) ? $explode[1] : 'asc';
        $produto = Produto::where('ativo', 'S')->where('estoque', '>', 0)->where('linha_id', $linha_id)->where('sub_linha_id', $sub_linha_id)->orderBy($explode[0], $explode[1])->get();
        foreach ($produto as $p) {
            $p->linha     = Linha::find($p->linha_id);
            $p->sub_linha = SubLinha::find($p->sub_linha_id);
        }
        return $produto;
    }

    public function produto($id)
    {
        $produto = Produto::find($id);

        return view('produto', compact('produto'));
    }
}
