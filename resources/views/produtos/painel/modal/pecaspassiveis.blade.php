
@csrf
<div class="modal fade" id="modalpecaspassiveis" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
       
 <tr>
  <table class="table"  style="text-align: center;">
    <td> Saldo Partes </td>
  </table>
 </tr>
 <tr>
       <table class="table"  style="text-align: center;">
                      <tr>
                
                      <td>Secundario</td>
                      <td>Frente</td>
                      <td>Ponte</td>
                      <td>Haste direita</td>
                      <td>Haste esquerda</td>
                      <td>Lente direita</td>
                      <td>Lente esquerda</td>
                      <td>Lente</td>
                      <td>Lente em bloco</td>
                      <td>Pecas Passiveis</td>
                      <td>Pecas não passiveis</td>
                      <td>Total</td>


            
                            
                        </tr>

                         <tr>
                @foreach ($itens as $catalogo)

                      <td>{{$catalogo->secundario}}</td>
                      <td>@if(isset($catalogo->fr)){{$catalogo->fr}}</td>
                      <td>{{$catalogo->ponte}}</td>
                      <td>{{$catalogo->hd}}</td>
                      <td>{{$catalogo->he}}</td>
                      <td>{{$catalogo->ld}}</td>
                      <td>{{$catalogo->le}}</td>
                      <td>{{$catalogo->lente}}</td>
                      <td>{{$catalogo->lente_em_bloco}}</td>
                      <td>{{$catalogo->saldo_passivel}}</td>
                      <td>{{$catalogo->pecas_sem_complemento}}</td>
                      <td>{{$catalogo->total}}</td>
            
                      @endif
                        </tr>
                   @endforeach
              
</table>

  

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        
      </div>
    </div>
  </div>
</div>
