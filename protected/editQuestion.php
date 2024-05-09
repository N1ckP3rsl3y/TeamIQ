<?php
        require_once("../generalFunctionality.php");

        $conn = connectToDatabase();

        $name = mysqli_real_escape_string($conn, $_GET['name']);
        $question = mysqli_real_escape_string($conn, $_GET['question']);
        $explanation = mysqli_real_escape_string($conn, $_GET['explanation']);
        
        $tag = $_GET['tag'];
        $id = $_GET['id'];
        $position = $_GET['position'];

        $name = getQuizID($conn, $name);
                
        // update question
        $sql = sprintf("UPDATE `%s` SET `question`='%s',`answer_position`='%s',`explanation`='%s',`tag`='%s' WHERE id = %s",
                        $name, $question, $position, $explanation, $tag, $id);
        mysqli_query($conn, $sql);

        mysqli_close($conn);
?>
