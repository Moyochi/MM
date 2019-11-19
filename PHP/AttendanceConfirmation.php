<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';
if(isset($_GET['class_id'])){
    $class_id=$_GET['class_id'];
}else{
    //login.phpから飛んできた1行目のclass_idが入る。
    $class_id=$_SESSION['class']['id'][0];
}

//生徒の情報viewを選択
$student = prepareQuery("select * from load_responsible_1 where class_id = ?",[$class_id]);
$subject= prepareQuery("select * from mm.students_subjects where class_id= ?",[$class_id]);

var_dump($_SESSION);

try{
}catch (PDOException $exception){
    die('接続エラー:'.$exception->getMessage());
}
try{
}catch (PDOException $exception){
    die('接続エラー:'.$exception->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
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
                状況管理
            </h1>
        </div>
    </div>
</div>



<input type="text" id="datepicker">
<ul id="myList">
</ul>







<!-- 科目選択 -->
<div id="class" class="title_menu">
    <script type="text/javascript">
        function test () {
            // 選択されたオプションのバリューを取得する
            var element = document.getElementById("class_name");
            // クラスIDを自分に渡すURLを組み立てる
            var a = element.value;
            // location.hrefに渡して遷移する
            location.href = 'index.php?class_id=' + a;
            <?php
            //                      $class_idをほかのページでも使えるようにした。
            $_SESSION['index_class_id']=$class_id;
            ?>
        }
    </script>
    <select id="class_name" onchange="test()">
        <!-- 折り返し処理 -->
        <div id="re">
            <?php foreach($_SESSION['class']['name'] as $d){?>
                <!--flex-grow: 1;-->
                <option value="<?=htmlspecialchars($d) ?>" <?php if(isset($_GET['class_id']) && $d == $_GET['class_id']){echo 'selected';}?>><?=htmlspecialchars($d) ?></option>
            <?php }$pdo=null; ?>
        </div>
    </select>
</div>
</div>





<!-- 先生の名前 -->
<a href="./TeacherPro.php" ><?php echo h($_SESSION['teacher_name']) ?></a>


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

<!--写真が入ります-->
<!--グラフに飛ぶよん-->
<form action="update.php" method="post">
    <input type="image" src="../image/noimage.gif">

    <p>
    <table id="ac">
        <tr class="ad">
            <th class="as">出席番号</th>
            <th class="as">名前</th>
            <th class="as">出席率</th>
            <th class="as">出席判定</th>
        </tr>
        <!-- exec_selectによる折り返し処理:開始 -->

        <?php foreach ($student as $st){ ?>
            <tr class="ad">
                <th class="as"><?=htmlspecialchars($st['student_num']) ?></th>
                <th class="as"><?=htmlspecialchars($st['student_name'])?></th>
                <td class="as"><?=htmlspecialchars($st['attend_rate']) ?></td><!-- 出席率 -->
                <td class="as"><php? ?></td><!--<th><?//=htmlspecialchars($row['累計の遅刻数'])?></th> -->
            </tr>
        <?php } $pdo=null; ?>
    </table>
    <!--            <input type="submit" value="決定">-->
    <button type=“submit”>決定</button>

</form>

◯人中◯人出席しました。


</body>
</html>