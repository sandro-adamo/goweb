$(document).ready(function() {

	$.each($(".all"), function(index, val) {
		var filtro = $(this).data('value');
		verificaFiltros(filtro);
	});
	
});

$("#selectAll").change(function(event) {

	var situacao = $(this).prop('checked');

	if (situacao == true) {
		$(".addModeloCatalogo").each(function(index){

			var valor = $(this).val();
			addModeloCatalogo(valor);

		})
		$(".addModeloCatalogo").prop('checked',true);
	} else {
		$(".addModeloCatalogo").prop('checked',false);		
	}
	/* Act on the event */
});


$(".addItemCatalogo").change(function(event) {

	var valor = $(this).val();
	addItemCatalogo(valor);

});

function addItemCatalogo(valor) { 

	//alert('teste'+valor);
	//var valor = $(this).val();
	var token = $('meta[name="csrf-token"]').attr('content');
	var codigo = $('meta[name="novocatalogo"]').attr('content');

	$.ajax({
	  url: '/api/catalogo/'+codigo+'/addItem',
	  type: 'POST',
	  headers: {
	    'X-CSRF-TOKEN': token
	  },
	  data: {
	    item: valor
	  },
	  dataType: "json",
	  success: function(result) {

	    alert("Item adicionado com sucesso!");

	  }
	})

};


$(".addModeloCatalogo").change(function(event) {

	var valor = $(this).val();
	addModeloCatalogo(valor);
	alert("Modelo adicionado com sucesso!");

});


function addModeloCatalogo(valor) {


	var token = $('meta[name="csrf-token"]').attr('content');
	var codigo = $('meta[name="novocatalogo"]').attr('content');

	$.ajax({
	  url: '/api/catalogo/'+codigo+'/addModelo',
	  type: 'POST',
	  headers: {
	    'X-CSRF-TOKEN': token
	  },
	  data: {
	    modelo: valor
	  },
	  dataType: "json",
	  success: function(result) {


	  }
	})

};


function verificaFiltros(filtro) {

	var total = parseInt($('input[name="'+filtro+'"]').length);
	var sel   = parseInt($('input[name="'+filtro+'"]:checked').length);

	if (sel == total) {
		$('.all[data-value="'+filtro+'"]').prop('checked', true);
	} else {
		$('.all[data-value="'+filtro+'"]').prop('checked', false);
	}

}

$(".retrai").click(function(e) {
	e.preventDefault();
	var filtro = $(this).data('value');

	if ($(this).hasClass('retrai')) {
		$(this).removeClass('retrai').addClass('expande');
		$("i[data-value='"+filtro+"']").removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
		$("#"+filtro).css('display', 'none');
	} else {
		$(this).removeClass('expande').addClass('retrai');
		$("i[data-value='"+filtro+"']").removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
		$("#"+filtro).css('display', 'inline');
	}
});

$(".all").change(function(e) {
	var valor = $(this).prop('checked');
	var filtro = $(this).data('value');

	if (valor == true) {
		$(".item[data-value='"+filtro+"']").attr('checked', true);
	} else {
		$(".item[data-value='"+filtro+"']").attr('checked', false);
	}
});

$(".item").change(function(event) {
	var valor = $(this).prop('checked');
	var filtro = $(this).data('value');

	var total = parseInt($('input[name="'+filtro+'"]').length);
	var sel   = parseInt($('input[name="'+filtro+'"]:checked').length);

	if (sel == total) {
		$('.all[data-value="'+filtro+'"]').prop('checked', true);
	} else {
		$('.all[data-value="'+filtro+'"]').prop('checked', false);
	}
	
});

$("#frmFiltro").submit(function(e){
	e.preventDefault();

	var ordem = $("#ordem").val();
	var tipoOrdem = $("#tipoOrdem").val();


	var agrup = $("#agrup").val();
	var url = '/painel/'+agrup+'/?ordem='+ordem+','+tipoOrdem+'&anomod=';

	$('input[name="ano"]:checked').each(function(index) {
		index++;
		var valor = $(this).val();
		if (index == $('input[name="ano"]:checked').length) {
			url += valor;
		} else {
			url += valor+',';        
		}
	});

	var show = $('input[name="show"]:checked').val();
	url += '&show='+show;


	var col_total = parseInt($('input[name="colecao"]').length);
	var col_sel   = parseInt($('input[name="colecao"]:checked').length);

	if (col_sel > 0 && col_sel < col_total) {

		url += '&colmod=';
		$('input[name="colecao"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == col_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	}

	var gen_total = parseInt($('input[name="genero"]').length);
	var gen_sel   = parseInt($('input[name="genero"]:checked').length);

	if (gen_sel > 0 && gen_sel < gen_total) {

		url += '&genero=';
		$('input[name="genero"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == gen_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	}	

	var mat_total = parseInt($('input[name="material"]').length);
	var mat_sel   = parseInt($('input[name="material"]:checked').length);

	if (mat_sel > 0 && mat_sel < mat_total) {

		url += '&material=';
		$('input[name="material"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == mat_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	}	

	var ida_total = parseInt($('input[name="idade"]').length);
	var ida_sel   = parseInt($('input[name="idade"]:checked').length);

	if (ida_sel > 0 && ida_sel < ida_total) {

		url += '&idade=';
		$('input[name="idade"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == ida_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	}		


	var fix_total = parseInt($('input[name="fixacao"]').length);
	var fix_sel   = parseInt($('input[name="fixacao"]:checked').length);

	if (fix_sel > 0 && fix_sel < fix_total) {

		url += '&fixacao=';
		$('input[name="fixacao"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == fix_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	}			

	


	var clas_total = parseInt($('input[name="classificacao"]').length);
	var clas_sel   = parseInt($('input[name="classificacao"]:checked').length);

	if (clas_sel > 0 && clas_sel < clas_total) {

		url += '&codclasmod=';
		$('input[name="classificacao"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == clas_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	}	

	var forn_total = parseInt($('input[name="fornecedor"]').length);
	var forn_sel   = parseInt($('input[name="fornecedor"]:checked').length);

	if (forn_sel > 0 && forn_sel < forn_total) {

		url += '&fornecedor=';
		$('input[name="fornecedor"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == forn_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	}	

	var stat_total = parseInt($('input[name="status"]').length);
	var stat_sel   = parseInt($('input[name="status"]:checked').length);

	if (stat_sel > 0 && stat_sel < stat_total) {

		url += '&codstatusatual=';
		$('input[name="status"]:checked').each(function(index) {
			index++;
			var valor = $(this).val();
			if (index == stat_sel) {
				url += valor;
			} else {
				url += valor+',';        
			}
		});

	} 

	var preco_de = parseInt($('input[name="preco_de"]').val());
	var preco_ate = parseInt($('input[name="preco_ate"]').val());
	//var stat_sel   = parseInt($('input[name="status"]:checked').length);


	url += '&preco_de='+preco_de+'&preco_ate='+preco_ate;



	alert(url);

	window.location=url;
	//$("#frmFiltro").submit();
});
