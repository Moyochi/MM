<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';

    //var_dump($_GET);


    $teacher = prepareQuery("
        SELECT TH.class_id,T.teacher_name,CO.course_name
FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id)
  INNER JOIN mm.teachers_homerooms TH ON T.teacher_id=TH.teacher_id)
  INNER JOIN classes C ON TH.class_id=C.class_id
  INNER JOIN courses_classes CC ON CC.class_id=TH.class_id and CC.class_year = 2019
  INNER JOIN courses CO ON  CO.course_id=CC.course_id
WHERE L.login_id = ?
ORDER BY TH.class_id",[$_SESSION['username']]);

    if(isset($_GET['class_id'])){
        $class_id=$_GET['class_id'];
    }else{
        //login.phpから飛んできた1行目のclass_idが入る。
        $class_id=$teacher[0]['class_id'];
    }


////    echo $teacher['teacher_name'];
$_SESSION['teacher_name']=$teacher[0]['teacher_name'];

//var_dump($_SESSION);

//var_dump($_SESSION);
//echo h($_SESSION['teacher_name']);
?>

<!DOCTYPE html>
<html>
    <head>
        <!--        <link rel="stylesheet" media="all" href="CSS入れる予定">-->
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
        <title>担当グループ</title>
    </head>
    <body>

        <!--<p>--><?php //echo h($_SESSION['username']); ?><!--さんいらっしゃい！</p>-->
        <!---->
        <!--<p><input type="password" name="password" placeholder="--><?php //echo h($_SESSION['username']); ?><!--"></p>-->

        <!--　DB接続　-->
        <?php


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

            try{
            }catch (PDOException $exception){
                die('接続エラー:'.$exception->getMessage());
            }


        ?>



        <!--どのアカウントで入ったか確認-->



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
                            var element = document.getElementById("course_name");
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
                    <select id="course_name" onchange="test()">
                    <!-- 折り返し処理 -->
                    <?php foreach($teacher as $d){?>
                    <!--flex-grow: 1;-->
                        <option value="<?=htmlspecialchars($d['class_id']) ?>" <?php if(isset($_GET['class_id']) && $d['class_id'] == $_GET['class_id']){echo 'selected';}?>><?=htmlspecialchars($d['course_name']) ?></option>
                    <?php }$pdo=null; ?>
                    </select>
                </div>


            </div>

            <!-- 上のメニューバー -->
            <a href="AttendanceConfirmation.php">状況管理</a></p>
            <a href="ACM.php">出席簿</a><p>
            <a href="./TeacherPro.php" ><?php echo h($_SESSION['teacher_name']); ?></a>

            <!--検索バー -->
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
            <!--<input type="image" src="image/face.png">-->



            <p><?php echo h($_SESSION['mon']); ?>さんいらっしゃい！</p>
            <span id="view_time"></span>
            <script type="text/javascript">
                document.getElementById("view_time").innerHTML = getNow();
                function getNow() {
                    var now = new Date();
                    var mon = now.getMonth()+1; //１を足すこと
                    return mon
                }
               <?php
                //                      $class_idをほかのページでも使えるようにした。
                    $_SESSION['mon']=$mon;
                ?>
            </script>
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
<!--                        <th>月別の出席の推移</th>-->
                        <th>今月の出席率</th>
                        <th>累計の遅刻数</th>
                        <th>欠席数</th>
                        <th>早退数</th>
                        <th>今年度出席率</th>
                    </tr>
                    <!-- exec_selectによる折り返し処理:開始 -->

                    <?php foreach ($student as $st){ ?>
                        <tr>
                            <th><?=htmlspecialchars($st['student_num']) ?></th>
                            <th><?=htmlspecialchars($st['student_name'])?></th>
                            <th><?=htmlspecialchars($st['attend_rate_month']); echo "%";?></th> <!-- 今月の出席率 -->
                            <th><?=htmlspecialchars($st['ac3'])?></th> <!-- 累計の遅刻数 -->
                            <th><?=htmlspecialchars($st['ac2'])?></th> <!-- 欠席数 -->
                            <th><?=htmlspecialchars($st['ac4'])?></th> <!-- 早退数 -->
                            <th><?=htmlspecialchars($st['attend_rate']); echo "%";?></th> <!-- 出席率 -->
                        </tr>
                    <?php } $pdo=null; ?>
                </table>
                <a href="./AttendanceConfirmation.php" >編集</a>
            </form>
        </div>
    </body>
</html>