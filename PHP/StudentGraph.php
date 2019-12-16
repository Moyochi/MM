<?php
require_once 'functions.php';
require 'db.php';

require_logined_session();
header('Content-Type:text/html; charset=UTF-8');
?>

<?php
if(isset($_GET['student_id'])){
    $student_id = $_GET['student_id'];
}else{
    header('Location: index.php');
}
$count_data_previous = prepareQuery("
    select student_id, month, `1`,`2`,`3`
    from attend_count_all_month
    where month between 4 and 8
      and student_id = ?",[$student_id]);
$count_data_late = prepareQuery("
    select student_id, month, `1`,`2`,`3`
    from attend_count_all_month
    where student_id = ? and month between 9 and 12 or student_id = ? and month = 1
    ",[$student_id,$student_id]);
$rate_data_previous = prepareQuery("
    select * 
    from load_studentgraph_2_previous
    where student_id = ?",[$student_id]);
$rate_data_late = prepareQuery("
    select * 
    from load_studentgraph_2_late
    where student_id = ?",[$student_id]);

//var_dump($count_data_previous);
//echo '<br><br>';
//var_dump($count_data_late);
//echo '<br><br>';
//var_dump($rate_data_previous);
//echo '<br><br>';
//var_dump($rate_data_late);

//SQLで受け取るデータが、出席情報が存在している月の情報だけなので、該当月に1回も出席していない生徒の場合は、
//0というデータが入っていない。そのため、データの形式を統一するために、0で埋める作業をする。
$month_pre = ['04','05','06','07','08'];
$graph_data_pre = [];
$month_late = ['09','10','11','12','01'];
$graph_data_late = [];

//エラー起きるけど、上のdumpを見ながら修正したいから、ページができるまで待機。

$i= 0;
foreach ($count_data_previous as $row){
    while(true){
        if($month_pre[$i]==$row['month']){
            $graph_data_pre[] = [$row[1],$row[2],$row[3]];
            $i++;
            break 2;
        }
        $graph_data_pre[] = [0,0,0];
        $i++;
    }
}
$i = 5 - count($graph_data_pre);
while ($i>0){
    $graph_data_pre[] = [0,0,0];
    $i--;
}
$i= 0;
foreach ($count_data_late as $row){
    while(true){
        if($month_late[$i]==$row['month']){
            $graph_data_late[] = [$row[1],$row[2],$row[3]];
            $i++;
            break 2;
        }else{
            $graph_data_late[] = [0,0,0];
            $i++;
        }
    }
}
$i = 5 - count($graph_data_late);
while ($i>0){
    $graph_data_late[] = [0,0,0];
    $i--;
}

//var_dump($graph_data_pre);
//echo '<br><br>';
//    var_dump($graph_data_late);

?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Responsible.css">
    <link rel="stylesheet" media="all" href="../CSS/Style.css">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>Responsible.html</title>
</head>
<body>


<!--どのアカウントで入ったか確認-->

<div class="header">

    <div class="title">

        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1 class="head">
                <!-- 題名 -->
                学生プロフィール
            </h1>
        </div>
    </div>

</div>

<!-- 上のメニューバー -->
<div class="bu">
    <!--    <a href="AttendanceConfirmation.php" id="attend">状況管理</a>-->
</div>

<!--検索バー -->
<div class="container">
    <input type="text" placeholder="Search..." id="sa-ch">
    <div class="search"></div>
</div>

<div class="contents">
    <ul class="nav">
        <li><a href="./index.php">担当グループ</a></li>
        <li><a href="Group.php">グループ管理</a></li>
        <li><a href="Users.php">ユーザー検索</a></li>
        <li><a href="Resuser.php">管理者ユーザー一覧</a></li>
        <li><a href="Groupmake.php">グループ作成</a></li>
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </ul>
    <!--グラフ作成-->
    <img src="a.php">
</div>


</body>
</html>