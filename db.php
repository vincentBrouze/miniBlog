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

/* Renvoie un pointeur mysqli vers les articles ou $crit apparait dans :
- le titre ou 
- la description ou
- le nom de l'auteur.
*/
function getChercheArticles($connexion, $crit) {
  $chaine = mysqli_real_escape_string($connexion, $crit);
  $req = "SELECT Article.id as id, Article.titre as titre, Article.description as description, DATE_FORMAT(Article.dateCreation, '%d %M %Y') as dateC, Article.logo as logo, Auteur.nom as nomA, Categorie.nom as nomC FROM Article INNER JOIN Auteur on Article.idAuteur = Auteur.id INNER JOIN Categorie on Article.idCategorie = Categorie.id WHERE Article.titre LIKE '%$chaine%' OR Article.description LIKE '%$chaine%' OR Auteur.nom LIKE '%$chaine%' ORDER BY Article.dateCreation DESC";

  $res = mysqli_query($connexion, $req);
  return $res;
}

function getCategories($connexion) {
  $req = "SELECT id, nom FROM Categorie ORDER BY nom ASC";
    
  return mysqli_query($connexion, $req);
}

/* Retourne un ID d'auteur trouvé ou créé
   ou false +msg si échec.
   -Refaire les ifs-then-else en bordel
*/
function getCreeAuteur($connexion, $nom, $mail) {
  $req = "SELECT id, nom, email FROM Auteur WHERE email='$mail'";

  $res =  mysqli_query($connexion, $req);
  if ($res) {
    if (mysqli_num_rows($res) > 0) {
      $ligne = mysqli_fetch_assoc($res);
      return $ligne['id'];
    } else {
      $nom = mysqli_real_escape_string($connexion, $nom);
      $mail = mysqli_real_escape_string($connexion, $mail);
      $req = "INSERT INTO Auteur (nom, email) VALUES ('$nom', '$mail')";
      $res = mysqli_query($connexion, $req);
      if ($res) return mysqli_insert_id($connexion);
      else {
	//echo "Erreur de requete $req" . mysqli_error($connexion);
	return false;
      }
    }
  } else {
    //echo "Erreur de requete $req" . mysqli_error($connexion);
    return false; 
  }
}

/* Insere un article dans la base */
function insereArticle($connexion, $idCateg, $titre, $desc, $logo, $contenu, $idA) {
  $contenu = mysqli_real_escape_string($connexion, $contenu);
  $titre = mysqli_real_escape_string($connexion, $titre);
  $desc = mysqli_real_escape_string($connexion, $desc);
  $logo = mysqli_real_escape_string($connexion, $logo);

  if ($desc != '')
    $req = "INSERT INTO Article (titre, description, logo, contenu, idCategorie, idAuteur) VALUES ('$titre', '$desc', '$logo', '$contenu', '$idCateg', '$idA')";
  else 
    $req = "INSERT INTO Article (titre, description, contenu, idCategorie, idAuteur) VALUES ('$titre', '$desc', '$contenu', '$idCateg', '$idA')";
  
   $res = mysqli_query($connexion, $req);
   if (!$res) {
     //echo "Erreur de requete $req" . mysqli_error($connexion);
     return false; 			      
   }
   return true;
}

function getArticle($connexion, $id) {
  $req = "SELECT Article.id as id, Article.titre as titre, Article.description as description, DATE_FORMAT(Article.dateCreation, '%d %M %Y') as dateC, Article.contenu as contenu, Article.logo as logo, Auteur.nom as nomA, Categorie.nom as nomC FROM Article INNER JOIN Auteur on Article.idAuteur = Auteur.id INNER JOIN Categorie on Article.idCategorie = Categorie.id WHERE Article.id = '$id'";

  $res = mysqli_query($connexion, $req);
  if (!$res) {
      die("Erreur de requete $req" . mysqli_error($connexion));
  }
  return mysqli_fetch_assoc($res);
}

/* retourne  l'id de l'user si l'authentification réussit
   -1 sinon.
*/
function authenticate($connexion, $login, $mdp) {
  $login = mysqli_real_escape_string($connexion, $login);
  $mdpHache = sha1($mdp);

  $req = "SELECT id, nom FROM Auteur WHERE login = '$login' AND mdp ='$mdpHache'";
  $res = mysqli_query($connexion, $req);
  if (!$res) {
    die("Erreur d'authentification" . mysqli_error($connexion));
  }
  if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $id = $row['id'];
    return $id;
  } else return -1;
}

?>