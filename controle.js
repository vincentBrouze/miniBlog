/* Affichage d'un article */
function cliqueArticle(evt) {
    var id = evt.target.dataset.id;
    var param = "action=affArticle&id="+id;
    $("main").load("action.php", param);

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
    console.log(data);
    var res = JSON.parse(data);

    var statut = res[0];
    var msg = res[1];
    
    /* Declenche le modal */
    if (statut) {
	$("#titreDialogue").html("Succès");
	$("#contDialogue").html("Nouvel article créé avec succès");
    } else {
	$("#titreDialogue").html("Échec");
	$("#contDialogue").html(statut);
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
    formData.append("action", "traiteForm");
    $.ajax({
	type: "post",
	url: "action.php", 
	data: formData, 
	processData: false,
	contentType: false,
	success: resForm});
    console.log(formData);

    evt.preventDefault();
    return false;
}

/* cb sur le form d'ajout */
function cbForm(evt) {
    $("#formNouv").submit(envoieForm);
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
}


/* Au chargement de la page*/
function init() {
    /* Charge la liste des articles */
    var param = "action=listeArticles";
    $("main").load("action.php", param, cbArticles);

    var param = "action=menu";
    $("#placeMenu").load("action.php", param, cbCateg);

}

window.onload = init;
