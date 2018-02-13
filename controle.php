<?php

$action="listeArticles";
$currCateg = -1;
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
?>