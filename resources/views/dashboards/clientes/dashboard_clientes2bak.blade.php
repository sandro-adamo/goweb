\@extends('layout.metronic')

@php

$representantes = Session::get('representantes');

$base = \DB::select("select distinct data_base from ds_carteira");


$representantes = Session::get('representantes');
print_r($representantes);


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

$grifes3 = Session::get('grifes3');
print_r($grifes3);



$qtdegrifes1 = \DB::select(" 


select codgrife, count(cliente) clientes_carteira, sum(codcli_subgrupo) pdvs_carteira, 
sum(cliente_compra) clientes_compra,  sum(codcli_compra) pdvs_compra, sum(codcli_cliente_compra) pdvs_cliente_compra 
from (	
    select codgrife, cliente, cod_cliente,
    case when codcli_compra > 0 then 1 else 0 end as cliente_compra, codcli_subgrupo, codcli_compra, 
    case when codcli_compra > 0 then codcli_subgrupo else 0 end as codcli_cliente_compra from (		
        select codgrife, cod_cliente, cliente, count(codcli) codcli_subgrupo, sum(v365) codcli_compra from (			
         
			select distinct codgrife, cliente, cod_cliente, codcli , case when v365 > 0 then 1 else 0 end as v365, v365 qtde
			from ds_carteira 
            where rep_carteira in ($representantes)
			and flag_cadastro = '0 - ATIVO' limit 1
			
        ) as fim group by codgrife, cliente, cod_cliente
	) as fim1     
) as fim2 group by codgrife

");
			  
			
@endphp


@section('conteudo')
<form action="" method="get">
<div class="row">
  <div class="col-md-12">
    <div class="card box-widget card-body">
      <div class="row"> Cliente da carteira x compras ultimos 365 dias
  
      
      </div>
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
                  <th>grife</th>
                  <th style="text-align: center">clientes_carteira</th>
				  <th style="text-align: center">pdvs_carteira</th>
                  <th style="text-align: center">clientes_compra</th>
                  <th style="text-align: center">pdvs_compra</th>
				  <th style="text-align: center">pdvs_cliente_compra</th>
				  </tr>
              @foreach ($qtdegrifes1 as $a)

       <tr>

                  <td align="center" class="text-bold"><a href="/cliente_det?codgrife={{$a->codgrife}}">{{$a->codgrife}}</a></td>
                  <td align="center">{{number_format($a->clientes_carteira,0, '.','.')}}</td>
				  <td align="center">{{number_format($a->pdvs_carteira,0, '.','.')}}</td>
                  <td align="center">{{number_format($a->clientes_compra,0, '.','.')}}</td>
				  <td align="center">{{number_format($a->pdvs_compra,0, '.','.')}}</td>
				  <td align="center">{{number_format($a->pdvs_cliente_compra,0, '.','.')}}</td> 
     
		   </tr>
		      @endforeach
      </tbody>
      </table>
    </div>
    <div class="col-md-12" align="center"></div>
  </div>
	</form>
</div>







</html>

<script src="https://unpkg.com/blip-chat-widget" type="text/javascript">
</script>
<script>
    (function () {
        window.onload = function () {
            new BlipChat()
            .withAppKey('dmVyc2FvMzo1MGY2OWY1NS0xMmZhLTQzYWMtOTg2Yi0xZDZkZmYzNmRmNTA=')
            .withButton({"color":"#2CC3D5","icon":""})
            .withCustomCommonUrl('https://chat.blip.ai/')
            .build();
        }
    })();
</script>
@stop