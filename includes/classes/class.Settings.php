<?php
require_once 'class.Strings.php';

error_reporting(E_ALL);

/**
 * Uses xml
 *
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */

/* user defined constants */

/**
 * This is the settings object, most of the settings if not all
 * are retrieved from the xml document in the includes folder
 *
 * @access public
 * @author Christopher Carlisle, <ccarlisle1@islander.tamucc.edu>
 */
class Settings
{
    // --- OPERATIONS ---
    private $strings;
    private $stringLocation;

    public function __construct()
    {
        $this->stringLocation = "/var/www/mySite/includes/airpicStrings.xml";
        $this->strings = new Strings($this->stringLocation);
    }
    /**
     * Returns the url for the database
     *
     * @return sends the url for the database back
     */
    public function getDbUrl()
    { return $this->strings->getStringByName("dburl"); }
    /**
     * Returns the url for the database
     *
     * @return sends the username for the database back
     */
    public function getDbUsername()
    { return $this->strings->getStringByName("dbusername"); }

    /**
     * Returns the password for the database
     *
     * @return sends the password for the database back
     */
    public function getDbPassword()
    { return $this->strings->getStringByName("dbpassword"); }

    /**
     * Returns the database for the database
     *
     * @return sends the database for the database back
     */
    public function getDatabase()
    { return $this->strings->getStringByName("database"); }

    /**
     * Returns the database for the database
     *
     * @return sends the database for the database back
     */
    public function getLogPath()
    { return $this->strings->getStringByName("logPath"); }

    /**
     * Returns the database for the database
     *
     * @return sends the database for the database back
     */
    public function getTitle()
    { return $this->strings->getStringByName("Title"); }

    /**
     * Returns the database for the database
     *
     * @return sends the database for the database back
     */
    public function getSiteTitle()
    { return $this->strings->getStringByName("Site Title"); }

    /**
     * Returns the database for the database
     *
     * @return sends the database for the database back
     */
    public function getSlogan()
    { return $this->strings->getStringByName("Slogan"); }

    public function getStringsLocation()
    { return $this->stringLocation; }

} /* end of class Settings */

?>
