<?php
require_once 'functions.php';
require_logined_session();
require 'db.php';

header('Content-Type:text/html; charset=UTF-8');

if(isset($_GET['class_id'])){
    $class_id = $_GET['class_id'];
    $class_name = $_GET['class_name'];
}else{
    //別のページから飛んできた時は1行目のclassが入る。
    $class_id = $_SESSION['class'][0]['id'];
    $class_name = $_SESSION['class'][0]['name'];
}
if(isset($_GET['time']) and isset($_GET['day'])){
    $day = $_GET['day'];
    $time = $_GET['time'];
}else{
    $day = $day = '2019-09-01';
//    $day = date("Y-m-d");
    $time = 1;
}

try{
    $subject_name = prepareQuery("
        select subject_name
        from lesson_history LH
          left join subjects S on LH.subject_id = S.subject_id
        where class_id = ? and date = ? and time = ?",
        [$class_id, $day, $time]);
    if($subject_name!=array()){
        $subject_name = $subject_name[0];
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
        where date = ? and time_period = ?",[$class_id,$day,$time]);
    }else{
        $error = "該当の時間に授業は行われていません。";
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
                出席簿
            </h1>
        </div>
    </div>
</div>


<!-- 先生の名前 -->
<a href="./TeacherPro.php" ><?php echo h($_SESSION['teacher_name']) ?></a>

<!-- 上のメニューバー -->
<div class="bu">
    <!--    <a href="AttendanceConfirmation.php" id="attend">状況管理</a>-->
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
        <li><a href="Groupmake.php">グループ作成</a></li>
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </ul>



    <!--写真が入ります-->
    <form action="update.php" method="post" >

        <!-- 時間割選択 -->
        <div id="class" class="title_menu">
            <select name="time_period"  id="class_name" onchange="cale()">
                <option value="1">1限目</option>
                <option value="2">2限目</option>
                <option value="3">3限目</option>
                <option value="4">4限目</option>
            </select>
        </div>

        <!-- 日付の選択 -->
        <input name="datepicker" type="text" id="datepicker" onchange="cale()" value="<?php if(!empty($_GET['day'])) echo $_GET['day'] ?>">
        <ul id="myList"></ul>
        <input type="hidden" name="day_js" value="hoge">


        <table>
            <tr>
                <th>出席番号</th>
                <th>名前</th>
                <th>出席率</th>
                <th>出席判定</th>
            </tr>
            <!-- exec_selectによる折り返し処理:開始 -->

            <?php foreach ($student as $row){ ?>
                <input type="hidden" value="<?=htmlspecialchars($row['student_id']) ?>" name="student_id[]">
                <th><?=htmlspecialchars($row['student_num']) ?></th>
                <th><?=htmlspecialchars($row['student_name'])?></th>
                <th><?=htmlspecialchars($row['rate'].'%')?></th>
                <th>
                    <select name="attend_id[]">
                        <option value="1" <?php if($row['attend_id'] == 1)echo 'selected';?>>出席</option>
                        <option value="2" <?php if($row['attend_id'] == 2)echo 'selected';?>>欠席</option>
                        <option value="3" <?php if($row['attend_id'] == 3)echo 'selected';?>>遅刻</option>
                        <option value="4" <?php if($row['attend_id'] == 4)echo 'selected';?>>早退</option>
                        <option value="5" <?php if($row['attend_id'] == 5)echo 'selected';?>>欠課</option>
                        <option value="6" <?php if($row['attend_id'] == 6)echo 'selected';?>>遅延</option>
                        <!--                        --><?//=htmlspecialchars($row['attend_name'])?>
                    </select>
                </th>
                </tr>
            <?php } $pdo=null; ?>
        </table>
        <!--            <input type="submit" value="決定">-->
        <button type=“submit”>決定</button>
        <br>
        <br>
        ◯人中◯人出席しました。
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
        function time() {

        }

        function cale () {
            // クラスIDを自分に渡すURLを組み立てる
            let datapicker = $('#datepicker').val();
            // 選択されたオプションのバリューを取得する
            let date = $("#class_name").val();



            // クラスIDを自分に渡すURLを組み立てる
            let params = getParameter();
            // params['class_id'] = params['class_id'];
            params['day'] = datapicker;
            params['time'] = date;
            let url = setParameter(params);
            console.log(url);

            location.href = url;


            // location.hrefに渡して遷移する

            // location.href = 'ACM1.php?time=' + a;
            <?php
            //                      $class_idをほかのページでも使えるようにした。
            $_SESSION['time']=$time;
            ?>
        }

        //パラメータを設定したURLを返す
        function setParameter( paramsArray ) {
            var resurl = location.href.replace(/\?.*$/,"");
            for ( key in paramsArray ) {
                resurl += (resurl.indexOf('?') == -1) ? '?':'&';
                resurl += key + '=' + paramsArray[key];
            }
            return resurl;
        }

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