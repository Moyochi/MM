<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';
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
    </body>
</html>