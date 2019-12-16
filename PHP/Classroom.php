<?php
require 'db.php';
require_once 'functions.php';
require_logined_session();
header('Content-Type:text/html; charset=UTF-8');
?>
<?php
if(isset($_SESSION['current_class_id']) and isset($_SESSION['current_class_name'])){
    $class_id = $_SESSION['current_class_id'];
    $class_name = $_SESSION['current_class_name'];
}else{
    header('Location: index.php');
}
//左上のクラスの選択で利用。
$classroom_list = query("select * from classrooms order by classroom_name");
//スケジュールを表示するクラスについて、クラスの指定がある場合はpostで受け取り、
//指定がない場合(初回表示時)にはクラスリストの一番上が選択される。
if(isset($_POST['update_class_id'])){
    $update_class_id = $_POST['update_class_id'];
}else{
    $update_class_id = $classroom_list[0]['classroom_id'];
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
                [$class_schedule_update[$day][$time],$update_class_id,$day,$time]);
        }
    }
}
//スケジュールを取得。
$schedule_get = array();
for($i=1; $i<6; $i++){
    $schedule_get[] = prepareQuery("
        select day_of_the_week, time_period, CLS.subject_id, subject_name 
        from classrooms_lesson_schedule CLS 
        left join subjects S on CLS.subject_id = S.subject_id 
        where classroom_id = ? and day_of_the_week = ?
        order by time_period, time_period"
        ,[$update_class_id, $i]);
}
$schedule = array();
foreach ($schedule_get as $row){
    foreach ($row as $data){
        $schedule[$data['day_of_the_week']-1][$data['time_period']-1] = $data;
    }
}

if(!isset($schedule)){
    for($i=1;$i<6;$i++){
        for($j=1;$j<5;$j++){
            $update_flg=1;
            prepareQuery("insert into classrooms_lesson_schedule values (?,?,?,0,'10:00:00','10:15:00')"
                ,[$update_class_id,$i,$j]);
        }
    }
}else {
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 4; $j++) {
            if (!isset($schedule[$i][$j])) {
                $update_flg=1;
                prepareQuery("insert into classrooms_lesson_schedule values (?,?,?,0,'10:00:00','10:15:00')"
                    , [$update_class_id, $i+1, $j+1]);
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
            ,[$update_class_id, $i]);
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
        <div id="class" class="title_menu">
            <select id="class_id" onchange="selectClass()" disabled>
                <!-- 折り返し処理 -->
                <div id="re">
                    <?php foreach($_SESSION['class'] as $d){?>
                        <!--flex-grow: 1;-->
                        <option
                                data-id="<?=h($d['id'])?>" data-name="<?=h($d['name'])?>"
                            <?php if($d['id'] == $class_id){echo 'selected';}?>>
                            <?=h($d['name'])?>
                        </option>
                    <?}?>
                </div>
            </select>
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
            <script>
                function selectClassroom() {
                var element = document.getElementById("classroom_id_selecter");
                var selectedIndex = element.selectedIndex;
                var form = document.createElement("form");
                form.setAttribute("action", location.href);
                form.setAttribute("method", "post");
                form.style.display = "none";
                document.body.appendChild(form);
                var data = {
                'update_class_id':element.options[selectedIndex].dataset.id,
                }
                for (var paramName in data) {
                var input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('name', paramName);
                input.setAttribute('value', data[paramName]);
                form.appendChild(input);
                }
                form.submit();
                }
            </script>
            <h2 id="kyo_label">
                教室管理&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="classroom_id_selecter" name="class_update" onchange="selectClassroom()">
                    <?php
                    foreach ($classroom_list as $row){
                        if($row['classroom_id']==$update_class_id){
                            echo "<option data-id=$row[classroom_id] data-name=$row[classroom_name] selected>$row[classroom_name]</option>";
                        }else{
                            echo "<option data-id=$row[classroom_id] data-name=$row[classroom_name]>$row[classroom_name]</option>";
                        }
                    }
                    ?>
                </select>
            </h2>
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
                echo 'i:'.$i.' ';
                $tableHTML .=
                    "<tr>"
                    . "<td>". ($i+1) ."限目</td>";
                //日付ループ
                for ($j=0; $j<5; $j++) {
                    echo 'j:'.$j.' ';
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
        <input type="hidden" name="update_class_id" value=<?=$update_class_id?>>
        <input type="submit" id="time_ok" value="決定">
    </div></form>


</body>
</html>

