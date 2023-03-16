<?php

/**/

require_once("functions_db.php");

connect_db();

if (isset($_GET['id_doc'])) {
    //echo "<h1>test</h1><p>test</p>";
    $original_string = select_document_path_with_id($_GET['id_doc']);

    // Séparez le chemin en différentes parties
    $parts = explode("\\", $original_string);

    // Prenez les parties du chemin à partir de l'index 3
    $parts = array_slice($parts, 4);

    // Rejoignez les parties du chemin en utilisant "/" comme séparateur
    $new_string = implode("/", $parts);

    echo "<img src='{$new_string}' alt='' height='250' width='250'>"; // Outputs: "docs/dir1/dir1.1/dir1.1.1/taro-1686669_1920-1024x683.jpg"

    //echo "<img src='{$transformed_path}' alt='{$_GET['id_doc']}'>"; // Outputs: "docs/dir1/dir1.1/dir1.1.1/taro-1686669_1920-1024x683.jpg"
}

/*
if (isset($_GET['id_doc'])) {
    $original_path = select_document_path_with_id($_GET['id_doc']);
    //echo $original_path;
    $pattern = "/docs/";
    preg_match($pattern, $original_path, $matches, PREG_OFFSET_CAPTURE);
    if ($matches) {
        $pos = $matches[0][1];
        $transformed_path = substr($original_path, $pos);
        $transformed_path = str_replace("\\", "/", $transformed_path);

        echo "<p>{$transformed_path}</p>";
    }
}

*/


/*
$original_string = "C:\\xampp\\htdocs\\gestionnaire-fichiers\\docs\\dir1\\food-3062139_1920-1024x683.jpg";

// Séparez le chemin en différentes parties
$parts = explode("\\", $original_string);

// Prenez les parties du chemin à partir de l'index 3
$parts = array_slice($parts, 4);

// Rejoignez les parties du chemin en utilisant "/" comme séparateur
$new_string = implode("/", $parts);

echo "<img id='selected-document-content' src='{$new_string}' alt=''>"; // Outputs: "docs/dir1/dir1.1/dir1.1.1/taro-1686669_1920-1024x683.jpg"
*/
?>
