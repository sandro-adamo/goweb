@extends('layout.principal')

@section('title')
<i class="fa fa-user"></i> Movimentação - {{$movimentacoes[0]->id_movimentacao}}
@append 

@section('conteudo')




@if (Session::has("alert-success"))
  <div class="callout callout-success">{{Session::get('alert-success')}}</div>
@endif

<form action="/usuarios/movimentacoes/altera" method="post" class="form-horizontal">
@csrf
<div class="row">
  <div class="col-md-9">

    @if (Session::has('alert'))
      <div class="callout callout-warning text-bold">{{Session::get('alert')}}</div> 
    @endif

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-o"></i> Dados movimentacao</h3>
      </div>
      <div class="box-body">

        <div class="form-group">
          <label class="col-md-2 control-label">Tipo</label>
          <div class="col-md-4">
             <input type="tipo" id="disabledInput" name="id_origem" class="form-control" value="{{$movimentacoes[0]->tipo}}" disabled="">
             <input type=hidden name=tipo2 value="{{$movimentacoes[0]->tipo}}">
          </div>
        </div>



      

        <div class="form-group">
          <label class="col-md-2 control-label">Id origem</label>
          <div class="col-md-4">
            <input type="text" id="disabledInput" disabled="" name="id_origem" class="form-control" value="{{$movimentacoes[0]->id_origem}}">
             <input type="text" id="disabledInput" disabled="" name="" class="form-control" value="{{$movimentacoes[0]->nome_origem}}">
             <input type=hidden name=id_origem2 value="{{$movimentacoes[0]->id_origem}}">
          </div>
           
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Id destino</label>
          <div class="col-md-4">
            <input type="text" id="disabledInput" disabled="" name="id_destino" class="form-control" value="{{$movimentacoes[0]->id_destino}}">
            <input type="text" id="disabledInput" disabled="" name="" class="form-control" value="{{$movimentacoes[0]->nome_destino}}">
            <input type=hidden name=id_destino2 value="{{$movimentacoes[0]->id_destino}}">
          </div>
        </div>

        
 
        <div class="form-group">
          <label class="col-md-2 control-label">Grife</label>
          <div class="col-md-8">
  <input type="checkbox" value="AH" name="ah" @if($movimentacoes[0]->AH=='AH') checked @endif ><label >AH</label>
  <input type="checkbox" value="AT" name="at"@if($movimentacoes[0]->AT=='AT') checked @endif><label>AT</label>
  <input type="checkbox" value="BG" name="bg"@if($movimentacoes[0]->BG=='BG') checked @endif><label for="BG">BG</label>
  <input type="checkbox" value="EV" name="ev"@if($movimentacoes[0]->EV=='EV') checked @endif><label for="EV">EV</label>
  <input type="checkbox" value="HI" name="hi"@if($movimentacoes[0]->HI=='HI') checked @endif><label for="HI">HI</label>
  <input type="checkbox" value="JO" name="jo"@if($movimentacoes[0]->JO=='JO') checked @endif><label for="JO">JO</label>
  <input type="checkbox" value="SP" name="sp"@if($movimentacoes[0]->SP=='SP') checked @endif><label for="SP">SP</label>
  <input type="checkbox" value="TC" name="tc"@if($movimentacoes[0]->TC=='TC') checked @endif><label for="TC">TC</label>
  <input type="checkbox" value="JM" name="jm"@if($movimentacoes[0]->JM=='JM') checked @endif><label for="JM">JM</label>
  <input type="checkbox" value="FE" name="fe"@if($movimentacoes[0]->FE=='FE') checked @endif><label for="FE">FE</label>
  <input type="checkbox" value="AI" name="ai"@if($movimentacoes[0]->AI=='AI') checked @endif><label for="AI">AI</label>
  <br>
  <input type="checkbox" value="GU" name="gu"@if($movimentacoes[0]->GU=='GU') checked @endif><label for="GU">GU</label>
  <input type="checkbox" value="MM" name="mm"@if($movimentacoes[0]->MM=='MM') checked @endif><label for="MM">MM</label>
  <input type="checkbox" value="PU" name="pu"@if($movimentacoes[0]->PU=='PU') checked @endif><label for="PU">PU</label>
  <input type="checkbox" value="AA" name="aa"@if($movimentacoes[0]->AA=='AA') checked @endif><label for="AA">AA</label>
  <input type="checkbox" value="AM" name="am"@if($movimentacoes[0]->AM=='AM') checked @endif><label for="AM">AM</label>
  <input type="checkbox" value="AZ" name="az"@if($movimentacoes[0]->AZ=='AZ') checked @endif><label for="AZ">AZ</label>
  <input type="checkbox" value="BR" name="br"@if($movimentacoes[0]->BR=='BR') checked @endif><label for="BR">BR</label>
  <input type="checkbox" value="BV" name="bv"@if($movimentacoes[0]->BV=='BV') checked @endif><label for="BV">BV</label>
  <input type="checkbox" value="BC" name="bc"@if($movimentacoes[0]->BC=='BC') checked @endif><label for="BC">BC</label>
  <input type="checkbox" value="CL" name="cl"@if($movimentacoes[0]->CL=='CL') checked @endif><label for="CL">CL</label>
  <input type="checkbox" value="CT" name="ct"@if($movimentacoes[0]->CT=='CT') checked @endif><label for="CT">CT</label>
  <input type="checkbox" value="MC" name="mc"@if($movimentacoes[0]->MC=='MC') checked @endif><label for="MC">MC</label>
  <input type="checkbox" value="SM" name="sm"@if($movimentacoes[0]->SM=='SM') checked @endif><label for="SM">SM</label>
  <input type="checkbox" value="ST" name="st"@if($movimentacoes[0]->ST=='ST') checked @endif><label for="ST">ST</label>
  


</div>
</div>

         <div class="form-group">
          <label class="col-md-2 control-label">Status</label>
          <div class="col-md-4">
            <select name="status" class="form-control">
             

                <option value="Iniciado">Iniciado </option>
                <option value="Em processo">Em processo</option>
                <option value="Finalizado">Finalizado</option>
                <option value="Finalizado">Cancelado</option>
              
            </select>
          </div>
        </div>


           <div class="form-group">
          <label class="col-md-2 control-label">Data inicio</label>
          <div class="col-md-8">
            <input id="disabledInput" disabled="" type="text" name="data_inicio" class="form-control" value="{{$movimentacoes[0]->dt_created}}">
            <input type=hidden name=data_inicio2 value="{{$movimentacoes[0]->dt_created}}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">Data atualização</label>
          <div class="col-md-8">
            <input type="date" name="data_atualizacao" class="form-control" value="" >
          </div>
        </div>

          <div class="form-group">
            <label class="col-md-2 control-label">Observações</label>
           <div class="col-md-8"> 
          <textarea class="form-control" name="obs" rows="4" cols="20">
         
          </textarea>
          </div>
        </div>
         <input type=hidden name=id_movimentacao value="{{$movimentacoes[0]->id_movimentacao}}">
        <button class="btn btn-flat btn-default">Enviar</button>

       
</form>
       

      </div>



<div class="tab-content">
              <div class="active tab-pane" id="geral">
      <div class="col-md-12">
     
        <!-- The time line -->
        <ul class="timeline">




@foreach ($movimentacoes as $movimentacao)

      <li class="time-label">
            <span class="bg-gray">
              {{date("d/m/Y", strtotime($movimentacao->dt_updated))}}
            </span>
      </li>


      

      <li>

            <i class="fa fa-envelope bg-gray"></i>
        

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$movimentacao->dt_updated}}</span>

              <h3 class="timeline-header"><a href="#">{{$movimentacao->responsavel}}</a></h3>

              <div class="timeline-body">
                {{$movimentacao->obs.$movimentacao->codgrife}}
              </div>
        


              <div class="timeline-footer">
                
              </div>
            </div>

          </li>
        

@endforeach
        </ul>
      </div>
        </div>

    </div>





@stop
