<?php
header('Content-Type: text/html; charset=utf-8');
require('controle.php');
require('db.php');
require('vues.php');

$connexion = connecte_db();
afficheHeader();
die('');
/* Le menu, en fonction de l'action*/
afficheNav($connexion, $action, $currCateg);

if ($action == "listeArticles") {
  /* Liste des articles */
  $res = getListeArticles($connexion);
  afficheListeArticles($res);
} else if ($action == "ecrire") {
  afficheFormNouv($connexion);
} else if ($action =="affArticle") {
  afficheArticle($connexion, $id);
} else if ($action == "listCateg") {
  $res = getListeArticles($connexion, $currCateg);
  afficheListeArticles($res);
}

?>