<?php

error_reporting(E_ALL);

/**
 * This is the image object
 *
 * @author Christopher Carlisle
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This is the main object that contains the backend
 *
 * @access public
 */
class Image
{
    // --- ATTRIBUTES ---
    private $db;
    private $common;

    // --- OPERATIONS ---

    /**
     * Short description of method __constructor
     *
     * @access public
     * @return mixed
     */
    public function __construct($_db,$_common)
    {
        $this->db = $_db;
        $this->common =  $_common;
    }

    //retrieves a stack of id's for the page
    public function getImageIdStackForPageNum($userid, $pageNum)
    {

        //should come from settings obj :(
        $total = $this->getTotalImages($userid);
        if($pageNum == 1 && $total < 6)
            $imagesPerPage = $total;
        else
            $imagesPerPage = 6;


        $sql = sprintf("SELECT * FROM imgInfo WHERE userid=%d",
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query image database");
        else
        {
            //create a stack of all the rows
            $stack = array();

            //push each row on the stack
            while($row = mysql_fetch_array($result))
            { array_push($stack, $row); }

            //sort the stack by time
            usort($stack , 'Image::sortImageArrayByTimeCreated');

            $newstack = array();
            //display the images
            for($index=($pageNum-1)*$imagesPerPage;
                    $index < ($pageNum * $imagesPerPage) && $index < count($stack);
                    $index++)
            {
                if($stack[$index] != NULL) //display image if one is available
                    array_push($newstack, $stack[$index]['id']);
            }
            return $newstack;
        }
    }

    //returs total number of images
    public function getTotalImages($userid)
    {
        $sql = sprintf("SELECT * FROM imgInfo WHERE userid=%d",
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query image database!");
        else
            return mysql_num_rows($result);
    }

    //must be a row from imgInfo db containing created field
    private static function sortImageArrayByTimeCreated($a, $b)
    {
        return $b['created'] - $a['created'];
    }

    //retrieves the number of pages of images the user has
    public function getNumPagesForUser($userid)
    {
        //should come from settings class :(
        $imagesPerPage = 6;

        $sql = sprintf("SELECT * FROM imgInfo WHERE userid=%d",
                mysql_real_escape_string($userid));
        $result = $this->db->query($sql);

        if($result === FALSE)
                die("Could not query image db");
        else
            $numImages = mysql_num_rows($result);

        $numPages = $numImages/$imagesPerPage;
        $numPages = floor($numPages);

        if($numImages%$imagesPerPage != 0)
            $numPages++;

        return $numPages;
    }

    //retrieves the total bandwidth for the image
    public function getBandwidthImage($id)
    {
        $bw = $this->getBandwidthImageInt($id);

        //should come from settings class
        return round(($bw/ 1024),2);
    }

    public function getBandwidthImageInt($id)
    {
        $sql = sprintf("SELECT bandwidth FROM imgInfo WHERE id='%d'",
                mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database1");
        else
        {
            $row = mysql_fetch_array($result);
            return $row['bandwidth'];
        }
    }

    //retrieves the number of times the image was viewed
    public function getNumViewsImage($id)
    {
        $sql = sprintf("SELECT views FROM imgInfo WHERE id='%d'",
                mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database2");
        else
        {
            $row = mysql_fetch_array($result);
            return $row['views'];
        }
    }

    //retreives the file path for the image
    public function getImagePath($id)
    {
        $filename = $this->getFileName($id);
        return ("images/" . $filename);
    }

    //retrieves the filename for the id
    public function getFileName($id)
    {
        $sql = sprintf("SELECT filename FROM imgInfo WHERE id='%d'",
                mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database3");
        else
        {
            $row = mysql_fetch_array($result);
            return $row['filename'];
        }
    }

    //retrieves the file size of the id
    public function getFileSize($id)
    {
        $size = $this->getFileSizeInt($id);
        return round(($size/1024),2);
    }

    public function getFileSizeInt($id)
    {
        $sql = sprintf("SELECT size FROM imgInfo WHERE id='%d'",
                mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database4");
        else
        {
            $row = mysql_fetch_array($result);
            return $row['size'];
        }
    }

    //retrieves the delete url
    public function getDeleteURL($id)
    {
        $sql = sprintf("SELECT del FROM imgURL WHERE id='%d'",
                mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database5");
        else
        {
            $row = mysql_fetch_array($result);
            return $row['del'];
        }
    }

    //returns the image id for the url given
    public function getImageId($url)
    {
        $sql = sprintf("SELECT id FROM imgURL WHERE view='%s' OR del='%s'",
                mysql_real_escape_string($url),
                mysql_real_escape_string($url));
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database for url");
        else
        {
            $row = mysql_fetch_array($result);
            return $row['id'];
        }
    }

    //returns the option of view or delete based on url
    public function getImageOption($url)
    {
        $sql = sprintf("SELECT * FROM imgURL WHERE view='%s' OR del='%s'",
            mysql_real_escape_string($url),
            mysql_real_escape_string($url));
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query database for option");
        else
        {
            $row = mysql_fetch_array($result);
            if($row['view'] == $url)
                return 'v';
            elseif($row['del'] == $url)
                return 'd';
            else
                return 'b';
        }

    }

    //retrieves the view url
    public function getViewURL($id)
    {
        $sql = sprintf("SELECT view FROM imgURL WHERE id='%d'",
                mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Could not query the database6");
        else
        {
            $row = mysql_fetch_array($result);
            return $row['view'];
        }
    }

    //sets the filename of the id
    public function setFileName($id,$fileName)
    {
    }

    //sets the file size of the id
    public function setFileSize($id,$fileSize)
    {
    }

    //sets the view and delete urls
    public function setURL($id, $view, $del)
    {
    }

    //adds to the bandwidth the file size
    public function addBandwidth($id)
    {
        $currentbw = $this->getBandwidthImageInt($id);
        $size = $this->getFileSizeInt($id);
        $newbw = $currentbw + $size;
        $sql = sprintf("UPDATE imgInfo SET bandwidth=%d WHERE id=%d",
                mysql_real_escape_string($newbw),
                mysql_real_escape_string($id));
        $result =$this->db->query($sql);

        if($result === FALSE)
            die("Update of bandwidth failed!");
    }

    //increments the number of view by one
    public function addView($id)
    {
        $count = $this->getNumViewsImage($id);
        $count++;
        $sql = sprintf("UPDATE imgInfo SET views=%d WHERE id=%d",
                mysql_real_escape_string($count),
                mysql_real_escape_string($id));
        $result =$this->db->query($sql);

        if($result === FALSE)
            die("Update of view count failed!");
    }

    //get image id for the url
    public function getID($url)
    {
    }

    //returns whether this is a view or delete operation
    public function getOperation($url)
    {
    }

    public function addImage($userid, $fileName, $size)
    {

        $sql = sprintf("INSERT INTO imgInfo (userid, filename,size,created)
                VALUES (%d, '%s', %d, %d)",
                mysql_real_escape_string($userid),
                mysql_real_escape_string($fileName),
                mysql_real_escape_string($size),
                time());

        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Insertion of image information failed!");


        $sql ="SELECT MAX(id) FROM imgInfo";
        $result = $this->db->query($sql);

        if($result === FALSE)
            die("Insertion of image information failed!");
        else {
            $array = mysql_fetch_array($result);
            $id = $array[0];
        }

        do
        {
            $view = $this->common->randomString(5);
            $sql = sprintf("SELECT * FROM imgURL WHERE view='%s' OR del='%s'",
                    mysql_real_escape_string($view),
                    mysql_real_escape_string($view));
            $result = $this->db->query($sql);
            if(mysql_num_rows($result) == 0)
                break;
            //check to see it doesn't already exist in either del or view
        }
        while(1);

        do
        {
            $del = $this->common->randomString(5);
            $sql = sprintf("SELECT * FROM imgURL WHERE view='%s' OR del='%s'",
                    mysql_real_escape_string($del),
                    mysql_real_escape_string($del));
            $result = $this->db->query($sql);
            if(mysql_num_rows($result) == 0)
                break;
        }
        while(1);

        $sql = sprintf("INSERT INTO imgURL VALUES (%d, '%s', '%s')",
                mysql_real_escape_string($id),
                mysql_real_escape_string($view),
                mysql_real_escape_string($del));
        $result = $this->db->query($sql);
        if($result === FALSE)
            die("Could not insert into image url database");
        else
            return $id;

    }

    public function removeImage($id)
    {
        $fullPath = $this->getImagePath($id);

        //delete actual file
        $file = unlink($fullPath);

        //delete imgURL
        $sql = sprintf("DELETE FROM imgURL WHERE id=%d", mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            return false;
        else
            $url=true;

        //delete imgInfo
        $sql = sprintf("DELETE FROM imgInfo WHERE id=%d", mysql_real_escape_string($id));

        $result = $this->db->query($sql);

        if($result === FALSE)
            return false;
        else
            $info=true;


        if($file && $url && $info)
            return true;
        else
            return false;
    }
}

    /*


////////////////////////////////////
//index

sort($fullArray); //sort the array in ascending order
$fullArray = array_reverse($fullArray); //reverse array to descending order

//if the page is not set in the URL or is not a number
//set it to page 1
if($page == NULL || !is_numeric($page))g
    $page = 1; //it must be page 1

//get the number of pages that are available
$availablePages = getNumPages($fullCount, $imagesPerPage);

//if a number < 1 or greater than the number
//of available pages shows up then set
//the page number to 1
if($page > $availablePages || $page < 1)
    $page = 1;

////////////////////////////////////////////////
//settings

$title = "Airpic"; //title of page
$fullDir="images/"; //image directory
$thumbDir="thumbs/"; //thumbnail directory

$imagesPerPage = 6; //must be multiple of 3

} /* end of class Airpic */

?>
