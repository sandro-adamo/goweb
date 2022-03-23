@extends('layout.principal')

@section('title')
<i class="fa fa-user"></i> inserir movimentação
@append 

@section('conteudo')




@if (Session::has("alert-success"))
  <div class="callout callout-success">{{Session::get('alert-success')}}</div>
@endif

<form action="/usuarios/movimentacoes/nova/inserir" method="post" class="form-horizontal">
@csrf
<div class="row">
  <div class="col-md-9">

    @if (Session::has('alert'))
      <div class="callout callout-warning text-bold">{{Session::get('alert')}}</div> 
    @endif

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-o"></i> Dados do Usuário</h3>
      </div>
      <div class="box-body">

        <div class="form-group">
          <label class="col-md-2 control-label">Tipo</label>
          <div class="col-md-4">
            <select name="tipo" class="form-control" required>
             
                <option value="troca">Troca </option>
                <option value="desligamento">Desligamento</option>

              
            </select>
          </div>
        </div>



      

        <div class="form-group">
          <label class="col-md-2 control-label">Id origem</label>
          <div class="col-md-8">
          <select name="id_origem" id="id_origem" required class="form-control">
                <option value=""> Selecione </option>

                @php                  
                $reps = \DB::select("select id, concat(nome,' - ',id,' - ',razao) as nome from go.addressbook where tipo = 'RE' and sit_representante = '' order by nome asc ");
                @endphp                   

                @foreach ($reps as $rep) 
                <option value="{{$rep->id}}"> {{$rep->nome}} </option>
                @endforeach

              </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Id destino</label>
          <div class="col-md-8">
              <select name="id_destino" id="id_destino" required class="form-control">
                <option value=""> Selecione </option>

                @php                  
                $reps = \DB::select("select id, concat(nome,' - ',id,' - ',razao) as nome from go.addressbook where tipo = 'RE' and sit_representante = '' order by nome asc ");
                @endphp                   

                @foreach ($reps as $rep) 
                <option value="{{$rep->id}}"> {{$rep->nome}} </option>
                @endforeach

              </select>
          </div>
        </div>

        
         <div class="form-group">
          <label class="col-md-2 control-label">Grife</label>
          <div class="col-md-8">
  <input type="checkbox" value="AH" name="ah"><label >AH</label>
  <input type="checkbox" value="AI" name="ai"><label for="AI">AI</label>
  <input type="checkbox" value="AT" name="at"><label>AT</label>
  <input type="checkbox" value="BG" name="bg"><label for="BG">BG</label>
  <input type="checkbox" value="EV" name="ev"><label for="EV">EV</label>
<input type="checkbox" value="FE" name="fe"><label for="EV">FE</label>
  <input type="checkbox" value="HI" name="hi"><label for="HI">HI</label>
  <input type="checkbox" value="JO" name="jo"><label for="JO">JO</label>
  <input type="checkbox" value="SP" name="sp"><label for="SP">SP</label>
  <input type="checkbox" value="TC" name="tc"><label for="TC">TC</label>
  <input type="checkbox" value="JM" name="jm"><label for="JM">JM</label>

  
  <br>
  <input type="checkbox" value="GU" name="gu"><label for="GU">GU</label>
  <input type="checkbox" value="MM" name="mm"><label for="MM">MM</label>
  <input type="checkbox" value="PU" name="pu"><label for="PU">PU</label>
  <input type="checkbox" value="AA" name="aa"><label for="AA">AA</label>
  <input type="checkbox" value="AM" name="am"><label for="AM">AM</label>
  <input type="checkbox" value="AZ" name="az"><label for="AZ">AZ</label>
  <input type="checkbox" value="BR" name="br"><label for="BR">BR</label>
  <input type="checkbox" value="BV" name="bv"><label for="BV">BV</label>
  <input type="checkbox" value="BC" name="bc"><label for="BC">BC</label>
  <input type="checkbox" value="CL" name="cl"><label for="CL">CL</label>
  <input type="checkbox" value="CT" name="ct"><label for="CT">CT</label>
  <input type="checkbox" value="MC" name="mc"><label for="MC">MC</label>
  <input type="checkbox" value="SM" name="sm"><label for="SM">SM</label>
  <input type="checkbox" value="ST" name="st"><label for="ST">ST</label>
  


</div>
</div>

         <div class="form-group">
          <label class="col-md-2 control-label">Status</label>
          <div class="col-md-4">
            <select name="status" class="form-control" required>
             

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
            <input type="date" name="data_inicio" class="form-control" value="" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">Data atualização</label>
          <div class="col-md-8">
            <input type="date" name="data_atualizacao" class="form-control" value="" required>
          </div>
        </div>

          <div class="form-group">
            <label class="col-md-2 control-label">Observações</label>
           <div class="col-md-8"> 
          <textarea class="form-control" name="obs" rows="4" cols="20">
         
          </textarea>
          </div>
        </div>
        <button class="btn btn-flat btn-default">Enviar</button>

       
</form>
       

      </div>

    </div>





@stop
