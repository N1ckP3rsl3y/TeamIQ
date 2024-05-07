<?php
    require_once("userAuthentication.php");
    $isProf = checkIfProfessor();
    if ($isProf)
    {
        // redirect to professor quiz list
        header("Location: https://ac.nau.edu/redpen/protected/professor_quiz_list.php");

        exit;
    }
?>
<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="student.css">
        <link rel="stylesheet" href="../styles.css">
        <title>Red Pen</title>
        <script defer src="student.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
    </head>

    <body>
         <!-- Header Section -->
         <header class="fixed-header">
        <a href="homepage.php">
            <img src="../photos/nau.png" alt="Logo" width="200" height="125">
        </a>
        <img src="../photos/Red Pen.png" alt="Logo" width="425" height="125">
        <nav>
            <ul>
                <li><a href="student.html">Back</a></li>
            </ul>
        </nav>
        </header>

        <main>
            <?php
                /*
                    Purpose(s) of this file:
                        1) Destination page for the student user which displays
                           the available quizzes for their semester
                */

                function printQuizzesNotCompleted($notCompStr)
                {
                    echo "<div class='qSecHeader'>";
                    echo "<h1>Not Attempted</h1>\n";
                    echo "<div id='remindersContainer'>";
                    echo ($notCompStr == "") ?
                    "No Quiz Meets This Criteria at the Moment." :
                    $notCompStr;

                    echo "</div>";
                    echo "</div>";
                }

                function printQuizzesInProgress($inProgStr)
                {
                    echo "<div class='qSecHeader'>";
                    echo "<h1>In Progress</h1>\n";
                    echo "<div id='remindersContainer'>";
                    echo ($inProgStr == "") ?
                            "No Quiz Meets This Criteria at the Moment." :
                            $inProgStr;

                    echo "</div>";
                    echo "</div>";
                }

                function printQuizzesCompleted($compQuizStr)
                {
                    echo "<div class='qSecHeader'>";
                    echo "<h1>Completed</h1>\n";
                    echo "<div id='remindersContainer'>";
                    echo ($compQuizStr == "") ?
                        "No Quiz Meets This Criteria at the Moment." :
                        $compQuizStr;

                    echo "</div>";
                    echo "</div>";
                }

                require_once("../generalFunctionality.php");
                require_once("alumni_functionality.php");
				require_once("getAttempts.php");

			    $notAttemptedStr = "";
			    $inProgStr = "";
			    $completeStr = "";

                $conn = connectToDatabase();

                // Check to see if the student is within the database
                // if not, add them
                // This is currently for handling one semester
                $sem = handleAlumniIfNeeded($conn);
                addUserToDatabaseIfNeeded($conn, $sem);

                /*
                    Get all quizzes that have been stored by the professor

                    Output in the format of (if quiz is visible to student)

                    - Quiz option
                        - Styled button (goes to respective quiz)
                        - Store JavaScript function call to know what quiz is
                          being taken
                    - End quiz option
                */
                $quizTable = sprintf("SELECT * FROM quizzes;");

                $resQuiz = mysqli_query($conn, $quizTable);

                while($row = mysqli_fetch_assoc($resQuiz))
                {
                    if($row["visible"]) {
                        $status = "";

                        $quizInfo = getQuizAttempt($conn, $row["quiz_id"],
                                                   $_SERVER["REMOTE_USER"], $sem);

                        $quizComp = $quizInfo["quizComp"];
                        $numAttempts = $quizInfo["numAttempts"];

                        if(!$quizComp)
                        {
                            $status = "Not Attempted";
                            $status = getHomeQuizStatus($conn, $sem, $row["quiz_id"],
                                                        $numAttempts);
                        }
                        $disQuizName = getQuizNameDisplay($row["quiz_name"]);

                        $quizOpt = "<div class='quizOption'>";
                        $studentInput = "<button class='quizList' ";
                        $studentInput .=
                            sprintf("onclick=getQuizQuestions('%s')>%s</button>",
                            $row["quiz_id"], $disQuizName);

                        $quizOpt .= $studentInput;
                        $quizOpt .= "</div>";


                        if($status === "Not Attempted")
                        {
                            $notAttemptedStr .= $quizOpt;
                        }
                        else if($status === "In Progress")
                        {
                            $inProgStr .= $quizOpt;
                        }
                        else if($quizComp)
                        {
                            $completeStr .= $quizOpt;
                        }
                    }
                }

                printQuizzesNotCompleted($notAttemptedStr);
                printQuizzesInProgress($inProgStr);
                printQuizzesCompleted($completeStr);

                mysqli_close($conn);
            ?>
        </main>

        <!-- Footer Section -->
        <footer>
            <p>Red Pen</p>
            <p>&copy; 2024</p>
        </footer>
    </body>
</html>
