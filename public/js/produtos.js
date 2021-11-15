$(".addFavoritos").click(function(event){ 
	event.preventDefault();
	var item = $(this).data('value');


	if ($(this).find('i').hasClass('fa-heart-o')) {
		$(this).find('i').removeClass('fa-heart-o').addClass('fa-heart');
		$.ajax({
			type: 'GET',
			url: '/painel/favoritos/add',
			data: {
				item: item
			},
			success: function(result) {
				$(this).find('i').addClass('text-red');
			}

		});
	} else {
		$(this).find('i').removeClass('fa-heart').addClass('fa-heart-o');
		$.ajax({
			type: 'GET',
			url: '/painel/favoritos/del',
			data: {
				item: item
			},
			success: function(result) {
				$(this).find('i').addClass('text-red');
			}

		});
	}


	$.ajax({
		type: 'GET',
		url: '/painel/favoritos/checa',
		dataType: 'json',
		success: function(result) {

			if (result.length > 0 ){
				$("#favoritos").removeClass('fa-heart-o').addClass('fa-heart');
				$("#qtdeFavoritos").html(result.length);
			} else {
				$("#favoritos").removeClass('fa-heart').addClass('fa-heart-o');
				$("#qtdeFavoritos").remove();
			}
			console.log(result);
		}

	});

});


$(".uploadFoto").click(function(event) {

	event.preventDefault();

	var tipo = $(this).data('tipo');
	var valor = $(this).data('value');
	$("#modalUploadFoto #tipo").val(tipo);
	$("#modalUploadFoto #valor").val(valor);


	$("#modalUploadFoto").modal('show');
});


$(".alteraGenero").click(function(event) {

	event.preventDefault();

	var secundario = $(this).data('value');

	$("#modalGenero #id_item").val(secundario);

	$("#modalGenero").modal('show');

		/* Act on the event */
});


$(".alteraPreco").click(function(event) {

	event.preventDefault();

	var id_item = $(this).data('value');
	var tipo = $(this).data('tipo');

	$("#modalPreco #titulo").html('Preco');

	$("#modalPreco #tipo").val(tipo);
	$("#modalPreco #id_item").val(id_item);

	$("#modalPreco").modal('show');

	/* Act on the event */
});


$(".alteraCaracteristica").click(function(event) {

	event.preventDefault();

	var caracteristica = $(this).data('caracteristica');
	var id_item = $(this).data('value');
	var tipo = $(this).data('tipo');

	$.ajax({
		url: '/api/produto/caracteristica/'+caracteristica,
		dataType: "json",
		success: function(result) {
			var combo = '<select name="valor" class="form-control">';

			$.each(result, function(index, el) {
				
				combo += '<option value="'+el.codigo+'">'+el.valor+'</option>';

			});

			combo += '</select>';

			$("#valores").html(combo);
		}
	})

	$("#modalCaracteristica #titulo").html(caracteristica);

	$("#modalCaracteristica #tipo").val(tipo);
	$("#modalCaracteristica #id_item").val(id_item);
	$("#modalCaracteristica #caracteristica").val(caracteristica);

	if (caracteristica == 'colmod') {

		$("#modalCaracteristica #alteraColItem").html('<input type="checkbox" name="alteraColItem" value="1"> Altera ColItem ? ');

	} else {

		$("#modalCaracteristica #alteraColItem").html('');

	}

	$("#modalCaracteristica").modal('show');

	/* Act on the event */
});

$(".status3").change(function(e){

	var id = $(this).data('value');
	var valor = $(this).val();

alert(id);

	$.ajax({
		url: '/api/processa/altera-status',
		data: {
			id: id,
			valor: valor
		},
		type: "POST",
		headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
		dataType: "json",
		success: function(result) {
			console.log(result);
			window.location="principal.php?centro=_produtos/detalhe-status&agrup="+result.agrup+"&status="+result.status_atual+"&processamento="+result.processamento;
		}, 
		error: function(result) {
			alert('erro');
		}

	});

});