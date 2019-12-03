<?php
require_once 'functions.php';
require_logined_session();
require 'db.php';

header('Content-Type:text/html; charset=UTF-8');

//グループ名変更
if(isset($_POST['function_flg']) and $_POST['function_flg']=='UPDATE_NAME') {
    if (isset($_POST['update_grope_id']) and isset($_POST['update_grope_name'])) {
        prepareQuery("update classes set class_name = ? where class_id = ?"
            , [$_POST['update_grope_name'], $_POST['update_grope_id']]);
        $class = prepareQuery("
                select c.class_id, class_name
                from teachers_homerooms t2
                left join classes c on t2.class_id = c.class_id
                where teacher_id = ?", [$_SESSION['teacher_id']]);
        $_SESSION['class'] = null;
        foreach ($class as $row) {
            $_SESSION['class'][] = ['id' => $row['class_id'], 'name' => $row['class_name']];
        }
    }
}
//グループ作成
if(isset($_POST['function_flg']) and $_POST['function_flg']=='CREATE_GROPE') {
    if (isset($_POST['gname']) and $_POST['gname'] != "" and $_FILES['file']['tmp_name'] != null) {
        //ファイルの受け取りが正常に行われている場合、ファイルのアクセス権限を変更する
        $file_path = './test_csv/' . $_FILES["file"]["name"];
        chmod($_FILES['file']['tmp_name'], 0666);
        //ファイルをプロジェクト配下のディレクトリに配置し、値を読み込む。
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)) {
            //ファイルのプロジェクト配下への再配置が成功
            $file = new SplFileObject($file_path);
            $file->setFlags(
                SplFileObject::READ_CSV |
                SplFileObject::SKIP_EMPTY |
                SplFileObject::READ_AHEAD
            );
            $records = array();
            foreach ($file as $i => $row) {
                if ($i === 0) {
                    foreach ($row as $j => $col) {
                        if ($j == 0) {
                            $colbook[$j] = "ID";
                        } else {
                            $colbook[$j] = $col;
                        }
                    }
                    continue;
                }
                // 2行目以降はデータ行として取り込み
                $line = array();
                foreach ($colbook as $j => $col) {
                    $line[$colbook[$j]] = @$row[$j];
                }
                $records[] = $line;
            }
            //新規グループに付与するクラスID(クラスIDの最大+1)を取得する
            $cid = query('select max(class_id)id from classes')[0]['id'] + 1;
            //グループsqlを実行する
            $sql = 'insert into classes values (' . $cid . ',\'' . $_POST['gname'] . '\');';
            //グループ内にレコードを追加する
            foreach ($records as $j) {
                $sql = $sql . 'insert into students values (' . $j[$colbook[0]] . ',' . $cid . ',\'' . $j[$colbook[2]] . '\',' . $j[$colbook[3]] . ');';
                $sql = $sql . "insert into classes_students values (" . $cid . "," . $j[$colbook[1]] . "," . $j[$colbook[0]] . ");";
            }
            query($sql);
        } else {
            //ファイルのプロジェクト配下への再配置が失敗
            echo 'ファイルアップロードに失敗しました。';
        }
    }
}

if(isset($_GET['grope_id'])){
    $grope_id = $_GET['grope_id'];
    foreach ($_SESSION['class'] as $row){
        if($_GET['grope_id']==$row['id']) $grope_name = $row['name'];
    }
}else {
    $grope_id = $_SESSION['class'][0]['id'];
    $grope_name = $_SESSION['class'][0]['name'];
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


<!--どのアカウントで入ったか確認-->

<div class="header">

    <div class="title">

        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1 class="head">
                <!-- 題名 -->
                担当グループ編集
            </h1>
        </div>
        <div id="class" class="title_menu">
            <script type="text/javascript">
                function test () {
                    // 選択されたオプションのバリューを取得する
                    var element = document.getElementById("class_name");
                    // クラスIDを自分に渡すURLを組み立てる
                    var grope_id = element.value;
                    // location.hrefに渡して遷移する
                    location.href = 'ResponsibleEdit.php?grope_id=' + grope_id;
                }
            </script>
            <select id="class_name" onchange="test()">
                <!-- 折り返し処理 -->
                <div id="re">
                    <?php foreach($_SESSION['class'] as $d){?>
                        <!--flex-grow: 1;-->
                        <option value="<?=htmlspecialchars($d['id']) ?>" <?php if(isset($_GET['grope_id']) && $d['id'] == $_GET['grope_id']){echo 'selected';}?>><?=htmlspecialchars($d['name']) ?></option>
                    <?php }$pdo=null; ?>
                </div>
            </select>
        </div>
    </div>

</div>

<!-- 上のメニューバー -->
<div class="bu">
<!--        <a href="AttendanceConfirmation.php" id="attend">状況管理</a>-->
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
    <div>
    <form action="" method="post">
        <div class="group_name">
            <h2>グループ名変更</h2>
            <p><input type="text" name="update_grope_name" placeholder="<? echo $grope_name?>" size="50"></p>
        </div>
        <input type="hidden" name="update_grope_id" value="<? echo $grope_id?>">
        <input type="hidden" name="function_flg" value="UPDATE_NAME">
        <button type="submit">決定</button>
    </form>
    <hr>
    <form action="" method="post" enctype="multipart/form-data">
        <h2>グループ作成<br></h2>
        グループ名 : <input type="text" name="gname" placeholder="grope_name"><br>
        <input type="file" name="file" size="30">
        <input type="hidden" name="function_flg" value="CREATE_GROPE">
        <input type="submit" value="送信">
    </form>
    </div>

</div>
</body>
</html>