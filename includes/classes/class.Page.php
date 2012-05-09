<?php
require_once 'class.Strings.php';

/**
 * This is the main object that contains the backend
 *
 * @author Christopher Carlisle
 */
require_once('class.Airpic.php');

/* user defined includes */

/* user defined constants */

/**
 * Generates the web page for the device and loads appropriate stylesheet
 *
 * @access public
 * @author Christophoer Carlisle, <ccarlisle1@islander.tamucc.edu>
 */
class Page
{
    // --- ATTRIBUTES ---
    private $notification;
    private $extraHeaderItems;
    private $session;
    private $footText;
    private $headText;
    private $pageText;
    private $sideLinks;

    // --- OPERATIONS ---

    public function __construct($_session)
    { $this->session = $_session; }

    private function getNotification()
    { return $this->notification; }

    public function getSideLinks()
    {}

    private function getTitle()
    {
        $titleString = new Settings();
        return $titleString->getTitle();
    }

    private function getSiteTitle()
    {
        $siteTitleString = new Settings();
        return $siteTitleString->getSiteTitle();
    }

    private function getSiteSlogan()
    {
        $sloganString= new Settings();
        return $sloganString->getSlogan();
    }

    private function getExtraHeader()
    { return $this->extraHeaderItems; }

    private function getHeader()
    {//{{{2
        $header =<<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
<head>
            <title>
HTML;

        $header.= $this->getTitle();//Get the title for the current page

        $header.=<<<HTML
            </title>
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link href="http://fonts.googleapis.com/css?family=Homemade+Apple:regular" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="accountMenu.js"></script>
<link href="main.css" rel="stylesheet" type="text/css" media="screen" />
HTML;

        $header .= $this->getExtraHeader();

        $header.="</head>";

        return $header;

    }//}}}2

    private function getMenuItems($title)
    {//{{{2

        $fname = $this->session->user->getFirstName();

        $page=<<<HTML
        <a href="gallery">gallery</a>
        <a href="upload.php">upload</a>
        <a id="login" onclick="toggleLogin()">$fname</a>
HTML;
        //check for an administrator to decide whether or not to give the
        //administrator page
        if($this->session->user->isAdmin())
            $page.="<a href=\"admin.php\"> admin</a>";
        return $page;
    }//}}}2

    /**
     * Returns content for footer to be displayed on the webpage. Grabs content
     * footer from the strings table. It also grabs any additional content that
     * to be inserted into the footer before it is delivered to the webpage.
     *
     * @access private
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return mixed
     */
    private function getFooter()
    {//{{{2
        return <<<HTML
HTML;
    }//}}}2

    /**
     * Returns the html for the login area
     *
     * @access private
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @return mixed
     */
    private function getLoginArea()
    {//{{{2
        //if a session exists then tell the user Welcome in the login area
        //and give them a login area
        if($this->session->sessionExists())
        {
            $name = $this->session->user->getFirstName();
            $page=<<<HTML
                <h3>Welcome</h3>
                Hello <a href="profile.php">$name</a><br />
                <a href="logout.php">logout</a>
HTML;

        }
        //if a session does not exist then show the login area
        else
        {
            $server = $_SERVER['PHP_SELF'];
            $page=<<<HTML
                <h3>Login or <a href="register.php">register</a></h3>
                <form action="login.php?page=$server" method="post">
                Username:<br />
                <input type="text" name="user" /><br />
                Password:<br />
                <input type="password" name="pass" /><br />
                <input type="checkbox" name="remember" />Remember me
                <span id="loginButton"><input type="submit" value="Login" /></span>
                </form>
                <a href="#">Forgot password...</a>
HTML;
        }
        return $page; //return whatever was put into the page
    }//}}}2

    //{{{1 setters
    /**
     * Receives text and appends it to the footer text (footText) of this
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  content
     * @return mixed
     */
    public function setFooter($content)
    {
    }

    /**
     * Receives text and appends it to the header text (headText) of this
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  text
     * @return mixed
     */
    public function setHeader($text)
    { 
        if(isset($this->extraHeaderItems))
            $this->extraHeaderItems.=$text; 
        else
            $this->extraHeaderItems=$text; 
    }

    /**
     * Receives text and appends it to the notification text (notificationText)
     * this instance.
     *
     * @access public
     * @author Christopher Carlisle, <ccarlisle1@kestrel.tamucc.edu>
     * @param  Text to be appended to page's current notification text (notificationText)
     */
    public function setNotification($text)
    {
        if(isset($this->notification))
            $this->notification .= "<br />" . $text;
        else
            $this->notification =  $text;

    }

    public function setSideLink($sideLink)
    {
        if(!isset($this->sideLinks))
            $this->sideLinks = array(); 
        array_push($this->sideLinks, $sideLink);
    }

    /**
     * Constructs a webpage for the appropriate device
     *
     * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
     * @param  page, title
     */
    public function displayPage($page, $title)
    {//{{{2

        //get the top part of the page, aka the headers for the webpage
        $echo = $this->getHeader();

        //--- Top portion of page

        //visible part of the webpage starts here
        $echo .=<<<HTML

            <body>

<div id="header">
    <div id="title">
        <p id="left">
HTML;
        //get the icons for the top right of the page

        //insert the site title
     $echo.= "<a href=\"http://airpic.org/\">" . $this->getSiteTitle() .  "</a>"; 
     $echo.= "</p>";

        //---Menu starts here ----

        //end the top portion of the webpage and start creating the menu
        $echo .= <<<HTML
            <p id="right">
HTML;

        $echo .= $this->getMenuItems($title);

        $echo .=<<<HTML
            </p>
            </div>
            </div>
HTML;

        $echo .= $page;

        //not needed atm
        //$echo .= $this->getLoginArea();

        $echo .=<<<HTML
            <div id="accountMenu">
                <a href="account.php">Account Settings</a><br />
                <a href="logout.php">Logout</a><br />
            </div>
            </body>
            </html>
HTML;
        echo $echo;
    }//}}}2



} /* end of class Page */

?>
