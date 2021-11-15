@extends('layout.principal')

@section('title')
<i class="fa fa-list"></i> Jobs
@append 

@section('conteudo')

<div class="row">
  <div class="col-md-12">
    @if (Session::has('alert'))
      <p class="callout callout-warning"><i class="fa fa-warning"></i> {{ Session::get('alert') }} </p>
    @endif
    <div class="box box-widget box-body">
<!--
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="pesquisa" class="form-control">
        </div>
-->
<!--
        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
          <a href="/usuarios/novo" class="btn btn-flat btn-default pull-right">Novo Usuário</a>
        </div>
-->
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="10%">Tabela</th>
            <th width="10%">Registros</th>
            <th width="20%">Inicio</th>
            <th width="20%">Fim</th>
            <th width="10%">Tempo</th>
			  <th width="10%">Programado</th>
			      <th width="10%">Atualizar tabela</th>
			
			  
          </tr>
        </thead>
        <tbody>
          @foreach ($atualizacaobase as $atualizacaobases)

         

          <tr>
            <td>{{$atualizacaobases->tabela}}</td>
            <td><a>{{$atualizacaobases->registros}}</a></td>
            <td align="center"> {{$atualizacaobases->inicio}} </td>
            <td align="center">{{$atualizacaobases->fim}} </td>
			     <td align="center">{{$atualizacaobases->tempo}} </td>
			  <td align="center">{{$atualizacaobases->Data_hora_atualizacao}} </td>
           
            <td align="center"><a href="http://goweb.goeyewear.com.br/painel/{{$atualizacaobases->tabela}}" class="text-green"> <i class="fa fa-play"></i> Executar</a></td>
		
			  
          </tr>
          @endforeach
        </tbody>
      </table>
		<br>

          </div>

		<table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="5%">Status</th>
            <th width="35%">Nome</th>
            <th width="20%">Registros</th>
            <th width="20%">Ult. Execução</th>
            <th colspan="2">Baixar BI</th>
			  <th colspan="2">Direto banco</th>
			  
          </tr>
        </thead>
        <tbody>
{{--           @foreach ($jobs as $job)

            @php 

              $table = \DB::select("select date(created_at) as timestamp, count(id) as total from $job->tabela group by date(created_at)");

            @endphp --}}

          <tr>
            <td></td>
            <td><a href="/integracao/producoes">Produções</a></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"><a href="" class="text-blue uploadArquivo" data-value=""> <i class="fa fa-upload"></i> Upload</a></td>
            <td align="center"><a href="/jobs//executa" class="text-green"> <i class="fa fa-play"></i> Executar</a></td>
			      <td align="center"><a href="" class="text-red"> <i class="fa fa-play"></i> Direto Banco</a></td>
          </tr>


        </tbody>
      </table>
    </div>
    <div class="col-md-12" align="center"></div>
  </div>
	
<!-- Modal -->
<form action="/integracao/upload" method="post" enctype="multipart/form-data">
  @csrf
<div class="modal fade" id="modalUploadArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload</h4>
      </div>
      <div class="modal-body">
        <input type="text" name="job_id" id="job_id">
        <input type="file" name="arquivo" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>  
</form>    
@stop