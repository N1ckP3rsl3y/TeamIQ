<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $tag = $_GET['abbr'];

    $sql = sprintf("DELETE FROM `tagList` WHERE tag_abbr = '%s'", $tag);
    mysqli_query($conn, $sql);

    // change tag abbr that was deleted to " " in tables
    $sql = sprintf("SELECT quiz_id FROM `quizzes`");
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res))
        {
        $thisTable = $row['quiz_id'];
        $sql = sprintf("UPDATE `%s` SET `tag`=' ' WHERE tag = '%s'", $thisTable, $tag);
        mysqli_query($conn, $sql);
        }

    mysqli_close($conn);
?>
