<?php
require_once 'functions.php';
require_logined_session();
require 'db.php';

header('Content-Type:text/html; charset=UTF-8');

if(isset($_POST['class_id']) and isset($_POST['class_name'])){
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $_SESSION['current_class_id'] = $class_id;
    $_SESSION['current_class_name'] = $class_name;
}else{
    if(isset($_SESSION['current_class_id']) and isset($_SESSION['current_class_name'])) {
        $class_id = $_SESSION['current_class_id'];
        $class_name = $_SESSION['current_class_name'];
    }else{
        header('Location: index.php');
    }
}
if(isset($_GET['time']) and isset($_GET['day'])){
    $date = $_GET['day'];
    $time = $_GET['time'];
}else{
    $date = date("Y-m-d");
    $time = 1;
}

try{
    //教科名を実施授業履歴から取得。
    $subject_name = prepareQuery("
        select subject_name
        from lesson_history LH
          left join subjects S on LH.subject_id = S.subject_id
        where classroom_id = ? and date = ? and time = ?",
        [$class_id, $date, $time]);
    $time_period_array = prepareQuery("
        select time 
        from lesson_history LH 
          left join lesson_history_grope grope on LH.lesson_history_id = grope.lesson_history_id
        where class_id = ? and date = ?",
        [$class_id,$date]);
    if($subject_name!=array()){
        //その時間に行われた授業名。
        $subject_name = $subject_name[0]['subject_name'];
        //student_attend_lessonに登録されている出席情報を基に他情報(学生名など)をjoinして取得。
        $student = prepareQuery("
        select SQ.student_id, student_num, student_name, SAL.attend_id, attend_name, ROUND(rate)rate
        from students_attend_lesson SAL
          right join (SELECT S.student_id, CS.student_num, student_name
          FROM students S
            INNER JOIN classes_students CS on CS.student_id = S.student_id
          WHERE S.class_id = ?
          ORDER BY student_num asc) SQ on SAL.student_id = SQ.student_id
          left join attend a on SAL.attend_id = a.attend_id
          left join lesson_rate LR on SQ.student_id = LR.student_id and SAL.subject_id = LR.subject_id
        where date = ? and time_period = ?",[$class_id,$date,$time]);
    }else{
        $error = "該当の時間に実施された授業はありません。";
        if($time_period_array == array()){
            $error = "該当の日付に実施された授業はありません。";
        }
    }
}catch (PDOException $exception){
    die('接続エラー:'.$exception->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Responsible.css">
    <link rel="stylesheet" media="all" href="../CSS/ACMS1.css">
    <link rel="stylesheet" media="all" href="../CSS/Style.css">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta charset="UTF-8">
    <title>ACMS</title>
</head>
<body>
<div class="header">
    <div class="title">

        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1 class="head">
                <!-- 題名 -->
                出席簿
            </h1>
        </div>
        <div id="class" class="title_menu">
            <script type="text/javascript">
                function selectClass() {
                    var element = document.getElementById("class_id");
                    var selectedIndex = element.selectedIndex;
                    var timeSelecter = document.getElementById("time_period");
                    var form = document.createElement("form");
                    if(timeSelecter.selectedIndex == -1){
                        form.setAttribute("action", location.href.replace(/\?.*$/,"") + '?day=' + $('#datepicker').val() + '&time=1');
                    }else{
                        form.setAttribute("action", location.href.replace(/\?.*$/,"") + '?day=' + $('#datepicker').val() + '&time=' + timeSelecter.options[timeSelecter.selectedIndex].value);
                    }
                    form.setAttribute("method", "post");
                    form.style.display = "none";
                    document.body.appendChild(form);
                    var data = {
                        'class_id':element.options[selectedIndex].dataset.id,
                        'class_name':element.options[selectedIndex].dataset.name,
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
            <select id="class_id" onchange="selectClass()">
                <!-- 折り返し処理 -->
                <div id="re">
                    <?php foreach($_SESSION['class'] as $d){?>
                        <!--flex-grow: 1;-->
                        <option data-id="<?=h($d['id'])?>" data-name="<?=h($d['name'])?>"
                            <?php if($d['id'] == $class_id){echo 'selected';}?>>
                            <?=h($d['name'])?>
                        </option>
                    <?}?>
                </div>
            </select>
        </div>
    </div>
</div>


<!-- 先生の名前 -->
<a href="./TeacherPro.php" ><?=h($_SESSION['teacher_name']) ?></a>
<p><?if($subject_name!=null)echo h($subject_name)?></p>

<!-- 上のメニューバー -->
<div class="bu">
</div>
<!--　検索バー -->
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



    <!--写真が入ります-->
    <form action="update.php" method="post" >

        <!-- 時間割選択 -->
        <div id="class" class="title_menu">
            <select name="time_period"  id="time_period" onchange="selectClass()">
                <?php
                foreach ($time_period_array as $i => $row){
                    $htmlText = "<option value='".$row['time']."'";
                    if($i+1 == $time){$htmlText .= 'selected';}
                    $htmlText .= ">".$row['time']."限目</option>";
                    echo $htmlText;
                } ?>
            </select>
        </div>

        <!-- 日付の選択 -->
        <input name="datepicker" type="text" id="datepicker" onchange="selectClass()" value="<?=$date?>">
        <ul id="myList"></ul>

        <?php
            if(!isset($error)){
                echo "
                    <table>
                        <tr>
                            <th>出席番号</th>
                            <th>名前</th>
                            <th>出席率</th>
                            <th>出席判定</th>
                        </tr>";
                        //出席(attend_id = 1,3,4,6)の数をカウントする変数
                        //出席者の行毎にattend_presenceを増加
                        $attend_presence = 0;
                        $is_attend = ['1','3','4','6'];
                        foreach ($student as $i => $row){
                            if(in_array($row['attend_id'],$is_attend,true)) $attend_presence++;
                            echo "
                            <input type='hidden' name= $i value= $row[student_id]>
                            <th>".h($row['student_num'])."</th>
                            <th><a id='name' href='StudentPro.php?student_num=".h($row['student_num'])."&class_id=".h($class_id)."'>".h($row['student_name'])."</a></th>
                            <th>".h($row['rate'])."%</th>
                            <th>
                                <select name=attend_id_$i>
                                    <option value='1'"; if($row['attend_id']==1)echo' selected';echo ">出席</option>
                                    <option value='2'"; if($row['attend_id']==2)echo' selected';echo ">欠席</option>
                                    <option value='3'"; if($row['attend_id']==3)echo' selected';echo ">遅刻</option>
                                    <option value='4'"; if($row['attend_id']==4)echo' selected';echo ">早退</option>
                                    <option value='5'"; if($row['attend_id']==5)echo' selected';echo ">欠課</option>
                                    <option value='6'"; if($row['attend_id']==6)echo' selected';echo ">遅延</option>
                                </select>
                            </th>
                        </tr>";
                        }
                        echo"
                    </table>
                 ";
            }else{
                echo $error.'<br>';
            }
        ?>
        <input type="hidden" name="class_id" value="<?=$class_id ?>">
        <input type="hidden" name="class_name" value="<?=$class_name ?>">
        <button type=“submit”>変更</button>
        <br>
        <br>
        <div id="sums">
            <?if(isset($student))echo count($student)."人中".$attend_presence."人出席しました。"?>
        </div>

    </form>

</div>



    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">

        // html読み込み後に実行されるJavascriptの処理
        $(function(){
            let param = getParameter();
            $('#datepicker').datepicker({dateFormat:'yy-mm-dd'});
            if (param['day'] === undefined) {
                // パラメータで分岐させる
                $('#datepicker').datepicker('setDate', new Date());
            }
        });

        //パラメータを取得する
        function getParameter(){
            var paramsArray = [];
            var url = location.href;
            parameters = url.split("#");
            if( parameters.length > 1 ) {
                url = parameters[0];
            }
            parameters = url.split("?");
            if( parameters.length > 1 ) {
                var params   = parameters[1].split("&");
                for ( i = 0; i < params.length; i++ ) {
                    var paramItem = params[i].split("=");
                    paramsArray[paramItem[0]] = paramItem[1];
                }
            }
            return paramsArray;
        }
    </script>
</body>
</html>