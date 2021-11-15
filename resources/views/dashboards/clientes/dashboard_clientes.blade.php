@extends('layout.principal')

@php

$representantes = Session::get('representantes');

 $base = \DB::select("select distinct data_base from ds_carteira");

@endphp


@section('title')
<i class="fa fa-users"></i> Dashboard Clientes (data base {{$base[0]->data_base}})
@append 

@section('conteudo')



@php

$representantes = Session::get('representantes');
print_r($representantes);


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

$grifes3 = Session::get('grifes3');
print_r($grifes3);



$qtdegrifes1 = \DB::select(" 

select codgrife, count(cliente) clientes, sum(pdvs) pdvs, sum(q365) qtde 
from (
	select cliente, codgrife, count(codcli) pdvs, sum(v365) q365 
	from ds_carteira cart
	where v365 > 0 and rep_carteira in ($representantes)
	group by cliente, codgrife
) as fim1 group by codgrife	");
			  
			  


$grifecli = \DB::select("/**quantidade de pdvs por frequencia de grifes **/
				
    
select grifes, sum(clientes_365) cli_365, sum(clientes_180) as cli_180, sum(clientes_120) as cli_120 from (
	select c365 grifes, count(cliente) clientes_365, 0 as clientes_180, 0 as clientes_120
    from (

		select cliente, sum(c365) c365 from (
			select cliente, 
			case when v365 > 0 then count(codgrife) else 0 end as c365
				from (
				select cliente, codgrife, sum(v365) v365
				from ds_carteira cart
				where v365 > 0 and rep_carteira in ($representantes)
				-- and cliente = '100243 - N J DA SILVA OTICA ME' 
				group by cliente, codgrife
			) as fim group by cliente, codgrife
		) as fim2 group by cliente
	) as fim3 group by c365

union all

	select c365 grifes, 0 as clientes_365, count(cliente) clientes_180, 0 as clientes_120
    from (

		select cliente, sum(c365) c365 from (
			select cliente, 
			case when v365 > 0 then count(codgrife) else 0 end as c365
				from (
				select cliente, codgrife, sum(v365) v365
				from ds_carteira cart
				where v180 > 0 and rep_carteira in ($representantes)
				-- and cliente = '100243 - N J DA SILVA OTICA ME' 
				group by cliente, codgrife
			) as fim group by cliente, codgrife
		) as fim2 group by cliente
	) as fim3 group by c365

union all

	select c365 grifes, 0 as clientes_365, 0 clientes_180, count(cliente) as clientes_120
    from (

		select cliente, sum(c365) c365 from (
			select cliente, 
			case when v365 > 0 then count(codgrife) else 0 end as c365
				from (
				select cliente, codgrife, sum(v365) v365
				from ds_carteira cart
				where v120 > 0 and rep_carteira in ($representantes)
				-- and cliente = '100243 - N J DA SILVA OTICA ME' 
				group by cliente, codgrife
			) as fim group by cliente, codgrife
		) as fim2 group by cliente
	) as fim3 group by c365
) as fimg
group by grifes ");
		




$queryf = \DB::select("
		  select codgrife, sum(c7) c7, sum(c30) c30, sum(c120) c120, sum(c180) c180, sum(c365) c365, sum(ctotal) ctotal from (
	select codgrife, 
    case when v7 > 0 then 1 else 0 end as c7,
    case when v30 > 0 then 1 else 0 end as c30,
	case when v120 > 0 then 1 else 0 end as c120,
	case when v180 > 0 then 1 else 0 end as c180,
	case when v365 > 0 then 1 else 0 end as c365,
    case when vtotal > 0 then 1 else 0 end as ctotal
		from (
		select cliente, codgrife, sum(v7) v7,sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal
		from ds_carteira cart
		 where rep_carteira in ($representantes) 
		group by cliente, codgrife
	) as fim 
) as fim1 group by codgrife");
		  




    $queryd = \DB::select("
 	

select codgrife, sum(novo_7dd) novo_7dd, 
sum(novo_30dd) novo_30dd, sum(novo_120dd) novo_120dd, sum(novo_180dd) novo_180dd,  sum(fidelizados) fidelizados, sum(n_fidelizados) n_fidelizados, 
sum(recuperados) recuperados, sum(a_recuperar) a_recuperar ,
sum(pnovo_7dd) pnovo_7dd, sum(pnovo_30dd) pnovo_30dd, sum(pnovo_120dd) pnovo_120dd, sum(pnovo_180dd) pnovo_180dd, sum(pfidelizados) pfidelizados,
sum(pn_fidelizados) pn_fidelizados, sum(precuperados) precuperados, sum(pa_recuperar) pa_recuperar
	from (
		  
	select codgrife, 
		sum(v7) v7,sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal, sum(pnovo_7dd) pnovo_7dd, sum(pnovo_30dd) pnovo_30dd, 
        sum(pnovo_120dd) pnovo_120dd, sum(pnovo_180dd) pnovo_180dd, sum(pfidelizados) pfidelizados,
        sum(pn_fidelizados) pn_fidelizados, sum(precuperados) precuperados, sum(pa_recuperar) pa_recuperar,
		case when v7 > 0 and vtotal = v7 then count(cliente) else 0 end as novo_7dd,
		case when v30 > v7 and vtotal = v30 then count(cliente) else 0 end as novo_30dd,
        case when v120 > v30 and vtotal = v120 then count(cliente) else 0 end as novo_120dd,
        case when v180 > v120 and vtotal = v180 then count(cliente) else 0 end as novo_180dd,

		case when v180 > 0 and v365 > v180 then count(cliente) else 0 end as fidelizados,
		case when v365 > 0 and v180 = 0 then count(cliente) else 0 end as n_fidelizados,
        case when v180 = v365 and v180 < vtotal and v180 > 0 then count(cliente) else 0 end as recuperados, 
		case when vtotal > 0 and v365 = 0 then count(cliente) else 0 end as a_recuperar
		from (

		select cliente, codgrife,sum(v7) v7,sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal,
        sum(pnovo_7dd) pnovo_7dd, sum(pnovo_30dd) pnovo_30dd, sum(pnovo_120dd) pnovo_120dd, sum(pnovo_180dd) pnovo_180dd, sum(pfidelizados) pfidelizados,
        sum(pn_fidelizados) pn_fidelizados, sum(precuperados) precuperados, sum(pa_recuperar) pa_recuperar from (
        
			select cliente, codgrife, codcli,sum(v7) v7,sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal,
					case when v7 > 0 and vtotal = v7 then 1 else 0 end as pnovo_7dd,
					case when v30 > v7 and vtotal = v30 then 1 else 0 end as pnovo_30dd,
                    case when v120 > v30 and vtotal = v120 then 1 else 0 end as pnovo_120dd,			
					case when v180 > v120 and vtotal = v180 then 1 else 0 end as pnovo_180dd,
			
					case when v180 > 0 and v365 > v180 then 1 else 0 end as pfidelizados,
					case when v365 > 0 and v180 = 0 then 1 else 0 end as pn_fidelizados,
					case when v180 = v365 and v180 < vtotal and v180 > 0 then 1 else 0 end as precuperados, 
					case when vtotal > 0 and v365 = 0 then 1 else 0 end as pa_recuperar
			from ds_carteira cart
			where rep_carteira in ($representantes)  and flag_cadastro <> '1 - desativado' 
            
			group by cliente, codgrife, codcli , v7, v30, v120, v180, v365, vtotal
            
		) as fim0 group by cliente, codgrife

            
		) as fim
		group by codgrife, cliente
	) as fim1 group by codgrife");





$perfil = \DB::select(" 
 	select codgrife, sum(A) A, sum(B) B, sum(C) C, sum(D) D,  sum(E) E, sum(F) F
    from (
	
		select codgrife, sum(v365) v365, 
		case when perfil_365 = 'A' then count(cliente) else 0 end as A,
		case when perfil_365 = 'B' then count(cliente) else 0 end as B,		
        case when perfil_365 = 'C' then count(cliente) else 0 end as C,
        case when perfil_365 = 'D' then count(cliente) else 0 end as D,
        case when perfil_365 = 'E' then count(cliente) else 0 end as E,
        case when perfil_365 = 'F - DESCONSIDERAR' then count(cliente) else 0 end as F
        
			from (
		
			select cliente, codgrife, perfil_365, sum(v365) v365
			from ds_carteira cart
			where rep_carteira in ($representantes)  and flag_cadastro <> '1 - desativado' and v365 > 0 
			group by cliente, codgrife, perfil_365
            
		) as fim0 group by codgrife, cliente, perfil_365
        
	) as fim1 group by codgrife ");


			
$regiao = \DB::select(" 			


select uf, faixa_cep, count(municipio) municipios, sum(clientes) clientes, sum(pdvs) pdvs, sum(v120) v120, 
		sum(populacao) populacao, (sum(v120)/sum(populacao))*1000 pcs1000

from (
	select municipio, uf, min(faixa_cep) faixa_cep, count(cod_cliente) clientes, sum(pdvs) pdvs , sum(v120) v120, max(populacao) populacao
    from (
		
        select ab.municipio, ab.uf, populacao, left(ab.cep,2) faixa_cep, cart.cod_cliente, count(ab.id) pdvs, sum(v120) v120
		from ds_carteira cart
		left join addressbook ab on ab.id = cart.codcli
		left join ibge on ibge.municipio = ab.municipio and ab.uf = ibge.uf
		where rep_carteira in ($representantes) 
		group by ab.municipio, ab.uf, left(ab.cep,2) , cart.regiao, cart.cod_cliente, populacao
		order by faixa_cep         
        
	) as fim group by municipio, uf order by faixa_cep 
) as fim2 group by uf, faixa_cep		
");			
			
	echo $grifes;
			
@endphp
			

<!-- solid sales graph -->
<div class="box box-solid">
<div class="box-header">


<div class="col-md-6">
<div class="box box-widget">
<div class="box-header with-border">

<h3 class="box-title">Grifes de clientes atendidos da carteira ativa</h3>
		  				
<span class="pull-right" style="margin-left: 20px;"><a href="/dashboard/exportaExcel?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Mapa</a></span>
<span class="pull-right"><a href="/dashboard/exportaClientes?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Carteira</a></span>
				
      </div>

      <div class="box-body">
        <div class="row">
          <div class="col-md-7">

        
              @php

                $total = count($qtdegrifes1);
                $index = 0;

              @endphp
			  
			  

              <table class="table table-bordered table-condensed" style="font-size: 11px";>
				<tr>Vendas dos ultimos 12 meses</tr>
                <tr>
                  <th></th>
                  <th style="text-align: center">Clientes</th>
				  <th style="text-align: center">PDVs</th>
                  <th style="text-align: center">Peças</th>
                  <th></th>
                  <th style="text-align: center">Clientes</th>
				  <th style="text-align: center">PDVs</th>
                  <th style="text-align: center">Peças</th>
                </tr>
              @foreach ($qtdegrifes1 as $a)

                @if ($index == 0)
                  <tr>
                @elseif ($index == 2) 
                  </tr>
                
                  @php
                    $index = 0;
                  @endphp
                  
                @endif 

                  <td align="center" class="text-bold"><a href="/cliente_det?codgrife={{$a->codgrife}}">{{$a->codgrife}}</a></td>
                  <td align="center">{{number_format($a->clientes,0, '.','.')}}</td>
				  <td align="center">{{number_format($a->pdvs,0, '.','.')}}</td>
                  <td align="center">{{number_format($a->qtde,0, '.','.')}}</td>
                
                @php
                  $index++;
                @endphp
                
              @endforeach
              </table>
          </div>
	  
	  
          <div class="col-md-4">  
            <table class="table table-bordered table-condensed" style="font-size: 11px;">
              <tr>
                <td>Grifes</td>
                <td>Cli 120dias</td>
                <td>Cli 180dias</td>
				<td>Cli 365dias</td>
              </tr>         

              @php
                $grifes18 = 0;
                $grifes12 = 0;
				$grifes365 = 0;
              @endphp


              @foreach ($grifecli as $a)

                @php
                  $cor = '';
                  if ($a->grifes <= 3) {
                    $cor = 'bg-red';
                  } elseif ($a->grifes > 3 and $a->grifes <=5) {
                    $cor = 'bg-yellow';
                  } elseif ($a->grifes > 5) {
                    $cor = 'bg-green';
                  }

                  $grifes12 += $a->cli_120;
                  $grifes18 += $a->cli_180;
				  $grifes365 += $a->cli_365;

                @endphp

                <tr>
                  <td align="center" class="{{$cor}}">{{$a->grifes}}</td>
                  <td align="center"><a href="/cliente_grifes?faixa={{$a->grifes}}&tipo=v120">{{$a->cli_120}}</a></td>
                  <td align="center"><a href="/cliente_grifes?faixa={{$a->grifes}}&tipo=v180">{{$a->cli_180}}</a></td>
				  <td align="center"><a href="/cliente_grifes?faixa={{$a->grifes}}&tipo=v365">{{$a->cli_365}}</a></td>
                </tr>      
              @endforeach     

              <tr>
                <td align="center" class="text-bold">TOTAL</td>
				<td align="center" class="text-bold">{{$grifes12}}</td>
                <td align="center" class="text-bold">{{$grifes18}}</td>
				<td align="center" class="text-bold">{{$grifes365}}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div> 

           
			
<div class="col-md-5">
		<div class="box box-widget">		
		<div class="table-responsive">

        <table class="table table-bordered table-condensed" id="example3" style="font-size: 11px" align="center">
				<thead>
		 		<tr>	<td colspan="7">Clientes que compraram no ultimo periodo</td> </tr>
		  			
					<tr>	
					
					<td colspan="1" align="center">codgrife</td>
					<td colspan="1" align="center">0-7 dias</td>
					<td colspan="1" align="center">0-30 dias</td>
					<td colspan="1" align="center">0-120 dias</td>
					<td colspan="1" align="center">0-180 dias</td>
					<td colspan="1" align="center">0-365 dias</td>
					<td colspan="1" align="center">total</td>
  </thead>
					</tr>
			
			@foreach ($queryf as $c)
			  
				<tr>
					
					<td align="left">{{$c->codgrife}}</td>
					<td align="center">{{$c->c7}}</td>
					<td align="center">{{$c->c30}}</td>
					<td align="center">{{$c->c120}}</td>
					<td align="center">{{$c->c180}}</td>
					<td align="center">{{$c->c365}}</td>
					<td align="center">{{$c->ctotal}}</td>

				</tr>
			@endforeach 			 
			</table>
			
				</div>
			</div>
		</div>
    </div>  
	
	
	<div class="col-md-7">
		<div class="box box-widget">		
		<div class="table-responsive">
Status comercial clientes (sem clientes desativados)
        <table class="table table-bordered table-condensed" id="example3" style="font-size: 11px" align="center">
				<thead>
			
			<tr> <td colspan="1">Grifes</td> 
					
			<td colspan="2">Novos 0-7 dias</td><td colspan="1"></td>
			<td colspan="2">Novos 7-30 dias</td><td colspan="1"></td>
			<td colspan="2">Novos 30-120 dias</td><td colspan="1"></td>
			<td colspan="2">Novos 120-180 dias</td><td colspan="1"></td>
			<td colspan="2" class="text-green">Fidelizados</td><td colspan="1"></td>
			<td colspan="2" class="text-blue">Recuperados</td><td colspan="1"></td>
			<td colspan="2" class="text-yeallow">Nao Fidelizados</td><td colspan="1"></td>
			<td colspan="2" class="text-red">A recuperar</td>
			</tr>
					
			<tr>
            <td align="center">grife</td>
			<td align="center">Cli</td>
			<td align="center">Pdvs</td>
			<td></td>
			<td align="center">Cli</td>
			<td align="center">Pdvs</td>
			<td></td>
			<td align="center">Cli</td>
			<td align="center">Pdvs</td>
			<td></td>
			<td align="center">Cli</td>
			<td align="center">Pdvs</td>
			<td></td>				
            <td align="center" class="text-green">Cli</td>
			<td align="center" class="text-green">Pdvs</td>
			<td></td>
			<td align="center" class="text-blue">Cli</td>
			<td align="center" class="text-blue">Pdvs</td>
			<td></td>
			<td align="center" class="text-yellow">Cli</td>
			<td align="center" class="text-yellow">Pdvs</td>
			<td></td>
			<td align="center" class="text-red">Cli</td>
			<td align="center" class="text-red">Pdvs</td>

		</thead>
        </tr>
				
          @foreach ($queryd as $a)

            <tr>
			   	<td align="center">{{$a->codgrife}}</td>
				
				<td align="center">{{$a->novo_7dd}}</td>
				<td align="center">{{$a->pnovo_7dd}}</td>
				<td></td>
				<td align="center">{{$a->novo_30dd}}</td>
				<td align="center">{{$a->pnovo_30dd}}</td>
				<td></td>
				<td align="center">{{$a->novo_120dd}}</td>
				<td align="center">{{$a->pnovo_120dd}}</td>
				<td></td>
				<td align="center">{{$a->novo_180dd}}</td>
				<td align="center">{{$a->pnovo_180dd}}</td>
				<td></td>
				<td align="center">{{$a->fidelizados}}</td>
				<td align="center">{{$a->pfidelizados}}</td>
				<td></td>
				<td align="center">{{$a->recuperados}}</td>
				<td align="center">{{$a->precuperados}}</td>
				<td></td>
				<td align="center"><a href="/cliente_statusdet?statuscli=Nao_Fidelizados&codgrife={{$a->codgrife}}">{{$a->n_fidelizados}}</a></td>
				<td align="center">{{$a->pn_fidelizados}}</td>
				<td></td>
				<td align="center"><a href="/cliente_statusdet?statuscli=A_Recuperar&codgrife={{$a->codgrife}}">{{$a->a_recuperar}}</a></td>
				<td align="center">{{$a->pa_recuperar}}</td>

                </tr>
          @endforeach 
        </table>
        </h6>
			</div>
		</div>
	</div>	
	
	
			
<div class="col-md-5">
		<div class="box box-widget">		
		<div class="table-responsive">

        <table class="table table-bordered table-condensed" id="example3" style="font-size: 11px" align="center">
				<thead>
			
			<tr> <td colspan="8">Status comercial clientes (sem clientes desativados)</td> </tr>

			<tr>
            <td align="center">grife</td>
            <td align="center" class="text-green">Perfil A</td>
            <td align="center" class="text-blue">Perfil B</td>
			<td align="center">Perfil C</td>
            <td align="center">Perfil D</td>
            <td align="center" class="text-red">Perfil E</td>
			<td align="center" class="text-red">Perfil F</td>
		
		</thead>
        </tr>
				
          @foreach ($perfil as $a)

            <tr>
			   	<td align="center">{{$a->codgrife}}</td>
				<td align="center">{{$a->A}}</td>
				<td align="center">{{$a->B}}</td>
				<td align="center">{{$a->C}}</td>
				<td align="center">{{$a->D}}</td>
				<td align="center">{{$a->E}}</td>
				<td align="center">{{$a->F}}</td>
			

                </tr>
          @endforeach 
        </table>
        </h6>
			</div>
		</div>
	</div>



	
			
<div class="col-md-5">
		<div class="box box-widget">		
		<div class="table-responsive">

        <table class="table table-bordered table-condensed" id="example3" style="font-size: 11px" align="center">
				<thead>
			
			<tr> <td colspan="8">Status comercial clientes (sem clientes desativados)</td> </tr>

			<tr>
			<td align="center">uf</td>
            <td align="center">faixa_cep</td>
            <td align="center">municipios</td>
			<td align="center">clientes</td>	
			<td align="center">pdvs</td>
			<td align="center">qtde_120dd</td>
			<td align="center">populacao</td>
		 	<td align="center">pcs/1000 hab</td>
			<td align="center">a recuperar</td>
			
		
		</thead>
        </tr>
				
          @foreach ($regiao as $a)

            <tr><td align="center">{{$a->uf}}</td>
			   	<td align="center"><a href="/cliente_regiao?faixa_cep={{$a->faixa_cep}}">{{$a->faixa_cep}}</a></td>
				<td align="center">{{$a->municipios}}</td>
				<td align="center">{{$a->clientes}}</td>
				<td align="center">{{$a->pdvs}}</td>
				<td align="center">{{number_format($a->v120,0)}}</td>
				<td align="center">{{number_format($a->populacao,0)}}</td>
				<td align="center">{{number_format($a->pcs1000,2)}}</td>
				
			

                </tr>
          @endforeach 
        </table>
        </h6>
			</div>
		</div>
	</div>

	
			<!-- /.box-body -->
							<div class="box-footer no-border">
							  <div class="row">
								
								<!-- ./col -->
							  </div>
							  <!-- /.row -->
							</div>
            <!-- /.box-footer -->
	
	
	
</div>
<!-- /.box -->
 			


@stop

	
	