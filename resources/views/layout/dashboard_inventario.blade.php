@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Dashboard inventario
@append 

@section('conteudo')

@php

    if (isset($_GET["inicio"]) && isset($_GET["fim"])) {
      $inicio = $_GET["inicio"];
      $fim = $_GET["fim"];

    } else {
      $inicio = date("Y-m-d");
      $fim = date("Y-m-d");

    }
    
    $inventario_iniciado = \DB::select("select count(id_inventario) as qtd_inventario, sum(qtd_pecas) qtd_pecas
		from(select id_inventario, count(id) qtd_pecas
		from inventarios
		where status = 'aberto'
		and exclui = 1
		group by id_inventario) as base
			");

	$devolucao_aberto = \DB::select("select count(id) as qtd_inventario, sum(qtd) qtd_pecas
		from(select id, (select count(id) from inventarios where inventarios.id_devolucao = goweb.devolucoes.id) as qtd
			from goweb.devolucoes
			where situacao = 'aberta'
			and tipo = 'mostruario'
			


			group by id
		 ) as base
where qtd <> 0");

	$devolucao_errada = \DB::select("select count(id) as qtd_inventario, sum(qtd) qtd_pecas
		from(select id, (select count(id) from inventarios where inventarios.id_devolucao = goweb.devolucoes.id) as qtd
			from goweb.devolucoes
			where situacao = 'aberta'
			and tipo = 'mostruario'
			


			group by id
		 ) as base
where qtd = 0");

$devolucao_aguardando_postagem = \DB::select("select count(id) as qtd_inventario, sum(qtd) qtd_pecas
		from(select id, (select count(id) from inventarios where inventarios.id_devolucao = goweb.devolucoes.id) as qtd
			from goweb.devolucoes
			where situacao = 'Aguardando Postagem'
			and tipo = 'mostruario'
			


			group by id
		 ) as base
");
	 $atualizacao_aberto = \DB::select("select count(id_inventario) as qtd_inventario, sum(qtd_pecas) qtd_pecas
		from(select id_inventario, count(id) qtd_pecas
		from inventarios
		where status = 'finalizado'
		and exclui = 1
		and acao = 'devolver'
		and id_devolucao is null 
		group by id_inventario) as base
			");
	


@endphp
<!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-refresh"></i></span>

            <div class="info-box-content">
				<span class="info-box-text">Inventario iniciado</span>
              <span class="info-box-number">Inventarios: {{$inventario_iniciado[0]->qtd_inventario}}</span>
              <span class="info-box-number"><small>Peças: {{number_format($inventario_iniciado[0]->qtd_pecas, 0)}} </small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div></div>
	
<h3>Devolução</h3>
 <div class="row">
		  

        <!-- /.col -->
        <div class="col-md-3 col-xs-8 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-hourglass-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Em aberto</span>
              <span class="info-box-number">Devoluções: {{$devolucao_aberto[0]->qtd_inventario}}</span>
              <span class="info-box-number"><small>Peças: {{number_format($devolucao_aberto[0]->qtd_pecas, 0)}} 
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
        <div class="col-md-3 col-xs-8 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Errada</span>
              <span class="info-box-number">Devoluções: {{$devolucao_errada[0]->qtd_inventario}}</span>
              <span class="info-box-number"><small>Peças: {{number_format($devolucao_errada[0]->qtd_pecas, 0)}} 
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
			  
			  <!-- /.col -->
        <div class="col-sm-4 col-sm-6  col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-default"><i class="fa fa-truck"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Aguardando postagem</span>
              <span class="info-box-number">Devoluções: {{$devolucao_aguardando_postagem[0]->qtd_inventario}}</span>
              <span class="info-box-number"><small>Peças: {{number_format($devolucao_aguardando_postagem[0]->qtd_pecas, 0)}} 
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      <!-- /.row -->
			  </div>
	
			
			<h3>Atualização NF</h3>
 <div class="row">
		  

        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-hourglass-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Em aberto</span>
              <span class="info-box-number">Devoluções: {{$atualizacao_aberto[0]->qtd_inventario}}</span>
              <span class="info-box-number"><small>Peças: {{number_format($atualizacao_aberto[0]->qtd_pecas, 0)}} 
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

       
			
      <!-- /.row -->
			  </div>
<br><br>	
 <div class="row">
<form action="" >
  <label>Data</label>
  <input type="date" name="inicio" @if (isset($_GET["inicio"])) value="{{$_GET["inicio"]}}" @else value="{{date('Y-m-d')}}" @endif>
  <input type="date" name="fim" @if (isset($_GET["fim"])) value="{{$_GET["fim"]}}" @else value="{{date('Y-m-d')}}" @endif>
  <button type="submit">Pesquisar</button>
</form>
			</div>
<br>

    

<div class="row">

  <div class="col-md-6">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Devoluções conferidas</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
			<tr>
              <td >Nome</a></td>
              <td align="right">Qtd peças conferidas</td>
		  <td align="right">Qtd devoluções conferidas</td>
            </tr>
          

        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Peças conferidas</h3>
      </div>
      <div class="box-body">
          
        <table class="table table-bordered">
         
			<tr>
              <td >Nome</a></td>
              <td align="right">Peças inseridas manuais</td>
			  <td align="right">Pelas já no sistema</td>
			  <td align="right">Total conferido</td>
            </tr>
          
        </table>
      </div>

    </div>
  </div>
</div>

<div class="row">

  <div class="col-md-6">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered">

        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">


    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered">


        </table>
      </div>
    </div>




    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered">


        </table>
        <br>

        <table class="table table-bordered">


        </table>
      </div>
    </div>
    

  </div>
</div>
<!-- ChartJS -->

@stop