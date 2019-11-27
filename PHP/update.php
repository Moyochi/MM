<?php
//require_once 'functions.php';
//session_start();


header('Content-Type:text/html; charset=UTF-8');

var_dump($_POST['student_id']);
echo '<br>';
var_dump($_POST['attend_id']);
echo '<br>';
var_dump($_POST['time_period']);
echo '<br>';
var_dump($_POST['datepicker']);
echo '<br>';


require 'db.php';
try {
    $time=$_POST['time_period'];
    $day=$_POST['datepicker'];

//    $_POST('update');
//    echo $_POST('student_num');

    $sql = "";
    foreach ($_POST['attend_id'] as $i => $row){
        $sql = "UPDATE students_attend_lesson SET  attend_id = ".$row." WHERE student_id = ".$_POST['student_id'][$i]." AND date='".$_POST['datepicker']."' AND time_period=".$_POST['time_period'].";<br>";
        query($sql);
    }
    echo $sql;
    header("Location: http://localhost:8081/mm/PHP/ACM1.php?class_id=undefined&day=$day&time=$time");
} catch (Exception $e) {
    echo 'エラーが発生しました。:' . $e->getMessage();
}

?>
