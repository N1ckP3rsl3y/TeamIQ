<?php
    require_once("userAuthentication.php");
    userAuthenticator();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../styles.css">
        <title>Red Pen</title>
        <script defer src="handle_quizzes.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
    </head>
    <body>
         <!-- Header Section -->
         <header class="fixed-header">
            <a href="professor.php">
                <img src="../photos/nau.png" alt="NAU Logo" width="200" height="125">
            </a>
            <img src="../photos/Red Pen.png" alt="Red Pen Logo" width="425" height="125">
            <nav>
                <ul>
                    <li><a href="professor.php">Home</a></li>
                    <li><a href="professor_quiz_list.php">View Quizzes</a></li>
                    <!-- <li><a href="quizEditor.php">Quiz Editor</a></li> -->
                    <li><a href="professor_page.html">Professor Page</a></li>
                    <li><a href="professor_page.html">Back</a></li>
                </ul>
            </nav>
        </header>

         <!-- Main Section -->
         <main>
            <div id="remindersContainer">
                <button id="plus" onclick="newQuiz()">
                    <h3>➕</h3>
                </button>

                <h1>Quiz Editor</h1>
                    <?php
                    require_once("../generalFunctionality.php");

                    $conn = connectToDatabase();

                    if(!$conn)
                    {
                        echo "Something went wrong when connecting to the database";
                    }

                    $sql = "SELECT * FROM quizzes";

                    $res = mysqli_query($conn, $sql);

                    while($row = mysqli_fetch_assoc($res))
                    {
                        $quizValue = $row['quiz_id'];
                        $quizDisplay = $row['quiz_name'];


                        echo "<button id='QuizContainer' onclick='setQuiz(" .json_encode($quizValue).")'><h2>".$quizDisplay."</h2></button>";
                        if($row['visible'] == 0)
                            {
                            echo "<button id='sample' onclick='makeVisible(" .json_encode($quizValue).")'><h1><center>🔐</center></h1></button>";
                            }
                        else
                            {
                            // change this one
                            echo "<button id='sample' onclick='makeHidden(" .json_encode($quizValue).")'><h1><center>✅</center></h1></button>";
                            }
                        echo "<button id='sample'onclick='deleteTable(".json_encode($quizValue).")'><h1><center>🗑️</center></h1></button>";
                    }
                    mysqli_close($conn);
                    ?>

            </div>
        </main>

         <!-- Footer Section -->
         <footer>
               <p>Red Pen</p>
               <p>&copy; 2024</p>
         </footer>


    </body>
</html>
