<?php
require_once("../generalFunctionality.php");

$rendering = $_GET['string'];

$rendered = renderString($rendering);

$questionHTML = "";
$questionHTML .= "<form method='POST-REDIRECT-GET' class='answerpos'";
$questionHTML .= sprintf("<div id='question'>\n");
$questionHTML .= $rendered . "\n<br>";
$questionHTML .= "</div>\n";
$questionHTML .= "</form>\n";

$questionHTML .= "<div id='buttonArray'>";
$questionHTML .= "</div>";

echo json_encode($questionHTML);
?>
