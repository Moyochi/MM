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
    <title>Resuser.php</title>
</head>
<body>


<!--どのアカウントで入ったか確認-->

<div class="header">

    <div class="title">

        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1 class="head">
                <!-- 題名 -->
                管理者ユーザー
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
    <a href=ResponsibleEdit.php id="attend">編集</a>


<form action="" method="post">
    <!-- 写真が入ります -->
    <!-- グラフに飛ぶよん -->
    <form action="ぐらふのPHP" method="post"></form>
    <input type="image" src="" id="img">



    <!-- クラスメンバーの表示 -->
    <style>
        td {
            border: 1px solid #000;
        }
        td:nth-child(1),
        td:nth-child(2) {
            border: none;
        }
    </style>

    <table>
        <thead>
        <tr>
            <th>出席番号</th>
            <th>名前</th>
            <th>月別の出席の推移</th>
            <th>累計の遅刻数</th>
            <th>欠席数</th>
            <th>早退数</th>
            <th>出席率</th>
        </tr>
        </thead>
        <!-- exec_selectによる折り返し処理:開始 -->

        <tbody>
        <?php foreach ($student as $st){ ?>
            <tr>
                <th><?=htmlspecialchars($st['student_num']) ?></th>
                <th><a href="TeacherPro.php"><?=htmlspecialchars($st['student_name'])?></a></th>
                <td>100</td><!-- <th><?//=htmlspecialchars($row['月別の出席の推移'])?></th> -->
                <td>100</td><!--<th><?//=htmlspecialchars($row['累計の遅刻数'])?></th> -->
                <td>100</td><!--<th><?//=htmlspecialchars($row['欠席数'])?></th> -->
                <td>100</td><!--<th><?//=htmlspecialchars($row['早退数'])?></th> -->
                <td>100</td><!--<th><?//=htmlspecialchars($row['出席率'])?></th> -->
            </tr>
        <?php } $pdo=null; ?>
        </tbody>
    </table>
    <a href="./Resuser.php" id="edit">編集</a>
</form>


</body>
</html>