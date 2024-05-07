<?php
    require_once("userAuthentication.php");
    userAuthenticator();
?>
<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="../styles.css">
        <title>Red Pen</title>
        <script defer src="handle_quizzes.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
    </head>
    <body>
         <!-- Header Section -->
         <header class="fixed-header">
        <a href="professor.php">
            <img src="../photos/nau.png" alt="Logo" width="200" height="125">
        </a>
        <img src="../photos/Red Pen.png" alt="Logo" width="425" height="125">
        <nav>
            <ul>
                <li><a href="professor.php">Home</a></li>
                <li><a href="professor_quiz_list.php">View Quizzes</a></li>
                <!-- <li><a href="quizEditor.php">Quiz Editor</a></li> -->
                <li><a href="professor_page.html">Professor Page</a></li>
                <li><a href="questionsPreview.php">Back</a></li>
            </ul>
        </nav>
        </header>
        <main>
            <div class="instructions">
                <p>
                    Create your quiz question
                    <br><br>
                    Note the following special character render combinations:
                    <br>
                    Two hyphens: En-dash
                    <br>
                    Three hyphens: Em-dash
                </p>
            </div>

            <div class="quiz-editor">

                <?php
                require_once("../generalFunctionality.php");

                $conn = connectToDatabase();

                if(!$conn)
                {
                    echo "Something went wrong when connecting to the database";
                }

                session_start();

                $activeTable = $_SESSION['setTable'];

                $sql = sprintf("SELECT quiz_name FROM `quizzes` where quiz_id = '%s'", $activeTable);
                $res = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($res))
                {
                    $activeQuiz = $row['quiz_name'];
                }

                echo "<h1 id='quizTitle'>".$activeQuiz."</h1>";

                if($_SESSION['editing'] == "true")
                    {
                    $editQuestion = $_SESSION['question'];
                    $editExplanation = $_SESSION['explanation'];
                    $editPosition = $_SESSION['position'];
                    $editTag = $_SESSION['tag'];
                    $editID = $_SESSION['id'];
                    }
                else
                    {
                    $editQuestion = "";
                    $editExplanation = "";
                    $editPosition = "-1";
                    $editTag = "";
                    $editID = "";
                    }



                echo "<label for='quizQuestion'>Enter a sentence</label><br>";
                echo "<textarea type='text' id='quizQuestion' onclick='setUpQuestionEdit(".json_encode($editPosition).")'";
                echo "placeholder='Sentence goes here.' rows='5' cols='22'>".$editQuestion."</textarea><br><br>";

                echo "<label for='quizExplanation'>Explanation</label><br>";
                echo "<textarea type='text' id='quizExplanation'";
                echo "placeholder='Enter an explanation on why this word shouldn't be there.' rows='5' cols='22'>".$editExplanation."</textarea><br>";

                echo "<h2> </h2>"; // renderedString goes here

                echo "<label for='tagSelect'>By Tag:</label><br>";
                echo "<select id='tagSelect'>";



                if($editTag == "")
                    {
                    echo "<option value='' selected disabled>Select Tag</option>";
                    }
                else
                    {
                    echo "<option value='' disabled>Select Tag</option>";
                    }

                $sql = "SELECT * FROM tagList";

                $res = mysqli_query($conn, $sql);

                while($row = mysqli_fetch_assoc($res))
                {
                    if($row['tag_abbr'] == $editTag)
                        {
                        echo "<option value='".$row['tag_abbr']."' selected>" .$row['tag_name']. "</option>";
                        }
                    else
                        {
                        echo "<option value='".$row['tag_abbr']."'>" .$row['tag_name']. "</option>";
                        }
                }

                mysqli_close($conn);

                echo "</select>";

                echo "<input type='text' id='newTag' name='name' placeholder='New Tag Name'><br><br>";
                echo "<input type='text' id='newTagAbbr' name='abbr' placeholder='New Tag Abbr'><br><br>";


                echo "<button id='addTag' onclick='toggleTags()'>New Tag</button>";
                echo "<button id='oldTag' onclick='toggleTags()'>Select Tag</button>";

                if($_SESSION['editing'] == "true")
                    {
                    echo "<button class='enter' onclick='runEditQuestion(".json_encode($editID).", ".json_encode($editPosition).")'>Submit</button>";
                    }
                else
                    {
                    echo "<button class='enter' onclick='addQuestion()'>Submit</button>";
                    }

                ?>

                <div>
                <span id="error-print"></span>
                </div>
            </div>
        </main>

        <!-- Footer Section -->
        <footer>
            <p>Red Pen</p>
            <p>&copy; 2024</p>
        </footer>
    </body>
</html>
