<?php

require_once 'includes/backend.php';

if($Airpic->session->sessionExists() && 
        $Airpic->session->user->isAdmin() &&
        isset($_GET['u']) && 
        is_numeric($_GET['u']))
{

    //check to see that all fields are set
    if(isset($_POST['positive']) && $_POST['positive'] == "on")
    {
        //update info
        $userid = $_GET['u'];
        if($Airpic->session->user->deleteUserID($userid))
            $output = "<div id=\"notification\"><p>Successfully deleted user</p>";
        else
            $output = "<div id=\"notification\"><p>Failed to deleted user</p>";
        $output .= "<p><a href=\"admin.php\">Go back to admin page</a></p></div>";
    }
    else
    {

    $userid = $_GET['u'];

    $user = new User($Airpic->db, $Airpic->settings, $Airpic->common);

    $user->loadUserByID($userid);

    $username = $user->getUserName();
    $fname = $user->getFirstName();
    $lname = $user->getLastName();
    $email = $user->getEmail();
    $isAdmin = $user->isAdmin();

    $javascript = "<script type=\"text/javascript\" src=\"validator.js\"></script>";
    $Airpic->page->setHeader($javascript);

    if(isset($update))
        $output= "<div id=\"notification\">$update</div>";
    else
        $output="";

$output.=<<<HTML


<div class="singleArea">
<h2>Delete Account</h2>
<form method="post" action="deleteUser.php?u=$userid" id="form">
    <table>
        <tr> <td class="label">Username:</td><td>$username</td> </tr>
        <tr> <td class="label">First Name:</td> <td>$fname</td> </tr>
        <tr> <td class="label">Last Name:</td> <td>$lname</td> </tr>
        <tr> <td class="label">Email:</td> <td>$email</td> </tr>
        <tr> <td class="label">Administrator: </td> <td>$isAdmin </td> </tr>
<tr><td></td></tr>
<tr>
<td><input type="checkbox" name="positive" /></td>
<td><span id="sure">Are you sure you want to delete $username?</span></td></tr>
<tr><td colspan="2">
    <input type="submit" id="submitButton" value="Delete" />
</td></tr>
        </table>
</form>
</div>
HTML;
    }

$Airpic->page->displayPage($output,"Account");
}
else
    $Airpic->common->redirect("index.php");

?>
