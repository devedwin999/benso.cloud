<?php
include('includes/connection.php');
include('includes/function.php');
include 'barcode1/barcode128.php';

$dta = base64_decode($_REQUEST['data']);

require 'barcode1/vendor/autoload.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

$folder = 'uploads/piece_barcode/';

$imageFiles = glob($folder . '*.png');

foreach ($imageFiles as $file) {
    
    if (is_file($file)) {
        unlink($file);
    }
}
?>

    <div id="divName" style="font-family: Calibri;width:80px !important; background-color:#fff;margin-left: 140px;">
        <?php
        foreach(explode(',', $dta) as $value) {
            
        $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.* FROM bundle_details a WHERE a.id=" . $value));
        if($sql['boundle_qr']!="") {
            for ($p = 0; $p < $sql['pcs_per_bundle']; $p++) {
                
                $tty = range(1, $sql['pcs_per_bundle']);
                rsort($tty);
                
                $pce = $sql['boundle_qr'].'-'.$tty[$p];
                ?>
                
                <!--<div style="width:32%;height: 65px;margin-top: 18px;text-align: center;position: relative;display: inline-block;">-->
                        
                        <?php
                        
                        $Color = [0, 0, 0];
                        
                        $path = 'uploads/piece_barcode/'. $sql['id'].'-'. $p .'.png';
                        
                        file_put_contents($path, $generator->getBarcode($sql['id'].'-'. $p, $generator::TYPE_CODE_128, 3, 50, $Color));
                            
                        print '<img src="'. $path .'" style="margin-top:3px;width:80px;margin-left:7px">';
                        ?>
                    <!--</span>-->
                    
                    <div>
                        <span style="margin-left:7px;font-size:10px;"><?= sales_order_code($sql['order_id']).' | '. $sql['lay_length'] .' | '. variation_value($sql['variation_value']) .' | '. $sql['bundle_number'].' | '. $tty[$p]; ?></span>
                        <!--<span style="margin-left:7px"><?= sales_order_code($sql['order_id']).' | '. $sql['lay_length'] .' | '. variation_value($sql['variation_value']) .' | '. $sql['bundle_number'].' | '. $p; ?></span>-->
                    </div>
                    <br>
                <!--</div>-->
                
        <?php } } } ?>
    </div>

