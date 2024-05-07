<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = $_GET['name'];
    $id = $_GET['id'];
    $tagAbbr = $_GET['tag'];


    $currentSem = "students_";
    $currentSem .= getCurrentSemester($conn);


    $numNames = 0;
    $nameCount = 0;
    $noName = false;
    $packedData = 'username';

    // if not given a name, grab all quizzes
    if($name == " ")
        {
        $noName = true;

        $sql = "SELECT quiz_id FROM `quizzes` WHERE visible = 1";
        $fetchNames = mysqli_query($conn, $sql);
        while($namesRow = mysqli_fetch_array($fetchNames))
            {
            $nameArray[$numNames] = $namesRow['quiz_id'];
            $numNames++;
            }
        }

    // grab all columns that fit the criteria
    $acceptedCount = 0;
    do
        {
        if($noName)
            {
            $name = $nameArray[$nameCount];
            }

        
        $questionCount = 0;
        // if given a tag, only grab that tag
        if($tagAbbr != " ")
            {
            $sql = sprintf("SELECT id, tag FROM `%s` WHERE tag = '%s'", $name, $tagAbbr);
            $tempList = mysqli_query($conn, $sql);
            if(mysqli_num_rows($tempList) > 0)
                {
                $data[0]['tag0'] = "data";
                }

            while($Rrow = mysqli_fetch_array($tempList))
                {
                    $idList[$acceptedCount] = $Rrow['id'];
                    $tagList[$acceptedCount] = $Rrow['tag'];

                    $acceptedQuestion = $name;
                    $acceptedQuestion .= "_";
                    $acceptedQuestion .= $Rrow['id'];

                    $packedData .= ', ';
                    $packedData .= $acceptedQuestion;
                    
                    $acceptedCount++;
                    $questionCount++;
                }
            }
        // grab all
        else
            {
            $sql = sprintf("SELECT id, tag FROM `%s`", $name);
            $tempList = mysqli_query($conn, $sql);
            $data[0]['tag0'] = "data";
            while($Rrow = mysqli_fetch_array($tempList))
                {
                    $idList[$acceptedCount] = $Rrow['id'];
                    $tagList[$acceptedCount] = $Rrow['tag'];

                    $acceptedQuestion = $name;
                    $acceptedQuestion .= "_";
                    $acceptedQuestion .= $Rrow['id'];

                    $packedData .= ', ';
                    $packedData .= $acceptedQuestion;
                    
                    $acceptedCount++;
                    $questionCount++;
                }
            }

        $questionsPerQuiz[$nameCount] = $questionCount;
        $nameCount++;
        } while($nameCount < $numNames);


    $nameCount = 0;

    // get attempt data from students
    if($id == " ")
        {
        // get every student
        $sql = sprintf("SELECT %s FROM %s WHERE username LIKE '%%.1' ORDER BY username", $packedData, $currentSem);
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 0)
            {
            $data[0]['username'] = "nodata";
            echo json_encode($data);
            return;
            }
        }
    else
        {
        // get one student
        $sql = sprintf("SELECT %s FROM %s WHERE username LIKE '%s.1'", $packedData, $currentSem, $id);
        $result = mysqli_query($conn, $sql);

        // student not found
        if(mysqli_num_rows($result) == 0)
            {
            $data[0]['username'] = "notfound";
            echo json_encode($data);
            return;
            }

        }

    // set up file download
    $myfile = fopen("../files/studentResultData.csv", "w") or die("Unable to open file!");

    // format and correct data
    $studentCount = 0;
    while($row = mysqli_fetch_assoc($result))
        {
        $nameCount = 0;
        $data[$studentCount]['username'] = $row['username'];

        $savedCount = 1;
        do
            {
            $questionCount = 1;
            while($questionCount <= $questionsPerQuiz[$nameCount])
                {
                if($noName)
                    {
                    $name = $nameArray[$nameCount];
                    }

                $oldKey = $name;
                $oldKey .= '_';
                $oldKey .= $idList[$savedCount - 1];

                
                $newKey = 'question';
                $newKey .= $savedCount;
                $row[$oldKey] = abs($row[$oldKey]);

                if($row[$oldKey] > 1000)
                    {
                    $row[$oldKey] %= 1000;
                    }

                $data[$studentCount][$newKey] = $row[$oldKey];

                //set tags here
                $tagKey = 'tag';
                $tagKey .= $savedCount;

                $data[$studentCount][$tagKey] = $tagList[$savedCount - 1];

                $savedCount++;
                $questionCount++;
                }
            $nameCount++;
            } while ($nameCount < $numNames);

        $studentCount++;

        fputcsv($myfile, $row);
        }

    fclose($myfile);

    mysqli_close($conn);

    echo json_encode($data);

?>
