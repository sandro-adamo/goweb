@extends('layout.principal')
@php

  function getMes($mes) {
    $mes2 = '';

    switch($mes) {
        case 1:
          $mes2 = 'Janeiro';
          break;
        case '2':
          $mes2 = 'Fevereiro';
          break;
        case '3':
          $mes2 = 'Março';
          break;
        case '4':
          $mes2 = 'Abril';
          break;
        case '5':
          $mes2 = 'Maio';
          break;
        case '6':
          $mes2 = 'Junho';
          break;
        case '7':
          $mes2 = 'Julho';
          break;
        case '8':
          $mes2 = 'Agosto';
          break;
        case '9':
          $mes2 = 'Setembro';
          break;
        case '10':
          $mes2 = 'Outubro';
          break;
        case '11':
          $mes2 = 'Novembro';
          break;
        case '12':
          $mes2 = 'Dezembro';
          break;

        default:
          $mes2 = 'nao definido';
    }

    return $mes2;

  }

  if (isset($_GET["periodo"])) {

    $periodo = explode('-', $_GET["periodo"]);

    $ano2 = $periodo[1];
    $mes2 = $periodo[0];   


  } else {

    $ano2 = date('Y');
    $mes2 = date('m');

  }

@endphp


@section('title', 'Dashboard')

@section('conteudo')

@if  ( \Auth::user()->id_perfil <> 23)
<form action="" method="get" class="form-horizontal">
  <div class="row ">

    <div class="col-md-2 col-md-offset-9">
        <select name="periodo" id="periodo" class="form-control">
  
          @for ($ano=2021;$ano>=2018;$ano--) {
  
            @if ($ano == date('Y'))
  
              @for ($mes=date('m');$mes>=1;$mes--) 
                <option value="{{$mes}}-{{$ano}}" @if ($ano == $ano2 and $mes == $mes2) selected="" @endif>{{getMes($mes)}} {{$ano}}</option>
              @endfor
  
            @else
  
              @for ($mes=12;$mes>=1;$mes--) 
                <option value="{{$mes}}-{{$ano}}" @if ($ano == $ano2 and $mes == $mes2) selected="" @endif>{{getMes($mes)}} {{$ano}}</option>
              @endfor
            @endif
  
          @endfor
  
        </select>
  
    </div>
    <div class="col-md-1">
      <button type="submit" class="btn btn-flat btn-default btn-block">Pesquisar</button>
                    
    </div>
  </div>
  </form>
  
@php

$representantes = Session::get('representantes');
$id_representante = \Auth::user()->id_addressbook;
$id_perfil = \Auth::user()->id_perfil;
$id_perfil1 = \Auth::user()->id;








 if($representantes=='95276666' or \Auth::user()->id=='3' or \Auth::user()->id=='488') { 
}
else{
@endphp

@if (\Auth::user()->id_perfil == 12 or \Auth::user()->id_perfil == 16) 

@else

@php
      if (\Auth::viaRemember()) {
        echo 'teste1';
      }

      if (Auth::check()) {
        //echo 'teste2';
      }
      if (Auth::viaRemember()) {
        echo 'teste3';
      }


        $sql = '';

$representantes = Session::get('representantes');
// print_r($representantes);


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

$grifes3 = Session::get('grifes3');
print_r($grifes3);



   if ($id_perfil == '1' or $id_perfil == '2') {
    $representante = '';
    }
    else{
    $representante = 'id_rep in ('.$representantes.') and';
    }
@endphp




<div class="row">
  <div class="col-md-12">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>

			<span class="pull-right" style="margin-left: 20px;"><a href="/dashboard/exportaExcel?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Mapa</a></span>
			<span class="pull-right"><a href="/dashboard/exportaClientes?id={{\Auth::user()->id_addressbook}}" class="pull-right"><i class="fa fa-file-o"></i> Exporta Carteira</a></span>

		  </div>
		</div>	  
	</div>


@if($representantes=='91537666' or $id_perfil1 == 3791 or \Auth::user()->id_perfil == 5 ) 
	
@php	


$vendas = array();

$vendas = \DB::select("

	-- identifica primeiro pedido e orcamentos    
	select ano, mes, -- vendas, -- id_rep, codgrife, financeiro,  
			sum(venda_total) venda_total, sum(venda_aberto) venda_aberto, sum(venda_pedido)-sum(vlr_so_cancelado) venda_pedido, 
            sum(venda_orcamento)+sum(vlr_so_cancelado) venda_orcamento,
            
            sum(vlr_pedido)-sum(vlr_so_cancelado) vlr_pedido,
            case when sum(atendimentos) < sum(vlr_so_cancelado) then sum(primeiro_ped) - sum(vlr_so_cancelado) else sum(primeiro_ped) end as primeiro_ped, 
            case when sum(atendimentos) >= sum(vlr_so_cancelado) then sum(atendimentos) - sum(vlr_so_cancelado) else sum(atendimentos) end as atendimentos,
            
            -- so
            sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_so_faturado) vlr_so_faturado
            
        
		
	from (
    
		select * from (
			select vendas, ano, mes, id_rep, codgrife, financeiro, 
	
			sum(vlr_total) venda_total, sum(vlr_total)-sum(vlr_venda_ped)-sum(vlr_venda_orcamento) venda_aberto, sum(vlr_venda_ped) venda_pedido, sum(vlr_venda_orcamento) venda_orcamento, 
			ifnull(sum(valor),0) vlr_pedido, ifnull(sum(vlr_prim),0) primeiro_ped, ifnull(sum(valor)-sum(vlr_prim),0) atendimentos,
            -- so
            ifnull(sum(vlr_so_cancelado),0) vlr_so_cancelado, ifnull(sum(vlr_so_faturado),0) vlr_so_faturado, ifnull(sum(vlr_so_aberto),0) vlr_so_aberto
			from (
			 
				select * from (        
						select ano, mes, codgrife, id_rep, vendas, financeiro, 
						
						sum(valor) vlr_total, sum(vlr_venda_aberto) vlr_venda_aberto, sum(vlr_venda_ped) vlr_venda_ped, sum(vlr_venda_orcamento) vlr_venda_orcamento, sum(vlr_venda_canc) vlr_venda_canc
						from (
							
							select year(dt_venda) ano, month(dt_venda) mes, codgrife, id_rep, pedido vendas, valor, financeiro,
								
                                case when concat(vds.ult_status,'/',vds.prox_status) in ('500/505','505/510','510/512') then vds.valor else 0 end as vlr_venda_aberto,		  
								case when concat(vds.ult_status,'/',vds.prox_status) in ('510/999','512/999','515/999','516/999') then vds.valor else 0 end as vlr_venda_ped,
								case when vds.prox_status in ('515','516') then vds.valor else 0 end as vlr_venda_orcamento,
								case when vds.ult_status in ('980','984') then vds.valor else 0 end as vlr_venda_canc
								
							from vendas_jdes vds
							left join addressbook ab on ab.id = vds.id_cliente
							where id_rep in ($representantes) and vds.ult_status not in ('980','984') 
							and year(dt_venda)= $ano2 and month(dt_venda) = $mes2
							-- and dt_venda >= CONCAT(YEAR(DATE_ADD(NOW(), INTERVAL -2 MONTH)), '-', MONTH(DATE_ADD(NOW(), INTERVAL -2 MONTH)), '-01')

							
						) as fim0
						group by ano, mes, id_rep, vendas, financeiro, codgrife
				) as fim


				left join (
				
					select  grife, ped_original, 
                    sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_prim) vlr_prim
					from (
						select codgrife grife, ped_original, num_pedido, emissao, dt_prim, 
                        sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, 
                        sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto,
						case when dt_prim = emissao then sum(valor) else 0 end as vlr_prim
						from (

							select codgrife, pedido num_pedido, ped_original, dt_emissao emissao, valor,                            
								-- case when ped.ult_status in ('980','984') then valor else 0 end as vlr_so_cancelado,
                                case when ped.ult_status in ('902','904','912','914') then valor else 0 end as vlr_so_cancelado,
                                
								case when ped.ult_status like '6%' then valor else 0 end as vlr_so_faturado,
								case when (ped.ult_status like '5%') then valor else 0 end as vlr_so_aberto,
 								(select min(ped0.dt_emissao) from pedidos_jdes ped0 where ped0.ped_original = ped.ped_original) as dt_prim
								
								from pedidos_jdes ped
								where id_rep in ($representantes) and ped.ult_status not in ('980','984')
														
                            
						) as fim
						group by codgrife, ped_original, num_pedido, emissao, dt_prim
					) as fim2 group by grife, ped_original
				
				) as final 
				on final.ped_original = vendas and final.grife = fim.codgrife
				
			) as final1
		group by vendas, ano, mes, id_rep, codgrife, financeiro
		) as final


) as final2
group by ano, mes -- , vendas

	
");
	

@endphp
	
<div class="col-md-12">
		
<h2 class="page-header">Representante</h2>

      <div class="row">
        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
              <div class="widget-user-image">
             <!--    <img class="img-circle" src="../dist/img/user7-128x128.jpg" alt="User Avatar"> -->
              </div>
              <!-- /.widget-user-image -->
              <h5 class="widget-user-username">Total Vendido do Mes  
				  @if($id_perfil == 5)
				  <a href="/comercial_rep?ano=2021&mes=06"><i class="fa fa-users"></i></a>@endif
			  </h5>
				
              <h3 class="widget-user-desc">@if ($vendas) {{number_format($vendas[0]->venda_total,2,',','.')}} @endif</h3>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
      
				@if($id_perfil == 4) <a href=""><i class="fa fa-users"></i></a>@endif
                <li>
					<a href="#">Faturados <span class="pull-right badge bg-green">
					@if ($vendas) {{number_format($vendas[0]->vlr_so_faturado,2,',','.')}} @endif</span></a>
				</li>
				
				@if($id_perfil == 4) <a href=""><i class="fa fa-users"></i></a>@endif  
                <li><a href="#">Em processo<span class="pull-right badge bg-aqua">
					@if ($vendas) {{number_format($vendas[0]->vlr_so_aberto,2,',','.')}} @endif</span></a>
				</li>
			
				@if($id_perfil == 4) <a href=""><i class="fa fa-users"></i></a>@endif  
				<li><a href="#">Orcamento do mes <span class="pull-right badge bg-orange">
					@if ($vendas) {{number_format($vendas[0]->venda_orcamento,2,',','.')}} @endif</span></a>
				</li>
				  
				@if($id_perfil == 4) <a href=""><i class="fa fa-users"></i></a>@endif
                <li><a href="/bloqueados_det?ano={{date('Y')}}&mes={{date('m')}}" class="small-box-footer">
				Bloqueados <span class="pull-right badge bg-orange">0</span></a>
				</li>
				 
				@if($id_perfil == 4) <a href=""><i class="fa fa-users"></i></a>@endif 
				<li>
					  <a href="/bloqueados_det?ano={{date('Y')}}&mes={{date('m')}}" class="small-box-footer">Cancelados 
					  <span class="pull-right badge bg-red">0</a></span>	   
				</li> 
				 
				  
              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
              <h5 class="widget-user-username">Pedidos Gerados no Mês</h5>
              <h3 class="widget-user-desc">union all com faturamentos se pedidos</h3>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">3,200</h5>
                    <span class="description-text">Faturados</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">13,000</h5>
                    <span class="description-text">Em processo</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">Bloqueados</span>
					  
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
		  
		  
		  
		  
		  
        <!-- /.col -->
        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('../dist/img/photo1.png') center center;">
              <h3 class="widget-user-username">Orcamentos</h3>
              <h5 class="widget-user-desc">ddd</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="../dist/img/user3-128x128.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">3,200</h5>
                    <span class="description-text">Adimplentes</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">13,000</h5>
                    <span class="description-text">Inadimplentes</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">Boleto Minimo</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <!-- /.col -->
		  
		  
		  
		  <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('../dist/img/photo1.png') center center;">
              <h3 class="widget-user-username">Boletos</h3>
              <h5 class="widget-user-desc">ddd</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="../dist/img/user3-128x128.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">3,200</h5>
                    <span class="description-text">Pagos</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header">13,000</h5>
                    <span class="description-text">Vencidos</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">A vencer</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
		  
		  
		  
      </div>
      <!-- /.row -->
	
	
	</div>	
  	@endif
	
</div>	
@endif
	

@stop

@php
}
@endphp

@endif


