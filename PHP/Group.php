<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';


?>


<html>
    <head>
        <link rel="stylesheet" media="all" href="../CSS/All.css">
        <link rel="stylesheet" media="all" href="../CSS/Style.css">
        <meta charset="UTF-8">
        <title>Group</title>
    </head>
    <body>
    <h1 class="header">
            <div class="title">
                <div class="title_text">
                    <h1>グループ管理</h1>
                </div>
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
           追加するグループを選んでください。<br>

                <!--フォームタグ-->
                <p><form action="" method="post">

                    <input type="checkbox" id="addclass" value="JK1">情報工学科１

                    <div class="sub">
                        <button type=“button”><a href="Responsible.html">OK</a></button>
                    </div></form>

    </body>
</html>

