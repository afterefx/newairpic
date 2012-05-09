<?php

ini_set("display_errors", 1);//display any errors that come up
require_once "classes/class.Airpic.php"; //include the backend classes

$Airpic = new Airpic(); //start the airpic backend

$Airpic->session->sessionStart(); //start a session

?>
