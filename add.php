<?php
header('Content-Type: text/html; charset=utf-8');
require('controle.php');
require('db.php');
require('vues.php');

$connexion = connecte_db();

/* Recupere l'id de l'auteur ou le cree si besoin */
$idAuteur = getCreeAuteur($connexion, $nomA, $email);

/* gere l'upload */
$dest = 'logos/'.basename($_FILES['logo']['name']);
if (!move_uploaded_file($_FILES['logo']['tmp_name'], $dest)) {
  die('Erreur dupload de '.$_FILES['logo']['tmp_name']." vers $dest");
}
/* insere l'article */
insereArticle($connexion, $categorie, $titre, $description, $dest, $article, $idAuteur);

?>