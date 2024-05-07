<?php
        require_once("../generalFunctionality.php");

        $conn = connectToDatabase();

        $name = mysqli_real_escape_string($conn, $_GET['name']);
        $old =  $_GET['old'];

        // check that new name is unique
        $sql = sprintf("SELECT quiz_name FROM `quizzes`");
        $res = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($res))
        {
                if($row['quiz_name'] == $name)
                        {
                        echo json_encode("taken");
                        return;
                        }
        }

        // update quiz
        $sql = sprintf("UPDATE `quizzes` SET `quiz_name`='%s' WHERE quiz_id = '%s'",
                        $name, $old);
        mysqli_query($conn, $sql);

        // update quiz
        $send = getQuizID($conn, $name);

        echo json_encode("return");
        mysqli_close($conn);
?>
