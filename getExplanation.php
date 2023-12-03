<?php
    require_once("generalFunctionality.php");

    $conn = connectToDatabase();

    $sql = "SELECT explanation FROM quizOne
            WHERE question = 'I recorded the amount of bottles of milk in the inventory.';";

    $result = mysqli_query($conn, $sql);

    $value = mysqli_fetch_assoc($result);
    echo "<p>" . $value["explanation"] . "</p>";

    mysqli_close($conn);
?>
