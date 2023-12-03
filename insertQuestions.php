<?php
    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    $question = $_REQUEST['question'];
    $position = 1;
    $explanation = $_REQUEST['explanation'];

    $sql = "INSERT INTO quizOne VALUES ('$question', '$position', '$explanation')";

    $insertVal = mysqli_query($conn, $sql);

    mysqli_close($conn);
?>
