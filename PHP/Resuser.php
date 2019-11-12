<?php

?>

<html>
    <head>
        <link rel="stylesheet" media="all" href="../CSS/All.css">
        <meta charset="UTF-8">
        <title>Resuser</title>
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
                    <h1>管理者ユーザー一覧</h1>

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

                <!--フォームタグ-->
                <p><form action="" method="post">

                    <!--写真が入ります-->
                    <!--グラフに飛ぶよん-->
                    <form action="StudentGraph.html" method="post"></form>
                    <input type="image" src="../image/noimage.gif" id="img">

                    名前 教師ID

                    <div class="sub">
                        <button type=“button”><a href="ResuserEdit.html">編集</a></button>
                    </div></form>

    </body>
</html>
