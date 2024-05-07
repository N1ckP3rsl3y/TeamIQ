var answerPos = -1;
var swap = 1; // 1 means the selected value 0 means they input new tag name
const stringRender = document.querySelector('textarea[type="text"]');

stringRender.addEventListener("blur", (event) => {rendering()});

/**********
    General functions
**********/
    
function setCorrectElement(number)
    {
    answerPos = number;
    }

/*********
    String Rendering
*********/

function rendering()
    {
    stringValue = document.getElementById("quizQuestion").value;
    $.ajax({
        type: 'GET',
        url : 'setRenderData.php',
        data:
            {
            string: stringValue
            },
        success: function(result)
            {
            var response = JSON.parse(result);
            var main = document.getElementsByTagName("h2")[0];
            main.innerHTML = response;
            setupEventListenersProf();
            }
        });
    }

function setupEventListenersProf()
    {
    var wordElements = document.querySelectorAll(".wordChoice");
    var wordElement;
    var index;
    for(index = 0; index < wordElements.length; index++)
        {
        wordElement = wordElements[index];
        wordElement.addEventListener('click', function(event)
            {
            for(var i = 0; i < wordElements.length; i++)
                {
                wordElements[i].setAttribute("class", "wordChoice");
                }
            
            event.target.setAttribute("class", event.target.className + " disabled");
            setCorrectElement(getElementNum(event));
            });

        if(index == answerPos - 1)
            {
            wordElement.setAttribute("class", "wordChoice disabled");
            }
        }
    }

function getElementNum(event)
    {
    let siblingTags = event.target.parentElement.children;
    let tagNum = 0;
    let sibTag = siblingTags[0];

    while(sibTag != event.target) 
        {
        tagNum++;
        sibTag = siblingTags[tagNum];
        }
    return tagNum + 1;
    }

function setUpQuestionEdit(number)
    {
    setCorrectElement(number);
    if(number != -1)
        {
        rendering();
        }
    }
   
/*********
    General Functions
*********/
function addNew()
    {
    setEditData("Q", "false", "true");
    }

function toggleTags()
    {
    if(swap == 1)
        {
        document.getElementById("tagSelect").style.display = "none";
        document.getElementById("newTag").style.display = "inline";
        document.getElementById("newTagAbbr").style.display = "inline";
        document.getElementById("addTag").style.display = "none";
        document.getElementById("oldTag").style.display = "inline";
        swap = 0;
        }
    else
        {
        document.getElementById("tagSelect").style.display = "inline";
        document.getElementById("newTag").style.display = "none";
        document.getElementById("newTagAbbr").style.display = "none";
        document.getElementById("addTag").style.display = "inline";
        document.getElementById("oldTag").style.display = "none";
        swap = 1;
        }
    }

    function setEditData(editChar, editBool, move)
    {
    $.ajax({
        type: 'GET',
        url : 'setEditData.php',
        data:
            {
            edit: editBool,
            char: editChar
            },
        success: function()
            {
            if( move == "true")
                {
                if(editChar == "Q")
                    {
                    window.location.href = "quizEditor.php"; 
                    }
                else if(editChar == "T")
                    {
                    window.location.href = "tags_create.php"; 
                    }
                }
            else
                {
                if(editChar == "Q")
                    {
                    window.location.href = "questionsPreview.php"; 
                    }
                else if(editChar == "T")
                    {
                    window.location.href = "tags.php"; 
                    }
                }
            }
        });
    }


/*********
    Student Handling
*********/

function createNewSemester()
    {
    let inputName = prompt("Please enter new semester:", "Semester");
    if (inputName != null && inputName != "") 
        {
        let text = "Are you sure you want to create a new semester?\nThis will update the set semester to be the semester you create.";
        if (confirm(text) == true) 
            {
            runCreateSemester(inputName);
            } 
        } 
    }

function runCreateSemester(semName)
    {
    $.ajax({
        type: 'GET',
        url : 'newSemester.php',
        data:
            {
            name: semName
            },
        success: function()
            {
            
            }
        });
    }

function moveStudent()
    {
    let inputName = prompt("Please enter a student id:", "Student");
    if (inputName != null && inputName != "") 
        {
        let text = "Are you sure you want to add that student to this semester?\nThis will add their results to this semesters data.";
        if (confirm(text) == true) 
            {
            runAddStudent(inputName);
            } 
        } 
    }

function runAddStudent(studentID)
    {
    $.ajax({
        type: 'GET',
        url : 'addStudent.php',
        data:
            {
            id: studentID
            },
        success: function()
            {
            
            }
        });
    }
/*********
    Tag Handling
*********/

function addNewTag()
    {
    setEditData("T", "false", "true");
    }

function editTag(tagAbbr)
    {
    $.ajax({
        type: 'GET',
        url : 'grabTagData.php',
        data:
            {
            abbr: tagAbbr
            },
        success: function()
            {
            setEditData("T", "true", "true");
            }
        });
    }

/************
    Posting and Hiding Quiz
*************/

function makeVisible(tableID)
    {
    let text = "Are you sure you want to post this quiz?\nThis will make the quiz visible to all students.";
    if (confirm(text) == true) 
        {
        runPost(tableID);
        } 
    }

function makeHidden(tableID)
    {
    let text = "Are you sure you want to hide this quiz?\nThis will hide the quiz to all students.";
    if (confirm(text) == true) 
        {
        runHide(tableID);
        } 
    }

function runPost(tableName)
    {
    $.ajax({
        type: 'GET',
        url : 'postTable.php',
        data:
            {
            name: tableName,
            do: "post"
            },
        success: function()
            {
            location.reload();
            }
        });
    }

function runHide(tableName)
{
    $.ajax({
        type: 'GET',
        url : 'postTable.php',
        data:
            {
            name: tableName,
            do: "hide"
            },
        success: function()
            {
            location.reload();
            }
    });
}


/************
    Quiz creation and editing
*************/

function newQuiz()
    {
    let inputName = prompt("Please enter your quiz name:", "Quiz name");
    if (inputName != null && inputName != "") 
        {
        runCreateQuiz(inputName);
        } 
    }

function runCreateQuiz(newName)
    {
    $.ajax({
        type: 'GET',
        url : 'createQuiz.php',
        data : 
            {
            name: newName
            },
        success : function(send)
            {
            var response = JSON.parse(send);
            if(response == "taken")
                {
                alert("That quiz name already exists. Pick a different name or change the old one.");
                }
            else
                {
                location.reload();
                }
            }
        });
    }

function setQuiz(tableID)
    {

    $.ajax({
        type: 'GET',
        url : 'setData.php',
        data:
            {
            table: tableID
            },
        success: function()
            {
           window.location.href = "questionsPreview.php"; 
            },
        error : function()
            {
        alert("error");
            }
        });
    }

function editQuizName(oldName)
    {
    let inputName = prompt("Please enter your new quiz name:", "Quiz name");
    if (inputName != null && inputName != "") 
        {
        runEditQuiz(inputName, oldName);
        }
    }

function runEditQuiz(newName, oldName)
    {
    $.ajax({
        type: 'GET',
        url : 'editQuizName.php',
        data:
            {
            name: newName,
            old: oldName
            },
        success: function(send)
            {
            var response = JSON.parse(send);
            if(response == 'taken')
                {
                alert("That name is already taken");
                }
            else
                {
                setQuiz(oldName);
                }
            }
        });
    }

/**********
    Quiz and question deletion
**********/

function deleteTable(tableID)
    {
    let text = "Are you sure you want to delete this quiz?\nThis action cannot be undone.";
    if (confirm(text) == true) 
        {
        runDelete(tableID, " ");
        } 
    }

function deleteQuestion(questionID, tableName)
    {
    let text = "Are you sure you want to delete this question?\nThis action cannot be undone.";
    if (confirm(text) == true) 
        {
        runDelete(tableName, questionID);
        } 
    } 

function runDelete(tableName, questionID)
    {
    $.ajax({
        type: 'GET',
        url : 'deleteTable.php',
        data:
            {
            name: tableName,
            id: questionID
            },
        success: function()
            {
            location.reload();
            }
        });
    }

/**********
    Handling Questions
**********/

// add
function addQuestion()
    {
    quizName = document.getElementById("quizTitle").innerHTML; //Create seperate name
    question = document.getElementById("quizQuestion").value;
    answerPosition = answerPos;
    explanation = document.getElementById("quizExplanation").value;

    if(swap == 1)
        {
        tag = document.getElementById("tagSelect").value;
        }
    else
        {
        tagName = document.getElementById("newTag").value;
        tag = document.getElementById("newTagAbbr").value;
        if(tag == "")
            {
            alert("Must have tag abbreviation");
            }
        else
            {
            runAddTag(tagName, tag, " ", "false");
            }
        }

    if(tag != "")
        {
        runAddQuestion(quizName, question, answerPosition, explanation, tag);
        }
    
    }

function  runAddQuestion(tableName, theQuestion, position, theExplanation, tagAbbr)
    {
    $.ajax({
        type: 'GET',
        url : 'insertQuestions.php',
        data : 
            {
            name: tableName,
            question : theQuestion,
            answerPosition: position,
            explanation: theExplanation,
            tag: tagAbbr
            },
        success : function()
            {
            window.location.href = "questionsPreview.php"; 
            }
        });
    }


// edit
function editQuestion(questionID, tableName)
    {
    $.ajax({
        type: 'GET',
        url : 'grabQuestionData.php',
        data:
            {
            id: questionID,
            name: tableName
            },
        success: function()
            {
            setEditData("Q", "true", "true");
            }
        });
    }

function runEditQuestion(questionID, position)
    {
    quizName = document.getElementById("quizTitle").innerHTML;
    newQuestion = document.getElementById("quizQuestion").value;
    answerPosition = position;
    explanation = document.getElementById("quizExplanation").value;

    if(answerPos > -1)
        {
        answerPosition = answerPos;
        }

    if(swap == 1)
        {
        tag = document.getElementById("tagSelect").value;
        }
    else
        {
        tagName = document.getElementById("newTag").value;
        tag = document.getElementById("newTagAbbr").value;
        if(tag == "")
            { 
            alert("Must have tag abbreviation")
            }
        else
            {
            runAddTag(tagName, tag, " ", "false");
            }
        }
    if(tag != "")
        {
        $.ajax({
            type: 'GET',
            url : 'editQuestion.php',
            data:
                {
                name: quizName,
                id: questionID,
                question: newQuestion,
                position: answerPosition,
                explanation: explanation,
                tag: tag
                },
            success: function()
                {
                // LEFT BLANK ON PURPOSE
                }
            });
        
        setEditData("Q", "false", "false");
        }
    
    }


/**********
    Handling Tags
**********/

// Add
function addTag()
    {
    tagName = document.getElementById("tagname").value; //Create seperate name
    tagAbbr = document.getElementById("Abbr").value;
    tagDescr = document.getElementById("Desc").value;

    //Check answer position valid
    
    runAddTag(tagName, tagAbbr, tagDescr, "true");
        
    }

function runAddTag(tagName, tagAbbr, tagDescr, move)
    {
    $.ajax({
        type: 'GET',
        url : 'insertTag.php',
        data : 
            {
            name: tagName,
            abbr : tagAbbr,
            desc: tagDescr
            },
        success : function()
            {
            }
        });
    
    if(move == "true")
        {
        setEditData("T", "false", "false");
        }
    }


// Edit
function runEditTag(oldTagAbbr)
    {

    tagName = document.getElementById("tagname").value;
    tagAbbr = document.getElementById("Abbr").value;
    tagDesc = document.getElementById("Desc").value;

    $.ajax({
        type: 'GET',
        url : 'editTag.php',
        data:
            {
            name: tagName,
            abbr: tagAbbr,
            desc: tagDesc,
            old: oldTagAbbr
            },
        success: function(send)
            {
            var result = JSON.parse(send);
            if(result == 'taken')
                {
                alert("That abbreviation is already taken.");
                }
            }
        });
    setEditData("T", "false", "false");
    }

// Delete
function deleteTag(tagAbbr)
    {
    let text = "Are you sure you want to delete this tag?\nThis action cannot be undone.";
    if (confirm(text) == true) 
        {
        runDeleteTag(tagAbbr);
        } 
    }

function runDeleteTag(tagAbbr)
    {
    $.ajax({
        type: 'GET',
        url : 'deleteTag.php',
        data:
            {
            abbr: tagAbbr
            },
        success: function()
            {
            // LEFT BLANK ON PURPOSE

            }
        });
    setEditData("T", "false", "false");
    }

// include for rendering
function include(file) {
 
    let script = document.createElement('script');
    script.src = file;
    script.type = 'text/javascript';
    script.defer = true;
 
    document.getElementsByTagName('head').item(0).appendChild(script);
}

include('student.js');