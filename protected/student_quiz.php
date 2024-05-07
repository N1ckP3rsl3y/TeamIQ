<?php
    /*
        Purpose(s) of this file:
            1) Get and display the questions within a quiz that was selected
            from the user and display them
    */

    header('Content-Type: text/html; charset=utf-8');

    require_once("../generalFunctionality.php");
    require_once("getAttempts.php");

    $QUEST_COMP_INC = 1000;
    $GIVEUP_STR = "<div style='display: none;' id='giveUpWrapper'>
                &emsp;&emsp;<a class='giveupButton'>Give Up?</a>
                </div>
                <br>";

    $EXPLANATION_FORMAT = "<div style='display: none;' id='expSecWrapper'>
                        <div id='explanationSection'>
                        <p>%s</p>
                        </div>
                        </div>";

    $qNum = 1;
    $attResult = null;
    $attReq = null;
    $ansPosStr = "";
    $buttonsStr = "";

    $conn = connectToDatabase();
    $sem = getCurrentSemester($conn);

    mysqli_query($conn, 'SET NAMES UTF8');

    $quizName = $_POST["quizName"];
    $quizInfo = array(
        "attempts" => array(),
        "rawAttempts" => array(),
        "question_comp" => array(),
        "answer_positions" => array(),
        "arrayOfHTML" => array(),
        "quiz_attempt" => checkForNewQuizAttempt($conn, $quizName,
        $_SERVER["REMOTE_USER"], $sem)
    );

    /*
        Get/display questions and explanations for the current quiz

        Output in the format of

        - All question buttons
        - Question header (e.g., "Question 1")
        - Rendered text (clickable text)
        - Explanation section (hidden by default)
        - Attempt section (i.e., # tries)
        - Give up button (hidden by default, appears after three incorrect tries)
    */

        $qRetrieve = sprintf(
            "SELECT question, answer_position, explanation, id FROM %s",
            $quizName);

        $res = mysqli_query($conn, $qRetrieve);

        for($index = 0; $index < mysqli_num_rows($res); $index++)
        {
            $buttonsStr .= " <button id='penButton'> 🖍️ </button>";
        }

    mysqli_data_seek($res, 0); // Reset result pointer

    while($row = mysqli_fetch_assoc($res))
    {
        $questionStr = "";
        $triesSuffix = "";
        $questionHTML = "";

        $questionHTML .= "<div class='instructions'>";
        $questionHTML .= sprintf("<p>Please find the error in the sentence below.
                                    You don't have to explain the error or fix it;
                                    just click on the part you think is wrong.
                                    The issue can be a word, character, or
                                    even a space. If I agree, you'll see my explanation.
                                    If you're off base, try again.</p>\n", $qNum);
        $questionHTML .= "</div>";

        $questionHTML .= "<form method='POST-REDIRECT-GET' class='quiz' action='student_quiz_list.php'";
        $questionHTML .= " onsubmit='reportComplete()'>";

        $questionHTML .= $buttonStr;

        array_push($quizInfo["answer_positions"], $row["answer_position"]);
        $quizInfo["buttonsHTML"] .= " <button id='penButton'> 🖍️ </button>";

        $qAttNum = getAttempts($conn, $quizName, $attResult, $attReq,
                                $sem, $quizInfo["quiz_attempt"]);
        $disableQuest = ($qAttNum < 0 || $qAttNum > $QUEST_COMP_INC);

        // Create "Question ..." and render the string
        $questionHTML .= "<div id='question'>\n";

        $questionHTML .= "<div id='buttonArray'>";
        $questionHTML .= "</div>";

        $questionHTML .= sprintf("<p><strong>Question: %d</strong></p>\n", $qNum);

        $questionHTML .= ($disableQuest) ? "<div id='qTextWrapper' class='disabled'>\n" :
                                                    "<div id='qTextWrapper'>\n";

        $questionHTML .= renderString($row["question"]) . "\n<br>";
        $questionHTML .= "</div>\n";

        // Print the attempt section
        // Only display positive attempt values (i.e., don't show give up)
        $questionHTML .= "<div id='attemptSection' onload='updateQuestionAttempt()'>\n";
        $questionHTML .= "<p>";
        array_push($quizInfo["question_comp"], $qAttNum > $QUEST_COMP_INC || $qAttNum < 0);

        // push raw attempts for rerendering on js side
        array_push($quizInfo["rawAttempts"], $qAttNum);

        $attNum = getCorrectAttNum($qAttNum, $QUEST_COMP_INC);
        array_push($quizInfo["attempts"], (int)$attNum);

        $questionHTML .= $attNum;
        $questionHTML .= " tries";
        $questionHTML .= getCorrectAttSuffix($qAttNum, $QUEST_COMP_INC);
        $questionHTML .= "</p>";
        $questionHTML .= "</div>\n";

        // Print the give up button and the explanation side (both hidden)
        $questionHTML .= "<div style='display: none;' id='giveUpWrapper'>";
        $questionHTML .= "&emsp;&emsp;<a class='giveupButton'>Give Up?</a>";
        $questionHTML .= "</div>";
        $questionHTML .= "<br>";

        $questionHTML .= "<div style='display: none;' id='expSecWrapper'>";
        $questionHTML .= "<div id='explanationSection'>";
        $questionHTML .= "<p>" . $row['explanation'] . "</p>\n";
        $questionHTML .= "</div>";
        $questionHTML .= "</div>";

        $questionHTML .= "<div id='prevButtonSlot'></div>\n";
        $questionHTML .= "<div id='nextButtonSlot'></div>\n";
        $questionHTML .= "<div id='completeButtonSlot'></div>\n";


        $questionHTML .= "</div>\n";
        $questionHTML .= "</form>\n";

        array_push($quizInfo["arrayOfHTML"], $questionHTML);
        $qNum = $qNum + 1;
    }

    mysqli_close($conn);

    // Return information in JSON format
    echo json_encode($quizInfo);
?>
