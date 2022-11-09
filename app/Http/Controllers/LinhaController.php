<?php

namespace App\Http\Controllers;

use App\Models\Linha;
use App\Models\Produto;
use App\Models\SubLinha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LinhaController extends Controller
{
    public function index(){
        $produto = Produto::where('ativo', 'S')->where('estoque','>',0)->orderBy('linha_id')->orderBy('sub_linha_id')->get();
        $linha_array = [];

        foreach ($produto as $p) {
           $linha_array[] = $p->linha_id;
        }

        $linha = Linha::whereIn('id', $linha_array)->orderBy('nome')->get();

        return $linha;
    }
}
