<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $abbr = $_GET['abbr'];

    $sql = sprintf("SELECT * FROM `tagList` WHERE tag_abbr='%s'", $abbr);
    $res = mysqli_query($conn, $sql);
    session_start();

    $row = mysqli_fetch_assoc($res);
        
    $_SESSION['name'] = $row['tag_name'];
    $_SESSION['abbr'] = $row['tag_abbr'];
    $_SESSION['description'] = $row['tag_description'];
        

    mysqli_close($conn);
?>
