<?php

include('includes/connection.php'); 

// require 'barcode/vendor/autoload.php';
// $generator = new Picqer\Barcode\BarcodeGeneratorHTML();

// online ref : https://github.com/picqer/php-barcode-generator

?>

<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
    integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

<!-- <script type="text/javascript">
    $(document).ready(function () {
        var divToPrint = document.getElementById('divToPrint');
        var popupWin = window.open();
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
    })
</script> -->


<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Piece Barcode Print</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">
    
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>

<style>
    .d-flex {
        display: flex;
    }
</style>

<body>
    <div id="divName" style="display:flex;flex-wrap: wrap;font-family: Calibri;width:574px !important; background-color:#fff;margin-top:-15px;margin-left:-5px;">
        <?php
        
        $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.*, c.order_code, d.type FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id LEFT JOIN sales_order c ON b.order_id=c.id 
        LEFT JOIN variation_value d ON a.variation_value=d.id WHERE a.id=" . $_GET['bid']));

        if($sql['boundle_qr']!="") {
            for ($p = 1; $p <= $sql['pcs_per_bundle']; $p++) {
                
                $pce = $sql['boundle_qr'].'-'.$p;
                ?>
                
                <div style="width:32%;height: ;text-align: center;position: relative;display: inline-block;">
                    
                    <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $sql['id'].'-'. $p ?>&choe=UTF-8" title="' . $sql['boundle_qr'] . $p . '"  style="width:85%;height:70px; margin-top:10px;"/>
                    
                    <!--<? //= $generator->getBarcode($sql['id'].'-'. $p, $generator::TYPE_CODE_128, 1, 40); ?><br>-->
                    <div style="position: absolute;bottom: 0px;background-color: #fff;width: 100%">
                        <span style=""><?= $sql['type'].' | '. $sql['bundle_number'].' | '. $p; ?></span>
                    </div>
                </div>
                
                
        <?php } } ?>
    </div>
</body>

<script>
// $(document).ready(function() {
    
//     setTimeout(function() {
//         var printContents = document.getElementById('divName').innerHTML;
//             var originalContents = document.body.innerHTML;
    
//             document.body.innerHTML = printContents;
    
//             window.print();
    
//             document.body.innerHTML = originalContents;
//     }, 2000)
// })
    // function printDiv(divName) {
    //     var printContents = document.getElementById(divName).innerHTML;
    //     var originalContents = document.body.innerHTML;

    //     document.body.innerHTML = printContents;

    //     window.print();

    //     document.body.innerHTML = originalContents;
    // }
</script>


                        <!--<img src="uploads/qrcode/<?= $sql['boundle_qr'] . '/piece/' . $sql['boundle_qr'] . $p . '.png'; ?>" alt="" height="70px">-->
                        <!--<img src="https://barcode.orcascan.com/?type=code128&data=<?= $sql['boundle_qr'] .'-'. $p ?>&choe=UTF-8" title="' . $sql['boundle_qr'] . $p . '" style="width:150px"/>&nbsp;&nbsp;&nbsp;-->
                        <!--<img src="https://barcode.tec-it.com/en/Code128?data=<?= $sql['boundle_qr'] .'-'. $p ?>&choe=UTF-8" title="' . $sql['boundle_qr'] . $p . '" style="width:150px"/>&nbsp;&nbsp;&nbsp;-->