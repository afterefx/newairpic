<?php

error_reporting(E_ALL);

/**
 * Airpic - class.Common.php
 *
 *
 * This file is part of Airpic.
 *
 * @author: Christopher Carlisle
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This is the main object that contains the backend
 */
require_once('class.Airpic.php');

/* user defined includes */

/* user defined constants */

/**
 * Short description of class Common
 *  This class contains all the functions that wil be used accross the
 *     pages.
 *
 * @access public
 */
class Common
{
    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * encrypt()
     *  Takes text and encrypts it with sha512 then returns it
     *
     * @params $text
     * @return encrypted text
     */
    public function encrypt($text)
    { return hash("sha512",$text); }

    /**
     * hash()
     *  Takes the string and passes it to the encrypt function and returns it
     *
     * @params $text
     * @return encrypted text
     */
    public function hash($text)
    { return $this->encrypt($text); }

     /**
     * redirect()
     *    sends header location of the page to be redirected to
     *
     * @params $url
     */
    public function redirect($url)
    { header("Location: $url"); }

    /**
     * Generates an alphanumeric string that is 200 characters long.
     *
     * @return a token that is unique
     */
    public function generateToken()
    {
        $number = time(); //this will give us a number that never repeats
        $token = $this->encrypt($number);
        return $token;
    }

    /**
     * Takes a file name and a message. The message is written to the file that
     *  was given
     *
     * @params fileName , message
     * @return nothing
     */
    public function appendToFile($fileName, $message)
    {
        $handler = fopen($fileName,'a');//open the file in an append mode
        fwrite($handler, "\n". $message); //write the passed message to the file
        fclose($handler); //close the file
    }

    /**
      * Generates a random string
      *
      */
    public function randomString($length)
    {
        $characters = '0123456789abcdefghijkmnopqrstuvwxyzACDEFGHJKLMNPRSTUVWXYZ';
        $string = "";
        $strLength= strlen($characters);

        for ($p = 0; $p < $length; $p++)
        {
            $string .= $characters[mt_rand(0, $strLength)];
        }
        return $string;
    }
} /* end of class Common */

?>
