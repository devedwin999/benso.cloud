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
    <title>BENSO - Daily Production Report</title>

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
    
    .cutting, .checking {
        background: #69ff91;
    }
    
    .sewingin, .packing {
        background: #e6d5ff;
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
                    <?php if(RP_PRODUCTION_REGISTER!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Filter Type</label>
                                <select name="" id="FilterOpt" class="custom-select2 multiple-select"
                                    style="width: 100%" onchange="FilterOpt()">
                                    <option value="">Select</option>
                                    <?php
                                    $var = array(
                                        'employee' => 'Employee',
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

                            <!--<div class="col-md-3" id="fromToDiv" style="display:<?= ($_GET['type'] == 'fromTo') ? 'block' : 'none'; ?>">-->
                            <div class="col-md-3" id="fromToDiv" >
                                <label for="">Production Start & End Date</label>
                                <div class="d-flex">
                                    <input type="date" name="" id="FromDate" class="form-control" value="<?= $_GET['fdt'] ? $_GET['fdt'] : date('Y-m-d') ?>" style="width:50%">
                                    <input type="date" name="" id="ToDate" class="form-control" value="<?= $_GET['tdt'] ? $_GET['tdt'] : date('Y-m-d') ?>"  style="width:50%">
                                </div>
                            </div>

                            <div class="col-md-3" id="employeeDiv" style="display:<?= ($_GET['type'] == 'employee') ? 'block' : 'none'; ?>">
                                <label for="">Employee</label>
                                <br>
                                <select name="employee" id="employee" class="form-control custom-select2" style="width:100%">
                                    
                                    <?php
                                    $po = mysqli_query($mysqli, "SELECT id, employee_name FROM employee_detail WHERE type='employee' AND is_active='active' ORDER BY employee_name ASC");
                                    print '<option value="">Select</option>';
                                    while($io = mysqli_fetch_array($po)) {
                                        $iarr = ($io[0] == $_GET['employee']) ? 'selected' : '';
                                        print '<option value="'. $io[0] .'" '. $iarr .'>'. $io[1] .'</option>';
                                    }
                                    ?>
                                    
                                </select>
                            </div>

                            <div class="col-md-3" id="buyerDiv" style="display:<?= ($_GET['type'] == 'buyer') ? 'block' : 'none'; ?>">
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

                            <div class="col-md-3" id="bonumDiv" style="display:<?= ($_GET['type'] == 'bonum') ? 'block' : 'none'; ?>">
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

                            <div class="col-md-3" id="styleNumDiv" style="display:<?= ($_GET['type'] == 'styleNum') ? 'block' : 'none'; ?>">
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

                            <div class="col-md-3" id="prtNDiv" style="display:<?= ($_GET['type'] == 'prtN') ? 'block' : 'none'; ?>">
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

                            <div class="col-md-3" id="colrrDiv" style="display:<?= ($_GET['type'] == 'colrr') ? 'block' : 'none'; ?>">
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
                            

                            <div class="col-md-3">
                                <label for="">&nbsp;</label> <br>
                                <!--<input type="checkbox" name="" id="withImage" class="" <?= ($_GET['ig'] == 'true') ? 'checked' : ''; ?>>-->
                                <input type="button" name="" id="startFilter" onclick="startFilter()" class="btn btn-outline-secondary" value="Filter">
                                <input value="Clear" type="button" onclick="window.location.href='rep_dailyProduction.php'" class="btn btn-outline-secondary">
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
                        var employee = $("#employee").val();
                        
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
                        } else if (type == 'employee') {
                            if (employee == "") {
                                message_noload('warning', 'Select Employee!', 1500);
                                $("#employee").focus();
                                return false;
                            }
                            var search = '?type=' + type + '&employee='+ employee +'&' + ddt;
                        } else {
                            var search = '?'+ ddt;
                        }

                        window.location.href = "rep_dailyProduction.php" + search;
                    }

                    function FilterOpt() {
                        var a = ['employee', 'buyer', 'bonum', 'styleNum', 'prtN', 'colrr'];

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

                <div class="card-box mb-5" style="float:right">
                    <i class="fa fa-print" style="padding: 15px;" onclick="divToPrint()"></i>
                </div>

                <!-- Export Datatable start -->
                <div class="card-box mb-30" id="divToPrint">
                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Daily Production Report</h4>
                    </div>
                    <div class="pb-20" style="overflow-y: auto;">
                        <?php if(isset($_GET['fdt'])) { ?>
                            <table class="table table-striped table-bordered">
                                <?php
                                // $qry = "SELECT a.*, b.order_code, b.order_qty, b.delivery_date, sum(d.pcs_per_bundle) ct_qty, c.part, e.color_name, f.part_name, g.company_code, h.brand_name  ";
                                // $qry .= " FROM sales_order_detalis a ";
                                // $qry .= " LEFT JOIN sales_order b ON a.sales_order_id=b.id ";
                                // $qry .= " LEFT JOIN cutting_barcode c ON a.id=c.style ";
                                // $qry .= " LEFT JOIN bundle_details d ON c.id=d.cutting_barcode_id ";
                                // $qry .= " LEFT JOIN color e ON c.color=e.id ";
                                // $qry .= " LEFT JOIN part f ON c.part=f.id ";
                                // $qry .= " LEFT JOIN company g ON d.cuttiing_unit=g.id ";
                                // $qry .= " LEFT JOIN brand h ON b.brand=h.id ";
                                
                                // if (isset($_GET['type'])) {
                                //     $typ = $_GET['type'];
                                    
                                //     if ($typ == 'buyer') {
                                //         $qry .= "WHERE b.brand IN (" . $_GET['buyer'] . ") ";
                                //     } else if ($typ == 'bonum') {
                                //         $qry .= "WHERE b.id IN (" . $_GET['bonum'] . ") ";
                                //     } else if ($typ == 'styleNum') {
                                //         $qry .= "WHERE a.id IN (" . $_GET['styleNum'] . ") ";
                                //     } else if ($typ == 'prtN') {
                                //         $qry .= "WHERE c.part IN (" . $_GET['prtN'] . ") ";
                                //     } else if ($typ == 'colrr') {
                                //         $qry .= "WHERE c.color IN (" . $_GET['colrr'] . ") ";
                                //     }
                                // } else {
                                //     $onDt = date('Y-m-d');
                                //     // $qry .= " b.delivery_date BETWEEN '" . date('Y-m-d') . "' AND '" . $_GET['tdt'] . "' ";
                                // }
                                
                                // if ($_SESSION['login_role'] != '1') {
                                //     $qry .= " AND g.id= '" . $_SESSION['loginCompany'] . "'";
                                // }
                                
                                // $qry .= " GROUP BY c.style,c.part ";
                                // $qry .= " ORDER BY b.delivery_date ASC ";
                                // print $qry;
                                
                                
                                
                                $qry = "SELECT sum(a.pcs_per_bundle) ct_qty, c.order_code, d.color_name, e.part_name, b.part, b.color, b.style, f.item_image, f.style_no, f.total_qty ";
                                $qry .= " FROM bundle_details a ";
                                $qry .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                $qry .= " LEFT JOIN sales_order c ON b.order_id=c.id ";
                                $qry .= " LEFT JOIN color d ON b.color=d.id ";
                                $qry .= " LEFT JOIN part e ON b.part=e.id ";
                                $qry .= " LEFT JOIN sales_order_detalis f ON b.style = f.id ";
                                
                                
                                if (isset($_GET['type'])) {
                                    $typ = $_GET['type'];
                                    
                                    if ($typ == 'buyer') {
                                        $qry .= "WHERE b.brand IN (" . $_GET['buyer'] . ") ";
                                    } else if ($typ == 'bonum') {
                                        $qry .= "WHERE b.id IN (" . $_GET['bonum'] . ") ";
                                    } else if ($typ == 'styleNum') {
                                        $qry .= "WHERE a.id IN (" . $_GET['styleNum'] . ") ";
                                    } else if ($typ == 'prtN') {
                                        $qry .= "WHERE a.part IN (" . $_GET['prtN'] . ") ";
                                    } else if ($typ == 'colrr') {
                                        $qry .= "WHERE a.color IN (" . $_GET['colrr'] . ") ";
                                    }
                                } else {
                                    $onDt = date('Y-m-d');
                                }
                                
                                if ($_SESSION['login_role'] != '1') {
                                    $qry .= " AND a.cuttiing_unit= '" . $_SESSION['loginCompany'] . "'";
                                }
                                
                                $qry .= " GROUP BY b.style, b.part, b.color ";
                                $qry .= " ORDER BY b.id DESC ";
                                
                                
                                // print $qry;
                                
                                
                                
                                
                                $query = mysqli_query($mysqli, $qry);
                                ?>
                                <thead>
                                    <tr>
                                        <th colspan="24"
                                            style="text-align:center">Production Date <?= date('d-m-Y', strtotime($_GET['fdt'])); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">Sl.No</th>
                                        <th rowspan="2">Style Image</th>
                                        <th rowspan="2">BO</th>
                                        <th rowspan="2">Style</th>
                                        <th rowspan="2">Part</th>
                                        <th rowspan="2">Color</th>
                                        <th rowspan="2">Order Qty</th>
                                        <th rowspan="2">Cut PlanQty</th>
                                        <th colspan="2" class="cutting">Cutting Qty</th>
                                        <th colspan="2" class="ps_out">Process Outward</th>
                                        <th colspan="2" class="ps_inw">Process Inward</th>
                                        <th colspan="2" class="sewingin">Sewing In</th>
                                        <th colspan="2" class="sewingout">Sewing Out</th>
                                        <th colspan="2" class="checking">Checking Qty</th>
                                        <th colspan="2" class="ironing">Ironing Qty</th>
                                        <th colspan="2" class="packing">Packing Qty</th>
                                    </tr>
                                    <tr>
                                        <th class="cutting">Tdy</th>
                                        <th class="cutting">TTL</th>
                                        <th class="ps_out">Tdy</th>
                                        <th class="ps_out">TTL</th>
                                        <th class="ps_inw">Tdy</th>
                                        <th class="ps_inw">TTL</th>
                                        <th class="sewingin">Tdy</th>
                                        <th class="sewingin">TTL</th>
                                        <th class="sewingout">Tdy</th>
                                        <th class="sewingout">TTL</th>
                                        <th class="checking">Tdy</th>
                                        <th class="checking">TTL</th>
                                        <th class="ironing">Tdy</th>
                                        <th class="ironing">TTL</th>
                                        <th class="packing">Tdy</th>
                                        <th class="packing">TTL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $x = 1;
                                    $num_ = mysqli_num_rows($query);
                                    if($num_>0){
                                    while ($sql = mysqli_fetch_array($query)) {
                                        
                                        if($_GET['employee']) {
                                            $toto = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.created_date BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' AND b.employee = '". $_GET['employee'] ."' "));
                                            
                                            $toto_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND b.employee = '". $_GET['employee'] ."' "));
                                                
                                            $sw_In = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.in_sewing='yes' AND `in_sewing_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' AND line='". $_GET['employee'] ."'"));
                                                
                                            $sw_comp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.complete_sewing='yes' AND `comp_sewing_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' AND line='". $_GET['employee'] ."' "));
                                                
                                            $sw_comp_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.complete_sewing='yes' AND line='". $_GET['employee'] ."' "));
                                                
                                            $sw_In_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.in_sewing='yes' AND line='". $_GET['employee'] ."'"));
                                                
                                            // $test_check = mysqli_query($mysqli, "SELECT a.ch_good_pcs FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                            //         WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.checking_complete='yes' AND `checking_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'  AND checking_employee='". $_GET['employee'] ."' ");
                                        } else {
                                            $toto = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.created_date BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'  "));
                                                
                                            $toto_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND '" . $_GET['tdt'] . "'  "));
                                                
                                            $sw_In = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.in_sewing='yes' AND `in_sewing_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' "));
                                                
                                            $sw_In_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.in_sewing='yes' "));
                                                
                                            $sw_comp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.complete_sewing='yes' AND `comp_sewing_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' "));
                                                
                                            $sw_comp_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.complete_sewing='yes' "));
                                                
                                            // $test_check = mysqli_query($mysqli, "SELECT a.ch_good_pcs FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                            //         WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.checking_complete='yes' AND `checking_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'  ");
                                        }
                                        
                                        // while($ch_comp = mysqli_fetch_array($test_check)) {
                                        //     $bhpp[$x][] = count(explode(',', $ch_comp['ch_good_pcs']));
                                        // }
                                                
                                            
                                        $ch_comp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.tot_good_pcs) as tot_good_pcs FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                            WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND `checking_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'"));
                                            
                                        $ch_comp_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.tot_good_pcs) as tot_good_pcs FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                            WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' "));
                                                
                                        $pr_outw = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                            WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.in_proseccing='yes' AND `in_proseccing_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'  "));
                                                
                                        $pr_outw_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                                WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.in_proseccing='yes' "));
                                                
                                        $pr_inw = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                            WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.complete_processing='yes' AND `complete_processing_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'  "));
                                                
                                        $pr_inw_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id 
                                            WHERE b.style='" . $sql['style'] . "' AND b.part = '". $sql['part'] ."' AND a.complete_processing='yes' "));
                                                
                                        $iron = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.ironing_qty) as ironing_qty FROM ironing_detail a 
                                            WHERE a.style_id='" . $sql['style'] . "' AND part_id = '". $sql['part'] ."' AND `complete_processing_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'"));
                                                
                                        $iron_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.ironing_qty) as ironing_qty FROM ironing_detail a 
                                            WHERE a.style_id='" . $sql['style'] . "' AND part_id = '". $sql['part'] ."'"));
                                        
                                        $pack = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.packing_qty) as packing_qty FROM packing_detail a 
                                            WHERE a.style_id='" . $sql['style'] . "' AND part_id = '". $sql['part'] ."' AND `entry_date` BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "'"));
                                        
                                        $pack_ttl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.packing_qty) as packing_qty FROM packing_detail a 
                                            WHERE a.style_id='" . $sql['style'] . "' AND part_id = '". $sql['part'] ."' "));
                                        
                                        
                                        if($toto['pcs_per_bundle']>0 || $pr_outw['pcs_per_bundle']>0 || $pr_inw['pcs_per_bundle']>0 || $sw_In['pcs_per_bundle']>0 || $sw_comp['pcs_per_bundle']>0 || $ch_comp['tot_good_pcs']>0 || $iron['ironing_qty']>0 || $pack['packing_qty']>0) {
                                        ?>
                                        <tr>
                                            <td><?= $x; ?></td>
                                            <td><img src="uploads/so_img/<?= $sql['order_code'] . '/' . $sql['item_image']; ?>" alt="-" width="50"></td>
                                            <td><?= $sql['order_code']; ?></td>
                                            <td><?= $sql['style_no']; ?></td>
                                            <td><?= $sql['part_name']; ?></td>
                                            <td><?= $sql['color_name']; ?></td>
                                            <td><?= $tdd11[] = $sql['total_qty']; ?></td>
                                            <td><?= $tdd22[] = ($sql['total_qty'] + $sql['total_excess']); ?></td>
                                            <td class="cutting"><?= $tdd33[] = $toto['pcs_per_bundle'] ? $toto['pcs_per_bundle'] :'0'; ?></td>
                                            <td class="cutting"><?= $tdd33_ttl[] = $toto_ttl['pcs_per_bundle'] ? $toto_ttl['pcs_per_bundle'] :'0'; ?></td>
                                            <td class="ps_out"><?= $tdd44[] = $pr_outw['pcs_per_bundle'] ? $pr_outw['pcs_per_bundle'] :0; ?></td>
                                            <td class="ps_out"><?= $tdd44_ttl[] = $pr_outw_ttl['pcs_per_bundle'] ? $pr_outw_ttl['pcs_per_bundle'] :0; ?></td>
                                            <td class="ps_inw"><?= $tdd55[] = $pr_inw['pcs_per_bundle'] ? $pr_inw['pcs_per_bundle'] :0; ?></td>
                                            <td class="ps_inw"><?= $tdd55_ttl[] = $pr_inw_ttl['pcs_per_bundle'] ? $pr_inw_ttl['pcs_per_bundle'] :0; ?></td>
                                            <td class="sewingin"><?= $tdd66[] = $sw_In['pcs_per_bundle'] ? $sw_In['pcs_per_bundle'] :0; ?></td>
                                            <td class="sewingin"><?= $tdd66_ttl[] = $sw_In_ttl['pcs_per_bundle'] ? $sw_In_ttl['pcs_per_bundle'] :0; ?></td>
                                            <td class="sewingout"><?= $tdd77[] = $sw_comp['pcs_per_bundle'] ? $sw_comp['pcs_per_bundle'] :0; ?></td>
                                            <td class="sewingout"><?= $tdd77_ttl[] = $sw_comp_ttl['pcs_per_bundle'] ? $sw_comp_ttl['pcs_per_bundle'] :0; ?></td>
                                            <td class="checking"><?= $tdd88[] = $ch_comp['tot_good_pcs'] ? $ch_comp['tot_good_pcs'] :0; ?> <? //= $tdd88[] = (array_sum($bhpp[$x])>0) ? array_sum($bhpp[$x]) : 0;?></td> 
                                            <td class="checking"><?= $tdd88_ttl[] = $ch_comp_ttl['tot_good_pcs'] ? $ch_comp_ttl['tot_good_pcs'] :0; ?> <? //= $tdd88[] = (array_sum($bhpp[$x])>0) ? array_sum($bhpp[$x]) : 0;?></td> 
                                            <td class="irolning"><?= $tdd99[] = $iron['ironing_qty'] ? $iron['ironing_qty'] : 0; ?></td>
                                            <td class="irolning"><?= $tdd99_ttl[] = $iron_ttl['ironing_qty'] ? $iron_ttl['ironing_qty'] : 0; ?></td>
                                            <td class="packing"><?= $tdd00[] = $pack['packing_qty'] ? $pack['packing_qty'] : 0; ?></td>
                                            <td class="packing"><?= $tdd00_ttl[] = $pack_ttl['packing_qty'] ? $pack_ttl['packing_qty'] : 0; ?></td>
                                        </tr> 
                                        <?php $x++;
                                        $CPT[] = $toto['pcs_per_bundle'];
                                    } } ?>
                                    
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" style="text-align: right;">Total &nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td><?= array_sum($tdd11); ?></td>
                                            <td><?= array_sum($tdd22); ?></td>
                                            <td class="cutting"><?= array_sum($tdd33); ?></td>
                                            <td class="cutting"><?= array_sum($tdd33_ttl); ?></td>
                                            <td class="ps_out"><?= array_sum($tdd44); ?></td>
                                            <td class="ps_out"><?= array_sum($tdd44_ttl); ?></td>
                                            <td class="ps_inw"><?= array_sum($tdd55); ?></td>
                                            <td class="ps_inw"><?= array_sum($tdd55_ttl); ?></td>
                                            <td class="sewingin"><?= array_sum($tdd66); ?></td>
                                            <td class="sewingin"><?= array_sum($tdd66_ttl); ?></td>
                                            <td class="sewingout"><?= array_sum($tdd77); ?></td>
                                            <td class="sewingout"><?= array_sum($tdd77_ttl); ?></td>
                                            <td class="checking"><?= array_sum($tdd88); ?></td>
                                            <td class="checking"><?= array_sum($tdd88_ttl); ?></td>
                                            <td class="ironing"><?= array_sum($tdd99); ?></td>
                                            <td class="ironing"><?= array_sum($tdd99_ttl); ?></td>
                                            <td class="packing"><?= array_sum($tdd00); ?></td>
                                            <td class="packing"><?= array_sum($tdd00_ttl); ?></td>
                                        </tr>
                                    </tfoot>
                                    <?php } ?>
                                    <!-- cutting department end -->
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>

</body>

</html>