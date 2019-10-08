<?php
require_once __DIR__ . '/functions.php';
require_unlogined_session();

foreach (['username','password','token','submit'] as $key){
    $key=(string)filter_input(INPUT_POST,$key);
}
require 'db.php';
//エラーを格納する配列を初期化
$errors=[];

//POSTのときのみ実行
if($_SERVER['REQUEST_METHOD']==='POST'){
    // csrf


    //idのパラメータチェック
    if (isset($_POST['username'])){
        $username=$_POST['username'];
    }
    if (isset($_POST['password'])){
        $password=$_POST['password'];
    }
    if($username=="" || $password ===""){
        $errors[]='ユーザ名またはパスワードが入力されていません。';
    }else{
        $username=h($username);
        $password=h($password);

        //dbとの接続
        $dbtype='mysql';
        $host='localhost';
        $db='dbname';
        $charset='utf8';

//        $dsn="mysql:host=localhost;dbname=mm";
//        $db=new PDO($dsn,'root','password');
        $db=new PDO('mysql:host=localhost;port=33066;dbname=mm;charset=utf8','root','password');
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $st=$db->prepare('SELECT * FROM login WHERE login_id =:username');
        $st->bindValue(':username',$username,PDO::PARAM_STR);

        $st->execute();
        $result=$st->fetch(PDO::FETCH_ASSOC);

        $id = prepareQuery("
            SELECT TH.class_id,class_name 
            FROM((login L INNER JOIN teachers T ON L.login_id=T.login_id) 
            INNER JOIN teacher_homeroom TH ON T.teacher_id=TH.teacher_id) 
            INNER JOIN classes C ON TH.class_id=C.class_id 
            WHERE L.login_id = ? 
            ORDER BY class_id
            LIMIT 1;
            ",[$_SESSION['username']]);

        if ($result['login_id'] == $username && $result['login_password'] == $password) {
            echo 'ログイン成功';
            session_start();
            $_SESSION['username'] = $username;
            // 画面遷移する処理を書く
            header('Location: index.php');
            exit();
        } else {
            echo 'ログイン失敗';
        }



//        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        $sql = 'SELECT * FROM login WHERE login_id ==?)';
//        $prepare = $db->prepare($sql);
//        $prepare->bindValue(1, $username, PDO::PARAM_INT);
//        $prepare->execute();
//        $result=$prepare->fetch(PDO::FETCH_ASSOC);

//        if (validate_token(filter_input(INPUT_POST,'token')) && password_verify($password,$result["password"])) {
//            //認証が成功
//            //セッションIDの追跡を防ぐらしい
//            session_regenerate_id(true);
//            //ユーザー名をセットする
//            $_SESSION['username'] = $username;
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
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h1>ログイン</h1>
        <?php if($errors): ?>
        <!--フォームタグ-->
        <ul>
            <?php foreach ($errors as $err): ?>
            <li><?=h($err)?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <form action="login.php" method="post">
            <p>ID</p>
            <p><input type="text" name="username" size="50" value="<?php echo $username=isset($_POST['username']) ? $_POST['username']: ''; ?>"></p>

            <br>

            <p>PASSWORD</p>
            <p><input type="password" name="password" size="50"></p>

            <br>

            <!--画面遷移-->
            <button type="submit">ログイン</button>

            <!-- ログインしたユーザーの1行めのclassidを送る -->
            <input type="hidden" name='id' value="id">

        </form>

    </body>
</html>