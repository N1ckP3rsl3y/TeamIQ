<?php
    require_once("../generalFunctionality.php");

    $conn = connectToDatabase();

    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $abbr = $_GET['abbr'];
    $desc = mysqli_real_escape_string($conn, $_GET['desc']);

    if($abbr == "")
        {
        // generate temporary tag id
        $tempTagAbbr = "temp";
        $notFound = true;
        $index = 0;
        while($notFound)
            {
            $tempTagAbbr .= (string)($index);
            $sql = sprintf("SELECT * FROM `tagList` WHERE tag_abbr = '%s'", $tempTagAbbr);
            $res = mysqli_query($conn, $sql);
            if(mysqli_num_rows($res) == 0)
                {
                $notFound = false;
                }
            else
                {
                $tempTagAbbr = "temp";
                }
            $index++;
            }
        }
    else
        {
        $tempTagAbbr = $abbr;
        }

    $sql = sprintf("SELECT * FROM `tagList` WHERE tag_abbr = '%s'", $tempTagAbbr);
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) == 0)
        {
        // if tag abbr doesn't exist
        $sql = sprintf("INSERT INTO `tagList`(`tag_name`, `tag_abbr`, `tag_description`)
                    VALUES ('%s','%s','%s')", $name, $tempTagAbbr, $desc);
        }

    mysqli_query($conn, $sql);
    mysqli_close($conn);
?>
