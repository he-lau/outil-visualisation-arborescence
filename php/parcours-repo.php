<?php

require_once('functions_db.php');
/*
  Explorer le dossier local "docs" puis MAJ de la db
*/
function explorer($chemin, $parent=" ",$level=0){
    global $niveau;

    connect_db();

    //echo $parent;
    //echo $level;

    // lstat : pour obtenir les informations debug
    $lstat    = lstat($chemin);
    //$mtime    = date('d/m/Y H:i:s', $lstat['mtime']);
    $filetype = filetype($chemin);

    // Affichage des infos sur le fichier $chemin
    //echo "$chemin   type: $filetype size: $lstat[size]\n";
    $nom = basename($chemin).PHP_EOL;
    // Si $chemin est un dossier => on appelle la fonction explorer() pour chaque élément (fichier ou dossier) du dossier$chemin
    if( is_dir($chemin) ){
      // dossier local root
      if ($level==0) {
        insert_dossier($chemin,$nom,0,NULL);
      }
      else {
        //echo "Mon parent : ".$parent;
        // on recupere son id avec son chemin
        $parent_id = get_dossier_id($parent)[0];
        //echo"\t Son id : ".$parent_id;
        insert_dossier($chemin,$nom,$level,$parent_id);
      }

/*
      for($i=1; $i<=(4*$level); $i++) {
          echo "&nbsp&nbsp&nbsp&nbsp";
      }
      echo "Je suis un dossier : ".$nom;
      echo "<br>";
*/
        $me = opendir($chemin);
        while( $child = readdir($me) ){
            if( $child != '.' && $child != '..' ){
                explorer( $chemin.DIRECTORY_SEPARATOR.$child,$chemin,$level+1 );
            }
        }
    }

    elseif (is_file($chemin)) {
      $parent_id = get_dossier_id($parent)[0];

      $extension = substr($nom, strrpos($nom, '.') + 1);

      insert_document($nom,$chemin,$parent_id,$extension,$lstat['size']);
/*
      for($i=1; $i<=(4*$level); $i++) {
          echo "&nbsp&nbsp&nbsp&nbsp";
      }
      // code...
      echo "Je suis un fihier : ".$nom;
      echo "Mon extension : ".$extension;
      echo "Ma taille : ".$lstat['size'];
      echo "<br>";
*/
    }
}

/*
  Affichage de l'arborecence depuis la bd
*/

/*
function arbo_sans_root_sans_html($parentId, $level, $pdo) {

  // Sélectionnez tous les dossiers qui ont le parent ID spécifié
  $stmt = $pdo->prepare('SELECT * FROM DOSSIER WHERE parent_id = :parent_id');
  $stmt->execute(['parent_id' => $parentId]);
  $folders = $stmt->fetchAll();

  // Pour chaque dossier, affichez son nom et appelez récursivement la fonction pour traiter ses sous-dossiers
  foreach ($folders as $folder) {
    echo str_repeat('--', $level) . $folder['nom'] . "<br>";
    arbo_sans_root_sans_html($folder['id'], $level + 1, $pdo);
  }

  // Sélectionnez tous les documents qui ont le parent ID spécifié
  $stmt = $pdo->prepare('SELECT * FROM DOCUMENT WHERE dossier_id = :dossier_id');
  $stmt->execute(['dossier_id' => $parentId]);
  $documents = $stmt->fetchAll();

  // Pour chaque document, affichez son nom
  foreach ($documents as $document) {
    echo str_repeat('--', $level) . $document['nom'] . "<br>";
  }
}

*/


function arbo_avec_html($parentId, $level, $pdo) {
  // Si $parentId est 0, sélectionnez le dossier racine (ou les dossiers racines)
  if ($parentId == 0) {
    $stmt = $pdo->prepare('SELECT * FROM DOSSIER WHERE parent_id IS NULL');
    $stmt->execute();
    $folders = $stmt->fetchAll();
  }
  // Sinon, sélectionnez les dossiers avec l'ID de parent spécifié
  else {
    $stmt = $pdo->prepare('SELECT * FROM DOSSIER WHERE parent_id = :parent_id');
    $stmt->execute(['parent_id' => $parentId]);
    $folders = $stmt->fetchAll();
  }

  // Pour chaque dossier, affichez son nom et appelez récursivement la fonction
  // pour traiter ses sous-dossiers
  foreach ($folders as $folder) {
    echo "<li>";
    echo '<input type="checkbox"/>';
    echo "<span class='dossier' id='{$folder['id']}'>" . $folder['nom'] . "</span>";
    echo '<ul>';
    arbo_avec_html($folder['id'], $level + 1, $pdo);
    echo '</ul>';
    echo '</li>';
  }

  // Sélectionnez tous les documents avec l'ID de dossier spécifié
  $stmt = $pdo->prepare('SELECT * FROM DOCUMENT WHERE dossier_id = :dossier_id');
  $stmt->execute(['dossier_id' => $parentId]);
  $documents = $stmt->fetchAll();

  // Pour chaque document, affichez son nom
  foreach ($documents as $document) {
    echo "<li>";
    echo "<span class='document' id='{$document['id']}'>" . $document['nom'] . '</span>';
    echo '</li>';
  }
}



 ?>
