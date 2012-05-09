<?php
require_once "includes/backend.php";

if($Airpic->session->sessionExists())
    $Airpic->common->redirect("http://airpic.org/gallery");
else
{
if (isset($_POST["user"]) && isset($_POST["pass"]))
{
    $userpassword = $_POST["pass"];

    if(isset($_GET['page']) && !empty($_GET['page']))
        $redirect = $_GET['page'];
    else
        $redirect = "gallery.php";

    if(isset($_POST["remember"]) && $_POST["remember"] == "true")
        $result = $Airpic->session->loginRedirect($_POST["user"], $userpassword, $redirect, true);
    else
        $result = $Airpic->session->loginRedirect($_POST["user"], $userpassword, $redirect, false);

    $notification = ($result) ? "<p>You should be redirected to the index page. You may
        click <a href=\"index.php\">here</a> if you would like to skip
        ahead.</p>":"<p>Login failure. <br />Please make sure your username and password are
        typed correctly. Also check your caps lock key.</p>";


}
echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
<title>Airpic</title>
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="http://fonts.googleapis.com/css?family=Homemade+Apple:regular" rel="stylesheet" type="text/css" />
<link href="main.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/png" href="static/favicon.png" />
<script type="text/javascript" src="loginMenu.js"></script>
</head>

<body>
<div id="frontImage">
<img src="static/wingsL.png" alt="Airpic logo"/>
</div>
HTML;
if(isset($notification))
    echo "<div id=\"notification\">$notification</div>";
    echo<<<HTML
<div id="frontLogin">
<form method="post" action="index.php">
    <p>
                username <br />
                <input type="text" name="user" /><br />
                password <br />
                <input type="password" name="pass" /> <br />
                <input type="checkbox" name="remember" /> remember me <br />
                <input type="submit" value="login" />
    </p>
</form>
</div>
</body>
</html>
HTML;
}

?>
