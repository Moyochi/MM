<?php
require_once '../PHP/functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require '../PHP/db.php';

//var_dump($_GET);
$student = prepareQuery("SELECT TH.class_id,class_name,CS.student_num,S.student_name
                        FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id)
                        INNER JOIN teacher_homeroom TH ON T.teacher_id=TH.teacher_id)
                        INNER JOIN classes C ON TH.class_id=C.class_id
                        INNER JOIN students S ON S.class_id = C.class_id
                        INNER JOIN classes_students CS ON CS.class_id = S.class_id and CS.student_id = S.student_id
                        WHERE L.login_id = ?
                        AND C.class_id = ?
                        GROUP BY S.student_name
                        ORDER BY student_num asc ,class_id",[$_SESSION['username'], $class_id]);

try{
}catch (PDOException $exception){
    die('接続エラー:'.$exception->getMessage());
}
?>

<?php
$comment = $_GET['calendar'];
echo $comment;
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>出席簿</title>
</head>
<body>
<h1>出席簿</h1>


<!-- カレンダー -->
<!--        <input type="date" name="calendar" max="9999-12-31">-->
<form action="ACM.php" method="get">
    <input type="date" name="calendar" max="9999-12-31">
    <input type="submit" value="送信">
</form>

<!-- 何限目かを選択 -->
<div id="class" class="time">
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
            $_SESSION['class_id']=$class_id;
            ?>
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





</body>
</html>
