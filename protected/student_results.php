
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
                        1) Display the results of the user for all quizzes,
                           more specifically will show the
                            * Quiz name
                            * Quiz attempt number
                            * Attempts for each question in the quiz
                */

                $QUEST_COMP_INC = 1000;

                require_once("../generalFunctionality.php");
                require_once("alumni_functionality.php");
				require_once("getAttempts.php");

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
                $quizTable = sprintf("SELECT * FROM quizzes;", $sem);
                $quizTableRes = mysqli_query($conn, $quizTable);

                while($quizTableRow = mysqli_fetch_assoc($quizTableRes))
                {
                    if($quizTableRow["visible"])
                    {
                        $studentResults = sprintf("SELECT * FROM students_%s WHERE username REGEXP '^%s'",
                        $sem, $_SERVER["REMOTE_USER"]);
                        $studentRes = mysqli_query($conn, $studentResults);

                        $outTable = "<table>";
                        $tHeaderStr = "";
                        $quizAttempt = 1;

                        while($quizAttRow = mysqli_fetch_assoc($studentRes))
                        {
                            $questNum = 1;
                            $colStr = sprintf("%s_%d", $quizTableRow["quiz_id"], $questNum);
                            $outputAttempt = false;

                            while($questNum < count($quizAttRow) &&
                                                array_key_exists($colStr, $quizAttRow))
                            {
                                $colStr = sprintf("%s_%d", $quizTableRow["quiz_id"], $questNum);

                                if($quizAttRow[$colStr] != 0)
                                {
                                    $outputAttempt = true;
                                }
                                $questNum++;
                            }

                            // Either output if the attempt is meant to be or
                            // it's the first quiz attempt (always have at least one row displayed)
                            if($outputAttempt || $quizAttempt == 1)
                            {

                                /*
                                    A (for the most part) line-by-line description
                                    of the following code can be described as (if needed):

                                        * Create the HTML element for the "Attempt" column of the table
                                        * Assuming it's the first attempt
                                            * Create the "Attempt" header
                                        * While we have not output every question
                                            * Assuming it's the first attempt
                                                * Create the "Question <num>" header
                                            * Get the adjusted attempt value (only if completed or giveup)
                                            * Only output an attempt if > 0, otherwise, output "TBD"
                                            * Update the column title we want to get the value of
                                        * Assuming it's the first attempt
                                            * Create a string for the quiz name header
                                        * Contetenate the generated string as it's own row
                                */

                                $tRowStr = "<td>" . $quizAttempt . "</td>";
                                $questNum = 1;
                                $colStr = sprintf("%s_%d", $quizTableRow["quiz_id"], $questNum);

                                if($quizAttempt == 1)
                                {
                                    $tHeaderStr .= "<th>Attempt</th>";
                                }

                                while($questNum < count($quizAttRow) &&
                                                    array_key_exists($colStr, $quizAttRow))
                                {
                                    if($quizAttempt == 1)
                                    {
                                        $tHeaderStr .= "<th>Question " . $questNum . "</th>";
                                    }
                                    $attempt = getCorrectAttNum($quizAttRow[$colStr], $QUEST_COMP_INC);

                                    if($attempt == null || $attempt == 0)
                                    {
                                        $tRowStr .= "<td>TBD</td>";
                                    }
                                    else
                                    {
                                        $tRowStr .= "<td>" . $attempt . "</td>";
                                    }

                                    $questNum++;
                                    $colStr = sprintf("%s_%d", $quizTableRow["quiz_id"], $questNum);
                                }

                                if($quizAttempt == 1)
                                {
                                    $dispQuizName = getQuizNameDisplay($quizTableRow["quiz_name"]);
                                    $outTable .= "<div><h2>" . $dispQuizName . "</h2></div>";
                                    $outTable .= $tHeaderStr;
                                }

                                $outTable .= "<tr>";
                                $outTable .= $tRowStr;
                                $outTable .= "</tr>";
                            }

                            $quizAttempt++;
                        }

                        $outTable .= "</table><br><br>";
                        echo $outTable;
                    }
                }

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
