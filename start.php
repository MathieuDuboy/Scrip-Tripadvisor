<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

    header('Content-Type:text/html; charset=UTF-8');

    function getbody($filename) {
        $file = file_get_contents($filename);
        $dom = new DOMDocument;
        $dom->loadHTML($file);
        return $dom;
    }

    // ajout du fichier permettant de parser le DOM
    include('html_dom_parser.php');
    include('config.php');

    if(isset($_GET["nb"])) $nb = $_GET["nb"]; else $nb = 0;
    if(isset($_GET["debut"])) $id_actuel = $_GET["debut"];


    if($nb == 0) {
      $bodycontent = getbody($url_ville_restaurant);
      // Récupérer l'id actuel de la table liens_resto
      // $id_actuel_resto
      $sql = "SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'liens_restaurants'";
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_array($result);
      $id_actuel = $row["auto_increment"];
      echo $id_actuel;
    }else {
      $tab_url = explode("-", $url_ville_restaurant);
      $oa = 30*$nb;
      $new_url = $tab_url[0].'-'.$tab_url[1].'-oa'.$oa.'-'.$tab_url[2];
      echo $new_url;
      $bodycontent = getbody($url_ville_restaurant);

    }
    $tab_urls_resto = [];
    $arr = $bodycontent->getElementsByTagName("a"); // DOMNodeList Object
    foreach($arr as $item) { // DOMElement Object
      $href =  $item->getAttribute("href");
      //echo $href.'<br />';
      if ( (strpos($href, 'Restaurant_Review-') !== false) ) {
        if ( (strpos($href, '#REVIEWS') === false)) {
           array_push($tab_urls_resto, $href);
        }
      }
    }

    // Suppresion des doublons
    $tab_urls_resto = array_unique($tab_urls_resto);
    print_r($tab_urls_resto);

    // stockage des données
    foreach($tab_urls_resto as $vrai_url) {
      $sql    = "INSERT INTO `liens_restaurants` (`id`, `url`) VALUES (NULL, '".$vrai_url."');";
      $result = mysqli_query($db, $sql);
    }

    $nb = $nb+1;
    if($nb == $derniere_page_pagination+1) {
      // rechercher le dernier id ajouté dans la table
      $last_id = mysqli_insert_id($db);
      ?>  <meta http-equiv="refresh" content="2;url=resto.php?debut=<?php echo $id_actuel; ?>&fin=<?php echo $last_id; ?>"><?php
    }else {
      ?>  <meta http-equiv="refresh" content="2;url=start.php?nb=<?php echo $nb; ?>&debut=<?php echo $id_actuel; ?>"><?php
    }
?>
