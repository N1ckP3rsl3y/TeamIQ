<?php
    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    $question = $_POST['question'];
    $position = $_POST['answerPosition'];
    $explanation = $_POST['explanation'];
    
    //renderString($question);
    
    $sql = sprintf("INSERT INTO quizOne VALUES ('%s', '%d', '%s')",
                   $question, $position, $explanation);


    mysqli_query($conn, $sql);

    mysqli_close($conn);
?>
