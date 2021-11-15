@extends('layout.principal')

@section('title')
<i class="fa fa-file-o"></i> Meus Orçamentos
@append 

@section('conteudo')


<form action="/backorder/atende" method="post">
  @csrf
<div class="row">
  <div class="col-md-6">
    <div class="box box-widget box-body">

      <h6>
        <table class="table table-striped table-bordered compact" id="myTable">
          <thead>
            <tr>
			 <th width="10%">item</th>
             <th width="10%">qtde</th>
			 <th width="10%">atende</th>
			 <th width="10%">nao atende</th>

				
           </tr>
         </thead>
         <tbody>

          @foreach ($itens as $itens)

          @if (trim($itens->saldo) == '0')
            <tr style="color:red;">
          @else
            <tr style="color:black;">
          @endif
            
            <td width="4%" align="center">{{$itens->modelo}}</td>
			<td width="4%" align="center"><a href="produtos/orcamento/{{$itens->modelo}}?fornec={{$itens->modelo}}">{{$itens->curto}}</a></td>
		    <td width="4%" align="center">{{$itens->item}}</td>
			<td width="4%" align="center">{{$itens->saldo}}</td>
				
            </tr>

            @endforeach

          </tbody>
        </table>
      </h6>
    </div>
  </div>
</form>
@stop