<?php
	error_reporting(E_ALL);
    ini_set('display_errors',1);



	function consultaFoto($referencia) {


	    $dbhost = "10.30.210.15";
	    $dbuser = "portalgo";
	    $dbpass = "W4xyM64iYEm3m7ON";
	    $dbdata = "go";

	    $conmssql = mysqli_connect($dbhost,$dbuser,$dbpass,$dbdata);

		$produto = mysqli_query($conmssql, "select trim(agrup) as agrupamento, trim(secundario) as item, modelo,
		case when secundario = '$referencia' then secundario else modelo end as item2
									from go.itens
									
									where (secundario = '$referencia' or modelo = '$referencia')");
	
		$produto = mysqli_fetch_assoc($produto);

		$link = '/var/www/html/';

		$foto_baixa1 	= $link.'fotos/BAIXA/'.trim($produto["agrupamento"]).'/'.trim($produto["item"]).'.jpg';
		$foto_baixa2 	= $link.'fotos/BAIXA/'.trim($produto["agrupamento"]).'/'.trim($produto["item"]).'.JPG';
		$link.
		$foto_alta1 	= $link.'fotos/ALTA/'.trim($produto["agrupamento"]).'/'.trim($produto["item"]).'.jpg';
		$foto_alta2 	= $link.'fotos/ALTA/'.trim($produto["agrupamento"]).'/'.trim($produto["item"]).'.JPG';
		$link.
		$foto_modelo1 	= $link.'fotos/MODELO/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.JPG';
		$foto_modelo2 	= $link.'fotos/MODELO/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.jpg';
		$link.
		$foto_ficha1 	= $link.'fotos/FICHA/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.JPG';
		$foto_ficha2 	= $link.'fotos/FICHA/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.jpg';
		$link.
		$foto_ficha_design1 = $link.'fotos/FICHA_DESIGN/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.JPG';
		$foto_ficha_design2	= $link.'fotos/FICHA_DESIGN/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.jpg';
		$link.
		$foto_prototipo1 	= $link.'fotos/PROTOTIPO/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.jpg';
		$foto_prototipo2	= $link.'fotos/PROTOTIPO/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.JPG';
		$link.
		$combinacao1 	= $link.'fotos/COMBINACAO/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.jpg';
		$combinacao2 	= $link.'fotos/COMBINACAO/'.trim($produto["agrupamento"]).'/'.trim($produto["item2"]).'.JPG';
		$link.
		$agregado1 	= $link.'fotos/AGREGADOS/'.trim($referencia).'.JPG';
		$agregado2	= $link.'fotos/AGREGADOS/'.trim($referencia).'.jpg';

		$foto = 'fotos/no-image.png';
	
		
		if (file_exists($agregado1)) {

			$foto = $agregado1;	
		}
		
		if (file_exists($agregado2)) {
			$foto = $agregado2;	
		}

		if (file_exists($combinacao1)) {
			$foto = $combinacao1;	
		}
		
		if (file_exists($combinacao2)) {
			$foto = $combinacao2;	
		}
		
		if (file_exists($foto_ficha1)) {
			$foto = $foto_ficha1;	
		}
		if (file_exists($foto_ficha2)) {
			$foto = $foto_ficha2;
		}
		

		if (file_exists($foto_modelo1)) {
			$foto = $foto_modelo1;	
		}
		
		if (file_exists($foto_modelo2)) {
			$foto = $foto_modelo2;	
		}
	
		if (file_exists($foto_alta2)) {
			$foto = $foto_alta2;	
		}
		if (file_exists($foto_alta1)) {
			$foto = $foto_alta1;	
		}

		if (file_exists($foto_prototipo1)) {
			$foto = $foto_prototipo1;	
		}
		if (file_exists($foto_prototipo2)) {
			$foto = $foto_prototipo2;		
		}
		if (file_exists($foto_ficha_design1)) {
			$foto = $foto_ficha_design1;
		}

		if (file_exists($foto_ficha_design2)) {
			$foto = $foto_ficha_design2;
		}


		if (file_exists($foto_baixa2)) {
			$foto = $foto_baixa2;	
		}
		if (file_exists($foto_baixa1)) {
			$foto = $foto_baixa1;		
		}
		if (file_exists($foto_alta2)) {
			$foto = $foto_alta2;	
		}
		if (file_exists($foto_alta1)) {
			$foto = $foto_alta1;		
		}
		$foto = $foto;


		return $foto;
							

	}


    $referencia = $_GET['referencia'];
    $produto = consultaFoto($_GET['referencia']);    
    // Set the path to the image (I'm using a WordPress theme)
    $rootpath = '/var/www/html/';
    $imgpath =  $produto;
     
    // Get the mimetype for the file
    $finfo = finfo_open(FILEINFO_MIME_TYPE);  // return mime type ala mimetype extension
    $mime_type = finfo_file($finfo, $imgpath);
    finfo_close($finfo);
     
    switch ($mime_type){
        case "image/jpeg":
            // Set the content type header - in this case image/jpg
            header('Content-Type: image/jpeg');
             
            // Get image from file
            $img = imagecreatefromjpeg($imgpath);
             
            // Output the image
            imagejpeg($img);
             
            break;
        case "image/png":
            // Set the content type header - in this case image/png
            header('Content-Type: image/png');
             
            // Get image from file
            $img = imagecreatefrompng($imgpath);
             
            // integer representation of the color black (rgb: 0,0,0)
            $background = imagecolorallocate($img, 0, 0, 0);
             
            // removing the black from the placeholder
            imagecolortransparent($img, $background);
             
            // turning off alpha blending (to ensure alpha channel information 
            // is preserved, rather than removed (blending with the rest of the 
            // image in the form of black))
            imagealphablending($img, false);
             
            // turning on alpha channel information saving (to ensure the full range 
            // of transparency is preserved)
            imagesavealpha($img, true);
             
            // Output the image
            imagepng($img);
             
            break;
        case "image/gif":
            // Set the content type header - in this case image/gif
            header('Content-Type: image/gif');
             
            // Get image from file
            $img = imagecreatefromgif($imgpath);
             
            // integer representation of the color black (rgb: 0,0,0)
            $background = imagecolorallocate($img, 0, 0, 0);
             
            // removing the black from the placeholder
            imagecolortransparent($img, $background);
             
            // Output the image
            imagegif($img);
             
            break;
    }
     
    // Free up memory
    imagedestroy($img);