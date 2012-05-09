<?php

require_once 'includes/backend.php';

//check to see if there is a session
if($Airpic->session->sessionStart())
        $Airpic->session->logout(); //logout the session
$Airpic->common->redirect("index.php"); //redirect the user back to the index page

?>
