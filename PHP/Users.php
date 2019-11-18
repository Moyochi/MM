<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';
?>

<html>
    <head>
        <link rel="stylesheet" media="all" href="../CSS/All.css">
        <link rel="stylesheet" media="all" href="../CSS/Users.css">
        <meta charset="UTF-8">
        <title>Users</title>
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
                    <h1>ユーザー検索</h1>

                </div>
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

        <div id="a">
        条件の絞り込みを選択してください。
        </div>

        <!--フォームタグ-->
        <p><form action="" method="post">

            <!--条件-->
            <input type="checkbox" id="subject">学科
            <select>
                <option>-</option>
            </select>

            <input type="checkbox" id="grade">学年
            <select>
                <option>-</option>
            </select>

            <br><br>

            <input type="checkbox" id="up">出席率
            <input type="text" id="rate">
            <select>
                <option>以上</option>
            </select>

            <!--出席率スイッチ-->
            <div id="sw1">
                <input type="checkbox" id="switch1">
                <label for="switch1"></label>
                <div id="swImg"></div>
            </div>



            表示順番の指定をしてください。<br><br><br>

            <input type="checkbox" id="syouz">昇順
            <input type="checkbox" id="kouz">降順

            <br><br>

            グループ内検索<br><br><br>

            <!--グループ内スイッチ-->
            <div id="sw3">
                <input type="checkbox" id="swi1">
                <label for="switch3"></label>
                <div id="swImg"></div>
            </div>


            <div class="sub">
                <a href="">検索</a>
            </div></<form>

        </form>

    </body>
</html>
