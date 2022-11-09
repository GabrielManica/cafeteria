<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id',
        'fabricante_id',
        'linha_id',
        'sub_linha_id',
        'fornecedor_id',
        'unidade_medida_id',
        'nome',
        'codigo_barras',
        'preco_custo',
        'preco_venda',
        'peso_liquido',
        'peso_bruto',
        'largura',
        'altura',
        'volume',
        'estoque',
        'estoque_minimo',
        'observacao',
        'foto',
        'ativo',
        'data_desativacao',
        'data_hora_criacao',
        'data_hora_alteracao',
        'data_hora_exclusao',
        'preco_venda_pronta_entrega',
        'markup',
        'preco_venda_prazo',
        'preco_venda_pronta_entrega_prazo',
        'percentual_ipi',
        'percentual_icms',
        'referencia_fornecedor',
        'observacao_site',
        'produto_destaque',
    ];
}
