@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $faixa_cep = $_GET["faixa_cep"];

 

@endphp

@section('title')
<i class="fa fa-suitcase"></i> {{$faixa_cep}}   
@append 

@section('conteudo')

<form action="" method="get"> 
@php

	
						
$query_1 = \DB::select("						



	select municipio, uf, min(faixa_cep) faixa_cep, count(cod_cliente) clientes, sum(pdvs) pdvs , sum(v120) v120, max(populacao) populacao,
	(sum(v120)/max(populacao))*1000 pc1000
    from (
		
        select ab.municipio, ab.uf, populacao, left(ab.cep,2) faixa_cep, cart.cod_cliente, count(ab.id) pdvs, sum(v120) v120
		from ds_carteira cart
		left join addressbook ab on ab.id = cart.codcli
		left join ibge on ibge.municipio = ab.municipio and ab.uf = ibge.uf
		where cart.rep_carteira in ($representantes)
		group by ab.municipio, ab.uf, left(ab.cep,2), cart.cod_cliente, populacao
		order by faixa_cep 
        
        
        
	) as fim 
    where faixa_cep = $faixa_cep
    group by municipio, uf 
    order by faixa_cep 


");
			
	


echo ' - '. count($query_1); 
	
	
@endphp


<div class="row">

		<div class="col-md-7">
			<div class="box box-widget box-body">
				<div class="table-responsive">

				<table class="table table-bordered" id="example3">
				<thead>
				<tr>	
				<td colspan="10">CLIENTES QUE COMPRARAM A GRIFE {{$faixa_cep}} NO PERIODO ( {{$faixa_cep}} )</td>

				</tr>

				<tr>	

				<td>form</td>
				<td colspan="1" align="center">regiao</td>				
				<td colspan="1" align="center">municipio</td>
				<td colspan="1" align="center">clientes</td>
				<td colspan="1" align="center">pdvs</td>
				<td colspan="1" align="center">qtde 120 dias</td>
				<td colspan="1" align="center">populacao</td>
				<td colspan="1" align="center">qtde/1000 hab</td>

				</thead>	
				</tr>


				@foreach ($query_1 as $query1)

				<tr>
				<td><a href="/cliente_form?cli={{$query1->uf}}"></a><i class="fa fa-file"></i> </td>
				<td>{{$query1->uf}}</td>
				<td align="left"><a href="/cliente_regiaodet?municipio={{$query1->municipio}}&faixa_cep={{$faixa_cep}}">{{$query1->municipio}}</a></td>
				
				<td align="center">{{$query1->clientes}}</td>
				<td align="center">{{$query1->pdvs}}</td>
				<td align="center">{{number_format($query1->v120,0)}}</td>
				<td align="center">{{number_format($query1->populacao,0)}}</td>	
				<td align="center">{{number_format($query1->pc1000,2)}}</td>
				</tr>
				@endforeach 

				</table>
				</div>
			</div>
		</div>
</h6>

	<div class="col-md-3">
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=2.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 50%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
   
	  
<div id="map"></div>

	  
</div>	    
    
		</div>	
</form>

@stop

{{--
<script src="/js/jquery.min.js" language="javascript"></script>
<script>

//	var ceps = [ '36030-770', '77015-290'];

	var ceps = [

		['TOLEDO-PR', 18],
		['GUAIRA', 18],
		['MARINGA', 19],
		['CASCAVEL', 11]

		
	];


	
	
  var map;
  function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
	  center: {lat:  -23.4273, lng:  -51.9375},
	  zoom: 3
	});
	


	var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';		
	
	ceps.forEach(function(index,value ) {
		var valor = index[0] + ' Brasil';
		return $.ajax({
			url: "https://maps.googleapis.com/maps/api/geocode/json",
			data: {
				address: valor,
				key: 'AIzaSyCmC7alneeiljpEvewb5OHK5FTtf4RQx8U'
			},
			dataType:"json",
			async: false,
			success: function(result) {
				var registros = String(index[2]);
				var lat = result.results["0"].geometry.location.lat;
				var lng = result.results["0"].geometry.location.lng;

				if (index[2] > 230) {
					var pinColor = "FE2E2E";
				} else if (index[2] > 23 && index[2] < 1) {
					var pinColor = "FFFF00";				
				} else {
					var pinColor = "64FE2E";					
				}
				var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
					new google.maps.Size(21, 34),
					new google.maps.Point(0,0),
					new google.maps.Point(10, 34));


				var beachMarker = new google.maps.Marker({
				  position: {lat: lat, lng: lng},
				  label: {text: registros, color: 'black',fontSize: '11px'},
				  map: map,
				  icon: pinImage,
				  title: index[0]+index[1]
				});		
							
			}
			
		});

		//var lat = localizacao.results["0"].geometry.location.lat
//			console.log(lat);
	});

  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmC7alneeiljpEvewb5OHK5FTtf4RQx8U&callback=initMap"
async defer></script>

--}}

</body>
</html>

	




