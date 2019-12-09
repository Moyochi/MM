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
        $day = date("Y-m-d");
        $time = 1;
    }
    $day = '2019-09-01';
    $time = 1;
    //指定なしの場合は今日の日付を設定する。
    $subject_name = prepareQuery("
        select subject_name
        from lesson_history LH
          left join subjects S on LH.subject_id = S.subject_id
        where classroom_id = ? and date = ? and time = ?",
        [$class_id, $day, $time])[0];


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
        where date = ? and time_period = ?",[$class_id,date('Y-').$day,$time]);
    try{
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
                状況管理
            </h1>
        </div>
    </div>
</div>



<input type="text" id="datepicker">
<ul id="myList">
</ul>







<!-- 科目選択 -->
<div id="class" class="title_menu">
    <script type="text/javascript">
        function test () {
            // 選択されたオプションのバリューを取得する
            var element = document.getElementById("class_name");
            // クラスIDを自分に渡すURLを組み立てる
            var a = element.value;
            // location.hrefに渡して遷移する
            location.href = 'index.php?class_id=' + a;
            <?php
            //                      $class_idをほかのページでも使えるようにした。
            $_SESSION['index_class_id']=$class_id;
            ?>
        }
    </script>
    <select id="class_name" onchange="test()">
        <!-- 折り返し処理 -->
        <div id="re">
            <?php foreach($_SESSION['class']['name'] as $d){?>
                <!--flex-grow: 1;-->
                <option value="<?=htmlspecialchars($d) ?>" <?php if(isset($_GET['class_id']) && $d == $_GET['class_id']){echo 'selected';}?>><?=htmlspecialchars($d) ?></option>
            <?php }$pdo=null; ?>
        </div>
    </select>
</div>
</div>





<!-- 先生の名前 -->
<a href="./TeacherPro.php" ><?php echo h($_SESSION['username']) ?></a>


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


    <!--写真が入ります-->
    <!--グラフに飛ぶよん-->
    <form action="update.php" method="post">
        <input type="image" src="../image/noimage.gif">

        <p>
        <table>
            <tr>
                <th>出席番号</th>
                <th>名前</th>
                <th>出席率</th>
                <th>出席判定</th>
            </tr>
            <!-- exec_selectによる折り返し処理:開始 -->

            <?php foreach ($student as $row){ ?>
                <tr>
                    <th><?=htmlspecialchars($row['student_num']) ?></th>
                    <th><?=htmlspecialchars($row['student_name'])?></th>
                    <th><?=htmlspecialchars($row['rate'].'%')?></th>
                    <th><?=htmlspecialchars($row['attend_name'])?></th>
                </tr>
            <?php } $pdo=null; ?>
        </table>
        <!--            <input type="submit" value="決定">-->
        <button type=“submit”>決定</button>

    </form>

    ◯人中◯人出席しました。

</div>


</body>
</html>