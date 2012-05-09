<?php

error_reporting(E_ALL);

/**
 * Airpic - class.DatabaseManager.php
 *
 *
 * This file is part of Airpic.
 *
 */

if (0 > version_compare(PHP_VERSION, '5'))
{
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
 * Short description of class DatabaseManager
 *
 * @access public
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
 */
class DatabaseManager
{
    // --- ATTRIBUTES ---
    private $connection; //connection object to database

    // --- OPERATIONS ---//{{{1

    /**
     * Creates a connection to the database using the settings
     * from the settings object passed in
     *
     * @params _settings
     * @return a DatabaseManager object
     */
    public function __construct($_settings)
    {//{{{2

        //Get settings for the database
        $url = $_settings->getDbUrl();
        $username = $_settings->getDbUsername();
        $password = $_settings->getDbPassword();
        $db = $_settings->getDatabase();

        if (($connection = mysql_connect($url, $username, $password)) === FALSE)
            die("Could not connect to database");

        if(isset($connection))
            $this->connection = $connection;

        // select database
        if (mysql_select_db($db, $connection) === FALSE)
            die("Could not select database.");
    }//}}}2

    /**
     * This will perform a query on the database we are connected
     * to and return the results
     *
     * @params _settings
     * @return a object for the results
     */
    public function query($sql)
    { return mysql_query($sql, $this->connection); }

    /**
     * Retrieves the ID generated for an AUTO_INCREMENT
     * column by the previous query (usually INSERT).
     *
     * @return number that was used last for an auto increment
     */
    public function getLastID()
    { return mysql_insert_id(); }

    /**
     * Closes the connection to the database
     *
     */
    public function __destruct()
    { mysql_close($this->connection); }


    //}}}1
} /* end of class DatabaseManager */

?>
