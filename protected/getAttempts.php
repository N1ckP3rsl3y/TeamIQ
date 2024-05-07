<?php

    /*
        Purpose(s) of this file:
            1) Helper to `student_quiz.php`/`student_quiz_list.php` to get
               attempt information for each question/quiz
    */

    /**
     * Brief
     *  Get the current amount of attempts in the requested question
     *  for the current iteration of the selected quiz
     *
     * Note
     *  This function is called once per question from `student_quiz.php`
     *  and for efficiency purposes, the first call will get all necessary
     *  data from the database and will be used throughout these calls
     *
     * Input
     *  1) conn - Open connection to the database
     *  2) quizName - Name of the quiz that is being taken
     *  3) result - Pointer to the initial results for the quiz attempts stored
     *              in the caller file
     *  4) attReq - Pointer to the current attempt of the quiz to display
     *  5) sem - Respective semester of the student
     *  6) numQuizAtts - Quiz attempt number the student is currently taking
     *
     * Output
     *  1) HTML code for the attempt of the current question
     *  2) Updated `result` variable
     *  3) Updated `attReq` variable
     */
    function getAttempts($conn, $quizName, &$result, &$attReq, $sem, $numQuizAtts)
    {
        // Check if we need to query the database for the available questions
        if(is_null($result))
        {
            // Get all information for the quiz name for all users
            $sql = sprintf(
                "SELECT COLUMN_NAME FROM information_schema.columns
                 WHERE table_name = 'students_%s'
                 AND column_name REGEXP '(^%s)+_(?!comp)';",
                 $sem, $quizName, $quizName);

            $result = mysqli_query($conn, $sql);
        }

        // Get and display the next attempt for the student
        if(is_null($attReq))
        {
            $attemptReq = sprintf(
                "SELECT * FROM students_%s WHERE username='%s.%d'",
                    $sem, $_SERVER["REMOTE_USER"], $numQuizAtts);

            $attReq = mysqli_query($conn, $attemptReq);
            $attReq = mysqli_fetch_assoc($attReq);
        }

        $row = mysqli_fetch_assoc($result);

        return $row == null ? null : $attReq[$row["COLUMN_NAME"]];
    }

    /**
     * Brief
     *  Determine under which category a quiz resides when getting the list
     *  of quizzes (determines one quiz at a time), the list of categories are:
     *      * Not Attempted
     *      * In Progress
     *      * Completed
     *
     * Input
     *  1) conn - Open connection to the database
     *  2) sem - Respective semester of the student
     *  3) quizName - Name of the quiz that is being taken
     *  4) quizAttempt - Describes what number attempt the student is
     *                   on for the specific quiz
     *
     * Output
     *  1) Calculated string describing what category the quiz belongs
     */
    function getHomeQuizStatus($conn, $sem, $quizName, $quizAttempt)
    {
        $hasAZeroAttempt = false;
        $attemptSum = 0;

        // Get current quizzes' attempts
        $attemptReq = sprintf(
            "SELECT * FROM students_%s WHERE username='%s.%d'",
             $sem, $_SERVER["REMOTE_USER"], $quizAttempt);

        $attRes = mysqli_query($conn, $attemptReq);
        $allCols = mysqli_fetch_assoc($attRes);

        // Get all information for the quiz name for all users
        $sql = sprintf(
            "SELECT COLUMN_NAME FROM information_schema.columns
            WHERE table_name = 'students_%s'
            AND column_name REGEXP '(^%s)+_(?!comp)';",
            $sem, $quizName);

        $result = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_assoc($result))
        {
            // Do not count negative numbers - make them positive
            // This eliminates the possibility of a 0 sum given the
            // correct amount of attempts at give up and actual attempts
            // E.g., -1 + 1 + 5 + 3 - 8 = 0
            $attempt = $allCols[$row["COLUMN_NAME"]];
            $attemptSum += ($attempt < 0) ? $attempt * -1 : $attempt;
        }

        return ($attemptSum == 0) ? "Not Attempted" : "In Progress";
    }

    /**
     * Brief
     *  Determine if a new quiz attempt for the user should be created
     *  within the database, if so, create a new attempt
     *
     * Input
     *  1) conn - Open connection to the database
     *  2) quizName - Name of the quiz that is being taken
     *  3) username - User's NAU student/professor name (NOT the student ID)
     *  4) sem - Respective semester of the student
     *
     * Output
     *  1) Possibly updated database to contain a new user attempt
     */
    function checkForNewQuizAttempt($conn, $quizName, $username, $sem)
    {
        $quizInfo = getQuizAttempt($conn, $quizName, $username, $sem,
                                         $allQuizComp);
        $allQuizComp = $quizInfo["quizComp"];
        $getQuizAttempt = $quizInfo["numAttempts"];

        // If the attempt is completed, create a new one
        if($allQuizComp)
        {
            $getQuizAttempt++;
            $addNewQuizAttStr = sprintf(
                "INSERT INTO students_%s (username) VALUES ('%s.%d');",
                 $sem, $username, $getQuizAttempt
            );

            mysqli_query($conn, $addNewQuizAttStr);
        }

        // Return the current attempt number
        return $getQuizAttempt;
    }

    /**
     * Brief
     *  Determine the current attempt of a quiz to write to in the database
     *
     * Input
     *  1) conn - Open connection to the database
     *  2) quizName - Name of the quiz that is being taken
     *  3) username - User's NAU student/professor name (NOT the student ID)
     *  4) sem - Respective semester of the student
     *
     * Output
     *  1) Most recent attempt number of the quiz selected by the user
     */
    function getQuizAttempt($conn, $quizName, $username, $sem)
    {
        $QUEST_COMP_INC = 1000;

        $qComp = true;
        $totCompletes = 0;
        $quizCompStr = $quizName . "_comp";
        $results = array(
            "quizComp" => true,
            "numAttempts" => 0
        );

        // Get the information on if the current attempt for the quiz is not completed
        $latestAttNumStr = sprintf(
            "SELECT %s FROM students_%s WHERE username REGEXP '%s.[0-9]'",
             $quizCompStr, $sem, $username
        );

        $result = mysqli_query($conn, $latestAttNumStr);
        $numAttempts = mysqli_num_rows($result);

        while($row = mysqli_fetch_assoc($result))
        {
            $totCompletes += ($row[$quizCompStr]) ? 1 : 0;
        }

        // Not all attempt rows are full for this quiz
        if($totCompletes < $numAttempts)
        {
            $numAttempts = $totCompletes + 1;
            $results["quizComp"] = false;
        }

        $results["numAttempts"] = $numAttempts;

        return $results;
    }

    function getQuizAttemptProf($conn, $quizName, $username, $sem)
    {
        $qComp = true;
        $totCompletes = 0;
        $quizCompStr = $quizName . "_" . "comp";
        $results = array(
            "quizComp" => true,
            "numAttempts" => 0
        );

        // Get the information on if the current attempt for the quiz is not completed
        $latestAttNumStr = sprintf(
            "SELECT username,%s FROM students_%s WHERE username REGEXP '%s.[0-9]';",
             $quizCompStr, $sem, $username
        );

        $result = mysqli_query($conn, $latestAttNumStr);
        $numAttempts = mysqli_num_rows($result);

        while($row = mysqli_fetch_assoc($result))
        {
            $totCompletes += ($row[$quizCompStr]) ? 1 : 0;
        }

        if($totCompletes < $numAttempts)
        {
            $results["quizComp"] = false;
            $numAttempts = $totCompletes + 1;
        }

        $results["numAttempts"] = $numAttempts;

        return $results;
    }
?>
