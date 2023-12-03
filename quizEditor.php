<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="student.css">
        <link rel="stylesheet" href="styles.css">
        <title>Red Pen</title>
        <script defer src="questions.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
    </head>

    <body>
         <!-- Header Section -->
         <header class="fixed-header">
        <a href="index.html">
            <img src="photos/nau.png" alt="Logo" width="200" height="125">
        </a>
        <img src="photos/Red Pen.png" alt="Logo" width="425" height="125">
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="quizzes.html">Quizzes</a></li>
                <!-- <li><a href="">Login</a></li> -->
            </ul>
        </nav>
        </header>
        <main>
            <?php
                if(isset($_POST['question']) and
                   isset($_POST['answerPosition']) and
                   isset($_POST['explanation']))
                {
                    require_once("insertQuestions.php");
                }
            ?>
            <div class="instructions">
                <p>
                    Create your quiz question
                </p>
            </div>
            <form method="POST" id="formID">

                <label for="quizQuestion">Enter a sentence</label><br>
                <textarea type="text" id="quizQuestion" name="question"
                    placeholder="sentence goes here"rows="5" cols="22"></textarea><br><br>

                <label for="incorrectWord">Enter incorrect word number</label><br>
                <input type="text" id="incorrectWord" name="answerPosition"
                    placeholder="'1'"/><br><br>

                <label for="quizExplanation">Explanation</label><br>
                <textarea type="text" id="quizExplanation" name="explanation"
                    placeholder="Enter an explanation on why this word shouldn't be there"rows="5" cols="22"></textarea><br>

                <input type="submit" value="Submit"/>
                <div>
                    
                </div>
            </form>
        </main>

        <!-- Footer Section -->
        <footer>
            <p>Red Pen</p>
            <p>&copy; 2024</p>
        </footer>
    </body>
</html>
