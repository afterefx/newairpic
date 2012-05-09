<?php

require_once 'includes/backend.php';

if($Airpic->session->sessionExists())
{
    $userid = $Airpic->session->user->getUserID();
    $max = $Airpic->image->getNumPagesForUser($userid);

    if(isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] <= $max && $_GET['p'] > 0)
        $pageNum = $_GET['p'];
    else
        $pageNum = 1;

    $id = $Airpic->image->getImageIdStackForPageNum($userid, $pageNum);

    $output="<div id=\"gallery\">";
    for($i=0; $i < count($id); $i++)
    {
        if($i==0) //start first row
            $output.="<div class=\"row\">";
        if($i==3)//end first and start second row
            $output.="</div><div class=\"row\">";

        $current = $id[$i];
        $viewURL = $Airpic->image->getViewURL($current);
        $fullPath = $Airpic->image->getImagePath($current);

        //place image
       $output.=<<<HTML
        <div class="pic"><a href="image.php?i=$viewURL"><img src="$fullPath" alt="Image $viewURL" /></a></div>
HTML;

    }

//ends last row & gallery
$output.="</div></div>";

//who page links
if($max != 1 )
{
    $output.="<div id=\"pageLinks\"><p>";

    //previous page
    if($pageNum > 1)
        $output.="<a href=\"gallery.php?p=" . ($pageNum-1) . "\">&lt;&lt;</a>";
    if($pageNum < $max)
        $output.="<a href=\"gallery.php?p=" . ($pageNum+1) . "\">&gt;&gt;</a>";

    $output.="</p></div>";

    //next page
}

$Airpic->page->displayPage($output,"Gallery");

}
else
    $Airpic->common->redirect("index.php");
?>
