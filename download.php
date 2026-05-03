<?php

if(isset($_GET['file'])) {

    $file = $_GET['file'];

    // Prevent directory traversal
    $file = str_replace(['..', '\\'], '', $file);

    $filepath = __DIR__ . '/generated/' . $file;

    if(file_exists($filepath)) {

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($filepath));

        readfile($filepath);
        exit;

    } else {
        echo "File not found: " . $file;
    }
}
?>