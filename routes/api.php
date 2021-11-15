<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/blip/messages', 'AtendimentoController@addMessage');

Route::post('/atendimentos/novo', 'AtendimentoController@getProtocolo');
Route::post('/atendimentos/{id}/historico', 'AtendimentoController@addHistorico');
Route::post('/atendimentos/{id}/pesquisa', 'BlipController@pesquisaSatisfacao');
Route::get('/atendimentos/{id}/pesquisa', 'BlipController@pesquisaSatisfacao');
Route::post('/atendimentos/{id}', 'AtendimentoController@update');
Route::post('/atendimentos/{id}/finaliza', 'AtendimentoController@finalizaTicket');
Route::get('/atendimentos/{id}/finaliza', 'AtendimentoController@finalizaTicket');

Route::get('/trocas/{id}', 'BlipController@consultaTroca');
Route::get('/trocas/{id}/checaCliente', 'BlipController@checaClienteTroca');
Route::get('/clientes/{id}', 'BlipController@verificaCliente');

Route::get('/pedidos/{id}', 'BlipController@consultaPedido');


Route::middleware('auth:api')->group(function () {


});

Route::post('modelos/ciclos', 'CicloColecaoController@alteraCiclo');
Route::get('produto/caracteristica/{caracteristica}', 'CaracteristicaController@getCaracteristicas');

Route::post('catalogo/{codigo}/addModelo', 'CatalogoController@addModelo');
Route::post('catalogo/{codigo}/addItem', 'CatalogoController@addItem');
Route::get('integracao2/{referencia}', 'IntegracaoController@consultaSaldo');

Route::get('/produto/cores', 'ItemController@outrasCores');
Route::get('/produto/{referencia}', 'ItemController@dadosProduto');

Route::get('/compras/pedidos/{referencia}', 'CompraController@listaPedidosItem');
Route::get('/compras/item/consulta', 'CompraController@consultaItem');
Route::post('/compras/item/insere', 'CompraController@insereItem');
Route::post('/compras/item/planejamento', 'CompraController@inserePlanejamento');
Route::get('/compras/item/planejamento', 'CompraDistribuicaoController@listaDistribuicaoItem');
Route::post('/compras/item/edita', 'CompraController@editaItem');
Route::post('/compras/item/exclui', 'CompraController@excluiItem');


Route::post('/processa/altera-status', 'StatusProcessaController@alteraStatus');


Route::get('/dashboard/indGrife', 'DashboardController@indGrife');
Route::get('/pivot', 'PivotController@listaItens');
Route::post('/pivot', 'PivotController@carrega');

Route::get('/pivot_10', 'PivotController@listaItens_10');
Route::post('/pivot_10', 'PivotController@carrega_10');


Route::get('/pivot_site', 'PivotController@listaItens_site');
Route::post('/pivot_site', 'PivotController@carrega_site');


Route::get('/ecommerce/', 'ECommerceController@liberaGrifes');
