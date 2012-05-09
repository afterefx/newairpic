<?php
require_once '../includes/backend.php';

sleep(1); //let the loading gif animation show for a second :)
$q = $_POST["q"];


//////////////////////////////////////////
//check the string for invalid characters
//////////////////////////////////////////

$pattern='/^[A-Za-z0-9]+$/';//only alphanumeric characters are allowed in the username

if(!preg_match($pattern, $q))
{
    echo "<img src=\"images/x.png\" /> The username $q contains invalid characters.";
    return;
}

//////////////////////////////////////////
//check the length of the username
//////////////////////////////////////////
if(strlen($q) < 3)
{
    echo "<img src=\"images/x.png\" /> The username $q is too short.";
    return;
}
elseif(strlen($q) > 18)
{
    echo "<img src=\"images/x.png\" /> The username $q is too long.";
    return;
}

//////////////////////////////////////////
// query to check for the username in both
// users and userRegistration
//////////////////////////////////////////
//check the users table
$usersResult = $Airpic->session->user->checkForUser($q);

//check the user registration table
$registrationResult = $Airpic->userRegistration->checkForUser($q);

if($registrationResult || $usersResult )
    echo "<img src=\"images/x.png\" /> The username $q is not available.";
else
    echo "<img src=\"images/check.png\" /> Username is available.";
?>
