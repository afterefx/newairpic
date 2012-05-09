<?php

/**
 * Airpic - class.Session.php
 *
 *
 * This file is part of Airpic.
 *
 *
 */

/* user defined includes
 * need to include a user class and acl class when its created */

/**

 *Description
 *   This handles everything that has to do with the sessions.
 *
 * - Functions
 * constructure (done)
 * User Functions
 * login(username,password,remember)(done)
 * loginRedirect(username,password,redirect,remember)
 * logout() (done)
 * sessionStart() (done)
 * check if session exists (done)
 * createSession(username,time,remember) (done)
 * redirectToLogin()(done)
 *
 * //////////////////////////
 * - Admin functions
 * getSessions()   (done)
 * getUserName()   (done)
 * getSessionKey() (done)
 * getIP()         (done)
 * getLastSeen()   (done)
 * getDateCreated()(done)
 * logOutPast      (done)
 * logOutUser      (done)
 * sessionExists   (done)
 **/

class Session
{
    private $db;
    private $settings;
    private $common;

    //Session info
    private $username;
    private $sessionKey;
    private $isLoggedIn = false;
    private $lastSeen;
    private $ip;
    private $dateCreated;
    public $user;

    /**
     * Sets up all the data
     *
     * @params $_db, $_settings, $_common
     */
    public function __construct($_db, $_settings, $_common)
    {//{{{3
        $this->db = $_db;
        $this->settings = $_settings;
        $this->common = $_common;
        $this->user = new User($_db, $_settings, $_common);
    }//}}}3

    //User Functions

    /**
     * Takes the user and password, verifies them and points them to the index page.
     *
     * @params $username, $password, $redirect="index.php", $remember=false
     */
    public function loginRedirect($username, $password, $redirect="index.php", $remember=false)
    {//{{{3
        $userpassword = $this->common->hash($password);

        // prepare SQL
        $sql = sprintf("SELECT 1 FROM users WHERE username='%s' AND password='%s'",
                mysql_real_escape_string($username),
                mysql_real_escape_string($userpassword));

        // execute query
        $result = $this->db->query($sql);
        if ($result === FALSE)
            die("Could not query database");

        // check whether we found a row
        if (mysql_num_rows($result) == 1)
        {
            $this->createSession($username, time(), $remember);
            echo header("Location: $redirect");
        }
        else
            return false;
    }//}}}3

    /**
     * Removes current users session from the session table
     *
     */
    public function logout()
    { return $this->deleteSession(); }

    /**
     * Takes the current session and checks if it is existing. Then starts it.
     *
     */
    public function sessionStart()
    {//{{{3
        session_start();
        //check for session
        if($this->sessionExists())
        {
            $this->username = $this->getUserNameByToken($_SESSION['token']);
            $this->user->loadUserByUserName($this->username);
            $this->isLoggedIn = true;
            return true;
        }
        else
            return false;
    }//}}}3

    /**
     * Checks to see if the session exists in the data base. Also creates a cookie or not if selected if the session exists
     *
     */
    public function sessionExists()
    {//{{{3
        //if already logged in say they are
        if($this->isLoggedIn == true)
            return true;
        //if a token is set check to see if it is valid
        elseif(isset($_SESSION["token"]))
        {
            $sql = sprintf("SELECT 1 FROM session WHERE token='%s'",
                    mysql_real_escape_string($_SESSION["token"]));

            $result = $this->db->query($sql);
            if($result === FALSE)
                die("Could not find an existing session");

            //if token is valid load user
            if(mysql_num_rows($result) == 1)
            {
                $this->sessionKey = $_SESSION["token"]; //get key from user
                $this->userName = $this->retrieveUserName(); //load username
                $this->updateSession(); //update the session if it exists
                $this->isLoggedIn = true; //set logged in to true
                return true;
            }
            else
                return false;
        }
        //if a session is loaded in a cookie check to see if it is valid
        elseif(isset($_COOKIE["token"]))
        {
            $sql = sprintf("SELECT 1 FROM session WHERE token='%s'", mysql_real_escape_string($_COOKIE["token"]));

            $result = $this->db->query($sql);//query database
            if($result === FALSE)
                die("Could not query databae");

            if(mysql_num_rows($result) == 1) //if it's good
            {
                $_SESSION['token'] = $_COOKIE['token']; //set session token
                $this->sessionKey = $_SESSION["token"]; //set session key
                $this->userName = $this->retrieveUserName(); //load username
                $this->updateSession(); //update session
                $this->isLoggedIn = true; //login set to true
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }//}}}3

    /**
     * Creates the session by inserting into the session table
     *
     * @params $username, $time, $remember=false
     * @note we are using the defalut time for php
     */
    private function createSession($username, $time, $remember=false)
    {//{{{3
        $token = $this->common->generateToken();
        $filePath = $this->settings->getLogPath();

        //get an user information
        $uobj = new User($this->db, $this->settings, $this->common);
        $uobj->loadUserByUserName($username); //load the user
        $userid = $uobj->getUserID(); //get the id

        //add token to database and assign to user
        //token, userid, username, ip, dateCreated, lastSeen
        $sql = sprintf("INSERT INTO session VALUES ('%s', %d, '%s', '%s', %d, %d)",
                $token, $userid, $username, $_SERVER["REMOTE_ADDR"], $time, $time);
        $result = $this->db->query($sql);
        $_SESSION['token'] = $token;

        if($result === FALSE)
            die("Insertion of session failed");


        $message = $username . " created session " . date("F j, Y @ g:i a") . " using createSession()";
        $this->common->appendToFile($filePath,$message);

        if($remember)
            setcookie("token", $token, time()+14*24*60*60);
    }//}}}3

    //////////////////////////
    //Admin functions
    //////////////////////////

    /**
     * Returns all active sessions in the session table
     *
     */
    public function getAllActiveSessions()
    {//{{{3
        // prepare SQL
        $sql = "SELECT * FROM session";
        // execute query
        $result = $this->db->query($sql);
        if ($result === FALSE)
            die("Could not query database");

        $stack = array();
        while($row = mysql_fetch_array($result))
        {
            $object = new Session($this->db, $this->settings, $this->common);
            $object->getSessionByToken($row['token']);
            array_push($stack, $object);
        }

        return $stack;
    }//}}}3

    /**
     * Makes the object have everything it needs
     *
     */
    public function getSessionByToken($token)
    { //{{{3
        $sql = sprintf("SELECT * FROM session WHERE token ='%s'",
                mysql_real_escape_string($token));
        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query database");

        $row = mysql_fetch_array($result);

        $this->sessionKey = $token;
        $this->username = $row['userName'];
        $this->ip= $row['ipAddress'];
        $this->dateCreated = $row['dateCreated'];
        $this->lastSeen = $row['lastSeen'];
    }//}}}3

    /**
     * Makes the object have everything it needs
     *
     */
    public function getSessionByUser($user)
    { //{{{3
        $sql = sprintf("SELECT * FROM session WHERE userName ='%s'",
                mysql_real_escape_string($user));
        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query database");

        $row = mysql_fetch_array($result);

        $this->sessionKey = $row['token'];
        $this->username = $user;
        $this->ip= $row['ipAddress'];
        $this->dateCreated = $row['dateCreated'];
        $this->lastSeen = $row['lastSeen'];
    }//}}}3

    public function getUserName()
    { return $this->username; }

    public function getIpAddress()
    { return $this->ip;}

    public function getUserSessionKey()
    { return $this->sessionKey;}

    public function getSessionKey()
    { return $this->sessionKey;}

    public function getDateCreated()
    { return $this->dateCreated;}

    public function getLastSeen()
    { return $this->lastSeen;}

    //get user name by token
    public function getUserNameByToken($session)
    {//{{{3
        $sql = sprintf("SELECT * FROM session WHERE token = '%s'", mysql_real_escape_string($session));

        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query database");

        $row = mysql_fetch_array($result);
        $returnName = $row["userName"];

        return $returnName;
    }//}}}3


    /**
     * Get the user id based on the token
     *
     * @params $userNameIn
     */
    public function getUserIDByToken($TokenIn)
    {//{{{3
        $returnID = "";
        $sql = sprintf("SELECT * FROM session WHERE token = %s",
                mysql_real_escape_string($TokenIn));
        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query database");
        while($row = mysql_fetch_array($result))
        {
            $returnName = $row["userID"];
        }

        return $returnID;
    }//}}}3

    /**
     * Checks is the a user has a existing session
     *
     * @params $userNameIn
     */
    public function checkSessionExists($userNameIn)
    {//{{{3
        // prepare SQL
        $retrunValue = false;
        $sql = sprintf("SELECT * FROM session WHERE userName = %s",
                mysql_real_escape_string($username));

        // execute query
        $result = $this->db->query($sql);
        if ($result === FALSE)
            die("Could not query database");

        if (mysql_num_rows($result) == 1)
            $returnValue = true;

        return $returnValue;
    }//}}}3

    /**
     * Logs out all the users past a given days back
     *
     * @param $daysIn
     */
    public function logOutUserPast($daysIn)
    {//{{{3
        // prepare SQL
        $sql = sprintf("SELECT * FROM session WHERE dateCreated = $daysIn",
                mysql_real_escape_string($username),
                mysql_real_escape_string($userpassword));

        // execute query
        $result = $this->db->query($sql);
        if ($result === FALSE)
            die("Could not query database");

        if (mysql_num_rows($result) == 1)
        { $returnValue = true; }
        while($row = mysql_fetch_array($result))
        { $this->logoutSession($row['token']); }

    }//}}}3

    /**
     * Removes the user from the session table
     *
     * @param $user
     */
    public function logoutUser($user)
    {//{{{3
        $sql = sprintf("DELETE FROM session WHERE userName='%s'", mysql_real_escape_string($user));
        $filePath = $this->settings->getLogPath();


        // execute query
        $result = $this->db->query($sql);

        $message = $this->username . " removed session for " . $user . " on " . date("F j, Y @ g:i a") . " using logoutUser()";
        $this->common->appendToFile($filePath,$message);
        return $result;
    }//}}}3

    public function logoutSession($session)
    {//{{{3

        $user = $this->getUserNameByToken($session);


        $sql = sprintf("DELETE FROM session WHERE token='%s'", mysql_real_escape_string($session));
        $filePath = $this->settings->getLogPath();

        $result = $this->db->query($sql);

        $message = $this->username . " removed session for " . $user . " on " . date("F j, Y @ g:i a") . " using logoutSession()";
        $this->common->appendToFile($filePath,$message);
        return $result;

    }//}}}3

    /**
     * Removes the current session from the database
     *
     * @params $_db, $_settings, $_common
     */
    private function deleteSession()
    {//{{{3

        if(isset($_SESSION['token']))
        {
            $sql = sprintf("DELETE FROM session WHERE token='%s'", mysql_real_escape_string($_SESSION["token"]));
            $filePath = $this->settings->getLogPath();

            // execute query
            $result = $this->db->query($sql);
            $message = $this->username . " removed session " . date("F j, Y @ g:i a") . " using deleteSession()";
            $this->common->appendToFile($filePath,$message);
        }
        else
            $result = FALSE;

        if(isset($_COOKIE['token']))
        {
            $sql = sprintf("DELETE FROM session WHERE token='%s'", mysql_real_escape_string($_COOKIE["token"]));

            $result = $this->db->query($sql);
        }

        // delete cookies, if any
        setcookie("token", "", time() - 3600);

        // log user out
        setcookie(session_name(), "", time() - 3600);

        session_destroy();

        if($result)
            echo 'You are logged out.';
        else
            echo "Deleteing session failed";

    }//}}}3

    /**
     * Removes the user from the session table
     *
     * @returns username based on the token id from session or cookie
     */
    private function retrieveUserName()
    {//{{{3

        //if a token is set use the token to find it
        if(isset($_SESSION["token"]))
        {
            $getUserSQL = sprintf("SELECT userName FROM session WHERE token='%s'",
            mysql_real_escape_string($_SESSION["token"]));

            $result = $this->db->query($getUserSQL);

            if($result === FALSE)
                die("Could not retrieve the username query database.");
            else
            {
                $row = mysql_fetch_array($result);
                return $row['userName'];
            }
        }
        //if a cookie is set use the token there ot find it
        elseif(isset($_COOKIE["token"]))
        {

            $getUserSQL = sprintf("SELECT userName FROM session WHERE token='%s'",
            mysql_real_escape_string($_COOKIE["token"]));

            $result = $this->db->query($getUserSQL);

            $row = mysql_fetch_array($result);
            return $row['userName'];
        }

    }//}}}3

    /**
     * Updates the ip address and the time that the user was last
     * seen for the session.
     *
     * @returns username based on the token id from session or cookie
     */
    private function updateSession()
    {//{{{3
        $time = time();
        $sql = sprintf("UPDATE session SET lastSeen=%d, ipAddress='%s' WHERE token='%s'",
                $time,
                $_SERVER["REMOTE_ADDR"],
                $this->sessionKey);

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not update time last seen");

    }//}}}3
}
?>
