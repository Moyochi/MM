<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';

var_dump($_GET);

if ($_GET['class_id'] ){
}
?>

<!DOCTYPE html>
<html>
<head>
    <!--        <link rel="stylesheet" media="all" href="CSS入れる予定">-->
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>担当グループ</title>
</head>
<body>

<!--　DB接続　-->
<?php

    $teacher = prepareQuery("
    SELECT TH.class_id,class_name 
    FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id) 
    INNER JOIN teacher_homeroom TH ON T.teacher_id=TH.teacher_id) 
    INNER JOIN classes C ON TH.class_id=C.class_id 
    WHERE L.login_id = ? 
    ORDER BY class_id",[$_SESSION['username']]);

    $student = prepareQuery("SELECT TH.class_id,class_name,CS.student_num,S.student_name
            FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id)
            INNER JOIN teacher_homeroom TH ON T.teacher_id=TH.teacher_id)
            INNER JOIN classes C ON TH.class_id=C.class_id
            INNER JOIN students S ON S.class_id = C.class_id
            INNER JOIN classes_students CS ON CS.class_id = S.class_id and CS.student_id = S.student_id
            WHERE L.login_id = ?
            AND C.class_id = ?
            GROUP BY S.student_name
            ORDER BY student_num asc ,class_id",[$_SESSION['username'], $_GET['class_id']]);

    try{
    }catch (PDOException $exception){
        die('接続エラー:'.$exception->getMessage());
    }
?>



<!--どのアカウントで入ったか確認-->
<p><?php echo h($_SESSION['username']); ?>さんいらっしゃい！</p>


<div class="header">
    <div class="title">
        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1>担当グループ</h1>
        </div>


        <!-- クラスメニュー -->
        <div id="class" class="title_menu">
            <script type="text/javascript">
                function test () {
                    // 選択されたオプションのバリューを取得する
                    var element = document.getElementById("class_name");
                    // クラスIDを自分に渡すURLを組み立てる
                    var a=element.value;
                    // location.hrefに渡して遷移する
                    location.href = 'index.php?class_id=' + a;
                    // PHP
                    // GETからclass_idを取得してDBと検索する

                }
            </script>
            <select id="class_name" onchange="test()">
            <!-- 折り返し処理 -->
            <?php foreach($teacher as $d){?>
            <!--flex-grow: 1;-->
                <option value="<?=htmlspecialchars($d['class_id']) ?>" <?php if(isset($_GET['class_id']) && $d['class_id'] == $_GET['class_id']){echo 'selected';}?>><?=htmlspecialchars($d['class_name']) ?></option>
            <?php }$pdo=null; ?>
            </select>

        </div>
    </div>
    <div class="sub">
        <p class="attend"><button type=“button”><a href="AttendanceConfirmation.html">出席確認</a></button></p>
        <p class="teacher"><button type=“button”>担任</button></p>
    </div>

    <!--検索バー -->
    <div class="tabs">
        <li><input id="responsible"  name="menu"><a href="Responsible.html">担当グループ</a></li>
        <li><input id="group" name="menu"><a href="Group.html">グループ管理</a></li>
        <li><input id="users" name="menu"><a href="Users.html">ユーザー検索</a></li>
        <li><input id="resuser" name="menu"><a href="Resuser.html">管理者ユーザー一覧</a></li>
        <li><input id="groupmake" name="menu"><a href="Groupmake.html">グループ作成</a></li>
        <li><input id="classroom" name="menu"><a href="Classroom.html">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </div>

    <!--人の表情が入ります-->
    <!--<input type="image" src="image/face.png">-->

    <!-- フォームタグ -->
    <p><form action="" method="post">
        <!-- 写真が入ります -->
        <!-- グラフに飛ぶよん -->
        <form action="ぐらふのPHP" method="post"></form>
        <input type="image" src="" id="img">





        <!-- クラスメンバーの表示 -->
        <style>
            td {
                border: 1px solid #000;
            }
            td:nth-child(1),
            td:nth-child(2) {
                border: none;
            }
        </style>
        <table>
            <tr>
                <th>出席番号</th>
                <th>名前</th>
                <th>月別の出席の推移</th>
                <th>累計の遅刻数</th>
                <th>欠席数</th>
                <th>早退数</th>
                <th>出席率</th>
            </tr>
            <!-- exec_selectによる折り返し処理:開始 -->

            <?php foreach ($student as $st){ ?>
                <tr>
                    <th><?=htmlspecialchars($st['student_num']) ?></th>
                    <th><?=htmlspecialchars($st['student_name'])?></th>
                    <td>100</td><!-- <th><?//=htmlspecialchars($row['月別の出席の推移'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['累計の遅刻数'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['欠席数'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['早退数'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['出席率'])?></th> -->
                </tr>

            <tr>
                <td></td>
                <td></td>
                <td>100</td>
                <td>100</td>
                <td>100</td>
                <td>100</td>
                <td>100</td>
            </tr>
            <?php } $pdo=null; ?>
        </table>
    </form></p>
</div>
</body>
</html>