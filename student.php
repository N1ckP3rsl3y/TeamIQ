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
            <div class="instructions">
                <p>Please find the error in the sentence below. 
                You don't have to explain the error or fix it; just click on 
                the part you think is wrong. If I agree, you'll see my 
                explanation. If you're off base, try again.
                </p>
            </div>
            <form method="POST" class="quiz">
            <div id="attemptSection">
                <?php
                    require_once("getAttempts.php");
                ?>
            </div>
            <div>
                <?php
                    require_once("getQuestions.php");
                ?>
            </div>
            <div id="explanationSection">
                <?php
                    require_once("getExplanation.php");
                ?>
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
