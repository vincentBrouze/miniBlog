<?php
/* La partie header jusqu'au main*/
function afficheHeader() {
  echo '
<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="style.css">

<!-- include summernote css/js -->
<link href="dist/summernote.css" rel="stylesheet">
<script src="dist/summernote.js"></script>
<script src="dist/lang/summernote-fr-FR.js"></script>

  <script src="controle.js"></script>
   <title>Mon blog</title>
  </head>
  <body>
  <header><h1>Blog</h1><h2>Articles sur le web</h2></header>
<div id="placeMenu"></div>
   <main></main>

<!-- Modal de dialogue pour lajout d article-->
<div id="dialogue" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 id="titreDialogue" class="modal-title">Modal Header</h4>
      </div>
      <div id="contDialogue" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal de login -->
<div id="login" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Login</h4>
      </div>
      <div id="contDialogue" class="modal-body">
<form id="formLogin" action="login.php" method="get">
 <div class="form-group row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
      <input type="text" class="form-control-plaintext" id="champsLogin" placeholder="Login">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="champsPassword" placeholder="Password">
    </div>
  </div>
  <button type="submit" class="btn btn-primary mb-2">Valider</button>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

</body></html>';
}

/* La liste des articles */
function afficheListeArticles($res, $pageCour, $nbp) {

  while ($ligne = mysqli_fetch_assoc($res)) {
    echo '<article class="article">';
    echo "<h2><img class='logo' src='".$ligne['logo']."'/><a data-id='".$ligne['id']."' href='index.php?action=affArticle&id=".$ligne['id']."'>".$ligne['titre']."</a></h2>";
    echo "<p class='nomDate'>Par ".$ligne['nomA'].", le ".$ligne['dateC']." -- ".$ligne['nomC']."</p>";
    echo "<p>".$ligne['description']."</p>";
    echo "</article>";
  }

  /* Pagination */
  $classe = "";
  $data = $pageCour - 1;
  if ($pageCour == 1) {
    $classe = "disabled";
    $data= 1;
  }
  echo '<nav aria-label="Page navigation">
  <ul class="pagination">
    <li class="'.$classe.'">
      <a class="chgtPage" data-pagination="'.$data.'" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>';

  for ($i = 1; $i <= $nbp; $i++) {
    $classe = "";
    if ($i == $pageCour) $classe = "active";
      echo '<li class="'.$classe.'"><a class="chgtPage" data-pagination="'.$i.'" href="#">'.$i.'</a></li>';
  }
  $classe = "";
  $data = $pageCour + 1; 
  if ($nbp == $pageCour) {
    $classe = "disabled";
    $data= 1;
  }
  echo '
      <li class="'.$classe.'"><a class="chgtPage" data-pagination="'.$data.'" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>';
}

function afficheCategories($connexion, $curr) {
  $res = getCategories($connexion);

  while ($ligne = mysqli_fetch_assoc($res)) {
    if ($ligne['id'] == $curr) echo '<li class="active">';
    else echo '<li>';
    echo '<a data-id="'.$ligne['id'].'" href="index.php?action=listCateg&id='.$ligne['id'].'">'.$ligne['nom'].'</a>';
    echo '</li>';
  }
}

/* La partie menu */
function afficheNav($connexion, $action, $currCateg = -1) {
  echo '<nav class="navbar navbar-default navbar-collapse-xs" id="nav">';
  echo '<div class="container-fluid">'; 
  echo '<div class="navbar-header"> <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menuDeroule">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button></div>';
  echo "<div id='menuDeroule' class='navbar-collapse collapse'>";
  echo '<ul class="nav navbar-nav  mr-auto">';
  if ($currCateg == -1) 
    echo '<li class="active"><a data-id="-1" href="index.php">Tout</a></li>';
  else 
    echo '<li><a href="index.php">Tout</a></li>';
  afficheCategories($connexion, $currCateg);
  echo '</ul>';

  /* En fct de la session */
  if (isset($_SESSION['id'])) {//Utilisateur connecté
    echo '<a id="add" href="add.php" class="btn btn-danger navbar-btn navbar-right">Écrire</a>';
    echo "<a title='logout' id='boutLogout' href='logout.php' class='btn btn-danger navbar-btn navbar-right'><i class='glyphicon glyphicon-log-out'></i></a>";
  } else {
    echo "<a title='login' id='boutLogin' href='login.php' class='btn btn-danger navbar-btn navbar-right' data-toggle='modal' data-target='#login'><i class='glyphicon glyphicon-log-in'></i></a>";
  }
  echo '<form id="formCherche" class="navbar-form navbar-right" action="recherche.php">
<div class="input-group">
    <input id="inputCherche" required type="text" class="form-control" placeholder="Rechercher">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        <i class="glyphicon glyphicon-search"></i>
      </button>
    </div>
   </div>
  </div>
    </form>';
  echo '</div></nav>';

}

function afficheFormCategories($connexion) {
  $res = getCategories($connexion);

  echo "<label for='categorie'>Categorie* :</label><select id='categorie' name='categorie'>";
  while ($ligne = mysqli_fetch_assoc($res)) {
    echo "<option value='".$ligne['id']."'>".$ligne['nom']."</option>";
  }
  echo "</select>";
}

/* Formulaire de création */
function afficheFormNouv($connexion) {
  echo "<form enctype='multipart/form-data' id='formNouv' method='post' action='add.php'>";
  echo "<fieldset><legend>Article</legend>";  
  afficheFormCategories($connexion);
  echo "<label for='titre'>Titre* :</label><input required id='titre' name='titre' type='text'/>";
  echo "<label for='description'>Description* :</label><input required id='description' name='description' type='text'/>";
  echo "<label for='logo'>Logo :</label><input id='logo' name='logo' type='file'/>";
  echo "<label for='article'>Article* :</label><textarea id='article' name='article' rows='20'></textarea>";

  echo "</fieldset>";
  /*
  echo "<fieldset><legend>Auteur</legend>";
  echo "<label for='nomA'>Nom* :</label><input required id='nomA' name='nomA' type='text'/>";  
  echo "<label for='email'>Email* :</label><input required id='email' name='email' type='text'/>";  
  echo "</fieldset>";
  */
  echo "<button type='submit'>Valider</button>";
  echo "</form>";
  
}

function afficheArticle($connexion, $id) {
  $ligne = getArticle($connexion, $id);

  echo "<article class=''>";
  echo "<h2><img class='logo' src='".$ligne['logo']."'/>".$ligne['titre']."</h2>";
  echo "<p>";
  echo $ligne['contenu'];
  echo "</p>";
  echo "</article>";
}
?>