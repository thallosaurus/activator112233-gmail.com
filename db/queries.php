<?php
$GLOBALS["lm"]["QRY"]["find_in_radius"]
//= "SELECT id, name, db_lat, db_lon, ROUND(6371 * ACOS( COS( RADIANS( db_lat ) ) * COS( RADIANS( :u_lat ) ) * COS( RADIANS( :u_lon ) - RADIANS( db_lon ) ) + SIN( RADIANS( db_lat ) ) * SIN( RADIANS( :u_lat ) ) ), 4) AS distance FROM coordinates HAVING distance <= :u_rad ORDER BY distance ASC";
= "SELECT coordinates.id, coordinates.ts AS timestamp, coordinates.db_lat AS db_lat, coordinates.db_lon AS db_lon, posts.title, posts.category, posts.type, ROUND(6371 * ACOS( COS( RADIANS( db_lat ) ) * COS( RADIANS( :u_lat ) ) * COS( RADIANS( :u_lon ) - RADIANS( db_lon ) ) + SIN( RADIANS( db_lat ) ) * SIN( RADIANS( :u_lat ) ) ), 4) AS distance, (UNIX_TIMESTAMP()*1000 - UNIX_TIMESTAMP(coordinates.ts)*1000) AS age FROM coordinates JOIN posts ON coordinates.id = posts.id WHERE (:ignorewhere IS NULL OR posts.category LIKE :cat) HAVING distance <= :u_rad ORDER BY timestamp DESC";

$GLOBALS["lm"]["QRY"]["post_data"]
    = "INSERT INTO `posts`(`title`, `content`, `type`, `category`) VALUES (:title, :content, :type, :category)";

$GLOBALS["lm"]["QRY"]["register_geolocation"]
    = "INSERT INTO `coordinates`(`id`, `db_lon`, `db_lat`) VALUES (:id, :lon, :lat)";

$GLOBALS["lm"]["QRY"]["open"]
    = "SELECT `content` AS link, `type` FROM `posts` WHERE `id` = :id";

$GLOBALS["lm"]["QRY"]["cat"]
    = "SELECT `id`, `value`, `icon` FROM `categories`";

$GLOBALS["lm"]["QRY"]["search_for_cat"]
    = "SELECT `id` FROM `categories` WHERE `value` = :name";

$GLOBALS["lm"]["QRY"]["search_for_cat_by_id"]
    = "SELECT `id`, `value`, `icon` FROM `categories` WHERE `id` = :id";

$GLOBALS["lm"]["QRY"]["create_cat"]
    = "INSERT INTO `categories`(`value`, `icon`) VALUES (:name, :icon)";
?>
