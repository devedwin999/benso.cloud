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
    <title>BENSO - To Be Sent Orders</title>

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
					<div class="pd-20">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-blue">Order In Hand - Summary</h4>
                            </div>

                            <div class="col-md-6">
                                <a class="btn btn-light float-right" onclick="divToPrint('divToPrint')"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                                    <a class="btn btn-outline-info" href="toBeSent_abs.php"><i class="fa fa-file" aria-hidden="true"></i> Buyer Wise</a>
                                    <a class="btn btn-info" href="toBeSent.php" ><i class="fa fa-file" aria-hidden="true"></i> Summary</a>
                                    <a class="btn btn-outline-info" href="order-in-hand.php" ><i class="fa fa-file" aria-hidden="true"></i> Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box mb-30">
                    <?php if(TOBE_SENT!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="">Filter Type</label>
                                <select name="" id="FilterOpt" class="custom-select2 multiple-select" style="width: 100%" onchange="FilterOpt()">
                                    <option value="">Select</option>
                                    <?php
                                    $var = array(
                                        'buyer' => 'Buyer',
                                        'bonum' => 'BO',
                                        // 'styleNum' => 'Style',
                                        // 'prtN' => 'Part',
                                        // 'colrr' => 'Color',
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
                                <label for="">Delivery Date From & To date <input type="checkbox" <?= ($_GET['del_date']=='true') ? 'checked' : ''; ?> id="del_date"></label>
                                <div class="d-flex">
                                    <input type="date" name="" id="FromDate" class="form-control"
                                        value="<?= $_GET['fdt'] ? $_GET['fdt'] : date('Y-m-01') ?>" style="width:48%">
                                    &nbsp;
                                    <input type="date" name="" id="ToDate" class="form-control"
                                        value="<?= $_GET['tdt'] ? $_GET['tdt'] : date('Y-m-t'); ?>" style="width:48%">
                                </div>
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
                                <input type="button" name="" id="startFilter" onclick="startFilter()"
                                    class="btn btn-outline-secondary" value="Filter">
                                <input value="Clear" type="button"
                                    onclick="window.location.href='toBeSent.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>'"
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

                        var del_date = $("#del_date").is(':checked');
                        
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
                        search += '&del_date=' + del_date;

                        window.location.href = "toBeSent.php" + search;
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

                <!-- Export Datatable start -->
                <div class="card-box mb-30" id="divToPrint">
                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Order In Hand - Summary</h4>
                    </div>
                    <div class="pb-20" style="overflow-y:auto">
                        <table class="table hover table-striped table-bordered" id="example" border="1" style="border-collapse: collapse" style="width:100%">
                            
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>BO No</th>
                                    <th>Brand </th>
                                    <th>Po Qty </th>
                                    <th>Unit</th>
                                    <th>Unit Qty</th>
                                    <th>Delivery Date</th>
                                    <th>Currency</th>
                                    <th>Order Value</th>
                                    <th>Order Value in INR (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                $qry = "SELECT a.*, b.brand_name, c.currency_name, c.currency_value ";
                                $qry .= " FROM sales_order a ";
                                $qry .= " LEFT JOIN brand b ON a.brand = b.id ";
                                $qry .= " LEFT JOIN mas_currency c ON a.currency = c.id ";
    
                                $qry .= " WHERE a.is_dispatch IS NULL ";
    
                                if (isset($_GET['type'])) {
                                    $typ = $_GET['type'];
                                    if ($typ == 'buyer') {
                                        $qry .= " AND a.brand IN (" . $_GET['buyer'] . ") ";
                                    } else if ($typ == 'bonum') {
                                        $qry .= " AND a.id IN (" . $_GET['bonum'] . ") ";
                                    }
                                    
                                }
                                
                                if($_GET['del_date']=='true') {
                                    $qry .= " AND a.delivery_date BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' ";
                                }
    
                                if ($_SESSION['login_role'] != '1') {
                                    // $qry .= " AND a.id= '" . $_SESSION['loginCompany'] . "'";
                                }
    
                                $qry .= " ORDER BY a.id DESC ";
                                // print $qry;
                                $query = mysqli_query($mysqli, $qry);

                                $x = 1;
                                $po = '';
                                $poo = $poo1 = 0;
                                $tott1 = $tott2 = array();
                                if(mysqli_num_rows($query)>0) {
                                    while ($sql = mysqli_fetch_array($query)) {
                                        $mlp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.price, sum(a.total_qty) as qttt, sum(a.total_qty * a.price) as tot, b.part_count FROM sales_order_detalis a 
                                        LEFT JOIN unit b ON a.unit_id = b.id
                                        WHERE a.sales_order_id=" . $sql['id']));
                                        
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $x; ?>
                                            </td>
                                            <td><?= $sql['order_code']; ?></td>
                                            <td><?= $sql['brand_name']; ?></td>
                                            <td><?= number_format($aa1 = $mlp['qttt'], 0, '.', ','); $poo1 += $aa1; ?></td>
                                            <td><?= $mlp['part_count']; ?></td>
                                            <td><?= number_format($aa = ($mlp['qttt']*$mlp['part_count']), 0, '.', ','); $poo += $aa; ?></td>
                                            <td><?= date('d M, Y', strtotime($sql['delivery_date'])); ?></td>
                                            <td><?= $mlp['price'].' - '.$sql['currency_name']; ?></td>
                                            <td><?= number_format($mlp['tot'],2); ?></td>
                                            <td><?= number_format($sql['currency_value'] * $mlp['tot'],2); ?></td>
                                            <!--<td><? //= number_format(($mlp['tot']*$mlp['part_count']),2); ?></td>-->
                                            <!--<td><? //= number_format($sql['currency_value'] * ($mlp['part_count']*$mlp['tot']),2); ?></td>-->
                                        </tr>
                                        <?php $x++;
                                        $tott1[] = $mlp['tot'];
                                        $tott2[] = $sql['currency_value'] * $mlp['tot'];
                                    } 
                                } else {
                                    print '<tr><td class="text-center" colspan="11">No result found!</td></tr>';
                                } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" style="text-align:right">Total</th>
                                    <th><?= number_format($poo1,2); ?></th>
                                    <th></th>
                                    <th><?= number_format($poo,2); ?></th>
                                    <th></th>
                                    <th></th>
                                    <th><?= number_format(array_sum($tott1),2); ?></th>
                                    <th><?= number_format(array_sum($tott2),2); ?></th>
                                </tr>
                            </tfoot>
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