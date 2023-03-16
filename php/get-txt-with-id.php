<?php

require_once("functions_db.php");

connect_db();

if (isset($_GET['id_doc'])) {
    $file_path = select_document_path_with_id($_GET['id_doc']);
    $extension = pathinfo($file_path, PATHINFO_EXTENSION);

    if ($extension === 'txt') {
        $text = file_get_contents($file_path);
        echo "<p>{$text}</p>";
    } else if ($extension === 'pdf') {
        header('Content-Type: application/pdf');
        readfile($file_path);
    } else if ($extension === 'html' || $extension === 'htm') {
        $html = file_get_contents($file_path);
        echo htmlspecialchars($html);
    }
}

?>
