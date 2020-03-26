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
    $a = array("t_categories", "t_coordinates", "t_posts");

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
?>