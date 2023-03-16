<html>
<head>
<link rel="stylesheet" href="css/style.css">
<link href="css/file-tree.css" rel="stylesheet">
  </head>
<body>


<!-- div pour l'upload de fichiers -->
<div id="modal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Upload Fichier</h2>
    </div>
    <div class="modal-body">
      <form id="form-upload" action="php/upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="fichier" id="fileupload">
        <input type="submit" name="submit" value="Upload">
      </form>
    </div>
  </div>
</div>


<!-- Barre d'option -->
<div class="option-bar">
  <div class="text">
    <!-- LOGO/NOM -->
    <div id='logo'><a href="index.php">NOM/LOGO</a></div>
  </div>
  <div class="actions">
    <!-- Boutons d'actions-->
    <form method="post">
    <input  id="parcours-repo" type="submit" name="parcours-repo"
            class="button" value="Parcourrir le dossier local" />

    <input id="add-doc" type="button" name="add-doc"
            class="button" value="Ajouter un document" />
    </form>
  </div>

  <?php
  require_once('php/functions_db.php');
  require_once('php/parcours-repo.php');
   ?>

  <?php
  //  explorer("C:\\xampp\htdocs\gestionnaire-fichiers\docs");
  // si parcours-repo à été cliqué
  if(array_key_exists('parcours-repo', $_POST)) {
    // maj bd avec dossier local
    explorer("C:\\xampp\htdocs\gestionnaire-fichiers\docs");
  }
  ?>

</div>

<!-- div principal -->
<div class="splitter">
    <!-- volet de gauche -->
    <div id="first">
      <?php
      connect_db();
      // construit l'aborescence depuis la bd
      echo "<ul id='tree'>";
      //arbo_avec_html(1, 0, $db);
      arbo_avec_html(0, 0, $db);
      echo "</ul>";

      ?>
    </div>

    <div id="separator" ></div>
    <!-- volet de droite -->
    <div id="second" >
      <h1>Bienvenu(e) !</h1>

      <p>
        Ce projet a été réalisé dans le cadre du module "Technologie Internet/ Web" (M1 Informatique).
      </p>

    </div>
</div>

<script src="js/multi-pane.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/script.js"></script>


</body>
</html>
