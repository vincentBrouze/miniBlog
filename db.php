<?php

/* Connexion à la base, login et mdp en dur
   renvoie l'id de connexion.
*/
function connecte_db() {
  $connexion = mysqli_connect("localhost", 'root', 'admin');
  if (!$connexion) {
    die("<p>connexion échouée.</p>");
  }
  
  if (!mysqli_set_charset($connexion, "utf8")) {
    die("<p>utf-8 échouée.</p>");
  }
  /* Locales pour la conversion de dates */
  if (!mysqli_query($connexion, "SET lc_time_names = 'fr_FR'")) {
    die("<p>Set locales échoué.</p>");
  }
  
  if (!mysqli_select_db($connexion, "MINIBLOG")) {
    die("<p>Sélection de db ratée.</p>");
  }
  return $connexion;
}

function getListeArticles($connexion, $categ = -1) {
  if ($categ == -1)
    $req = "SELECT Article.id as id, Article.titre as titre, Article.description as description, DATE_FORMAT(Article.dateCreation, '%d %M %Y') as dateC, Article.logo as logo, Auteur.nom as nomA, Categorie.nom as nomC FROM Article INNER JOIN Auteur on Article.idAuteur = Auteur.id INNER JOIN Categorie on Article.idCategorie = Categorie.id ORDER BY Article.dateCreation DESC";
  else 
    $req = "SELECT Article.id as id, Article.titre as titre, Article.description as description, DATE_FORMAT(Article.dateCreation, '%d %M %Y') as dateC, Article.logo as logo, Auteur.nom as nomA, Categorie.nom as nomC FROM Article INNER JOIN Auteur on Article.idAuteur = Auteur.id INNER JOIN Categorie on Article.idCategorie = Categorie.id WHERE Categorie.id = '$categ' ORDER BY Article.dateCreation DESC";
  $res = mysqli_query($connexion, $req);
  return $res;
}

function getCategories($connexion) {
  $req = "SELECT id, nom FROM Categorie ORDER BY nom ASC";
    
  return mysqli_query($connexion, $req);
}

function getCreeAuteur($connexion, $nom, $mail) {
  $req = "SELECT id, nom, email FROM Auteur WHERE email='$mail'";

  $res =  mysqli_query($connexion, $req);
  if ($res) {
    if (mysqli_num_rows($res) > 0) {
      $ligne = mysqli_fetch_assoc($res);
      echo "Id existante : ".$ligne['id'];
      return $ligne['id'];
    } else {
      $nom = mysqli_real_escape_string($connexion, $nom);
      $mail = mysqli_real_escape_string($connexion, $mail);
      $req = "INSERT INTO Auteur (nom, email) VALUES ('$nom', '$mail')";
      $res = mysqli_query($connexion, $req);
      if ($res) return mysqli_insert_id($connexion);
      else die("Erreur de requete $req" . mysqli_error($connexion));
    }
  } else {
    die("Erreur de requete $req" . mysqli_error($connexion));
  }
  
  
}

function insereArticle($connexion, $idCateg, $titre, $desc, $logo, $contenu, $idA) {
  $contenu = mysqli_real_escape_string($connexion, $contenu);
  $titre = mysqli_real_escape_string($connexion, $titre);
  $desc = mysqli_real_escape_string($connexion, $desc);
  $logo = mysqli_real_escape_string($connexion, $logo);

  $req = "INSERT INTO Article (titre, description, logo, contenu, idCategorie, idAuteur) VALUES ('$titre', '$desc', '$logo', '$contenu', '$idCateg', '$idA')";

   $res = mysqli_query($connexion, $req);
   if (!$res) {
     die("Erreur de requete $req" . mysqli_error($connexion));
   }
}

function getArticle($connexion, $id) {
  $req = "SELECT Article.id as id, Article.titre as titre, Article.description as description, DATE_FORMAT(Article.dateCreation, '%d %M %Y') as dateC, Article.contenu as contenu, Article.logo as logo, Auteur.nom as nomA, Categorie.nom as nomC FROM Article INNER JOIN Auteur on Article.idAuteur = Auteur.id INNER JOIN Categorie on Article.idCategorie = Categorie.id WHERE Article.id = '$id'";

  $res = mysqli_query($connexion, $req);
  if (!$res) {
      die("Erreur de requete $req" . mysqli_error($connexion));
  }
  return mysqli_fetch_assoc($res);
}


?>