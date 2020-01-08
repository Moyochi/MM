<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';
?>

<?php
if(isset($_SESSION['current_class_id']) and isset($_SESSION['current_class_name'])){
    $class_id = $_SESSION['current_class_id'];
    $class_name = $_SESSION['current_class_name'];
}else{
    header('Location: index.php');
}

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

//担当クラス追加
if(isset($_GET['class_id'])){
    $selected_class_list = preg_split("/[,]/",$_GET['class_id']);
    foreach ($selected_class_list as $row){
        var_dump($row);
        prepareQuery("insert into teachers_homerooms values (?,'2019',?)", [$row,$_SESSION['teacher_id']]);
    }
    //セッション内の担当クラスリストを更新。
    $_SESSION['class'] = [];
    $class = prepareQuery("
                select TH.class_id,class_name
                from teachers T
                  left join teachers_homerooms th on T.teacher_id = th.teacher_id
                  left join classes c on th.class_id = c.class_id
                where T.teacher_id = ?
                ORDER BY class_id",[$_SESSION['teacher_id']]);
    foreach ($class as $row) {
        $_SESSION['class'][] = ['id' => $row['class_id'],'name' => $row['class_name']];
    }
    header('Location: index.php');
}

$class_list = prepareQuery("select classes.class_id,class_name from classes left join teachers_homerooms h on classes.class_id = h.class_id where teacher_id <> ? or teacher_id is null",[$_SESSION['teacher_id']]);
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
        <div id="class" class="title_menu">
            <select id="class_id" onchange="selectClass()" disabled>
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
<form action="" id = class_list_select method="get">
    <?php
    echo '<div class="addclass">';
    foreach ($class_list as $row) {
        echo '<input type="checkbox" value="'.$row['class_id'].'">'.$row['class_name'];
    }
    echo '</div>';
    ?>
    <input type="button" id="sub" value="OK" onclick=selectClass()>
    <script type="text/javascript">
        function selectClass() {
            var element = document.getElementById("class_list_select");
            var class_list = [];
            for (var i = 0; i < element.length-1; i++) {
                if (element[i].checked) {
                    class_list.push(element[i].value);
                }
                // location.hrefに渡して遷移する
                location.href = 'Group.php?class_id=' + class_list;
            }
        }
    </script>
</form>
</body>
</html>