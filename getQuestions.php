<?php
    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    $sql = "SELECT question FROM quizOne";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $questionArea = "<div id='question'>\n";

        echo $questionArea;
        renderString($row["question"]);
        echo "</div>";
    }

    mysqli_close($conn);
?>
