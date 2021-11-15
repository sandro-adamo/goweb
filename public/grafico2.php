<?php
ini_set('display_errors',1);
// graphPage.php
include_once("/var/www/html/portalgo/public/jpgraph/src/jpgraph.php"); 
require_once ('/var/www/html/portalgo/public/jpgraph/src/jpgraph_bar.php');
 
$data1y=array(10,3.6,1.5,0.1,37.2,9.4,0);
$data2y=array(16.66,16.66,16.66,16.66,16.66,16.66,16.66);

// Size of graph
$width=650;
$height=200;
 
// Set the basic parameters of the graph
$graph = new Graph($width,$height);
$graph->SetScale('textlin');
 
$top = 40;
$bottom = 20;
$left = 120;
$right = 20;
$graph->Set90AndMargin($left,$right,$top,$bottom);
 
// Nice shadow
 
// Setup labels
$lbl = array("NACIONAL","PRIME","NOVOS CANAIS",
"ATP","LUXO","PREMIUM","DIRETO");
$graph->xaxis->SetTickLabels($lbl);
 
// Label align for X-axis
//$graph->xaxis->SetLabelAlign('right','center','right');
$graph->xaxis->SetLabelAlign('right','center','right', 'right');
 
// Label align for Y-axis
$graph->yaxis->SetLabelAlign('right', 'center','bottom');
 
// Titles
$graph->title->Set('% Vendas + Estimativa');
 
// Create a bar pot
$b1plot = new BarPlot($data1y);
$b1plot->SetFillColor("orange");

$b2plot = new BarPlot($data2y);
$b2plot->SetFillColor("blue");

$gbplot = new AccBarPlot(array($b1plot,$b2plot));
$gbplot->SetWidth(0.5);
 
// ...and add it to the graPH
$graph->Add($gbplot);
 
$graph->title->Set("% Venda + Estimativa");
//$graph->xaxis->title->Set("milhoes");
//$graph->yaxis->title->Set("milhoes");
 
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$gbplot->SetWidth(0.5);
$gbplot->SetYMin(20);

 
// $graph->Add($bplot);
 
$graph->Stroke();
?>