const NUM_ATTEMPTS_BEFORE_GIVEUP = 3;

let questionAttempts = [];
let questionList = [];

/**
 * Once the window loads, all the event listeners for clicking words
 */
window.onload = () =>
{
    setupEventListeners();

    console.log(questionList);
}

/**
 * Update the database for the specific student to increase the number
 * of attempts for the question
 */
function queryDatabase()
{
    $.ajax({
      type: 'POST',
      url: 'updateAttempt.php',
      data: {
        // Left empty on purpose
      },
      success: function(data)
      {
        let dataWithAttemptsStr = injectTriesString(data);
        document.getElementById("attemptSection").innerHTML = dataWithAttemptsStr;
      }
    });
};

/**
 * Check to see if a clicked word is the correct answer
 * If so, update the HTML to show the explanation and disable the selections
 */
function checkAnswer(event, question, clickedWord)
{
    $.ajax({
        type: 'POST',
        url: 'getAnswer.php',
        data: {
            // Left empty on purpose
        },
        success: function(data)
        {
            if(isCorrectAnswer(data, question, clickedWord))
            {
                // Get question and answer explanation section of HTML
                var qSec = event.target.parentElement;
                var formSec = qSec.parentElement.parentElement;
                var qExplanationSec = formSec.querySelectorAll("#explanationSection");

                // Disable the question section and show the answer explanation
                qSec.setAttribute("class", qSec.className + " disabled");
                qExplanationSec[0].style.visibility = "visible";
            }
            else
            {
                var qIndex = questionList.indexOf(question, 0);
                questionAttempts[qIndex] += 1;

                if(questionAttempts[qIndex] == NUM_ATTEMPTS_BEFORE_GIVEUP)
                {
                    // Show the button
                }
            }
        }
    });
}

/**
 * Update a specific students "give up" attempt on the database
 */
function updateGiveUpAttempt()
{
    $.ajax({
        type: 'POST',
        url: 'updateGiveUp.php',
        data: {
            // Left empty on purpose
        },
        success: function(data)
        {
            // Left empty for future additions
        }
    });
}

/**
 * Check to see if the clicked word is the correct answer according to the
 * database
 */
function isCorrectAnswer(data, question, clickedWord)
{
    // Get all words in question, and get the the word at "wordIndex"
    var words = question.split(" ");
    var wordIndex = parseInt(data);
    var strNoNewLine = words[wordIndex - 1].replace("\n", "");

    return strNoNewLine == clickedWord;
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
    finalStr += "</p>";

    return finalStr;
}

/**
 * Take a string and extract the text values from it by removing the
 * HTML tags around the text
 */
function stripHTML(string)
{
    let strippedString = "";
    let endTag = false;

    // Loop through string and capture the characters between
    // the close and open tags (detected by '>' and '<', repectively)
    for(let index = 0; index < string.length; index++)
    {
        if(string[index] == '>')
        {
            endTag = true;
        }

        if(string[index] == '<')
        {
            endTag = false;
        }

        if(endTag && string[index] != '<' && string[index] != '>')
        {
            strippedString += string[index];
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
    var strippedQuestion;
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
                var parentElement = event.target.parentNode;

                let questionStrNoHTML = stripHTML(parentElement.innerHTML);

                // Get attempts and check if the word is the correct answer
                queryDatabase(questionStrNoHTML);
                checkAnswer(event, questionStrNoHTML, event.target.innerHTML);

                // Deactivate the clicked word (see student.css for more informatoin)
                event.target.setAttribute("class", event.target.className + " disabled");
            }
        });
    }

    for(index = 0; index < giveUpButtons.length; index++)
    {
        giveUpButton = giveUpButtons[index];
        strippedQuestion = stripHTML(giveUpButton.innerHTML);
        questionList.push(giveUpButton.innerHTML);

        giveUpButton.addEventListener('click', function(event)
        {
            updateGiveUpAttempt();

            // Add a new attempt total for individual questions
            questionAttempts.push(0);
        })
    }
}

/**
 * Check to see if the user can click a word
 * They can only click it if the word has not been clicked
 * and the correct answer has not been chosen
 */
function canClickWord(event)
{
    var questionSec = event.target.parentNode;
    var currWord = event.target;

    return !questionSec.className.includes("disabled") &&
           !currWord.className.includes("disabled");
}
