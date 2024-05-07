<?php
    function connectToDatabase() {
        $server = "mysql.ac.nau.edu";
        $database = "redpenweb";
        $user = "redpenweb";
        $password = "teamiq2023!?";

        return mysqli_connect($server, $user, $password, $database);
    }

    function renderString($string)
    {
        $delim = " ";
        $indivWords = explode($delim, $string);

        foreach($indivWords as $word)
        {
            echo "<a class='wordChoice'>" . $word . "</a>\n";
            echo " ";
        }
    }

    function getCurrentSemester($conn)
    {
        $currentSemester = "SELECT CurrentSemester FROM Semester;";

        $res = mysqli_query($conn, $currentSemester);
        $row = mysqli_fetch_assoc($res);

        return $row["CurrentSemester"];
    }

    function getQuizID($conn, $name)
    {
        $sql = sprintf("SELECT quiz_id FROM `quizzes` WHERE quiz_name = '%s'", $name);
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);

        return $row['quiz_id'];
    }

    function getQuizNameDisplay($quizName)
    {
        $delim = "_";
        $separatedName = explode($delim, $quizName);

        return implode(" ", $separatedName);
    }

    function addUserToDatabaseIfNeeded($conn, $sem)
    {
        $checkForStudent = sprintf("SELECT COUNT(1)
                FROM students_%s WHERE username REGEXP '^%s';",
                $sem, $_SERVER["REMOTE_USER"]);

        $res = mysqli_query($conn, $checkForStudent);
        $row = mysqli_fetch_assoc($res);

        // Check to see if the student exists in the database
        // for the first attempt
        if(!$row["COUNT(1)"])
        {
            // Add first attempt row for the student
            // (<username>.<attempt #>)
            $addFirstAttempt = sprintf(
                "INSERT INTO students_%s (username) VALUES ('%s.1');",
                $sem, $_SERVER["REMOTE_USER"]);

            $res = mysqli_query($conn, $addFirstAttempt);
        }
    }

    function moveUserToDatabaseIfNeeded($conn, $sem, $userID)
    {
        $checkForStudent = sprintf("SELECT COUNT(1)
                FROM students_%s WHERE username REGEXP '^%s';",
                $sem, $userID);

        $res = mysqli_query($conn, $checkForStudent);
        $row = mysqli_fetch_assoc($res);

        // Check to see if the student exists in the database
        // for the first attempt
        if(!$row["COUNT(1)"])
        {
            // Add first attempt row for the student
            // (<username>.<attempt #>)
            $addFirstAttempt = sprintf(
                "INSERT INTO students_%s (username) VALUES ('%s.1');",
                $sem, $userID);
            $res = mysqli_query($conn, $addFirstAttempt);
        }
    }

    function getCorrectAttNum($qAttNum, $quest_comp_inc)
    {
        $finalNum = $qAttNum;

        if($finalNum < 0)
        {
            $finalNum *= -1;
        }

        if($finalNum > $quest_comp_inc)
        {
        $finalNum %= $quest_comp_inc;
        }

        return $finalNum;
    }

    function getCorrectAttSuffix($qAttNum, $quest_comp_inc)
    {
        $finalMsg = "";

        if($qAttNum > $quest_comp_inc)
        {
            $finalMsg = " (Completed)";
        }
        else if($qAttNum < 0)
        {
            $finalMsg = " (Gave Up)";
        }

        return $finalMsg;
    }
?>
