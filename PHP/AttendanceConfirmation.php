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
        $class_id = $_SESSION['class']['id'][0];
        $class_name = $_SESSION['class']['name'][0];
    }
    if(isset($_GET['time'])){
        $time = $_GET['time'];
    }else{
        $time = 1;
    }
    //指定なしの場合は今日の日付を設定する。
    if(isset($_GET['day'])){
        $day = $_GET['day'];
    }else{
        $day = date("m-d");
    }

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
        <link rel="stylesheet" media="all" href="CSS/All.css">
        <link rel="stylesheet" media="all" href="CSS/style.css">
        <meta charset="UTF-8">
        <title>responsible</title>
    </head>
    <body>
        <h1>状況管理</h1>
        <!--クラスメニュー-->
        <div class="class">
            <li>授業名
                <ul>
                    <li>プログラミング演習</li>
                </ul>
            </li>
        </div>
    </body>

        <!--日付-->
        <script type="text/javascript">
            weeks=new Array("日","月","火","水","木","金","土");
            today=new Date();
            m=today.getMonth()+1;
            d=today.getDate();
            w=weeks[today.getDay()];
            document.write("<span>",m,"<\/span>月");
            document.write("<span>",d,"<\/span>日");
            document.write("(<span>",w,"<\/span>)");
        </script>

        <a href="./TeacherPro.php" ><?php echo h($_SESSION['teacher_name']) ?></a>

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



    </body>
</html>

