<?php

    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    $sql = "UPDATE students
            SET quiz_1_q1_giveup = 1
            WHERE username = 'grt43';";

    mysqli_query($conn, $sql);
?>
