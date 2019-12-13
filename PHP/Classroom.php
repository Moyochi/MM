<?php
require 'db.php';
require_once 'functions.php';
require_logined_session();
header('Content-Type:text/html; charset=UTF-8');

//左上のクラスの選択で利用。
$class_list = query("select * from classrooms order by classroom_name");
//スケジュールを表示するクラスについて、クラスの指定がある場合はpostで受け取り、
//指定がない場合(初回表示時)にはクラスリストの一番上が選択される。
if(isset($_POST['class_update'])){
    $class_update = $_POST['class_update'];
}else{
    $class_update = $class_list[0]['classroom_id'];
}


//時間割変更処理
if(isset($_POST["1-1"])){
    for($i=1; $i<6 ;$i++){
        for($j=1; $j<5 ;$j++){
            if(isset($_POST[$i.'-'.$j])){
                $class_schedule_update[$i][$j] = $_POST[$i.'-'.$j];
            }else break 1;
        }
    }
    foreach ($class_schedule_update as $day => $row){
        foreach ($row as $time => $data){
            prepareQuery("
                update classrooms_lesson_schedule set subject_id = ?
                where classroom_id = ? and day_of_the_week = ? and time_period = ?",
                [$class_schedule_update[$day][$time],$class_update,$day,$time]);
        }
    }
}

//スケジュールを取得。
$schedule = array();
for($i=1; $i<6; $i++){
    $schedule[] = prepareQuery("
        select day_of_the_week, time_period, CLS.subject_id, subject_name 
        from classrooms_lesson_schedule CLS 
        left join subjects S on CLS.subject_id = S.subject_id 
        where classroom_id = ? and day_of_the_week = ?
        order by time_period, time_period"
        ,[$class_update, $i]);
}

if(!isset($schedule)){
    for($i=1;$i<6;$i++){
        for($j=1;$j<5;$j++){
            $update_flg=1;
            prepareQuery("insert into classrooms_lesson_schedule values (?,?,?,0,'10:00:00','10:15:00')"
                ,[$class_update,$i,$j]);
        }
    }
}else {
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!isset($schedule[$i][$j])) {
                $update_flg=1;
                prepareQuery("insert into classrooms_lesson_schedule values (?,?,?,0,'10:00:00','10:15:00')"
                    , [$class_update, $i+1, $j+1]);
            }
        }
    }
}
if(isset($update_flg)){
    $schedule = array();
    for($i=1; $i<5; $i++){
        $schedule[] = prepareQuery("
        select day_of_the_week, time_period, CLS.subject_id, subject_name 
        from classrooms_lesson_schedule CLS 
        left join subjects S on CLS.subject_id = S.subject_id 
        where classroom_id = ? and time_period = ?
        order by time_period, day_of_the_week"
            ,[$class_update, $i]);
    }
}

$subject_list = query("select * from subjects");

function createSelectHTML($subject_list, $selected_subject_id,$day_week, $time_period){
    $resultText = "<select name = ".$day_week.'-'.$time_period.">";
    foreach ($subject_list as $row){
        if($row['subject_id']==$selected_subject_id){
            $resultText .= "<option value=" . $row['subject_id'] . " selected>" . $row['subject_name'] . "</option>";
        }else {
            $resultText .= "<option value=" . $row['subject_id'] . ">" . $row['subject_name'] . "</option>";
        }
    }
    $resultText .= "</select>";
    return $resultText;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Classroom.css">
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
                教室管理
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
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </ul>
</div>

<!--フォームタグ-->
<form action="" method="post">
    <table>
        <div class="kyo">
            <h2 id="kyo_label">教室管理</h2>
            <tr>
                <th></th>
                <th>月曜日</th>
                <th>火曜日</th>
                <th>水曜日</th>
                <th>木曜日</th>
                <th>金曜日</th>
            </tr>
            <?php
            $tableHTML = "";
            //時限ループ
            for ($i=0; $i<4; $i++){
                $tableHTML .=
                    "<tr>"
                    . "<td>". ($i+1) ."限目</td>";
                //日付ループ
                for ($j=0; $j<5; $j++) {
                    $tableHTML .=
                        " <th>"
                        .  "<div class=\"timetable\" class=\"time_menu\">"
                        .  createSelectHTML($subject_list,$schedule[$j][$i]['subject_id'],$j+1,$i+1)
                        .  "</div>"
                        . "</th>";
                }
                $tableHTML .= "</tr>";
            }
            echo $tableHTML;
            ?>
    </table>

    <!--画面リロード-->
    <div class="sub">
        <input type="submit" id="time_ok" value="決定">
    </div></form>


</body>
</html>

