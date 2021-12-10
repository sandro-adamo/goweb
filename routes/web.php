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
Route::post('/getProtocoloAtendimento', 'AtendimentoController@getProtocolo');
Route::get('/addHistorico', 'AtendimentoController@addHistorico');
Route::get('/estimativa', 'EstimativaComercialController@geraEstimativa');
Route::get('/historicos/{id}/excluir', 'ClienteController@excluirHistorico');
Route::get('/exportaComissoesFat/{ano}/{periodo}', 'ComissaoController@exportaComissoesFat');
Route::get('/atualizaSituacaoGrife', 'CarteiraController@atualizaSituacaoGrife');
Route::get('/atualizaSituacaoCliente', 'CarteiraController@atualizaSituacaoCliente');
Route::get('/atualizaFidelizados', 'CarteiraController@atualizaFidelizados');
Route::get('/importaComissoes', 'ComissaoController@importaComissoes');
Route::get('/exportaExcel/{ano}/{mes}', 'DashboardController@exportaExcel');

Route::get('/produtos', 'ClienteController@listaClientes');
Route::get('/campanhas', 'ClienteController@listaClientes');
Route::get('/produtos/statusitens/atualiza', 'StatusProcessaController@atualizastatusitens');

Route::get('/reenviaEmail/{id}', 'MostruarioController@reenviaEmail');

Route::post('/codigos', 'SessaoController@alteraCodigos');


Route::post('/ajuste/grava', 'ItemController@gravaAjusteImagem');
Route::post('/ajuste/grava', 'ItemController@gravaAjusteImagem');
Route::get('/novopreco', 'ItemController@sobePreco');
Route::get('/ajuste', function () {
    return view('produtos.fotos');
});

Route::get('/mapa', function () {
    return view('vendas.mapa');
});
Route::get('/malas', function () {
    return view('mostruarios.malas');
});
Route::get('/mapas', function () {
    return view('comercial.mapas');
});
Route::get('/session', function () {
    return view('sistema.session');
});
Route::get('/qrcode', function () {
    return view('produtos.qrcode');
});
Route::get('/ver_qrcode', function () {
    return view('produtos.qrcode_pagina');
});



Route::get('/pivot', function () {
    return view('produtos.pivot');
});
Route::post('/pivot', 'PivotController@carrega');


Route::get('/pivot_10', function () {
    return view('produtos.pivot_10');
});
Route::post('/pivot_10', 'PivotController@carrega');


Route::get('/pivot_site', function () {
    return view('produtos.pivot_site');
});
Route::post('/pivot_site', 'PivotController@carrega');


Route::get('/pivot2', function () {
    return view('produtos.pivot2');
});
Route::get('/foto/{referencia}', 'ItemController@consultaFoto');

Route::view('/login', 'login.index');
Route::get('/login/trocar/{id}', 'LoginController@trocarUsuario');
Route::post('/login', 'LoginController@authenticate');
Route::get('/logout', 'LoginController@logout');

Route::get('/reset', 'LoginController@alteraSenha');
Route::post('/reset', 'LoginController@reset');

Route::get('/', function () {
    //return view('layout.home');
    return view('layout.dashboard_representante');
});


Route::get('/home2', function () {
    //return view('layout.home');
    return view('home2');
});
Route::get('/erroscadastro', function () {
    return view('layout.erroscadastro');
});

Route::get('/search', 'ComercialController@buscaClientes');
Route::get('/dashboar2w', 'DashboardController@carrega');


Route::get('/dashboard', function () {
//return view('layout.dashboard_representante');
    return view('layout.dashboard_vendas');
});


Route::get('/dshome', function () {
//return view('layout.dashboard_representante');
    return view('dashboards.ds_home');
});



/** DS_estoques **/
Route::get('dsestoque', function () { return view('dashboards.estoque.lista'); });

Route::get('dsetq_agrup', function () {
    return view('dashboards.estoque.dsetq_agrup');
});


Route::get('dsetq_colecao', function () {
    return view('dashboards.estoque.dsetq_colecao');
});



/** DS_representante **/
Route::get('ds_rep', function () { return view('dashboards.representante.lista'); });
Route::get('rep_det', function () { return view('dashboards.representante.rep_det'); });


Route::get('/ds_rep/{agrupamento}/{modelo}/{item}', 'RepresController@ListaReps');





/** DS_kering **/
Route::get('kering', function () { return view('dashboards.kering.lista'); });

Route::get('dkdet_agrup', function () {
    return view('dashboards.kering.dkdet_agrup');
});

Route::get('dkdet_item', function () {
    return view('dashboards.kering.dkdet_item');
});	
	

Route::get('dkdet_comprasagrup', function () {
    return view('dashboards.kering.dkdet_comprasagrup');
});

Route::get('dkdet_orcagrup', function () {
    return view('dashboards.kering.dkdet_orcagrup');
});
	


Route::get('dkvds_item', function () {
    return view('dashboards.kering.dkvds_item');
});

Route::get('dkorc_item', function () {
    return view('dashboards.kering.dkorc_item');
});





	
Route::get('/dashboard/importacao', function () {
    return view('layout.dashboard_importacao');
});



Route::get('/dashboard/estoque', function () {
    return view('layout.dashboard_estoque');	
});


Route::get('/dsclientes2', function () {
    return view('dashboards.clientes.dashboard_clientes2');	
});


Route::get('/dsimport', function () {
    return view('dashboards.importacao.dashboard_importacao');	
});

Route::get('/dsimportrec', function () {
    return view('dashboards.importacao.dashboard_import_rec');	
});


Route::get('/dsimportdet', function () {
    return view('dashboards.importacao.dashboard_importacaodet');	
});

Route::get('/comercial', function () {
    return view('dashboards.comercial.dashboard_comercial');
	
});

Route::get('/dashboard_novo', function () {
    return view('layout.dashboard_vendas');
});



Route::get('/dcarteira', function () {
    return view('layout.dashboard_carteira');
	
});



Route::get('/dsclientes', function () {
    return view('dashboards.clientes.dashboard_clientes');	
});


Route::get('/clientes_grifes', function () {
    return view('dashboards.clientes.clientes_grifes');	
});

Route::get('/cliente_det', function () { return view('dashboards.clientes.clientes_det');	});
Route::get('/cliente_diasdet', function () { return view('dashboards.clientes.clientes_diasdet');	});
Route::get('/cliente_form', function () { return view('dashboards.clientes.clientes_form');	});
Route::get('/cliente_grifes', function () { return view('dashboards.clientes.clientes_grifes'); });
Route::get('/cliente_statusdet', function () { return view('dashboards.clientes.clientes_statusdet'); });
Route::get('/cliente_regiao', function () { return view('dashboards.clientes.clientes_regiao'); });
Route::get('/cliente_regiaodet', function () { return view('dashboards.clientes.clientes_regiaodet'); });
Route::get('/cliente_faixa', function () { return view('dashboards.clientes.clientes_faixa');	});

Route::post('/clientes_form/grava', 'ClienteController@gravaMotivoNaoVenda' );






/** apagar apos arrumar abaixo 
Route::get('/clientes_grifes', function () {return view('dashboards.clientes.clientes_grifes'); });
Route::get('/clientes_det', function () { return view('layout.clientes_det');	});
Route::get('/clientes_diasdet', function () { return view('layout.clientes_diasdet');	});
Route::get('/clientes_form', function () { return view('layout.clientes_form');	});
Route::post('/clientes_form/grava', 'ClienteController@gravaMotivoNaoVenda' );
apagar ate aqui apos arrumar **/


Route::get('/fichadivergencia', function () { return view('mostruarios.fichadivergencia');	});


Route::get('/itens_clientes', function () { return view('produtos.painel.itens_clientes');	});
Route::get('/itens_clientes_uf', function () { return view('produtos.painel.itens_clientes_uf');	});
Route::get('/itens_clientes_municipio', function () { return view('produtos.painel.itens_clientes_municipio');	});
Route::get('/itens_clientes_cliente', function () { return view('produtos.painel.itens_clientes_cliente');	});




Route::get('/devolucoes_det', function () { return view('layout.devolucoes_det'); });
Route::get('/bloqueados_det', function () { return view('layout.bloqueados_det'); });
Route::get('/mpdv_det', function () { return view('clientes.mpdv_det'); });


Route::get('/rep_grife', function () { return view('produtos.painel.rep_grife'); });
Route::get('/rep_grife_det', function () { return view('produtos.painel.rep_grife_det'); });

Route::get('/timeline_grife', function () { return view('produtos.painel.timeline_grife'); });
Route::get('/timeline_det', function () { return view('produtos.painel.timeline_det'); });
Route::get('/painel_agrup', function () { return view('produtos.painel.agrup'); });



Route::get('/comercial_det', function () { return view('dashboards.comercial.dashboard_comercial_det'); });
Route::get('/comercial_hist', function () { return view('dashboards.comercial.dashboard_comercial_hist'); });


Route::get('/comercial_rep', function () { return view('dashboards.comercial.dashboard_comercial_rep'); });

Route::get('/comercial_det_ped', function () { return view('dashboards.comercial.dashboard_comercial_det_ped'); });

Route::get('/comercial_vda', function () { return view('dashboards.comercial.dashboard_comercial_vda'); });

Route::get('/comercial_vda_det', function () { return view('dashboards.comercial.dashboard_comercial_vda_det'); });

Route::get('/comercial_ped', function () { return view('dashboards.comercial.dashboard_comercial_ped'); });

Route::get('/orcamento_dash', function () { return view('dashboards.comercial.dashboard_orcamento'); });

Route::get('/orcamento_det', function () { return view('dashboards.comercial.dashboard_orc_det'); });





Route::get('/orcamentos', 'OrcamentoController@listaOrcamentos');
Route::get('/vendas', 'VendaController@listaVendas');
Route::get('/vendas/{id}', 'VendaController@detalhesVenda');
Route::get('/pedidos/{id}', 'PedidoController@detalhesPedido');
Route::post('/pedidos/{data}/vincular', 'PedidoController@vinculaPedido');

Route::get('/notas', 'NotaFiscalController@listaNotasFiscais');
Route::get('/notas/{numero}', 'NotaFiscalController@detalhesNotaFiscal');

Route::get('/pedidos_det', 'PedidoController@listaPedidos_det');
Route::get('/trocas', 'TrocaController@listaTrocas');



Route::post('/pedidos/importar', 'PedidoController@importarPedido');

Route::get('/db_cliente', function () {
    return view('layout.db_cliente');
});

Route::resource('/address-book', 'AddressBookController');

Route::get('/clientes/vendas_det', 'ClienteController@vendasDet');
Route::get('/clientes/vendas_ped', 'ClienteController@vendasPed');

Route::get('/clientes/pedidos_det', 'ClienteController@pedidosDet');
Route::get('/clientes/pedidos_ped', 'ClienteController@pedidosPed');

Route::get('/clientes/notas_det', 'ClienteController@notasDet');
Route::get('/clientes/notas_ped', 'ClienteController@notasPed');


Route::get('/clientes/fidelizados', 'ClienteController@listaFidelizados');
Route::get('/clientes/fidelizados_cli', 'ClienteController@listaFidelizados_cli');
Route::post('/clientes/fidelizados_cli', 'ClienteController@gravaStatusGrife');

Route::get('/clientes/situacao', 'ClienteController@listaClientesSituacao');
Route::get('/clientes', 'ClienteController@listaClientes');
Route::get('/clientes/novo/{id}', 'ClienteController@detalhesNovoCliente');
Route::get('/clientes/novo', 'ClienteController@novoCliente');
Route::post('/clientes/anexos', 'ClienteController@inserirAnexo');
Route::post('/clientes/visitas', 'ClienteController@insereVisita');
Route::post('/clientes/grava', 'ClienteController@gravaCliente');
Route::post('/clientes/historicos/grava', 'ClienteController@gravaHistorico');
Route::get('/clientes/pdv/{id}', 'ClienteController@detalhesCliente');
Route::get('/clientes/subgrupo', 'ClienteController@detalhesGrupo');

Route::get('/det_subgrupo', function () { return view('clientes.det_subgrupo');	});


Route::get('/clientes/subgrupo/{id}', 'ClienteController@detalhesGrupo');



Route::get('/grupos/{grupo}', 'ClienteController@detalhesGrupo');


Route::get('/comissoes', 'ComissaoController@listaComissoes');
Route::get('/comissoes/{ano}/{periodo}', 'ComissaoController@detalhesComissao');
Route::post('/comissoes/nf/upload', 'ComissaoController@enviaNotaFiscal');
Route::get('/financeiro', 'TituloController@listaTitulos');

Route::get('/financeiro/{titulo}/{tipo}/{parcela}/boleto', 'BoletoController@geraBoleto');
Route::get('/financeiro/{titulo}', 'TituloController@detalhesTitulo');

Route::get('/financeiro_det', function () {
    return view('financeiro.financeiro_det');
	
});



Route::get('/eventos/{ano}/{periodo}', 'ComissaoController@detalhesComissao');

Route::get('/perfis', 'UsuarioPerfilController@listaPerfis');
Route::get('/perfis/novo', 'UsuarioPerfilController@novoPerfil');
Route::get('/perfis/{id}', 'UsuarioPerfilController@buscaPerfil');
Route::post('/perfis/grava', 'UsuarioPerfilController@gravaPerfil');

Route::get('/autorizacoes', 'AutorizacaoController@listaAutorizacoes');
Route::get('/autorizacoes/{id}/autoriza', 'AutorizacaoController@autoriza');

Route::get('/usuarios/movimentacoes/lista', 'RepresentanteController@listaMovimentacao');
Route::get('/usuarios/movimentacoes/nova', 'RepresentanteController@novaMovimentacao');
Route::get('/usuarios/movimentacoes/historico/{id}', 'RepresentanteController@historicoMovimentacao');
Route::post('/usuarios/movimentacoes/nova/inserir', 'RepresentanteController@inserirMovimentacao');
Route::post('/usuarios/movimentacoes/altera', 'RepresentanteController@alteraMovimentacao');
Route::get('/usuarios', 'UsuarioController@listaUsuarios');
Route::get('/usuarios/novo', 'UsuarioController@novoUsuario');
Route::get('/usuarios/{id}', 'UsuarioController@buscaUsuario');
Route::post('/usuarios/grava', 'UsuarioController@gravaUsuario');




Route::get('/produtos/falta/foto', 'ItemController@faltaFoto');
Route::post('/produtos/novo', 'ItemController@novoItem');
Route::get('/produtos/esgotados', 'EsgotadoController@listaEsgotados');
Route::get('/produtos/pesquisa_status', 'EsgotadoController@pesquisastatus');
Route::get('/produtos/gera_devolucao', 'EsgotadoController@gera_devolucao');
Route::post('/produtos/gera_devolucao', 'EsgotadoController@grava_devolucao');

Route::get('/mostruarios/devolucoes/', 'MostruarioController@listaDevolucoes');
Route::get('/mostruarios/devolucoes/nova', 'MostruarioController@novaDevolucao');
Route::post('/mostruarios/devolucoes/nova', 'MostruarioController@gravaNovaDevolucao');
Route::post('/mostruarios/devolucoes/enviar', 'MostruarioController@enviarDevolucao');
Route::post('/mostruarios/devolucoes/confere', 'MostruarioController@confereDevolucaoItem');
Route::get('/mostruarios/devolucoes/excel', 'MostruarioController@baixaListaExcel2');
Route::get('/mostruarios/devolucoes/{id}/excel', 'MostruarioController@baixaListaExcel');
Route::get('/mostruarios/devolucoes/{id_item}/excluir', 'MostruarioController@excluirItem');
Route::get('/mostruarios/devolucoes/checa', 'MostruarioController@checaItemDevolver');
Route::get('/mostruarios/atualizacao/lista', 'MostruarioController@listaAtualizacao');
Route::get('/mostruarios/atualizacao/inicia/{id_inventario}', 'MostruarioController@iniciaAtualizacao');


Route::get('/mostruarios/analiseinventario', 'MostruarioController@processaAnalise');
Route::get('/mostruarios/devolucoes/{id}', 'MostruarioController@detalhesDevolucao');
Route::get('/mostruarios/devolucoes/{id}/finalizar', 'MostruarioController@finalizaDevolucao');
Route::post('/mostruarios/devolucoes/{id}/confirmar', 'MostruarioController@confirmaDevolucao');

Route::get('/mostruarios', 'MostruarioController@listaPedidos');
Route::get('/mostruarios/reabre', 'MostruarioController@reabre');
Route::get('/mostruarios/exporta/geral', 'EsgotadoController@exportaGeralMala');
Route::get('/mostruarios/exporta/divergencia', 'EsgotadoController@exportaDivergentes');

Route::get('/mostruarios/inventarios/detalhes/{acao}/{id}', 'MostruarioController@detalhesInventario');
Route::get('/mostruarios/inventarios/{acao}', 'MostruarioController@listaInventario');
Route::get('/mostruarios/troca/{acao}', 'MostruarioController@listaInventario');
Route::get('/mostruarios/inventarios/novo/{acao}', 'MostruarioController@novoInventario');
Route::post('/mostruarios/inventarios/confere/{acao}', 'MostruarioController@confereInventario');
Route::get('/mostruarios/inventarios/confere/{acao}', 'MostruarioController@confereInventario');
Route::get('/mostruarios/inventarios/duplica/{acao}', 'MostruarioController@insereDuplicado');

Route::post('/mostruarios/inventarios/alteracao', 'MostruarioController@alteracaoInventario');
Route::get('/mostruarios/inventarios/altera/{id}/{id_inventario}/{acao}', 'MostruarioController@alteraInventario');
Route::post('/mostruarios/inventarios/alteracao', 'MostruarioController@alteracaoInventario');

Route::get('/mostruarios/inventarios/reabre/{id_inventario}', 'MostruarioController@reabreInvetario');


Route::get('/mostruarios/inventarios/{id}/exportadevolucao', 'MostruarioController@exportaDevolucao');
Route::get('/mostruarios/inventarios/{id}/exportainventario', 'MostruarioController@exportaInventario');
Route::get('/mostruarios/inventarios/{id}/excluir/{acao}', 'MostruarioController@excluiItemInvetario');

Route::get('/mostruarios/inventarios/consultaSituacao/{acao}', 'MostruarioController@consultaSituacao');
Route::post('/mostruarios/inventarios/importa/{acao}', 'MostruarioController@importaInventario');
Route::post('/mostruarios/inventarios/importaDevolucao/{acao}', 'MostruarioController@importaDevolucao');
Route::post('/mostruarios/inventarios/importaManter/{acao}', 'MostruarioController@importaManter');
Route::post('/mostruarios/inventarios/enviar', 'MostruarioController@enviaInventario');
Route::post('/mostruarios/inventarios/exportainventario', 'MostruarioController2@exportaInventario');


Route::get('/mostruarios/conferencias/', 'MostruarioConferenciaController@listaConferencias');
Route::get('/mostruarios/conferencias/nova', 'MostruarioConferenciaController@novaConferencia');
Route::get('/mostruarios/conferencias/{id}', 'MostruarioConferenciaController@detalhesConferencia');
Route::get('/mostruarios/conferencias/{id}/excel', 'MostruarioConferenciaController@geraListaExcel');
Route::get('/mostruarios/conferencias/{id}/finalizar', 'MostruarioConferenciaController@finalizaConferencia');
Route::get('/mostruarios/conferencias/{id}/{id_item}/excluir', 'MostruarioConferenciaController@excluirItem');
Route::post('/mostruarios/conferencias/confere', 'MostruarioConferenciaController@confereDevolucaoItem');
Route::post('/mostruarios/conferencias/{id}/confirmar', 'MostruarioConferenciaController@confirmaConferencia');

Route::get('/mostruarios', 'MostruarioController@listaPedidos');
Route::get('/mostruarios/exporta/geral', 'EsgotadoController@exportaGeralMala');
Route::get('/mostruarios/exporta/divergencia', 'EsgotadoController@exportaDivergentes');

Route::get('/mostruarios/atualizacao', 'MostruarioController@listaPedidomostruario');
Route::post('/mostruarios/atualizacao/selecionados', 'MostruarioController@confirmaPedidomostruario');
Route::get('/mostruarios/atualizacao/liberaAtulizacaolista', 'MostruarioController@liberaAtulizacaolista');
Route::get('/mostruarios/atualizacao/aberto', 'MostruarioController@pedidosSolicitados');
Route::post('/mostruarios/atualizacao/aberto/enviado', 'MostruarioController@pedidosAtendidos');
Route::get('/listapedidomost/{pedido}', 'MostruarioController@pedidosSM');
Route::get('/mostruarios/solicitacao/{rep}', 'MostruarioController@solicitacaoREP');
Route::post('/mostruarios/pedidos/libera', 'MostruarioController@liberaAtualizacaopainel');
Route::get('/mostruarios/rastreios', 'MostruarioController@listaRastreio');
Route::post('/mostruarios/rastreios/upload', 'MostruarioController@atualizarastreio');






Route::post('/produto/preco/atualiza', 'ItemController@alteraPreco');
Route::get('/produtos/status/processamentos', 'StatusProcessaController@verProcessamentos');
Route::post('/produtos/status/uploadarquivo', 'StatusProcessaController@uploadArquivo2');

Route::get('/produtos/status/processamentos/{processamento}', 'StatusProcessaController@detalhesProcessamento');

Route::get('/produtos/status/processamentos/{processamento}/edita', 'StatusProcessaController@editaProcessamento');

Route::get('/produtos/status/editastatus3', 'StatusProcessaController@alteraStatus');

Route::get('/produtos/status/processamentos/{processamento}/excluir', 'StatusProcessaController@excluiProcessamento');

Route::get('/produtos/status/processa', 'StatusProcessaController@processa');

Route::get('/produtos/status/processakering', 'StatusProcessaKeringController@atualizaprocessa');


Route::get('/produtos/processa/atualiza', 'StatusProcessaController@atualizaprocessa1');

Route::get('/enchant_broker_describe(broker)', 'StatusProcessaController@atualizastatusitens');

Route::post('/produtos/imagem/troca', 'ItemController@trocaImagem');
Route::get('/produtos/metas/', 'MetasController@Metas');

Route::get('/produtos/agregados', 'AgregadosController@listaAgregados');
Route::get('/produtos/agregados/{modelo}', 'AgregadosController@listaAgregadosItens');



Route::get('/produtos/grades', 'GradesController@listaGrades');
Route::get('/produtos/gradesitens/{modelo}', 'GradesController@listaGradesItens');
Route::get('/produtos/gradescolecoes/{modelo}', 'GradesController@listaGradesColecoes');
Route::get('/produtos/gradesmodelos/{modelo}', 'GradesController@listaGradesModelos');
Route::get('/produtos/gradescoldet/{modelo}', 'GradesController@listaGradesColdet');



Route::get('/catalogo_novo', function () {
    //return view('layout.home');
    return view('produtos.catalogos.index');
});

Route::get('/painel', 'PainelController@agrupamentos');
Route::get('/painel/favoritos', 'PainelController@favoritos');
Route::get('/painel/cet/{item}', 'PainelController@cet_aberto');
Route::get('/painel/favoritos/add', 'PainelController@addFavoritos');
Route::get('/painel/favoritos/del', 'PainelController@delFavoritos');
Route::get('/painel/favoritos/checa', 'PainelController@checaFavoritos');
Route::get('/painel/tabela', 'PainelController@tabela');
Route::get('/painel/search/', 'PainelController@search');
Route::get('/painel/grifes/', 'PainelController@marcas');
Route::get('/painel/campanhas/{item}', 'PainelController@verCampanhas');
Route::post('/painel/campanhas/{item}', 'PainelController@uploadFoto');
Route::get('/painel/midias/{item}/excluir', 'PainelController@exclirMidia');
Route::get('/painel/midias/{item}', 'PainelController@verMidias');
Route::post('/painel/midias/{item}', 'PainelController@uploadFotoMidia');
Route::get('/painel/imprimir/{modelo}', 'PainelController@imprimir');
Route::get('/painel/{agrupamento}', 'PainelController@modelos');
Route::get('/painel/estilo/{agrupamento}', 'PainelController@modelosEstilo');
Route::get('/painel/{agrupamento}/{modelo}', 'PainelController@itens');
Route::get('/painel/{agrupamento}/{modelo}/{item}', 'PainelController@item');

Route::post('/painel/{agrupamento}/{modelo}/{item}', 'PainelController@gravaHistorico');


Route::get('/exportasalesreport/{agrupamento}', 'PainelController@exportaSalesReport');
Route::get('/exportaprecosugerido/{agrupamento}', 'PainelController@exportaPrecosugerido');
Route::get('/exportaprecosugeridod/{agrupamento}', 'PainelController@exportaPrecosugeridod');

Route::get('/historico/{id}/deleta', 'ItemHistoricoController@deletaHistorico');


Route::get('/catalogo/excel/monta', 'CatalogoController@CatalogoExcel');
Route::post('/catalogo/excel/monta', 'CatalogoController@montaCatalogoExcel');

Route::get('/catalogo/excel', 'CatalogoController@exportaCatalogoExcel');
Route::get('/catalogo/pedido', 'CatalogoController@exportaCatalogoDisponivel');

Route::get('/catalogo/novo', 'CatalogoController@novoCatalogo');
Route::get('/catalogo/{codigo}/exclui', 'CatalogoController@excluiCatalogo');
Route::get('/catalogo/padrao/{tipo}', 'CatalogoController@catalogoPadrao');
Route::get('/catalogo/{codigo}', 'CatalogoController@verCatalogo');
Route::post('/catalogo/padrao/{tipo}/exporta', 'CatalogoController@exportaCatalogoPadrao');
Route::get('/catalogo/{codigo}/{id}/delItem', 'CatalogoController@delItem');
Route::get('/catalogo/{codigo}/salva', 'CatalogoController@gravaCatalogo');
Route::post('/catalogo/{codigo}/salva', 'CatalogoController@gravaCatalogo');
Route::post('/catalogo/{codigo}/importar', 'CatalogoController@importarItens');
Route::get('/catalogo/{codigo}/edita', 'CatalogoController@editaCatalogo');
Route::get('/catalogo/{codigo}/cancela', 'CatalogoController@cancelaCatalogo');
Route::get('/catalogo/{codigo}/pdf', 'CatalogoController@exportaCatalogo');
Route::get('/meus-catalogos', 'CatalogoController@meusCatalogos');
Route::post('/catalogo/novo', 'CatalogoController@montaCatalogo');


Route::get('/backorder', 'BackOrderController@listaBackOrder');
Route::post('/backorder/atende', 'BackOrderController@atendeOrcamento');
Route::get('/backorder/{cliente}', 'BackOrderController@detalhesCliente');
Route::get('/backorder/detalhes/{grife}', 'BackOrderController@detalhesGrife');
//Route::get('/backorder', 'BackOrderController@detalhesGrife');

Route::get('/comercial/frequencia/clientes', function() {
	return view('comercial.frequencia.cliente');
});
Route::get('/comercial/frequencia/representantes', function() {
	return view('comercial.frequencia.representante');
});
Route::get('/comercial/frequencia/supervisores', function() {
	return view('comercial.frequencia.supervisor');
});


Route::get('/compras', 'CompraController@listaCompras');
Route::get('/compras/nova', 'CompraController@novaCompra');

Route::post('/compras/{id}/edita', 'CompraController@atualiza');
Route::post('/compras/{id}/envia', 'CompraController@enviaPedido');
Route::post('/compras/{id}/insere', 'CompraController@insereItemPedido');
Route::post('/compras/{id}/atualiza', 'CompraController@atualizaCompraItem');
Route::get('/compras/{id}/imprimir', 'CompraController@imprimir');
Route::post('/compras/pedido/novo', 'CompraController@novoPedido');
Route::get('/compras/{id}', 'CompraController@detalhesCompra');
Route::post('/compras/pedido/importa', 'CompraController@importaPedido');

Route::post('/compras/{id}/atualiza_importa', 'CompraController@importaAtualiza');
Route::get('/compras/reports/enviaatrasos', 'CompraController@enviaatrasos');
Route::get('/compras/reports/diferencapedidos', 'CompraController@diferencapedidos');
Route::get('/compras/reports/mudancadataentrega', 'CompraController@mudancadataentrega');
Route::get('/compras/reports/faltacadastro', 'CompraController@faltacadastro');

Route::get('/compras/pedido/fornecedor', 'CompraController@fornecedor');

Route::get('/compras/pedido/modelo/novo/{idcompra}', 'CompraController@ModeloNovoInsere');
Route::post('/compras/pedido/modelo/novo/salva', 'CompraController@ModeloNovoInsereGrava');
Route::post('/compras/pedido/modelo/copiar', 'CompraController@ModeloNovoCopiar');
Route::post('/compras/pedidos/modelos/criacao/upload', 'CompraController@ModeloNovoUpload');
Route::post('/compras/pedidos/cores/criacao/upload', 'CompraController@ModeloNovoCoresUpload');
Route::get('/compras/pedido/modelo/{idmodelo}', 'CompraController@ModeloNovo');
Route::get('/compras/pedido/modelo/edita/{idmodelo}', 'CompraController@ModeloNovoEdita');
Route::post('/compras/pedido/modelo/edita/salva', 'CompraController@ModeloNovoEditaSalva');
Route::post('/compras/pedido/item/novo', 'CompraController@ItemNovoInsere');
Route::get('/compras/pedido/item/exclui/{id}', 'CompraController@ItemNovoExclui');
Route::post('/compras/pedido/item/edita/salva', 'CompraController@ItemNovoEditaSalva');
Route::get('/compras/pedido/item/edita/{id}', 'CompraController@ItemNovoEdita');
Route::get('/compras/pedido/{id_pedido}/exporta', 'CompraController@exportaPedido');
Route::post('/compras/pedidos/upload/{idmodelo}/{tipo}', 'CompraController@UploadArquivoModelo');
Route::get('/compras/pedido/agrupamentos', 'CompraController@PrePedidoAgrupamento');
Route::post('/compras/pedidos/historico', 'CompraController@ModeloNovoHistorico');
Route::get('/compras/pedido/modelo/transformar_pedido/{id_compra}', 'CompraController@TransformarEmPedido');






//Route::get('/compras/pedido/modelo/{modelo}', 'CompraController@Modelo');
//Route::get	('/compras/pedido/modelo/edita/salva', 'CompraController@ModeloNovoEditaSalva');



//Route::get('/compras/pedido/modelo/edita/{modelo}', 'CompraController@ModeloNovoEdita');
//

Route::get('/compras/oi/lista', 'CompraController@listaOI');
Route::get('/compras/oi/detalhes/{pedido}/{tipo}', 'CompraController@detalhesOI');
Route::get('/compras/oi/entrega/{pedido}/{tipo}', 'CompraController@entregaOI');


Route::get('/compras/invoice/detalhes/{invoices}', 'CompraController@detalhesInvoice');
//Route::get('/compras/invoice/detalhes/{invoice}', 'ImportacaoController@detalhesInvoice');
Route::get('/compras/invoice/deleta/{invoice}', 'ImportacaoController@deletaInvoice');
Route::post('/compras/invoice/importa', 'CompraController@importainvoice');
Route::get('/compras/invoice/lista', 'CompraController@listainvoices');

Route::get('/compras/entregas/exclui/{pedido}', 'CompraController@excluiEntregas');
Route::get('/compras/entregas/programacao', 'CompraController@programacaoEntrega');
Route::get('/compras/entregas/relatorio_invoice', 'CompraController@relatorioInvoice');
Route::get('/compras/entregas/exclui_invoice/{invoice}', 'CompraController@ExcluiInvoice');
Route::get('/compras/entregas/downalod_edita/{id_compra}', 'CompraController@DownloadEditaEntregas');
Route::post('/compras/entregas/upload_edita/{id_compra}', 'CompraController@UploadEditaEntregas');
Route::get('/compras/pedido/{id_pedido}/teste', 'CompraController@insereItem');
Route::post('/compras/arquivos/upload', 'CompraController@UploadArquivos');
Route::post('/compras/{id}', 'CompraController@acoes');


   

Route::post('produto/caracteristica/atualiza', 'CaracteristicaController@alteraCaracteristica');

Route::post('/integracao/upload', 'IntegracaoController@uploadArquivo');

Route::get('/integracao', 'IntegracaoController@listaIntegracoes');

Route::get('/carga', 'ItemController@importeAtualizacaoMassa');
Route::post('/produtos/atualizacao', 'ItemController@atualizacaoMassa');

Route::get('/integracao/producoes', 'IntegracaoController@listaProducoes');
Route::get('/integracao/producoes/{numero}/excluir', 'IntegracaoController@excluiProducoes');


Route::get('/integracao3', 'IntegracaoController@integraItens');
Route::get('/integracao2', 'IntegracaoController@con3sultaItens');
Route::get('/integracao/addressbook/atualiza', 'IntegracaoController@atualizaAddressBook');
Route::get('/integracao/tabelas/atualizacao', 'IntegracaoController@atualizacaoBase');
Route::get('/integracao/vendas_sint/atualiza', 'IntegracaoController@atualizavendas_sint');
Route::get('/integracao/itens/atualiza', 'IntegracaoController@atualizaItens');
Route::get('/integracao/saldos/atualiza', 'IntegracaoController@atualizasaldos');
Route::get('/integracao/orc_sint/atualiza', 'IntegracaoController@atualizaorc_sint');
Route::get('/integracao/caracteristica/atualiza', 'IntegracaoController@atualizaCaracteristicas');
Route::get('/integracao/processaamazon/atualiza', 'IntegracaoController@atualizaprocessaamazon');
Route::get('/integracao/representantes/atualiza', 'RepresentanteController@atualizaRepresentantes');
Route::get('/integracao/vendas_18/atualiza', 'IntegracaoController@atualizaVendas_18');
Route::get('/integracao/vendas_13meses/atualiza', 'IntegracaoController@atualizaVendas_13meses');
Route::get('/integracao/producoes/upload', 'IntegracaoController@atualizaProducoes');
Route::get('/integracao/producoes/sint', 'IntegracaoController@atualizaProducoessint');

Route::get('/xpto', 'ImportacaoController@listaImportacoes');
Route::post('/xpto/importa', 'ImportacaoController@importaArquivo');

Route::get('/jobs/{id}/executa', 'JobController@executa');
Route::get('/jobs/{id}/sql', 'JobController@executaSQL');
Route::get('/integracao/{id}', 'JobController@detalhes');

Route::get('/carteira', 'ClienteController@listaClientes'); 
Route::get('/carteira2', 'CarteiraController@listaCarteira2'); 

Route::get('/carteira/cart_detcli', 'CarteiraController@detalhesCliente'); 

Route::get('/carteira/ficha', 'CarteiraController@fichaCliente'); 
Route::get('/carteira/somamedia', 'CarteiraController@somaMedia'); 
Route::get('/carteira/cart_detcli', 'CarteiraController@cartDetcli'); 
Route::get('/carteira/fin_cli', 'CarteiraController@finCli'); 
Route::get('/carteira/fin_pdv', 'CarteiraController@finPdv'); 
Route::get('/carteira/ficha_det', 'CarteiraController@fichaDet'); 


Route::get('/concorrente/importa', 'ConcorrenteController@importaArquivo'); 

Route::get('/ecommerce', 'ECommerceController@listaClientes');
Route::get('/ecommerce/{id}', 'ECommerceController@detalhesCliente');

Route::get('/carteira/carteira', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('comercial.carteira.carteira');
});

Route::get('/carteira/detalhes', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('comercial.carteira.aberto');
});

Route::get('/carteira/det_grife', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('comercial.carteira.det_grife');
});

	

// PARA O SANDRO
// /get É O CAMINHO DA URL, EX (o que vem no centro)
Route::get('/arvore', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.arvore');
});

Route::get('/exemplo', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.exemplo');
});

Route::get('/vendas_sint', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.vendas_sint');
});
Route::get('/vendas_rep', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.vendas_rep');
});
Route::get('/estoque', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.estoque');
});

Route::get('/compras_det', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.compras_det');
});

Route::get('/modelos_colecao', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.modelos_colecao');
});

Route::get('/sugestoes', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.sugestoes');
});

Route::get('/sugestoes2', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.sugestoes2');
});

Route::get('/sazonalidades', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.sazonalidades');
});

Route::get('/elastic', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.elastic');
});

Route::get('/elastic-dirrep', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.exemplo.elastic-dirrep');
});

Route::get('/dashboard/db_cliente', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('layout.db_cliente');
});

Route::get('/topmodelos', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.painel.topmodelos');
});

Route::get('erroscadastrocapa', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.painel.erroscadastrocapa');
});

Route::get('erroscadastro', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.painel.erroscadastro');
});

Route::get('/job/atualizaVendasCML', 'JobController@atualizaVendasCML');


Route::get('/dashboard/carteira', 'DashboardController@carteira');
Route::get('/dashboard/vendas', 'DashboardController@vendas');
Route::get('/dashboard/indGrife', 'DashboardController@indGrife');
Route::get('/dashboard/indFrequencia', 'DashboardController@indFrequencia');
Route::get('/dashboard/frequencia', 'DashboardController@frequencia');
Route::get('/dashboard/grifeMensal', 'DashboardController@grifeMensal');
Route::get('/dashboard/orcamentosMensal', 'DashboardController@orcamentosMensal');

Route::get('/dashboard/exportaClientes', 'DashboardController@exportaClientes');
Route::get('/dashboard/exportaExcel', 'DashboardController@exportaExcel');

Route::get('/report/volumeestoque', 'ReportController@VolumeEstoque');


Route::get('/reportEstoque', 'ReportController@emailEstoque');

//Route::get('/reports', 'ReportsController@statustop');
//Route::get('/reports', function () {
//    return view('produtos.reports.reports');
//});
Route::get('/reports', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.reports.reports');
	});
Route::get('/edenilton', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.reports.reportsED');
	});
Route::get('/wellington', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.reports.reportsWE');
	});
Route::get('/repedido', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.reports.reports_repedido');
	});
Route::get('/repedidodetalhe', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('produtos.reports.reports_repedido_detalhe');
	});
Route::get('/inventario/dashboard', function() {
	// QUAL O ARQUIVO PRA EXIBIR
	return view('layout.dashboard_inventario');
	});
