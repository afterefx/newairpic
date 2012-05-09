<?php
require_once 'includes/backend.php';
require_once('includes/classes/class.Strings.php');

//Check if user is Admmin
if($Airpic->session->user->isAdmin() != 1)
{
    $Airpic->common->redirect("index.php");
}

$server = $_SERVER["PHP_SELF"];
$page = "";
$strings = new Strings("includes/airpicStrings.xml");
$status = "";

//If a ID has been selected to be edited
if(isset($_POST["key"]) && isset($_POST["value"]))
{
    //For Editing
    $status = $strings->editString($_POST["key"], $_POST["value"]);
}

//If an error occurs
if( !empty($status) )
    $page .= "<div id=\"notification\">$status</div>";

$page .="<div class=\"singleArea\">";

//Page Title
$page .= <<<HTML
  <h2>String Editor</h2>
HTML;



//Table
$page .= <<<HTML
      <br />
      <table id="strings" border = "1" width = "100%">
        <tr>
            <th> Edit </th>
            <th> ID </th>
            <th> Value </th>
        </tr>

HTML;

//Get XML Page
$stringXMLArray = $strings->getXML();
//Get Number of Strings in the XML page
$count = $strings->getXMLCount();

//Form
$page .= "<form action=\"settings.php\" method=\"post\">";

//For each Item in the XML file, display the Key and Value
for ($i = 0; $i < $count; $i++)
{
  $key = $stringXMLArray[$i]['key'];
  $value = $stringXMLArray[$i]['value'];
  if($key == "dbpassword")
  {
      $pString="";
      $length = strlen($value);
      for($y=0; $y<$length; $y++)
          $pString.="*";
  $page .= <<<HTML
    <tr>
        <td id="radio"> <input type="radio" name="key" value="$key"/> </td>
        <td> $key </td>
        <td> $pString </td>
    </tr>
HTML;
  }
  else
  {
  $page .= <<<HTML
    <tr>
        <td id="radio"> <input type="radio" name="key" value="$key"/> </td>
        <td> $key </td>
        <td> $value </td>
    </tr>
HTML;
  }
}

$page .= "</table>";


$page .=<<<HTML
  <br /><div id="newVal">New value for selected ID</div>
  <input type="text" name="value"/> <input type="submit" value="Update"/>
  </form>
</div>
HTML;


$Airpic->page->displayPage($page, "Settings");

?>
