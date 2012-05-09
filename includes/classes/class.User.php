<?php

error_reporting(E_ALL);

/**
 * Airpic - class.User.php
 *
 *
 * This file is part of Airpic.
 *
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
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
 * Holds all of the information about the user
 *
 * @access public
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
 */
class User
{
    // --- ATTRIBUTES ---
    private $userid; //userid for the current user
    private $username; //username for the current user
    private $firstName; //first name for the user
    private $lastName; //last name for the user
    private $email; //email for the user
    private $lastModified; //the time the user was last modified
    private $modifiedBy; //username of user that modified the current user
    private $birthdate; //birthday for the current user
    private $isAdmin; //defines whether the user is an admin

    // --- OPERATIONS ---//{{{1

    /**
     * Instantiates an user object.
     * The class needs the database object to make sql queries,
     * settings object to query certain settings and common object
     * to hash passwords and other functions for the class
     *
     * @params _db, _settings, _common
     */
    public function __construct($_db, $_settings, $_common)
    {//{{{2
        $this->db = $_db;
        $this->settings = $_settings;
        $this->common = $_common;
    }//}}}2

    // Getters and setters{{{2

    //getters {{{3
    /**
     * Returns the birthdate
     *
     * @return birthdate
     */
    public function getBirthdate()
    { return $this->birthdate; }

    /**
     * Returns the userid
     *
     * @return userid
     */
    public function getUserID()
    { return $this->userid ; }

    /**
     * Returns the username
     *
     * @return username
     */
    public function getUsername()
    { return $this->username ; }

    /**
     * Returns the firstname of the user
     *
     * @return firstName
     */
    public function getFirstName()
    { return $this->firstName ; }

    /**
     * Returns the lastname of the user
     *
     * @return lastName
     */
    public function getLastName()
    { return $this->lastName ; }

    /**
     * Returns the email of the user
     *
     * @return email
     */
    public function getEmail()
    { return $this->email ; }

    /**
     * Returns the time that the user was last modified
     *
     * @return lastModified
     */
    public function getLastModified()
    { return $this->lastModified ; }

    /**
     * Returns the name of the person that
     * last modified the user
     *
     * @return modifiedBy
     */
    public function getModifiedBy()
    { return $this->modifiedBy ; }

    //}}}3  end getters ======

    //{{{3 setters
    /**
     * Sets the userid
     *
     * @params _userid
     */
    public function setUserid($_userid)
    { $this->userid  = $_userid; }

    /**
     * Sets the username
     *
     * @params _username
     */
    public function setUsername($_username)
    { $this->username = $_username ; }

    /**
     * Sets the firstname for the user
     *
     * @params _firstName
     */
    public function setFirstName($_firstName)
    { $this->firstName = $_firstName ; }

    /**
     * Sets the lastname for the user
     *
     * @params _lastName
     */
    public function setLastName($_lastName)
    { $this->lastName = $_lastName ; }

    /**
     * Sets the email for the user
     *
     * @params _email
     */
    public function setEmail($_email)
    { $this->email = $_email ; }

    /**
     * Sets last modified time
     *
     * @params _lastModified
     */
    public function setLastModified($_lastModified)
    { $this->lastModified = $_lastModified ; }

    /**
     * Sets modified by. This is the person that modified the user
     *
     * @params _modifiedBy
     */
    public function setModifiedBy($_modifiedBy)
    { $this->modifiedBy = $_modifiedBy ; }

    /**
     * Sets the birthdate for the user
     *
     * @params birthdate
     */
    public function setBirthdate($_birthdate)
    { $this->birthdate = $_birthdate ; }

    public function toggleAdmin()
    {}

    //}}}3 end setters =====

    // -- end getters and setters }}}2

    /**
     * Loads the users information into the object
     *
     * @param  username
     */
    public function loadUserByUserName($username)
    {
        $sql = sprintf("SELECT * FROM users WHERE username='%s'",
                mysql_real_escape_string($username));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database7");
        else
        {
            $row = mysql_fetch_array($result);
            $this->setUserid($row['userid']);
            $this->setUsername($username);
            $this->setFirstName($row['fname']);
            $this->setLastName($row['lname']);
            $this->setEmail($row['email']);
            $this->setLastModified($row['modified']);
            $this->setModifiedBy($row['modifiedBy']);
            $this->isAdmin = $row['isAdmin'];
        }
    }

    /**
     * Loads an user into an instantiated object by the userid provided
     *
     * @return userid
     */
    public function loadUserByID($userid)
    {
        $sql = sprintf("SELECT * FROM users WHERE userid=%s",
                mysql_real_escape_string($userid));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database8");
        else
        {
            $row = mysql_fetch_array($result);
            $this->setUsername($row['username']);
            $this->setFirstName($row['fname']);
            $this->setLastName($row['lname']);
            $this->setEmail($row['email']);
            $this->setUserid($userid);
            $this->setLastModified($row['modified']);
            $this->setModifiedBy($row['modifiedBy']);
            $this->isAdmin = $row['isAdmin'];
        }
    }

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
        $sql = sprintf("SELECT * FROM users WHERE username='%s'",
                mysql_real_escape_string($username));

        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query the database9");

        if(mysql_num_rows($result) > 0)
            return true;
        else
            return false;
    }
    //-- end operations -- }}}1

    public function getAllUserNames()
    {
        $sql = sprintf("SELECT * FROM users");

        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not query the databaseA");

        $stack = array();
        while($row = mysql_fetch_array($result))
        {
            $value = $row['username'];

            array_push($stack,$value);
        }
        return $stack;
    }



    public function isAdmin()
    {//{{{3
      //Retrieves USERID
      $userID = $this->getUserID();

      //Searches DB for a userID match
      $sql = sprintf("SELECT isAdmin FROM users where userid = %d",
		  mysql_real_escape_string($userID));

      //Queries DB
      $result = $this->db->query($sql);
      if ($result === FALSE)
            die("Could not query databaseB");

      //Retrieve the Row from the Query
      $row = mysql_fetch_array($result);

      //Returns the value of the "administator" for UserID
      return $row['isAdmin'];
    }//}}}3


  public function updateUser($userid, $username, $oldpass, $password, $fname, $lname, $email)
  {//{{{3
      $usernameRes = true;
      $passwordRes = true;
      $fnameRes = true;
      $lnameRes = true;
      $emailRes = true;
      $roleRes = true;


      $idExists = $this->userIDExists($userid);
      if($idExists)
      {
          //check to make sure that someone else doesn't have //the new username
          $exists = $this->usernameExists($username);

          //check if the values changed
          //update if changed
          if(!$exists)
              $usernameRes = $this->updateUserName($userid,$username);
          if(strlen($password) > 0 && $this->checkPassword($userid,$oldpass))
          {
              $passwordRes = $this->updatePassword($userid,$password);
          }
          $fnameRes = $this->updateFname($userid,$fname);
          $lnameRes = $this->updateLname($userid,$lname);
          $emailRes = $this->updateEmail($userid,$email);

          if($usernameRes && $passwordRes && $fnameRes && $lnameRes && $emailRes)
              return true;
          else
              return false;
      }
      else
          return false;
  }//}}}3


  public function adminUpdateUser($userid, $username, $password, $fname, $lname, $email, $isAdmin)
  {//{{{3
      $usernameRes = true;
      $passwordRes = true;
      $fnameRes = true;
      $lnameRes = true;
      $emailRes = true;
      $roleRes = true;
      $isAdminRes = true;


      $idExists = $this->userIDExists($userid);
      if($idExists)
      {
          //check to make sure that someone else doesn't have //the new username
          $exists = $this->usernameExists($username);

          //check if the values changed
          //update if changed
          if(!$exists)
              $usernameRes = $this->updateUserName($userid,$username);
          if(strlen($password) > 0)
          {
              $passwordRes = $this->updatePassword($userid,$password);
          }
          $fnameRes = $this->updateFname($userid,$fname);
          $lnameRes = $this->updateLname($userid,$lname);
          $emailRes = $this->updateEmail($userid,$email);
          $isAdminRes = $this->updateIsAdmin($userid, $isAdmin);

          if($usernameRes && $passwordRes && $fnameRes && $lnameRes && $emailRes && $isAdminRes)
              return true;
          else
              return false;
      }
      else
          return false;
  }//}}}3

  private function checkPassword($userid,$password)
  {
      $password = $this->common->hash($password);

      $sql = sprintf("SELECT 1 FROM users WHERE userid=%d AND password='%s'",
              mysql_real_escape_string($userid),
              mysql_real_escape_string($password));
      $result = $this->db->query($sql);
      if($result === FALSE)
          die("Could not query databaseC!");

      if(mysql_num_rows($result))
          return true;
      else 
          return false;
  }

    private function userIDExists($userid)
    {
        $sql = sprintf("SELECT * FROM users WHERE userid=%d",
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query databaseD");

        if(mysql_num_rows($result))
            return true;
        else
            return false;
    }

    private function usernameExists($username)
    {

        $sql = sprintf("SELECT * FROM users WHERE username='%s'",
                mysql_real_escape_string($username));
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query databaseE");

        if(mysql_num_rows($result))
            return true;
        else
            return false;
    }

    private function updateUserName($userid,$username)
    {
        $sql = sprintf("UPDATE users SET username='%s' WHERE userid=%d",
                mysql_real_escape_string($username),
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
        {
            die("Could not query databaseF");
            return false;
        }
        else
            return true;

    }

    private function updatePassword($userid,$password)
    {
      $password = $this->common->hash($password);
        $sql = sprintf("UPDATE users SET password='%s' WHERE userid=%d",
                mysql_real_escape_string($password),
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
        {
            die("Could not query databaseG");
            return false;
        }
        else
            return true;
    }

    private function updateFname($userid,$fname)
    {
        $sql = sprintf("UPDATE users SET fname='%s' WHERE userid=%d",
                mysql_real_escape_string($fname),
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
        {
            die("Could not query databaseH");
            return false;
        }
        else
            return true;
    }

    private function updateLname($userid,$lname)
    {
        $sql = sprintf("UPDATE users SET lname='%s' WHERE userid=%d",
                mysql_real_escape_string($lname),
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
        {
            die("Could not query databaseI");
            return false;
        }
        else
            return true;
    }

    private function updateEmail($userid,$email)
    {
        $sql = sprintf("UPDATE users SET email='%s' WHERE userid=%d",
                mysql_real_escape_string($email),
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
        {
            die("Could not query databaseJ");
            return false;
        }
        else
            return true;
    }

    private function updateIsAdmin($userid, $isAdmin)
    {
        $sql = sprintf("UPDATE users SET isAdmin=%d WHERE userid=%d",
                mysql_real_escape_string($isAdmin),
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
        {
            die("Could not update databaseK");
            return false;
        }
        else
            return true;
    }

    public function deleteUserID($userid)
    {
        $sql = sprintf("DELETE FROM users WHERE userid=%d", 
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query databaseL");
        else
            return true;
    }



} /* end of class User */

?>
