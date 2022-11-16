<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'id';
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'nome',
        'documento',
        'celular'
    ];
}
