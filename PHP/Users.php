<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';
?>
<?php
if(isset($_SESSION['current_class_id']) and isset($_SESSION['current_class_name'])){
    $class_id = $_SESSION['current_class_id'];
    $class_name = $_SESSION['current_class_name'];
}else{
    header('Location: index.php');
}
?>

<html xmlns="http://www.w3.org/1999/html">
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Users.css">
    <meta charset="UTF-8">
    <title>Users</title>
</head>
<body>
<div class="header">
    <div class="title">
        <div class="title_text">
            <!--
            flex-grow: 3;
            -->
            <h1>ユーザー検索</h1>

        </div>
        <div id="class" class="title_menu">
            <select id="class_id" onchange="selectClass()" disabled>
                <!-- 折り返し処理 -->
                <div id="re">
                    <?php foreach($_SESSION['class'] as $d){?>
                        <!--flex-grow: 1;-->
                        <option
                                data-id="<?=h($d['id'])?>" data-name="<?=h($d['name'])?>"
                            <?php if($d['id'] == $class_id){echo 'selected';}?>>
                            <?=h($d['name'])?>
                        </option>
                    <?}?>
                </div>
            </select>
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
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </ul>
</div>


<div id="a">
    条件の絞り込みを選択してください。
</div>


<!--フォームタグ-->
<form action="" method="post">

        <!--条件-->
        <div class="if">
            &thinsp;&thinsp;&thinsp;&thinsp;
            <input type="checkbox" id="subject">学科
            <select>
                <option>-</option>
            </select>
            &thinsp;&thinsp;&thinsp;&thinsp;

            <input type="checkbox" id="grade">学年
            <select>
                <option>-</option>
            </select>
            <br>
            &thinsp;&thinsp;&thinsp;&thinsp;


            <input type="checkbox" id="up">出席率
            <input type="text" id="rate">
            <select>
                <option>以上</option>
            </select>
            &thinsp;&thinsp;
            <!--出席率スイッチ-->
            <input type="checkbox" class="switch1" data-off-label="月別" data-on-label="累計">

            <br>
        </div>
</div>


    <div id="b">
        表示順番の指定をしてください。
        <br><br>

        <div class="if2">
            &thinsp;&thinsp;&thinsp;&thinsp;
            <input type="checkbox" id="syouz">昇順
            &thinsp;&thinsp;&thinsp;&thinsp;
            <input type="checkbox" id="kouz">降順
        </div>
    </div>
        <div id="c">
            グループ内検索
            &thinsp;&thinsp;
            <!--グループ内スイッチ-->
            <input type="checkbox" class="switch3" data-off-label="OFF" data-on-label="ON">
        </div>
    </div>

<a href="" id="d">検索</a>

</form>

</body>
</html>
