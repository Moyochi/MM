<?php
require_once __DIR__ . '/functions.php';
require_unlogined_session();
require 'db.php';

foreach (['teacher_id','password','token','submit'] as $key){
    $key=(string)filter_input(INPUT_POST,$key);
}
//エラーを格納する配列を初期化
$errors=[];

//POSTのときのみ実行
if($_SERVER['REQUEST_METHOD']==='POST'){
    // csrf

    //idのパラメータチェック
    if (isset($_POST['teacher_id'])){
        $teacher_id=$_POST['teacher_id'];
    }
    if (isset($_POST['password'])){
        $password=$_POST['password'];
    }
    if($teacher_id=="" || $password ===""){
        $errors[]='ユーザ名またはパスワードが入力されていません。';
    }else{
        $teacher_id=h($teacher_id);
        $password=h($password);

        //dbとの接続
        $result = login($teacher_id);

        if ($result['login_id'] == $teacher_id && $result['login_password'] == $password) {
            session_start();
            $_SESSION['teacher_id'] = $result['teacher_id'];
            $_SESSION['teacher_name'] = $result['teacher_name'];
            $class = prepareQuery("
                SELECT TH.class_id,class_name
                FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id)
                    INNER JOIN mm.teachers_homerooms TH ON T.teacher_id=TH.teacher_id)
                    INNER JOIN classes C ON TH.class_id=C.class_id
                WHERE L.login_id = ?
                ORDER BY class_id",[$teacher_id]);
            foreach ($class as $row) {
                $_SESSION['class'][] = ['id' => $row['class_id'],'name' => $row['class_name']];
            }
            // 画面遷移する処理
            header('Location: index.php');
            exit();
        } else {
            echo 'ログイン失敗';
        }

//        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        $sql = 'SELECT * FROM login WHERE login_id ==?)';
//        $prepare = $db->prepare($sql);
//        $prepare->bindValue(1, $teacher_id, PDO::PARAM_INT);
//        $prepare->execute();
//        $result=$prepare->fetch(PDO::FETCH_ASSOC);

//        if (validate_token(filter_input(INPUT_POST,'token')) && password_verify($password,$result["password"])) {
//            //認証が成功
//            //セッションIDの追跡を防ぐらしい
//            session_regenerate_id(true);
//            //ユーザー名をセットする
//            $_SESSION['teacher_id'] = $teacher_id;
//            header('Location: ./index.php');
//            exit;
//            }
//        //認証が失敗
//        $errors[]="ユーザー名またはパスワードが違います。";
    }
}
//header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <link rel="stylesheet" media="all" href="../CSS/All.css">
        <link rel="stylesheet" media="all" href="../CSS/Login.css">
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
    <div class="header">

        <div class="title">
    <div class="title_text">
        <h1 class="head">
            ログイン
        </h1>
    </div>
        <?php if($errors): ?>
        <!--フォームタグ-->
        <ul>
            <?php foreach ($errors as $err): ?>
            <li><?=h($err)?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
            <form action="login.php" method="post">
                <div class="i">
                    <p >ID</p>
                    <p><input type="text" name="teacher_id" placeholder="IDを入力してください。" size="50"　 value="<?php echo $teacher_id=isset($_POST['teacher_id']) ? $_POST['teacher_id']: ''; ?>"></p>
                </div>
                <div class="p">
                    <p>PASSWORD</p>
                    <p><input type="password" name="password" placeholder="パスワードを入力してください。" size="50"></p>
                </div>
                <!--画面遷移-->
                <button type="submit" id="bub">ログイン</button>
            </form>
        </div>

    </body>
</html>
