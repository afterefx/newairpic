<?php

require_once 'includes/backend.php';

if($Airpic->session->sessionExists() && $Airpic->session->user->isAdmin())
{


    //check to see that all fields are set
    if(isset($_POST['username']) && isset($_POST['firstname']) &&
            isset($_POST['lastname']) && isset($_POST['email']) &&
            isset($_POST['password']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = $_POST['email'];
        $isAdmin= (isset($_POST['isAdmin']) && $_POST['isAdmin'] =="on")? 1 : 0;

        if($Airpic->userRegistration->createUserNow($username, $password,
                    $email, $fname, $lname, $isAdmin))
            $update = "Successfully created new user";
        else
            $update = "Failed to create new user";
    }
    elseif(isset($_POST['username']) || isset($_POST['firstname']) ||
            isset($_POST['lastname']) || isset($_POST['email']) ||
            isset($_POST['password']))
        $update = "Username, First name, Last name, and Email must be set";




    $javascript = "<script type=\"text/javascript\" src=\"validator.js\"></script>";
    $Airpic->page->setHeader($javascript);

    if(isset($update))
        $output= "<div id=\"notification\">$update</div>";
    else
        $output="";

$output.=<<<HTML


<div class="singleArea">
<h2>Create New Account</h2>
<form method="post" action="addUser.php" id="form">
    <table>
        <tr>
            <td class="label">Username:</td><td><input type="text" name="username"  onchange="checkForUsername(this.value)"  onkeyup="loading(this)" /></td>
            <td><span id="availability"></span></td>
        </tr>
        <tr>
            <td class="label">Password:</td>
            <td><input type="text" id="password" name="password" /></td>
        </tr>
        <tr>
            <td class="label">First Name:</td>
            <td><input type="text" id="firstname" name="firstname" onchange="checkFirstName()" /></td>
            <td><span id="validFirst" /></td>
        </tr>
        <tr>
            <td class="label">Last Name:</td>
            <td><input type="text" id="lastname" name="lastname" onchange="checkLastName()" /></td>
            <td><span id="validLast" /></td>
        </tr>
        <tr>
            <td class="label">Email:</td>
            <td><input type="text" id="email" name="email" onchange="checkEmail()" /></td>
            <td><span id="validEmail" /></td>
        </tr>
        <tr>
            <td class="label">Administrator: </td>
            <td><input type="checkbox" id="isAdmin" name="isAdmin" /></td>
        </tr>
<tr><td></td></tr>
<tr><td colspan="2">
    <input type="submit" id="submitButton" value="Create" />
</td></tr>
        </table>
</form>
</div>
HTML;

$Airpic->page->displayPage($output,"Account");
}
else
    $Airpic->common->redirect("index.php");

?>
