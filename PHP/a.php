<?php
//
////DB接続
//require_once 'functions.php';
//require_logined_session();
//
//header('Content-Type:text/html; charset=UTF-8');
//require 'db.php';
//if (isset($_GET['class_id'])) {
//    $class_id = $_GET['class_id'];
//} else {
//    //login.phpから飛んできた1行目のclass_idが入る。
//    $class_id = $_SESSION['class']['id'][0];
//}
//$student = prepareQuery("select * from load_responsible_1 where class_id = ?", [$class_id]);
//
//try {
//} catch (PDOException $exception) {
//    die('接続エラー:' . $exception->getMessage());
//}
//try {
//} catch (PDOException $exception) {
//    die('接続エラー:' . $exception->getMessage());
//}

//フレームワークでグラフ作成
include ("../JpGraph/src/jpgraph.php");
include ("../JpGraph/src/jpgraph_line.php");
include ("../JpGraph/src/jpgraph_bar.php");

// データの用意
$ydata = array(100,100,100,100,100,"");//累計の出席率
$ydata1 = array(100,100,100,80,90,"");//今月の出席率

$tiko = array(0, 0, 10, 3, 0);//遅刻数
$ketu = array(0, 0, 7, 9, 10);//欠席

$xdata = array("4","5","6","7","8","",);//前期の月


// グラフを作成。以下の 2 種類の呼び出しが必ず必要です
$graph = new Graph(700,300,"auto");
$graph->SetScale("textlin",0,100);
$graph->SetFrame(true);
$graph->title->Set("");//生徒の名前
$graph->xaxis->SetTickLabels($xdata);


// リニア プロットを作成
$lineplot=new LinePlot($ydata);//累計のグラフ
$lineplot->SetColor("blue");
$lineplot->mark->SetType(MARK_FILLEDCIRCLE );

$lineplot1=new LinePlot($ydata1);//今月のグラフ
$lineplot1->SetColor("red");
$lineplot1->mark->SetType(MARK_DTRIANGLE);
$lineplot1->mark->SetFillColor("red");


$barplot1 = new BarPlot($tiko);
$barplot1->SetFillColor('#FF4500');
$barplot2 = new BarPlot($ketu);
$barplot2->SetFillColor("#1E90FF");
$groupbarplot = new GroupBarPlot(array($barplot1,$barplot2));
$groupbarplot->SetWidth(0.35);//棒グラフの幅の広さ



// プロットをグラフに追加
$graph->Add($lineplot);//累計を表示
$graph->Add($lineplot1);//月の出席率を表示
//$graph->Add($groupbarplot);

// グラフを表示
$graph->Stroke();
?>
