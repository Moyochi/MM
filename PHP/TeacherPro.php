<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';

    $teacher = prepareQuery("
                SELECT T.teacher_id,T.teacher_name,T.tell,T.mail,T.personalnum,TH.class_id,class_name 
                FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id) 
                INNER JOIN teachers_homerooms TH ON T.teacher_id=TH.teacher_id) 
                INNER JOIN classes C ON TH.class_id=C.class_id 
                WHERE L.login_id = ? 
                ORDER BY class_id",[$_SESSION['teacher_id']]);

    try{
    }catch (PDOException $exception){
        die('接続エラー:'.$exception->getMessage());
    }
var_dump($_SESSION);
?>

<html xmlns="http://www.w3.org/1999/html">
    <head>
        <meta charset="UTF-8">
        <title>TeacherPro</title>
    </head>
    <body>
        <div class="header">
                <p class="title">
                    <div class="title_text">
                        <h1>教師プロフィール</h1>

                    </div>

                    <!--検索バー-->
                    🔎<input type="text" id="sa-ch">

                    <!--メニューバー-->
            <div class="tabs">
                <li><a href="./index.php">担当グループ</a></li>
                <li><a href="Group.php">グループ管理</a></li>
                <li><a href="Users.php">ユーザー検索</a></li>
                <li><a href="Resuser.php">管理者ユーザー一覧</a></li>
                <li><a href="Groupmake.php">グループ作成</a></li>
                <li><a href="Classroom.php">教室管理</a></li>
                <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
            </div>



<!--                    <p id="kyoid">教師ID</p>-->
                    <p id="kyoid1"><?php echo $_SESSION['teacher_id'] ?></p>
<!--                    <p id="name">名前<p>-->
                    <p id="name1"><?php echo $teacher[0]['teacher_name']; ?><p>
<!--                    <p id="class">担当クラス</p>-->
                    <p id="class1"><?php foreach ($_SESSION['class']['name'] as $st){ ?>
                        <tr>
                            <?=htmlspecialchars($st['class_name']) ?>
                        </tr>
                    <?php } $pdo=null; ?></p>
<!--                    <p id="asomail">麻生メアド</p>-->
                    <p id="asomail1"><?php echo $teacher[0]['mail'] ?></p>
<!--                    <p id="tell">内線</p>-->
                    <p id="tell1"><?php echo $teacher[0]['tell']; ?></p>
<!--                    <p id="personaltell">個人番号</p>-->
                    <p id="personaltell1">0<?php echo $teacher[0]['personalnum']; ?></p>


                    <p id="class">担当クラス</p>
                    >　



                    <!--写真が入ります-->
                    <input type="image" src="../image/noimage.gif" 　id="img">
                    自己紹介
                    <input type="text" id="introduction" size="30">
                    <!--LINEQRが入ります-->
                    <input type="image" src="../image/line.png" 　id="png">



    </body>
</html>

