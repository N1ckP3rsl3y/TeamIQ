<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = $_GET['name'];
    $id = $_GET['id'];

    $sql = sprintf("SELECT * FROM `%s` WHERE id='%s'", $name, $id);
    $res = mysqli_query($conn, $sql);

    session_start();
    $row = mysqli_fetch_assoc($res);
    $_SESSION['question'] = $row['question'];
    $_SESSION['explanation'] = $row['explanation'];
    $_SESSION['id'] = $row['id'];
    $_SESSION['tag'] = $row['tag'];
    $_SESSION['position'] = $row['answer_position'];

    mysqli_close($conn);
?>
