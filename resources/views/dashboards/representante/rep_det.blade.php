@extends('produtos/painel/index')

@php
$query2 = \DB::select(" 
select * from itens where modelo = 'AH6254';
");

foreach ($query2 as $query_2)
endforeach


@endphp

@section('titulo') {{$query_2->secundario}} @append

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')
pagina rep
<div class="row">

  <div class="col-md-3">
    <div class="box box-widget">
      <div class="box-header with-border bg-gray"> 
        <b>{{$query_2->secundario}}</b>
        <span class="pull-right"><b></b></span>
      </div>
      <div align="center" style="min-height: 200px;margin-top: 30px;">
        <a href="" class="zoom" data-value="{{$item->secundario}}">
            <img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item->secundario}}" class="img-responsive">
        </a>
      </div>
      <div class=" box-body">
        <div class="row">
          <div class="col-md-6">{{$item->clasmod}}</div>
          <div class="col-md-6" align="right">{{$item->colmod}}</div>
        </div>

        @include('produtos.painel.info-vendas')
        @include('produtos.painel.info-estoques')

        <table class="table table-bordered" style="text-align: left;">
          
			 <tr>
            <td><i class="fa fa-tag"></i> Grife</td>
            <td>{{$item->grife}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="grife" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-list"></i> Agrupamento</td>
            <td>{{$item->agrup}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="agrupamento" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Modelo </td>
            <td>{{$item->modelo}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="modelo" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Primário </td>
            <td>{{$item->primario}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="primario" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Descrição </td>
            <td>{{$item->descricao}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="descricao1" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> EAN </td>
            <td>{{$item->ean}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="ean" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Valor tabela </td>
            <td>{{$item->valortabela}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="valortabela" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Status atual </td>
            <td>{{$item->statusatual}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="status" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			
			
		<tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Classificação item </td>
            <td>{{$item->clasitem}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="classitem" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
            <td><i class="fa fa-user-secret"></i> Coleção item </td>
            <td>{{$item->colitem}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="colitem" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Ano item </td>
            <td>{{$item->anoitem}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="anoitem" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-intersex"></i> Genêro</td>
            <td>{{$item->genero}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="genero" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>   
          <tr>
            <td><i class="fa fa-child"></i> Idade</td>
            <td>{{$item->idade}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="idade" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>    
          <tr>
            <td><i class="fa fa-wrench"></i> Material</td>
            <td>{{$item->material}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="material" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>                                           
          <tr>
            <td><i class="fa fa-link"></i> Fixação</td>
            <td>{{$item->fixacao}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="fixacao" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>   
          <tr>
            <td><i class="fa fa-user-secret"></i> Estilo</td>
            <td>{{$item->estilo}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="estilo" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr> 
			 <tr>
            <td><i class="fa fa-user-secret"></i> Tecnologia </td>
            <td>{{$item->tecnologia}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="tecnologia" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
		  <tr>
            <td><i class="fa fa-user-secret"></i> Tamanho Olho</td>
            <td>{{$item->tamolho}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="tamanhoolho" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Tamanho Haste</td>
            <td>{{$item->tamhaste}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="tamanhohaste" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Tamanho Ponte</td>
            <td>{{$item->tamponte}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="tamanhoponte" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Armação cor principal</td>
            <td>{{$item->corarm1}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="corarm1" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Armação Cor secundária</td>
            <td>{{$item->codarm2}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="corarm2" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Haste Cor principal</td>
            <td>{{$item->corhas1}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="corhaste1" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Haste Cor secundária</td>
            <td>{{$item->corhas2}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="corhaste2" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
			<tr>
            <td><i class="fa fa-user-secret"></i> Lente tecnologia/cor </td>
            <td>{{$item->corlente}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="corteclente" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
		  <tr>
            <td><i class="fa fa-th"></i> Tipo</td>
            <td>{{$item->tipoitem}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="tipoitem" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>            
          <tr>
            <td><i class="fa fa-code-fork"></i> Linha</td>
            <td>{{$item->linha}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="linha" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>                    
          <tr>
            <td><i class="fa fa-industry"></i> Fornecedor</td>
            <td>{{$item->fornecedor}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="fornecedor" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>    
		  <tr>
            <td><i class="fa fa-user-secret"></i> Tipo Armazenamento </td>
            <td>{{$item->codtipoarmaz}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="tipoarmazenamento" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
		  <tr>
            <td><i class="fa fa-user-secret"></i> Classe Contábil </td>
            <td>{{$item->classecontabil}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="classecontabil" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
		  <tr>
            <td><i class="fa fa-user-secret"></i> NCM </td>
            <td>{{$item->ncm}} <span class="pull-right"><a href="" class="alteraCaracteristica" data-caracteristica="ncm" data-value="{{$item->id}}"><i class="fa fa-edit"></i></a></span></td>
          </tr>
		  
		  
		  
		  
        </table>

      </div>
    </div>
  </div>

  <div class="col-md-8">

    <div class="row">
      <div class="col-md-12">
        <div class="box box-body box-widget">
          <span style="font-size: 16px;"> {{$item->id}}</span><br>
          <span style="font-size: 26px;"> {{$item->descricao}}</span><br>
          <span style="font-size: 16px;"> {{$item->ean}}</span>
        </div>
      </div>
    </div>

    @if (Session::has('alert-success'))

      <div class="alert alert-success">{{Session::get('alert-success')}}</div>

    @endif
	  @if ($item->id =='')
	  
	  @else

    <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id_item" value="{{$item->id}}">
      @csrf
    <span class="lead">Histórico</span>
    <div class="row">
      <div class="col-md-12">
        <select name="categoria" class="form-control">
			<option value = "historico" selected=""> Histórico</option>
			<option value="qualidade"> Qualidade</option>
			<option value="caracteristicas"> Característica</option>
			<option value="apontamentos"> Apontamentos</option>
			<option value="reprocesso"> Reprocesso Pintura</option>
      <option value="data_producao"> Data Produção</option>

        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <textarea class="form-control" name="historico" required="" rows="4"></textarea>
      </div>
    </div>
      <br>
<div class="row">
      <div class="col-md-12">Nova data de entrega
    <input  type="date" name="data">
</div>
    </div>
          <br>
<div class="row">
      <div class="col-md-12"> Número pedido fábrica
    <input  type="text" name="numeropedido">
</div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-10">
        <input type="file" name="arquivo" class="form-control">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-flat btn-default pull-right"><i class="fa fa-save"></i> Gravar</button>
      </div>
    </div>
    </form>
    <br>
@endif
    <!-- row -->
    <div class="row">
		<div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li  class="active"><a href="#geral" data-toggle="tab">Geral</a></li>
              <li><a href="#historico" data-toggle="tab">Histórico</a></li>
				<li><a href="#caracteristica" data-toggle="tab">Característica</a></li>
              <li><a href="#qualidade" data-toggle="tab">Qualidade</a></li>
				<li><a href="#apontamentos" data-toggle="tab">Apontamentos</a></li>
				<li><a href="#reprocesso" data-toggle="tab">Reprocessos</a></li>
        <li><a href="#data_producao" data-toggle="tab">Data producao</a></li>
				
          
			  <div class="tab-content">
              <div class="active tab-pane" id="geral">
      <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data

from historicos 
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%'
			
group by date(historicos.created_at)
order by date(historicos.created_at) desc");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-gray">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $historicos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%' and date(historicos.created_at) = '$data->data'
and categoria <> 'data_producao'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($historicos as $historico)

			<li>

            <i class="fa fa-envelope bg-gray"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$historico->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$historico->nome}}</a> alterou uma {{$historico->categoria}}</h3>

              <div class="timeline-body">
                {!!$historico->historico!!}
                @if ($historico->arquivo <> '')

                  @php
                    $arquivo = explode('.', $historico->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$historico->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$historico->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$historico->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

@endforeach
        </ul>
		  </div>
				</div>
				   <div class="tab-pane" id="historico">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data
from historicos
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%'
			and categoria = 'historico'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-blue">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $historicos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%' and date(historicos.created_at) = '$data->data'
			and categoria = 'historico'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($historicos as $historico)

			<li>

            <i class="fa fa-envelope bg-blue"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$historico->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$historico->nome}}</a> alterou uma {{$historico->categoria}}</h3>

              <div class="timeline-body">
                {!!$historico->historico!!}
                @if ($historico->arquivo <> '')

                  @php
                    $arquivo = explode('.', $historico->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$historico->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$historico->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$historico->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

@endforeach
					   </ul>
				  </div>
					   </div>
				   <div class="tab-pane" id="caracteristica">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data

from historicos 
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%'
			and categoria = 'caracteristica'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-yellow">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $historicos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%' and date(historicos.created_at) = '$data->data'
			and categoria = 'caracteristica'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($historicos as $historico)

			<li>

            <i class="fa fa-envelope bg-yellow"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$historico->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$historico->nome}}</a> alterou uma {{$historico->categoria}}</h3>

              <div class="timeline-body">
                {!!$historico->historico!!}
                @if ($historico->arquivo <> '')

                  @php
                    $arquivo = explode('.', $historico->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$historico->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$historico->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$historico->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

@endforeach
					   </ul>
				  </div>
					   </div>
					    <div class="tab-pane" id="qualidade">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data

from historicos 
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%'
			and categoria = 'qualidade'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-green">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $historicos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%' and date(historicos.created_at) = '$data->data'
			and categoria = 'qualidade'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($historicos as $historico)

			<li>

            <i class="fa fa-envelope bg-green"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$historico->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$historico->nome}}</a> alterou uma {{$historico->categoria}}</h3>

              <div class="timeline-body">
                {!!$historico->historico!!}
                @if ($historico->arquivo <> '')

                  @php
                    $arquivo = explode('.', $historico->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$historico->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$historico->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$historico->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

@endforeach
					   </ul>
				  </div>
      </div>
				  
				  
				  <div class="tab-pane" id="apontamentos">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data

from historicos 
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%'
			and categoria = 'apontamentos'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-yellow">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

$historicos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%' and date(historicos.created_at) = '$data->data'
			and categoria = 'apontamentos'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($historicos as $historico)

			<li>

            <i class="fa fa-envelope bg-yellow"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$historico->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$historico->nome}}</a> alterou uma {{$historico->categoria}}</h3>

              <div class="timeline-body">
                {!!$historico->historico!!}
                @if ($historico->arquivo <> '')

                  @php
                    $arquivo = explode('.', $historico->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$historico->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$historico->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$historico->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

@endforeach
					   </ul>
				  </div>
					   </div>

				  
<div class="tab-pane" id="reprocessos">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data
from historicos
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%'
			and categoria = 'reprocesso'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-blue">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $reprocessos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  '$item->secundario%' and date(historicos.created_at) = '$data->data'
			and categoria = 'reprocesso'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($reprocessos as $reprocesso)

			<li>

            <i class="fa fa-envelope bg-blue"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$reprocesso->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$reprocesso->nome}}</a> alterou uma {{$reprocesso->categoria}}</h3>

              <div class="timeline-body">
                {!!$reprocesso->historico!!}
                @if ($reprocesso->arquivo <> '')

                  @php
                    $arquivo = explode('.', $reprocesso->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$reprocesso->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$reprocesso->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$reprocesso->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach



@endforeach
					   </ul>
				  </div>
					   </div>			

             <div class="tab-pane" id="data_producao">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
     
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data
from historicos
left join itens on id_item = itens.id     
where secundario LIKE  '$item->secundario%'
      and categoria = 'data_producao'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
    
      ");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-blue">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $data_producao = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id     
where secundario LIKE  '$item->secundario%' and date(historicos.created_at) = '$data->data'
      and categoria = 'data_producao'
      order by historicos.created_at desc
      ");

    @endphp

      @foreach ($data_producao as $data_producao1)

      <li>

            <i class="fa fa-envelope bg-blue"></i>
        

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$data_producao1->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$data_producao1->nome}}</a> alterou uma {{$data_producao1->categoria}}</h3>

              <div class="timeline-body">
                {!!$data_producao1->historico!!}<br>
                <b>Nova data entrega</b> {!!$data_producao1->nova_data_producao!!}<br>
                <b>Pedido fábrica</b> {!!$data_producao1->pedido_fabrica!!}
                @if ($data_producao1->arquivo <> '')

                  @php
                    $arquivo = explode('.', $data_producao1->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$data_producao1->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$data_producao1->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
        


              <div class="timeline-footer">
                <a href="/historico/{{$data_producao1->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

        

@endforeach
             </ul>
          </div>
             </div>     	  				  
				  				  				  
				  				  				  				  
				  				  				  				  				  				  
    </div>

  </div>

</div>

@include('produtos.painel.modal.genero')
@include('produtos.painel.modal.caracteristica')
@stop