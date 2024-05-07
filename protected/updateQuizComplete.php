<?php
    /*
        Purpose(s) of this file:
            1) Update the completed attempt of a quiz to column `<Quiz ID>_comp`
    */

    require_once("../generalFunctionality.php");
    require_once("getAttempts.php");

    $conn = connectToDatabase();
    $sem = getCurrentSemester($conn);

    // Get all information given to the file by JavaScript
    $quizName = $_POST["quizName"];
    $numQuizAtts = $_POST["quizAttempt"];

    // Update the attempt amount in the database
    $updateSQL = sprintf(
        "UPDATE students_%s SET %s_comp=%d WHERE username = '%s.%d';",
         $sem, $quizName, 1, $_SERVER["REMOTE_USER"], $numQuizAtts);

    $result = mysqli_query($conn, $updateSQL);

    mysqli_close($conn);
?>
