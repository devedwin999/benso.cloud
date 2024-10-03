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
    <title>BENSO - Order in Hand</title>

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

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="card-box mb-30">
					<div class="pd-20">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-blue">Order In Hand - Detail</h4>
                            </div>

                            <div class="col-md-6">
                                <a class="btn btn-light float-right" onclick="divToPrint('divToPrint')"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                                    <a class="btn btn-outline-info" href="toBeSent_abs.php"><i class="fa fa-file" aria-hidden="true"></i> Buyer Wise</a>
                                    <a class="btn btn-outline-info" href="toBeSent.php" ><i class="fa fa-file" aria-hidden="true"></i> Summary</a>
                                    <a class="btn btn-info" href="order-in-hand.php" ><i class="fa fa-file" aria-hidden="true"></i> Detail</a>
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
                                    onclick="window.location.href='order-in-hand.php'"
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

                        window.location.href = "order-in-hand.php" + search;
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

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30" id="divToPrint">

                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Order In Hand - Detail</h4>
                    </div>

                    <div class="pd-20">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sl.no</th>
                                    <th>Buyer</th>
                                    <th>Merch</th>
                                    <th>Season</th>
                                    <th>Recevied Dt</th>
                                    <th>BO No</th>
                                    <th>Order No</th>
                                    <th>Style No</th>
                                    <th>Delivery Dt</th>
                                    <th>GSM</th>
                                    <th>Order Qty</th>
                                    <th>Price</th>
                                    <th>Value</th>
                                    <th>INR Value</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    $qry = mysqli_query($mysqli, "SELECT 
                                    c.brand_name, e.employee_name,b.season,b.order_date,b.order_code,a.po_num,a.style_no,a.delivery_date,a.gsm,a.total_qty,a.price,f.currency_value
                                    FROM sales_order_detalis a left join sales_order b ON a.sales_order_id=b.id left join brand c ON b.brand=c.id left join merchand_detail d ON b.merchandiser=d.id left join employee_detail e ON d.merchand_name=e.id  left join mas_currency f ON b.currency=f.id
                                    WHERE a.is_dispatch IS null");
                                    
                                    // $qry = "SELECT a.po_num,a.style_no,a.delivery_date,a.gsm,a.total_qty,a.price ";
                                    // $qry .= " ,c.brand_name, e.employee_name,b.season,b.order_date,b.order_code,f.currency_value ";

                                    $query = "SELECT a.style_no, b.order_code, b.order_date, b.brand, b.season, b.currency, c.merchand_name,a.po_num,a.delivery_date,a.gsm,a.total_qty,a.price ";
                                    $query .= " FROM sales_order_detalis a ";
                                    $query .= " LEFT JOIN sales_order b ON a.sales_order_id=b.id ";
                                    $query .= " LEFT JOIN merchand_detail c ON b.merchandiser=c.id ";
                                    $query .= " WHERE b.is_dispatch IS NULL ";

                                    if($_GET['del_date']=='true') {
                                        $query .= " AND a.delivery_date BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' ";
                                    }
                                    
                                    if (isset($_GET['type'])) {
                                        $typ = $_GET['type'];
                                        if ($typ == 'buyer') {
                                            $query .= " AND b.brand IN (" . $_GET['buyer'] . ") ";
                                        } else if ($typ == 'bonum') {
                                            $query .= " AND b.id IN (" . $_GET['bonum'] . ") ";
                                        }
                                        
                                    }

                                    $quyy = mysqli_query($mysqli, $query);
                                    $X = 1;
                                    if(mysqli_num_rows($quyy)>0) {
                                    while ($result = mysqli_fetch_array($quyy)) {
                                ?>
                                    <tr>

                                        <td><?= $X; ?></td>
                                        <td><?= brand_name($result['brand']); ?></td>
                                        <td><?= employee_name($result['merchand_name']); ?></td>
                                        <td><?= $result['season']; ?></td>
                                        <td><?= date('d-m-Y', strtotime($result['order_date'])); ?></td>
                                        <td><?= $result['order_code']; ?></td>
                                        <td><?= $result['po_num']; ?></td>
                                        <td><?= $result['style_no']; ?></td>
                                        <td><?= date('d-m-Y', strtotime($result['delivery_date'])); ?></td>
                                        <td><?= $result['gsm']; ?></td>
                                        <td><?= $result['total_qty']; ?></td>
                                        <td><?= $result['price']; ?></td>
                                        <td><?= $fg = ($result['total_qty'] * $result['price']); ?></td>
                                        <td><?= ($fg * currency_value($result['currency'])); ?></td>
                                    </tr>
                                <?php $X++; } } else { print '<tr><td colspan="14" class="text-center">No result found!</td></tr>';}  ?>
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