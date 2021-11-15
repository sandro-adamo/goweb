@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Mostruários
@append 

@section('conteudo')



 
      <div class="box box-body box-widget">
        
        <h4 class="box-title"><b> Código representante {{$pedidos[0]->Rep}}</b></h4>
        <div class="col-ms-4">

          <table class="table table-condensed table-bordered">
        <tr>
          <td align="middle"> <b>Agrupamento</b></td>
          <td align="middle"> <b>Item</b></td>
          <td align="middle"> <b>Olho</b></td>
          <td align="middle"> <b>Haste</b></td>
          <td align="middle"> <b>Ponte</b></td>
          <td align="middle"> <b>Status</b></td>
          <td align="middle"> <b>Foto</b></td>
		<td align="middle"> <b>Inventário</b></td>
			

          <td><b> </b></td>
       
        </tr>
        <form action="/mostruarios/atualizacao/selecionados" method="post" class="form-horizontal">
          @csrf
          <input type="hidden" value="{{$pedidos[0]->Filial}}" name="filial">
          <input type="hidden" value="{{$pedidos[0]->Rep}}" name="rep">
         @foreach ($pedidos as $item)
				@if($item->inventario =='DEVOLVER')
                  <tr bgcolor="#F8060A" >  
				@else
				<tr >  
				@endif
                    <td align="middle" valign="bottom" >{{$item->Agrupamento}}</td>
                    <td align="middle" valign="middle">
                    <input type="checkbox" value="{{$item->Cod_Secundario}}" name="item[]"><label ></label>
                      {{$item->Cod_Secundario}}</td>
                    <td align="middle" valign="bottom" >{{$item->tamolho}}</td>
                      <td align="middle" valign="bottom" >{{$item->tamhaste}}</td>
                      <td align="middle" valign="bottom" >{{$item->tamponte}}</td>
                      <td align="middle" valign="bottom" >{{$item->statusatual}}</td>
                    <td align="middle>
                      
                    <div>
        <a href="" class="zoom" data-value="{{$item->Cod_Secundario}}">
            <img width= "100" src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item->Cod_Secundario}}" class="img-responsive">
        </a></div></td>
      
                    <td align="middle" valign="bottom" >@if($item->inventario =='DEVOLVER')Esse item você já devolveu, gostaria de pedir novamente?@endif</td>
  
                    



                  </tr>
                  @endforeach

</table>
 <button class="btn btn-flat btn-default btnCarregando">Confirmar</button>
                </form> 
         
      </div>
      </div>
    </div>

    @stop