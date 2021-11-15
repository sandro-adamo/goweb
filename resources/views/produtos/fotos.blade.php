@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Ajuste de Fotos
@append
@section('conteudo')

@php
  $agrupamento = \DB::select("select trim(codagrup) as codagrup, trim(agrup) as agrup
from itens
where codtipoitem = '006'  and codgrife  in ('AH','AT','BG','HI','TC','SP') 
group by codagrup, agrup");
@endphp

<form action="" method="get">
<div class="box box-body box-widget">
  <div class="col-md-4">
    <select name="agrup" class="form-control">
      @foreach ($agrupamento as $agrup)
        <option @if (isset($_GET["agrup"]) && $_GET["agrup"] == $agrup->codagrup) selected @endif  value="{{$agrup->codagrup}}">{{$agrup->agrup}}</option>
      @endforeach 
    </select>
  </div>
  <div class="col-md-2">
    <button type="submit" class="btn btn-flat btn-default">Pesquisar</button>
  </div>
</div>
</form>

@php

  if (isset($_GET["agrup"])) {
    $agrup = $_GET["agrup"];

    $query = \DB::select("select grife, agrup,modelo from (
    select grife, agrup, modelo, itens.secundario, descricao, valortabela, clasmod, colmod,
    case when disp_vendas < 0 then 0 else disp_vendas end qtde_estoque,
      case when right(agrup,4) = '(RX)' then '2.8'  when right(agrup,4) = '(SL)' then '2.5' else '3' end as mkup
      
    from itens
    left join saldos on saldos.curto = itens.id
       
    where codtipoitem = '006' and codagrup  = '$agrup' and codgrife  in ('AH','AT','BG','HI','TC','SP') 

  ) as fim
  left join fotos on fim.modelo = fotos.foto_modelo
  where qtde_estoque > 30  and fotos.id is null 
  group by  grife, agrup,modelo
  order by grife");
  }
@endphp


@if (isset($query))
  @foreach ($query as $linha)
    @php
      $foto = app('App\Http\Controllers\ItemController')->consultaFoto(trim($linha->modelo));
      $query2 = \DB::select("select *, mkup*valortabela valorvenda from (
    select grife, agrup, modelo, itens.secundario, descricao, valortabela, clasmod, colmod,
    case when disp_vendas < 0 then 0 else disp_vendas end qtde_estoque,
      case when right(agrup,4) = '(RX)' then '2.8'  when right(agrup,4) = '(SL)' then '2.5' else '3' end as mkup
      
    from itens
    left join saldos on saldos.curto = itens.id
       
      where codtipoitem = '006'  and codgrife  in ('AH','AT','BG','HI','TC','SP')  and modelo = '$linha->modelo'

  ) as fim
  where qtde_estoque > 30 ");
    @endphp


    @if ($foto <> 'fotos/no-image.png')
      <form action="/ajuste/grava" method="post">
        @csrf
        <input type="hidden" name="modelo" value="{{$linha->modelo}}">
      <div class="box box-widget">
        <div class="box-header with-border">
          <h3 class="box-title">{{$linha->modelo}}</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              
              <a href="" class="zoom" data-value="{{$linha->modelo}}"><img src="{{$foto}}" class="img-responsive"> </a>
              {{$query2[0]->clasmod}}<br>
              {{$query2[0]->colmod}} <br>
              
            </div>
            <div class="col-md-9">
              
              <div class="row">

                @foreach ($query2 as $linha2)

                  @php

                    $foto2= app('App\Http\Controllers\ItemController')->consultaFoto($linha2->secundario);

                  @endphp

                  <div class="col-md-3">
                     <input type="radio" name="secundario" value="{{$linha2->secundario}}" >
                     <label>{{$linha2->secundario}}</label>
                     <a href="" class="zoom" data-value="{{$linha2->secundario}}"> <img src="/{{$foto2}}" class="img-responsive"> </a> 
             
                  </div>
                @endforeach

                  <div class="col-md-3">
                     <input type="radio" name="secundario" value="" >
                     <label class="text-red">NAO DISPONIVEL</label>
             
                  </div>
              </div>

            </div>
          </div>
        </div>
        <div class="box-footer">
          <button class="btn btn-success btn-flat pull-right" type="submit"><i class="fa fa-save"></i> Salvar</button>
        </div>
      </div>
      </form>
    @endif
  @endforeach 
@endif

@stop 
