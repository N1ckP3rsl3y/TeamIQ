<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = mysqli_real_escape_string($conn, $_GET['name']);

    $specialChars = array("(", ")", "{", "}", "[", "]", "<", ">", "-", "`", "~", ";", ":", "'", "\"", "^", "!", "?", "/", "\\", ",", "&", "%", "*", " ");
    $name = str_replace( $specialChars, '', $name);

    // create new semester table string
    $tableName = "students_";
    $tableName .= $name;

    // grab old semester
    $oldSem = "students_";
    $oldSem .= getCurrentSemester($conn);

    
    // create semester table based off previous semest
    $sql = sprintf("CREATE TABLE IF NOT EXISTS %s LIKE %s", $tableName, $oldSem);
    mysqli_query($conn, $sql);
    
    // set current semester
    $sql = sprintf("UPDATE `Semester` SET `CurrentSemester`='%s'", $name);

    mysqli_query($conn, $sql);
    mysqli_close($conn);
?>
