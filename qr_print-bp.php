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

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Bundle Barcode Print</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="card-box mb-30">
                    
                    <?php if(MAS_YARN!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">Bundle Barcode Print</h4>
                        <input type="hidden" value="<?= $_GET['dta']; ?>" id="url_data">
                        
                        <a class="btn btn-outline-primary printBtn" style="float:right;"><i class="fa fa-print"></i> Print</a>
                    </div>
                    <div class="pb-20">
                        
                        <div class="row" id="content_body" style="min-height: 500px;">
                            <div id="divName" style="margin-left:20%;width:550px;background-color:#fff;display: flex;flex-wrap: wrap;">
                                
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
                                    
                                    <div style="text-align:center;width:50%;height:438px;border: 2px solid black;">
                                        
                                        <table style="width:100%;text-align: center;position: relative;bottom: -15px;">
                                            <tr>
                                                <td>BO: <?= sales_order_code($sql['order_id']); ?></td>
                                                <td>Size: <?= variation_value($sql['variation_value']); ?></td>
                                                <td>Lot: <?= $lay['fabric_lot']; ?></td>
                                            </tr>
                                        </table>
                                        
                                        
                                        <span style="position: relative;bottom: -25px;">Bundle No : <?= $sql['bundle_number']; ?> (B.Qty: <?= $sql['pcs_per_bundle']; ?>) </span><br>
                                        
                                        
                                        <span style="position: relative;bottom: -30px;"><?= $sql['color_name']; ?></span>
                                        
                                        <?php if($sql['boundle_qr']!="") { ?>
                                            <?php
									
            									$qr_value = $sql['boundle_qr'];
            									$qr_name = $qr_path . 'bundle_qr_' . md5($qr_value) . '.png';
            									
            									$qrarray[] = $qr_name;
            									
            									QRcode::png($qr_value, $qr_name);
            									
            									echo '<img src="' . $qr_path . basename($qr_name) . '"  style="height: 260px;" align="center"/><hr/>';
            									
            									print '<span style="position: relative;top: -60px;">'. $sql['boundle_qr'] .'</span>';
            									
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
                    </div>
                </div>
                
            </div>
            <?php
            
            $qrarray = json_encode($qrarray);
            $barcode_array = json_encode($barcode_array);
            
            include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <?php
        function small($string, $length)
        {
            return substr($string, 0, $length);
        }
    ?>
    
    
    <script>
        $(document).ready(function() {
            
            setTimeout(function() {
                var url = <?= $qrarray; ?>;
                
                var data = 'value=' + url;
                
                $.ajax({
                    type : 'POST',
                    url : 'ajax_action2.php?delete_qrimage',
                    data : data,
                    success: function(msg) {
                        
                    }
                });
            }, 5000);
        });
        
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
            }, 5000);
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