<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = mysqli_real_escape_string($conn, $_GET['name']);

    $name = trim($name, " \n\r\t"); // Trim any whitespace


    // make sure name is unique
    $compareName = strtolower($name);
    $sql = sprintf("SELECT quiz_name FROM `quizzes`");
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res))
        {
        if($compareName == strtolower($row['quiz_name']))
            {
            // return taken
            $send = json_encode("taken");
            echo $send;
            return;
            }
        }

    //get last quiz id
    $sql = sprintf("SELECT quiz_id FROM `quizzes` ORDER BY quiz_id DESC LIMIT 1");
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
        
    // set quiz id, grabbing end of last
    $tempID = substr($row['quiz_id'], 5);
    $tempID++;
    $setID = $tempID;

    if($tempID < 10)
        {
        $setID = '0';
        $setID .= $tempID;
        }

    $id = "Quiz_";
    $id .= $setID;
        


    // create new table
    $sql = sprintf("CREATE TABLE IF NOT EXISTS %s (
        question varchar(5000),
        answer_position int(11),
        explanation varchar(5000),
        tag varchar(50),
        id int(11))", $id);

    mysqli_query($conn, $sql);

    
    // add to quizzes database
    $sql = sprintf("INSERT INTO `quizzes`(`quiz_name`, `visible`, `quiz_id`) VALUES ('%s',0,'%s')", $name, $id);
    mysqli_query($conn, $sql);


    // create the _comp column for student database
    $compStr = $id;
    $compStr .= "_comp";

    $sql = "SHOW TABLES LIKE 'students_%'";
    $res = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($res))
        {
        $sql = sprintf("ALTER TABLE %s ADD COLUMN %s bit(1) DEFAULT 0", $row["Tables_in_redpenweb (students_%)"], $compStr);
        mysqli_query($conn, $sql);
        }

    // return
    $send = json_encode("return");
    echo $send;
    mysqli_close($conn);
?>
