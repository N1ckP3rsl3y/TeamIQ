<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $question = mysqli_real_escape_string($conn, $_GET['question']);
    $explanation = mysqli_real_escape_string($conn, $_GET['explanation']);
    $tag = $_GET['tag'];
    $position = $_GET['answerPosition'];

    //get quiz ID
    $name = getQuizID($conn, $name);
            
    //get previous question ID num
    $sql = sprintf("SELECT id FROM %s ORDER BY id DESC LIMIT 1", $name);
    $res = mysqli_query($conn, $sql);

    $id = 1;
    if(mysqli_num_rows($res) > 0)
        {
        $row = mysqli_fetch_assoc($res);
        $id = $row['id'] + 1;
        }

    // insert question
    $sql = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s')",
                   $name, $question, $position, $explanation, $tag, $id);
    mysqli_query($conn, $sql);


    //add question to student database
    $adding = $name;
    $adding .= "_";
    $adding .= $id;

    $sql = "SHOW TABLES LIKE 'students_%'";
    $res = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($res))
        {
        $sql = sprintf("ALTER TABLE %s ADD %s int(11) DEFAULT 0", $row["Tables_in_redpenweb (students_%)"], $adding);
        mysqli_query($conn, $sql);
        }

    mysqli_close($conn);
?>
