<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';


?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Graph</title>
    </head>
    <body>
        <div class="header">
            <div class="title">
                <!--
                color: #364e96;
                border: solid 3px #364e96;
                padding: 0.5em;
                border-radius: 0.5em;
                display: flex;
                -->
                <div class="title_text">
                    <!--
                    flex-grow: 3;
                    -->
                    <h1>グループ管理</h1>

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

                追加するグループを選んでください。<br>

                <!--フォームタグ-->
                <p><form action="" method="post">

                    <input type="checkbox" id="addclass" value="JK1">情報工学科１

                    <div class="sub">
                        <button type=“button”><a href="Responsible.html">OK</a></button>
                    </div></form>

    </body>
</html>

