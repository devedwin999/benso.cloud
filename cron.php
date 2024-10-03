<?php




$cron = $_GET['cron'] ?? '';

if ($cron === 'clear_qrcode') {
    $directories = ["uploads/qrcode/bundle_barcode", "uploads/qrcode/bundle_qr", "uploads/qrcode/piece_qr"];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                    
                    if (is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
    }
}