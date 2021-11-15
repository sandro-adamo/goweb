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
          <td align="middle"> <b>Nome</b></td>
          <td align="middle"> <b>Filial</b></td>
          <td align="middle"> <b>Razao/b></td>
          <td align="middle"> <b>Codigo</b></td>
          <td align="middle"> <b>Dt solicitado</b></td>
           <td align="middle"> <b>Qtd</b></td>
          <td align="middle"> <b>Ação</b></td>


          <td><b> </b></td>
       
        </tr>
       

         @foreach ($reps as $rep)
          <form action="/mostruarios/atualizacao/aberto/enviado" method="post" class="form-horizontal">
          @csrf

                  <tr >  
                    <td align="middle" >{{$rep->nome}}</td>
                    <td align="middle" >{{$rep->filial}}</td>
                    <td align="middle" >{{$rep->razao}}</td>
                    <td align="middle" ><a href="/mostruarios/solicitacao/id_rep={{$rep->id_rep}}">{{$rep->id_rep}}</td>

                    <td align="middle" >{{$rep->dt_solicitado}}</td>
                    <td align="middle" >{{$rep->qtd}}</td>
                    
                   <input type="hidden" name="id_rep" value="{{$rep->id_rep}}">

                    <td align="middle" ><button class="btn btn-flat btn-default">Realizado</button></td>
                    
      
                    
  
                    
        </form> 


                  </tr>
                  @endforeach

</table>
 
              
         
      </div>
      </div>
    </div>

    @stop