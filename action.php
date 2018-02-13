<?php
header('Content-Type: text/html; charset=utf-8');
require('controle.php');
require('db.php');
require('vues.php');

$connexion = connecte_db();

if ($action == "listeArticles") {
  /* Liste des articles */
  $res = getListeArticles($connexion);
  afficheListeArticles($res);
} else if ($action == "menu") {
  afficheNav($connexion, "", -1);
} else if ($action == "affArticle") {
  afficheArticle($connexion, $id);
} else if ($action == "listCateg") {
  $res = getListeArticles($connexion, $currCateg);
  afficheListeArticles($res);
}

?>