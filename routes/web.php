<?php

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

Route::get('/', function () {
    return view('container/home');
});

Route::prefix('produto')->group(function () {
    Route::get('index', 'ProdutoController@index')->name('produto.index');
    Route::get('getProdutos/{id?}', 'ProdutoController@getProdutos')->name('produto.getProdutos');
    Route::post('salvar/{id?}', 'ProdutoController@salvar')->name('produto.salvar');
    Route::delete('deletar/{id}', 'ProdutoController@deletar')->name('produto.deletar');
});

Route::prefix('pedido')->group(function () {
    Route::get('index', 'PedidoController@index')->name('pedido.index');
    Route::get('getPedidos/{id?}', 'PedidoController@getPedidos')->name('pedido.getPedidos');
    Route::post('salvar/{id?}', 'PedidoController@salvar')->name('pedido.salvar');
    Route::delete('deletar/{id}', 'PedidoController@deletar')->name('pedido.deletar');
});
