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
    <title>BENSO - Group Sheet</title>

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
                    <?php if(GROUP_SHEET!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Filter Type</label>
                                <select name="" id="FilterOpt" class="custom-select2 multiple-select"
                                    style="width: 100%" onchange="FilterOpt()">
                                    <option value="">Select</option>
                                    <?php
                                    $var = array(
                                        'buyer' => 'Buyer',
                                        'bonum' => 'BO',
                                        'styleNum' => 'Style',
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
                                <label for="">Delivery Date From & To date</label>
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
                                    onclick="window.location.href='group_sheet.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>'"
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

                        window.location.href = "group_sheet.php" + search;
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

                <div class="card-box mb-5" style="float:right">
                    <i class="fa fa-print" style="padding: 15px;" onclick="divToPrint()"></i>
                </div>

                <!-- Export Datatable start -->
                <div class="card-box mb-30" id="divToPrint">
                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Group Sheet</h4>
                    </div>
                    <div class="pb-20" style="overflow-y:auto">
                            <!--class="table hover multiple-select-row data-table-export nowrap dataTable no-footer dtr-inline"-->
                        <table id="example" class="table table-striped table-bordered" cellspacing="0" border="1" style="border-collapse: collapse">
                            <?php
                            $qry = "SELECT a.*, b.order_code, b.order_qty, b.delivery_date ";
                            $qry .= " FROM sales_order_detalis a ";
                            $qry .= " LEFT JOIN sales_order b ON a.sales_order_id=b.id ";
                            // $qry .= " LEFT JOIN cutting_barcode c ON a.id=c.style ";
                            // $qry .= " LEFT JOIN bundle_details d ON c.id=d.cutting_barcode_id ";
                            // $qry .= " LEFT JOIN color e ON c.color=e.id ";
                            // $qry .= " LEFT JOIN part f ON c.part=f.id ";
                            // $qry .= " LEFT JOIN company g ON d.cuttiing_unit=g.id ";
                            $qry .= " LEFT JOIN brand h ON b.brand=h.id ";

                            $qry .= " WHERE ";

                            if (isset($_GET['type'])) {
                                $typ = $_GET['type'];
                                if ($typ == 'buyer') {
                                    $qry .= " b.brand IN (" . $_GET['buyer'] . ") ";
                                } else if ($typ == 'bonum') {
                                    $qry .= " b.id IN (" . $_GET['bonum'] . ") ";
                                } else if ($typ == 'styleNum') {
                                    $qry .= " a.id IN (" . $_GET['styleNum'] . ") ";
                                // }else if ($typ == 'prtN') {
                                //     $qry .= " c.part IN (" . $_GET['prtN'] . ") ";
                                // } else if ($typ == 'colrr') {
                                //     $qry .= " c.color IN (" . $_GET['colrr'] . ") ";
                                }
                                
                                $qry .= " AND b.delivery_date BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' ";
                            } else {
                                $onDt = date('Y-m-d');
                                $qry .= " b.delivery_date BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' ";
                            }

                            if ($_SESSION['login_role'] != '1') {
                                // $qry .= " AND g.id= '" . $_SESSION['loginCompany'] . "'";
                            }

                            // $qry .= " GROUP BY c.style,c.part ";
                            $qry .= " ORDER BY b.delivery_date ASC ";
                            // print $qry;
                            $query = mysqli_query($mysqli, $qry);

                            ?>
                            <thead>
                                <tr>
                                    <th colspan="17"
                                        style="text-align:center">Delivery Date Between <?= date('d-m-Y', strtotime($_GET['fdt'])); ?> AND <?= date('d-m-Y', strtotime($_GET['tdt'])); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>BO</th>
                                    <th>STYLE NO</th>
                                    <th>IMAGE</th>
                                    <th>FABRIC DETAILS</th>
                                    <th>Part</th>
                                    <th>Color</th>
                                    <th>Order Qty</th>
                                    <th>Cut PlanQty</th>
                                    <th>Delivery Date</th>
                                    <th>YARN INHOUSE DATE</th>
                                    <th>FABRIC INHOUSE DATE</th>
                                    <th>FIT APPROVALS</th>
                                    <th>LAB DIP APPROVALS</th>
                                    <th>PP SAMPLE</th>
                                    <th>FPT</th>
                                    <th>GPT</th>
                                    <th>REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $x = 1;
                                $num_ = mysqli_num_rows($query);
                                
                                while ($sql = mysqli_fetch_array($query)) {
                                    $y=1;
                                    // $fab = mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_program a LEFT JOIN fabric b ON a.fabric=b.id WHERE a.sales_order_detalis_id='" . $sql['id'] . "' ");
                                    // while($fab_wh = mysqli_fetch_array($fab)) {
                                    // $fabname[$x][] = $y.'. '.$fab_wh['fabric_name'];
                                    // $y++; }
                                        
                                        $prt = json_decode($sql['part_detail']);

                                        foreach($prt as $part_) {
                                            
                                            $part_ = explode(',,', $part_);
                                            $part_NM = explode('=', $part_[0]);
                                            $color_NM = explode('=', $part_[1]);
                                            
                                            $part_name = mysqli_fetch_array(mysqli_query($mysqli, "SELECT part_name FROM part WHERE id = '". $part_NM[1] ."'"));
                                            $color_name = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id = '". $color_NM[1] ."'"));
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['order_code']; ?></td>
                                        <td><?= $sql['style_no']; ?></td>
                                        <td><img src="uploads/so_img/<?= $sql['order_code'] . '/' . $sql['item_image']; ?>" alt="-" width="50"></td>
                                        <td><? //= ($fabname[$x]!="") ? implode('<br>', $fabname[$x]) : ''; ?></td>
                                        <td><?= $part_name['part_name']; ?></td>
                                        <td><?= $color_name['color_name']; ?></td>
                                        <td><?= $sql['total_qty']; ?></td>
                                        <td><?= ($sql['total_qty'] + (($sql['total_qty']/100) * $sql['excess'])); ?></td>
                                        <td><?= date('d-m-y', strtotime($sql['delivery_date'])); ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> 
                                    <?php
                                        }
                                    
                                    $x++; } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>


<script>
    // $(document).ready(function() {
    //     $('#example').DataTable( {
    //         paging: false,
    //         searching: false,
    //         extend: 'pdfHtml5',
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'csv'
    //         ]
    //     } );
    // } );
</script>

<script type="text/javascript">
    function divToPrint() {
        var divToPrint = document.getElementById('divToPrint');
        var popupWin = window.open();
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
    }
</script>
</body>

</html>