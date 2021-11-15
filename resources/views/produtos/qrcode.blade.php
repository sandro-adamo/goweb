@php
// include QR_BarCode class 
include "qrcode.php"; 


$itens = \DB::select("select secundario 
from itens 
left join qrcode on itens.secundario = qrcode
where secundario = 'BG8011 A01'
group by secundario
");


foreach ($itens as $item) {

    // QR_BarCode object 
    $qr = new QR_BarCode(); 

    // create text QR code 
    $qr->text("$item->secundario"); 

    // display QR code image
    //$qr->qrCode();
    $qr->qrCode(350,'storage/qrcode2/'.$item->secundario.'.png');
}

@endphp