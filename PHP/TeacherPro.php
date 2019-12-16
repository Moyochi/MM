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

try{
$teacher = prepareQuery("
    SELECT T.teacher_id,T.teacher_name,T.tell,T.asomail,T.personalnum,TH.class_id,class_name 
    FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id) 
    INNER JOIN teachers_homerooms TH ON T.teacher_id=TH.teacher_id) 
    INNER JOIN classes C ON TH.class_id=C.class_id 
    WHERE T.teacher_id = ?
    ORDER BY class_id",[$_SESSION['teacher_id']]);
}catch (PDOException $exception){
    die('接続エラー:'.$exception->getMessage());
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
                教師プロフィール
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
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>

    </ul>
    <p id="kyoid">教師ID<br>
        <?php echo $teacher[0]['teacher_name'] ?></p>
        <?php echo '<br>'?>

    <p id="name">名前<br>
    <?php echo $teacher[0]['teacher_name']; ?><p>
        <?php echo '<br>'?>

    <p id="class">担当クラス<br>
        <?php foreach ($teacher as $st){ ?>
        <tr>
            <p><?=htmlspecialchars($st['class_name']) ?></p>
        </tr>
        <?php } $pdo=null; ?>
    </p>
    <?php echo '<br>'?>

    <p id="asomail">麻生メアド<br>
        <?php echo ":".$teacher[0]['asomail'] ?></p>
    <?php echo '<br>'?>
    <p id="tell">内線<br>
        <?php echo ":".$teacher[0]['tell']; ?></p>
    <?php echo '<br>'?>
    <p id="personaltell">個人番号<br>
        0<?php echo ":".$teacher[0]['personalnum']; ?></p>

</div>
</body>
</html>

