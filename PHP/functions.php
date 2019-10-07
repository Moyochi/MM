<?php
/*
 * ログイン状態によってリダイレクト
 * 初回時または失敗時にはヘッダを送信してexitする
 */

    function require_unlogined_session(){
        //セッション開始
        @session_start();
        //ログインしていれば
        if(isset($_SESSION["username"])){
            header('Location: ./'/*ホーム画面*/);
            exit;
        }
    }

    function require_logined_session(){
        //セッション開始
        @session_start();
        //ログインしていなければlogin.phpに遷移
        //login.phpまだ作ってない
        if(!isset($_SESSION["username"])){
            header('Location: ./login.php');
            exit;
        }
    }

    //CSRFトークンの生成
    function generate_token(){
        //セッションIDからハッシュを生成
        return hash('sha256',session_id());
    }

    //CSRFトークン
    function validate_token($token){
        return $token === generate_token();
    }

    //htmlspecialchars
    function h($var){
        if(is_array($var)){
            return array_map(h,$var);
        }else{
            return htmlspecialchars($var,ENT_QUOTES,'UTF-8');
        }
    }
?>