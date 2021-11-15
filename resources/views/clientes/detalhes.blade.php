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
            <hr style="padding: 0; margin: 3px;"/>
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
          <div class="col-xs-9 col-md-9">
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
      <div class="box-footer">
          <div class="row">
            <div class="col-sm-3 col-xs-6">
              <div class="description-block border-right">
                <span class="description-text">Cadastro</span>
                <span class="description-percentage text-green"></span>
                <h5 class="description-header">@if (trim($cliente->financeiro) == 'CI') <span class="text-red text-bold">Inativo</span> @else <span class="text-green text-bold">Ativo</span>  @endif</h5>
                
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-xs-6">
              <div class="description-block border-right">
                <span class="description-text">Comercial</span>
                <span class="description-percentage text-yellow"></span>
                <h5 class="description-header">
                   
                  @switch($cliente->situacao)
                    @case('a_recuperar')
                      <span class="text-orange text-bold">Recuperar</span>
                      @break
                    @case('fidelizado')
                      <span class="text-green text-bold">Fidelizado</span>
                      @break
                    @case('nao_fidelizado')
                      <span class="text-red text-bold">Não Fidelizado</span>
                      @break
                    @case('novo')
                      <span class="text-blue text-bold">Novo</span>
                      @break    
                    @default
                      <span class="text-blue text-bold"></span>
                  @endswitch                                                                         
                </h5>
               
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-xs-6">
              <div class="description-block border-right">
                 <span class="description-text">Finaceiro</span>
                <span class="description-percentage text-green"></span>

                <h5 class="description-header">@if (trim($cliente->financeiro) == 'IN') <span class="text-red text-bold">Inadimplente</span> @else <span class="text-green text-bold">Adimplente</span>  @endif</h5>
               
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-3 col-xs-6">
              <div class="description-block">
                 <span class="description-text">Juridico</span>
                <span class="description-percentage text-red"></span>
                <h5 class="description-header">@if (trim($cliente->financeiro) == 'JU') <span class="text-red text-bold">Restrições</span> @else <span class="text-green text-bold">Sem Restrições</span>  @endif</h5>
               
              </div>
              <!-- /.description-block -->
            </div>
          </div>
          <!-- /.row -->
        </div>
    </div>  

    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Timeline</a></li>
        <li><a href="#tab_2" data-toggle="tab">Comercial</a></li>
        <li><a href="#tab_3" data-toggle="tab">Orçamentos</a></li>
        <li><a href="#tab_4" data-toggle="tab">Vendas</a></li>
        <li><a href="#tab_5" data-toggle="tab">Devoluções</a></li>
        <li><a href="#tab_6" data-toggle="tab">Trocas</a></li>
 <!--     <li><a href="#tab_5" data-toggle="tab">Devoluções</a></li> 
   <li><a href="#tab_6" data-toggle="tab">Pesquisa</a></li>   -->  
        <li><a href="#tab_7" data-toggle="tab">Financeiro</a></li>
        <li><a href="#tab_8" data-toggle="tab">MPDV</a></li>
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
              <span class="time"></span>

              <h3 class="timeline-header"><a href="#">{{$historico->usuario->nome}}</a> registrou um histórico @if (\Auth::id() == $historico->id_usuario) <a href="/historicos/{{$historico->id}}/excluir" class="pull-right text-red">Excluir</a> @endif</h3>

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

        @php

          $total_linha = 0;
          $total_genero = 0;
          $total_material = 0;
          $total_tecnologia = 0;
          $total_formato = 0;
          $total_estilo = 0;

          $linhas = \DB::select("select substr(itens.linha,1,15) as linha, sum(qtde) qtde
from vendas_jde
left join itens on id_item = itens.id
where id_cliente = $cliente->id and ult_status not in ('980','984')
group by itens.linha
order by sum(qtde) desc
limit 5");

          foreach ($linhas as $linha) {

            $total_linha += $linha->qtde;

          } 

          $generos = \DB::select("select substr(genero,1,15) as genero, sum(qtde) qtde
from vendas_jde
left join itens on id_item = itens.id
where id_cliente = $cliente->id and ult_status not in ('980','984')
group by genero
order by sum(qtde) desc
limit 5");

          foreach ($generos as $genero) {

            $total_genero += $genero->qtde;

          } 

          $materiais = \DB::select("select substr(material,1,15) as material, sum(qtde) qtde
from vendas_jde
left join itens on id_item = itens.id
where id_cliente = $cliente->id and ult_status not in ('980','984')
group by material
order by sum(qtde) desc
limit 5");

          foreach ($materiais as $material) {

            $total_material += $material->qtde;

          } 

          $tecnologias = \DB::select("select substr(tecnologia,1,15) as tecnologia, sum(qtde) qtde
from vendas_jde
left join itens on id_item = itens.id
where id_cliente = $cliente->id and ult_status not in ('980','984')
group by tecnologia
order by sum(qtde) desc
limit 5");

          foreach ($tecnologias as $tecnologia) {

            $total_tecnologia += $tecnologia->qtde;

          } 

         $formatos = \DB::select("select substr(formato,1,15) as formato, sum(qtde) qtde
from vendas_jde
left join itens on id_item = itens.id
where id_cliente = $cliente->id and ult_status not in ('980','984')
group by formato
order by sum(qtde) desc
limit 5");

          foreach ($formatos as $formato) {

            $total_formato += $formato->qtde;

          } 

         $estilos = \DB::select("select substr(estilo,1,15) as estilo, sum(qtde) qtde
from vendas_jde
left join itens on id_item = itens.id
where id_cliente = $cliente->id and ult_status not in ('980','984')
group by estilo
order by sum(qtde) desc
limit 5");


          foreach ($estilos as $estilo) {

            $total_estilo += $estilo->qtde;

          } 
        @endphp


  			<div class="row">
          <div class="col-md-12">
          <table class="table table-bordered table-hover" id="example3">
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
                  <td width="25%" valign="middle" align="center">{{\Auth::user()->nome}}</td>
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

      <br>


        <div class="row">
          @if ($linhas)
          <div class="col-md-6">
            <label>linha</label>
            <table class="table table-bordered table-condensed">
              @foreach($linhas as $linha)

                @php
                  if ($linha->qtde > 0) {
                    $perc_linha = ($linha->qtde / $total_linha) * 100;
                  } else {
                    $perc_linha = 0;
                  }
                @endphp

                <tr>
                  <td>{{$linha->linha}}</td>
                  <td width="15%" align="center">{{number_format($perc_linha)}}%</td>
                </tr>
              @endforeach
            </table>
          </div>
          @endif

          @if ($generos)
          <div class="col-md-6">
            <label>genêro</label>
            <table class="table table-bordered table-condensed">
              @foreach($generos as $genero)

                @php
                  if ($genero->qtde > 0) {
                    $perc_genero = ($genero->qtde / $total_genero) * 100;
                  } else {
                    $perc_genero = 0;                    
                  }
                @endphp

                <tr>
                  <td>{{$genero->genero}}</td>
                  <td width="15%" align="center">{{number_format($perc_genero)}}%</td>
                </tr>
              @endforeach
            </table>
          </div>
          @endif



        </div>

        <div class="row">
          @if ($materiais)
          <div class="col-md-3">
            <label>material</label>
            <table class="table table-bordered table-condensed">
              @foreach($materiais as $material)

                @php
                  if ($material->qtde > 0) {
                    $perc_material = ($material->qtde / $total_material) * 100;
                  } else {
                    $perc_material = 0;
                  }
                @endphp

                <tr>
                  <td>{{$material->material}}</td>
                  <td width="15%" align="center">{{number_format($perc_material)}}%</td>
                </tr>
              @endforeach
            </table>
          </div>
          @endif

          @if ($tecnologias)
          <div class="col-md-3">
            <label>tecnologia</label>
            <table class="table table-bordered table-condensed">
              @foreach($tecnologias as $tecnologia)

                @php
                  if ($tecnologia->qtde > 0) {
                    $perc_tecnologia = ($tecnologia->qtde / $total_tecnologia) * 100;
                  } else {
                    $perc_tecnologia = 0;
                  }
                @endphp

                <tr>
                  <td>{{$tecnologia->tecnologia}}</td>
                  <td width="15%" align="center">{{number_format($perc_tecnologia)}}%</td>
                </tr>
              @endforeach
            </table>
          </div>
          @endif


          @if ($formatos)
          <div class="col-md-3">
            <label>formato</label>
            <table class="table table-bordered table-condensed">
              @foreach($formatos as $formato)

                @php
                  if ($formato->qtde > 0) {
                    $perc_formato = ($formato->qtde / $total_formato) * 100;
                  } else {
                    $perc_formato = 0;
                  }
                @endphp

                <tr>
                  <td>{{$formato->formato}}</td>
                  <td width="15%" align="center">{{number_format($perc_formato)}}%</td>
                </tr>
              @endforeach
            </table>
          </div>
          @endif


          @if ($estilos)
          <div class="col-md-3">
            <label>estilo</label>
            <table class="table table-bordered table-condensed">
              @foreach($estilos as $estilo)

                @php
                  if ($estilo->qtde > 0) {
                    $perc_estilo = ($estilo->qtde / $total_estilo) * 100;
                  } else {
                    $perc_estilo = 0;
                  }
                @endphp

                <tr>
                  <td>{{$estilo->estilo}}</td>
                  <td width="15%" align="center">{{number_format($perc_estilo)}}%</td>
                </tr>
              @endforeach
            </table>
          </div>
          @endif

        </div>

				
		</div>
		  
        <div class="tab-pane" id="tab_3">

          @php
            $qtde_orc = 0;
            $valor_orc = 0;

            $qtde_atende = 0;
            $valor_atende = 0;

//             $orc_status = \DB::select("select statusatual, sum(qtde) as qtde

// from vendas_jde
// left join itens on id_item= itens.id
// where id_cliente= $cliente->id and ult_status = '510' and prox_status = '515'

// group by statusatual
// limit 5");


//             $orc_ano = \DB::select("select anomod, sum(qtde) as qtde

// from vendas_jde
// left join itens on id_item= itens.id
// where id_cliente= $cliente->id and ult_status = '510' and prox_status = '515'

// group by anomod
// order by anomod desc
// limit 5");



//             $orc_clas = \DB::select("select clasmod, sum(qtde) as qtde

// from vendas_jde
// left join itens on id_item= itens.id
// where id_cliente= $cliente->id and ult_status = '510' and prox_status = '515'

// group by clasmod
// order by clasmod desc
// limit 5");

//             $perc_orc = \DB::select("select  sum(venda) as venda, sum(orc) as orc, sum(cancel) as cancel
// from (
// select situacao, case when situacao = 'Venda' then 1 else 0 end as venda,
// case when situacao = 'Orcamento' then 1 else 0 end as orc,
// case when situacao = 'Cancelado' then 1 else 0 end as cancel
// from (
// select case when ult_status = '510' and prox_status = '515' then 'Orcamento' when ult_status not in ('980','984') then 'Venda' else 'Cancelado' end as situacao

// from vendas_jde
// where id_cliente = $cliente->id
// ) as base
// ) as fim");

            if (isset($perc_orc) && $perc_orc[0]->orc > 0 && $perc_orc[0]->venda > 0) {
              $perc_orc2 = ($perc_orc[0]->orc / $perc_orc[0]->venda) * 100;
              //$perc_orc2 = 0;
            } else {
              $perc_orc2 = 0;
            }
            $perc = 0;
          @endphp

          @foreach ($orcamentos as $orcamento)

            @php
              $qtde_orc += $orcamento->qtde;
              $valor_orc += $orcamento->valor;

              $qtde_atende += $orcamento->atende_qtde;
              $valor_atende += $orcamento->atende_valor;

              if ($valor_atende > 0 && $valor_orc > 0) {
                $perc = $valor_atende / $valor_orc * 100;
              } else {
                $perc = 0;
              }

            @endphp

          @endforeach
{{-- 
          <table class="table table-bordered">
          <tr>

            <td width="25%" align="center"><label>% atendimento</label><h3>{{number_format($perc)}}%</h3></td>
            <td width="25%" align="center"><label>previsto 30 dias</label></td>
            <td width="25%" align="center"><label>improdutivo</label></td>
            <td width="25%" align="center"><label>% venda x orçamento</label><h3>@if ($perc_orc2)  {{number_format($perc_orc2)}}% @endif</td>
          </tr>
          </table>

          <div class="row">
            <div class="col-md-4">
              <label>status</label>
              <table class="table table-bordered">
                @foreach ($orc_status as $status)
                  <tr>
                    <td>{{$status->statusatual}}</td>
                    <td width="20%" align="center">{{$status->qtde}}</td>
                  </tr>
                @endforeach
              </table>
            </div>
            <div class="col-md-4">
              <label>ano</label>
              <table class="table table-bordered">
                @foreach ($orc_ano as $ano)
                  <tr>
                    <td>{{$ano->anomod}}</td>
                    <td width="20%" align="center">{{$ano->qtde}}</td>
                  </tr>
                @endforeach
              </table>
            </div>
            <div class="col-md-4">
              <label>classificação</label>
              <table class="table table-bordered">
                @foreach ($orc_clas as $clas)
                  <tr>
                    <td>{{$clas->clasmod}}</td>
                    <td width="20%" align="center">{{$clas->qtde}}</td>
                  </tr>
                @endforeach
              </table>
            </div>
          </div> --}}
          <table class="table table-bordered table-hover " id="example3">
            <thead>
              <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">Marca</th>
                <th colspan="2" style="text-align: center;">Orçamentos</th>
                <th colspan="2" style="text-align: center;">Atende</th>

              </tr>
              <tr>

                <th style="text-align: center;">Peças</th>
                <th style="text-align: center;">Valor</th>
                <th style="text-align: center;">Peças</th>
                <th style="text-align: center;">Valor</th>

              </tr>
            </thead>
            <tbody>

              @if (isset($orcamentos) && count($orcamentos) > 0)
                @foreach ($orcamentos as $orcamento)

                  <tr>
                    <td width="15%" align="center"><img src="/img/marcas/{{$orcamento->grife}}.png" class="img-responsive" width="120px"></td>
                    <td width="5%" valign="middle" align="center">{{$orcamento->qtde}}</td>
                    <td width="10%" valign="middle" align="right">{{number_format($orcamento->valor,2,',','.')}}</td>
                    <td width="5%" valign="middle" align="center">{{$orcamento->atende_qtde}}</td>
                    <td width="10%" valign="middle" align="right">{{number_format($orcamento->atende_valor,2,',','.')}}</td>
                   
                  </tr>


                @endforeach
              @else 
                <tr>
                  <td colspan="5" align="center">Nenhum orçamento para este cliente</td>
                </tr>
              @endif
            </tbody>
            <tfoot>
              <tr class="text-bold">
                <td align="right"> TOTAL</td>
                <td align="center"><a href="/backorder/{{$cliente->id}}/?fornec={{$cliente->id}}"> {{$qtde_orc}}</a></td>
                <td align="right"> {{number_format($valor_orc,2,',','.')}}</td>
                <td align="center"> {{$qtde_atende}}</td>
                <td align="right"> {{number_format($valor_atende,2,',','.')}}</td>
              </tr>
            </tfoot>
          </table>

        </div>
		  
		  
		  
      <div class="tab-pane" id="tab_4">
		<table class="table table-bordered">

            <thead>
              <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle">Pedido</th>
                <th rowspan="2" style="text-align: center; vertical-align: middle">Data</th>
 
        				<th colspan="2" style="text-align: center; vertical-align: middle">Vendido</th> 
                <th colspan="2" style="text-align: center; vertical-align: middle">Cancelado</th>
                <th colspan="2" style="text-align: center; vertical-align: middle">Orçamento</th>
                <th colspan="2" style="text-align: center; vertical-align: middle">Faturado</th>
               
              </tr>
              <tr>
                <th style="text-align: center; vertical-align: middle">Qtde</th>
                <th style="text-align: center; vertical-align: middle">Valor</th>
                <th style="text-align: center; vertical-align: middle">Qtde</th>
                <th style="text-align: center; vertical-align: middle">Valor</th>
                <th style="text-align: center; vertical-align: middle">Qtde</th>
                <th style="text-align: center; vertical-align: middle">Valor</th>
                <th style="text-align: center; vertical-align: middle">Qtde</th>
                <th style="text-align: center; vertical-align: middle">Valor</th>
              </tr>                
            </thead>
            <tbody>
              @php
                $total_qtde_vendida = 0;
                $total_qtde_cancelada = 0;
                $total_qtde_orcamento = 0;
                $total_qtde_faturada = 0;
                $total_valor_vendida = 0;
                $total_valor_cancelada = 0;
                $total_valor_orcamento = 0;
                $total_valor_faturada = 0;
              @endphp

              @foreach ($vendas as $venda)

                @php
                  $total_qtde_vendida += $venda->qtde_vendida;
                  $total_qtde_cancelada += $venda->qtde_cancelada;
                  $total_qtde_orcamento += $venda->qtde_orcamento;
                  $total_qtde_faturada += $venda->qtde_faturada;
                  $total_valor_vendida += $venda->valor_vendido;
                  $total_valor_cancelada += $venda->valor_cancelada;
                  $total_valor_orcamento += $venda->valor_orcamento;
                  $total_valor_faturada += $venda->valor_faturada;
                @endphp

                <tr>
                  <td align="center"><a href="/vendas/{{$venda->pedido}}">{{$venda->pedido}}</a></td>
                  <td align="center">{{date('d/m/Y', strtotime($venda->dt_venda))}}</td>
                  <td align="center">{{$venda->qtde_vendida}}</td>
      			      <td align="right">{{number_format($venda->valor_vendido,2,',','.')}}</td>	
                  <td align="center">{{$venda->qtde_cancelada}}</td>
                  <td align="right">{{number_format($venda->valor_cancelada,2,',','.')}}</td> 
                  <td align="center">{{$venda->qtde_orcamento}}</td>
                  <td align="right">{{number_format($venda->valor_orcamento,2,',','.')}}</td> 
                  <td align="center">{{$venda->qtde_faturada}}</td>
                  <td align="right">{{number_format($venda->valor_faturada,2,',','.')}}</td> 
                </tr>                
              @endforeach
            </tbody>

                <tr class="text-bold">
                  <td colspan="2"></td>
                  <td style="text-align: center">{{$total_qtde_vendida}}</td>  
                  <td style="text-align: right">{{number_format($total_valor_vendida,2,',','.')}}</td>  
                  <td style="text-align: center">{{$total_qtde_cancelada}}</td>  
                  <td style="text-align: right">{{number_format($total_valor_cancelada,2,',','.')}}</td>  
                  <td style="text-align: center">{{$total_qtde_orcamento}}</td>  
                  <td style="text-align: right">{{number_format($total_valor_orcamento,2,',','.')}}</td>  
                  <td style="text-align: center">{{$total_qtde_faturada}}</td>  
                  <td style="text-align: right">{{number_format($total_valor_faturada,2,',','.')}}</td>  
                </tr>  

            
          </table>
        </div>
        <div class="tab-pane" id="tab_5">
          Em desenvolvimento
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_6">


          <div class="row">
            <div class="col-md-12">
              <span class="lead text-blue"><i class="fa fa-edit"></i> Resumo </span>
              <table class="table table-bordered">
              <tr>

                <td width="25%" align="center"><label>Trocas</label>@if (isset($trocasdet[0]->trocas)) <a href="/trocas?id_cliente={{$cliente->id}}"><h3>{{$trocasdet[0]->trocas}}</h3></a> @endif</td>
                <td width="25%" align="center"><label>Abertas</label>@if (isset($trocasdet[0]->abertas)) <a href="/trocas?id_cliente={{$cliente->id}}&status=Aberta"><h3>{{$trocasdet[0]->abertas}}</h3></a> @endif</td>
                <td width="25%" align="center"><label>% Antecipação</label>@if (isset($trocasdet[0]->perc_antecipacao)) <a href="/trocas?id_cliente={{$cliente->id}}"><h3>{{number_format($trocasdet[0]->perc_antecipacao,2,',','.')}}%</h3></a> @endif</td>
                <td width="25%" align="center">
                  <label>% Trocas x Venda</label>
                  @if (isset($trocas_vendas[0]->perc_trocas_vendas)) <a href="/trocas?id_cliente={{$cliente->id}}"><h3>{{number_format($trocas_vendas[0]->perc_trocas_vendas,2,',','.')}}  %</h3></a> @endif
                </td>
              </tr>
              </table>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <span class="lead text-orange"><i class="fa fa-warning"></i> Pendências </span>
              <table class="table table-bordered">
              <tr>
                <td width="25%" align="center"><label> Inadimplência</label>@if (isset($trocasdet[0]->inadimplentes)) <a href="/trocas?id_cliente={{$cliente->id}}"><h3>{{$trocasdet[0]->inadimplentes}}</h3></a> @endif</td>
                <td width="25%" align="center"><label> Interação</label>@if (isset($trocasdet[0]->pend_interacao)) <a href="/trocas?id_cliente={{$cliente->id}}"><h3>{{$trocasdet[0]->pend_interacao}}</h3></a> @endif</td>
                <td width="25%" align="center"><label> Fiscal</label>@if (isset($trocasdet[0]->pend_fiscal)) <a href="/trocas?id_cliente={{$cliente->id}}"><h3>{{$trocasdet[0]->pend_fiscal}}</h3></a> @endif</td>
                <td width="25%" align="center"><label> Devolução</label>@if (isset($trocasdet[0]->pend_devolucao)) <a href="/trocas?id_cliente={{$cliente->id}}"><h3>{{$trocasdet[0]->pend_devolucao}}</h3></a> @endif</td>
              </tr>
              </table>
            </div>
          </div>
          <div class="row">

            <div class="col-md-6">

              <span class="lead text-blue"><i class="fa fa-list"></i>  Itens mais trocados </span>
              <table class="table table-condensed table-bordered">
              @foreach ($trocas_pecas as $peca)
                <tr>                
                  <td>{{$peca->secundario}}</td>
                  <td align="center">{{$peca->qtde}}</td>
                </tr>
              @endforeach 
              </table>
            </div>


            <div class="col-md-6">
              <span class="lead text-blue"><i class="fa  fa-ticket"></i>  Grifes mais trocadas </span>
              <table class="table table-condensed table-bordered">
              @foreach ($trocas_grifes as $peca)
                <tr>                
                  <td>{{$peca->grife}}</td>
                  <td align="center">{{$peca->qtde}}</td>
                </tr>
              @endforeach 
              </table>


            </div>

          </div>
        </div>


        <div class="tab-pane" id="tab_7">


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
                <td align="right"><a href=""> {{number_format($financeiro[0]->Futuro, 2,',','.')}}</a></td>
              </tr>
              <tr>                
                <th>Atual</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->Atual, 2,',','.')}}</a></td>
             
			</tr>
				 
              <tr>                
                <th> Vencidos</th>
                <td align="right"></td>
              </tr>
				  
				  
              <tr>                
                <th>&nbsp 1 - 30</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->venc_1_30, 2,',','.')}}</a></td>
              </tr>
              <tr>                
                <th>&nbsp 30 - 60</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->venc_31_60, 2,',','.')}}</a></td>
              </tr>
              <tr>                
                <th>&nbsp 61 - 90</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->venc_61_90, 2,',','.')}}</a></td>
              </tr>
              <tr>                
                <th>&nbsp 91 - 120</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->venc_91_120, 2,',','.')}}</a></td>
              </tr>
              <tr>                
                <th>&nbsp 121 - 150</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->venc_121_150, 2,',','.')}}</a></td>
              </tr>
              <tr>                
                <th>&nbsp 151 - 999</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->venc_151_999, 2,',','.')}}</a></td>
              </tr>
              <tr>                
                <th>&nbsp Acima de 999</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->venc_999, 2,',','.')}}</a></td>
              </tr>
				  
				    <tr>                
                <th>  Vencido Total</th>
                <td align="right"><a href=""> {{number_format($financeiro[0]->vencidott, 2,',','.')}}</a></td>
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

            <a class="btn btn-flat btn-default" href="/financeiro?id_cliente={{$cliente->id}}">Ver todos títulos</a>

          </div>
        </div>



			  
			  
@php

$mpdv = \DB::select("


select cod_grife, sum(pecas) pecas, sum(mpdv_disp+mpdv_disp1) mpdv_disp,  sum(mpdv_exp+mpdv_exp1) mpdv_exp, sum(mpdv_tt-mpdv_disp-mpdv_disp1-mpdv_exp-mpdv_exp1) mpdv_outros 
from (
select cod_grife, 
case when ped.tipo_item = 006 then qtde else 0 end as pecas,
case when ped.tipo_item = 004 then qtde else 0 end as mpdv_tt,
case when ped.tipo_item = 004 and modelo like 'disp%' then qtde else 0 end as mpdv_disp,
case when ped.tipo_item = 004 and modelo like 'exp%' then qtde else 0 end as mpdv_exp,
case when ped.tipo_item = 004 and modelo = 'NÃO APAGAR' then qtde else 0 end as mpdv_disp1,
case when ped.tipo_item = 004 and modelo = 'kit' then qtde else 0 end as mpdv_exp1

from pedidos_jde ped 
left join itens on itens.id = id_item
where ped.tipo_item in ('004','006') and  datediff(now(),dt_venda) <=365
and id_cliente = '$cliente->id'
and ult_status not in (980,984)

) as fim group by cod_grife
 ");


$pedido_mpdv = \DB::select("
select ped.pedido, dt_emissao, group_concat(distinct ped.cod_grife) cod_grife, prox_status, sum(qtde) qtde

from pedidos_jde ped 
left join itens on itens.id = id_item
where ped.tipo_item = 004 and ult_status not in (980,984)
and id_cliente = '$cliente->id' 
group by ped.pedido, dt_emissao,  prox_status order by dt_emissao desc ");		  
		  		  
		  
@endphp
			  
			  
	
<div class="tab-pane" id="tab_8">


          <div class="row">
	
	
             <div class="col-md-7">
	 	        <div class="table-responsive">
		

              <table class="table table-bordered ">


                <thead>
		<tr>	
			<td colspan="5" align="center">Pedidos emitidos nos ultimos 12 meses</td>
		</tr>
                  <tr>
				    <th>grife</th>
                    <th>pecas</th>
                    <th>displays</th>
                    <th>expositores</th>
					<th>outros_mpdvs</th>
            
                  </tr>                
                </thead>
                <tbody>
                @foreach ($mpdv as $mpdvs)
                    <tr>
                      <td align="center"><a href="/mpdv_det?grife={{$mpdvs->cod_grife}}&cliente={{$cliente->id}}&pedido=''">{{$mpdvs->cod_grife}}</a></td>
					  <td align="center">{{$mpdvs->pecas}}</td>
                      <td align="center">{{$mpdvs->mpdv_disp}}</td>
                      <td align="center">{{$mpdvs->mpdv_exp}}</td>
					  <td align="center">{{$mpdvs->mpdv_outros}}</td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            </div>

            <div class="col-md-5">
              <div class="table-responsive">
		
              <table class="table table-bordered">

                <thead>
	<tr>	
			<td colspan="5" align="center">Pedidos de mpdv</td>
		</tr>
                  <tr>
				    <th>Pedido</th>
                    <th>Data</th>
                    <th>Grifes</th>
                    <th>Status</th>
                    <th>qtde</th>
  
                  </tr>                
                </thead>
                <tbody>
                @foreach ($pedido_mpdv as $pedidos_mpdv)
                    <tr>
					  <td align="center"><a href="/mpdv_det?pedido={{$pedidos_mpdv->pedido}}&cliente={{$cliente->id}}&grife=''">{{$pedidos_mpdv->pedido}}</a> </td>
					  <td align="center">{{$pedidos_mpdv->dt_emissao}}</td>
                      <td align="center">{{$pedidos_mpdv->cod_grife}}</td>
                      <td align="center">{{$pedidos_mpdv->prox_status}}</td>
                      <td align="center">{{$pedidos_mpdv->qtde}}</td>
                     
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            </div>

      

          </div>
        </div>
        <!-- /.tab-pane fim tbel8 -->
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->



  </div>


  <div class="col-md-12 col-lg-3">
    <form action="" method="" class="form-horizontal">
    <div class="box box-widget hidden-xs hidden-sm ">
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
              <option>Tirar da Carteira</option>
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