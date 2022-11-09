<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLinha extends Model
{
    protected $table = 'sub_linha';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id',
        'linha_id',
        'nome',
    ];
}
