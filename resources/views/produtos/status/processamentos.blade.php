@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Processamento de Status
  

   
@append 

@section('conteudo')


	<a href="/integracao/processa/atualiza" class="btn btn-flat btn-default"><i class="fa fa-gears"></i> Processar1</a>


<div class="box box-body box-widget">
<table class="table table-bordered table-striped">
  @foreach ($processamentos as $processa)
    <tr>
    	<td><a href="/produtos/status/processamentos/{{ $processa->processamento }}">{{ $processa->processamento }}</a></td>
    	<td>{{ $processa->data }}</td>
    </tr>
  @endforeach
</table>
</div>



@stop