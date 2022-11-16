<?php

namespace App\Http\Controllers;

use App\Models\Linha;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\SubLinha;
use Illuminate\Http\Request;
use App\Models\PedidoProduto;
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

        return view('produto', compact('produto', 'total_carrinho'));
    }

    public function finalizar(Request $request)
    {
        $cliente = Cliente::where('documento', $request->cpf)->first();

        if(!$cliente)
        {
            $cliente = new Cliente;
            $cliente->nome = $request->nome;
            $cliente->documento = $request->cpf;
            $cliente->save();
        }

        $pedido = new Pedido;
        $pedido->total_pedido = Session::get('total_valor_carrinho');
        $pedido->cliente_id = $cliente->id;
        $pedido->data_pedido = date('Y-m-d');
        $pedido->save();

        foreach (Session::get('produtos_carinho') as $p) {
            $produto = new PedidoProduto;
            $produto->pedido_id = $pedido->id;
            $produto->produto_id = $p->id;
            $produto->quantidade = 1;
            $produto->valor_produto = $p->valor;
            $produto->save();
        }

        Session::put('produtos_carinho', null);
        Session::put('total_carrinho', 0);
        Session::put('total_valor_carrinho', 0);

        Session::put('mensagem', ['type'=>'success', 'mensagem'=> "Pedido #{$pedido->id} Finalizado com sucesso!"]);

        return back();
    }

    public function add_produto(Request $request)
    {
        $produto = Produto::find($request->produto_id);
        Session::put('mensagem', ['type'=>'success', 'mensagem'=> 'Produto adiconado ao carrinho!']);

        if(Session::get('total_carrinho'))
        {
            $total_carrinho = Session::get('total_carrinho');
            $total_carrinho = $total_carrinho + 1;
            Session::put('total_carrinho', $total_carrinho);

            $total_valor_carrinho = Session::get('total_valor_carrinho');
            $total_valor_carrinho = $total_valor_carrinho + $produto->valor;
            Session::put('total_valor_carrinho', $total_valor_carrinho);
        }
        else
        {
            Session::put('total_carrinho', 1);
            Session::put('total_valor_carrinho', $produto->valor);
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
