<?php
    require_once("generalFunctionality.php");

    $maxNumQuestions = 1;
    $qNum = 0;

    $conn = connectToDatabase();

    $sql = "SELECT question FROM quizOne";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result) and
           $qNum < $maxNumQuestions)
    {
        $questionArea = "<div id='question'>\n";

        echo $questionArea;
        renderString($row["question"]);
        echo "</div>";
        $qNum += 1;
    }

    mysqli_close($conn);
?>
