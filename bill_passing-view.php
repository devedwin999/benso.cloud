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
    <title>BENSO - Bill Passing</title>

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

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="pd-20 card-box mb-30">
                    <?php page_spinner(); //if(BILL_APPROVAL!=1) { action_denied(); exit; } ?>
                    
					<div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-primary" href="<?= $_GET['from']; ?>.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Bill Passing List</a>
                            <a class="btn btn-outline-info" href="mod_accounts.php"><i class="fa fa-home" aria-hidden="true"></i> Accounts</a>
						</div>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4"> View Production Bill Passing</h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
					<form id="add-form" method="post" autocomplete="off">

                        <?php
                            $res = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bill_passing WHERE id = ". $_GET['id']));

                            if($res['bill_from']=='cost_generation_det') {
                                $qry = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM cost_generation WHERE id = ". $res['bill_id']));
                                $bill_noo = $qry['receipt_ref'];
                            }
                        ?>
                        
						<div class="row">                            
                            <div class="col-md-2">
                                <label class="col-form-label fieldrequired">Entry Number :</label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="entry_number" id="entry_number" placeholder="Entry Number" value="<?= $res['entry_number']; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2 pe-none">
                                <label class="col-form-label fieldrequired">Entry Date :</label>
                                <div class="form-group">
                                    <input class="form-control" type="date" name="entry_date" id="entry_date" placeholder="Entry Date" value="<?= $res['entry_date']; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2 pe-none">
                                <label class="col-form-label fieldrequired">Bill Number :</label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="bill_number" id="bill_number">
                                        <?= select_dropdown('bill_receipt', array('id', 'bill_number'), 'bill_number DESC', $bill_noo, '', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-12 tableDiv" style="overflow-y: auto;">
                                <table class="table">
                                    <thead>
                                        <tr class="">
                                            <th>BO No</th>
                                            <th>Style</th>
                                            <th>Combo | Part | Color</th>
                                            <th>Process</th>
                                            <th>Order Qty</th>
                                            <!-- <th>Already Bill Passed Qty</th>
                                            <th>Unbilled Qty</th> -->
                                            <th>Budget Rate</th>
                                            <th>Bill Rate</th>
                                            <th>This Bill Qty</th>
                                            <th>This Bill Value</th>
                                            <th>Debit Qty</th>
                                            <th>Debit Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <?php
                                            $qu = "SELECT a.bill_rate, a.debit_amount, a.debit_qty, a.bill_amount, a.bill_qty ";
                                            $qu .= " ,b.order_id, b.color, b.part, b.combo, b.style, b.process, b.max_rate ";
                                            $qu .= " ,c.total_qty ";
                                            $qu .= " FROM bill_passing_det a";
                                            $qu .= " LEFT JOIN cost_generation_det b ON a.cost_generation_det = b.id ";
                                            $qu .= " LEFT JOIN sales_order_detalis c ON b.style = c.id ";
                                            $qu .= " WHERE a.bill_passing_id = '". $_GET['id'] ."'";

                                            $query = mysqli_query($mysqli, $qu);
                                            
                                            while($fetch = mysqli_fetch_array($query)) {
                                        ?>
                                            <tr>
                                                <td><?= sales_order_code($fetch['order_id']); ?></td>
                                                <td><?= sales_order_style($fetch['style']); ?></td>
                                                <td><?= color_name($fetch['combo']).' | '.part_name($fetch['part']).' | '.color_name($fetch['color']); ?></td>
                                                <td><?= process_name($fetch['process']); ?></td>
                                                <td><?= $fetch['total_qty']; ?></td>
                                                <td><?= $fetch['max_rate']; ?></td>
                                                <td><?= $fetch['bill_rate']; ?></td>
                                                <td><?= $fetch['bill_qty']; ?></td>
                                                <td><?= $fetch['bill_amount']; ?></td>
                                                <td><?= $fetch['debit_qty']; ?></td>
                                                <td><?= $fetch['debit_amount']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <hr>
                            </div>
                            
                            <div class="col-md-12 text-center tableDiv d-none">
                                <hr>
                                <a class="btn btn-outline-secondary" onclick="history.back()">Close</a>
                                <a class="btn btn-outline-primary" onclick="saveProd_Passing()">Save Bill</a>
                            </div>
                        </div>
					</form>
				</div>
            </div>
            <?php include('includes/footer.php'); include('modals.php'); ?>
        </div>
    </div>

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
</body>
</html>