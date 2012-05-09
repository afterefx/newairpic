<?php

require_once 'includes/backend.php';

if($Airpic->session->sessionExists() && 
        $Airpic->session->user->isAdmin() &&
        isset($_GET['u']) && 
        is_numeric($_GET['u']))
{

    //check to see that all fields are set
    if(isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) )
    {
        //check if oldpw is set and new pw
        if(isset($_POST['password']))
            $password = NULL;
        else
            $password = $_POST['password'];

        //update info
        $userid = $_GET['u'];
        $username = $_POST['username'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = $_POST['email'];
        $isAdmin = (isset($_POST['isAdmin']) && $_POST['isAdmin'] == "on") ? 1 : 0;

        if($Airpic->session->user->adminUpdateUser($userid, $username, $password, $fname, $lname, $email, $isAdmin))
            $update = "Successfully updated information";
        else
            $update = "Failed to update information";
    }
    elseif(isset($_POST['username']) || isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['email']) )
        $update = "Username, First name, Last name, and Email must be set";


    $userid = $_GET['u'];

    $user = new User($Airpic->db, $Airpic->settings, $Airpic->common);

    $user->loadUserByID($userid);

    $username = $user->getUserName();
    $fname = $user->getFirstName();
    $lname = $user->getLastName();
    $email = $user->getEmail();
    $checked = ($user->isAdmin()) ? "checked=\"checked\" ":"";

    $javascript = "<script type=\"text/javascript\" src=\"validator.js\"></script>";
    $Airpic->page->setHeader($javascript);

    if(isset($update))
        $output= "<div id=\"notification\">$update</div>";
    else
        $output="";

$output.=<<<HTML


<div class="singleArea">
<h2>Manage Account</h2>
<form method="post" action="editUser.php?u=$userid" id="form">
    <table>
        <tr>
            <td class="label">Username:</td><td><input type="text" name="username" value="$username" onchange="checkForUsername(this.value)"  onkeyup="loading(this)" /></td>
            <td><span id="availability"></span></td>
        </tr>
        <tr>
            <td class="label">Password:</td>
            <td><input type="text" id="password" name="password" /></td>
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
        <tr>
            <td class="label">Administrator: </td>
            <td><input type="checkbox" id="isAdmin" name="isAdmin" $checked /></td>
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
