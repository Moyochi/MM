<?php
require_once 'functions.php';
require_logined_session();
require 'db.php';
header('Content-Type:text/html; charset=UTF-8');
?>
<?php
if( ( !isset($_GET['student_num'])or!isset($_GET['class_id']) ) and !isset($_GET['student_id']) ){
    header('Location: index.php');
}
if(isset($_GET['student_num']) and isset($_GET['class_id'])) {
    $student = prepareQuery("
    select LS1.student_id, LS1.student_name, C.class_name, S.sex, late, absence, early, LS1.attend_rate , month, ARM.attend_rate attend_rate_month
    from load_responsible_1 LS1
      left join students S on LS1.student_id = S.student_id
      left join classes C on S.class_id = C.class_id
    left join attend_rate_month ARM on S.student_id = ARM.student_id
    where LS1.student_num = ? and LS1.class_id = ? and month = ?"
        , [$_GET['student_num'], $_GET['class_id'], date('m')]);
}elseif(isset($_GET['student_id'])){
    $student = prepareQuery("
    select LS1.student_id, LS1.student_name, C.class_name, S.sex, late, absence, early, LS1.attend_rate , month, ARM.attend_rate attend_rate_month
    from load_responsible_1 LS1
      left join students S on LS1.student_id = S.student_id
      left join classes C on S.class_id = C.class_id
    left join attend_rate_month ARM on S.student_id = ARM.student_id
    where S.student_id = ? and month = ?",
        [$_GET['student_id'], date('m')]);
}
if(isset($student)){
    $student = $student[0];
}
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Responsible.css">
    <link rel="stylesheet" media="all" href="../CSS/Style.css">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>StudentPro.html</title>
</head>
<body>


<!--どのアカウントで入ったか確認-->

<div class="header">

    <div class="title">

        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1 class="head">
                <!-- 題名 -->
                学生プロフィール
            </h1>
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
        <li><a href="Groupmake.php">グループ作成</a></li>
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </ul>
</div>
学籍番号<br>
<a id="snumber"><?php echo $student['student_id']; ?></a>
<br><br>
学科<br>
<a id="subject"><?php echo h($student['class_name'])?></a>
<br><br>
<!--    学年<br>--><!--学年は学科に含まれる-->
<!--    <a id="grade">--><?php //echo $student[0]['grade']?><!--</a>-->
<!--    <br><br>-->
名前<br>
<a id="name"><?php echo h($student['student_name'])?></a>
<br><br>
性別<br>
<a id="sex"><?php echo $student['sex']?></a>
<br><br>
<!--今月の出席率は新しく追加したので、idがついていないです。必要であれば付けてください。-->
今月の出席率<br>
<a><?php echo $student['attend_rate_month'] ?></a>
<br><br>
遅刻数<br>
<a id="late"><?php echo $student['late'] ?></a>
<br><br>
欠席数<br>
<a id="absence"><?php echo $student['absence'] ?></a>
<br><br>
早退数<br>
<a id="absence"><?php echo $student['early'] ?></a>
<br><br>
出席率<br>
<a id="absence"><?php echo $student['attend_rate'] ?></a>
<br><br>
<form action="StudentGraph.php" method="get">
    <input type="hidden" name="student_id" value="<?=$student['student_id']?>">
    <input type="submit" value="グラフ表示">
</form>
</body>
</html>