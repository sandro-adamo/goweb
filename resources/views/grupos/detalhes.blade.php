@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Detalhes do @if ($tipo == 'pdv') Cliente @else Grupo @endif
@append 



@php 
    $query1 = \DB::select("
	select * from addressbook where cliente = '$cliente->cliente'	"); 
@endphp




@section('conteudo')
{{-- <div class="row">

          <div class="col-md-3" align="center">
            <i class="fa fa-user fa-2x"></i>
          </div>
          <div class="col-md-9">
            <h3 class="box-title">{{$cliente->razao}}</h3>
          </div>
</div> --}}
<div class="row">
  <div class="col-md-12 col-lg-9">

    @if ($cliente->tipo == 'CI')
      <div class="callout callout-warning">
        <b>Atenção:</b> Cliente Inativo

        <a href="" class="pull-right"> Solicitar atualização</a>
      </div>
    @endif
	  
	  {{-- 
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-group"></i> Dados do Grupo/Sub-Grupo</h3>
      </div>

      <div class="box-body">

        <div class="form-group">
          <label class="col-md-2 control-label">Grupo</label>
          <div class="col-md-4">
            <a href="/clientes/{{$cliente->grupo}}">{{$cliente->grupo}}</a>
          </div>
          <label class="col-md-2 control-label">Sub-Grupo</label>
          <div class="col-md-4">
            <a href="/clientes/{{$cliente->subgrupo}}">{{$cliente->subgrupo}}</a></a>
          </div>
		
        </div>
        
      </div>
    </div>  
 --}}
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-building"></i> Dados do Cliente</h3>
      </div>

      <div class="box-body">

        <div class="row">
          <label class="col-md-2">Grupo</label>
          <div class="col-md-4">
            <a href="/clientes/{{$cliente->grupo}}">{{$cliente->grupo}}</a>
          </div>
          <label class="col-md-2">Sub-Grupo</label>
          <div class="col-md-4">
            <a href="/clientes/{{$cliente->subgrupo}}">{{$cliente->subgrupo}}</a></a>
          </div>
        </div>


        @if ($tipo == 'pdv')

        <div class="row">
          <div class="col-md-12">
            <hr/>
          </div>
        </div>

        <div class="row">
          <label class="col-xs-2 col-md-2">Código</label>
          <div class="col-xs-2 col-md-2">
            {{$cliente->id}}
          </div>
          <label class="col-xs-2 col-md-2 control-label">CNPJ</label>
          <div class="col-xs-2 col-md-2">
            {{$cliente->cnpj}}
          </div>
        </div>
        <div class="row">
          <label class="col-xs-2 col-md-2">Razão</label>
          <div class="col-xs-9 col-md-9">
            {{$cliente->razao}}
          </div>
        </div>
        <div class="row">
          <label class="col-xs-2 col-md-2">Fantasia</label>
          <div class="col-xs-9 col-md-2">
            {{$cliente->fantasia}}
          </div>
        </div>

        <div class="row">
          <label class="col-xs-2 col-md-2">Endereco</label>
          <div class="col-xs-9 col-md-9">
            {{$cliente->endereco}}
          </div>
        </div>
        <div class="row">

          <label class="col-xs-2 col-md-2">Bairro</label>
          <div class="col-xs-3 col-md-3">
            {{$cliente->bairro}}
          </div>

          <label class="col-xs-2 col-md-2">Municipio</label>
          <div class="col-xs-3 col-md-3">
            {{$cliente->municipio}}
          </div>

          <label class="col-xs-1 col-md-1">UF</label>
          <div class="col-xs-1 col-md-1">
            {{$cliente->uf}}
          </div>

        </div>
        <div class="row">

          <label class="col-md-2">contato</label>
          <div class="col-md-2">
            {{$cliente->dd1}}
          </div>

          <label class="col-md-1">tel1</label>
          <div class="col-md-2">
            {{$cliente->tel1}}
          </div>

          <label class="col-md-1">tel2</label>
          <div class="col-md-2">
            {{$cliente->tel2}}
          </div>

        </div>
        <div class="row">

          <label class="col-md-2">email1</label>
          <div class="col-md-4">
            {{$cliente->email1}}
          </div>


          <label class="col-md-2">email2</label>
          <div class="col-md-4">
            {{$cliente->email2}}
          </div>
        </div>
          @endif  
      </div>
    </div>  

    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Timeline</a></li>
        <li><a href="#tab_2" data-toggle="tab">PDV's</a></li>
        <li><a href="#tab_3" data-toggle="tab">Comercial</a></li>
        <li><a href="#tab_4" data-toggle="tab">Orçamentos</a></li>
        <li><a href="#tab_5" data-toggle="tab">Vendas</a></li>
        <li><a href="#tab_6" data-toggle="tab">Devoluções</a></li>
        <li><a href="#tab_7" data-toggle="tab">Trocas</a></li>
 <!--     <li><a href="#tab_5" data-toggle="tab">Devoluções</a></li> 
   <li><a href="#tab_6" data-toggle="tab">Pesquisa</a></li>   -->  
        <li><a href="#tab_8" data-toggle="tab">Financeiro</a></li>
{{--         
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Dropdown <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action1</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
            <li role="presentation" class="divider"></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
          </ul>
        </li> --}}
        <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
      </ul>
		
		
	
		
		
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">


          <form action="/clientes/historicos/grava" method="post">
            <input type="hidden" name="id_cliente" value="{{$cliente->id}}" >
            @csrf
            <div class="row">
              <div class="col-md-12">
                <div class="box box-widget">
                  <div class="box-body">
                    <textarea class="form-control" name="historico" rows="5" placeholder="Escreva aqui um histórico para este cliente"></textarea>
                  </div>
                  <div class="box-footer">
                    <div class="row">
                      <div class="col-md-8">
                        <input type="file" class="form-control">
                      </div>
                      <div class="col-md-4">
                        <button type="submit" class="btn btn-default pull-right"><i class="fa fa-send"></i> Enviar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>

          <!-- row -->
          <div class="row">
            <div class="col-md-12">
              <!-- The time line -->
              <ul class="timeline">
                <!-- timeline time label -->
      {{--           <li class="time-label">
                      <span class="bg-blue">
                        10 Feb. 2014
                      </span>
                </li> --}}
                <!-- /.timeline-label -->
                <!-- timeline item -->

                @foreach ($historicos as $historico)
                <li>
                  <i class="fa fa-envelope bg-blue"></i>

                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                    <h3 class="timeline-header"><a href="#">{{$historico->usuario->nome}}</a> registrou um histórico</h3>

                    <div class="timeline-body">
                      {{$historico->historico}}
                    </div>
                    <div class="timeline-footer">

                    </div>
                  </div>
                </li>
                @endforeach
      {{-- 
                <li class="time-label">
                      <span class="bg-blue">
                        3 Jan. 2014
                      </span>
                </li> --}}

                <li>
                  <i class="fa fa-clock-o bg-gray"></i>
                </li>
              </ul>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

        </div>	


            
        <div class="tab-pane" id="tab_2">


        
        </div>
      

        <div class="tab-pane" id="tab_3">

  			<div class="row">
          <div class="col-md-12">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Status</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Marca</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Representante</th>
                <th colspan="2" style="text-align: center;">2020</th>
                <th colspan="2" style="text-align: center;">2019</th>
                <th colspan="2" style="text-align: center;">2018</th>
              </tr>
              <tr>

                <th style="text-align: center;">Peças</th>
                <th style="text-align: center;">Valor</th>
                <th style="text-align: center;">Peças</th>
                <th style="text-align: center;">Valor</th>
                <th style="text-align: center;">Peças</th>
                <th style="text-align: center;">Valor</th>
              </tr>
            </thead>
            <tbody>

              @php
                $total_qtde_2020 = 0;
                $total_qtde_2019 = 0;
                $total_qtde_2018 = 0;

                $total_valor_2020 = 0;
                $total_valor_2019 = 0;
                $total_valor_2018 = 0;
              @endphp

              @foreach ($grifes as $grife)
                @php
                  $total_qtde_2020 += $grife->qtde_2020;
                  $total_qtde_2019 += $grife->qtde_2019;
                  $total_qtde_2018 += $grife->qtde_2018;

                  $total_valor_2020 += $grife->valor_2020;
                  $total_valor_2019 += $grife->valor_2019;
                  $total_valor_2018 += $grife->valor_2018;

                @endphp
                <tr>
                  <td width="15%" valign="middle" align="center">
                    @switch ($grife->situacao)
                      @case('novo')
                        <span class="text-blue text-bold">NOVO</span>
                        @break
                      @case('fidelizado')
                        <span class="text-green text-bold">FIDELIZADO</span>
                        @break
                      @case('a_recuperar')
                        <span class="text-orange text-bold">A RECUPERAR</span>
                        @break
                      @case('nao_fidelizado')
                        <span class="text-red text-bold">NÃO FIDELIZADO</span>
                        @break
                      @default
                        {{$grife->situacao}}
                    @endswitch


                  </td>
                  <td width="15%" align="center"><img src="/img/marcas/{{$grife->grife}}.png" class="img-responsive" width="120px"></td>
                  <td width="25%" valign="middle" align="center">{{$grife->rep}}</td>
                  <td width="5%" valign="middle" align="center">{{$grife->qtde_2020}}</td>
                  <td width="10%" valign="middle" align="right">{{number_format($grife->valor_2020,2,',','.')}}</td>
                  <td width="5%" valign="middle" align="center">{{$grife->qtde_2019}}</td>
                  <td width="10%" valign="middle" align="right">{{number_format($grife->valor_2019,2,',','.')}}</td>
                  <td width="5%" valign="middle" align="center">{{$grife->qtde_2018}}</td>
                  <td width="10%" valign="middle" align="right">{{number_format($grife->valor_2018,2,',','.')}}</td>
                </tr>


              @endforeach

            </tbody>
            <tfoot>
              <tr class="text-bold">
                <td colspan="3" align="right">TOTAL</td>
                <td align="center">{{$total_qtde_2020}}</td>
                <td align="right">{{number_format($total_valor_2020,2,',','.')}}</td>
                <td align="center">{{$total_qtde_2019}}</td>
                <td align="right">{{number_format($total_valor_2019,2,',','.')}}</td>
                <td align="center">{{$total_qtde_2018}}</td>
                <td align="right">{{number_format($total_valor_2018,2,',','.')}}</td>
              </tr>
            </tfoot>
          </table>
          </div>

			</div>

				
		</div>
		  
        <div class="tab-pane" id="tab_4">
          Lorem Ipsum is simply dummy text of the printing and typesetting industry.
          Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
          when an unknown printer took a galley of type and scrambled it to make a type specimen book.
          It has survived not only five centuries, but also the leap into electronic typesetting,
          remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
          sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
          like Aldus PageMaker including versions of Lorem Ipsum.
        </div>
      <div class="tab-pane" id="tab_5">
		<table class="table table-bordered tabela">

            <thead>
              <tr>
                <th>Código</th>
                <th>Fantasia</th>
                <th>UF/Cidade</th>
        				<th>valor_17</th> 
        				<th>valor_18</th> 
                <th>valor_19</th>
                <th>valor_20</th>
               
              </tr>
            </thead>
            <tbody>
              @php
                $total2017 = 0;
                $total2018 = 0;
                $total2019 = 0;
                $total2020 = 0;

              @endphp

              @foreach ($lojas as $loja)
                @php
                  $total2017 += $loja->valor_17;
                  $total2018 += $loja->valor_18;
                  $total2019 += $loja->valor_19;
                  $total2020 += $loja->valor_20;

                @endphp

                <tr>
                  <td align="center"><a href="/clientes/{{$loja->pdv}}">{{$loja->pdv}}</a></td>
                  <td>{{$loja->fantasia}}</td>
                  <td>{{$loja->uf}} - {{$loja->municipio}}</td>
      			      <td align="right">{{number_format($loja->valor_17,2,',','.')}}</td>	
      			      <td align="right">{{number_format($loja->valor_18,2,',','.')}}</td>	
                  <td align="right">{{number_format($loja->valor_19,2,',','.')}}</td>	
                  <td align="right">{{number_format($loja->valor_20,2,',','.')}}</td>	
                 
                </tr>                
              @endforeach
            </tbody>
            <tfoot>

                <tr>
                  <td colspan="3"></td>
                  <td style="text-align: right">{{number_format($total2017,2,',','.')}}</td>  
                  <td style="text-align: right">{{number_format($total2018,2,',','.')}}</td>  
                  <td style="text-align: right">{{number_format($total2019,2,',','.')}}</td>  
                  <td style="text-align: right">{{number_format($total2020,2,',','.')}}</td>  
                </tr>  

            </tfoot>
            
          </table>
        </div>
        <div class="tab-pane" id="tab_6">
          Lorem Ipsum is simply dummy text of the printing and typesetting industry.
          Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
          when an unknown printer took a galley of type and scrambled it to make a type specimen book.
          It has survived not only five centuries, but also the leap into electronic typesetting,
          remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
          sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
          like Aldus PageMaker including versions of Lorem Ipsum.
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_7">

          <div class="row">

            <div class="col-md-6">

              <table class="table table-condensed table-bordered">
              <tr>                
                <th width="65%">Solicitações de Troca - Qtde</th>
                <td align="right">{{number_format($trocas[0]->qtde, 0,',','.')}}</td>
              </tr>
              <tr>                
                <th width="65%">Solicitações de Troca - Valor</th>
                <td align="right">{{number_format($trocas[0]->valor_solic, 2,',','.')}}</td>
              </tr>
              </table>

              <h4 class="box-title text-bold"><i class="fa fa-warning text-orange"></i> Pendências</h4>
              <table class="table table-condensed table-bordered">
             <tr>                
                <td> Pendencias Interacao</td>
				<td align="right">@if (isset($trocasdet[0]->qtde_pend_interacao)) {{number_format($trocasdet[0]->qtde_pend_interacao, 0,',','.')}} @endif</td>  
                <td align="right">@if (isset($trocasdet[0]->valor_pend_interacao)) {{number_format($trocasdet[0]->valor_pend_interacao, 2,',','.')}} @endif</td>
              </tr>
              <tr>                
                <td> Pendências Fiscais</td>
                <td align="right">0</td>
              </tr>
              <tr>                
                <td> Devoluções Pendentes</td>
				<td align="right">@if (isset($trocasdet[0]->qtde_pend_devolucao)) {{number_format($trocasdet[0]->qtde_pend_devolucao, 0,',','.')}} @endif</td>  
                <td align="right">@if (isset($trocasdet[0]->valor_pend_devolucao)) {{number_format($trocasdet[0]->valor_pend_devolucao, 2,',','.')}} @endif</td>
              </tr>

              </table>
            </div>


            <div class="col-md-6">

              <table class="table table-condensed table-bordered">
              <tr>                
                <th width="65%">% qtde Vendas x Trocas</th>
                <td align="right">0</td>
              </tr>
              <tr>                
                <th width="65%">% vlr Vendas x Trocas</th>
                <td align="right">0</td>
              </tr>
              </table>
                            
              <h4 class="box-title text-bold"><i class="fa fa-warning text-orange"></i> Cobrança SAC</h4>
              <table class="table table-condensed table-bordered">
              <tr>                
                <td> Boleto em aberto</td>
                <td align="right">0</td>
              </tr>
              <tr>                
                <td> Boleto troca pago</td>
                <td align="right">0</td>
              </tr>
              <tr>                
                <td> Boleto cancelado</td>
                <td align="right">0</td>
              </tr>

              </table>


            </div>

          </div>
        </div>


        <div class="tab-pane" id="tab_8">


          <div class="row">
            <div class="col-md-5">  

              <table class="table table-condensed table-bordered">
              <tr>                
                <th width="65%">Limite de Crédito do Subgrupo</th>
                <td align="right">{{number_format($financeiro[0]->limite, 2,',','.')}}</td>
              </tr>
              </table>

              <table class="table table-condensed table-bordered">
              <tr>                
                <th width="65%">Futuro</th>
                <td align="right">{{number_format($financeiro[0]->Futuro, 2,',','.')}}</td>
              </tr>
              <tr>                
                <th>Atual</th>
                <td align="right">{{number_format($financeiro[0]->Atual, 2,',','.')}}</td>
             
			</tr>
				 
              <tr>                
                <th> Vencidos</th>
                <td align="right"></td>
              </tr>
				  
				  
              <tr>                
                <th>&nbsp 1 - 30</th>
                <td align="right">{{number_format($financeiro[0]->venc_1_30, 2,',','.')}}</td>
              </tr>
              <tr>                
                <th>&nbsp 30 - 60</th>
                <td align="right">{{number_format($financeiro[0]->venc_31_60, 2,',','.')}}</td>
              </tr>
              <tr>                
                <th>&nbsp 61 - 90</th>
                <td align="right">{{number_format($financeiro[0]->venc_61_90, 2,',','.')}}</td>
              </tr>
              <tr>                
                <th>&nbsp 91 - 120</th>
                <td align="right">{{number_format($financeiro[0]->venc_91_120, 2,',','.')}}</td>
              </tr>
              <tr>                
                <th>&nbsp 121 - 150</th>
                <td align="right">{{number_format($financeiro[0]->venc_121_150, 2,',','.')}}</td>
              </tr>
              <tr>                
                <th>&nbsp 151 - 999</th>
                <td align="right">{{number_format($financeiro[0]->venc_151_999, 2,',','.')}}</td>
              </tr>
              <tr>                
                <th>&nbsp Acima de 999</th>
                <td align="right">{{number_format($financeiro[0]->venc_999, 2,',','.')}}</td>
              </tr>
				  
				    <tr>                
                <th>  Vencido Total</th>
                <td align="right">{{number_format($financeiro[0]->vencidott, 2,',','.')}}</td>
              </tr>
				  
              </table>

            </div>

            <div class="col-md-7">
              <div class="table-responsive">

              <table class="table table-bordered tabela">
                <thead>
                  <tr>
					<th>Codigo</th>
                    <th>Status</th>
                    <th>Emissão</th>
                    <th>Nota Fiscal</th>
                    <th>Parcela</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                  </tr>                
                </thead>
                <tbody>
                @foreach ($financeiro_analit as $titulo)
                    <tr>
                      <td align="center">@if (isset($titulo->cod_cli)) {{$titulo->cod_cli}} @endif</td>
					            <td align="center">{{$titulo->status_parcela}}</td>
                      <td align="center">{{$titulo->dt_emissao_nf}}</td>
                      <td align="center">{{$titulo->numero_nf}}</td>
                      <td align="center">{{$titulo->parcela}}</td>
                      <td align="center">{{$titulo->dt_vencimento}}</td>
                      <td align="right">{{number_format($titulo->valor_em_aberto,2,',', '.')}}</td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            </div>

          </div>
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->



  </div>


  <div class="col-md-12 col-lg-3">
    <form action="" method="" class="form-horizontal">
    <div class="box box-widget hidden-xs hidden-sm hidden-md">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-gears"></i> Controle</h3>
      </div>

      <div class="box-body">
        
        <div class="form-group">
          <label class="col-md-3 control-label">Código</label>
          <div class="col-md-8">
            <input type="text" disabled="" name="id_cliente" value="{{$cliente->id}}" class="form-control">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-3 control-label">Status</label>
          <div class="col-md-8">
            <select name="status" class="form-control">
              <option value=""></option>
              <option>Não atende</option>
              <option>Inativo</option>
              <option>Não existe</option>
              <option>OK</option>
              <option>Problema no sistema</option>
              <option>Telefone não existe</option>
            </select>
          </div>
        </div>

      </div>
    </div>  
    </form>

    <form action="/clientes/anexos" method="post" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id_tabela" value="{{$cliente->id}}">
      <input type="hidden" name="tabela" value="addressbook">
    <div class="box box-widget hidden-xs hidden-sm hidden-md">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-image"></i> Foto</h3>
      </div>

      <div class="box-body" align="center">

        @php
          $fotos = \App\Anexo::where('tabela', 'addressbook')->where('id_tabela', $cliente->id )->where('tipo', 'Foto')->orderBy('id', 'desc')->take(1)->get();
        @endphp

        @if ($fotos && count($fotos) > 0) 
          @foreach ($fotos as $foto)

            <img src="/storage/{{$foto->caminho}}" class="img-responsive">

          @endforeach
        @else


        @endif
        <input type="file" name="foto" class="form-control">
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-flat btn-default pull-right"><i class="fa fa-upload"></i> Upload</button>
      </div>
    </div> 
    </form>

    @if ($tipo == 'pdv')
    <div class="box box-widget hidden-xs hidden-sm hidden-md">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-building"></i> Outras Lojas</h3>
      </div>

      <div class="box-body">
        @foreach ($lojas as $loja)
          @if ($loja->pdv <> $cliente->id)
            <a href="/clientes/{{$loja->pdv}}">

                  <table class="table-bordered table-hover table" style="margin: 0;">
                    <tr>
                      <td>

                          <div class="row">
                            <div class="col-md-2" align="center" ><center><i class="fa fa-building text-green fa-2x"></i></center></div>
                            <div class="col-md-10" style="padding-left: 0;"> 
                             {{$loja->pdv}}<br>{{$loja->razao}}
                            </div>
                          </div>
                      </td>
                    </tr>
                  </table>

            </a>
          @else
            <table class="table-bordered table" style="margin: 0;">
              <tr>
                <td>{{$loja->pdv}}<br>{{$loja->razao}}</td>
              </tr>
            </table>
          @endif
        @endforeach

      </div>
    </div> 

  </div>
  @endif

</div>


<div class="modal fade" id="modalAtualizaCadastro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Atualização Cadastral</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </div>
</div>


<form action="/clientes/visitas" method="post" class="form-horizontal">
  @csrf
<div class="modal fade" id="modalSolicitaVisita" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Solicitação de Visita </h4>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <label class="col-md-2 control-label">Cliente</label>
          <div class="col-md-9">
            <input type="hidden" name="id_cliente" class="form-control" value="{{$cliente->id}}" readonly="">
            <input type="text" name="cliente" class="form-control" value="{{$cliente->razao}}" readonly="">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Representante</label>
          <div class="col-md-9">
            <input type="text" name="rep" id="rep" class="form-control" readonly="">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Grife</label>
          <div class="col-md-9">
            <input type="text" name="grife" id="grife" class="form-control" readonly="">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Motivo</label>
          <div class="col-md-9">
            <textarea name="motivo" class="form-control"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </div>
  </div>
</div>
</form>
@stop