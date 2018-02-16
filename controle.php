<?php

session_start();

$action="listeArticles";
$pageCour = 1;
$intervalle = 10;
$currCateg = -1;

/* Analyse des parametres */
if (isset($_POST['action'])) {
  $action = $_POST['action'];
  if (isset($_POST['categorie']) && isset($_POST['titre']) &&
    isset($_POST['description']) && isset($_POST['article'])) {
    $categorie = $_POST['categorie'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $article = $_POST['article'];
    if (isset($_POST['articleMEF'])) {
      $article = $_POST['articleMEF'];
    }
  } else {
    die('Erreur de formulaire');
  }
} else { /* Pas de $_POST */
  if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == "listeArticles") {
      if (isset($_GET['page'])) {
	$pageCour = $_GET['page'];
      } 
    }
   
    if ($action == "affArticle") {
      if (isset($_GET['id'])) {
	$id = $_GET['id'];
      } else $action = "listeArticles";
    }
    if ($action == "listCateg") {
      if (isset($_GET['id'])) {
	$currCateg = $_GET['id'];
	if ($currCateg == -1) $action = "listeArticles";
      } else $action = "listeArticles";
    }
    if ($action =="cherche") {
      if (isset($_GET['string'])) {
	$critere = $_GET['string'];
      } else $action = "listeArticles";
    }
    if ($action =="login") {
      if (isset($_GET['login']) && isset($_GET['mdp'])) {
	$login = $_GET['login'];
	$mdp = $_GET['mdp'];
      } else $action = "listeArticles";
    }
    if ($action == 'add') {
      /*
      if (!isset($_SESSION['login'])) {
	$action = "listeArticles";
      }
      */
    }
  }
}
?>