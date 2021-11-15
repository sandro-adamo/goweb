@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Mostruários
@append 

@section('conteudo')

@php
  $id_usuario = \Auth::id();
  $devolucoes = \DB::select("
    select devolucoes2.status as situacao, count(*) itens
    from devolucoes2
    where devolucoes2.id_usuario = '$id_usuario' and devolucoes2.status not in ('Concluída', 'Cancelada') and acao = 'DEVOLVER' 
    group by devolucoes2.status");


  $id_usuario = \Auth::user()->id_addressbook;

  $processamento = \App\StatusProcessa::orderBy('data', 'desc')->select('processamento')->first();


  $geral = \DB::select("
                  select status_atual, count(itens) as itens
                  from(select secundario, statusatual as status_atual, count(malas.id_item) as itens
                from malas
                /*left join processa on processa.id_item = mostruarios.id_item and processamento = '$processamento->processamento'*/
                left join itens on id_item = itens.id
                where malas.id_rep = '$id_usuario'
                group by statusatual, secundario) as base
                group by   status_atual");


                $divergencia = \DB::select("select acao

                , count(secundario) as itens
                from(
                select itens.agrup, itens.secundario, descricao, colmod, valortabela as preco, tamolho,
                case when codultstatus in ('DISP', '15D', '30D') and codstatusatual in ('DISP', '15D', '30D') then 'manter_venda'
                when codultstatus in ('DISP', '15D', '30D') and codstatusatual in ('esgot', 'prod') then 'tirar_venda'
                when codultstatus in ('esgot', 'prod') and codstatusatual in ('DISP', '15D', '30D') then 'retornar_venda' 
                when codultstatus in ('esgot', 'prod') and codstatusatual in ('esgot', 'prod') then 'manter_fora' 

                else 'o_outro' end as acao, 
                statusatual, date(datastatusatual) as dt_statusatual, ultstatus, date(dataultstatus) as dt_ultstatus
                from malas

                left join itens on malas.id_item = itens.id
                left join ind_status atual on codstatusatual = atual.id_status
                left join ind_status ultimo on codultstatus = ultimo.id_status

                /*left join processa on processa.id_item = malas.id_item and processamento = $processamento->processamento*/

                WHERE atual.indice <> ultimo.indice and malas.id_rep = '$id_usuario'
                order by itens.agrup, itens.modelo asc) as base
                group by acao

                order by acao asc
                ");


                $pedidos = \DB::select("select nf,pedido, dt_emissao,
                case 
                when  ult_status < 543 then 'Inserido'
                when ult_status > 543 and ult_status < 560 then 'Separação'
                when ult_status > 560 and ult_status < 605 then 'Embalagem'
                when ult_status > 605 and ult_status < 619 then 'Faturamento'
                when ult_status = 620  then 'Faturado' 
                when ult_status = 620 and rastreio <>''   then 'Enviado' 
                else 0 end as  'Status',
                rastreio, mostruarios.transportadora,
                sum(qtde) as qtde

                from mostruarios
                left join addressbook on addressbook.id = id_cliente
                where rastreio <>''
                and transportadora <>''
                and id_cliente = $id_usuario
                
                group by pedido, dt_emissao, nf,ult_status, rastreio, mostruarios.transportadora
                order by dt_emissao desc
                limit 5");
                

@endphp


  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Potêncial</span>
          <span class="info-box-number">0</span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
            Não iniciada
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-refresh"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Atualizações</span>
          <span class="info-box-number">0</span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
            Não iniciada
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <a href="/mostruarios/devolucoes">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="fa  fa-dropbox"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Devoluções</span>
            <span class="info-box-number">@if ($devolucoes) {{$devolucoes[0]->itens}} @else 0 @endif </span>

            <div class="progress">
              <div class="progress-bar" style="width: 0%"></div>
            </div>
            <span class="progress-description">
              @if ($devolucoes) {{$devolucoes[0]->situacao}} @else Não Iniciado @endif
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
    </a>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-red">
        <span class="info-box-icon"><i class="fa fa-close"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Esgotado</span>
          <span class="info-box-number">0</span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
            Não iniciada
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->



  <div class="row">
    <div class="col-md-6">


      <div class="box box-widget">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> Pesquisa Status</h3>
        </div>
        <div class="box-body">

          <div class="row">

            @if (Session::has('alert'))
            <div class="callout callout-warning">{{Session::get('alert')}}</div>
            @endif

            <form method="get" action="">
              <div class="col-md-5">
                <input type="text" name="referencia" id="referencia" autofocus  required="" class="form-control" value=""  placeholder="referencia" />
              </div>        
              <div class="col-md-2">
                <input type="submit" value="Pesquisa" class="btn btn-flat btn-primary" onclick="PlaySound()">
              </div>
            </form>

        </div>
      

        




        @php
          $item = array();
          $modelo = array();

          if(isset($_GET["referencia"])) {
            $referencia = $_GET["referencia"];
            $item = \DB::select("select 
              case when clasmod like 'li%' then 'MANTER' else 'DEVOLVER' end as st_peca, modelo, secundario, statusatual, datastatusatual, ultstatus, dataultstatus
              from itens where secundario = '$referencia'");

            $modelo = $item[0]->modelo;
            $modelo = \DB::

            select("select * from itens where modelo = '$modelo'");
          }
        @endphp

        <br>

          @if (isset($_GET["referencia"]))
          <div class="row">

          <div class="table-responsive">
            <div class="col-md-4">

              <!-- FOTO -->
              <img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item[0]->secundario}}" alt='Photo' width="250"> 
              {{$item[0]->secundario}}


              <table class="table table-hover">
                <tr>                 
                  <th>ULTIMO</th>
                  <th>DATA</th>                 
                </tr>
                <tr>
                  <td>{{$item[0]->statusatual}}</td>
                  <td>{{$item[0]->datastatusatual}}</td>
                  <td>{{-- {{$item[0]->st_peca}} --}}</td>
                </tr>
                <tr>                 
                  <th></th>
                  <th></th>
                </tr>
                <tr>                 
                  <th>Penultimo</th>
                  <th></th>                 
                </tr>
                <tr>  
                  <td>{{$item[0]->ultstatus}}</td>
                  <!--    <td>{{$item[0]->dataultstatus}}</td>-->
                  <td>2018-12-07</td>
                </tr>
              </table>
            </div>







            <div class="col-md-8">
                <table class="table table-condensed table-bordered">

                  <tr>
                    <th>ITEM</th>
                    <th>ULTIMO STATUS</th>
                    <th>VALOR</th>
                  </tr>

                  @foreach ($modelo as $modelos)

                  <tr>  

                    <td align="center">
                      <a href="/mostruarios?referencia={{$modelos->secundario}}">
                        <img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$modelos->secundario}}" alt='Photo' width="100"> 
                        {{$modelos->secundario}}
                      </a>
                    </td>
  
                    <td align="center">{{$modelos->statusatual}}</td>
                    <td align="right">R$ {{number_format($modelos->valortabela,2,',','.')}}</td>



                  </tr>
                  @endforeach


                </table>

            </div>
            </div>
            </div>

            @endif


      </div>
      </div>


 
      <div class="box box-body box-widget">
        
        <h4 class="box-title"><i </i><b> Pedidos de mostruários</b></h4>
        <div class="col-ms-4">

          <table class="table table-condensed table-bordered">
        <tr>
          <td> <b>Pedido</b></td>
          <td><b>Data emissão </b></td>
          <td><b> NF</b></td>
          <td><b> Status</b></td>
          <td><b> Rastreio</b></td>
          <td><b> Transportadora</b></td>
          <td><b> Qtde</b></td>
        </tr>
         @foreach ($pedidos as $pedido1)

                  <tr>  

                    <td align="center">
                      <a href="/listapedidomost?referencia={{$pedido1->pedido}}">
                      {{$pedido1->pedido}}
                      </a>
                    </td>
                    <td> {{$pedido1->dt_emissao}}</td>
                    <td> {{$pedido1->nf}}</td>
                    <td> {{$pedido1->Status}}</td>
                    <td> {{$pedido1->rastreio}}</td>
                    <td> {{$pedido1->transportadora}}</td>
                    <td> {{$pedido1->qtde}}</td>
  
                    



                  </tr>
                  @endforeach
</table>
         
      </div>
      </div>
    </div>
      <div class="col-md-6">


        <div class="box box-body box-widget">
          <h4>
            <a href="/mostruarios/exporta/geral" ><i class="fa fa-paste fa-2x"></i></a>
            <b> Qtde de itens por ultimo status (geral mala)</b>
          </h4> 

          <table class="table table-condensed table-bordered">

            <tr>
              <th>ULTIMO STATUS</th>
              <th>ITENS</th>

            </tr>

            @php
            $total = 0 ;
            @endphp  

            @if (isset($geral) && count($geral) > 0)


            @foreach ($geral as $status) 


            @php
            $total += $status->itens;
            @endphp

            <tr>
              <td><a href="">{{$status->status_atual}}</a></td>
              <td align="right">{{$status->itens}}</td>
              
            </tr>


            @endforeach 

            @else 

            <tr>
              <td align="center" colspan="2" class="text-bold"> Nenhum item na mala</td>              
            </tr>

            @endif

            <tr>
              <td><b>TOTAL</b></td>
              <td align="right"><b>{{$total}}</b></td>

            </tr>
          </table>

        </div> 

        <div class="box box-body box-widget">
          <h4>
            <a href="/mostruarios/exporta/divergencia" ><i class="fa fa-paste fa-2x"></i></a>
            <b> Qtde de itens divergentes na ultima semana (divergencia semanal)</b>
          </h4> 

          <table class="table table-condensed table-bordered">

            <tr>
              <th>ACAO</th>
       
              <th>ITENS</th>           
            </tr>

            @php
            $total = 0 ;
            @endphp  

            @if (isset($divergencia) && count($divergencia) > 0)


            @foreach ($divergencia as $status) 


            @php
            $total += $status->itens;
            @endphp

            <tr>
              <td>{{$status->acao}}</td>
          
              <td align="right">{{$status->itens}}</td>
              
            </tr>


            @endforeach 

            @else 

            <tr>
              <td align="center" colspan="4" class="text-bold"> Nenhum item na mala</td>              
            </tr>

            @endif

          </table>
        



        </div>


      <div class="box box-body box-widget">

        testes1
      </div>

      </div>
    </div>

    @stop