@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Malas {{$item}}
@append 
  
@section('conteudo')

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
       
       
.
      </div>      
      <br>
       <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
          @if($tipo=='modelo')
            <th>Item</th>
          @endif
			  <th>Id rep</th>
			  <th>Filial</th>
            <th>Nome</th>
            <th>Mala</th>
            <th>Solicitado</th>
			<th>Em análise</th>
             </tr>
        </thead>
        <tbody>
          @foreach ($mala as $malas)
          <tr>
			  @if($tipo=='modelo')
            <td> {{$malas->item}}</td>
          @endif
			  <td> {{$malas->id_rep}}</td>
            <td> {{$malas->filial}}</td>
            <td>{{$malas->nome}}</td>
            <td>{{$malas->mala}}</td>
			<td>{{$malas->solicitado}}</td>
			 <td>{{$malas->em_analise}}</td>
            
           

          </tr>  
            @endforeach
        </tbody>
      </table>
    </div>
</div>






@stop