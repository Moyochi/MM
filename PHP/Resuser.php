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

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Responsible.css">
    <link rel="stylesheet" media="all" href="../CSS/Resuser.css">
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


<form action="" method="post">
    <!-- 写真が入ります -->
    <input type="image" src="" id="img">


    <table>
        <thead>
        <tr>
            <th>名前</th>
        </tr>
        </thead>
        <!-- exec_selectによる折り返し処理:開始 -->

        <tbody>
        <?php foreach ($student as $st){ ?>
            <tr>
                <th><?=htmlspecialchars($st['student_num']) ?></th>
                <th><a href="TeacherPro.php"><?=htmlspecialchars($st['student_name'])?></a></th>
            </tr>
        <?php } $pdo=null; ?>
        </tbody>
    </table>
</form>
<a href="./Resuser.php" id="aedit">編集</a>


</body>
</html>