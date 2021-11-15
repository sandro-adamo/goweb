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
             <th width="10%">modelo</th>
			 <th width="10%">item</th>
             <th width="10%">qtde</th>
				<th width="10%">atende</th>
				<th width="10%">nao atende</th>

				
           </tr>
         </thead>
         <tbody>

          @foreach ($orcamentos as $orcamento)

          @if (trim($orcamento->qtde) == '0')
            <tr style="color:red;">
          @else
            <tr style="color:black;">
          @endif
            
              <td width="4%" align="center">{{$orcamento->modelo}}</td>
			  <td width="4%" align="center"><a href="/orc_item/{{$orcamento->id_item}}?fornec={{$orcamento->id_item}}">{{$orcamento->item}}</a></td>
		    <td width="4%" align="center">{{$orcamento->qtde}}</td>
				<td width="4%" align="center">{{$orcamento->atende}}</td>
				<td width="4%" align="center">{{$orcamento->nao_atende}}</td>
            </tr>

            @endforeach

          </tbody>
        </table>
      </h6>
    </div>
  </div>
</form>
@stop