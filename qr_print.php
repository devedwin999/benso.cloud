<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


include "phpqrcode/qrlib.php";
 									
$qr_path = 'uploads/qrcode/bundle_qr/';

if (!file_exists($qr_path))
mkdir($qr_path);


require 'barcode1/vendor/autoload.php';
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

$ID = $_GET['id'];
$LAY = $_GET['lay'];

$qrarray = array();
$barcode_array = array();
?>
<!DOCTYPE html>
<html>

<body>

    <?php
    // include('includes/header.php');
    // include('includes/sidebar.php');
    ?>
                        
                        <div class="" id="content_body" style="min-height: 500px;">
                            <div id="divName" style="margin-left:;width:550px;background-color:#fff;display: flex;flex-wrap: wrap;">
                                
                                <?php
                                
                                if(!empty($_GET['comp'])) {
                                    $comp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT component_name FROM mas_component WHERE id = " . $_GET['comp']));
                                    $comNm = $comp['component_name'];
                                } else {
                                    $comNm = '';
                                }
                                
                                $lay = mysqli_fetch_array(mysqli_query($mysqli, "SELECT fabric_lot FROM cutting_barcode WHERE id = '". $ID . "' AND lay_number=" . $LAY));
                                
                                
                                $a = "SELECT a.* ";
                                $a .= " FROM bundle_details a ";
                                $a .= " WHERE  a.cutting_barcode_id='" . $ID . "' AND a.lay_length=" . $LAY;
                                
                                $qry = mysqli_query($mysqli, $a);
                                
                                while ($sql = mysqli_fetch_array($qry)) {
                                    ?>
                                    
                                    <div style="text-align:center;width:47%;height:410px;border: 2px solid black;">
                                        
                                        <table style="width:100%;text-align: center;position: relative;bottom: -15px;">
                                            <tr>
                                                <td>BO: <?= sales_order_code($sql['order_id']); ?></td>
                                                <td>Size: <?= variation_value($sql['variation_value']); ?></td>
                                                <td>Lot: <?= $lay['fabric_lot']; ?></td>
                                            </tr>
                                        </table>
                                        
                                        <span style="position: relative;bottom: -25px;">Bundle No : <?= $sql['bundle_number']; ?> (B.Qty: <?= $sql['pcs_per_bundle']; ?>) </span><br>
                                        <span style="position: relative;bottom: -30px;"><?= color_name($sql['color']); ?></span>
                                        
                                        <?php if($sql['boundle_qr']!="") { ?>
                                            <?php
									
            									$qr_value = $sql['boundle_qr'];
            									$qr_name = $qr_path . 'bundle_qr_' . md5($qr_value) . '.png';
            									
            									$qrarray[] = $qr_name;
            									
            									QRcode::png($qr_value, $qr_name);
            									
            									echo '<img src="' . $qr_path . basename($qr_name) . '"  style="height: 260px; width: 250px;" align="center"/><hr/>';
            									
            									print '<span style="position: relative;top: -45px;">'. $sql['boundle_qr'] .'</span>';
            									
            									$Color = [0, 0, 0];
            									
                                                $barcode = 'uploads/qrcode/bundle_barcode/bundle_barcode'. $qr_value .'.png';
                                                
                                                $barcode_array[] = $barcode;
                                                
                                                file_put_contents($barcode, $generator->getBarcode($qr_value, $generator::TYPE_CODE_128, 3, 50, $Color));
                                                    
                                                print '<img src="'. $barcode .'" style="width:240px;position: relative;top: -20px;height: 43px;">';
            									
            								?>
                                        <span style="position: relative;top: -30px;">
                                            <!--<img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $sql['boundle_qr']; ?>&choe=UTF-8" title="' . $sql['boundle_qr'] . $p . '"  style="width:90%;height:80px"/>-->
                                        </span>
                                        
                                        <?php } else { print 'Not Generated';} ?>
                                        
                                        <span style="position: relative;bottom: 25px;"><?= $comNm ? $comNm : ''; ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
            <?php
            
            $qrarray = json_encode($qrarray);
            $barcode_array = json_encode($barcode_array);
            
             ?>
        </div>
    </div>
    <!-- js -->
    
    <?php
        function small($string, $length)
        {
            return substr($string, 0, $length);
        }
    ?>
    
    
    <script>
        
        $(document).ready(function() {
            
            setTimeout(function() {
                var url = <?= $barcode_array; ?>;
                
                var data = 'value=' + url;
                
                $.ajax({
                    type : 'POST',
                    url : 'ajax_action2.php?delete_barcodeimage',
                    data : data,
                    success: function(msg) {
                        
                    }
                });
            }, 10000);
        });
    </script>
    
    <script type="text/javascript">
    
        $(".printBtn").click(function() {
            
            var divToPrint = document.getElementById('divName');
            var popupWin = window.open();
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();
        });
     
    </script>

</html>