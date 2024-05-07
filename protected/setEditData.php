<?php

session_start();
$char = $_GET['char'];
if($char == "Q")
    {
    $_SESSION['editing'] = $_GET['edit'];
    }
else if($char == "T")
    {
    $_SESSION['tagEdit'] = $_GET['edit'];
    }

?>