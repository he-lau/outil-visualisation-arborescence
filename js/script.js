

/*
  Fonction pour surligner l'element cliqué et s'assurer que c'est le seul
*/
function selectItem(item, parentElement) {
  // Remove the "selected-item" class from all child elements of the parent element
  var selectedElements = parentElement.getElementsByClassName("selected-item");
  for (var i = 0; i < selectedElements.length; i++) {
    selectedElements[i].classList.remove("selected-item");
  }

  // Add the "selected-item" class to the selected element
  item.classList.add("selected-item");
}

// elements de l'arbre
const treeElements = document.querySelectorAll("#tree li span");

/*
  Characteristiques du document/dossier selectionné
*/
var id_selected = -1;
var type_selected = "";
var selected_path = "";

/*
  Listener pour chaque element
*/
treeElements.forEach(element => {
  element.addEventListener("click", event => {
    // maj id de l'element avec l'attribut "id"
    id_selected = element.id;

    //element.classList.add("selected-item");
    selectItem(element, document.querySelector("#first"));

    // c'est un document
    if (element.classList.contains("document")) {
      console.log("Je suis un document");
      var type = "document";
    } else if (element.classList.contains("dossier")) {
      // The element has the class "dossier"
      console.log("Je suis un dossier");
      var type = "dossier";
      // Init la page lors du click
      // recharge la page pour réitialiser le volet de droite
      const state = { page: 1 };
      const url = "index.php";
      history.pushState(state, "", url);
    }

    // maj type
    type_selected = type;

    console.log(element.id);
    // Requête AJAX avec jQuery JSON
    $.ajax({
      url: "./php/get-folder-or-document-info.php",
      type: "GET",
      dataType: "json",
      // on renseigne l'id et le type de l'element et en initailisant la pagination à 1
      data: { id: id_selected, type: type_selected, page: 1 },
      success: function(data) {
        // Traitement des données renvoyées par le script PHP
        console.log(data);
        console.log(data["data"]["path"]);

        // on garde en mémoire le chemin
        selected_path = data["data"]["path"];

        // si la requête est executée correctement
        if (data["success"]) {
          // on injecte le html au volet de droite
          $("#second").html(data["html"]);

        } else {
          console.log("Requete AJAX error");
        }
      }
    });
  });
});



/***********************************
  Upload
*************************************/

// fenêtre d'upload
var modal = document.getElementById("modal");

// la croix pour fermer la fenêtre
var span = document.getElementsByClassName("close")[0];

// ajout de document au dossier séléctionné
$('#add-doc').click(function(e){
  //alert(id_selected);

  /*
    - ajouter au dossier local :
      - avoir le chemin du ossier de destination avec son id
    - maj bd
    - maj l'affichage (ui)
  */
  if (type_selected=="dossier") {
    //alert('hello folder');

    // on affiche la fenêtre d'upload
    modal.style.display = "block";
    //alert(selected_path);
  }
  else {
    alert("On ne peut ajouter un document que dans un dossier.")
  }

});

// si on clique sur la croix, on ferme la fenêtre
span.onclick = function() {
  modal.style.display = "none";
}
// si on clique en dehors de la fenêtre, on ferme la fenêtre
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


// Si l'utilisateur soumet le form d'upload
$('#form-upload').submit(function(event) {
  // Prevent the form from being submitted
  event.preventDefault();

  $.ajax({
    type: 'POST',
    // IMPORTANT : dans l'url en GET, je passe le chemin du dossier
    // il sera utilisé pour la destination du fichier
    url: "./php/upload.php"+"?chemin="+selected_path,
    // on recupere les informations du fichier uploadé
    data: new FormData($('#form-upload')[0]),
    contentType: false,
    processData: false,
    success: function(response) {
      //console.log(response);
      //alert(response);

      // rafraichir la page, pour reconstruire l'arbre
      location.reload();
    },
    error: function(xhr, status, error) {
      // There was an error with the request. You can handle the error here.
      alert(error);
    }
  });

});

/***********************************

  Listeners avec déléguation, pour l'affichage dynamique dans le volet de droite

  - pagination
  - contenu des documents

*************************************/


// Listener avec délégation pour la maj de l'arbre
document.addEventListener("click", function(e){

// liens de pagination
const target = e.target.closest("#second a"); // Or any other selector.

if(target && type_selected=="dossier"){

  //alert('A');

    console.log("a");

    // on recupere la page depuis l'url que renvoie le lien
    var url = $(target).attr("href").substring(1);

    console.log(url);
    //alert(url);

    // Send another AJAX request with updated data
    $.ajax({
      // $_GET["page"]
      url: './php/get-folder-or-document-info.php'+url,
      type: 'GET',
      dataType: 'json',
      data: {id: id_selected, type:type_selected}, // Update the data here
      success: function(data) {
        console.log(data);
        console.log(this.href);
        //console.log(<?php echo $_GET['page']; ?>);
        $("#second").html(data['html']);
      }
    });
}
});


/*****************************

Contenu des documents

*****************************/

document.addEventListener("click", function(e){

// element du tableau
const target = e.target.closest("#second tr");

if (target && type_selected=="dossier") {
  // id, nom, extension du doc selectionne
  let id_document_selected = $(target).find(':first-child').html();
  let name_document_selected = $(target).find(':eq(1)').html().toString().trim();
  let extension_document_selected = $(target).find(':eq(2)').html().toString().trim();
  //extension_document_selected = extension_document_selected.toString();


    // fonction pour surligner l'element choisi
    selectItem(target, document.querySelector("#second"));

    //alert(extension_document_selected);
    console.log(extension_document_selected);
    console.log(id_document_selected);

    /*
      Affichage du contenu des documents à l'aide de leurs id
    */

    if (extension_document_selected=="jpg" || extension_document_selected=="png"|| extension_document_selected=="jpeg") {
      //alert('image');
      // requête pour récuperer le chemin du document
      $.ajax({
        type: "GET",
        data : {id_doc:id_document_selected},
        url: "./php/get-img-with-id.php",
        success: function(response) {
          console.log(response);
          // on s'assure de n'avoir qu'un seul document affiché
          $('#selected-document-content').remove();

          response = "<div id='selected-document-content'><h2>"+name_document_selected+"</h2>"+response+"</div>";

          // Ajoute au DOM dans le volet droite
          const newChild = $(response);
          $('#second').append(newChild);
        }
      });

    }
    else if (extension_document_selected=="txt" || extension_document_selected=="html" || extension_document_selected=="htm" || extension_document_selected=="pdf") {
      $.ajax({
        type: "GET",
        data : {id_doc:id_document_selected},
        url: "./php/get-txt-with-id.php",
        success: function(response) {
          console.log(response);
          $('#selected-document-content').remove();

          response = "<div id='selected-document-content'><h2>"+name_document_selected+"</h2>"+response+"</div>";

          const newChild = $(response);
          $('#second').append(newChild);
        }
      });
    }
    else {
      alert("Affichage pour ."+extension_document_selected+" non encore implémenté.")
    }



}
});
