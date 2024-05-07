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
                    <li><a href="professor_page.html">Back</a></li>
               </ul>
         </nav>
         </header>

         <!-- Main Section -->
         <main>
            <div id= "tagPageContainer">

                <a href="tags_create.php">
                <button id="plus" onclick="addNewTag()">
                    <h3>➕</h3>
                </button>
                </a>
                <br>
                <br>
                <br>
                <br>


                <h1>Tags</h1>
                    <div id= "main_tag_container">
                    <!-- Placeholders -->
                    <?php
                        require_once("../generalFunctionality.php");

                        $conn = connectToDatabase();

                        if(!$conn)
                        {
                            echo "Something went wrong when connecting to the database";
                        }

                        $sql = sprintf("SELECT * FROM `tagList` ORDER BY `tag_name`");

                        $res = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($res))
                        {
                        echo "<button class='tag_card' onclick='editTag(".json_encode($row['tag_abbr']).")'>";

                            echo "<div class='tag_container'>";
                                echo "<p>".$row['tag_abbr']."</p> ";
                                echo "<h4><b>Name: ".$row['tag_name']."</b></h4> ";
                                echo "<hr>".$row['tag_description']."</p> ";

                            echo "</div>";



                        echo "</button>";
                        }

                    ?>
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
