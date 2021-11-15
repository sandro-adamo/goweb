@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Mostruários
@append 

@section('conteudo')

@php
	$representantes = Session::get('representantes');
  $id_usuario = \Auth::id();
  $devolucoes = \DB::select("
    select devolucoes2.status as situacao, count(*) itens
    from devolucoes2
    where devolucoes2.id_usuario = '$id_usuario' and devolucoes2.status not in ('Concluída', 'Cancelada') and acao = 'DEVOLVER' 
    group by devolucoes2.status");


  $id_usuario = \Auth::user()->id_addressbook;

  $processamento = \App\StatusProcessa::orderBy('data', 'desc')->select('processamento')->first();

if ($representantes == '') {

    $rep = ' == $id_usuario ';

  } else {

    $rep = ' IN ('.$representantes.') ';

  }

  $geral = \DB::select("
                  select status_atual, count(itens) as itens
                  from(select secundario, statusatual as status_atual, count(malas.id_item) as itens
                from malas
                /*left join processa on processa.id_item = mostruarios.id_item and processamento = '$processamento->processamento'*/
                left join itens on id_item = itens.id
                where malas.id_rep $rep
                and malas.local  = 'mala'
                group by statusatual, secundario) as base
                group by   status_atual");

 
                $divergencia = \DB::select("select acao

                , count(secundario) as itens
                from(
               select*
      from(
select itens.agrup, itens.secundario, descricao, colmod, valortabela as preco, tamolho,
case when codultstatus in ('DIS', '15D', '30D') and codstatusatual in ('DIS', '15D', '30D') then 'manter_venda'
when codultstatus in ('DIS', '15D', '30D') and codstatusatual in ('esg', 'pro') then 'tirar_venda'
when codultstatus in ('esg', 'prod') and codstatusatual in ('DIS', '15D', '30D') then 'retornar_venda' 
when codultstatus in ('esg', 'pro') and codstatusatual in ('esg', 'pro') then 'manter_fora' 

else 'o_outro' end as acao, 
statusatual, date(datastatusatual) as dt_statusatual, ultstatus, date(dataultstatus) as dt_ultstatus
from malas

left join itens on malas.id_item = itens.id

/*left join processa on processa.id_item = malas.id_item and processamento = $processamento->processamento*/

WHERE  malas.id_rep $rep
and malas.local = 'mala'
order by itens.agrup, itens.modelo asc) as base1
where acao in ('tirar_venda','retornar_venda','manter_venda','manter_fora')) as base
                group by acao

                order by acao asc
                ");

if (isset($id_usuario)  ){


                $pedidos = \DB::select("select nf,mostruarios.pedido, dt_emissao,
                case 
                when  ult_status < 543 then 'Inserido'
                when ult_status >= 543 and ult_status < 560 then 'Separação'
                when ult_status >= 560 and ult_status < 605 then 'Embalagem'
                when ult_status >= 605 and ult_status < 619 then 'Faturamento'
                when ult_status = 620  then 'Faturado' 
                when ult_status = 620 and (mostruarios.rastreio <>''or mostruarios_rastreios.rastreio <> '')  then 'Enviado' 
                else 0 end as  'Status',
                
				case when 	mostruarios_rastreios.rastreio is not null then mostruarios_rastreios.rastreio else mostruarios.rastreio end as 'rastreio'
				, 
					case when 	mostruarios_rastreios.rastreio is not null then mostruarios_rastreios.transportadora else mostruarios.transportadora end as 'transportadora'
					,
                sum(qtde) as qtde

                from mostruarios
                left join addressbook on addressbook.id = id_cliente
				left join mostruarios_rastreios on mostruarios.pedido = mostruarios_rastreios.pedido and mostruarios.tipo = mostruarios_rastreios.tipo
                where 
                 id_cliente $rep
                and pc_cliente = '' 
                and mostruarios.tipo = 'sm'
                and  ult_status not in ('980','984')
                
                group by pedido, dt_emissao, nf,ult_status, mostruarios.rastreio, mostruarios.transportadora, mostruarios_rastreios.rastreio, mostruarios_rastreios.transportadora
                order by dt_emissao desc
                limit 10 ");

                $recebermala = \DB::select("
                Select*
                from(select codgrife, id_movimentacao, tipo, 
                case when  id_origem = $id_usuario then 'Enviando'
                when id_destino $rep then 'Recebendo' else '-' end as 'acao'
                from movimentacoes_most where ( id_destino = $id_usuario) and status in ('Iniciado','Em processo')
                and tipo in ('troca','desligamento')
                group by codgrife, id_movimentacao, tipo, id_origem, id_destino) as base
                where acao = 'Recebendo'");


                 $enviarmala = \DB::select("
                Select*
                from(select codgrife, id_movimentacao, tipo, 
                case when  id_origem = $id_usuario then 'Enviando'
                when id_destino = $id_usuario then 'Recebendo' else '-' end as 'acao'
                from movimentacoes_most where (id_origem = $id_usuario ) and status in ('Iniciado','Em processo')
                and tipo in ('troca', 'desligamento')
                group by codgrife, id_movimentacao, tipo, id_origem, id_destino) as base
                where acao = 'Enviando'");

             }   

             $atualizacao_aguardando = \DB::select("select*
                  from mostruario_autoriza
                  where id_rep $rep
                  and status = 'liberado'
                  ");

@endphp

  
  <a href="/mostruarios/inventarios/{{$acao1 = 'inventario'}}">
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-aqua">
        <span class="info-box-icon"><i class="fa fa-barcode"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">INVENTARIO</span>
          <span class="info-box-number">0</span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
            Não iniciado
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    </a>
	  @if  ( \Auth::user()->id_perfil <> 23)
    <!-- /.col -->
    @if (count($atualizacao_aguardando)>0)
    <a href="/mostruarios/atualizacao">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-refresh"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Atualizações</span>
          <span class="info-box-number"></span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
            Liberada
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </a>
@else
 <a href="">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-refresh"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Atualizações</span>
          <span class="info-box-number"></span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
            Não liberada
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </a>

  @endif
    <!-- <a href="">
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
            EM MANUTENÇÃO
            </span>
          </div>
         /.info-box-content 
        </div>
        /.info-box 
      </div>
    </a> -->
    <!-- /.col -->
    <!-- <div class="col-md-3 col-sm-6 col-xs-12">
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
        /.info-box-content 
      </div>
       /.info-box 
    </div> -->
     @endif
      @if(isset($enviarmala[0]->codgrife) )
      <a href="/mostruarios/troca/{{$enviarmala[0]->acao}}">
     <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-purple">
        <span class="info-box-icon"><i class="fa fa-exchange"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Enviar grife</span>

          <span class="info-box-number">
            @foreach ($enviarmala as $grifes)
            {{$grifes->codgrife.' '}}
            @endforeach
          </span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
           
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </a>
    @endif
	  @if  ( \Auth::user()->id_perfil <> 23)

     @if(isset($recebermala[0]->codgrife))
     <a href="/mostruarios/troca/{{$recebermala[0]->acao}}">
     <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box bg-gray ">
        <span class="info-box-icon"><i class="fa fa-exchange"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Receber grife</span>

          <span class="info-box-number">
            @foreach ($recebermala as $grifes2)
            {{$grifes2->codgrife.' '}}
            @endforeach
          </span>

          <div class="progress">
            <div class="progress-bar" style="width: 0%"></div>
          </div>
          <span class="progress-description">
         
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </a>
    @endif
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
                  <td>{{$item[0]->dataultstatus}}</td>
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

                   @if (isset($pedido1->dt_emissao))
                  <tr>  

                    <td align="center">
                      <a href="/listapedidomost/pedido={{$pedido1->pedido}}">
                      {{$pedido1->pedido}}
                      </a>
                    </td>
                    <td> {{$pedido1->dt_emissao}}</td>
                    <td> {{$pedido1->nf}}</td>
                    <td> {{$pedido1->Status}}</td>
                    <td> {{$pedido1->rastreio}}</td>
                    <td> {{$pedido1->transportadora}}</td>
                    <td> {{$pedido1->qtde}}</td>
  
                   

 @endif

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
          
				<td align="right"><a href="/fichadivergencia?status={{$status->acao}}">{{$status->itens}}</a></td>
              
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
@endif
    @stop