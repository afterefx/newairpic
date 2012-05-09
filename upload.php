<?php

require_once 'includes/backend.php';

if($Airpic->session->sessionExists())
{
    if(!empty($_FILES))
    {
        if (( ($_FILES["path"]["type"] == "image/jpeg")
            || ($_FILES["path"]["type"] == "image/png")))
        {
            if ($_FILES["path"]["error"] > 0)
            {
                $output = "<div id=\"notification\"><p>";
                $output .= "Error uploading file: " . $_FILES["file"]["error"];
                $output .="</p></div>";
            }
            else
            {
                //echo "Upload: " . $_FILES["path"]["name"] . "<br />";
                //echo "Type: " . $_FILES["path"]["type"] . "<br />";
                //echo "Size: " . $_FILES["path"]["size"] . "   /1024=Kb<br />";
                //echo "Temp file: " . $_FILES["path"]["tmp_name"] . "<br />";

                $userid = $Airpic->session->user->getUserID();
                $size = $_FILES["path"]["size"];

                if($_FILES["path"]["type"] == "image/jpeg")
                    $newFilename = $userid . time() . ".jpg";
                else
                    $newFilename = $userid . time() . ".png";

                $fullPath = "images/" . $newFilename;

                if (file_exists($fullPath))
                {
                    $output = "<div id=\"notification\"><p>";
                    $output .= "File already exists";
                    $output .="</p></div>";
                }
                else
                {
                    if(move_uploaded_file($_FILES["path"]["tmp_name"], $fullPath))
                    {
                        $id=$Airpic->image->addImage($userid, $newFilename, $size);
                        $imageUploaded = true;

                    }
                    else
                    {
                        $output = "<div id=\"notification\"><p>";
                        $output .= "Failed to move file from temp";
                        $output .="</p></div>";
                    }
                }
            }
        }
        else
        {
            $output = "<div id=\"notification\"><p>";
            $output .= "Invalid file";
            $output .="</p></div>";
        }
    }


//=================================
//=================================
//====     Initial page       =====
//=================================
//=================================
    if(!isset($output))
        $output="";

    $output.="<div class=\"singleArea\">";

    if(isset($imageUploaded) && $imageUploaded == true)
    {
        $fullPath = $Airpic->image->getImagePath($id);
        $size = $Airpic->image->getFileSize($id);
        $viewURL = $Airpic->image->getViewURL($id);
        $delURL = $Airpic->image->getDeleteURL($id);
        $Airpic->image->addView($id);
        $Airpic->image->addBandwidth($id);
        $count = $Airpic->image->getNumViewsImage($id);
        $bandwidth = $Airpic->image->getBandwidthImage($id);
        $output.=<<<HTML
            <img src="$fullPath" alt="Image $viewURL" />
            <p>
            File size: $size Kb<br />
            View URL:
            <a href="http://airpic.org/image.php?i=$viewURL">http://airpic.org/image.php?i=$viewURL</a><br />
            Deletion URL:
            <a href="http://airpic.org/image.php?i=$delURL">http://airpic.org/image.php?i=$delURL</a><br />
            Views: $count<br />
            Bandwidth: $bandwidth Kb<br />
            </p>
            <p>
            <a href="upload.php">Upload new image</a>
            </p>


HTML;
    }
    else
    {
        $output.= <<<HTML
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="path" />
                <input type="submit" value="Upload" />
            </form>
HTML;
    }
        $output.="</div>";

    $Airpic->page->displayPage($output,"Upload");
}
else
    $Airpic->common->redirect("index.php");


?>
