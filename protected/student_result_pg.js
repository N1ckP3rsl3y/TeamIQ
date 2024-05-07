
var barGraph = null;
let input = document.getElementById("studentName");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    setData();
  }
});

function setData()
{
    tableName = document.getElementById('quizSelect').value;
    tagName = document.getElementById('tagSelect').value;
    studentID = document.getElementById("studentName").value.toLowerCase();

    if(tagName != "" && tableName != "" && studentID != "")
      {
      // search all tags from table and student
      getData(tableName, studentID, tagName);
      }
    else if(tableName != "" && studentID != "")
      {    
      // search by student and quiz
      getData(tableName, studentID, " ");
      }
    else if(tableName != "" && tagName != "")
      {
      getData(tableName, " ", tagName);
      }
    else if(studentID != "" && tagName != "")
      {
      // search all tags one student
      getData(" ", studentID, tagName);
      }
    else if(studentID != "")
      {
      // search by studentID (Table view all quizzes)
      setStudent(studentID);

      }
    else if(tableName != "")
      {
      // search by quiz all students all tags
      getData(tableName, " ", " ");
      }
    else if(tagName != "")
      {
      // search by tags
      getData(" ", " ", tagName);
      }
}

function setStudent(studentID)
  {
    $.ajax({
      type: 'GET',
      url : 'setStudentData.php',
      data:
      {
          id: studentID
      },
      success: function()
      {
        window.location.href = "student_results_prof_view.php";     
      },
      error : function()
      {
      alert("error");
      }
  });
  }

function getData(tableName, studentID, tagName)
{
    
    $.ajax({
        type: 'GET',
        url : 'getResultData.php',
        data : 
          {
            name: tableName,
            id : studentID,
            tag: tagName
          },
        success : function(send)
          {
            var questionCount;
            var username;
            var temp = [];
            var students = [[]];
            var questionData;
            var questionTitle = [];
            var backgroundColors = "rgba(59, 89, 152, 0.5)";
            var dataset;

            if(barGraph != null)
            {
              barGraph.destroy();
            }
          
            var response = JSON.parse(send);

            if(response[0].username == "notfound")
              {
              document.getElementById("download-button").style.display = "none";
              document.getElementById("error-print").innerHTML = studentID + " not found";
              return;
              }
            else if(response[0].username == "nodata")
              {
              document.getElementById("download-button").style.display = "none";
              document.getElementById("error-print").innerHTML = "No student data found. Have students been added to this semester?";
              }

            document.getElementById("download-button").style.display = "initial";
            document.getElementById("error-print").innerHTML = "";
            
            var length = response.length;
            for(var i = 0; i < length; i++) 
            {
              questionCount = 0;
              if(response[i].question1 != undefined)
                {
                temp.push(response[i].question1); 
                if(i == 0) questionTitle.push(response[i].tag1);
                questionCount++;
                }
              if(response[i].question2 != undefined)
                {
                temp.push(response[i].question2); 
                if(i == 0) questionTitle.push(response[i].tag2);
                questionCount++;
                }
              if(response[i].question3 != undefined)
                {
                temp.push(response[i].question3); 
                if(i == 0) questionTitle.push(response[i].tag3);
                questionCount++; 
                }
              if(response[i].question4 != undefined)
                {
                temp.push(response[i].question4); 
                if(i == 0) questionTitle.push(response[i].tag4);
                questionCount++;
                }
              if(response[i].question5 != undefined)
                {
                temp.push(response[i].question5); 
                if(i == 0) questionTitle.push(response[i].tag5);
                questionCount++;
                }
              if(response[i].question6 != undefined)
                {
                temp.push(response[i].question6); 
                if(i == 0) questionTitle.push(response[i].tag6);
                questionCount++;
                }
              if(response[i].question7 != undefined)
                {
                temp.push(response[i].question7); 
                if(i == 0) questionTitle.push(response[i].tag7);
                questionCount++;
                }
              if(response[i].question8 != undefined)
                {
                temp.push(response[i].question8); 
                if(i == 0) questionTitle.push(response[i].tag8);
                questionCount++;
                }
              if(response[i].question9 != undefined)
                {
                temp.push(response[i].question9); 
                if(i == 0) questionTitle.push(response[i].tag9);
                questionCount++;
                }
              if(response[i].question10 != undefined)
                {
                temp.push(response[i].question10); 
                if(i == 0) questionTitle.push(response[i].tag10);
                questionCount++;
                }
              if(response[i].question11 != undefined)
                {
                temp.push(response[i].question11); 
                if(i == 0) questionTitle.push(response[i].tag11);
                questionCount++;
                }
              if(response[i].question12 != undefined)
                {
                temp.push(response[i].question12); 
                if(i == 0) questionTitle.push(response[i].tag12);
                questionCount++;
                }
              if(response[i].question13 != undefined)
                {
                temp.push(response[i].question13); 
                if(i == 0) questionTitle.push(response[i].tag13);
                questionCount++;
                }
              if(response[i].question14 != undefined)
                {
                temp.push(response[i].question14); 
                if(i == 0) questionTitle.push(response[i].tag14);
                questionCount++;
                }
              if(response[i].question15 != undefined)
                {
                temp.push(response[i].question15); 
                if(i == 0) questionTitle.push(response[i].tag15);
                questionCount++;
                }
              
              students.push(temp);
              temp = [];
            }
            
            
              // average out data
              for(var k = 0; k < questionCount; k++)
                {
                temp[k] = 0;
                if(studentID == " ")
                  {
                  
                  username = "Average Data";

                  for(var j = 1; j <= length; j++)
                    {
                    temp[k] += students[j][k];
                    }
                  temp[k] /= length;
                  }
                else
                  {
                  username = studentID;
                  temp[k] += students[1][k];
                  }
                }

            if(response[0].tag0 != "data")
              {
              username = "Tag Not Live";
              }
            questionData = temp;
            
            dataset = 
            {
              label: username,
              fill: false,
              backgroundColor: backgroundColors,
              data: questionData
            };

            var ctx = document.getElementById('canvas');
            var chartdata = 
            {
              labels: questionTitle,
              datasets: [dataset]
            };
      
            barGraph = new Chart(ctx, 
              {
              type: 'bar',
              data: chartdata,
              options: 
              {
                title: 
                {
                  display: true,
                  text: 'Student Display'
                },
                scales: 
                {
                  xAxes: 
                  [{
                    stacked: true
                  }],
                  yAxes: 
                  [{
                    ticks: 
                    {
                      beginAtZero: true
                    }
                  }]
                }
                
              }
              });
          },
        error : function(response)
          {
        document.getElementById("error-print").innerHTML = "error" + response;
          }
    });
}

