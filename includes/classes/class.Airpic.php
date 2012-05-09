<?php

error_reporting(E_ALL);

/**
 * This is the main object that contains the backend
 *
 * @author Christopher Carlisle
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

require_once('class.Common.php');
require_once('class.DatabaseManager.php');
require_once('class.Page.php');
require_once('class.Session.php');
require_once('class.User.php');
require_once('class.UserRegistration.php');
require_once('class.Settings.php');
require_once('class.Image.php');


/* user defined includes */

/* user defined constants */

/**
 * This is the main object that contains the backend
 *
 * @access public
 */
class Airpic
{
    // --- ATTRIBUTES ---
    public $settings ;
    public $common ;
    public $db ;
    public $session ;
    public $page ;
    public $userRegistration ;
    public $image ;

    // --- OPERATIONS ---

    /**
     * Short description of method __constructor
     *
     * @access public
     * @return mixed
     */
    public function __construct()
    {
        $settings = new Settings();
        $common = new Common();
        $db = new DatabaseManager($settings);
        $session = new Session($db, $settings, $common);
        $page = new Page($session);
        $userRegistration = new UserRegistration($db);
        $image = new Image($db, $common);

        $this->settings = $settings;
        $this->common = $common;
        $this->db = $db;
        $this->session = $session;
        $this->page = $page;
        $this->userRegistration = $userRegistration;
        $this->image = $image;
    }

    /**
     * Detects the type of device the system is being used from
     *
     * @access public
     * @return mixed
     */
    public function detectDeviceType()
    {
    }

} /* end of class Airpic */

?>
