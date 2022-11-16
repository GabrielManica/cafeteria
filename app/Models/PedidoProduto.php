<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    protected $table = 'pedido_produto';
    protected $primaryKey = 'id';
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'valor_produto'
    ];
}
