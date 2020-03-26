<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require("./lib/config.php");

header('Content-Type: application/json');
require("../vendor/autoload.php");
require("./db_test.php");
require("./lib/answer.php");

function run()
{
    $action = isset($_POST["action"])    ?   trim($_POST["action"])   :   "error";
    $title = isset($_POST["title"])      ?   trim($_POST["title"])    :   "no title specified";
    $content = isset($_POST["content"])  ?   trim($_POST["content"])  :   "content";
    $type = isset($_POST["type"])   ?   trim($_POST["type"])    :   0;
    $category = isset($_POST["category"])   ?   trim($_POST["category"])    :   "all";
    $lat = isset($_POST["lat"])  ?   trim($_POST["lat"])  :   0.0;
    $lon = isset($_POST["lon"])  ?   trim($_POST["lon"])  :   0.0;
    $radius = isset($_POST["radius"])   ?   trim($_POST["radius"]) : 25;
    $id = isset($_POST["id"])   ?   trim($_POST["id"])  :   -1;
    $media = isset($_POST["media"]) ?   trim($_POST["media"])   :   null;
    $page = isset($_POST["page"])   ?   trim($_POST["page"])    :   1;
    $cat = isset($_POST["cat"])   ?   trim($_POST["cat"])    :   -1;

    $nuke = isset($_GET["nuke"]) ? trim($_GET["nuke"])  : null;

    if ($nuke != null)
    {
        nukeTableDebug();
    }

    //file_put_contents("php://stderr", "something happened!");

    switch ($action)
    {
        case "update":
            return get_in_radius($lat, $lon, $radius, $cat, $page);

        case "post":
            return post_data($title, $content, $type, $media, $category, $lat, $lon);

        case "open":
            return open($id);

        case "delete":
            return delete($id);

        case "get_categories":
            return get_categories();

        default:
            throw new Exception("Unknown action");
        break;
    }
}

$answer = new Answer();

try {
    $answerData = run();
    $answer->setContent($answerData);
    //$answer->addData("row_data", array_keys($answerData));
    $answer->addData("DEBUG", $_POST);
    echo $answer->encode();
}
catch (Exception $e)
{
    print_r($e);
    $answer->setContent($e);
}
?>