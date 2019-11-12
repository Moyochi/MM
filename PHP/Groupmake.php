<?php

?>


<html>
    <head>
        <link rel="stylesheet" media="all" href="../CSS/All.css">
        <meta charset="UTF-8">
        <title>Groupmake</title>
    </head>
    <body>
        <div class="header">
            <div class="title">
                <div class="title_text">
                    <h1>グループ作成</h1>　
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


        ファイルアップロード
        <!--フォームタグ-->
        <form action="" method="post">
            <!--アップロードボタン-->
            <button><input type="image" src="../image/file.png"></button>
        </form>


    </body>
</html>
