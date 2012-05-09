<?php

require_once 'includes/backend.php';

if($Airpic->session->sessionExists())
{
    if(!$Airpic->session->user->isAdmin())
        $Airpic->common->redirect("index.php");

    if(isset($_POST["submit"]))
    {
        if($_POST["submit"] == "Edit")
            $Airpic->common->redirect("editUser.php?u=" . $_POST["user"]);
        else
            $Airpic->common->redirect("deleteUser.php?u=" . $_POST["user"]);
    }

if(isset($_GET['key']))
{
    $token = $_GET['key'];
    $result = $Airpic->session->logoutSession($token);
}

$output=<<<HTML
    <div class="singleArea">
    <form action="admin.php" method="post">
        <table id="session">
        <tr><th><nobr>Manage Sessions</nobr></th></tr>
        <tr><th>Username</th><th>IP Address</th><th>Date Created</th><th>Last Seen</th></tr>
HTML;

$sessions = $Airpic->session->getAllActiveSessions();
foreach( $sessions as $session)
{
    $username = $session->getUserName();
    $ip = $session->getIpAddress();
    $created = date('n/w/y  g:i:s T',$session->getDateCreated());
    $lastseen = date('n/w/y g:i:s T',$session->getLastSeen());
    $sessionkey = $session->getSessionKey();

    $output.=<<<HTML
        <tr>
        <td>$username</td>
        <td>$ip</td>
        <td>$created</td>
        <td>$lastseen</td>
        <td><a href="admin.php?key=$sessionkey">Logout</a></td>
        </tr>
HTML;
}

$output.=<<<HTML
        <tr>&nbsp;</tr>
        <tr>&nbsp;</tr>
        <tr>&nbsp;</tr>
        <tr>&nbsp;</tr>
        <tr><th>Manage Users</th></tr>
        <tr><td><a href="addUser.php">Create New User</a></td></tr>
        <tr><td>
        <select name="user">
            <option>---</option>
HTML;

$usernames = $Airpic->session->user->getAllUserNames();
foreach($usernames as $aName)
{
    $obj = new User($Airpic->db, $Airpic->settings, $Airpic->common);
    $obj->loadUserByUserName($aName);
    $userid = $obj->getUserID();
    $output.="<option value=\"$userid\">$aName</option>";


}
$output.=<<<HTML
        </select>
        </td><td colspan="2"><input type="submit" name="submit" value="Edit" />
        <input type="submit" name="submit" value="Delete" /></td></tr>
        <tr>&nbsp;</tr>
        <tr>&nbsp;</tr>
        <tr>&nbsp;</tr>
        <tr>&nbsp;</tr>
        <tr>&nbsp;</tr>
        <tr><th><a href="settings.php">Site Settings</a></th></tr>
        </table>
        </table>

        </form>
    </div>
HTML;

$Airpic->page->displayPage($output, "Admin");
}
else
    $Airpic->common->redirect("index.php");

?>
