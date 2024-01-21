<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="student.css">
        <link rel="stylesheet" href="styles.css">
        <title>Red Pen</title>
        <script defer src="questions.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
        <script defer src="questions.js"></script>
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
                <li><a href="quizEditor.php">Quiz Editor</a></li>
                <!-- <li><a href="">Login</a></li> -->
            </ul>
        </nav>
        </header>

        <main>
                <div class="instructions">
                    <p>Please find the error in the sentence below.
                    You don't have to explain the error or fix it; just click on
                    the part you think is wrong. If I agree, you'll see my
                    explanation. If you're off base, try again.
                    </p>
                </div>
                <form method="POST" class="quiz">
                <div class="question1">
                    <button id="penButton"> 🖍️ </button>
                    <p><strong>Question: 1</strong></p>
                    <!-- Inject the questions for the current quiz -->
                    <?php
                        require_once("getQuestions.php");
                    ?>
                    <div id="attemptSection">
                        <?php
                            require_once("getAttempts.php");
                        ?>
                    </div>
                    <div style="display: none;" id="giveUpWrapper">
                        <div style="display: inline-block;">&emsp;&emsp;</div>
                        <a class="giveupButton">Give Up?</a>
                    </div>
                    <div id="explanationSection">
                        <?php
                            require_once("getExplanation.php");
                        ?>
                    </div>
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
