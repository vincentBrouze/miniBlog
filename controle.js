
function cliqueArticle(evt) {
    var id = evt.target.dataset.id;
    var param = "action=affArticle&id="+id;
    $("main").load("action.php", param);

    return false;
}

function cliqueCategorie(evt) {
    var id = evt.target.dataset.id;
    var param = "action=listCateg&id="+id;
    $("main").load("action.php", param);

    return false;    
}

function cbArticles(evt) {
    $(".article a").click(cliqueArticle);
}
function cbCateg() {
    $("#menuDeroule .nav a").click(cliqueCategorie);
}
/* Au chargement de la page*/
function init() {
    /* Carge la liste des articles */
    var param = "action=listeArticles"
    $("main").load("action.php", param, cbArticles);

    var param = "action=menu";
    $("#placeMenu").load("action.php", param, cbCateg);
}

window.onload = init;
