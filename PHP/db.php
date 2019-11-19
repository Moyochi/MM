<?php
$db = pdo_init();
function pdo_init(){
    try {
        $db = new PDO('mysql:host=localhost;dbname=mm', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}
function query($sql){
    try {
        global $db;
        $stmt = $db ->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return($data);

    }catch (PDOException $e) {
        return $e->getMessage();
    }
}
function prepareQuery($sql,$array){
    try{
        global $db;
        $stmt = $db->prepare($sql);
        $stmt->execute($array);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return($data);
    }catch (PDOException $e) {
        return $e->getMessage();
    }
}
function login($username){
    global $db;
    $st=$db->prepare('SELECT * FROM login left join teachers on login.login_id = teachers.login_id WHERE login.login_id = :username');
    $st->bindValue(':username',$username,PDO::PARAM_STR);

    $st->execute();
    $result=$st->fetch(PDO::FETCH_ASSOC);
    return $result;
}
?>