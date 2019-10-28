<?php
    require 'db.php';
    //セッション開始
    require_logined_session();

    try{
        $user="";
    }catch (Exception $e){
        echo 'エラーが発生しました。:'.$e->getMessage();
    }
?>