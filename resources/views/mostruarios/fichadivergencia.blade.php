@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Mostruários
@append 

@section('conteudo')

@php
  $id_usuario = \Auth::id();
  $id_usuario = \Auth::user()->id_addressbook;

$representantes = Session::get('representantes');

$status = $_GET["status"];



$query_0 = \DB::select("
select *
from(
select itens.agrup, itens.secundario item, descricao, colmod, valortabela as preco, tamolho,
case when codultstatus in ('DIS', '15D', '30D') and codstatusatual in ('DIS', '15D', '30D') then 'manter_venda'
when codultstatus in ('DIS', '15D', '30D') and codstatusatual in ('esg', 'pro') then 'tirar_venda'
when codultstatus in ('esg', 'prod') and codstatusatual in ('DIS', '15D', '30D') then 'retornar_venda' 
when codultstatus in ('esg', 'pro') and codstatusatual in ('esg', 'pro') then 'manter_fora' 

else 'o_outro' end as acao, 
statusatual, date(datastatusatual) as dt_statusatual, ultstatus, date(dataultstatus) as dt_ultstatus
from malas

left join itens on malas.id_item = itens.id

WHERE malas.id_rep in ($representantes)

and malas.local = 'mala'
) as base1
where acao = '$status' order by agrup, item");






@endphp
 

		 <div class="col-md-10">
      	

			<table class="table table-condensed table-bordered">


				
				
		<tbody>
			@foreach ($query_0 as $query0)
          <tr>
             <td id="foto" align="center" style="min-height:160px;">
               
                <a href="" class="zoom" data-value="{{$query0->item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$query0->item}}" style="max-height: 160px;" class="img-responsive"></a>
                
              </td>
            
            <td align="center">{{$query0->agrup}}</td>
            <td align="center">{{$query0->item}}</td>
		
           
            

          </tr>  
            @endforeach
        </tbody>
				
				

			</table>

	</div>


    @stop