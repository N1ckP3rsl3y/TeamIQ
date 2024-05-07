<?php
    require_once("userAuthentication.php");
    userAuthenticator();
?>
<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="../student.css">
        <link rel="stylesheet" href="../styles.css">
        <title>Red Pen</title>
        <script defer src="student.js"></script>
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
            </ul>
        </nav>
        </header>

        <mainPage>
            <div class="background-container" >
                <img src="../photos/red-pen-background.png" alt="Red Pen">
                <div class="centered-text">
                    <h1>Welcome to Red Pen!</h1>
                    <p > Practice your skills in a way that mimics real editing work.
                        With RedPen, there are no hints.  It is just you and a sentence
                        getting to know one another.  Wield your red pen in
                        the spirit of adventure.
                    </p>
                    <a href="professor_quiz_list.php" class="login-button">View Quizzes</a>
                    <a href="professor_page.html" class="login-button">Professor Page</a>
                    <a href="tags.php" class="login-button">Tags</a>
                </div>
            </div>
        </mainPage>

        <!-- Footer Section -->
        <footer>
            <p>Red Pen</p>
            <p>&copy; 2024</p>
        </footer>
    </body>
</html>
