<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $abbr = mysqli_real_escape_string($conn, $_GET['abbr']);
    $desc = mysqli_real_escape_string($conn, $_GET['desc']);
    $oldAbbr = mysqli_real_escape_string($conn, $_GET['old']);

    // update tagList table with new data
    $sqlSend = sprintf("UPDATE `tagList` SET `tag_name`='%s',`tag_abbr`='%s',`tag_description`='%s' WHERE tag_abbr='%s'",
            $name, $abbr, $desc, $oldAbbr);


    // if old tag abbr is same as new update regardless
    if($oldAbbr == $abbr)
        {
        mysqli_query($conn, $sqlSend);
        }
    else
        {
        // check that new abbriviation is unique
        $sql = sprintf("SELECT tag_abbr FROM `tagList`");
        $res = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($res))
            {
            if($abbr == $row['tag_abbr'])
                {
                $send = json_encode('taken');
                echo $send;
                return;
                }
            }
        mysqli_query($conn, $sqlSend);

        // update abbr in every quiz
        $sql = sprintf("SELECT quiz_id FROM `quizzes`");
        $res = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($res))
            {
            $sql = sprintf("UPDATE `%s` SET `tag`='%s' WHERE tag = '%s'", $row['quiz_id'], $abbr, $oldAbbr);
            mysqli_query($conn, $sql);
            }

        }

$send = json_encode('return');
echo $send;
mysqli_close($conn);
?>
