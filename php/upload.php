<?php
require_once("debug_console.php");
require_once("functions_db.php");

// Vérifier si le formulaire a été soumis + si le chemin du soddier est renseigné
if( isset($_POST) && isset($_GET['chemin']) )
{
  echo $_GET['chemin'];
    // Vérifie si le fichier a été uploadé sans erreur.
    if(isset($_FILES["fichier"]) && $_FILES["fichier"]["error"] == 0)
	{
        // https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
        // cle = extension, val = MIME type
        $ok = array("jpg" => "image/jpg",
                    "jpeg" => "image/jpeg",
                    "png" => "image/png",
                    "pdf" => "application/pdf",
                    "ppt" => "application/vnd.ms-powerpoint",
                    "htm" => "text/html",
                    "html" => "text/html",
                    "txt" => "text/plain"
                    );

        $name = $_FILES["fichier"]["name"];
        $type = $_FILES["fichier"]["type"];
        $size = $_FILES["fichier"]["size"];

        debug_to_console($name);
        debug_to_console($type);
        debug_to_console($size);

        // Vérifie l'extension du fichier
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        if(!array_key_exists($extension, $ok)) die("Erreur : format de fichier  non autorisé !");

        // Vérifie la taille du fichier - 5Mo maximum
        $sizemax = 8 * 1024 * 1024;
        if($size > $sizemax) die("Erreur: La taille du fichier ne doit pas dépassée $sizemax !");

        // Vérifie le type MIME du fichier
        if(in_array($type, $ok))
		{

            // TODO:
            //$dir = "docs/";
            //$path = "C:/xampp/htdocs/gestionnaire-fichiers/".$dir . $_FILES["fichier"]["name"];

            //$path = $_GET['chemin']. $_FILES["fichier"]["name"];
            $path = $_GET['chemin'] . '/' . $_FILES["fichier"]["name"];


            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists($path))
			{
                echo $_FILES["fichier"]["name"] . " existe déjà !";
            }
			else
			{
                echo ($_FILES["fichier"]["tmp_name"]);
                move_uploaded_file($_FILES["fichier"]["tmp_name"], $path);
                echo "Le  fichier a été téléchargé avec succès !";

                //$path ="C:/xampp/htdocs/gestionnaire-fichiers/upload/" . $_FILES["fichier"]["name"];

				        // MAJ db (injection)
                connect_db();
                $parent_id = get_dossier_id(str_replace('/','\\',  $_GET['chemin']))[0];

                // injection db
                insert_document($name,str_replace('/','\\',  $path),$parent_id,$extension,$size);

            }
        }
		else
		{
            echo "Erreur: Problème de téléchargement du fichier !";
        }
    }
	else
	{
        echo "Erreur: " . $_FILES["fichier"]["error"];
    }
}
?>
