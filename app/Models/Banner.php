<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id',
        'titulo',
        'sub_titulo',
        'conteudo',
        'imagem',
        'mostrar_so_imagem',
        'link'
    ];
}
