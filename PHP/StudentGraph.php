<?php
require_once 'functions.php';
require 'db.php';

require_logined_session();
header('Content-Type:text/html; charset=UTF-8');
?>

<?php
$_GET['studnet_id'] = '1000001';
if(isset($_GET['studnet_id'])){
    $student_id = $_GET['studnet_id'];
}else{
    header('Location: index.php');
}
$data_previous = prepareQuery("
    select student_id, month, `1`,`2`,`3`
    from attend_count_all_month
    where month between 4 and 8
      and student_id = ?",[$student_id]);
$data_late = prepareQuery("
    select student_id, month, `1`,`2`,`3`
    from attend_count_all_month
    where student_id = ? and month between 9 and 12 or student_id = ? and month = 1
    ",[$student_id,$student_id]);

//SQLで受け取るデータが、出席情報が存在している月の情報だけなので、該当月に1回も出席していない生徒の場合は、
//0というデータが入っていない。そのため、データの形式を統一するために、0で埋める作業をする。
$month_pre = [4,5,6,7,8];
$graph_data_pre = [];
$month_late = [9,10,11,12,1];
$graph_data_late = [];

$i= 0;
foreach ($data_previous as $row){
    while(true){
        if($month_pre[$i]==$row['month']){
            $graph_data_pre[] = [$row[1],$row[2],$row[3]];
            $i++;
            break;
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
var_dump($data);
foreach ($data_late as $row){
    while(true){
        if($month_late[$i]==$row['month']){
            $graph_data_late[] = [$row[1],$row[2],$row[3]];
//            debuzg([$row[1],$row[2],$row[3]]);
            echo  "<br><br>";
            $i++;
            break;
        }
        $graph_data_late[] = [0,0,0];
        $i++;
    }
}
$i = 5 - count($graph_data_late);
while ($i>0){
    $graph_data_late[] = [0,0,0];
    $i--;
}

var_dump($graph_data_pre);
echo '<br><br>';
    var_dump($graph_data_late);

?>
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
</div>

<!--グラフ作成-->
<img src="a.php">


</body>
</html>