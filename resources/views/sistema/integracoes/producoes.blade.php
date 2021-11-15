@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Producoes
@append 

@section('conteudo')


	


<div class="box box-body box-widget">
<table class="table table-bordered table-striped">
  @foreach ($producoes as $producao)
    <tr>
    	<td><a href="/integracao/producoes/{{ $producao->numero }}">{{ $producao->numero }}</a></td>
    	<td>{{ $producao->data1 }}</td>
		<td>{{ $producao->qtd }}</td>
    	<td width="10%" align="center"><a href="/integracao/producoes/{{ $producao->numero }}/excluir" class="text-red"><i class="fa fa-trash"></i> Excluir</a></td>
    </tr>
  @endforeach
</table>
</div>

@stop