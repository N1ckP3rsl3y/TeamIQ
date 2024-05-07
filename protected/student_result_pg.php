<?php
    require_once("userAuthentication.php");
    userAuthenticator();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../professor.css">
        <link rel="stylesheet" href="../styles.css">
        <title>Red Pen</title>
        <script defer src="student_result_pg.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    </head>
    <body>
         <!-- Header Section -->
         <header class="fixed-header">
         <a href="professor.php">
               <img src="../photos/nau.png" alt="Logo" width="200" height="125">
         </a>
         <img src="../photos/Red Pen.png" alt="Logo" width="425" height="125">
         <nav>
               <ul>
                    <li><a href="professor.php">Home</a></li>
                    <li><a href="professor_quiz_list.php">View Quizzes</a></li>
                    <!-- <li><a href="quizEditor.php">Quiz Editor</a></li> -->
                    <li><a href="professor_page.html">Professor Page</a></li>
                    <li><a href="professor_page.html">Back</a></li>
               </ul>
         </nav>
         </header>

         <!-- Main Section -->
         <main>
            <div class="container">
                <div class="header">

                    <div class="title-box">Student Results Page</div>
                </div>
                <div class="content">
                    <div class="left-box">
                        <div class="by-student">
                            <label for="studentName">By Student:</label>
                            <input type="text" id="studentName" placeholder="Enter student ID">
                        </div>
                    </div>

                    <div class="middle-box">
                        <div class="by-quiz">
                            <label for="quizSelect">By Quiz:</label>
                            <select id="quizSelect">
                                <option value="" selected disabled>Select Quiz</option>
                                <!-- Add your quiz options here -->
                                <?php
                                require_once("../generalFunctionality.php");

                                $conn = connectToDatabase();

                                if(!$conn)
                                {
                                    echo "Something went wrong when connecting to the database";
                                }

                                $sql = "SELECT * FROM quizzes where visible = 1";

                                $res = mysqli_query($conn, $sql);

                                while($row = mysqli_fetch_assoc($res))
                                {
                                    echo "<option value='".$row['quiz_id']."'>" .$row['quiz_name']. "</option>";
                                }

                                mysqli_close($conn);
                                ?>
                                <option value=""> Show all </option>
                            </select>
                        </div>
                        <div class="by-tag">
                            <label for="tagSelect">By Tag:</label>
                            <select id="tagSelect">
                                <option value="" selected disabled>Select Tag</option>
                                <!-- Add your quiz options here -->
                                <?php
                                require_once("../generalFunctionality.php");

                                $conn = connectToDatabase();

                                if(!$conn)
                                {
                                    echo "Something went wrong when connecting to the database";
                                }

                                $sql = "SELECT * FROM tagList;";

                                $res = mysqli_query($conn, $sql);

                                while($row = mysqli_fetch_assoc($res))
                                {
                                    echo "<option value='".$row['tag_abbr']."'>" .$row['tag_name']. "</option>";
                                }

                                mysqli_close($conn);
                                ?>
                                <option value=""> Show all </option>
                            </select>
                        </div>
                    </div>
                    <div class="right-box">
                        <button class="go-button" onclick="setData()">Go</button>
                        <div class="download-section">
                            <a href="../files/studentResultData.csv" download id="download-button" class="download-button">
                                Download
                            <a>
                        </div>
                    </div>
                    <div class="error-section">
                        <span id="error-print"></span>
                    </div>
                </div>

                <div class="chart_container">
                    <canvas id="canvas"> </canvas>
                </div>
            </div>
        </main>

         <!-- Footer Section -->
         <footer>
               <p>Red Pen</p>
               <p>&copy; 2024</p>
         </footer>


    </body>
</html>
