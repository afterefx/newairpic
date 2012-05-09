<?php

require_once 'includes/backend.php';

if($Airpic->session->sessionExists())
{

    //check to see that all fields are set
    if(isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) )
    {
        //check if oldpw is set and new pw
        if(!isset($_POST['oldpassword']) || !isset($_POST['password']))
            $oldpass = $password = NULL;
        else
        {
            $oldpass = $_POST['oldpassword'];
            $password = $_POST['password'];
        }
        //update info
        $userid = $Airpic->session->user->getUserID();
        $username = $_POST['username'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = $_POST['email'];

        if($Airpic->session->user->updateUser($userid, $username, $oldpass, $password, $fname, $lname, $email))
            $update = "Successfully updated information";
        else
            $update = "Failed to update information";
    }
    elseif(isset($_POST['username']) || isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['email']) )
        $update = "Username, First name, Last name, and Email must be set";




    $username = $Airpic->session->getUserName();
    $fname = $Airpic->session->user->getFirstName();
    $lname = $Airpic->session->user->getLastName();
    $email = $Airpic->session->user->getEmail();

    $javascript = "<script type=\"text/javascript\" src=\"validator.js\"></script>";
    $Airpic->page->setHeader($javascript);

    if(isset($update))
        $output= "<div id=\"notification\">$update</div>";
    else
        $output="";

$output.=<<<HTML


<div class="singleArea">
<h2>Manage Account</h2>
<form method="post" action="account.php" id="form">
    <table>
        <tr>
            <td class="label">Username:</td><td>$username<input type="hidden" name="username" value="$username" /></td>
            <td><span id="availability"></span></td>
        </tr>
        <tr>
            <td class="label">Old Password:</td>
            <td><input type="password" id="oldPassword" name="oldpassword" /></td>
        </tr>
        <tr>
            <td class="label">Password:</td>
            <td><input type="password" id="password" name="password" /></td>
            <td><span id="passMatch"></span></td>
        </tr>
        <tr>
            <td class="label">Confirm:</td>
            <td><input type="password" id="confirmPass" name="confirmPass" onchange="checkPassMatch()" /></td>
        </tr>
        <tr>
            <td class="label">First Name:</td>
            <td><input type="text" id="firstname" name="firstname" value="$fname" onchange="checkFirstName()" /></td>
            <td><span id="validFirst" /></td>
        </tr>
        <tr>
            <td class="label">Last Name:</td>
            <td><input type="text" id="lastname" name="lastname" value="$lname" onchange="checkLastName()" /></td>
            <td><span id="validLast" /></td>
        </tr>
        <tr>
            <td class="label">Email:</td>
            <td><input type="text" id="email" name="email" value="$email" onchange="checkEmail()" /></td>
            <td><span id="validEmail" /></td>
        </tr>
<tr><td></td></tr>
<tr><td colspan="2">
    <input type="submit" id="submitButton" value="Update" />
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
