/* Affichage complet d'un article */
function cliqueArticle(evt) {
    var id = evt.target.dataset.id;
    var param = "action=affArticle&id="+id;
    $("main").load("action.php", param);

    return false;
}

/* Envoie une requete de logout sur clic 
   du bouton.
 */
function cliqueLogout(evt) {
    var param = "action=logout";
    $.get("action.php", param);

    var param = "action=menu";
    $("#placeMenu").load("action.php", param, cbCateg);
    
    return false;  
}

/* Mise à jour de l'etat des boutons de la navbar*/
function majBoutonsNav(id) {
    var lien, lienCateg;
    var elems = $("#menuDeroule .nav li");
    elems.each(function () {
	lien = $(this).find("a");
	lienCateg = lien.data("id");
	if (lienCateg == id) {
	    $(this).toggleClass("active", true);
	} else {
	    $(this).toggleClass("active", false);
	}
    }
    );
}

/* Clic sur une catégorie */
function cliqueCategorie(evt) {
    var id = evt.target.dataset.id;
    var param = "action=listCateg&id="+id;
    $("main").load("action.php", param, cbArticles);
    majBoutonsNav(id);

    return false;    
}

/* Apres validation du form*/
function resForm(data) {
    //console.log(data);
    var res = JSON.parse(data);

    var statut = res[0];
    var msg = res[1];
    
    /* Declenche le modal */
    if (statut) {
	$("#titreDialogue").html("Succès");
	$("#contDialogue").html("Nouvel article créé avec succès");
    } else {
	$("#titreDialogue").html("Échec");
	$("#contDialogue").html(msg);
    }
    $('#dialogue').modal();

    /* Recharge la liste des articles */
    var param = "action=listeArticles";
    $("main").load("action.php", param, cbArticles);
    /* Remet le bouton du nav sur 'Tout'*/
    majBoutonsNav(-1);
}

/* Creation d'article */
function envoieForm(evt) {
    var formData = new FormData(evt.target);
    var code = $('#article').summernote('code');
    
    /* L'action pour controle.php */
    formData.append("action", "traiteForm");
    /* Ajoute le code HTML de l'editeur */
    formData.append("articleMEF", code);

    $.ajax({
	type: "post",
	url: "action.php", 
	data: formData, 
	processData: false,
	contentType: false,
	success: resForm});
    console.log(formData);

    /* Empeche la soumission normale */
    evt.preventDefault();
    return false;
}

/* cb sur le form d'ajout */
function cbForm(evt) {
    /* Clic sur valider */
    $("#formNouv").submit(envoieForm);

    /* Active l'éditeur */
    $('#article').summernote({
	height: 300,
	minHeight: null,
	maxHeight: null, 
	focus: false
    });

}

/* Clique sur le bouton add*/
function cliqueAdd(evt) {
    var param = "action=add";
    $("main").load("action.php", param, cbForm);

    return false;    
}

/* Fixe les cb sur les articles */
function cbArticles(evt) {
    $(".article a").click(cliqueArticle);

    /* Changement de page*/
    $(".chgtPage").click(function (evt) {
	var page = evt.target.dataset.pagination;
	var param = "action=listeArticles&page="+page;
	$("main").load("action.php", param, cbArticles);
	return false;
    });
}

/* Envoie une recherche */
function subRecherche(evt) {
    var chaineRech = $("#inputCherche").val();
    var param = "action=cherche&string="+chaineRech;
    
    $("main").load("action.php", param, cbArticles);
    
    /* Empeche l'envoi normal du formulaire */
    evt.preventDefault();
    return false;
}

/* Fixe les callback sur les éléments de menu */
function cbCateg() {
    $("#menuDeroule .nav a").click(cliqueCategorie);
    $("#add").click(cliqueAdd);
    $("#formCherche").submit(subRecherche);
    var boutLogout = $("#boutLogout");
    boutLogout.click(cliqueLogout);
}

/* Le submit du formulaire */
function subLogin(evt) {
    var log = $("#champsLogin").val();
    var mdp = $("#champsPassword").val();

    var param = "action=login&login="+log+"&mdp="+mdp;

    $.get("action.php", param, function (data) {
	if (JSON.parse(data)) {
	    var param = "action=menu";
	    $("#placeMenu").load("action.php", param, cbCateg);
	    $("#login").modal('hide');
	} else {
	    alert('erreur');
	}
    });
    
    evt.preventDefault();
    return false;
}

/* Au chargement de la page*/
function init() {
    /* Charge la liste des articles */
    var param = "action=listeArticles";
    $("main").load("action.php", param, cbArticles);

    var param = "action=menu";
    $("#placeMenu").load("action.php", param, cbCateg);

    /* CB sur le form de login */
    $("#formLogin").submit(subLogin);

}

/* Au chargement de la page*/
window.onload = init;
