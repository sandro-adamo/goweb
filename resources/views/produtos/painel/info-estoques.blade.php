@if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'estoques', 1))        
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"  data-toggle="tooltip" data-placement="top" title="Estoque Brasil"></i></td>
                            <td> 
								@if (isset($catalogo->secundario)) 
    <a href="/estoque?secundario={{$catalogo->secundario}}">{{$catalogo->brasil}}<a/>

								
									@elseif (empty($catalogo->secundario))
								{{$catalogo->brasil}}
		@endif
									</td>
                        </tr>
                    </table>

                </td>
                    @if (isset($catalogo->total)) 

                 <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                         <div >
                            <td align="center"><img src="/img/to.png" width="15"  data-toggle="tooltip" data-placement="top" title="Saldo Parte Passivel/ Não Passivel"></i></td>
                            <td> 
                                
    <a href="" data-toggle="modal" data-target="#modalpecaspassiveis">
        @if(isset($catalogo->saldo_industria))
        {{$catalogo->saldo_industria}}({{$catalogo->saldo_passivel}}/{{$catalogo->pecas_sem_complemento}})<a/>
        @endif
                                
                                    
                                    </td>
                        </tr>

</div>
      

    </div>


                    </table>

                </td>
               @elseif (empty($catalogo->total))
                                
        @endif


                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"  data-toggle="tooltip" data-placement="top" title="Trânsito"></i></td>
                            <td>

                                @if (isset($catalogo->secundario)) 
    <a href="/painel/cet/{{$catalogo->secundario}}">{{$catalogo->cet}}<a/>

                                
                                    @elseif (empty($catalogo->secundario))
                                {{$catalogo->cet}}
        @endif
                            </td>
                        </tr>
                    </table>
                </td>
               
               
               
				
            </tr>
            <tr>
                 <td>
                    <table class="table table-condensed table-bordered table1" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"  data-toggle="tooltip" data-placement="top" title="Estoque em Fornecedores"></i></td>
                            <td>{{$catalogo->etq}}</td>
                        </tr>
                    </table>
                </td>
             <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"  data-toggle="tooltip" data-placement="top" title="Produção"></i></td>
                            <td>{{$catalogo->cep}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-wrench text-orange"  data-toggle="tooltip" data-placement="top" title="Manutenção"></i></td>
                            <td>{{number_format($catalogo->saldo_manutencao,0)}}</td>
                        </tr>
                    </table>
                </td>
              <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Total</b></td>
                            <td>{{number_format($catalogo->totaletq)}}</td>
                        </tr>
                    </table>
                </td> 


            </tr>
            <td >
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                             
                            <td><i class="fa  fa-suitcase text-navy blue"  data-toggle="tooltip" data-placement="top" title="Mostruário"></i></td>
                            <td>{{number_format($catalogo->mostruarios,0)}}</td>
                        </tr>

                    </table>

                </td>

<td >
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr >
                            <td  ><i class="fa  fa-exchange text-red"  data-toggle="tooltip" data-placement="top" title="Trocas"></i></td>
							
                            <td>{{number_format($catalogo->trocas,0)}} </td>
							
                        </tr>
                    </table>
                </td>

<td >
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr >
                            
							@if (isset($catalogo->trocas) && $catalogo->trocas > 0 && $catalogo->vendas > 0)
                            <td> {{number_format($catalogo->trocas/$catalogo->vendas,2)}}%</td>
							@else
							<td> / 0%</td>
							@endif
                        </tr>
                    </table>
                </td>



        </table>


    </div>
</div>
@endif