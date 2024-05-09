<?php
    /*
        Purpose(s) of this file:
            1) Update the number of attempts within the database to be
               negative to denote that the user gave up on the question
               for the current quiz attempt
            2) Display the updated value
    */

    require_once("../generalFunctionality.php");
    require_once("getAttempts.php");

    $conn = connectToDatabase();
    $sem = getCurrentSemester($conn);

    $quizName = $_POST["quizName"];
    $qNum = $_POST["quizNum"];
    $currAtt = $_POST["currAttempts"];
    $numQuizAtts = $_POST["quizAttempt"];

    $quizColTitle = $quizName . "_" . $qNum;

    $negSQL = sprintf(
        "UPDATE students_%s SET %s=%d WHERE username='%s.%d';",
         $sem, $quizColTitle, $currAtt, $_SERVER["REMOTE_USER"],
         $numQuizAtts);

    mysqli_query($conn, $negSQL);

    echo "<p>";
    echo $currAtt;
    echo "</p>";

    mysqli_close($conn);
?>
