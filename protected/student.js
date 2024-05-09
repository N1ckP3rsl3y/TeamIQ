/* Constants */
var GIVEUP_INDEX = 3;
var QUESTION_INDEX = 1;
var NUM_ATTEMPTS_BEFORE_GIVEUP = 3;
var COMPLETE_QUEST_INC = 1000;

// Attempt section
var NORM_POSTFIX = 0;
var COMPLETE_POSTFIX = 1;
var GIVEUP_POSTFIX = 2;

var OPEN_TAG = '<';
var CLOSE_TAG = '>';

/* Global information */
var questAttempts = [];
var questionComps = [];
var questionAnsPos = [];
var questionHTML = [];
var rawAttempts = [];
var currentQuizName = "";
var currQuestNum = 0;
var submitButton = null;
var submitActivated = false;
var quizAttemptNum = -1;

/**
 * Once the window loads, all the event listeners for clicking words
 */
window.onload = () =>
{
    setupEventListeners();
}

/**
 * Update a quiz within the database to be completed, thus the next time
 * the student wants to take the quiz, the program can tell it needs to
 * create a new attempt for the student
 */
function reportComplete()
{
    $.ajax({
        type: 'POST',
        url: 'updateQuizComplete.php',
        data:
        {
            quizName: currentQuizName,
            quizAttempt: quizAttemptNum
        },
        async: false,
        success: function(data)
        {
            // Leave blank, nothing to do here
        }
    })
}


/**
 * Update the database for the specific student to increase the number
 * of attempts for the question
 */
function updateQuestionAttempt(event)
{
    var answerCorrect = checkAnswer(event);
    var postfixChoice = (answerCorrect) ? COMPLETE_POSTFIX : NORM_POSTFIX;
    var displayValue = (answerCorrect) ? questAttempts[currQuestNum] : rawAttempts[currQuestNum];
    if(answerCorrect) event.target.setAttribute("class", event.target.className + " correct");

    // Update question complete if the answer is correct
    questionComps[currQuestNum] = answerCorrect;

    $.ajax({
        type: 'POST',
        url: 'updateAttempt.php',
        data:
        {
            quizName: currentQuizName,
            quizNum: currQuestNum + 1, // Base 1 instead of 0
            currAttempts: rawAttempts[currQuestNum],
            quizAttempt: quizAttemptNum
        },
        success: function(data)
        {
            updateAttemptVisual("<p>" + displayValue + "</p>", postfixChoice);
        }
    });
}

/**
 * Get all quiz questions for the inputted selected quiz name
 */
function getQuizQuestions(selectedQuizName)
{
    $.ajax({
        type: 'POST',
        url: 'student_quiz.php',
        data:
        {
          quizName: selectedQuizName
        },
        success: function(data)
        {
            var data = JSON.parse(data);
            var main = document.getElementsByTagName("main")[0];
            questAttempts = data["attempts"];

            questionAnsPos = data["answer_positions"];
            questionComps = data["question_comp"];
            questionHTML = data["arrayOfHTML"];
            main.innerHTML = data["arrayOfHTML"][0];
            quizAttemptNum = data["quiz_attempt"];
            rawAttempts = data["rawAttempts"];
            currentQuizName = selectedQuizName;
            setupEventListeners();
            renderButtons();
            renderNextQuestion(0, 1);
        }
    });
}

/**
 * Render the next question on user input
 */
function renderNextQuestion(prevOrNext, directQuestionNum)
{
    if (prevOrNext == -1 || prevOrNext == 1)
    {
        // prevOrNext is -1 for previous question and 1 for next question
        currQuestNum += prevOrNext;
    }
    else
    {
        currQuestNum = directQuestionNum - 1;
    }
    var main = document.getElementsByTagName("main")[0];
    main.innerHTML = questionHTML[currQuestNum];
    attemptSection = document.getElementById("attemptSection");
    let currentAttempts = questAttempts[currQuestNum];
    let rawAttemptVal = rawAttempts[currQuestNum];
    let compVal = NORM_POSTFIX;

    // pass proper value in to update visuals
    // for references on what the values of compVal mean, refer to the updateVisualAttempt function
    if (rawAttemptVal > COMPLETE_QUEST_INC)
    {
        disableQuestion();
        compVal = COMPLETE_POSTFIX;
    }
    else if (rawAttemptVal < 0)
    {
        disableQuestion();
        compVal = GIVEUP_POSTFIX;
    }

    if (currentAttempts >= NUM_ATTEMPTS_BEFORE_GIVEUP && compVal == NORM_POSTFIX)
    {
        showQuestionGiveUpButton();
    }
    updateAttemptVisual("<p>" + currentAttempts + "</p>", compVal);

    setupEventListeners();

    renderButtons();
    if (currQuestNum == questionHTML.length - 1)
    {
        updateSubmitIfApplicable();
    }
}


function renderButtons()
{
    if (currQuestNum != 0)
    {
        let prevButtonSlot = "";
        prevButtonSlot = document.getElementById("prevButtonSlot"); //<button id='nextButton' type='button'> Next </button>";
        prevButtonSlot.innerHTML = "<button id='prevButton' type='button' class='navButton'> Previous </button>";

        var nextButton = document.getElementById("prevButton");
        nextButton.addEventListener("click", () => {renderNextQuestion(-1, 0)});

    }

    if (currQuestNum != questionHTML.length - 1)
    {
        // set up next button
        let nextButtonSlot = "";
        nextButtonSlot = document.getElementById("nextButtonSlot"); //<button id='nextButton' type='button'> Next </button>";
        nextButtonSlot.innerHTML = "<button id='nextButton' type='button' class='navButton'> Next </button>";

        var nextButton = document.getElementById("nextButton");
        nextButton.addEventListener("click", () => {renderNextQuestion(1, 0)});


    }
    else
    {
        let completeButtonSlot = "";
        completeButtonSlot = document.getElementById("completeButtonSlot"); //<button id='nextButton' type='button'> Next </button>";
        completeButtonSlot.innerHTML = "<input type='submit' value='Complete' class='completeButton' id='submitButton' disabled>";

        var completeButton = document.getElementById("submitButton");
        completeButton.addEventListener("click", () => {reportComplete()});


    }

    let buttonDiv = document.getElementById("buttonArray");
    let buttonValue = 1;
    while (buttonValue < questionHTML.length + 1)
    {
        var button = document.createElement('button');
        button.innerHTML = buttonValue;
        button.classList.add("questionRefButton");
        if (buttonValue == currQuestNum + 1)
        {
            button.classList.add("selectedButton");
        }

        else if (questionComps[buttonValue - 1])
        {
            button.classList.add("correctQuestion");
        }

        button.addEventListener("click", function(event)
        {
            renderNextQuestion(0, event.target.innerHTML);

        });
        buttonDiv.appendChild(button);
        buttonValue++;
    }
}
/**
 * Disable the choices within a given question
 */
function disableQuestion()
{
    // Get question and answer explanation section of HTML
    var giveUpQSec = document.getElementById("giveUpWrapper");
    var qSec = document.getElementsByClassName("quiz");
    var qExplanationSec = document.getElementById("expSecWrapper");

    // Disable the question section and show the answer explanation
    qSec.disabled = true;
    qExplanationSec.style.display = "inline-block";

    // Disable give up button
    giveUpQSec.style.display = "none";
}

/**
 * Check to see if a clicked word is the correct answer
 * If so, update the HTML to show the explanation and disable the selections
 */
function checkAnswer(event)
{
    var correct = false;

    questAttempts[currQuestNum]++;

    if(isCorrectAnswer(event, currQuestNum))
    {
        disableQuestion();
        correct = true;
        rawAttempts[currQuestNum] += COMPLETE_QUEST_INC + 1;
    }
    else
    {
        rawAttempts[currQuestNum]++;

        if(questAttempts[currQuestNum] === NUM_ATTEMPTS_BEFORE_GIVEUP)
        {
            // Show the button
            showQuestionGiveUpButton(event);
        }
    }

    return correct;
}

/**
 * Update a specific students "give up" attempt on the database
 */
function updateGiveUpAttempt(event)
{
    var visualAttempt;

    // update local copy of give up
    questAttempts[currQuestNum]++;
    rawAttempts[currQuestNum] = questAttempts[currQuestNum] * -1;

    visualAttempt = "<p>" + questAttempts[currQuestNum] + "</p>";

    $.ajax({
        type: 'POST',
        url: 'attemptGiveUp.php',
        data: {
            quizName: currentQuizName,
            quizNum: currQuestNum + 1,
            currAttempts: rawAttempts[currQuestNum],
            quizAttempt: quizAttemptNum
        },
        success: function()
        {
            let rawAttemptVal = rawAttempts[currQuestNum];

            if (rawAttemptVal < 0)
            {
                updateAttemptVisual(visualAttempt, GIVEUP_POSTFIX);
                disableQuestion();
                questionComps[currQuestNum] = true;

                if(!submitActivated)
                {
                    updateSubmitIfApplicable();
                }
            }
        }
    });
}

/**
 * Check to see if the clicked word is the correct answer according to the
 * database (stored in `questionAnsPos`)
 */
function isCorrectAnswer(event, qIndex)
{
    let corrWordInd = questionAnsPos[qIndex] - 1;
    let siblingTags = event.target.parentElement.children;
    let tagNum = 0;
    let sibTag = siblingTags[0];

    while(sibTag != event.target) {
        tagNum++;
        sibTag = siblingTags[tagNum];
    }

    return tagNum == corrWordInd;
}

/**
 * Inject the string "tries" into the attempts portion of the HTML
 * by deconstructing the "data" string and reconstructing it
 */
function injectTriesString(data)
{
    var strippedStr = data.replace("</p>", "");
    var finalStr = "<p>";

    finalStr += strippedStr;
    finalStr += " tries";

    return finalStr;
}

/**
 * Take a string and extract the text values from it by removing the
 * HTML tags around the text
 */
function stripHTML(string)
{
    var strippedString = "";
    var endTag = false;
    var currChar;

    // Loop through string and capture the characters between
    // the close and open tags (detected by '>' and '<', repectively)
    for(var index = 0; index < string.length; index++)
    {
        currChar = string[index];

        if(currChar == CLOSE_TAG)
        {
            endTag = true;
        }

        if(currChar == OPEN_TAG)
        {
            endTag = false;
        }

        if(endTag && currChar != OPEN_TAG && currChar != CLOSE_TAG)
        {
            strippedString += currChar;
        }
    }

    return strippedString;
}

/**
 * Set up all event listeners (wait for a click)
 */
function setupEventListeners()
{
    var wordElements = document.querySelectorAll(".wordChoice");
    var giveUpButtons = document.querySelectorAll(".giveupButton");
    var giveUpButton, wordElement;
    var index;

    for(index = 0; index < wordElements.length; index++)
    {
        wordElement = wordElements[index];

        wordElement.addEventListener('click', function(event)
        {
            // Make sure the word is clickable (see the function
            // definition for more description)
            if(canClickWord(event))
            {
                // Get attempts and check if the word is the correct answer
                updateQuestionAttempt(event);

                if(!submitActivated)
                {
                    updateSubmitIfApplicable();
                }

                // Deactivate the clicked word (see student.css for more information)
                event.target.setAttribute("class", event.target.className + " disabled");
            }
        });
    }

    for(index = 0; index < giveUpButtons.length; index++)
    {
        giveUpButton = giveUpButtons[index];

        giveUpButton.addEventListener('click', function(event)
        {
            updateGiveUpAttempt(event);
        })

        // Add or reset an attempt total for individual questions
        questAttempts.push(0);
    }



}

/**
 * Check to see if the user can click a word
 * They can only click it if the word has not been clicked
 * and the correct answer has not been chosen
 */
function canClickWord(event)
{
    var currWord = event.target;

    return !questionComps[currQuestNum] &&
           !currWord.className.includes("disabled");
}

/**
 *  Show the give up button for a specific question
 */
function showQuestionGiveUpButton()
{
    var giveUpQSec = document.getElementById("giveUpWrapper");

    giveUpQSec.setAttribute("style", "display: inline-block;");
}

/**
 * Update the specific question's attempt
 */
function updateAttemptVisual(data, compGaveUpOrNone)
{
    let dataWithAttemptsStr = injectTriesString(data);
    let qAttSection = document.getElementById("attemptSection");

    switch(compGaveUpOrNone)
    {
        case NORM_POSTFIX:
            qAttSection.innerHTML = dataWithAttemptsStr + "</p>";
            break;
        case COMPLETE_POSTFIX:
            qAttSection.innerHTML = dataWithAttemptsStr + " (Completed) </p>";
            break;
        case GIVEUP_POSTFIX:
            qAttSection.innerHTML = dataWithAttemptsStr + " (Gave Up) </p>";
            break;
        default:
            break; // Do nothing
    }
}

/**
 * Checks to see if all attempts for each question in the quiz is
 * completed
 *      * If so, enable the button so the user can submit the quiz
 *      * Otherwise, do nothing
 */
function updateSubmitIfApplicable()
{
    var index = 0;
    var activate = true;
    if (currQuestNum == questionHTML.length - 1)
    {
        submitButton = document.getElementById('submitButton');

        while(index < questionComps.length && activate)
        {
            if(!questionComps[index])
            {
                activate = false;
            }
            index++;
        }

        if(activate)
        {
            submitActivated = true;
            submitButton.disabled = false;
        }
    }
}
