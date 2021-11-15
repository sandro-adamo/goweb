$("#btnEnviaPedido").click(function(event) {

  event.preventDefault();

//  var resultado = confirm("Deseja realmente enviar este pedido?");

//  if (resultado == true) {

    $("#modalEnviaPedido").modal('show');
    //$("#frmLiberaPedido").submit();

//  }

});

$('#modalEnviaPedido').on('shown.bs.modal', function () {
    $('#modalEnviaPedido #item').focus();
});

$(".addEmail").click(function(event) {

  var email = '<div class="form-group">';
  email += '<label class="col-md-2 control-label">Email</label>';
            email += '<div class="col-md-8">';
                email += '<input type="email" name="email[]" class="form-control">';
            email += '</div>';
            email += '<div class="col-md-1">';
                email += '<button type="button" id="novoEmail" class="btn btn-flat btn-default"><i class="fa fa-plus"></i></button>';
            email += '</div>';
        email += '</div>';

  $("#emails").append(email);

});

$("#btnImporta").click(function(event) {

  event.preventDefault();

  $("#modalImportaItens").modal('show');
});

$("#btnNovoPedido2").click(function(event) {
  event.preventDefault();

  $("#modalNovoPedido").modal('show');

});

$(".novoPedido").click(function(event) {
  event.preventDefault();
  $(".campos").val('');


  var item = $(this).data('value');
  $("#modalNovoRepedido #item").val(item);


  $.ajax({
    url: '/api/produto/'+item,
    dataType: "json",
    success: function(dados) {

      $("#modalNovoRepedido #referencia").html(item);
      $("#modalNovoRepedido #foto").html('<img src="https://portal.goeyewear.com.br/teste999.php?referencia='+dados.secundario+'" class="img-responsive" >');


    }

  });


  var planejamento = '';

  
  planejamento += '<table class="table table-condensed table-bordered">';
  planejamento += '<tr class="bg-primary">';
  planejamento += '<td width="33%">Qtde</td>';
  planejamento += '<td width="33%">Ano</td>';
  planejamento += '<td width="33%">Mês</td>';
//  planejamento += '<td width="70%">Obs</td>';
  planejamento += '</tr>';

    planejamento += '<tr>';
    planejamento += '<td align="center" width="10%"><input type="text" size="5" class="" data-value="" style="text-align:center" value=""></td>';
    planejamento += '<td align="center" width="10%"><input type="number" size="5" class="" style="text-align:center" maxlength="4" max="2500" min="2000" value=""></td>';
    planejamento += '<td align="center" width="10%"><input type="number" size="5" class=""  style="text-align:center" maxlength="2" value=""></td>';
//    planejamento += '<td align="center" width="70%"><input type="text" size="20" class="obs_linha_"  style="text-align:center"></td>';
    planejamento += '</tr>';        
  planejamento += '</table>';


  $("#planejamento").html(planejamento);

  listaPedidosItem(item);
  $("#modalNovoRepedido").modal('show');


});


$(".selPedido").click(function(e) {
  e.preventDefault();

  var item = $("#modalNovoRepedido #item").val();


  var pedido = $(this).data('value');
  $(".pedido").removeClass('bg-blue');
  $('#pedido'+pedido).addClass('bg-blue');

  $("#id_compra").val(pedido);

});



$(document).on('click', ".btnEditaItem", function(e) {

  e.preventDefault();


  var id_compra_item = $(this).data('value');


  $.ajax({
    url: '/api/compras/item/consulta',
    data: {
      id_compra_item: id_compra_item
    }, 
    type: "GET",
    dataType: "json",
    success: function(result) {

      $("#modalNovoRepedido #id_compra").val(result.id_compra);
      $("#modalNovoRepedido #id_compra_item").val(result.id);
      $("#modalNovoRepedido #qtde").val(result.qtde);
      $("#modalNovoRepedido #obs").val(result.note);
      $("#modalNovoRepedido #data_entrega").val(result.dt_prevista);
    
      $(".pedido").removeClass('bg-blue');
      $("#modalNovoRepedido #pedido"+result.id_compra).addClass('bg-blue');
      console.log(result);

    }
  });


});

$(document).on('click', ".btnExcluiItem", function(e) {

  e.preventDefault();

  var resultado = confirm("Deseja realmente cancelar este pedido?");

  if (resultado == true) {

    var id_compra_item = $(this).data('value');


    $.ajax({
      url: '/api/compras/item/exclui',
      data: {
        id_compra_item: id_compra_item
      }, 
      type: "POST",
      success: function(result) {

        var item = $("#modalNovoRepedido #item").val();

        alert("Registro cancelado com sucesso!");
        listaPedidosItem(item);

      }
    });

  }

});


function listaPedidosItem(item) {

  $.ajax({

    url: '/api/compras/pedidos/'+item,
    dataType: "json",
    success: function(result) {
      var linha = '';
      $(".linha").remove();

      $.each(result, function(index, val) {

        if (val.status == 'CANCELADO') {
          linha += '<tr class="linha" style="text-decoration: line-through; color:red">';
          
        } else {

          linha += '<tr class="linha">';
        }

        linha += '<td align="center">'+val.id+'</td>';
        linha += '<td align="center"><a href="/compras/'+val.id_compra+'">'+val.id_compra+'</a></td>';
        linha += '<td align="center">'+val.status+'</td>';
        linha += '<td align="center">'+val.origem+'</td>';
        linha += '<td align="center">'+val.pedido_dt+'</td>';
        linha += '<td align="center">'+val.qtde+'</td>';
        linha += '<td align="center">'+val.qtde_conf+'</td>';
		linha += '<td align="center">'+val.dt_entrega+'</td>';
		linha += '<td align="center">'+val.qtd_entregue+'</td>';
        linha += '<td align="center">'+val.note+'</td>';

        if (val.status == 'ABERTO') {
          linha += '<td align="center"><a href="" class="btnEditaItem" data-value="'+val.id+'"><i class="fa fa-edit text-blue"></i></a></td>';
          linha += '<td align="center"><a href="" class="btnExcluiItem" data-value="'+val.id+'"><i class="fa fa-close text-red "></i></a></td>';
        } else {
          linha += '<td align="center"><i class="fa fa-edit text-gray"></i></td>';
          linha += '<td align="center"><i class="fa fa-close text-gray"></i></td>';
        }

        linha += '</tr>';

      });
      $("#dadosPedido").after(linha);
    }

  });

}


$("#frmNovoRepedido").submit(function(event) {
//alert(' oi');
  event.preventDefault();

  var pedido = $("#id_compra").val();
  var id_compra_item = $("#modalNovoRepedido #id_compra_item").val();


  if (pedido == '') {

    alert("pedido nao definido1");
    return false;

  }


  var item = $("#modalNovoRepedido #item").val();
  var qtde = $("#modalNovoRepedido #qtde").val();
  var entrega = $("#modalNovoRepedido #data_entrega").val();
  var obs = $("#modalNovoRepedido #obs").val();

  if (id_compra_item == '') {

    $.ajax({
      url: '/api/compras/item/insere',
      data: {
        pedido: pedido,
        item: item,
        qtde: qtde,
        entrega: entrega,
        obs: obs
      }, 
      type: "POST",
      success: function(result) {

        alert("Pedido inserido com sucesso.");
        listaPedidosItem(item);
        $(".campos").val('');

      }
    });

  } else {

    $.ajax({
      url: '/api/compras/item/edita',
      data: {
        id_compra_item: id_compra_item,
        pedido: pedido,
        item: item,
        qtde: qtde,
        entrega: entrega,
        obs: obs
      }, 
      type: "POST",
      success: function(result) {

        alert("Pedido alterado com sucesso.");
        listaPedidosItem(item);
        $(".campos").val('');

      }
    });
    
  }


});

$(".btnPlanejamentoItem").click(function(event) {
  /* Act on the event */
  event.preventDefault();

  var id_compra_item = $(this).data('value');

  carregaPlanejamentoItem(id_compra_item);

  $("#modalPlanejamentoItem").modal('show');
});




function carregaPlanejamentoItem(id_compra_item) {

  $.ajax({
    url: '/api/compras/item/consulta',
    data: {
      id_compra_item: id_compra_item
    }, 
    type: "GET",
    dataType: "json",
    success: function(result) {

        var planejamento = '';
console.log(result);
        $("#modalPlanejamentoItem #foto").html('<img src="https://portal.goeyewear.com.br/teste999.php?referencia='+result.item+'" class="img-responsive">');
        $("#modalPlanejamentoItem #item").html('<p style="font-size: 18px">'+result.item+'</p>');
        $("#modalPlanejamentoItem #qtde").val(result.qtde);
        $("#modalPlanejamentoItem #id_compra_item").val(id_compra_item);
		$("#modalPlanejamentoItem #id_compra_item").val(result.id_compra_item);
		//$("#modalPlanejamentoItem #obs_entrega").val(obs_entrega);
        
        planejamento += '<table class="table table-condensed table-bordered" id="tablePlanejamento">';
        planejamento += '<tr class="bg-primary">';
        planejamento += '<td width="5%">Id entrega</td>';
        planejamento += '<td width="10%">Qtde</td>';
        planejamento += '<td width="10%">Entrega</td>';
        planejamento += '<td width="10%">Confirmação</td>';
		planejamento += '<td width="10%">Qtd entregue</td>';
		planejamento += '<td width="30%">Obs </td>';
        planejamento += '<td width="1%"></td>';
        planejamento += '</tr>';
        
        $("#modalPlanejamentoItem #btnCancelaPlanejamento").css('display', 'none');
                
        $.each(result.distribuicao, function(index, val) {
			if (val.dt_alterada == 1) {
			style2 = 'style="text-decoration: line-through;color:red"';
			
			}
			else{
			style2 = '';	
		
			}
          
            planejamento += '<tr '+style2+'>';
            planejamento += '<td align="center" width="10%"><input type="text" size="5" name="linha[]" class="form-control linha" data-value="" style="text-align:center" value="'+val.id+'"></td>';
            planejamento += '<td align="center" width="10%"><input type="text" size="5" name="qtde_plan[]" class="form-control linha" data-value="" style="text-align:center" value="'+val.qtde_entrega+'"></td>';
            planejamento += '<td align="center" width="10%"><input type="text" size="5" name="dt_entrega[]" class="form-control ano_linha_" style="text-align:center" value="'+val.dt_entrega+'"></td>';
            planejamento += '<td align="center" width="10%"><input type="text" size="5" name="dt_confirmada[]" class="form-control mes_linha_"  style="text-align:center" value="'+val.dt_confirmada+'"></td>';
			planejamento += '<td align="center" width="30%"><input type="text" size="5" name="qtd_entregue[]" class="form-control linha" data-value="" style="text-align:center" value="'+val.qtd_entregue+'"></td>';
			
			
			
			planejamento += '<td align="center" width="20%">'+val.obs_entrega+'</td>';
			
            planejamento += '<td align="center" width="70%"><a href="" class=""></a></td>';
            planejamento += '</tr>';

           /* iterate through array or object */
        });

        for (i=0;i<1;i++) {

          // Return today's date and time
          var data = new Date();
          
          if (result.qtde) {
            
            data.setMonth(data.getMonth() + 4);
            var month = data.getMonth();
            var year = data.getFullYear();                      
            var qtde = '';
            
            if (i == 0) {
              qtde = result.qtde * 0.6;              
            } else if (i == 1 || i == 2) {
              qtde = result.qtde * 0.2;
              month = data.getMonth() + i;
            } else {
              qtde = '';  
              year = '';
              month = '';
            }
              
          }
          
          

          if (result.qtde_restante > 0) {


            planejamento += '<tr>';
            planejamento += '<td align="center" width="10%"><input type="text" size="2" name="linha[]" class="form-control linha" data-value="" style="text-align:center" value="'+i+'"></td>';
            planejamento += '<td align="center" width="10%"><input type="text" size="5" class="form-control linha" name="qtde_plan[]" data-value="" style="text-align:center" value="'+result.qtde_restante+'"></td>';
            planejamento += '<td align="center" width="10%"><input type="date" size="5" name="dt_entrega[]" class="form-control ano_linha_'+i+'" style="text-align:center" maxlength="4" max="2500" min="2000" value="'+year+'"></td>';
            planejamento += '<td align="center" width="10%"><input type="date" size="5" name="dt_confirmada[]" class="form-control mes_linha_'+i+'"  style="text-align:center" maxlength="2" value="'+month+'"></td>';
			  
			planejamento += '<td align="center" width="10%"><input type="text" size="5" class="form-control qtd_entregue" name="qtd_entregue[]" data-value="" style="text-align:center" value="'+i+'"></td>';  
			  
			
			  
			planejamento += '<td align="center" width="10%"><input type="text" size="5" name="obs_entrega[]" class="form-control obs_entrega" data-value="" style="text-align:center" value="'+i+'"></td>';
            planejamento += '<td align="center" width="70%"><a href="" class=""><i class="" data-value="" ></i></a></td>';
            planejamento += '</tr>';
            


          } else {


            planejamento += '<tr>';
            planejamento += '<td align="center" width="10%"><input type="text" size="2" name="linha[]" class="form-control linha" data-value="" style="text-align:center" value=""></td>';
            planejamento += '<td align="center" width="10%"><input type="text" size="5" class="form-control linha" name="qtde_plan[]" data-value="" style="text-align:center" value=""></td>';
            planejamento += '<td align="center" width="10%"><input type="date" size="5" name="dt_entrega[]" class="form-control ano_linha_'+i+'" style="text-align:center"></td>';
            planejamento += '<td align="center" width="10%"><input type="date" size="5" name="dt_confirmada[]" class="form-control mes_linha_'+i+'"  style="text-align:center" maxlength="2" value=""></td>';
			planejamento += '<td align="center" width="10%"><input type="text" size="5" class="form-control linha" name="qtd_entregue[]" data-value="" style="text-align:center" value=""></td>';
			planejamento += '<td align="center" width="10%"><input type="text" size="5" class="form-control obs_entrega" name="obs_entrega[]" data-value="" style="text-align:center" value=""></td>';
            planejamento += '<td align="center" width="70%"><a href="" class=""><i class="" data-value="" ></i></a></td>';
            planejamento += '</tr>';
            

          }
        

        }       
        
      
      
      
      planejamento += '</table>';
      //$("#modalPlanejamentoItem #id_compra_item").val(id_item);
      $("#modalPlanejamentoItem #tabPlanejamentoItem").html(planejamento);
      $("#modalPlanejamentoItem #carregando").css('display', 'none');
    
      
    }
  });     
  
  
}

$(document).on('click', '.inserePlanejamentoItem', function(event) {
  event.preventDefault();

  $(".linhaPlanejamento").removeClass('fa-plus text-green inserePlanejamentoItem').addClass('fa-trash text-red removePlanejamentoItem');
       
  var linha = '';
  linha += '<tr>';
  linha += '<td align="center" width="10%"><input type="text" name="linha[]" size="5" name="linha[]" class="form-control linha" data-value="" style="text-align:center" value=""></td>';
  linha += '<td align="center" width="10%"><input type="text" name="qtde_plan[]" size="5" name="qtde_plan[]" class="form-control linha" data-value="" style="text-align:center" value=""></td>';
  linha += '<td align="center" width="10%"><input type="date" name="dt_entrega[]" size="5" name="ano[]" class="form-control ano_linha_" style="text-align:center" ></td>';
  linha += '<td align="center" width="10%"><input type="date" name="dt_confirmada[]" size="5" name="mes[]" class="form-control mes_linha_"  style="text-align:center"></td>';
	linha += '<td align="center" width="10%"><input type="date" name="qtd_entregue[]" size="5" name="qtd_entegue[]" class="form-control qtd_entregue"  style="text-align:center"></td>';
	linha += '<td align="center" width="10%"><input type="date" name="obs_entrega[]" size="5" name="obs_entrega[]" class="form-control obs_entrega"  style="text-align:center"></td>';
  linha += '<td align="center" width="70%"><a href="" class="inserePlanejamentoItem"><i class=""></i></a></td>';
  linha += '</tr>';

  $("#tablePlanejamento").append(linha);
});


$(document).on('click', '.removePlanejamentoItem', function(event) {
  event.preventDefault();

  var tr = $(this).closest('tr');
  tr.css("background-color","#FF3700");
  tr.fadeOut(400, function(){
      tr.remove();
  });       
 
});