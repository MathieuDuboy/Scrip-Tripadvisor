<?php
include ('html_dom_parser.php');
include ('config.php');

$debut = $_GET["debut"];
$fin = $_GET["fin"];

function getbody($filename)
{
    $file = file_get_contents($filename);
    $dom = new DOMDocument;
    $dom->loadHTML($file);
    return $dom;
}

// Aller chercher dans la bdd le lien avec l'id $debut
$sql = "SELECT * FROM liens_restaurants WHERE id = $debut ";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
$url_resto = $row["url"];
// scrapper
$url_a_scrapper_resto = "https://www.tripadvisor.fr" . $url_resto;
$bodycontent_resto = getbody($url_a_scrapper_resto);

$addresse = "";
$locality = "";
$pays = "";
$note = "";
$tel = "";
$email = "";
$nom_restaurant = "";
$cuisine = "";
$images = "";

// REchercher sur les SPAN
foreach ($bodycontent_resto->getElementsByTagName('span') as $span)
{
    if ($span->getAttribute('class') == "street-address")
    {
        $addresse = $span->nodeValue;
        echo $addresse . '<br />';
    }
    if ($span->getAttribute('class') == "locality")
    {
        $locality = $span->nodeValue;
        echo $locality . '<br />';
    }
    if ($span->getAttribute('class') == "country-name")
    {
        $pays = $span->nodeValue;
        echo $pays . '<br />';
    }
    $la_classe = $span->getAttribute('class');
    if (strpos($la_classe, 'restaurants-detail-overview-cards-RatingsOverviewCard__overallRating') !== false)
    {
        $note = $span->nodeValue;
        echo $note . '<br />';
    }
}

// REchercher sur les liens
foreach ($bodycontent_resto->getElementsByTagName('a') as $liens)
{
    $le_lien = $liens->getAttribute('href');
    if (strpos($le_lien, 'tel:') !== false)
    {
        $tel = str_replace("tel:", "", $le_lien);
        echo $tel . '<br />';
    }

    if (strpos($le_lien, 'mailto:') !== false)
    {
        preg_match("/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/i", $le_lien, $matches);
        $email = $matches[0];
        echo $email . '<br />';
    }

}

// rechercher sur les h1
foreach ($bodycontent_resto->getElementsByTagName('h1') as $h1)
{
    //echo $h1->nodeValue.' / '.$h1->getAttribute('class').'<br />';
    if ($h1->getAttribute('class') == "ui_header h1")
    {
        $nom_restaurant = $h1->nodeValue;
        echo $nom_restaurant . '<br />';
    }
}

// rechercher sur les Divs
$tab_cuisine = [];
foreach ($bodycontent_resto->getElementsByTagName('div') as $div)
{
    $la_classe = $div->getAttribute('class');
    if (strpos($la_classe, 'restaurants-detail-overview-cards-DetailsSectionOverviewCard__tagText') !== false)
    {
        if ($div->nodeValue != '') array_push($tab_cuisine, $div->nodeValue);
    }
    //print_r($cuisine).'<br />';

}
$cuisine = implode(", ", $tab_cuisine);
echo $cuisine;

// rechercher sur les images
// problème ...
/*
$les_images = [];
foreach($bodycontent_resto->getElementsByTagName('img') as $img) {
  $la_classe = $img->getAttribute('class');
  echo $la_classe.'<br />';
  if (strpos($la_classe, 'basicImg') !== false) {
    $la_source = $img->getAttribute('src');
    echo $la_source.'<br />';

    if (strpos($la_source, 'https://media-cdn') !== false)  {
      array_push($les_images, $la_source);
      echo $la_source.'<br />';
    }
  }
}
$images = implode(";", $les_images);
echo $images;
*/
// Stocker les infos
$sql = "INSERT INTO `informations_restaurants` (`id`, `addresse`, `ville`, `pays`, `note`, `tel`, `email`, `nom_restaurant`, `cuisine`, `images`)
VALUES (NULL, '" . $addresse . "', '" . $locality . "', '" . $pays . "', '" . $note . "', '" . $tel . "', '" . $email . "', '" . $nom_restaurant . "', '" . $cuisine . "', '" . $images . "');";
$result = mysqli_query($db, $sql);

// Passer au suivant => redirection
$debut = $debut + 1;
if ($debut == $fin)
{
?>
    <meta http-equiv="refresh" content="2;url=fin.php">
  <?php
}
else
{
?>
    <meta http-equiv="refresh" content="2;url=resto.php?debut=<?php echo $debut; ?>&fin=<?php echo $fin; ?>">
  <?php
}

// Si $debut == $fin ou $fin+1 => Redirection vers la fin.php

?>
