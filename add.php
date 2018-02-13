<?php
header('Content-Type: text/html; charset=utf-8');
require('controle.php');
require('db.php');
require('vues.php');

if (isset($_POST['categorie']) && isset($_POST['titre']) &&
    isset($_POST['description']) && isset($_POST['article']) &&
    isset($_POST['nomA']) && isset($_POST['email'])) {
  $categorie = $_POST['categorie'];
  $titre = $_POST['titre'];
  $description = $_POST['description'];
  $article = $_POST['article'];
  $nomA = $_POST['nomA'];
  $email = $_POST['email'];
} else {
  die('Erreur deformulaire');
}
/*
var_dump($_FILES);
print_r($_POST);
print_r($_FILES);
*/
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