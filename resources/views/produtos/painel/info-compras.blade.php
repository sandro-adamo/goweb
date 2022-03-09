   


@if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'vendas', 1)) 

@if(isset($catalogo->mediavenda))

<div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>  
              <td>
                <table class="table table-condensed table-bordered table2" style="text-align: center;">
                    <tr>
						@if(isset($modelo->fornecedor1) and $modelo->fornecedor1 ='KERING EYEWEAR SPA')
                      <td title="Média dos ultimos 2 meses"><i class="fa fa-bar-chart"></i></td>
                     
                        <td>{{number_format($catalogo->mediavenda)}}</td>
                     
                      </td>
                      <td title="Projeção de venda para 3 meses"><i class="fa fa-line-chart"></i>  </td>
                      
                      <td>{{($catalogo->mediavenda)*3}}</td>


                      
                      
						@if (($catalogo->totaletq)-($catalogo->mediavenda*3)+$catalogo->orcamentos_valido)<0)
																																 															<td title="Necessidade de compra para 3 meses Estoque- orçamento">
				<i style='color:red;' class="fa  fa-plus-square"></i></td>
							<td align="center" style='color:red;'>	{{($catalogo->totaletq)-(($catalogo->mediavenda*3)+$catalogo->orcamentos_valido)}}</td>
							@else
		<td title="Necessidade de compra para 3 meses Estoque- orçamento"><i class="fa  fa-plus-square"></i></td>
							<td align="center">	{{($catalogo->totaletq)-(($catalogo->mediavenda*3)+$catalogo->orcamentos_valido)}}	</td>				 
							 @endif
			

                   

                        
                      <td title="Estoque disponivel hoje estoque - orçamento"><i class="fa fa-cubes"></i></td>
                      <td>{{number_format((($catalogo->totaletq)-$catalogo->orcamentos_valido))}}</td>
                      
				
				@else

                         <td title="Projeção de venda para 6 meses"><i class="fa fa-line-chart"></i>  </td>
                      
                      <td>{{($catalogo->mediavenda)*6}}</td>
						@if($catalogo->totaletq-(($catalogo->mediavenda*6)+$catalogo->orcamentos_valido)<0)
	
                      <td title="Necessidade de compra para 6 meses Estoque- orçamento"><i style='color:red;' class="fa  fa-plus-square"></i></td>
                     
                      <td style='color:red;'>{{$catalogo->totaletq-(($catalogo->mediavenda*6)+$catalogo->orcamentos_valido)}}
                      </td>

                   	@else
					 <td title="Necessidade de compra para 6 meses Estoque- orçamento"><i class="fa  fa-plus-square"></i></td>
                     
                      <td >{{$catalogo->totaletq-(($catalogo->mediavenda*6)+$catalogo->orcamentos_valido)}}
                      </td>
					@endif

                        
                      <td title="Estoque disponivel hoje estoque - orçamento"><i class="fa fa-cubes"></i></td>
                      <td>{{number_format((($catalogo->totaletq)-$catalogo->orcamentos_valido))}}</td>
             
				
				@endif
                    </tr>
                </table>
        </td>
    </tr>
</table>
</div>
</div>
@endif
@endif