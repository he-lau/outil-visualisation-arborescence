  <?php
  require_once 'functions_db.php';
  require_once 'debug_console.php';


  connect_db();

  function select_count_documents_by_dossier($dossier_id) {
    global $db;

  // Prepare the SELECT statement
  $stmt = $db->prepare('SELECT COUNT(*) FROM DOCUMENT WHERE dossier_id = :dossier_id');

  // Bind the dossier_id parameter to the statement
  $stmt->bindParam(':dossier_id', $dossier_id, PDO::PARAM_INT);

  // Execute the statement
  $stmt->execute();

  // Return the result
  return $stmt->fetch();
}

function select_documents_by_dossier_and_page($dossier_id, $indice_debut, $tuple_par_page) {
  global $db;

  // Prepare the SELECT statement
  $stmt = $db->prepare('SELECT * FROM DOCUMENT WHERE dossier_id = :dossier_id LIMIT :indice_debut, :tuple_par_page');

  // Bind the parameters to the statement
  $stmt->bindParam(':dossier_id', $dossier_id, PDO::PARAM_INT);
  $stmt->bindParam(':indice_debut', $indice_debut, PDO::PARAM_INT);
  $stmt->bindParam(':tuple_par_page', $tuple_par_page, PDO::PARAM_INT);

  // Execute the statement
  $stmt->execute();

  // Return the result
  return $stmt->fetchAll();
}


/***********************

get_dossier: html avec pagination pour les dossiers

************************/


function get_dossier($dossier_id) {
// Check if a page parameter has been passed in the URL
if (isset($_GET["page"])) {
  // Perform pagination calculations
  $tuple_par_page = 3;
  $tuple_total = select_count_documents_by_dossier($dossier_id)[0];
  $tuple_total = $tuple_total[0];
  $nombre_page = ceil($tuple_total / $tuple_par_page);
  $indice_debut = $_GET['page'] * $tuple_par_page - $tuple_par_page;
  debug_to_console("indice de debut " . $indice_debut);

  // Retrieve the rows for the current page
  $result = select_documents_by_dossier_and_page($dossier_id, $indice_debut, $tuple_par_page);

  // Display the rows in an HTML table
  echo "<table>";
  echo "<tr>";
  echo "<td>id</td>";
  echo "<td>nom du fichier</td>";
  //echo "<td>chemin</td>";
  //echo "<td>dossier parent</td>";
  echo "<td>extension</td>";
  echo "<td>taille</td>";
  echo "</tr>";
  foreach ($result as $key => $value) {
    debug_to_console($key);
    echo "<tr>";
    echo "<td>{$value["id"]}</td>";
    echo "<td>{$value["nom"]}</td>";
    //echo "<td>{$value["chemin"]}</td>";
    //echo "<td>{$value["dossier_id"]}</td>";
    echo "<td>{$value["extension"]}</td>";
    echo "<td>{$value["taille"]}</td>";
    echo "</tr>";
  }
  echo "</table>";

  if ($nombre_page>1) {
      // Display a list of links to the different pages
      echo "<ul id='lien-pagination'>";
      echo "<li>Pages : </li>";
      for ($i = 0; $i < $nombre_page; $i++) {
        $page = $i + 1;
        echo "<li class='li'><a href='#?page={$page}'>".$page."</a></li>";
      }
      echo "</ul>";
  }

}
}


/*
  Fonction pour le deboguage
*/
function get_hello($id) {
  echo "<p>Bonjour dossier {$id} !</p>";

  echo select_count_documents_by_dossier(9)[0];
}



//if (isset($_GET["page"]) ) {
/*
if (isset($_GET["page"])) {

  // pagination
  $tuple_par_page = 3;
  $tuple_total = select_count_all();
  $tuple_total = $tuple_total[0];

  $nombre_page = ceil($tuple_total / $tuple_par_page);

  $indice_debut = $_GET['page']*$tuple_par_page-$tuple_par_page;
  debug_to_console("indice de debut ".$indice_debut);

  $result = select_current_page($indice_debut,$tuple_par_page);

  echo "<table>";
  echo "<tr>";
  echo "<td>id</td>";
  echo "<td>fichier</td>";
  echo "<td>chemin</td>";
  echo "<td>dossier</td>";
  echo "<td>extension</td>";
  echo "<td>taille</td>";
  echo "</tr>";
  foreach ($result as $key =>$value) {
      debug_to_console($key);
      echo "<tr>";
      echo "<td>{$value["id"]}</td>";
      echo "<td>{$value["nom"]}</td>";
      echo "<td>{$value["chemin"]}</td>";
      echo "<td>{$value["dossier_id"]}</td>";
      echo "<td>{$value["extension"]}</td>";
      echo "<td>{$value["taille"]}</td>";
      echo "</tr>";
  }
  echo "</table>";

  // afficher le menu de navigation de page
  echo "<ul class='lien-pagination'>";
  echo "<li>Pages : </li>";
  for ($i=0; $i <$nombre_page; $i++) {
    $page = $i+1;
    //echo "<li><a href='index.php?page="."$page'".">".$page."</a></li>";
    echo "<li><a href='#?page={$page}'>".$page."</a></li>";
  }
  echo "</ul>";
  }
  */
  ?>
