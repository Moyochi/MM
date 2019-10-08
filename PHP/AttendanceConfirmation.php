<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';

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
        <title>responsible</title>
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

        <a href="./TeacherPro.php" ><?php $_SESSION['username'] ?></a>

        <!--メニューバー-->
        <div class="tabs">
            <li><a href="./index.php">担当グループ</a></li>
            <li><input id="group" name="menu"><a href="Group.html">グループ管理</a></li>
            <li><input id="users" name="menu"><a href="Users.html">ユーザー検索</a></li>
            <li><input id="resuser" name="menu"><a href="Resuser.html">管理者ユーザー一覧</a></li>
            <li><input id="groupmake" name="menu"><a href="Groupmake.html">グループ作成</a></li>
            <li><input id="classroom" name="menu"><a href="Classroom.html">教室管理</a></li>
            <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
        </div>


        <!--人の表情が入ります-->
        <input type="image" src="../image/face.png">

        <!--写真が入ります-->
        <!--グラフに飛ぶよん-->
        <form action="StudentGraph.html" method="post">
            <input type="image" src="../image/noimage.gif">

            <p>
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
                <?php } $pdo=null; ?>
            </table>

        </form>

        ◯人中◯人出席しました。

        <button type=“button”>決定</button>

    </body>
</html>

