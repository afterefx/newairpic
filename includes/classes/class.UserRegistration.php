<?php

error_reporting(E_ALL);

/**
 * Airpic - class.UserRegistration.php
 *
 *
 * This file is part of Airpic.
 *
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This is the main object that contains the backend
 *
 * @author Christopher Carlisle
 */
require_once('class.Airpic.php');

/* user defined includes */

/* user defined constants */

/**
 * Short description of class UserRegistration
 *
 * @access public
 */
class UserRegistration
{
    // --- ATTRIBUTES ---
    private $db;
    private $common;

    // --- OPERATIONS ---
    public function __construct($_db)
    { $this->db = $_db; }

    /**
     * Creates a token for the user and inserts into the database. It emails the
     * the token and how to confirm their account. To ensure the token is
     * completely unique we append the username onto the end and then hash
     * the string again.
     *
     * @access public
     * @param  username
     * @return mixed
     */
    public function createToken($username)
    {
        $obj = new Common();
        $string = $obj->generateToken();
        $string .= $username;//append string
        return $obj->hash($string);//rehash
    }

    /**
     * Confirms the user in the user registration table and activates the user
     *
     * @access public
     * @param  token
     * @return mixed
     */
    public function confirmToken($token)
    {
        $sql=sprintf("SELECT * from  userRegistration WHERE token ='%s'",
            mysql_real_escape_string($token));
        $result = $this->db->query($sql);
        if($result===FALSE)
            die("Could not find token in database");
        $row = mysql_fetch_array($result);
        return $this->approveUser($row['username']);
    }


    /**
     * Adds the user to the user registration table and automatically
     * approves the account
     *
     * @access public
     * @param  time, email, username, password, fname, lname, isAdmin
     * @return bool
     */
    public function createUserNow($username, $password, $email, $fname, $lname, $isAdmin)
    {
        $time = time();
        $result = $this->addUser($time, $email, $username, $password, $fname, $lname, $isAdmin);
        if(!$result)
            return false;
        else
            $result2 = $this->approveUser($username);

        return ($result2) ? true: false;


    }

    /**
     * Adds the user to the user registration table to await confirmation or
     * approval of account
     *
     * @access public
     * @param  time, email, username, password, fname, lname, isAdmin
     * @return bool
     */
    public function addUser($time, $email, $username, $password, $fname, $lname, $isAdmin)
    {
        //create a token for this registration
        $token = $this->createToken($username);
        $obj = new Common();
        $password = $obj->hash($password);

        //insert into table
        $sql=sprintf("INSERT INTO userRegistration
                (token,username,fName,lName,isAdmin,password,email) VALUES
                ('%s','%s','%s','%s',%d,'%s','%s')",
                mysql_real_escape_string($token),
                mysql_real_escape_string($username),
                mysql_real_escape_string($fname),
                mysql_real_escape_string($lname),
                mysql_real_escape_string($isAdmin),
                mysql_real_escape_string($password),
                mysql_real_escape_string($email));

        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not insert user into database");

        if($result)
            return true;
        else
            return false;

    }

    /**
     * Takes a user name that is in the user reg table and creates
     * a entry in the user table and in the role table. This allows
     * the user to log into the system
     *
     * @params $userName
     */
    public function approveUser($userName)
    {//{{{1

        $sql = sprintf("SELECT * FROM userRegistration WHERE username = '%s'",
                mysql_real_escape_string($userName));

        $result = $this->db->query($sql);

        if($result ===FALSE)
            die("Could not query database2");

        if(mysql_num_rows($result) == 1)
        {
           //found the only user to have that name
           $row = mysql_fetch_array($result);
           $userName = $row['username'];
           $fName = $row['fName'];
           $lName = $row['lName'];
           $password = $row['password'];
           $email = $row['email'];
           $isAdmin = $row['isAdmin'];

           $sql = sprintf("INSERT INTO users
           (username, password, fname, lname, email, createdOn, isAdmin, enabled, lastLogIn)
               VALUES
           ('%s', '%s', '%s', '%s', '%s', %d, %d, %d, %d)", $userName, $password, $fName, $lName, $email, time(), $isAdmin, 1, 0);

           //$sql = sprintf("INSERT INTO users
                   //(userid,username,password,fname,lname,email,createdOn,modified,modifiedBy,birthDate,isAdmin,enabled,questionID,secretAnswer,lastLogIn) VALUES
                   //(%d,'%s','%s','%s','%s','%s',%d,%u,'%s',%d,%d,%d,%u,'%s',%d)",
                  //$userID, $userName,$password,$fName,$lName,$email,time(),1," ",1,1,1," ",1);

           $result = $this -> db ->query($sql);

           if($result === FALSE)
               die("Insertion of user failed!");

           $this->deleteUser($userName);

           return true;
        }
    }//}}}1

    /**
     * Takes a user name and calls the delete user function
     *
     * @params $userName
     */
    public function denyUser($userName)
    { $this->deleteUser($userName); }

    /**
     * Takes a user name and removes it from the user reg table.
     *
     * @params $userName
     */
    public function deleteUser($userName)
    {//{{{2
        $sql = sprintf("DELETE FROM userRegistration WHERE username ='%s'", mysql_real_escape_string($userName));
        $result = $this->db->query($sql);
        return $result;
    }//}}}2

    /**
     * Checks to see if the username is already in the
     * table and returns a bool
     *
     * @access public
     * @param  username
     * @return mixed
     */
    public function checkForUser($username)
    {
        $sql = sprintf("SELECT * FROM userRegistration WHERE username='%s'",
                mysql_real_escape_string($username));

        $result = $this->db->query($sql);
        if($result === FALSE)
            return false;
            //die("Could not query the database");

        if(mysql_num_rows($result) > 0)
            return true;
        //else
    }
}
?>
