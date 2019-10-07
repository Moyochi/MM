<?php
require 'db.php';
?>

<?php
if (
//    isset($_POST['sa-ch'])
true
) {
    ready();
    $data = query("select * from students");

    var_dump($data);
}

?>



