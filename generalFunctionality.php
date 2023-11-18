<?php
    function connectToDatabase() {
        $server = "mysql.ac.nau.edu";
        $database = "redpenweb";
        $user = "redpenweb";
        $password = "teamiq2023!?";

        return mysqli_connect($server, $user, $password, $database);
    }

    function renderString($string)
    {
        $delim = " ";
        $indivWords = explode($delim, $string);

        foreach($indivWords as $word)
        {
            echo "<a class='wordChoice'>" . $word . "</a>\n";
            echo " ";
        }
    }
?>
