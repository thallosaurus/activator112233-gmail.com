<?php
require("./db/lib/config.php");
require("./db/db_test.php");

$id = isset($_GET["id"])    ?   trim($_GET["id"])   :   -1;

if ($id == -1)
{
    header("Location: index.php");
}

$thread = dbExec("get_whole_topic", array("id" => $id))->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <link rel="manifest" href="manifest.webmanifest">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/icon512.png">

    <title><?php echo $thread[0]["title"]?></title>
</head>
<body>
    <h1><?php echo $thread[0]["title"]?></h1>
    <span><?php echo $thread[0]["content"]?>
</body>
</html>