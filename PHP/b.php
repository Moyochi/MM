<?php
include ("../JpGraph/src/jpgraph.php");
include ("../JpGraph/src/jpgraph_line.php");
include ("../JpGraph/src/jpgraph_bar.php");

$graph = new Graph(250, 200, "auto");
$graph->SetFrame(true);
$graph->SetScale("textlin");

$graph->img->SetMargin(30, 30, 30, 30);

$ydata1 = array(10, 4, 7, 9, 1, 3);
$ydata2 = array(5, 12, 3, 8, 5, 9);

$barplot1 = new BarPlot($ydata1);
$barplot2 = new BarPlot($ydata2);

$barplot1->SetColor("red");
$barplot1->SetFillColor("yellow");
$barplot2->SetFillColor("#337112");

$groupbarplot = new GroupBarPlot(array($barplot1,$barplot2));

$graph->Add($groupbarplot);

$graph->Stroke();
?>