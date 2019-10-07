<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
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
    //ログインIDから担当クラスを取得
    try{
        $pdo=new PDO('mysql:host=localhost;port=33066;dbname=mm;charset=utf8','root','password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    }catch (PDOException $exception){
        die('a接続エラー:'.$exception->getMessage());
    }
    try{
        //class_idは2つあるからどっちのidかわからなくなるからTHをつける
        $teacher= $pdo->prepare("SELECT TH.class_id,class_name
                FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id)
                INNER JOIN teacher_homeroom TH ON T.teacher_id=TH.teacher_id)
                INNER JOIN classes C ON TH.class_id=C.class_id
                WHERE L.login_id = ?
                ORDER BY class_id");
//        WHERE L.login_id = ? and TH.year = ?");
//        $teacher_stmh=$pdo->prepare($teacher);
        $teacher->execute([$_SESSION['username']]);
//        $teacher_stmh->execute([$_SESSION['username'],'2019']);
        $data = $teacher->fetchAll(PDO::FETCH_ASSOC);

        //配列データ確認
//        var_dump($data);

//        $teacher_stmh=$pdo->prepare($data);
//        $teacher_stmh->execute();
    }catch (PDOException $exception){
        die('a接続エラー:'.$exception->getMessage());
    }




    //生徒のデータ取得
    try{
        $sql="SELECT * 
              FROM (classes_students CS
              INNER JOIN students S
              ON CS.student_id=S.student_id)";

    //出席番号だけ取得できる。
//               $sql="SELECT * FROM classes_students";
    //INNER JOINでつなげようとした。
//               $sql="SELECT * FROM classes_students CT
//                    INNER JOIN students S
//                    ON CT.students_id=S.students_id";


    //梅崎大先生のアドバイス。
//                $sql="SELECT CT.class_id, S.student_id, S.student_name
//                FROM classes_students CT
//                INNER JOIN students S
//                ON CT.students_id = S.student_id";
        $stmh=$pdo->prepare($sql);
        $stmh->execute();

    }catch (PDOException $exception){
        die('接続エラー:'.$exception->getMessage());
    }
?>



<!--どのアカウントで入ったか確認-->
<p><?php echo h($_SESSION['username']); ?>さんいらっしゃい！</p>


<div class="header">
    <div class="title">
        <!--color: #364e96;
        border: solid 3px #364e96;
        padding: 0.5em;
        border-radius: 0.5em;
        display: flex;
        -->
        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1>担当グループ</h1>
        </div>

        <?

        ?>


        <!-- クラスメニュー -->
        <div id="class" class="title_menu">
            <select>
            <!-- exec_selectによる折り返し処理 -->
            <?php foreach($data as $d){?>
            <!--flex-grow: 1;-->
                <option><?=htmlspecialchars($d['class_name']) ?></option>
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
            <?php while($row = $stmh->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <th><?=htmlspecialchars($row['student_num']) ?></th>
                    <th><?=htmlspecialchars($row['student_name'])?></th>
                    <td>100</td><!-- <th><?//=htmlspecialchars($row['月別の出席の推移'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['累計の遅刻数'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['欠席数'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['早退数'])?></th> -->
                    <td>100</td><!--<th><?//=htmlspecialchars($row['出席率'])?></th> -->
                </tr>
            <?php } $pdo=null; ?>
            <tr>
                <td></td>
                <td></td>
                <td>100</td>
                <td>100</td>
                <td>100</td>
                <td>100</td>
                <td>100</td>
            </tr>
        </table>
    </form></p>
</div>
</body>
</html>