<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="student.css">
        <script defer src="questions.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
    </head>

    <body>
        <form method="POST" id="formID">
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
        <form>
    </body>
</html>
