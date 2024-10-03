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
    <title>BENSO GARMENTING - Fabric Stock Report</title>

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

    <!-- sweetalert -->
    <link rel="stylesheet" type="text/css" href="src/plugins/sweetalert2/sweetalert2.css">

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
    .dhide {
        display:none;
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

                <div class="card-box mb-30">
                    <?php if(RP_FAB_STOCK!=1) { action_denied(); exit; } ?>
                    <div class="pd-20 d-none">
                        <div class="row">
                            
                            <div class="col-md-2">
                                <label for="">Filter Type</label>
                                <select name="" id="FilterOpt" class="custom-select2 multiple-select"
                                    style="width: 100%" onchange="FilterOpt()">
                                    <option value="">Select</option>
                                    <?php
                                    $var = array(
                                        'buyer' => 'Buyer',
                                        'bonum' => 'BO',
                                        'styleNum' => 'Style',
                                        'prtN' => 'Part',
                                        'colrr' => 'Color',
                                    );
                                    foreach ($var as $ky => $val) {
                                        $typ = ($_GET['type'] == $ky) ? 'selected' : '';
                                        print '<option value="' . $ky . '" ' . $typ . '>' . $val . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2" id="buyerDiv" style="display:<?= ($_GET['type'] == 'buyer') ? 'block' : 'none'; ?>">
                                <label for="">Buyer</label>
                                <br>
                                <select name="brand" id="brand" class="form-control custom-select2" style="width:100%" multiple>
                                    
                                    <?php
                                    $po = mysqli_query($mysqli, "SELECT id, brand_name FROM brand ORDER BY brand_name ASC");
                                    while($io = mysqli_fetch_array($po)) {
                                        $iarr = in_array($io[0], explode(',', $_GET['buyer'])) ? 'selected' : '';
                                        print '<option value="'. $io[0] .'" '. $iarr .'>'. $io[1] .'</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>

                            <div class="col-md-2" id="bonumDiv" style="display:<?= ($_GET['type'] == 'bonum') ? 'block' : 'none'; ?>">
                                <label for="">BO Number</label>
                                <br>
                                <select name="boNmber" id="boNmber" class="form-control custom-select2" style="width:100%" multiple>
                                    
                                    <?php
                                    $po = mysqli_query($mysqli, "SELECT id, order_code FROM sales_order ORDER BY order_code ASC");
                                    while($io = mysqli_fetch_array($po)) {
                                        $iarr = in_array($io[0], explode(',', $_GET['bonum'])) ? 'selected' : '';
                                        print '<option value="'. $io[0] .'" '. $iarr .'>'. $io[1] .'</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>

                            <div class="col-md-2" id="styleNumDiv" style="display:<?= ($_GET['type'] == 'styleNum') ? 'block' : 'none'; ?>">
                                <label for="">Style</label>
                                <br>
                                <select name="styleNum" id="styleNum" class="form-control custom-select2" style="width:100%" multiple>
                                    
                                    <?php
                                    $po = mysqli_query($mysqli, "SELECT id, style_no FROM sales_order_detalis ORDER BY id ASC");
                                    while($io = mysqli_fetch_array($po)) {
                                        $iarr = in_array($io[0], explode(',', $_GET['styleNum'])) ? 'selected' : '';
                                        print '<option value="'. $io[0] .'" '. $iarr .'>'. $io[1] .'</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>

                            <div class="col-md-2" id="prtNDiv" style="display:<?= ($_GET['type'] == 'prtN') ? 'block' : 'none'; ?>">
                                <label for="">Part</label>
                                <br>
                                <select name="prtN" id="prtN" class="form-control custom-select2" style="width:100%" multiple>
                                    
                                    <?php
                                    $po = mysqli_query($mysqli, "SELECT id, part_name FROM part ORDER BY part_name ASC");
                                    while($io = mysqli_fetch_array($po)) {
                                        $iarr = in_array($io[0], explode(',', $_GET['prtN'])) ? 'selected' : '';
                                        print '<option value="'. $io[0] .'" '. $iarr .'>'. $io[1] .'</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>

                            <div class="col-md-2" id="colrrDiv" style="display:<?= ($_GET['type'] == 'colrr') ? 'block' : 'none'; ?>">
                                <label for="">Color</label>
                                <br>
                                <select name="colrr" id="colrr" class="form-control custom-select2" style="width:100%" multiple>
                                    
                                    <?php
                                    $po = mysqli_query($mysqli, "SELECT id, color_name FROM color ORDER BY color_name ASC");
                                    while($io = mysqli_fetch_array($po)) {
                                        $iarr = in_array($io[0], explode(',', $_GET['colrr'])) ? 'selected' : '';
                                        print '<option value="'. $io[0] .'" '. $iarr .'>'. $io[1] .'</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>
                            
                            <div class="col-md-3" id="fromToDiv" >
                                <label for="">Delivery Date From & To date</label>
                                <div class="d-flex">
                                    <input type="date" name="" id="FromDate" class="form-control"
                                        value="<?= $_GET['fdt'] ? $_GET['fdt'] : date('Y-m-01') ?>" style="width:48%">
                                    &nbsp;
                                    <input type="date" name="" id="ToDate" class="form-control"
                                        value="<?= $_GET['tdt'] ? $_GET['tdt'] : date('Y-m-t'); ?>" style="width:48%">
                                </div>
                            </div>
                            

                            <div class="col-md-3">
                                <label for="">&nbsp;</label> <br>
                                <!--<input type="checkbox" name="" id="withImage" class="" <?= ($_GET['ig'] == 'true') ? 'checked' : ''; ?>>-->
                                <input type="button" name="" id="startFilter" onclick="startFilter()"
                                    class="btn btn-outline-secondary" value="Filter">
                                <input value="Clear" type="button"
                                    onclick="window.location.href='rep_sewing.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>'"
                                    class="btn btn-outline-secondary">
                            </div>

                        </div>
                    </div>
                </div>

                <script>
                    function startFilter() {
                        var query = window.location.search.substring(1);
                        var vars = query.split("=");
                        var image = vars[1];

                        var type = $("#FilterOpt").val();
                        var brand = $("#brand").val();
                        var boNmber = $("#boNmber").val();
                        var styleNum = $("#styleNum").val();
                        var prtN = $("#prtN").val();
                        var colrr = $("#colrr").val();
                        
                        var ddt = 'fdt=' + $("#FromDate").val() + '&tdt=' + $("#ToDate").val();
                        
                        if (type == 'buyer') {
                            if (brand == "") {
                                message_noload('warning', 'Buyer Required!', 1500);
                                $("#brand").focus();
                                return false;
                            }
                            var search = '?type=' + type + '&buyer='+ brand +'&' + ddt;
                        } else if (type == 'bonum') {
                            if (boNmber == "") {
                                message_noload('warning', 'Select BO!', 1500);
                                $("#boNmber").focus();
                                return false;
                            }
                            var search = '?type=' + type + '&bonum='+ boNmber +'&' + ddt;
                        } else if (type == 'styleNum') {
                            if (styleNum == "") {
                                message_noload('warning', 'Select Style!', 1500);
                                $("#styleNum").focus();
                                return false;
                            }
                            var search = '?type=' + type + '&styleNum='+ styleNum +'&' + ddt;
                        } else if (type == 'prtN') {
                            if (prtN == "") {
                                message_noload('warning', 'Select Part!', 1500);
                                $("#prtN").focus();
                                return false;
                            }
                            var search = '?type=' + type + '&prtN='+ prtN +'&' + ddt;
                        } else if (type == 'colrr') {
                            if (colrr == "") {
                                message_noload('warning', 'Select Color!', 1500);
                                $("#colrr").focus();
                                return false;
                            }
                            var search = '?type=' + type + '&colrr='+ colrr +'&' + ddt;
                        } else {
                            var search = '?'+ ddt;
                        }

                        window.location.href = "rep_sewing.php" + search;
                    }

                    function FilterOpt() {
                        var a = ['buyer', 'bonum', 'styleNum', 'prtN', 'colrr'];

                        a.forEach(showHide);
                    }

                    function showHide(item, index) {
                        var a = $("#FilterOpt").val();
                        if (a == item) {
                            $("#" + item + "Div").show();
                        } else {
                            $("#" + item + "Div").hide();
                        }
                    }
                    
                </script>

                <!--<div class="card-box mb-5" style="float:right">-->
                <!--    <i class="fa fa-print" style="padding: 15px;" onclick="divToPrint()"></i>-->
                <!--</div>-->

                <!-- Export Datatable start -->
                <div class="card-box mb-30" id="divToPrint">
                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Fabric Stock Report</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table table-striped hover">
                            <?php                                            
                            $qry = "SELECT a.*, b.po_stage, b.order_id, b.material_name, b.bag_roll,b.po_qty_wt,b.rate,b.amount ";
                            $qry .= "FROM fabric_po a ";
                            $qry .= "JOIN fabric_po_det b ON a.id = b.fab_po";
                            $query = mysqli_query($mysqli, $qry);
                            ?>
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Godown Type</th>
                                    <th>Stock Stage</th>
                                    <th>BO No</th>
                                    <th>Order Ref</th>
                                    <th>Stock Ref</th>
                                    <th>Material Name</th>
                                    <th>Bag/ Roll</th>
                                    <th>Wt/ Qty</th>
                                    <th>Stock Rate</th>
                                    <th>Stock Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $x = 1;
                                $num_ = mysqli_num_rows($query);
                                if($num_>0){
                                while ($sql = mysqli_fetch_array($query)) {                                            
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['']; ?></td>
                                        <td><?= process_name($sql['po_stage']); ?></td>
                                        <td><?= sales_order_code($sql['order_id']); ?></td>
                                        <td><?= ($sql['material_name']); ?></td>
                                        <td><?= $sql['']; ?></td>
                                        <td><?= $sql['']; ?></td>
                                        <td><?= $sql['bag_roll']; ?></td>
                                        <td><?= $sql['po_qty_wt']; ?></td>
                                        <td><?= $sql['rate']; ?></td>
                                        <td><?= $sql['amount']; ?></td>
                                    </tr> 
                                    <?php $x++;
                                } ?>

                                <?php } ?>
                            </tbody>
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