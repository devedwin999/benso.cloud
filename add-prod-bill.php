<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$data = array(
    
);


?>
<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Add Production Bill Passing</title>

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

	<style>
		.custom-select2 {
			width: 100% !important;
		}
	</style>
</head>

<body>

	<?php include('includes/header.php'); ?>

	<?php include('includes/sidebar.php'); ?>

	<div class="main-container nw-cont">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">

				<!-- Default Basic Forms Start -->
				<div class="pd-20 card-box mb-30">
				    
				    <?php //if(BILL_APPROVAL!=1) { action_denied(); exit; } ?>
				    
					<div class="pd-20">
						<a class="btn btn-outline-primary" href="<?= $_GET['from']; ?>.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Bill Passing List</a>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4"> <?php if(!isset($_GET['typ'])) { ?>New<?php } ?> Production Bill Passing <?= ($_GET['typ'] == 'approve') ? 'Approval' : ''; ?></h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
					<form id="add-form" method="post" autocomplete="off">
					    
					    <input type="hidden" name="bill_receipt_id" value="<?= $_GET['bid']; ?>">
					    
						<div class="row" id="printDiv">
						    <?php if(isset($_GET['id'])) { 
						        $bj = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.id, a.entry_number, b.employee_name, a.entry_date FROM cost_generation a LEFT JOIN employee_detail b ON a.employee = b.id WHERE a.id =". $_GET['id']));
						    ?>
						        <div class="col-md-12">
						            <table class="table">
						                <thead>
    						                <tr>
    						                    <td style="font-weight: bold;">Bill Reference Number : <?= $bj['entry_number']; ?></td>
    						                    <td style="font-weight: bold;">Bill Date : <?= date('d-m-y', strtotime($bj['entry_date'])); ?></td>
    						                    <td style="font-weight: bold;">Employee : <?= $bj['employee_name']; ?></td>
    						                    <?php if($_GET['typ']=='approve') { 
    						                        if(BILL_APPROVAL_APPROVE==1) {
    						                    ?>
        						                    <td class="text-center" style="max-width: 25%;">
        						                        <a class="btn btn-outline-success" onclick="approveBill_passing(<?= $_GET['bid']; ?>, 'approved')">Approve</a>
        						                        <a class="btn btn-outline-danger" onclick="approveBill_passing(<?= $_GET['bid']; ?>, 'rejected')">Reject</a>
        						                        <!--<hr>-->
        						                        <div style="padding: 10px;">
        						                            <textarea class="form-control d-none" id="rejectComment" placeholder="Reason for Rejection" style="width: 100%;height: 50px;"></textarea>
    						                            </div>
    						                        </td>
						                        <?php } } ?>
    						                </tr>
						                </thead>
						            </table>
						        </div>
						    <?php } ?>
						    
                            <div class="col-md-12" style="overflow-y:auto;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>BO No</th>
                                            <th>Style</th>
                                            <th>Part | Color</th>
                                            <th>Process</th>
                                            <th>Production Qty</th>
                                            <th>QC Approved Qty</th>
                                            <th>Order Qty</th>
                                            <th>Already Bill Passed Qty</th>
                                            <th>Unbilled Qty</th>
                                            <th>Budget Rate</th>
                                            <th>Bill Rate</th>
                                            <th>This Bill Qty</th>
                                            <th>This Bill Value</th>
                                            <th>Debit Qty</th>
                                            <th>Debit Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($_GET['id'])) {
                                            
                                            $qry = "SELECT a.*, c.id as cid, c.total_qty, f.department ";
                                            $qry .= " FROM cost_generation_det a";
                                            $qry .= " LEFT JOIN sales_order_detalis c ON a.style = c.id";
                                            $qry .= " LEFT JOIN process f ON a.process = f.id";
                                            $qry .= " WHERE a.cost_generation_id = '". $bj['id'] ."'";
                                            
                                            $hbv = mysqli_query($mysqli, $qry);
                                            
                                            while($row = mysqli_fetch_array($hbv)) {
                                                
                                                // if($row['process_id'] == 1 && $row['department'] == 1) {
                                                    
                                                //     $hk = mysqli_query($mysqli, "SELECT sum(b.pcs_per_bundle) as pcs_per_bundle FROM bundle_details b LEFT JOIN cutting_barcode a ON b.cutting_barcode_id = a.id 
                                                //             WHERE  a.order_id = '". $row['order_id'] ."' AND a.style = '". $row['style'] ."' AND a.part = '". $row['part'] ."' AND a.color = '". $row['color'] ."'");
                                                //     $nqy = mysqli_fetch_array($hk);
                                                    
                                                //     $prod_qty = $nqy['pcs_per_bundle'] ? $nqy['pcs_per_bundle'] : 0;
                                                    
                                                // } else if($row['process_id'] != 1 && $row['department'] == 1) {
                                                    
                                                //     $prod_qty = 0;
                                                    
                                                // } else if($row['department'] == 3) {
                                                //     $hk = mysqli_query($mysqli, "SELECT sum(b.pcs_per_bundle) as pcs_per_bundle FROM bundle_details b LEFT JOIN cutting_barcode a ON b.cutting_barcode_id = a.id 
                                                //             WHERE b.s_out_complete='yes' AND a.order_id = '". $row['order_id'] ."' AND a.style = '". $row['style'] ."' AND a.part = '". $row['part'] ."' AND a.color = '". $row['color'] ."'");
                                                //     $nqy = mysqli_fetch_array($hk);
                                                    
                                                //     $prod_qty = $nqy['pcs_per_bundle'] ? $nqy['pcs_per_bundle'] : 0;
                                                    
                                                // } else if($row['department'] == 7) {
                                                //     $hk = mysqli_query($mysqli, "SELECT sum(b.tot_good_pcs) as pcs_per_bundle FROM bundle_details b LEFT JOIN cutting_barcode a ON b.cutting_barcode_id = a.id 
                                                //             WHERE a.order_id = '". $row['order_id'] ."' AND a.style = '". $row['style'] ."' AND a.part = '". $row['part'] ."' AND a.color = '". $row['color'] ."'");
                                                //     $nqy = mysqli_fetch_array($hk);
                                                    
                                                //     $prod_qty = $nqy['pcs_per_bundle'] ? $nqy['pcs_per_bundle'] : 0;
                                                    
                                                // } else if($row['department'] == 8) {
                                                //     $hk = mysqli_query($mysqli, "SELECT sum(b.ironing_qty) as ironing_qty FROM ironing_detail a 
                                                //             WHERE a.order_id = '". $row['order_id'] ."' AND a.style = '". $row['style'] ."' AND a.part = '". $row['part'] ."' AND a.color = '". $row['color'] ."'");
                                                //     $nqy = mysqli_fetch_array($hk);
                                                    
                                                //     $prod_qty = $nqy['ironing_qty'] ? $nqy['ironing_qty'] : 0;
                                                    
                                                // } else if($row['department'] == 8) {
                                                //     $hk = mysqli_query($mysqli, "SELECT sum(b.packing_qty) as packing_qty FROM packing_detail a 
                                                //             WHERE a.order_id = '". $row['order_id'] ."' AND a.style = '". $row['style'] ."' AND a.part = '". $row['part'] ."' AND a.color = '". $row['color'] ."'");
                                                //     $nqy = mysqli_fetch_array($hk);
                                                    
                                                //     $prod_qty = $nqy['packing_qty'] ? $nqy['packing_qty'] : 0;
                                                    
                                                // } else {
                                                    
                                                    $prod_qty = 0;
                                                // }
                                                
                                                $already = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.bill_qty) as bill_qty FROM cost_generation_det a 
                                                            WHERE a.sod_part = '". $row['sod_part'] ."' AND a.process = '". $row['process'] ."' AND a.id != ". $row['id']));
                                        ?>
                                            <tr>
                                                <td><input type="hidden" name="id[]" value="<?= $row['id']; ?>"> <?= sales_order_code($row['order_id']); ?></td>
                                                <td><?= sales_order_style($row['style']); ?></td>
                                                <td><?= part_name($row['part']); ?> | <?= color_name($row['color']); ?></td>
                                                <td><?= process_name($row['process']); ?></td>
                                                <td><?= $pqq1[] = $prod_qty; ?></td>
                                                <td>-</td>
                                                <td><?= $pqq3[] = $row['total_qty'] ?></td>
                                                <td><?= $pqq4[] = $already['bill_qty'] ? $already['bill_qty'] : 0; ?></td>
                                                <td><?= $pqq5[] = ($row['total_qty'] - (($already['bill_qty']-$row['bill_qty']) + $row['bill_qty'] )) ?></td>
                                                <td><?= $pqq6[] = $row['max_rate'] ?></td>
                                                
                                                <?php if(!isset($_GET['typ'])) { ?>
                                                    <td><input class="form-control" id="b_rate<?= $row['id']; ?>" name="n_bill_rate[]" type="text" placeholder="Bill Rate" value="<?= $pqq7[] = $row['n_bill_rate'] ? $row['n_bill_rate'] : $row['bill_rate']; ?>"></td>
                                                    <td><input class="form-control" id="b_qtty<?= $row['id']; ?>" name="n_bill_qty[]" type="number" onkeyup="checkDebit(<?= $row['id']; ?>)" placeholder="This Bill Qty" data-val="<?= $row['bill_qty']; ?>" value="<?= $bq[] = $row['n_bill_qty'] ? $row['n_bill_qty'] : $row['bill_qty']; ?>"></td>
                                                    <td><input class="form-control" id="b_amtt<?= $row['id']; ?>" name="n_bill_amount[]" type="text" placeholder="This Bill Value" value="<?= $ba[] = $row['n_bill_amount'] ? $row['n_bill_amount'] : $row['bill_amount']; ?>"></td>
                                                    <td><input class="form-control" id="d_qtty<?= $row['id']; ?>" name="debit_qty[]" type="number" placeholder="Debit Qty" value="<?= $row['debit_qty']; ?>"></td>
                                                    <td><input class="form-control" id="d_valu<?= $row['id']; ?>" name="debit_amount[]" type="text" placeholder="Debit Value"  value="<?= $row['debit_amount']; ?>"></td>
                                                <?php } else { ?>
                                                    <td><?= $pqq7[] = $row['n_bill_rate'] ? $row['n_bill_rate'] : $row['bill_rate']; ?></td>
                                                    <td><?= $bq[] = $row['n_bill_qty'] ? $row['n_bill_qty'] : $row['bill_qty']; ?></td>
                                                    <td><?= $ba[] = $row['n_bill_amount'] ? $row['n_bill_amount'] : $row['bill_amount']; ?></td>
                                                    <td><?= $row['debit_qty']; ?></td>
                                                    <td><?= $row['debit_amount']; ?></td>
                                                <?php } ?>
                                            </tr>
                                        <?php } } ?>
                                    </tbody>
                                
                                    <tfoot style="color: black;">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total : </td>
                                            <td><?= array_sum($pqq1); ?></td>
                                            <td>-</td>
                                            <td><?= array_sum($pqq3); ?></td>
                                            <td><?= array_sum($pqq4); ?></td>
                                            <td><?= array_sum($pqq5); ?></td>
                                            <td><?= array_sum($pqq6); ?></td>
                                            <td><?= array_sum($pqq7); ?></td>
                                            <td><?= array_sum($bq); ?></td>
                                            <td><?= array_sum($ba); ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php if(!isset($_GET['typ'])) { ?>
                                            <tr>
                                                <th colspan="15" class="text-center">
                                                    <a class="btn btn-outline-primary" onclick="saveProd_Passing()">Save Bill</a>
                                                </th>
                                            </tr>
                                        <?php } ?>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
					</form>
				</div>
			</div>
			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->
	<?php include('includes/end_scripts.php'); ?>
	
	<script>
	    function approveBill_passing(id, typ) {
	        
	        var msg = $("#rejectComment").val();
	        
	        if(typ=='rejected' && msg=="") {
	            $("#rejectComment").removeClass('d-none');
	            $("#rejectComment").focus();
	        } else {
	           // $("#rejectComment").addClass('d-none');
	           // $("#rejectComment").val('');
	            
	            swal({
                title: 'Are you sure?',
                text: "Comform " + typ + " the bill passing?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
                }).then(function (dd) {
                    if (dd['value'] == true) {
                    
                        
                        $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?Approve_BillPassing_status=1&msg=' + msg + '&typ=' + typ + '&id=' + id,
                        
                        success: function (msg) {
                            var json = $.parseJSON(msg);
                            if(json.res == 0 ) {
                                message_noload('success', 'Bill Passing Status ' + typ, 1500);
                                
                                setTimeout(function() {
                                    window.location.href="<?= $_GET['from']; ?>.php";
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
	        
	        
	    }
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
                    url: 'ajax_action.php?saveProd_Passing=1',
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
	
	<script>
	    function checkDebit(id) {
	        var a = $("#b_qtty" + id).attr('data-val');
	        
	        var b = $("#b_qtty" + id).val();
	        
	        if(parseInt(b)>parseInt(a)) {
	            $("#b_qtty" + id).val(a);
	        }
	        
	    }
	</script>
</body>

</html>





























