<?php

/****************************

retourne html pour le volet de droite dossier/document

****************************/

require_once('functions_db.php');
require_once 'debug_console.php';

// Connect to the database
connect_db();

global $db;


if (isset($_GET['id'])) {
  if (isset($_GET['type'])) {
    $id = $_GET['id'];
    if ($_GET['type']=="dossier") {
      // code...
      $stmt = $db->prepare('SELECT * FROM DOSSIER WHERE id = :id');
      $stmt->execute(['id' => $id]);
      $folder = $stmt->fetch();

      // Afficher tous les document dans mon dossier avec un systeme de pagination
      require_once('interface.php');
      ob_start();
      get_dossier($id);
      //get_hello($id);
      $dossier_html = ob_get_flush();

      //$path_encode = preg_replace('#\\#', '/', strval($folder['chemin']));
      $path_encode = str_replace('\\', '/', $folder['chemin']);
      //$path_encode = str_replace('/','\\',  $folder['chemin']);
      //$path_encode = strval($folder['chemin']);

      $response = [
        'success' => true,
        'message' => 'La requête a réussi',
        'data' => [
          'type' => 'dossier',
          'id' => $folder['id'],
          'name' => $folder['nom'],
          'level' => $folder['niveau'],
          'parentId' => $folder['parent_id'],
          'path' =>  $path_encode
        ],
        'html' => $dossier_html
      ];
    }
    else {
      // document
      $stmt = $db->prepare('SELECT * FROM DOCUMENT WHERE id = :id');
      $stmt->execute(['id' => $id]);
      $document = $stmt->fetch();

      $response = array(
        'success' => true,
        'message' => 'La requête a réussi',
        'data' => array(
          'type' => 'document',
          'id' => $document['id'],
          'name' => $document['nom'],
          'path' => $document['chemin'],
          'folderId' => $document['dossier_id'],
          'extension' => $document['extension'],
          'size' => $document['taille'],
        ),
        'html' => "
          <ul>
            <li>id : {$document['id']}</li>
            <li>nom : {$document['nom']}</li>
            <li>chemin : {$document['chemin']}</li>
            <li>id du dossier parent : {$document['dossier_id']}</li>
            <li>extension : {$document['extension']}</li>
            <li>taille : {$document['taille']}</li>
          </ul>
        "
      );
    }


      ob_clean();

      header('Content-Type: application/json');
      echo json_encode($response);
  }
}
?>
