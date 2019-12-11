<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';
?>

<?php
//グループ作成
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
        query($sql);
        //グループ内にレコードを追加する
        foreach ($records as $j) {
            $sql = 'insert into students values (' . $j[$colbook[0]] . ',' . $cid . ',\'' . $j[$colbook[2]] . '\',' . $j[$colbook[3]] . ');';
            query($sql);
            $sql = 'insert into classes_students values (' . $cid . ',' . $j[$colbook[1]] . ',' . $j[$colbook[0]] . ');';
            query($sql);
        }
    } else {
        //ファイルのプロジェクト配下への再配置が失敗
        echo 'ファイルアップロードに失敗しました。';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/Group.css">
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
                グループ管理
            </h1>
        </div>
    </div>
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
<div id="d">
    グループ作成
</div>

<div id="b">

    <form action="" method="post" enctype="multipart/form-data">
        グループ名 : <input type="text" name="gname" placeholder="grope_name"><br>
        <input type="file" name="file" size="30">
        <input type="hidden" name="function_flg" value="CREATE_GROPE">
        <input type="submit" value="送信" id="buto">
    </form>

</div>

<div id="c">
    グループ追加
</div>

<div id="e">
追加するグループを選んでください。
</div>

<!--フォームタグ-->
<!--<p><form action="" method="post">-->


    <div class="addclass">
    <input type="checkbox" value="JK1">情報工学科１
    </div>


        <a href="Responsible.html" id="sub">OK</a>
<!--</form>-->
</body>
</html>