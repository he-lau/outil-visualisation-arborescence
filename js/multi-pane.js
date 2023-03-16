// Une fonction est utilisée pour glisser et déplacer
function dragElement(element) {
  // md contiendra les informations de l'événement mousedown (appui sur le bouton de la souris)
  var md;
  // Récupération des éléments HTML avec les identifiants "first" et "second"
  const first = document.getElementById("first");
  const second = document.getElementById("second");

  // Quand l'élément est cliqué avec le bouton de la souris, la fonction onMouseDown est exécutée
  element.onmousedown = onMouseDown;

  function onMouseDown(e) {
    // Enregistrement des informations de l'événement mousedown
    md = {
      e,
      offsetLeft: element.offsetLeft,  // Position horizontale de l'élément par rapport à son élément parent
      offsetTop: element.offsetTop,  // Position verticale de l'élément par rapport à son élément parent
      firstWidth: first.offsetWidth,  // Largeur de l'élément "first"
      secondWidth: second.offsetWidth,  // Largeur de l'élément "second"
    };

    // Quand la souris bouge, la fonction onMouseMove est exécutée
    document.onmousemove = onMouseMove;
    // Quand le bouton de la souris est relâché, les écouteurs d'événement onmousemove et onmouseup sont supprimés
    document.onmouseup = () => {
      document.onmousemove = document.onmouseup = null;
    };
  }

  function onMouseMove(e) {
    // Calcul de la différence de position entre la position actuelle de la souris et la position initiale lorsque le bouton a été appuyé
    var delta = {
      x: e.clientX - md.e.clientX,
      y: e.clientY - md.e.clientY,
    };

      // Limitation de la valeur de delta.x pour éviter que les éléments "first" et "second" aient une taille négative
      delta.x = Math.min(Math.max(delta.x, -md.firstWidth), md.secondWidth);

      // Mise à jour de la position et de la largeur des éléments
      element.style.left = md.offsetLeft + delta.x + "px";
      first.style.width = md.firstWidth + delta.x + "px";
      second.style.width = md.secondWidth - delta.x + "px";
  }
}


dragElement( document.getElementById("separator"));
