<?php

namespace App\Http\Controllers;

use App\Models\Linha;
use App\Models\Produto;
use App\Models\SubLinha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

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

    public function produto($id, Request $request)
    {
        $produto = Produto::find($id);

        $total_carrinho = Session::get('total_carrinho')?Session::get('total_carrinho'):0;
        $mensagem       = '';

        if(Session::get('mensagem'))
        {
            $mensagem = Session::get('mensagem');
            Session::put('mensagem', null);
        }

        return view('produto', compact('produto', 'total_carrinho', 'mensagem'));
    }

    public function add_produto(Request $request)
    {
        $produto = Produto::find($request->produto_id);
        Session::put('mensagem', 'Produto adiconado ao carrinho!');

        if(Session::get('total_carrinho'))
        {
            $total_carrinho = Session::get('total_carrinho');
            $total_carrinho = $total_carrinho + 1;
            Session::put('total_carrinho', $total_carrinho);
        }
        else
        {
            Session::put('total_carrinho', 1);
        }

        if(Session::get('produtos_carinho')){
            $produtos = Session::get('produtos_carinho');
            $produtos[] = $produto;
        }
        else
        {
            $produtos[] = $produto;
        }

        Session::put('produtos_carinho', $produtos);

        return redirect("/produto/".$request->produto_id);
    }
}
