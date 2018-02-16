<?php
header('Content-Type: text/html; charset=utf-8');
require('controle.php');
require('db.php');
require('vues.php');

$connexion = connecte_db();

if ($action == "listeArticles") {
  /* Liste des articles */
  $deb = ($pageCour - 1)*$intervalle;
  $nbp = getNbPages($connexion, $intervalle);
  $res = getListeArticles($connexion, $deb);
  afficheListeArticles($res, $pageCour, $nbp);
} else if ($action == "menu") {
  afficheNav($connexion, "", -1);
} else if ($action == "affArticle") {
  afficheArticle($connexion, $id);
} else if ($action == "listCateg") {
  $nbp = getNbPages($connexion, $intervalle, $currCateg);
  $res = getListeArticles($connexion, 1, 100, $currCateg);
  afficheListeArticles($res, 1, 1);
} else if ($action == 'add') {
  afficheFormNouv($connexion);
} else if ($action == "cherche") {
  $res = getChercheArticles($connexion, $critere);
  afficheListeArticles($res, 1, 1);
} else if ($action == 'logout') {
  unset($_SESSION['login']);
  unset($_SESSION['id']);
  echo true;
} else if ($action == 'traiteForm') {
  if (isset($_SESSION['id']))
    $idAuteur = $_SESSION['id'];
  else {
    echo json_encode(array(false, 
			   "Session expirée, veuillez vous reconnecter."));
    die('');
  }
  //$idAuteur = getCreeAuteur($connexion, $nomA, $email);
  if ($idAuteur === false) {
    echo json_encode(array(false, "Impossible d'authentifier l'auteur."));
  }
  if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != '') {
    $dest = 'logos/'.basename($_FILES['logo']['name']);
    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $dest)) {
      echo json_encode(array(false, "Probleme d'upload de fichier."));
      die();
    }
  } else $dest = '';
  /* insere l'article */
  $res = insereArticle($connexion, $categorie, $titre, $description, $dest, $article, $idAuteur);
  if ($res === false) {
    echo json_encode(array(false, "Probleme d'insertion d'article"));    
  } else echo json_encode(array(true, ''));
  /* Fin traiteForm*/
} else if ($action == 'login') {
  $auth = authenticate($connexion, $login, $mdp);
  if ($auth > 0) {
    $_SESSION['login'] = $login;
    $_SESSION['id'] = $auth;
    echo json_encode(true);
  } else echo json_encode(false, "Impossible d'ajouter un article");
}

?>