<?php
    require_once("userAuthentication.php");
    userAuthenticator();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../styles.css">
        <script defer src="handle_quizzes.js"></script>
        <script src="https://code.jquery.com/jquery-latest.js"></script>
        <title>Red Pen</title>
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
                    <li><a href="tags.php">Back</a></li>
               </ul>
         </nav>
         </header>

         <!-- Main Section -->
         <main>
            <div id= "remindersContainer">

                <h1>Create a new tag</h1>

                <form>
                    <?php
                    require_once("../generalFunctionality.php");

                    $conn = connectToDatabase();

                    if(!$conn)
                    {
                        echo "Something went wrong when connecting to the database";
                    }

                    session_start();
                    if($_SESSION['tagEdit'] == "true")
                        {
                        $editName = $_SESSION['name'];
                        $editAbbr = $_SESSION['abbr'];
                        $editDesc = $_SESSION['description'];
                        }
                    else
                        {
                        $editName = "";
                        $editAbbr = "";
                        $editDesc = "";
                        }



                    echo "<label for='TagName'>Tag Name:</label><br>";
                    echo "<textarea type='text' id='tagname' name='tagname'>".$editName."</textarea><br><br>";

                    echo "<label for='Abbr'>Abbreviation:</label><br>";
                    echo "<input type='text' id='Abbr' name='Abbr' value='".$editAbbr."'><br><br>";

                    echo "<label for='Desc'>Description:</label><br>";
                    echo "<textarea id='Desc' name='Desc' rows='4' cols='40'>".$editDesc."</textarea><br><br>";

                    if($_SESSION['tagEdit'] == "true")
                        {
                        echo "<button onclick='runEditTag(".json_encode($editAbbr).")'>Update</button>";
                        echo "<button onclick='deleteTag(".json_encode($editAbbr).")'>Delete</button>";
                        }
                    else
                        {
                        echo "<button onclick='addTag()'>Create</button>";
                        }

                    ?>
                </form>


            </div>
        </main>

         <!-- Footer Section -->
         <footer>
               <p>Red Pen</p>
               <p>&copy; 2024</p>
         </footer>


    </body>
</html>
