<?php
/********************************************************************************
 * 
 * Get Data from database in a radius:
 * 
 * MySQL-Code:
 * SELECT id, 
 * ( 6371 * 
 *     ACOS( 
 *         COS( RADIANS( db_latitude ) ) * 
 *         COS( RADIANS( $user_latitude ) ) * 
 *         COS( RADIANS( $user_longitude ) - 
 *         RADIANS( db_longitude ) ) + 
 *         SIN( RADIANS( db_latitude ) ) * 
 *         SIN( RADIANS( $user_latitude) ) 
 *     ) 
 * ) 
 * AS distance FROM the_table HAVING distance <= $the_radius ORDER BY distance ASC"
 * 
 *
 * Explanation:
 * db_latitude = database latitude field
 * db_longitude = database longitude field
 * $user_latitude = browser latitude coördinate
 * $user_longitude = browser longitude coördinate
 * $the_radius = the radius that you want to search in
 * 
 ********************************************************************************/

require("db.php");

define("FIXED_RADIUS", 15);
define("FIXED_USER_LAT", 12.083598); //Franz-Zebisch-Straße, Weiden i. d. Opf
define("FIXED_USER_LON", 49.682170);
define("LINK",0);
define("PHOTO",2);
define("TEXT",1);

define("MAX_ITEMS", 25);


connectDB(false);

function get_in_radius($lat, $lon, $rad, $cat, $page)
{
    //$start = $page * MAX_ITEMS;
    //$end = $page * MAX_ITEMS + MAX_ITEMS;
    
    //$start = 0;
    //$end = 25;
    $filter = ($cat > 0) ? 1 : NULL;
    //$cat = ($cat == -1) ? "\"%\"" : $cat;

    //print("page: " . $start . PHP_EOL . "end: " . $end);
    $stmt = dbExec("find_in_radius",
        array(
            "u_rad" => $rad,
            "u_lat" => $lat,
            "u_lon" => $lon,
            "ignorewhere" => $filter,
            "cat" => $cat
        )
    );

    //$stmt->bindParam(":start", $start, PDO::PARAM_INT);
    //$stmt->bindParam(":end", $end, PDO::PARAM_INT);

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($res);

    $arr = array();
    $res[] = get_fake_post();
    foreach ($res as $r)
    {
        $then = strtotime($r["timestamp"]);
        $now = time();

        $age = ($now - $then) / 1000;

        $arr[] = array($r["title"], $r["category"], $age . " s", $r["distance"] . "km");
    }

    return array("posts" => $res, "cat" => get_categories());
}

function get_category_id($name)
{
    //echo $name;
    //search for category id
    $cat = dbExec("search_for_cat", array(
        "name" => $name
    ))->fetchAll(PDO::FETCH_ASSOC);

    //print_r($cat);

    if (sizeof($cat) == 0)
    {
        dbExec("create_cat", array("name" => $name, "icon" => "folder"));
        return get_last_insert_id();
    }
    else
    {
        return $cat[0]["id"];
    }
}

function post_data($title, $content, $type, $media, $cat, $lat, $lon)
{
    if ($media != null)
    {
        //btoa(unescape(encodeURIComponent(data.result))));
        $urldecoded = urldecode($media);
    }

    $c = dbExec("post_data",
        array(
            "title" => $title,
            "content" => $content,
            "type" => $type,
            "category" => get_category_id($cat)
        )
    );

    $id = get_last_insert_id();

    //register geolocation:
    dbExec("register_geolocation",
        array(
            "id" => $id,
            "lat" => $lat,
            "lon" => $lon
        )
    );

    return $id;
}

//just a test for now
function open($id)
{
    $res = dbExec("open",
        array(
            "id" => $id
        )
    )->fetchAll(PDO::FETCH_ASSOC);

    if (sizeof($res) <= 0) throw new Exception("Post with Id " . $id . " wasn't found.");
    return $res[0];
}

function get_categories()
{
    $res = dbExec("cat")->fetchAll(PDO::FETCH_ASSOC);
    //return new Categories($res);
    return $res;
}

function get_category_by_id($id)
{
    $res = dbExec("search_for_cat_by_id", array("id" => $id))->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function delete($id)
{
    dbExec("delete_post", array("id" => $id))->fetchAll(PDO::FETCH_ASSOC);
    header("Location: ../index.php");
}

function nukeTableDebug()
{
    $a = array("t_categories", "t_coordinates", "t_posts", "t_comments");

    for ($i = 0; $i < sizeof($a); $i++)
    {
        dbExec($a[$i])->fetchAll(PDO::FETCH_ASSOC);
    }

    //restore "all" category
    dbExec("restore_default_categories")->fetchAll(PDO::FETCH_ASSOC);

    echo "Whole Database was cleared";

    header("Location: ../index.php");
    die();
}

function get_fake_post()
{
    $age = time() - strtotime("2020-03-26 04:20:59");
    $content = "Hey you, thanks for stepping bye! This is just a Test mockup social networking app. Its source code is on GitHub, so be sure to check it out! Use this Web-App on a smartphone. This was tested on a Samsung Galaxy S8 Plus and iPhone 11 Pro. Do whatever the fuck you want here, but be sure to nuke the database afterwards. If this is a dev build, you'll find a link to the database nuker. It automatically truncates all tables it finds and resets the whole database. Whats left is the category for #all and this note, because its hardcoded, lol. You can post here, but be sure to understand the twist - you need to use HTTPs for this - all posts you make get tied to your GPS Location. When you post them, they just go in my database and get stored there. Only there. When you wipe the whole database, everything gets deleted. Check out my Source Code for proof.";
    //id, timestamp, db_lat, db_lon, title, category, type, AS distance, age 
    return array(
        "id" => -2,
        "timestamp" => "2020-03-26 04:20:59",   //sorry boys, theres no 69th second :(
        "db_lat" => 0.00000,
        "db_lon" => 0.00000,
        "title" => "Readme, please",
        "content" => $content,
        "category" => 1,
        "type" => 1,
        "distance" => 8,
        "age" => $age
    );
}

function get_comments_for_post($id)
{
    $comments = dbExec("get_comments_for_id", array("id" => $id))->fetchAll(PDO::FETCH_ASSOC);
    return $comments;
}

function add_comment($id, $content)
{
    //:id, NOW(), :body, :from_user
    dbExec("insert_comment", array(
        "id" => $id,
        "body" => $content,
        "from_user" => "Anonymous"
    ));
}
?>