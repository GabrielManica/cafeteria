<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LojaController;
use App\Http\Controllers\ProdutoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LojaController::class, 'index'])->name('/');
Route::get('/loja', [LojaController::class, 'index'])->name('loja');
Route::get('/loja/linha/{linha_id}/sublinha/{sub_linha_id}', [LojaController::class, 'categoria'])->name('categoria');
Route::get('/loja/pesquisa', [LojaController::class, 'pesquisa'])->name('pesquisa');
Route::get('/produto/{produto_id}', [ProdutoController::class, 'produto']);
Route::get('/remove_item/{numero}', [ProdutoController::class, 'remove_item']);

Route::post('/adicionar_produto', [ProdutoController::class, 'add_produto'])->name('add');
Route::post('/finalizar', [ProdutoController::class, 'finalizar'])->name('finalizar');
