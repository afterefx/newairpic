<?php

error_reporting(E_ALL);

/**
 * Airpic - class.Strings.php
 *
 *
 * This file is part of Airpic.
 *
 */

/**
*  This file handles everything that has to do with "Strings"
*
*  Admin Functions
*    addString(variableName, value)
*    updateString(variableName, newValue)
*    getStringByName(variableName)
*    getXMLCount()
*    displayXML()
*    deleteString(variableName)
*    stringErrorChecking(variableName, value)
**/

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */

/* user defined constants */

/**
 * Short description of class Strings
 *
 * @access public
 */
class Strings
{
    private $airpicXML;

    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    //All file paths passed to the Strings
    //class should be relative to the www folder
	public function __construct($_XMLFile)
	{
		$this->airpicXML = $_XMLFile;

		if(!$airpicXML=simplexml_load_file($this->airpicXML))
		{ die('Error reading XML file'); }
	}

    /**
     * Adds a string to the strings.xml file
     *
     * @params $_stringKey, $_stringValue
     */
    public function addString($_stringKey, $_stringValue)
    {
        //check if Key exists
        if($this->stringErrorChecking($_stringKey, "exists"))
        { return "This String already exists"; }

        if($this->stringErrorChecking($_stringKey, "empty") || $this->stringErrorChecking($_stringValue, "empty")  )
        { return "The Key and/or Value field was empty"; }

        //format Key and Value
        $_stringKey = $this->stringErrorChecking($_stringKey, "key");
        $_stringValue = $this->stringErrorChecking($_stringValue, "value");

        $xml = simplexml_load_file($this->airpicXML);

        $newXML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?> \n<airpicString>\n";


        //Add each String already in the xml file
        foreach($xml->children() as $string)
        { $newXML .= "<key id = \"" . $string['id'] . "\">" .  $string . "</key>\n"; }

        //Add new string to xml file, and close up the xml file
        $newXML .= "<key id = \"" . $_stringKey . "\">" . $_stringValue . "</key>\n";
        $newXML .= "</airpicString>\n";

        //Write to File
        $aXML = new SimpleXMLElement($newXML);
        $aXML->asXML($this->airpicXML);

        return 1;
    }

    /**
     * Edits a strings value in the strings.xml file
     * Input:	Key (to change)
	 *			Value (new value for Key)
	 *
     * @params $_stringKey, $_stringValue
     */
    public function editString($_stringKey, $_stringValue)
    {
        //check if Key exists
        if(!$this->stringErrorChecking($_stringKey, "exists"))
        { return "This String does not exists"; }

        if($this->stringErrorChecking($_stringKey, "empty") || $this->stringErrorChecking($_stringValue, "empty")  )
        { return "The Key and/or Value field was empty"; }

        //Error Checking
        $_stringKey = $this->stringErrorChecking($_stringKey, "key");
        $_stringValue = $this->stringErrorChecking($_stringValue, "value");

        $xml = simplexml_load_file($this->airpicXML);

        $newXML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?> \n<airpicString>\n";

        //Search each child of string for a matchtestS
        foreach($xml->children() as $string)
        {
            //If it is not the remove string, add to our newXML file
            if( $string['id']!=  $_stringKey)
            { $newXML .= "<key id = \"" . $string['id'] . "\">" . $string . "</key>\n"; }
            else
            { $newXML .= "<key id = \"" . $string['id'] . "\">" . $_stringValue . "</key>\n"; }
        }

        $newXML .= "</airpicString>\n";

        //Write to File
        $aXML = new SimpleXMLElement($newXML);
        $aXML->asXML($this->airpicXML);

        return "String Edited";

    }


    /**
     * Returns a single string from strings.xml file in a .html formatted string
     *
     * @params $_stringKey
     */
    public function getStringByName($_stringKey)
    {
        if(!$this->stringErrorChecking($_stringKey, "exists"))
        { return "This String does not exists"; }

        $_stringKey = $this->stringErrorChecking($_stringKey, "key");

        $xml = simplexml_load_file($this->airpicXML);

        foreach($xml->children() as $string)
        {
            //Search for the Key's Value
            if( $string['id']==  $_stringKey)
            { return $string; }
        }
    }

    /**
     * Returns the number of strings in the XML file.
     *
     */
    public function getXMLCount()
    {
        $xml = simplexml_load_file($this->airpicXML);

        $myXMLCount = 0;

        $stringArray;
        foreach($xml->children() as $string)
        { $myXMLCount++; }

        return $myXMLCount;
    }

    /**
     * Returns the strings.xml file in a .html formatted string
     *
     * @params $_stringKey, $_stringValue
     */
    public function getXML()
    {
        $xml = simplexml_load_file($this->airpicXML);

        $myXMLString = "<br />" . $xml->getName() . " XML file:<br />";
        $n = 0;
        $stringArray;
        foreach($xml->children() as $string)
        {
          $myXMLString .= $string['id'] . ": " . $string . "<br />";
          $stringArray[$n]['key'] = $string['id'];
          $stringArray[$n]['value'] = $string;
          $n++;
        }

        return $stringArray;
    }



    /**
     * Delete a string to the strings.xml file
     * Input: Key to be deleted (i.e. Title, Header)
     *
     * @params $_stringKey
     */
    public function deleteString($_stringKey)
    {
        //check if Key exists
        if(!$this->stringErrorChecking($_stringKey, "exists"))
        { return "This String already exists"; }

        //checks if key is empty
        if($this->stringErrorChecking($_stringKey, "empty"))
        { return "The Key field was empty"; }

        //checks if key is primary
        if($this->stringErrorChecking($_stringKey, "primary"))
        { return "The Key is a Primary key and cannot be deleted."; }

        $xml = simplexml_load_file($this->airpicXML);

        $newXML = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?> \n<airpicString>\n";

        //Search each child of string for a match
        foreach($xml->children() as $string)
        {
            //If it is not the remove string, add to our newXML file
            if( $string['id']!=  $_stringKey)
            { $newXML .= "<key id = \"" . $string['id'] . "\">" . $string . "</key>\n"; }
        }

        $newXML .= "</airpicString>\n";

        //Write to File
        $aXML = new SimpleXMLElement($newXML);
        $aXML->asXML($this->airpicXML);

        return "String Removed";
    }


    /**
     * Check the input of user
     * Input: stringKey/stringValue and method
     *
     * Method: Using a switch to determine if it is to be:
     *
     * key	checks for htmlspecialchars and "/"
     * value	exact same as key:
     *		both return the "fixed variable"
     * exists	returns 1 of exists, else 0
     * primary	checks if key is "Title", "Header", "Footer", or "Welcome"
     *		returns the primary string matched if matched, else 0
     * to select what type of
     * input checking to run.
     *
     * @params $_stringKey
     */
    public function stringErrorChecking($_string, $_method)
    {
        $xml = simplexml_load_file($this->airpicXML);

        switch($_method)
        {
            case "key":
            case "value":
                //Check for html special chars, and edit for security
                $_string = str_replace("<", "_", $_string);
                $_string = str_replace(">", "_", $_string);
                $_string = htmlspecialchars(trim($_string));
                $_string = stripslashes($_string);
                return $_string;
                break;
            case "exists":
                //Search each child of string for a match
                foreach($xml->children() as $string)
                {
                    //If the Key is taken, do not add a new one.
                    if( strtolower($string['id'])==  strtolower($_string))
                    { return 1; }
                }
                return 0;
                break;

            case "primary":
              //If the Key is taken, do not add a new one.
                if( strtolower("Title") ==  strtolower($_string) ||
                    strtolower("Site Title") ==  strtolower($_string) ||
                    strtolower("Slogan") ==  strtolower($_string))
                        { return $_string; }
                return 0;
                break;
            case "empty":
              //If the string is empty
              empty($_string);
              break;
        }
    }
} /* end of class Strings */

?>
