@if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'vendas', 1))        
<div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-shopping-cart text-green"  data-toggle="tooltip" data-placement="top" title="Venda 180D/ Total "></i></td>
                            
                            <td>
                              @if ( \Auth::user()->admin == 1  or  \Auth::user()->id_perfil == 11 
								or  \Auth::user()->id_perfil == 2 or \auth::user()->id_perfil ==25)
                                <a href="/vendas_sint?modelo={{$catalogo->modelo}}">{{number_format($catalogo->a_180dd)}}/{{number_format($catalogo->vendas)}}</a>
                              @else 
                                {{number_format($catalogo->a_180dd)}}/{{number_format($catalogo->vendas)}}
                              @endif 
                            </td>
                            
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-bar-chart text-blue" data-toggle="tooltip" data-placement="top" title="Média de Venda"></i></td>
                            <td>{{number_format($catalogo->mediavenda)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
@php
	if ($catalogo->fornecedor = 'kering eyewear usa inc' ) {
		$mesesforn = 3;
	} else {
		$mesesforn = 6;
	}
@endphp							
                            <td><i class="fa fa-heartbeat text-red" data-toggle="tooltip" data-placement="top" title="Duração em Meses"></i></td>
							@if (isset($catalogo->mediavenda) && ($catalogo->mediavenda)=='0')
                            <td>{{number_format(0)}}</td>
							@else
							<td>{{number_format((($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)-$catalogo->orcamentos_valido)/($catalogo->mediavenda))}} 

                               
							</td>
                            @endif
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple" data-toggle="tooltip" data-placement="top" title="Orçamento"></i></td>
                            <td><a title="Orçamento total">{{number_format($catalogo->orcamentos)}}</a>/<a title="Orçamento válido">{{number_format($catalogo->orcamentos_valido)}}</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
@endif