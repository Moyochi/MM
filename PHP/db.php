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
        exit;
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
        exit;
    }
}
?>