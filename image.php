<?php

include_once 'includes/backend.php';

if($Airpic->session->sessionExists() && isset($_GET['i']))
{
    $url = $_GET['i'];
    $id = $Airpic->image->getImageId($url);
    $fullPath = $Airpic->image->getImagePath($id);
    $size = $Airpic->image->getFileSize($id);
    $viewURL = $Airpic->image->getViewURL($id);
    $Airpic->image->addView($id);
    $Airpic->image->addBandwidth($id);
    $count = $Airpic->image->getNumViewsImage($id);
    $bandwidth = $Airpic->image->getBandwidthImage($id);
    $option = $Airpic->image->getImageOption($url);
    if($option == 'd' || isset($_POST['id']) || $Airpic->session->user->isAdmin())
    {

        if(isset($_POST['id']) && isset($_POST['del']) && $_POST['del'] == "on")
        {
            if($Airpic->image->removeImage($id))
                $output=<<<HTML
                    <div class="singleArea">
                    <p>
                    Successfully deleted image.
                    </p>
                    <p>
                    <a href="/gallery">Go back to the gallery</a>
                    </p>
                    </div>
HTML;
            else
                $output=<<<HTML
                    <div class="singleArea"><p>Deleteion FAILED!</p></div>
HTML;
        }
        else
        {

        $output=<<<HTML
            <div class="singleArea">
                <a href="http://airpic.org/$fullPath"><img src="$fullPath" alt="Image $viewURL" /></a>
                <form method="post" action="image.php?i=$url">
            <p id="file">
            File size: $size Kb<br />
            Views: $count<br />
            Bandwidth: $bandwidth Kb<br />
            Are you sure you want to delete?<br />
            <input type="checkbox" name="del" />Yes I am positive<br />
            <input type="submit" value="Delete" />
            <input type="hidden" name="id" value="$id" />
            </p>
            </form>
            </div>

HTML;
        }
    }
    elseif($option == 'v')
    {
        $output=<<<HTML
            <div class="singleArea">
                <a href="http://airpic.org/$fullPath"><img src="$fullPath" alt="Image $viewURL" /></a>
            <p id="file">
            File size: $size Kb<br />
            Views: $count<br />
            Bandwidth: $bandwidth Kb<br />
            </p>
            </div>

HTML;
    }
    else
    {
        $output=<<<HTML
            <div class="singleArea">
            <p> Image with id $id does not exist</p>
            </div>
HTML;
    }


$Airpic->page->displayPage($output, "Image");
}
else
    header("Location: http://www.airpic.org/gallery");
?>
