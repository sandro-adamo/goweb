$(".solicitaVisita").click(function(event) {
	/* Act on the event */


	event.preventDefault();

	var rep = $(this).data('rep');
	var grife = $(this).data('grife');


	$("#modalSolicitaVisita #grife").val(grife);
	$("#modalSolicitaVisita #rep").val(rep);

	$("#modalSolicitaVisita").modal('show');
});