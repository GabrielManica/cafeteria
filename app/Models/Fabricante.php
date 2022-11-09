<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model
{
    protected $table = 'fabricante';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id',
        'nome',
        'logo',
        'preview_site'
    ];
}
