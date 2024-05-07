<?php
    /*
        Purpose(s) of this file:
            1) Update the database with an incremented attempt for
               the current question the user is working on
            2) Display the updated value
    */

    require_once("../generalFunctionality.php");
    require_once("getAttempts.php");

    $conn = connectToDatabase();
    $sem = getCurrentSemester($conn);

    // Get all information given to the file by JavaScript
    $quizName = $_POST["quizName"];
    $qNum = $_POST["quizNum"];
    $numQuizAtts = $_POST["quizAttempt"];
    $currAtt = $_POST["currAttempts"];

    $quizColTitle = $quizName . "_" . $qNum;

    // Update the attempt amount in the database
    $updateSQL = sprintf(
        "UPDATE students_%s SET %s_%d = %d WHERE username = '%s.%d';",
         $sem, $quizName, $qNum, $currAtt, $_SERVER["REMOTE_USER"],
         $numQuizAtts);

    $result = mysqli_query($conn, $updateSQL);

    echo $updateSQL;

    mysqli_close($conn);
?>
