<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $id = $_GET['id'];

    moveUserToDatabaseIfNeeded($conn, getCurrentSemester($conn), $id);

    mysqli_close($conn);
?>
