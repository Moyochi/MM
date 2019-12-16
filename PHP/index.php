<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';
?>
<?php
//表示するグループのclass_idを設定。
//初回表示時はセッションから、1番上のclass_idが利用され、
//指定された場合は、getで受け取った内容を設定する。
if(isset($_GET['class_id']) and isset($_GET['class_name'])){
    $class_id=$_GET['class_id'];
    $class_name=$_GET['class_name'];
    $_SESSION['current_class_id'] = $class_id;
    $_SESSION['current_class_name'] = $class_name;
}else{
    if(isset($_SESSION['current_class_id']) and isset($_SESSION['current_class_name'])){
        $class_id = $_SESSION['current_class_id'];
        $class_name = $_SESSION['current_class_name'];
    }else{
        //login.phpから飛んできた1行目のclass_idが入る。
        $class_id=$_SESSION['class'][0]['id'];
        $class_name=$_SESSION['class'][0]['name'];
        $_SESSION['current_class_id'] = $class_id;
        $_SESSION['current_class_name'] = $class_name;
    }
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
                        function selectClass() {
                            // 選択されたオプションのバリューを取得する
                            var element = document.getElementById("class_id");
                            // クラスIDを自分に渡すURLを組み立てる
                            var selectedIndex = element.selectedIndex;
                            var form_class_id = element.options[selectedIndex].dataset.id;
                            var form_class_name = element.options[selectedIndex].dataset.name;
                            // location.hrefに渡して遷移する
                            location.href = 'index.php?class_id=' + form_class_id + '&class_name=' + form_class_name ;
                        }
                    </script>
                    <select id="class_id" onchange="selectClass()">
                    <!-- 折り返し処理 -->
                        <div id="re">
                            <?php foreach($_SESSION['class'] as $d){?>
                            <!--flex-grow: 1;-->
                                <option
                                    data-id="<?=h($d['id'])?>" data-name="<?=h($d['name'])?>"
                                    <?php if($d['id'] == $class_id){echo 'selected';}?>>
                                    <?=h($d['name'])?>
                                </option>
                            <?}?>
                        </div>
                    </select>
                </div>
            </div>
        </div>

        <!-- 上のメニューバー -->
        <script>
            function post(url) {
                function selectClass() {
                    var element = document.getElementById("class_id");
                    var selectedIndex = element.selectedIndex;
                    var form = document.createElement("form");
                    form.setAttribute("action", url);
                    form.setAttribute("method", "post");
                    form.style.display = "none";
                    document.body.appendChild(form);
                    var data = {
                        'class_id':element.options[selectedIndex].dataset.id,
                        'class_name':element.options[selectedIndex].dataset.name,
                    };
                    for (var paramName in data) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', paramName);
                        input.setAttribute('value', data[paramName]);
                        form.appendChild(input);
                    };
                    form.submit();
                }
            }
        </script>
        <div class="bu">
            <a href="./ResponsibleEdit.php" id="edit" onclick = post('./ResponsibleEdit.php')>編集</a>
            <a href="ACM1.php" id="attendata" onclick="post('ACM1.php')">出席簿</a>
            <a href="TeacherPro.php" id="teacher">担任</a>
            <!--<a href="./TeacherPro.php" ><?php echo h($teacher['teacher_name']); ?></a>-->
        </div>

        <!--検索バー -->
        <div class="container">
            <input type="text" placeholder="Search..." id="sa-ch" onchange="search(this.value)">
            <script>
                function search(search_text){
                    var form = document.createElement("form");
                    form.setAttribute("action","search.php");
                    form.setAttribute("method", "post");
                    form.style.display = "none";

                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'user_search');
                    input.setAttribute('value', search_text);

                    form.appendChild(input);
                    document.body.appendChild(form);

                    form.submit();
                }
            </script>
            <div class="search"></div>
        </div>

            <div class="contents">
                <ul class="nav">
                <li><a href="./index.php">担当グループ</a></li>
                <li><a href="Group.php">グループ管理</a></li>
                <li><a href="Users.php">ユーザー検索</a></li>
                <li><a href="Resuser.php">管理者ユーザー一覧</a></li>
                <li><a href="Classroom.php">教室管理</a></li>
                <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
                </ul>

            <!--人の表情が入ります-->
            <!--<input type="image" src="image/face.png">-->

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
                                <th><?=h($st['student_num']) ?></th>
                                <th><a id="name" href="StudentPro.php?student_num=<?=h($st['student_num'])?>&class_id=<?=h($st['class_id'])?>"><?=h($st['student_name']) ?></a></th>
                                <td style="margin: 0"><?=h($month_rate[$i]['month_rate']).'%' ?></td><!-- 今月出席率 -->
                                <td style="margin: 0"><?=h($st['late']) ?></td><!-- 遅刻数 -->
                                <td style="margin: 0"><?=h($st['absence']) ?></td><!-- 欠席数 -->
                                <td style="margin: 0"><?=h($st['attend_rate']).'%' ?></td><!-- 合計出席率 -->
                            </tr>
                        <?php } $pdo=null; ?>
                        </tbody>
                    </table>
                </form>
            </div>
    </body>
</html>