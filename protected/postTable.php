<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = $_GET['name'];
    $do = $_GET['do'];

    $visible = $do == "post" ? 1 : 0;

    $sql = sprintf("UPDATE `quizzes` SET `visible`= %s WHERE quiz_id = '%s'", $visible, $name);
    mysqli_query($conn, $sql);

    mysqli_close($conn);

?>
