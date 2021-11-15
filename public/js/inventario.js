$(".alteraSituacao").click(function(event) {

	event.preventDefault();

	var id = $(this).data("value");


	$.ajax({
		url: '/mostruarios/inventarios/consultaSituacao',
		type: 'GET',
		dataType: 'json',
		data: {id: id},
	})
	.done(function(result) {
		$("#modalAlteraSituacao #referencia").val(result[0].item);
		$("#modalAlteraSituacao #id_linha").val(result[0].id);
		console.log(result);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
	$("#modalAlteraSituacao").modal('show');
	



});