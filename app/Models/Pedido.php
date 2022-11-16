<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'id';
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'total_pedido',
        'cliente_id',
        'data_pedido'
    ];
}
