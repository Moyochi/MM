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
//$_SESSION['teacher']=$teacher[0]['teacher_name'];

//var_dump($_SESSION);

//var_dump($_SESSION);
//echo h($_SESSION['teacher_name']);
    $data = prepareQuery('select * from load_responsible_1 where class_id = ?',[11]);

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


            $student = prepareQuery("select * from load_index_1 WHERE teacher_id = ? and month = ?"
                ,[$_SESSION['teacher_id'], 10]);

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
                            <th><?=htmlspecialchars($st['attend_rate_month'])?></th> <!-- 今月の出席率 -->
                            <th><?=htmlspecialchars($st['ac3'])?></th> <!-- 累計の遅刻数 -->
                            <th><?=htmlspecialchars($st['ac2'])?></th> <!-- 欠席数 -->
                            <th><?=htmlspecialchars($st['ac4'])?></th> <!-- 早退数 -->
                            <th><?=htmlspecialchars($st['attend_rate'])?></th> <!-- 出席率 -->
                        </tr>
                    <?php } $pdo=null; ?>
                </table>
                <a href="./AttendanceConfirmation.php" >編集</a>
            </form>
        </div>
    </body>
</html>