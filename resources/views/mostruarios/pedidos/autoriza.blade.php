@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Mostruários
@append 

@section('conteudo')



 
      <div class="box box-body box-widget">
        
        <h4 class="box-title"><b> Autoriza pedidos</b></h4>
        <div class="col-ms-4">

          <table class="table table-condensed table-bordered">
        <tr>
          <td align="middle"> <b>Razão</b></td>
          <td align="middle"> <b>Nome</b></td>
          <td align="middle"> <b>Id rep</b></td>
          <td align="middle"> <b>AH</b></td>
          <td align="middle"> <b>AT</b></td>
          <td align="middle"> <b>BG</b></td>
          <td align="middle"> <b>EV</b></td>
          <td align="middle"> <b>HI</b></td>
          <td align="middle"> <b>JO</b></td>
          <td align="middle"> <b>NG</b></td>
          <td align="middle"> <b>SP</b></td>
          <td align="middle"> <b>TC</b></td>
          <td align="middle"> <b>GU</b></td>
          <td align="middle"> <b>MM</b></td>
          <td align="middle"> <b>ST</b></td>
          <td align="middle"> <b>PU</b></td>
          <td align="middle"> <b>Ação</b></td>


          <td><b> </b></td>
       
        </tr>
       

         @foreach ($reps as $rep)
 <form action="/mostruarios/pedidos/libera" method="post" class="form-horizontal">
          @csrf

          @php

   
   $liberado = \DB::select("
    select* from mostruario_autoriza where id_rep = $rep->rep and status = 'liberado'");


    @endphp
                  <tr >  
                    <td align="middle" >{{$rep->razao}}</td>
                    <td align="middle" >{{$rep->nome}}</td>
                    <td align="middle" >{{$rep->rep}}</td>
                    <td align="middle" >@if ($rep->ah==1) X @endif</td>
                    <td align="middle" >@if ($rep->at==1) X @endif</td>
                    <td align="middle" >@if ($rep->bg==1) X @endif</td>
                    <td align="middle" >@if ($rep->ev==1) X @endif</td>
                    <td align="middle" >@if ($rep->hi==1) X @endif</td>
                    <td align="middle" >@if ($rep->jo==1) X @endif</td>
                    <td align="middle" >@if ($rep->ng==1) X @endif</td>
                    <td align="middle" >@if ($rep->sp==1) X @endif</td>
                    <td align="middle" >@if ($rep->tc==1) X @endif</td>
                    <td align="middle" >@if ($rep->gu==1) X @endif</td>
                    <td align="middle" >@if ($rep->mm==1) X @endif</td>
                    <td align="middle" >@if ($rep->st==1) X @endif</td>
                    <td align="middle" >@if ($rep->pu==1) X @endif</td>
                     <input type="hidden" value="{{$rep->rep}}" name="rep2">

                    @if(count($liberado)>0)
                    <td align="middle" >Liberado</td>
                    @else
                    <td align="middle" ><button class="btn btn-flat btn-default">Liberar</button></td>
                    @endif
      
                    
  
                     </form> 



                  </tr>
                  @endforeach

</table>
 
               
         
      </div>
      </div>
    </div>

    @stop