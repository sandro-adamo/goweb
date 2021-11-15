<?php
ini_set('display_errors',1);
// graphPage.php
include_once("/var/www/html/portalgo/public/jpgraph/src/jpgraph.php"); 
require_once ('/var/www/html/portalgo/public/jpgraph/src/jpgraph_pie.php');


if (isset($_GET["id"])) {

	$total_atingido = $_GET["id"];
	$falta_atingir = 100 - $_GET["id"];

	// Some data
	$data = array($total_atingido,$falta_atingir);
	 
	// A new pie graph
	$graph = new PieGraph(300,200,'auto');
	 
	// Setup title
	//$graph->title->Set("Pie plot with center circle");
	//$graph->title->SetFont(FF_ARIAL,FS_BOLD,14);
	$graph->title->SetMargin(8); // Add a little bit more margin from the top
	 
	// Create the pie plot
	$p1 = new PiePlotC($data);
	 
	// Set size of pie
	$p1->SetSize(0.32);
	 
	// Label font and color setup
	//$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
	$p1->value->SetColor('black');
	 
	// Setup the title on the center circle
	// $p1->midtitle->Set(number_format($total_atingido,1).'%');
	//$p1->midtitle->SetFont(FF_ARIAL,FS_NORMAL,10);
	 
	// Set color for mid circle
	$p1->SetMidColor('white');
	 
	// Use percentage values in the legends values (This is also the default)
	//$p1->SetLabelType(PIE_VALUE_PER);
	 
	// Add plot to pie graph
	$graph->Add($p1);
	 
	// .. and send the image on it's marry way to the browser
	$graph->Stroke();
	 
}
?>