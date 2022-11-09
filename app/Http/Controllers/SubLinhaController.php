<?php

namespace App\Http\Controllers;

use App\Models\Linha;
use App\Models\Produto;
use App\Models\SubLinha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubLinhaController extends Controller
{
    public function index(){
        $produto = Produto::where('ativo', 'S')->where('estoque','>',0)->orderBy('linha_id')->orderBy('sub_linha_id')->get();
        $sub_linha_array = [];

        foreach ($produto as $p) {
           $sub_linha_array[] = $p->sub_linha_id;
        }

        $sub_linha = SubLinha::whereIn('id', $sub_linha_array)->orderBy('nome')->get();

        return $sub_linha;
    }
}
