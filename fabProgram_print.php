<?php

include('includes/connection.php');
include('includes/function.php');
include('includes/perm.php');

$ID = $_GET['id'];


$a = "SELECT a.*, b.order_code,b.order_date,b.type, c.brand_name, d.full_name, d.part_count ";
$a .= " FROM sales_order_detalis a ";
$a .= " LEFT JOIN sales_order b ON a.sales_order_id=b.id ";
$a .= " LEFT JOIN brand c ON b.brand=c.id ";
$a .= " LEFT JOIN unit d ON a.unit_id=d.id ";
$a .= " WHERE  a.id='" . $_REQUEST['id'] . "'";

$qry = mysqli_query($mysqli, $a);
$sql = mysqli_fetch_array($qry);



$fab_type = array(
    'FAB_SOLID' => 'Solid',
    'FAB_YANDD' => 'Y/D',
    'FAB_MELANGE' => 'Melange',
);

?>


<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title><?= $sql['style_no']; ?> - Fabric Program Print</title>

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

<style>
    .d-flex {
        display: flex;
    }
    
    .ta-right {
        text-align:right;
    }
    
    .ta-left {
        text-align:left;
    }
    
    .tot-style {
        font-size: 15px;
        font-weight: bold;
    }

    .bold {
        font-weight: bold !important;
    }

    .table td {
        padding: .5rem !important;
    }
</style>

<body>
<?php
include('includes/header.php');
include('includes/sidebar.php');
?>
    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="card-box mb-30" style="max-width:1350px;">

                    <div class="mb-30 text-right" style="padding:10px;">
                        <a class="btn btn-outline-secondary" onclick="window.location.href='fabricProgram.php'">Program List</a>
                        <a class="btn btn-lighe" onclick="divToPrint('divToPrint')"><i class="fa fa-print"></i> Print</a>
                    </div>

                    <div id="divToPrint" class="pd-20 text-center">                        
                        <?php if(FABRIC_PROG_PRINT!=1) { action_denied(); exit; } ?>                
                        <div class="row">
                            <div class="col-md-6 text-left"><h4>Benso Garmenting Pvt Ltd</h4></div>
                            <div class="col-md-6"><h4 style="color:blue">Fabric Requirement</h4></div>
                            <div class="col-md-12"><br></div>
                        </div>
                        
                        <table class="table-bordered table" style="width:1100px;">
                            <tr>
                                <td class="text-right text-blue bold" colspan="2">BO.No:</td>
                                <td><?= $sql['order_code']; ?></td>
                                <td class="text-center text-blue bold" >Style No: <span class="text-dark u"><?= $sql['style_no']; ?></span></td>
                                <td class="text-right text-blue bold">O.Qty:</td>
                                <td><?= ($sql['total_qty'] * $sql['part_count']); ?></td>
                                <td class="text-right text-blue bold" style="min-width: 150px;">CUT PLAN QTY:</td>
                                <td><?= ((round(($sql['total_qty'] * ($sql['excess']/100)) + $sql['total_qty'])) * $sql['part_count']) ?></td>
                            </tr>
                            
                            <tr>
                                <td class="text-right text-blue bold" colspan="2">Buyer:</td>
                                <td><?= $sql['brand_name']; ?></td>
                                <td class="text-center text-blue bold">PART: <span class="text-dark u"><?= $sql['full_name']; ?></span></td>
                                <td class="text-right text-blue bold" style="min-width: 175px;">Fabric Inhouse Date:</td>
                                <td></td>
                                <td colspan="2" class="text-center" style="padding: 4px;text-align: right;background-color: #ffe4e8;font-weight: bold;">Entry Date</td>
                            </tr>
                            
                            <tr>
                                <td class="text-right text-blue bold" colspan="2">Cad Pcs Wt:</td>
                                <td></td>
                                <td class="text-center text-blue bold">Order Qty + <?= $sql['excess']; ?>% PCS Wt</td>
                                <td class="text-right text-blue bold">Style GSM:</td>
                                <td><?= $sql['gsm']; ?></td>
                                <td class="text-center" colspan="2" style="background-color: #ffe4e8;"><?= date('d-m-Y', strtotime($sql['order_date'])); ?></td>
                            </tr>
                            
                            <tr>
                                <td colspan="8" style="padding:15px"></td>
                            </tr>
                            
                            <tr>
                                <td colspan="8" class="text-center bold text-blue">Purchase/Process</td>
                            </tr>
                            
                            <?php
                                $nqy = "SELECT a.*, b.budget_type ";
                                $nqy .= " FROM fabric_requirements a ";
                                $nqy .= " LEFT JOIN process b ON a.process_id = b.id ";
                                $nqy .= " WHERE a.style_id = '". $ID ."' GROUP BY a.process_id ORDER BY a.process_order";
                                
                                $rqryy = mysqli_query($mysqli, $nqy);
                                if(mysqli_num_rows($rqryy)>0) {
                                    while($result = mysqli_fetch_array($rqryy)) {
                                    ?>
                                    <tr>
                                        <?php if($result['budget_type'] == 'Yarn') { ?>
                                            <td>#</td>
                                            <td colspan="6" class="text-center" style="color: pink;">&nbsp;<?= process_name($result['process_id']); ?> - Yarn Requirement</td>
                                            <td style="min-width: 100px;">REQ WT</td>
                                        <?php } else { ?>
                                            <td>#</td>
                                            <td style="color: pink;">&nbsp;<?= process_name($result['process_id']); ?></td>
                                            <td colspan="2">Yarn Mixing %</td>
                                            <td>Loss %</td>
                                            <td>Color</td>
                                            <td>DIA/SIZE</td>
                                            <td>REQ WT</td>
                                        <?php } ?>
                                    </tr>
                                        <?php
                                            $qryy = "SELECT * ";
                                            $qryy .= " FROM fabric_requirements a ";
                                            $qryy .= " WHERE a.process_id = '". $result['process_id'] ."' AND a.style_id = '". $ID ."' ";
                                            $s_res = mysqli_query($mysqli, $qryy);
                                            
                                            $p = 1;
                                            while($row = mysqli_fetch_array($s_res)) {
                                                
                                                $yn_[$p] = "";
                                                
                                                foreach(json_decode($row['yarn_mixing']) as $ynn) {
                                                    $ynn = explode('=', $ynn);
                                                    
                                                    $ycrrr = color_name($ynn[1]) ? ' - '. color_name($ynn[1]) : '';
                                                    
                                                    $yn_[$p] .= mas_yarn_name($ynn[0]).$ycrrr.' - '. $ynn[2].'%, ';
                                                }
                                            ?>
                                            <tr>
                                                <?php if($result['budget_type'] == 'Yarn') { ?>
                                                    <td><?= $row['id']; ?></td>
                                                    <td colspan="6">&nbsp;<?= mas_yarn_name($row['yarn_id']); ?></td>
                                                    <td><?= $row['req_wt']; ?></td>
                                                <?php } else if($result['budget_type'] == 'AOP Design') { ?>
                                                    <td><?= $row['id']; ?></td>
                                                    <td>&nbsp;<?= $fab_type[$row['fabric_type']] .' | ' . fabric_name($row['fabric_id']); ?></td>
                                                    <td colspan="2"><?= rtrim($yn_[$p], ", "); ?></td>
                                                    <td><?= $row['loss_p']; ?> %</td>
                                                    <td><?= color_name($row['color']); ?></td>
                                                    <td><?= $row['dia_size']; ?></td>
                                                    <td><?= $row['req_wt']; ?></td>
                                                <?php } else { ?>
                                                    <td><?= $row['id']; ?></td>
                                                    <td>&nbsp;<?= $fab_type[$row['fabric_type']] .' | ' . fabric_name($row['fabric_id']); ?></td>
                                                    <td colspan="2"><?= rtrim($yn_[$p], ", "); ?></td>
                                                    <td><?= $row['loss_p']; ?> %</td>
                                                    <td><?= color_name($row['color']); ?></td>
                                                    <td><?= $row['dia_size']; ?></td>
                                                    <td><?= $row['req_wt']; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                            $p++; }
                                            
                                            $qq1 = "SELECT a.fabric_id, a.fabric_type, sum(a.req_wt) as req_wtt ";
                                            $qq1 .= " FROM fabric_requirements a ";
                                            $qq1 .= " WHERE a.process_id = '". $result['process_id'] ."' AND a.style_id = '". $ID ."' GROUP BY a.fabric_id, a.fabric_type ";
                                            
                                            $ry1 = mysqli_query($mysqli, $qq1);
                                            $pp=0;
                                            while($nql1 = mysqli_fetch_array($ry1)) {
                                                
                                                $pp += $nql1['req_wtt'];
                                                ?>
                                                    <tr class="tot-style">
                                                        <td class="ta-right" style="text-align:right;" colspan="7"><?php if($result['budget_type'] != 'Yarn') { print $fab_type[$nql1['fabric_type']] .' | ' . fabric_name($nql1['fabric_id']) .' | '; } ?> Total:</td>
                                                        <td><?= $nql1['req_wtt']; ?></td>
                                                    </tr>
                                                    
                                            <?php } ?>
                                            <tr class="tot-style">
                                                <td class="ta-right" style="text-align:right;" colspan="7">Process Total:</td>
                                                <td><?= $pp; ?></td>
                                            </tr>
                                    <tr>
                                        <td colspan="7" style="padding:10px"></td>
                                    </tr>
                                    <?php 
                                }
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>

</body>
</html>










