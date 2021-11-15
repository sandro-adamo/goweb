@php
	// include QR_BarCode class 
	include "qrcode.php"; 


if (isset($_GET["modelo"])) {

	$modelo = $_GET["modelo"];

	$itens = \DB::select("select secundario 
					from itens 
					left join qrcode on itens.secundario = qrcode
					where qrcode <> '' 
					and modelo = '$modelo'
					group by secundario
					");
}

@endphp
<html>
<head>
	<title>{{$modelo}}</title>
	<style type="text/css">
		body { 
			margin: 0;
			padding: 0;
		}
		.foto {
		
			width: 2400px;
			height: 1200px;
		}
		.imagem-wrapper {
		    width: 600px;
		    height: 120px;
		    overflow: hidden;
		    position: relative; /* Para fazer que a imagem com position-absolute respeite a sua posição consoante este selector, ou evitar que saia do mesmo */
		}
		.imagem {
		    width: 600px;
		    /* código abaixo centra a imagem ao centro */
		    position: absolute;
		    top: 50%;
		    left: 50%;
		    transform: translateX(-50%) translateY(-50%);
		}
	</style>
	<script>
	window.print();
	window.onfocus=function(){ window.close();}
	</script>
</head>
<body>
	<table  > 
		@if (isset($itens))
			@foreach ($itens as $item) 
				
				<tr >
					@php
					    $foto = app('App\Http\Controllers\ItemController')->consultaFotoAlta($item->secundario);
					@endphp

					<td widthvalign="middle" align="center">
						<img  src="/fotos/QRCODE/{{$item->secundario}}.JPG" class="img-responsive" >
				    </td>
				    <td width="30%" valign="middle" align="center">
						<img src="/storage/qrcode/{{$item->secundario}}.png" width="650" class="img-responsive">
						<br> <span style="font-size:68px; font-family: Arial">{{$item->secundario}}</span></p>

				    </td>
				</tr>

			@endforeach
		@endif
   	</table>

</body>