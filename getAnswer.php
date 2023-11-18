<?php
    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    // Get if attempt is correct
    $sql = "SELECT answer_position FROM quizOne
            WHERE question = 'I recorded the number of bottles of milk in the inventory.'";

    $result = mysqli_query($conn, $sql);
    $value = mysqli_fetch_assoc($result);

    echo $value["answer_position"];

    mysqli_close($conn);
?>
