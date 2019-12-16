<?php
//require_once 'functions.php';
//session_start();


header('Content-Type:text/html; charset=UTF-8');

var_dump($_POST);
echo '<br>';

require 'db.php';
try {
    $time=$_POST['time_period'];
    $day=$_POST['datepicker'];

//    $_POST('update');
//    echo $_POST('student_num');

    $sql = "";
    for($i=0; ; $i++){
        if(!isset($_POST[$i])){break;}
        $sql = "UPDATE students_attend_lesson SET  attend_id = ".$_POST['attend_id_'.$i]." WHERE student_id = ".$_POST[$i]." AND date='".$_POST['datepicker']."' AND time_period=".$_POST['time_period'].";<br>";
        query($sql);
//        echo $sql;
    }

    header("Location: http://localhost:8081/mm1/PHP/ACM1.php?class_id=$_POST[class_id]&class_name=$_POST[class_name]&day=$day&time=$time");
} catch (Exception $e) {
    echo 'エラーが発生しました。:' . $e->getMessage();
}

?>
