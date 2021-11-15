$(".seleGrife").change(function(event) {

	event.preventDefault();

	var cliente = $(this).data('value');
	var grife = $(this).prop('name');

	var status = $(this).prop('checked')

	$.ajax({
		url: '/api/ecommerce/',
		data: {
			id_cliente: cliente,
			grife: grife,
			status: status
		},
		type: 'GET',


	})
	.done(function(result) {
		alert("Grife liberada com sucesso!")
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	



});