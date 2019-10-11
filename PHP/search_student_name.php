<?php
require 'db.php';
?>

<?php
if (isset($_POST['sa-ch'])) {
    $_POST['sa-ch'] ="河原 慎之介";
    $string = str_replace(array(" ", "　"), "", $_POST['sa-ch']);

    $search_user = prepareQuery("select * from students where student_name = ?",[$string]);
}
?>



