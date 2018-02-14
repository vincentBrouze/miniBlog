<?php

$action="listeArticles";
$currCateg = -1;

if (isset($_POST['action'])) {
  $action = $_POST['action'];
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
    die('Erreur de formulaire');
  }
} else { /* Pas de $_POST */
  if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == "affArticle") {
      if (isset($_GET['id'])) {
	$id = $_GET['id'];
      } else $action = "listeArticles";
    }
    if ($action == "listCateg") {
      if (isset($_GET['id'])) {
	$currCateg = $_GET['id'];
      } else $action = "listeArticles";
    }     
  }
}
?>