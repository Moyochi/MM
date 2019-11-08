<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';

$teacher = prepareQuery("
            SELECT TH.class_id,class_name 
            FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id) 
            INNER JOIN mm.teachers_homeroom TH ON T.teacher_id=TH.teacher_id) 
            INNER JOIN classes C ON TH.class_id=C.class_id 
            WHERE L.login_id = ? 
            ORDER BY class_id",[$_SESSION['username']]);

$student = prepareQuery("SELECT TH.class_id,class_name,CS.student_num,S.student_name
            FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id)
            INNER JOIN mm.teachers_homerooms TH ON T.teacher_id=TH.teacher_id)
            INNER JOIN classes C ON TH.class_id=C.class_id
            INNER JOIN students S ON S.class_id = C.class_id
            INNER JOIN classes_students CS ON CS.class_id = S.class_id and CS.student_id = S.student_id
            WHERE L.login_id = ?
            AND C.class_id = ?
            GROUP BY S.student_name
            ORDER BY student_num asc ,class_id",[$_SESSION['username'], $_SESSION['class_id']]);

try{
}catch (PDOException $exception){
    die('接続エラー:'.$exception->getMessage());
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>状況管理</h1>
<!--クラスメニュー-->
<div class="class">
    <li>授業名
        <ul>
            <li>プログラミング演習</li>
        </ul>
    </li>
</div>

<!--日付-->
<script type="text/javascript">
    weeks=new Array("日","月","火","水","木","金","土");
    today=new Date();
    m=today.getMonth()+1;
    d=today.getDate();
    w=weeks[today.getDay()];
    document.write("<span>",m,"<\/span>月");
    document.write("<span>",d,"<\/span>日");
    document.write("(<span>",w,"<\/span>)");
</script>

<a href="./TeacherPro.php" ><?php echo h($_SESSION['username']) ?></a>

<!--メニューバー-->
<div class="tabs">
    <li><a href="./index.php">担当グループ</a></li>
    <li><a href="Group.php">グループ管理</a></li>
    <li><a href="Users.php">ユーザー検索</a></li>
    <li><a href="Resuser.php">管理者ユーザー一覧</a></li>
    <li><a href="Groupmake.php">グループ作成</a></li>
    <li><a href="Classroom.php">教室管理</a></li>
    <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
</div>


<!--人の表情が入ります-->
<input type="image" src="../image/face.png">

<!--写真が入ります-->
<!--グラフに飛ぶよん-->
<form action="update.php" method="post">
    <input type="image" src="../image/noimage.gif">

    <p>
    <table>
        <tr>
            <th>出席番号</th>
            <th>名前</th>
            <th>出席状況</th>
        </tr>
        <!-- exec_selectによる折り返し処理:開始 -->

        <?php foreach ($student as $st){ ?>
            <tr>
                <th><?=htmlspecialchars($st['student_num']) ?></th>
                <th><?=htmlspecialchars($st['student_name'])?></th>
                <td>
                    <select name="de">
                        <option value="syu">出席</option>
                        <option value="ke">欠席</option>
                        <option value="tiko">遅刻</option>
                    </select>
                </td>
            </tr>
        <?php } $pdo=null; ?>
    </table>
    <!--            <input type="submit" value="決定">-->
</form>
<input type="button" value="決定" onclick="clickBtn1()"/>
<script>
    function onclicBtn1() {
        const color1=document.form1.de;
        const num=de.selectedIndex;
        onst str=de.option[num].valur;
        document.getElementById("span1").textContent=str;
    }
</script>

◯人中◯人出席しました。



</body>
</html>

