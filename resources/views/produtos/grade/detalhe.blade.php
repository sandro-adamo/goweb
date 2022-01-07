@extends('produtos/painel/index')

@section('titulo') {{$item->secundario}} @append

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')

<div class="row">

  <div class="col-md-8">

 
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

$fotos = \DB::select("select * from itens where modelo = 'ah6254' ");

	$result = count($fotos);
	echo 'resultado'.$result;
	
@endphp
	
	

<div class="col-md-12">
<span class="lead">Modelos</span>
<div class="row">		
		
		@foreach ($fotos as $catalogo)
		
		
		


<div class="row">

      <div class="col-md-2">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:14px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          <span class="pull-right">  {{$catalogo->modelo}}</span>
			  <span class="pull-right">{{$catalogo->colmod}}</span>
			 
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 100px; max-height: 100px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>

                  
          </div>
			
			
			 
			<br>
			<table class="table table-bordered" style="text-align: left;">
          <tr>
			  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
            <td class="">{{$catalogo->valortabela}} </b>
				</td>
          </tr> </table>
      
              
				

		
		
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			
            
		
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		

		
          <div class="box-body">
           <!-- linha 452--> 
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>

			 <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>{{number_format($catalogo->custo)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-warning text-yellow"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
 				
            </tr>




        </table>


    </div>
</div>
			  
			  
			  

          </div>
        </div>
      </div>
      @endforeach

     

    </div>
  </div>

</div>
					   </ul>
				  </div>
					   </div>
				  
				  
				  
				  

<div class="tab-pane" id="qualidade">
<div class="col-md-12">
<ul class="timeline">


@php

$fotos = \DB::select("select * from itens where modelo = 'ah6254' ");

	$result = count($fotos);
	echo 'resultado'.$result;
	
@endphp
	
	

<div class="col-md-12">
<span class="lead">Modelos</span>
<div class="row">		
		
		@foreach ($fotos as $catalogo)
		
		
		


<div class="row">

      <div class="col-md-2">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:14px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          <span class="pull-right">  {{$catalogo->modelo}}</span>
			  <span class="pull-right">{{$catalogo->colmod}}</span>
			 
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 100px; max-height: 100px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>

                  
          </div>
			
			
			 
			<br>
			<table class="table table-bordered" style="text-align: left;">
          <tr>
			  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
            <td class="">{{$catalogo->valortabela}} </b>
				</td>
          </tr> </table>
      
              
				

		
		
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			
            
		
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		

		
          <div class="box-body">
           <!-- linha 452--> 
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>

			 <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>{{number_format($catalogo->custo)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-warning text-yellow"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
 				
            </tr>




        </table>


    </div>
</div>
			  
			  
			  

          </div>
        </div>
      </div>
      @endforeach

     

    </div>
  </div>

</div>
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
              <span class="time"><i class="fa fa-clock-o"></i> aaaa</span>

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