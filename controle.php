<?php

session_start();

$action="listeArticles";
$currCateg = -1;

if (isset($_POST['action']) && isset($_SESSION['login'])) {
  $action = $_POST['action'];
  if (isset($_POST['categorie']) && isset($_POST['titre']) &&
    isset($_POST['description']) && isset($_POST['article'])) {
    $categorie = $_POST['categorie'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $article = $_POST['article'];
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