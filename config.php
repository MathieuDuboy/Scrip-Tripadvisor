<?php
// a modifier lors de chaque nouveau scrap
$url_ville_restaurant = "https://www.tripadvisor.fr/Restaurants-g196505-Arcachon_Gironde_Nouvelle_Aquitaine.html";
$derniere_page_pagination = 6;

define("DB_HOST", "localhost"); //Databse Host.
define("DB_USER", "root"); //Databse User.
define("DB_PASS", "root"); //database password.
define("DB_NAME", "trip_scrap"); //database Name.

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_errno > 0) {
    die('Unable to connect to database 1 [' . $db->connect_error . ']');
}
$db->set_charset("utf8");
?>
