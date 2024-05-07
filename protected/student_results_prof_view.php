
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
                <li><a href="student_result_pg.php">Back</a></li>
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

                require_once("../generalFunctionality.php");
				require_once("getAttempts.php");

                $conn = connectToDatabase();
                session_start();

                // Check to see if the student is within the database
                // if not, add them
                // This is currently for handling one semester
                $sem = getCurrentSemester($conn);
                $user = $_SESSION["USER"];

                $checkForStudent = sprintf("SELECT *
                        FROM students_%s WHERE username like '%s.%%'",
                        $sem, $user);
                    
                $res = mysqli_query($conn, $checkForStudent);

                if(mysqli_num_rows($res) == 0)
                {
                echo $user;
                echo " does not exist";
                }
                else
                {

                    echo "<h2>".$user."'s results</h2>";
                    /*
                        Get all quizzes that have been stored by the professor

                        Output in the format of (if quiz is visible to student)

                        - Quiz option
                            - Styled button (goes to respective quiz)
                            - Store JavaScript function call to know what quiz is
                            being taken
                        - End quiz option
                    */
                    $quizTable = sprintf("SELECT * FROM quizzes");
                    $quizTableRes = mysqli_query($conn, $quizTable);

                    while($quizTableRow = mysqli_fetch_assoc($quizTableRes))
                    {
                        if($quizTableRow["visible"])
                        {
                            $outTable = "<table>";
                            $tHeaderStr = "";
                            $quizAttempt = 1;

                            while($quizAttRow = mysqli_fetch_assoc($res))
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

                                if($outputAttempt || $quizAttempt == 1)
                                {
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
                                        $attempt = getCorrectAttNum($quizAttRow[$colStr], 1000);

                                        if($attempt == 0)
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
                                        $outTable .= "<div><h3>" . $dispQuizName . "</h3></div>";
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

                            mysqli_data_seek($res, 0);
                        }
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
