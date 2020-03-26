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
    <link rel="manifest" href="manifest.webmanifest.php">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/icon512.png">

    <title><?php echo $thread[0]["title"]?></title>
</head>
<body>
    <h1><?php echo $thread[0]["title"]?></h1>
    <span><?php echo $thread[0]["content"]?>
    <span><?php echo "Post-Id: " . $id?></span>
    <form action="/db/call.php" method="post">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?php print $id?>">
        <input type="submit" value="Löschen">
    </form>
</body>
</html>