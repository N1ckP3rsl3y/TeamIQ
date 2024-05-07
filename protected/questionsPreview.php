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
                    <li><a href="professor_quiz_list.php">Quizzes</a></li>
                    <!-- <li><a href="quizEditor.php">Quiz Editor</a></li> -->
                    <li><a href="professor_page.html">Professor Page</a></li>
                    <li><a href="quizzes.php">Back</a></li>
                </ul>
            </nav>
        </header>

         <!-- Main Section -->
         <main>


            <div id="remindersContainer">

                    <button id="plus" onclick="addNew()">
                        <h3>➕</h3>
                    </button>

                    <!-- Placeholders -->
                    <?php
                    require_once("../generalFunctionality.php");

                    $conn = connectToDatabase();

                    if(!$conn)
                    {
                        echo "Something went wrong when connecting to the database";
                    }

                    session_start();

                    $activeTable = $_SESSION['setTable'];

                    echo "<button id='plus' onclick='editQuizName(".json_encode($activeTable).")'>";
                    echo    "<h3>🖊️</h3>";
                    echo "</button>";

                    $sql = sprintf("SELECT quiz_name FROM `quizzes` where quiz_id = '%s'", $activeTable);
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res))
                    {
                        $activeName = $row['quiz_name'];
                    }

                    echo "<h1>".$activeName." <br> Question Previews</h1>";

                    $sql = sprintf("SELECT * FROM `%s` ORDER BY `id`", $activeTable);

                    $res = mysqli_query($conn, $sql);

                    while($row = mysqli_fetch_assoc($res))
                    {
                        echo "<div id='QuestionContainer'>";
                        echo "<h3>".$row['question']. "</h3>";
                        echo "</div>";
                        echo "<button id='edit' onclick='editQuestion(".json_encode($row['id']).", ".json_encode($activeTable).")'><h1><center>edit</center></h1></button>";
                        echo "<button id='remove' onclick='deleteQuestion(".json_encode($row['id']).", ".json_encode($activeTable).")'><h1><center>remove</center></h1></button>";
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
