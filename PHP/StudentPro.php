<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';

$student = prepareQuery('select * from load_responsible_1 where student_num = ? and class_id = ?',[$_GET['id'],$_GET['class_id']]);
var_dump($student);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Responsible.css">
    <link rel="stylesheet" media="all" href="../CSS/Style.css">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>StudentPro.html</title>
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
    学籍番号<br>
    <a id="snumber"><?php echo $student[0]['student_id']; ?></a>
    <br><br>
    学科<br>
    <a id="subject"><?php echo $student[0]['subject_id']?></a>
    <br><br>
    学年<br>
    <a id="grade"><?php echo $student[0]['grade']?></a>
    <br><br>
    名前<br>
    <a id="name"><?php echo $student[0]['student_name']?></a>
    <br><br>
    性別<br>
    <a id="sex"><?php echo $student['sex']?></a>
    <br><br>
    遅刻数<br>
    <a id="late"><?php echo $student['late'] ?></a>
    <br><br>
    欠席数<br>
    <a id="absence"><?php echo $student['absence'] ?></a>
    <br><br>
    早退数<br>
    <a id="absence"><?php echo $student['early'] ?></a>
    <br><br>
    出席率<br>
    <a id="absence"><?php echo $student['attend_rate'] ?></a>
    <br><br>
</div>
</body>
</html>