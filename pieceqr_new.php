<?php
include("includes/connection.php");
include("includes/function.php");
include("includes/perm.php");

include "phpqrcode/qrlib.php";

$dta = base64_decode($_POST['data']);

 									
$qr_path = 'uploads/qrcode/piece_qr/';

if (!file_exists($qr_path)) {
    mkdir($qr_path);
}

?>


<div id="divToPrint" style="font-family: Calibri;width:150px !important; background-color:#fff;text-align:center;">
    <?php
        foreach(explode(',', $dta) as $value) {
            
        $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.* FROM bundle_details a WHERE a.id=" . $value));
        
        print '<p style="text-align:center;padding: 7px;">Bundle No <br> '. $sql['bundle_number'] .'</p><br>';
        if($sql['boundle_qr']!="") {
            for ($p = 0; $p < $sql['pcs_per_bundle']; $p++) {
                
                $tty = range(1, $sql['pcs_per_bundle']);
                rsort($tty);
                
                $pce = $sql['boundle_qr'].'-'.$tty[$p];
                
                $Color = [0, 0, 0];
                $path = $qr_path . $sql['id'].'-'. $p .'.png';
                
                $qr_value = $sql['id'].'-'. $p;
                
				QRcode::png($qr_value, $path);
				
                // file_put_contents($path, $generator->getBarcode($sql['id'].'-'. $p, $generator::TYPE_CODE_128, 3, 50, $Color));
                            
                print '<img src="'. $path .'" style="margin-top:-15px;width:55px;margin-left:10px;">';
            ?>
                    
            <div style="text-align:center;">
                <span style="position: relative;left: 2px;top: -5px;font-size:8px;"><?= sales_order_code($sql['order_id']).' | '. $sql['lay_length'] .' | '. variation_value($sql['variation_value']); ?></span>
            </div>
            <div style="text-align:center;">
                <span style="position: relative;left: 2px;top: -5px;font-size:8px;"><?= $sql['bundle_number'].' | '. $tty[$p]; ?></span>
            </div>
            <br>
            
    <?php } } else { print '<div class="col-md-12 text-center"><h5 style="margin-top: 100px">Barcode Not Generated..</h5></div>';} } ?>
</div>

<?php
    function small($string, $length) {
        return substr($string, 0, $length);
    }
?>