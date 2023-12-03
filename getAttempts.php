<?php
    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    $sql = "SELECT quiz_1_q1 FROM students
            WHERE username = 'grt43';";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        echo "<p>";
        echo $row["quiz_1_q1"];
        echo " attempt(s)</p>";
    }

    mysqli_close($conn);
?>
