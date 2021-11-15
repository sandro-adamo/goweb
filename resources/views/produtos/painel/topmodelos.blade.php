@extends('produtos/painel/index')


@php
$agrupamento = $_GET["agrupamento"];

 switch ($agrupamento) {
            case 'AH01 - ANA HICKMANN (SL)':
              $mala = '30';
              break;
            case 'AH02 - ANA HICKMANN (RX)':
              $mala = '50';
              break;
				case 'AT01 - ATITUDE (SL)':
              $mala = '90';
              break;
				case 'AT02 - ATITUDE (RX)':
              $mala = '90';
              break;
				case 'BG01 - BULGET (SL)':
              $mala = '50';
              break;
				case 'BG02 - BULGET (RX)':
              $mala = '90';
              break;
				case 'EV01 - EVOKE (SL)':
              $mala = '50';
              break;
				case 'EV02 - EVOKE (RX)':
              $mala = '50';
              break;
				case 'HI01 - HICKMANN (SL)':
              $mala = '50';
              break;
				case 'HI02 - HICKMANN (RX)':
              $mala = '50';
              break;
				case 'JO01 - JOLIE (SL)':
              $mala = '20';
              break;
				case 'JO02 - JOLIE (RX)':
              $mala = '30';
              break;
				case 'SP01 - SPEEDO (SL)':
              $mala = '70';
              break;

				case 'SP02 - SPEEDO (RX)':
              $mala = '70';
              break;
				case 'TC01 - T-CHARGE (SL)':
              $mala = '30';
              break;
				case 'TC02 - T-CHARGE (RX)':
              $mala = '50';
              break;

            default:
              $mala = '100';

          }

$query = DB::select("

select modelo1, colmod, ult_30dd, br,
case when ifnull(br,0)/ifnull(mdv_mensal,0) <='1' then '1'
when ifnull(br,0)/ifnull(mdv_mensal,0) >'1' and ifnull(br,0)/ifnull(mdv_mensal,0) <'3' then '2'
when ifnull(br,0)/ifnull(mdv_mensal,0) >'3' then '3'
							  else '0' end as 'etq',
case when (ifnull(mdv_mensal,0)/ifnull(ult_30dd,0)) <='0.8' then '1'
when (ifnull(mdv_mensal,0)/ifnull(ult_30dd,0)) >'0.8' and (ifnull(mdv_mensal,0)/ifnull(ult_30dd,0)) >'1.2' then '2'
when (ifnull(mdv_mensal,0)/ifnull(ult_30dd,0)) >='1.2' then '3'
							  else '0' end as 'vda',
	(select secundario from itens ss where ss.modelo = modelo1 limit 1) as secundario
							
from(

select grife, agrup, modelo as modelo1, clasmod, colmod, sum(ult_30dd) ult_30dd, sum(cont_disp) itens_disp, sum(br) br, sum(prod) prod, sum(alessandro) alessandro, sum(leonardo) leonardo,
 sum(mdv_mensal) mdv_mensal,  sum(a_180dd) a_180dd

	from _analise_mala  where agrup = '$agrupamento' 
	and clasmod_origem like 'linha%'
		
	group by grife, agrup, modelo, clasmod, colmod

	) as base
order by br desc
/*limit $mala*/

");
	




@endphp

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')



<div class="row">

    <div class="col-md-12">
    <span class="lead">ETQ
		</span>
		<span class="fa fa-circle text-red">Etq baixo</span>
		<span class="fa fa-circle text-green">Etq ok</span>
		<span class="fa fa-circle text-blue">Etq alto</span>
    
		
		 
    <span class="lead">VDA</span>
		<span class="fa fa-circle text-red">Vda baixa</span>
		<span class="fa fa-circle text-green">Vda ok</span>
		<span class="fa fa-circle text-blue">Vda acima</span>
    <div class="row">
      @foreach ($query as $dados) 

           @php switch ($dados->etq) {
            case '1':
              $cor = 'red';
              break;
            case '2':
              $cor = 'green';
              break;        
            default:
              $cor = 'blue';

          }
		
		switch ($dados->vda) {
            case '1':
              $cor1 = 'red';
              break;
            case '2':
              $cor1 = 'green';
              break;        
            default:
              $cor1 = 'blue';

          }
		@endphp
        

      <div class="col-md-2">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:3px; padding: 3px 4px 3px 4px;"> 
                                 
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($dados->secundario);
        @endphp
		<span class="pull-left"><small>{{ $dados->modelo1 }}</small></span>
         <span class="pull-right"><small>{{ $dados->colmod }}</small></span>
          
           <div id="foto" align="center" style="min-height: 180px; max-height: 180px;">
            <a href="" class="zoom" data-value="{{ $dados->secundario }}"><img src="/{{$foto}}" class="img-responsive"></a>
            
			<span class="pull-left text-{{$cor}}" > ETQ : {{ $dados->br }}</span>
			<span class="pull-right text-{{$cor1}}" > VDA : {{ $dados->ult_30dd }}</span>
       
          </div>
          
        </div>
      </div>
      @endforeach

  
    </div>
  </div>

</div>


@stop