<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$data = array();

$ID = $_GET['id'];
$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.* FROM cutting_barcode a WHERE a.id=" . $ID));


if (isset($_POST['editBarcode'])) {
    
    $ok = '';
    
    $data = array(
        'entry_date' => $_REQUEST['date'],
        
        'employee' => $_REQUEST['employee'],
        'fabric' => $_REQUEST['fabric'],
        'fabric_color' => $_REQUEST['fabric_color'],
        'fabric_lot' => $_REQUEST['fabric_lot'],
        'gsm' => $_REQUEST['gsm'],
        'dia' => $_REQUEST['dia'],
        'wt' => $_REQUEST['wt'],
        'lay_length' => $_REQUEST['lay_length'],
        'per_lay_no' => $_REQUEST['per_lay_no'],
        'per_day_wt' => $_REQUEST['per_day_wt'],
        'no_of_lay' => $_REQUEST['no_of_lay'],
        'total_taken_wt' => $_REQUEST['total_taken_wt'],
        'reject_wt' => $_REQUEST['reject_wt'],
    );

    if ($_REQUEST['cutting_barcode_id'] == "") {
        $qry = Insert('cutting_barcode', $data);
        $inid = mysqli_insert_id($mysqli);
    } else {
        $qry = Update('cutting_barcode', $data, " WHERE id = '" . $_REQUEST['cutting_barcode_id'] . "'");
        $inid = $_REQUEST['cutting_barcode_id'];
    }

    $_SESSION['msg'] = "updated";
    header("Location:view-barcode.php");
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>

    <style>
        .table td,
        .table th {
            border-top: 0px solid #dee2e6 !important;
        }

        .col-md-4 {
            padding: 15px !important;
        }

        .addicon {
            font-size: 17px !important;
            color: #5e5e5e;
        }

        input.master:focus {
            border-color: #d7c214 !important;
        }

        .nav-link:hover {
            color: #1a8dc6 !important;
            border-bottom: 2px solid #1a8dc6 !important;
        }

        /* .form-control:disabled,
        .form-control[readonly] {
            background-color: #fbfbfb !important;
            opacity: 1;
        } */
    </style>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Add Budget</title>

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

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">


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

                <div class="pd-20 card-box mb-30">
                    <?php page_spinner(); if(CUTTING_QR_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Cutting Barcode
                            <a class="btn btn-outline-primary" href="view-barcode.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Barcode List</a>
                        </h4>
                        <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                    </div>

                    <form id="saveBarcodeForm" method="POST" enctype="multipart/form-data">

                        <input type="hidden" value="<?= $ID; ?>" name="cutting_barcode_id" id="cutting_barcode_id">

                        <div class="row">
                            <?php if (isset($_GET['id'])) { ?>
                                <div class="col-md-3"><h5>Entry Number : <span style="color: #f22;"><?= $sql['entry_number']; ?></span></h5></div>
                                <div class="col-md-3"><h5>Entry date : <span style="color: #f22;"><?= $sql['entry_date']; ?></span></h5></div>
                            <?php } ?>
                            
                            <div class="col-md-12">
                                <br>
                                <h5 style="text-decoration: underline;">Style Detail</h5>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="date" class="fieldrequired">Date :</label>
                                <input type="date" value="<?= $sql['date'] ? date('Y-m-d', strtotime($sql['date'])) : date('Y-m-d'); ?>" name="date" id="date" class="form-control">
                                <!-- <input type="text" value="<? // = $sql['date'] ? date('d-m-y', strtotime($sql['date'])) : date('d-m-y'); ?>" name="date" id="date" class="form-control date-picker"> -->
                            </div>
                            
                            <div class="col-md-2 pe-none">
                                <label for="so_id" class="fieldrequired">BO No :</label><br>
                                <select name="" class="custom-select2 form-control">
                                    <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', $sql['order_id'], ' WHERE is_approved = "approved" AND is_dispatch IS NULL ', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2 pe-none">
                                <label for="styleList" class="fieldrequired">Style No :</label>
                                <select name="" class="custom-select2 form-control">
                                    <?= select_dropdown('sales_order_detalis', array('id', 'style_no'), 'id DESC', $sql['style'], '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2 pe-none">
                                <label for="part" class="fieldrequired">Combo :</label>
                                <select name="" class="custom-select2 form-control">
                                    <option><?= color_name($sql['combo_id']); ?></option>
                                </select>
                            </div>
                            
                            <div class="col-md-2 pe-none">
                                <label for="part" class="fieldrequired">Part & Color :</label>
                                <select name="" class="custom-select2 form-control">
                                    <option><?= part_name($sql['part_id']) .' || '. color_name($sql['color_id']); ?></option>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="employee">Employee :</label>
                                <i class="icon-copy fa fa-plus-circle addicon d-none" aria-hidden="true"
                                    data-toggle="modal" data-target="#employee-add-modal" title="Add Employee"></i>
                                <select name="employee" id="employee" class="custom-select2 form-control">
                                    <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['employee'], ' WHERE type="employee"', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-12">
                                <br>
                                <h5 style="text-decoration: underline;">Fabric Detail</h5>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="fabric">Fabric :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#fabric-add-modal" title="Add Fabric"></i>
                                <select name="fabric" id="fabric" class="custom-select2 form-control">
                                    <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', $sql['fabric'], '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="fabric_color">Fabric Color :</label>
                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#color-add-modal" title="Add Color"></i>
                                <select name="fabric_color" id="fabric_color" class="custom-select2 form-control">
                                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $sql['fabric_color'], '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="fabric_lot">Fabric Lot :</label>
                                <input type="text" name="fabric_lot" id="fabric_lot" class="form-control" value="<?= $sql['fabric_lot'] ? $sql['fabric_lot'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="gsm">GSM :</label>
                                <input type="text" name="gsm" id="gsm" class="form-control" value="<?= $sql['gsm'] ? $sql['gsm'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="dia">Dia :</label>
                                <input type="text" name="dia" id="dia" class="form-control" value="<?= $sql['dia'] ? $sql['dia'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="wt">Wt :</label>
                                <input type="text" name="wt" id="wt" class="form-control" value="<?= $sql['wt'] ? $sql['wt'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-12">
                                <br>
                                <h5 style="text-decoration: underline;">Lay Detail</h5>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="lay_length">Lay Length :</label>
                                <input type="text" name="lay_length" id="lay_length" class="form-control" value="<?= $sql['lay_length'] ? $sql['lay_length'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="per_lay_no">Per Lay No of Pcs :</label>
                                <input type="text" name="per_lay_no" id="per_lay_no" class="form-control" value="<?= $sql['per_lay_no'] ? $sql['per_lay_no'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="per_day_wt">Per Lay Wt :</label>
                                <input type="text" name="per_day_wt" id="per_day_wt" class="form-control" value="<?= $sql['per_day_wt'] ? $sql['per_day_wt'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="no_of_lay">No of Lay :</label>
                                <input type="text" name="no_of_lay" id="no_of_lay" class="form-control" value="<?= $sql['no_of_lay'] ? $sql['no_of_lay'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="total_taken_wt">Total Taken Wt :</label>
                                <input type="text" name="total_taken_wt" id="total_taken_wt" class="form-control" value="<?= $sql['total_taken_wt'] ? $sql['total_taken_wt'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="reject_wt">Reject Wt :</label>
                                <input type="text" name="reject_wt" id="reject_wt" class="form-control" value="<?= $sql['reject_wt'] ? $sql['reject_wt'] : ''; ?>">
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="tab new_lay">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="laytab_New" role="tabpanel">
                                    <div class="pd-20" style="overflow-y: auto;">
                                        <p style="text-decoration: underline;">Lay- <?= $sql['lay_number']; ?></p><br>
                                        <input type="hidden" name="lay_number" id="lay_number">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr style="background-color: #d7d7d7;">
                                                    <th><label>Size</label></th>
                                                    <th><label>Planned Qty</label></th>
                                                    <th><label>This Lay Completed Qty</label></th>
                                                    <th><label>Pcs Per Bundle</label></th>
                                                    <th><label>No Of Bundle</label></th>
                                                    <th><label>From Bundle No</label></th>
                                                    <th><label>To Bundle No</label><input type="hidden" name="" id="tempClass"></th>
                                                </tr>
                                            </thead>
                                            <tbody style="">
                                                <?php
                                                $toB = $formB = $pcsB = $lay_qty = $completed = $balance = 0;
                                                $sqql = mysqli_query($mysqli, "SELECT a.*, min(a.bundle_number) as bundle_start, max(a.bundle_number) as bundle_end FROM bundle_details a WHERE a.cutting_barcode_id='" . $ID . "' AND a.lay_length = '" . $sql['lay_number'] . "' GROUP BY a.variation_value,a.pcs_per_bundle ");
                                                $m = 1;
                                                while ($result = mysqli_fetch_array($sqql)) {
                                                    ?>
                                                    <tr>
                                                        <td><?= variation_value($result['variation_value']); ?></td>
                                                        <td><?= $result['order_qty']; ?></td>
                                                        <td><?= $result['cutting_qty']; ?></td>
                                                        <td><?= $result['pcs_per_bundle']; ?></td>
                                                        <td><?= $result['total_bundle']; ?></td>
                                                        <td><?= $result['bundle_start']; ?></td>
                                                        <td><?= $result['bundle_end']; ?></td>
                                                    </tr>
                                                    <?php
                                                    $toB += $result['to_bundle'];
                                                    $formB += $result['from_bundle'];
                                                    $pcsB += $result['cutting_qty'];
                                                    $lay_qty += $result['lay_qty'];
                                                    $completed += $result['completed'];
                                                    $balance += $result['balance'];
                                                    $m++;
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="text-align:center;" class="save_div">
                            <a href="view-barcode.php" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</a>
                            <button type="submit" id="editBarcode" name="editBarcode" class="btn btn-outline-primary"><i class="fa fa-save"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="referenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cutting Quantity Reference</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <tr><td>Lay Qty : <span id="cutbltd"></span></td></tr>
                                <tr><td>Stock Qty : <span id="stocktd"></span></td></tr>
                                <tr><td>Balance Qty : <span id="balatd"></span></td></tr>
                                <tr><td>Cuttable Qty : <span id="cutttd"></span></td></tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('modals.php'); include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>


    <script>
        function referenceModal(stock, total, i) {
            var a = $("#cutting_qty" + i).val();

            b = parseInt(total) - (parseInt(a) + parseInt(stock));

            $("#cutbltd").text(a);
            $("#stocktd").text(stock);
            $("#balatd").text(b);
            $("#cutttd").text(total);
            $("#referenceModal").modal('show');
        }
    </script>

</body>

</html>