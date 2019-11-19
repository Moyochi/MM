<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';

//表示するグループのclass_idを設定。
//初回表示時はセッションから、1番上のclass_idが利用され、
//指定された場合は、getで受け取った内容を設定する。
    if(isset($_GET['class_id'])){
        $class_id=$_GET['class_id'];
    }else{
        //login.phpから飛んできた1行目のclass_idが入る。
        $class_id=$_SESSION['class']['id'][0];
    }


////    echo $teacher['teacher_name'];
//$_SESSION['teacher_name']=$teacher[1]['teacher_name'];

//var_dump($_SESSION);

//var_dump($_SESSION);
//echo h($_SESSION['teacher_name']);

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

        <!--<p>--><?php //echo h($_SESSION['username']); ?><!--さんいらっしゃい！</p>-->
        <!---->
        <!--<p><input type="password" name="password" placeholder="--><?php //echo h($_SESSION['username']); ?><!--"></p>-->

        <!--　DB接続　-->
        <?php


            $student = $data = prepareQuery('select * from load_responsible_1 where class_id = ?',[$class_id]);

            try{
            }catch (PDOException $exception){
                die('接続エラー:'.$exception->getMessage());
            }
        ?>


 <!--梅崎大先生のアドバイス。
                $sql="SELECT CT.class_id, S.student_id, S.student_name
                FROM classes_students CT
               INNER JOIN students S
                ON CT.students_id = S.student_id";-->

        <!--どのアカウントで入ったか確認-->

        <div class="header">

            <div class="title">

                <div class="title_text">
                    <!--flex-grow: 3;-->
                    <h1 class="head">
                        担当グループ
                    </h1>
                </div>

                <!-- クラスメニュー -->
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
                    <?php foreach($teacher as $d){?>
                    <!--flex-grow: 1;-->
                        <option value="<?=htmlspecialchars($d['class_id']) ?>" <?php if(isset($_GET['class_id']) && $d['class_id'] == $_GET['class_id']){echo 'selected';}?>><?=htmlspecialchars($d['class_name']) ?></option>
                    <?php }$pdo=null; ?>
                        </div>
                    </select>
                </div>
            </div>

        </div>

            <!-- 上のメニューバー -->
            <div class="bu">
                <a href="./AttendanceConfirmation.php" id="edit">編集</a>
                <a href="AttendanceConfirmation.php" id="attend">状況管理</a>
                <a href="ACM.php" id="attendata">出席簿</a>
                <a href="TeacherPro.php" id="teacher">担任</a>
            <!--<a href="./TeacherPro.php" ><?php echo h($teacher['teacher_name']); ?></a>-->
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
                    <thead>
                    <tr>
                        <th>出席番号</th>
                        <th>名前</th>
                        <th>月別の出席の推移</th>
                        <th>累計の遅刻数</th>
                        <th>欠席数</th>
                        <th>早退数</th>
                        <th>出席率</th>
                    </tr>
                    </thead>
                    <!-- exec_selectによる折り返し処理:開始 -->

                    <tbody>
                    <?php foreach ($student as $st){ ?>
                        <tr>
                            <th><?=htmlspecialchars($st['student_num']) ?></th>
                            <th><a href="StudentPro.php"><?=htmlspecialchars($st['student_name'])?></a></th>
                            <td style="margin: 0; display: none;">100</td><!--<td style="margin: 0">--><?//=htmlspecialchars($st['']) ?><!--</td><!-- 月別出席 -->
                            <td style="margin: 0"><?=htmlspecialchars($st['late']) ?></td><!-- 累計の遅刻数 -->
                            <td style="margin: 0"><?=htmlspecialchars($st['absence']) ?></td><!-- 欠席数 -->
                            <td style="margin: 0"><?=htmlspecialchars($st['early']) ?></td><!-- 相対数 -->
                            <td style="margin: 0"><?=htmlspecialchars($st['attend_rate']) ?></td><!-- 出席率 -->
                        </tr>
                    <?php } $pdo=null; ?>
                    </tbody>
                </table>
                <a href="ResponsibleEdit.php" id="edit">編集</a>
            </form>
        </div>
    </body>
</html>