<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';

    //var_dump($_GET);
    if(isset($_GET['index_class_id'])){
        $class_id=$_GET['index_class_id'];
    }else{
        //login.phpから飛んできた1行目のclass_idが入る。
        $class_id=$_SESSION['class'][0]['id'];
    }

try{
        //出席番号 名前 累計の遅刻数 欠席数 出席率
        $student = prepareQuery('select * from load_responsible_1 where class_id = ?',[$class_id]);
        //今月出席率
        $month_rate = prepareQuery('
            select students.student_id, COALESCE(attend_rate,0)month_rate
            from mm.students
              left join mm.attend_rate_month on students.student_id = attend_rate_month.student_id
            where class_id = ? and month = ?',[$class_id,date("m")]);
    }catch (PDOException $exception){
        die('接続エラー:'.$exception->getMessage());
}

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

        <!--<p>--><?php //echo h($_SESSION['teahcer_id']); ?><!--さんいらっしゃい！</p>-->
        <!---->
        <!--<p><input type="password" name="password" placeholder="--><?php //echo h($_SESSION['teahcer_id']); ?><!--"></p>-->




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
                            <?php
        //                      $class_idをほかのページでも使えるようにした。
                                $_SESSION['index_class_id']=$class_id;
                            ?>
                            // location.hrefに渡して遷移する
                            location.href = 'index.php?index_class_id=' + a;
                        }
                    </script>
                    <select id="class_name" onchange="test()">
                    <!-- 折り返し処理 -->
                        <div id="re">
                    <?php foreach($_SESSION['class'] as $d){?>
                    <!--flex-grow: 1;-->
                        <option value="<?=htmlspecialchars($d['id']) ?>" <?php if(isset($_GET['index_class_id']) && $d['id'] == $_GET['index_class_id']){echo 'selected';}?>><?=htmlspecialchars($d['name']) ?></option>
                    <?php }$pdo=null; ?>
                        </div>
                    </select>
                </div>
            </div>

        </div>

            <!-- 上のメニューバー -->
            <div class="bu">
                <a href="./ResponsibleEdit.php" id="edit">編集</a>
                <a href="ACM1.php" id="attendata">出席簿</a>
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




                <!--人の表情が入ります-->
                <!--<input type="image" src="image/face.png">-->


                <!-- フォームタグ -->
                <p><form action="ACM1.php" method="post">
                    <!-- 写真が入ります -->
                    <!-- グラフに飛ぶよん -->
                    <form action="ぐらふのPHP" method="post"></form>
<!--                    <input type="image" src="" id="img">-->



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
                            <th>今月出席率</th>
                            <th>年間遅刻数</th>
                            <th>年間欠席数</th>
                            <th>年間出席率</th>
                        </tr>
                        </thead>
                        <!-- exec_selectによる折り返し処理:開始 -->

                        <tbody>
                        <?php foreach ($student as $i => $st){ ?>
                            <tr>
                                <th><?=htmlspecialchars($st['student_num']) ?></th>
                                <th><a id="name" href="StudentPro.php"><?=htmlspecialchars($st['student_name']) ?></a></th>
                                <td style="margin: 0"><?=htmlspecialchars($month_rate[$i]['month_rate']).'%' ?></td><!-- 今月出席率 -->
                                <td style="margin: 0"><?=htmlspecialchars($st['late']) ?></td><!-- 遅刻数 -->
                                <td style="margin: 0"><?=htmlspecialchars($st['absence']) ?></td><!-- 欠席数 -->
                                <td style="margin: 0"><?=htmlspecialchars($st['attend_rate']).'%' ?></td><!-- 合計出席率 -->
                            </tr>
                        <?php } $pdo=null; ?>
                        </tbody>
                    </table>
                </form>

            </div>
    </body>
</html>