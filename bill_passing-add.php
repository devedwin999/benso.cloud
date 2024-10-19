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
                            <a class="btn btn-outline-primary" href="<?= $_GET['from']; ?>bill_passing.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Bill Passing List</a>
                            <a class="btn btn-outline-info" href="mod_accounts.php"><i class="fa fa-home" aria-hidden="true"></i> Accounts</a>
						</div>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4"> <?php if(!isset($_GET['typ'])) { ?>New<?php } ?> Production Bill Passing <?= ($_GET['typ'] == 'approve') ? 'Approval' : ''; ?></h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
					<form id="add-form" method="post" autocomplete="off">
                        
						<div class="row">
                            <?php
                                if (isset($_GET['id'])) {
                                    $code = $sql['supplier_code'];
                                } else {
                                    $qryz = mysqli_query($mysqli, "SELECT * FROM supplier WHERE supplier_code LIKE '%PBP-%' ORDER BY id DESC");
                                    $sqql = mysqli_fetch_array($qryz);
                                    $numm = mysqli_num_rows($qryz);
                                    if ($numm == 0) {
                                        $code = 'PBP-1';
                                    } else {
                                        $ex = explode('-', $sqql['supplier_code']);
                                            
                                        $value = $ex[1];
                                        $intValue = (int) $value;
                                        $newValue = $intValue + 1;
                                        
                                        $code = $ex[0] . '-' . $newValue;
                                    }
                                }
                            ?>
                            
                            <div class="col-md-2">
                                <label class="col-form-label fieldrequired">Entry Number :</label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="entry_number" id="entry_number" placeholder="Entry Number" value="<?= $code; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label fieldrequired">Entry Date :</label>
                                <div class="form-group">
                                    <input class="form-control" type="date" name="entry_date" id="entry_date" placeholder="Entry Date" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label fieldrequired">Bill Number :</label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="bill_number" id="bill_number">
                                        <?= select_dropdown('bill_receipt', array('id', 'bill_number'), 'bill_number DESC', '', '', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-md-12 tableDiv d-none" style="overflow-y: auto;">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>BO No</th>
                                            <th>Style</th>
                                            <th>Part | Color</th>
                                            <th>Process</th>
                                            <!-- <th>Production Qty</th> -->
                                            <!-- <th>QC Approved Qty</th> -->
                                            <th>Order Qty</th>
                                            <th class="d-none">Already Bill Passed Qty</th>
                                            <th class="d-none">Unbilled Qty</th>
                                            <th>Budget Rate</th>
                                            <th>Bill Rate</th>
                                            <th>This Bill Qty</th>
                                            <th>This Bill Value</th>
                                            <th>Debit Qty</th>
                                            <th>Debit Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <tr><td colspan="15" class="text-center">No Data Found</td></tr>
                                    </tbody>
                                </table>
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
    
    <script>
        $("#bill_number").change(function() {
            
            var bill_number = $("#bill_number").val();
            if(bill_number=="") { $(".tableDiv").addClass('d-none'); $("#tbody").html(''); message_noload('error', 'Bill Number Required!', 1000); return false; }
            
            $("#overlay").fadeIn(100);
            var data = { bill_number : bill_number }
            
            $.post('ajax_search2.php?fetch_prod_bill_passing', data, function(resp) {
                
                $("#tbody").html(resp);
                $(".tableDiv").removeClass('d-none');
                $("#overlay").fadeOut(500);
            });
            
        });
    </script>
    
    
    <script>
        function saveProd_Passing() {
            swal({
                title: 'Are you sure?',
                text: "Will Apply the Changes!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    
                    var form = $("#add-form").serialize();
                    
                    $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?saveProd_Passing',
                    data : form,
                    
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        if(json.res == 0 ) {
                            message_noload('success', 'Bill Passing Saved!', 1500);
                            
                            setTimeout(function() {
                                window.location.href="bill_passing.php";
                            },1500);
                        } else {
                            message_noload('error', 'Something Went Wrong!', 1500);
                        }
                    }
                    });
                } else {
                    swal(
                        'Cancelled',
                        '',
                        'error'
                    )
                }
            })
        }
    </script>

</body>

</html>


























