<?php
require_once 'functions.php';
require_logined_session();
require 'db.php';

header('Content-Type:text/html; charset=UTF-8');

//グループ名変更の場合
if(isset($_POST['update_grope_id'])&isset($_POST['update_grope_name'])){
    prepareQuery("update classes set class_name = ? where class_id = ?"
        ,[$_POST['update_grope_name'],$_POST['update_grope_id']]);
    $class = prepareQuery("
                select c.class_id, class_name
                from teachers_homerooms t2
                left join classes c on t2.class_id = c.class_id
                where teacher_id = ?",[$_SESSION['teacher_id']]);
    $_SESSION['class'] = null;
    foreach ($class as $row) {
        $_SESSION['class'][] = ['id' => $row['class_id'],'name' => $row['class_name']];
    }
}

if(isset($_GET['grope_id'])){
    $grope_id = $_GET['grope_id'];
    foreach ($_SESSION['class'] as $row){
        if($_GET['grope_id']==$row['id']) $grope_name = $row['name'];
    }
}else {
    $grope_id = $_SESSION['class'][0]['id'];
    $grope_name = $_SESSION['class'][0]['name'];
}
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
                担当グループ編集
            </h1>
        </div>
        <div id="class" class="title_menu">
            <script type="text/javascript">
                function test () {
                    // 選択されたオプションのバリューを取得する
                    var element = document.getElementById("class_name");
                    // クラスIDを自分に渡すURLを組み立てる
                    var grope_id = element.value;
                    // location.hrefに渡して遷移する
                    location.href = 'ResponsibleEdit.php?grope_id=' + grope_id;
                }
            </script>
            <select id="class_name" onchange="test()">
                <!-- 折り返し処理 -->
                <div id="re">
                    <?php foreach($_SESSION['class'] as $d){?>
                        <!--flex-grow: 1;-->
                        <option value="<?=htmlspecialchars($d['id']) ?>" <?php if(isset($_GET['grope_id']) && $d['id'] == $_GET['grope_id']){echo 'selected';}?>><?=htmlspecialchars($d['name']) ?></option>
                    <?php }$pdo=null; ?>
                </div>
            </select>
        </div>
    </div>

</div>

<!-- 上のメニューバー -->
<div class="bu">
<!--        <a href="AttendanceConfirmation.php" id="attend">状況管理</a>-->
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

    <form action="" method="post">
        <div class="group_name">
            <p>グループ名変更</p>
            <p><input type="text" name="update_grope_name" placeholder="<? echo $grope_name?>" size="50"></p>
        </div>
        <input type="hidden" name="update_grope_id" value="<? echo $grope_id?>">
        <button type="submit">決定</button>
    </form>


</div>
</body>
</html>