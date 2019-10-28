<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';

    $teacher = prepareQuery("
            SELECT TH.class_id,class_name 
            FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id) 
            INNER JOIN mm.teachers_homerooms TH ON T.teacher_id=TH.teacher_id) 
            INNER JOIN classes C ON TH.class_id=C.class_id 
            WHERE L.login_id = ? 
            ORDER BY class_id",[$_SESSION['username']]);

    if (isset($_GET['class_id'])) {
        $class_id=$_GET['class_id'];
    }else{
        $class_id=$teacher[0]['class_id'];
    }
$student = prepareQuery("
          select  SQ.class_id,
          SQ.student_id,
          SQ.class_name,
          SQ.student_num,
          SQ.student_name,
          COALESCE(SQ.attend_rate_month,0)attend_rate_month,
          COALESCE(SQ.absence_count,0)absence_count,
          COALESCE(SQ.attend_rate,0)attend_rate,
          COALESCE(MAC.attend_count,0)ac1,
          COALESCE(MAC2.attend_count,0)ac2,
          COALESCE(MAC3.attend_count,0)ac3,
          COALESCE(MAC4.attend_count,0)ac4
        from (
        
          SELECT
            TH.class_id,
            S.student_id,
            class_name,
            CS.student_num,
            S.student_name,
            ARM.attend_rate as attend_rate_month,
            RLAC.absence_count,
            AR.attend_rate
          FROM ((login L INNER JOIN teachers T ON L.login_id = T.login_id)
            INNER JOIN mm.teachers_homerooms TH ON T.teacher_id = TH.teacher_id)
            INNER JOIN classes C ON TH.class_id = C.class_id
            INNER JOIN students S ON S.class_id = C.class_id
            INNER JOIN classes_students CS ON CS.class_id = S.class_id and CS.student_id = S.student_id
            LEFT OUTER JOIN mm.attend_rate_month ARM ON ARM.student_id = CS.student_id
            LEFT OUTER JOIN mm.regarding_lesson_absence_count RLAC ON RLAC.student_id = CS.student_id
            LEFT OUTER JOIN mm.attend_rate AR ON AR.student_id = CS.student_id
          WHERE L.login_id = ?
                AND C.class_id = ?
          GROUP BY S.student_name
          ORDER BY student_num asc, class_id
          )SQ LEFT OUTER JOIN mm.attend_count MAC ON MAC.student_id=SQ.student_id and MAC.attend_id=1
          LEFT OUTER JOIN mm.attend_count MAC2 ON MAC2.student_id=SQ.student_id and MAC2.attend_id=2
          LEFT OUTER JOIN mm.attend_count MAC3 ON MAC3.student_id=SQ.student_id and MAC3.attend_id=3
          LEFT OUTER JOIN mm.attend_count MAC4 ON MAC4.student_id=SQ.student_id and MAC4.attend_id=4",[$_SESSION['username'], $class_id]);

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

        <!-- 科目メニュー -->
        <div id="class" class="subject_menu">
            <script type="text/javascript">
                function test () {
                    // 選択されたオプションのバリューを取得する
                    var element = document.getElementById("subject_id");
                    // クラスIDを自分に渡すURLを組み立てる
                    var a = element.value;
                    // location.hrefに渡して遷移する
                    location.href = 'AttendanceConfirmation.php?subject_id=' + a;
                    <?php
                    //                      $class_idをほかのページでも使えるようにした。
                    $_SESSION['subject_id']=$subject_id;
                    ?>
                }
            </script>
            <!-- subject=教科 -->
            <select id="subject_name" onchange="test()">
                <!-- 折り返し処理 -->
                <?php foreach($student as $d){?>
                    <!--flex-grow: 1;-->
                    <option value="<?=htmlspecialchars($d['subject_id']) ?>" <?php if(isset($_GET['subject_id']) && $d['subject_id'] == $_GET['subject_id']){echo 'selected';}?>><?=htmlspecialchars($d['subject_name']) ?></option>
                <?php }$pdo=null; ?>
            </select>
        </div>


        <!-- カレンダー -->
        <!--        <input type="date" name="calendar" max="9999-12-31">-->
        <form action="AttendanceConfirmation.php" method="get">
            <input type="date" name="calendar" value="<?php echo date('Y-m-d')?>" min="2019-04-01" max="2100-04-01">
            <input type="submit" value="送信">
        </form>

        <a href="./TeacherPro.php" ><?php echo h($_SESSION['teacher_name']) ?></a>

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
        <form name="form1" action="update.php" method="post">
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
                                    <option value="syu">出席</option>>
                                    <option value="ke">欠席</option>>
                                    <option value="tiko">遅刻</option>>
                                </select>
                        </td><!-- <th><?//=htmlspecialchars($row['月別の出席の推移'])?></th> -->
                        </tr>
                <?php } $pdo=null; ?>
            </table>
<!--            <input type="submit" value="決定">-->
<!--            <button type=“submit”>決定</button>-->
        </form>
        <input type="button" value="決定" onclick="clickBtn1()"/>
        <script>
            function clickBtn1() {
                const color1=document.form1.de;
                const num=de.selectedIndex;
                const str=de.options[num].value;
                document.getElementById("span1").textContent=str;
            }
        </script>

        ◯人中◯人出席しました。

    </body>
</html>

