<?php

require_once("debug_console.php");

/*
  Connection à la bd
*/
function connect_db() {
  global $db;

$server = "localhost;port=3306;dbname=m1_gestionnaire_fichiers";
$username = "root";
$password = "";

try {
  $db = new PDO("mysql:host=$server", $username, $password);
  // set the PDO error mode to exception
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  debug_to_console("Connection a la db réussi.");

} catch(PDOException $e) {
  debug_to_console("Connection failed:"  . $e->getMessage());
}
}

/*
  Obtenir le nombre total de documents dans la bd
*/

function select_count_all() {
  global $db;

  $select_count = $db->prepare("SELECT COUNT(*) FROM document");
  $select_count->execute();
  return $select_count->fetch();
}

/*
  Obtenir le nombre de document pour la pagination courante
*/

function select_current_page($indice_debut,$limit) {
  global $db;
  $select_current_page = $db->prepare("SELECT * FROM document LIMIT $indice_debut,$limit");
  $select_current_page->execute();
  return $select_current_page->fetchAll();

}

// Obtenir l'id d'un dossier avec son chemin
function get_dossier_id($chemin) {
  global $db;

  $select_dossier_id = $db->prepare("SELECT id FROM dossier WHERE chemin=?");
  $select_dossier_id->execute([$chemin]);
  return $select_dossier_id->fetch();
}

// Ajout d'un dossier à la db
function insert_dossier($chemin,$nom,$niveau,$parent_id) {
  global $db;

  $insert_into_dossier= $db->prepare("INSERT INTO DOSSIER (chemin, nom, niveau, parent_id) VALUES (:chemin, :nom, :niveau, :parent_id)");
  try {
    $insert_into_dossier->execute(array(
      "chemin" => $chemin,
      "nom" => $nom,
      "niveau" => $niveau,
      "parent_id" => $parent_id
    ));
    debug_to_console("Injection à la table DOSSIER réussi.\n");
  } catch (PDOException $e) {
    debug_to_console("Insert failed :"  . $e->getMessage());
  }

}


/*
  Ajout d'un document à la base
*/
function insert_document($nom,$chemin,$dossier_id,$extension,$taille) {
  global $db;

  $insert_into_document= $db->prepare("INSERT INTO document (nom,chemin,dossier_id,extension,taille) VALUES (:nom,:chemin,:dossier_id,:extension,:taille)");
  try {
    $insert_into_document->execute(array(
      "nom" => $nom,
      "chemin" => $chemin,
      "dossier_id" => $dossier_id,
      "extension" => $extension,
      "taille"=>$taille
    ));
    debug_to_console("Injection réussi.\n");
  } catch (PDOException $e) {
    debug_to_console("Insert failed :"  . $e->getMessage());
  }
}

function select_document_path_with_id($id) {
  global $db;

  $select_query = $db->prepare("SELECT chemin FROM document WHERE id = ?");
  $select_query->execute([$id]);
  return $select_query->fetchColumn();
}


?>
