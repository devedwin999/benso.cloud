<?php
// download.php

// Path to the APK file
$file = $base_url.'src/apk/benso.cloud.apk';

// Check if the file exists
if (file_exists($file)) {
    // Set headers to force download
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.android.package-archive');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    // Clear output buffer
    ob_clean();
    flush();

    // Read the file and output it
    readfile($file);

    echo json_encode([
        'status' => 'success',
        'message' => 'Application Successfully Downloaded!.'
    ]);

    exit;
} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'Download Failed!.'
    ]);
    exit;
}
?>
