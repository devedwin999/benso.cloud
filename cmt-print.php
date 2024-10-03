<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Piece Barcode Print</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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
                    <?php if(CUTTING_QR_GENERATE!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Piece Barcode Print</h4>
                        <input type="hidden" value="<?= $_GET['dta']; ?>" id="url_data">
                        
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-info printBtn" style="float:right;"><i class="fa fa-print"></i> Print</a>
                            <a class="btn btn-outline-primary" href="<?= ($_GET['ret']=='cmt_budget') ? 'cmt_budget' : 'budget_app'; ?>.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Budget List</a>
                        </div>
                    </div>
                    
                    <div class="pb-20">
                        <br><br>
                        <div class="pd-20" id="divName">
                            <table class="table table-bordered" style="border-collapse: collapse;width:100%;" border="1">
                                <?php
                                    $qry = mysqli_query($mysqli, "SELECT a.*, b.created_unit, b.brand FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id WHERE a.sales_order_id = ". $_GET['id']);
                                    while($result = mysqli_fetch_array($qry)) {
                                ?>
                                    <tr style="text-align:center;">
                                        <td colspan="6">
                                            <h4><?= sales_order_code($result['sales_order_id']); ?></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">BUYER : <?= brand_name($result['brand']); ?></td>
                                        <td colspan="2" rowspan="3" style="text-align: center;"><?= viewImage($result['item_image'], 100); ?> </td>
                                        <td colspan="2">ORDER QTY - Pcs </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Style : <?= sales_order_style($result['id']); ?></td>
                                        <td colspan="2"><?= $result['total_qty']; ?> Pcs </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">UNIT : <?= company_name($result['created_unit']); ?></td>
                                        <td colspan="2">-</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Process</td>
                                        <td>Style Dsc</td>
                                        <td>Colour</td>
                                        <td>Order Qty</td>
                                        <td>Rate</td>
                                        <td>Approved</td>
                                    </tr>
                                    <?php
                                        $pcs = mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Production Budget' AND so_id = '". $result['sales_order_id'] ."' ");
                                        while($nos = mysqli_fetch_array($pcs)) {
                                    ?>
                                    <tr>
                                        <td><?= process_name($nos['process']); ?></td>
                                        <td><?= $result['style_des'] ? $result['style_des'] : '-'; ?></td>
                                        <td>-</td>
                                        <td><?= $result['total_qty']; ?></td>
                                        <td><?= $nos['rate']; ?></td>
                                        <td><?= ($nos['is_approved'] == 'true') ? '<span style="color:green;">Approved</span>' : '<span style="color:red;">Not-Approved</span>'; ?></td>
                                    </tr>
                                    
                                <?php } } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
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