<?php
    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    $sql = "SELECT quiz_1_q1 FROM students
            WHERE username = 'grt43';";

    $result = mysqli_query($conn, $sql);
    $value = mysqli_fetch_assoc($result);

    $sql = sprintf("UPDATE students
                    SET quiz_1_q1 = %d
                    WHERE username = 'grt43';",
                    $value["quiz_1_q1"] + 1);

    mysqli_query($conn, $sql);

    $sql = "SELECT quiz_1_q1 FROM students
            WHERE username = 'grt43';";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        echo "<p>";
        echo $row["quiz_1_q1"];
        echo "</p>";
    }

    mysqli_close($conn);
?>
