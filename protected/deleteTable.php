<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = $_GET['name'];
    $id = $_GET['id'];

    $grabbing = $name;

    $sql = "SHOW TABLES LIKE 'students_%'";
    $semRes = mysqli_query($conn, $sql);

    if($id == " ")
        {
        // clear all questions
        $sql = sprintf("DELETE FROM `quizzes` WHERE quiz_id = '%s'", $name);
        mysqli_query($conn, $sql);

        $sql = sprintf("DROP TABLE %s", $name);
        mysqli_query($conn, $sql);
        $grabbing .= "_%%";
        }
    else
        {
        // clear specific question
        $sql = sprintf("DELETE FROM `%s` WHERE id='%s'", $name, $id);
        mysqli_query($conn, $sql);

        $grabbing .= "_";
        $grabbing .= $id;

        //loop through rest of table and bring ID down
        $sql = sprintf("SELECT id FROM `%s` where id > %s", $name, $id);
        $res = mysqli_query($conn, $sql);
        }


    $result = getCurrentSemester($conn);

    $currentSem = "students_";
    $currentSem .= $result;


    // get all questions that need deleted
    $sql = sprintf("SELECT COLUMN_NAME FROM information_schema.columns
    WHERE TABLE_NAME = '%s' AND COLUMN_NAME LIKE '%s'", $currentSem, $grabbing);
    $result = mysqli_query($conn, $sql);

    foreach($result as $row)
        {
    
        while($Rrow = mysqli_fetch_assoc($semRes))
            {
            $sql = sprintf("ALTER TABLE %s DROP COLUMN %s", $Rrow["Tables_in_redpenweb (students_%)"], $row['COLUMN_NAME']);
            mysqli_query($conn, $sql);
            }
        

        // if deleting one question, move remaining question ids
        if($id != " ")
            {
            while($row2 = mysqli_fetch_assoc($res))
                {
                // update ID in quiz
                $sql = sprintf("UPDATE `%s` SET `id`=%s WHERE id=%s", $name, $id, $row2['id']);
                mysqli_query($conn, $sql);

                //update ID in student attempts
                $oldAttemptGrab = $name;
                $oldAttemptGrab .= "_";
                $oldAttemptGrab .= $row2['id'];

                $newAttempt = $name;
                $newAttempt .= "_";
                $newAttempt .= $id;

                while($Rrow = mysqli_fetch_assoc($semRes))
                    {
                    $sql = sprintf("ALTER TABLE %s CHANGE %s %s int(11)", $Rrow["Tables_in_redpenweb (students_%)"], $oldAttemptGrab, $newAttempt);
                    mysqli_query($conn, $sql);
                    }

                $id = $row2['id'];
                }
            }
        }
        
    mysqli_close($conn);

?>
