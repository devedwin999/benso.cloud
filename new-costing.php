<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if($_GET['typ']=='view') {
    $type = 'View';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM cost_generation WHERE id =". $_GET['id']));
} else if($_GET['typ']=='edit') {
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM cost_generation WHERE id =". $_GET['id']));
    $type = 'Edit';
} else {
    $type = 'Add';
}
?>
<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO - <?= $type; ?> Cost Generation</title>

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
				    
				    <?php page_spinner();
				    if($_GET['typ']=='view') {
				        if(COST_GENERATION_VIEW!=1) { action_denied(); exit; }
				    } else if($_GET['typ']=='edit') {
				        if(COST_GENERATION_EDIT!=1) { action_denied(); exit; }
				    } else {
				        if(COST_GENERATION_ADD!=1) { action_denied(); exit; }
				    }
				    ?>
				    
					<div class="pd-20">
                        
                        <?php if($type=='View') { ?>
                            <a class="btn btn-light float-right" onclick="divToPrint('printDiv')"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                        <?php } ?>
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-primary" href="sub-contract-bill.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Cost List</a>
                            <a class="btn btn-outline-info" href="mod_production.php"><i class="fa fa-home" aria-hidden="true"></i> Production</a>
						</div>
					</div>
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4"><?= $type; ?> Cost Generation</h4>
							<p class="mb-30 text-danger">(*) Fields are Mandatory</p>
						</div>
					</div>
					<form id="add-form" method="post" autocomplete="off">
					    <?php if($type != 'View') { ?>
    						<div class="row">
    							<?php
    								$qryz = mysqli_query($mysqli, "SELECT * FROM cost_generation WHERE entry_number LIKE '%CG-%' ORDER BY id DESC");
    								$sqql = mysqli_fetch_array($qryz);
    								$numm = mysqli_num_rows($qryz);
    								if ($numm == 0) {
    									$code = 'CG-1';
    								} else {
    									$ex = explode('-', $sqql['entry_number']);
    
    									$value = $ex[1];
    									$intValue = (int) $value;
    									$newValue = $intValue + 1;
    							
    									$code = $ex[0] . '-' . $newValue;
    								}
    							?>
    							
    							<div class="col-md-2">
    								<label class="col-form-label fieldrequired">Entry Number:</label>
    								<div class="form-group">
    								    <input type="hidden" name="cost_generation_id" value="<?= $sql['id']; ?>">
    									<input class="form-control" type="text" name="entry_number" id="entry_number" placeholder="Company Code" value="<?= $code; ?>" readonly>
    								</div>
    							</div>
    							
    							<div class="col-md-2">
    								<label class="col-form-label fieldrequired" for="boNmber">BO Num:</label>
    								<div class="form-group">
    									<select class="form-control custom-select2" name="boNmber[]" id="boNmber" style="width:100%" multiple>
                                            <?= select_dropdown_multiple('sales_order', array('id', 'order_code'), 'id DESC', '', '', '`'); ?>
    								    </select>
    								</div>
    							</div>
    							 
                                <div class="col-md-2" id="styleNumDiv">
                                    <label class="fieldrequired" for="styleNum">Style:</label>
                                    <div class="form-group">
                                        <select name="styleNum[]" id="styleNum" class="form-control custom-select2" style="width:100%" multiple></select>
                                    </div>
                                </div>
                                
                                <div class="col-md-2" id="prtNDiv">
                                    <label for="prtN">Part:</label>
                                    <div class="form-group">
                                        <select name="prtN[]" id="prtN" class="form-control custom-select2" style="width:100%" multiple></select>
                                    </div>
                                </div>
    							
    							<div class="col-md-2">
    								<label class="col-form-label" for="process_name">Process Selection :<span class="text-danger">*</span></label>
    								<div class="form-group">
    									<select class="form-control custom-select2" name="process_name[]" id="process_name" style="width:100%" multiple>
                                            <? //= select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', '', '', '`'); ?>
    								    </select>
    								</div>
    							</div>
    							
    							<div class="col-md-2">
    								<label class="col-form-label" for="employee">Employee :<span class="text-danger">*</span></label>
    								<div class="form-group">
    									<select class="form-control custom-select2" name="employee[]" id="employee" style="width:100%" required multiple>
                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $logUser, ' WHERE is_active="active"', '`'); ?>
    								    </select>
    								</div>
    							</div>
    							
    						</div>
    					
    						<div class=" row">
                                <?php
                                    $pp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT is_cg, cg_name FROM employee_detail WHERE id = '". $logUser ."'"));
                                    if($pp['is_cg'] == 'Yes' && $pp['cg_name'] != '') {
                                ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a class="btn btn-outline-primary button-right" onclick="startCost_Generation()"><i class="fa-plus fa"></i> Add</a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="text-center col-md-12">
                                        <p class="text-danger">Cost Generators only access this page.</p>

                                        <?=  action_denied(); ?>
                                    </div>
                                <?php } ?>
    						</div>
    					<?php } ?>
						
						<div class="row" id="printDiv">
						    <?php
					            if($type=='View') {
					                
					                // $cgr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT cg_name FROM employee_detail WHERE id= ". $sql['employee']));
						    ?>
						        <div class="col-md-12">
						            <table class="table">
                                        <tr>
                                            <td colspan="3" class="text-center"><h5>Cost Generation</h5></td>
                                        </tr>
						                <tr>
						                    <td class="text-center"><h6>Entry Number : <?= $sql['entry_number']; ?></h6></td>
						                    <td class="text-center"><h6>Entry Date : <?= date('d-m-y', strtotime($sql['entry_date'])); ?></h6></td>
						                    <td class="text-center"><h6>Employee : <?= employee_name($sql['created_by']); ?></h6></td>
						                </tr>
						            </table>
						        </div>
						    <?php } ?>
						    
                            <div class="col-md-12" style="overflow-y:auto;">
                                <table class="table contents <?= (isset($_GET['typ'])) ? '' : 'd-none'; ?>">
                                    <thead>
                                        <tr>
                                            <th>BO No</th>
                                            <th>Style</th>
                                            <th>Combo | Part | Color</th>
                                            <th>Process</th>
                                            <th>Output Employee</th>
                                            <th>Billable Qty</th>
                                            <th>Bill Qty</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_">
                                        <?php if(isset($_GET['id'])) {
                                            
                                            $qry = "SELECT a.*,b.order_code, c.style_no, d.part_name, e.color_name, f.process_name FROM cost_generation_det a";
                                            $qry .= " LEFT JOIN sales_order b ON a.order_id = b.id";
                                            $qry .= " LEFT JOIN sales_order_detalis c ON a.style = c.id";
                                            $qry .= " LEFT JOIN part d ON a.part = d.id";
                                            $qry .= " LEFT JOIN color e ON a.color = e.id";
                                            $qry .= " LEFT JOIN process f ON a.process = f.id";
                                            $qry .= " WHERE a.cost_generation_id = '". $sql['id'] ."'";
                                            
                                            $hbv = mysqli_query($mysqli, $qry);
                                            
                                            while($row = mysqli_fetch_array($hbv)) {
                                                
                                            if($_GET['typ']=='view') {
                                        ?>
                                            <tr>
                                                <td><?= $row['order_code']; ?></td>
                                                <td><?= $row['style_no']; ?></td>
                                                <td><?= $row['part_name'].' - '.$row['color_name']; ?></td>
                                                <td><?= $row['process_name']; ?></td>
                                                <td><?= employee_name($row['employee']); ?></td>
                                                <td><?= $bq[] = $row['bill_qty']; ?></td>
                                                <td><?= $row['bill_rate']; ?></td>
                                                <td><?= $ba[] = $row['bill_amount']; ?></td>
                                            </tr>
                                        <?php } else if($_GET['typ']=='edit') { ?>
                                            <tr>
                                                <td><?= $row['order_code']; ?></td>
                                                <td><?= $row['style_no']; ?></td>
                                                <td><?= $row['part_name'].' - '.$row['color_name']; ?></td>
                                                <td><?= $row['process_name']; ?></td>
                                                <td>
                                                    <input type="hidden" name="cg_id[]" value="<?= $row['id']; ?>">
                                                    <input type="hidden" name="max_qty[]" id="max_qty<?= $row['id']; ?>" value="<?= $row['max_qty']; ?>">
                                                    <input type="number" class="form-control zero_valid" onkeyup="validate_billQty(<?= $row['id']; ?>)" name="bill_qty[]" id="bill_qty<?= $row['id']; ?>" value="<?= $bq[] = $row['bill_qty']; ?>">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="max_rate[]" id="max_rate<?= $row['id']; ?>" value="<?= $row['max_rate']; ?>">
                                                    <input type="text" class="form-control zero_valid" onkeyup="validate_billRate(<?= $row['id']; ?>)" name="bill_rate[]" id="bill_rate<?= $row['id']; ?>" value="<?= $row['bill_rate']; ?>">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="order_basic[]" value="<?= $row['sod_part'].'-'.$row['process']; ?>">
                                                    <input type="text" class="form-control zero_valid" name="bill_amount[]" id="bill_amount<?= $row['id']; ?>" readonly value="<?= $ba[] = $row['bill_amount']; ?>">
                                                </td>
                                            </tr>
                                        <?php } } } ?>
                                    </tbody>
                                    <tfoot id="">
                                        
                                        <?php if(isset($_GET['id'])) { ?>
                                            <tr id="tbody_tr">
                                                <td colspan="5" style="text-align: right;font-weight: bold;font-size: 20px;">Total : </td>
                                                <td style="font-weight: bold;font-size: 20px;"><?= array_sum($bq); ?></td>
                                                <td></td>
                                                <td style="font-weight: bold;font-size: 20px;"><?= number_format(array_sum($ba), 2); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tfoot>
                                </table>
                                <hr>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <br>
                            <?php if($type == 'View') { ?>
                                <a class="btn btn-outline-secondary" onclick="history.back()">Close</a>
                            <?php } //else if($_GET['id']) { ?>
                                <a class="btn btn-outline-secondary contents <?= (isset($_GET['typ']) && $_GET['typ']!= 'view') ? '' : 'd-none'; ?>" onclick="history.back()">Go Back</a>
                                <a class="btn btn-outline-primary contents <?= (isset($_GET['typ']) && $_GET['typ']!= 'view') ? '' : 'd-none'; ?>" onclick="Save_CostGeneration('<?= ($_GET['typ']=='edit') ? 'update': 'insert'; ?>')"><?= ($_GET['typ']=='edit') ? 'Update': 'Save'; ?> Cost</a>
                            <?php //} ?>
                        </div>
					</form>
				</div>
			</div>
			<?php 
			    include('includes/footer.php');
			    include('modals.php');
		    ?>
		</div>
	</div>
	<!-- js -->
	<?php include('includes/end_scripts.php'); ?>

	
	<script>
	    function Save_CostGeneration(typ) {
            var err = required_validation('add-form');
            
            if(err == 0) {
                var a = 0;
                $(".zero_valid").each(function() { if(parseFloat($(this).val()) <= 0) { a++; $(this).focus(); message_noload('error', 'All inputs should > 0', 1500); return false; }; });
                
                if(a > 0) {
                    return false;
                }
                
                var form = $("#add-form").serialize();
                $("#overlay").fadeIn(100);
                
                $.ajax({
                    type : 'POST',
                    url : 'ajax_action.php?Save_CostGeneration=1&typ=' + typ,
                    data : form,
                    success : function(msg) {
                        
                        var json = $.parseJSON(msg);
                        $("#overlay").fadeOut(500);
                        
                        if(json.res==0) {
                            message_noload('success', 'Cost '+ typ +'ed', 1500);
                            
                            setTimeout(function() {
                                window.location.href="sub-contract-bill.php";
                            }, 1500)
                        }
                    }
                });
            }
        }
    </script>

    <script>
        function validate_billRate(id) {
            
            var max_rate = $("#max_rate" + id).val();
            var bill_rate = $("#bill_rate" + id).val();
            
            if(parseFloat(max_rate) < parseFloat(bill_rate)) {
                message_noload('error', 'Allowed Budget Rate Exceeded!. Contact Higher Authority', 2000)
                $("#bill_rate" + id).val(max_rate)
                $("#bill_rate" + id).select()
            }
            
            calculate_billAmount(id);
        }
        
    </script>
    
    <script>
        function validate_billQty(id) {
            
            var max_qty = $("#max_qty" + id).val();
            var bill_qty = $("#bill_qty" + id).val();
            
            if(parseInt(max_qty) < parseInt(bill_qty)) {
                message_noload('error', 'Allowed Qty Exceeded!. Contact Higher Authority', 2000)
                $("#bill_qty" + id).val(max_qty)
                $("#bill_qty" + id).select()
            }
            
            calculate_billAmount(id);
        }
        
        
        function calculate_billAmount(id) {
            
            var bill_qty = $("#bill_qty" + id).val();
            var bill_rate = $("#bill_rate" + id).val();
            
            var amt = bill_qty * bill_rate;
            
            $("#bill_amount" + id).val(amt);
        }
    </script>

	<script>
        function startCost_Generation() {
            
            if($("#boNmber").val() == "") {
                message_noload('error', 'BO Num Required!', 1500);
                return false;
            } else if($("#employee").val() == "") {
                message_noload('error', 'Employee Required!', 1500);
                return false;
            } else if($("#process_name").val() == "") {
                message_noload('error', 'Process Required!', 1500);
                return false;
            } else {
                var form = $("#add-form").serialize();
                $("#overlay").fadeIn(100);
                
                $.ajax({
                    type : 'POST',
                    url : 'ajax_search.php?startCost_Generation',
                    data : form,
                    success : function(msg) {
                        
                        $("#overlay").fadeOut(500);
                        var json = $.parseJSON(msg);
                        $("#tbody_").html(json.tbody);
                        $(".contents").removeClass('d-none');
                    }
                });
            }
	    }
	</script>
	
    <script>
        $("#boNmber").change(function() {
            
            var id = $(this).val();
            if(id=="") {
                $("#prtN").html('');
                $("#styleNum").html('');
                $("#process_name").html('');
            }
            var data = 'id=' + id;
            
            $("#overlay").fadeIn(100);
            $.ajax({
                type : 'POST',
                url : 'ajax_search.php?getMultiBoStyle',
                data: data,

                success : function(msg) {
                    
                    var json = $.parseJSON(msg);
                    $("#styleNum").html(json.option);
                    $("#prtN").html('');
                }
            });
            
            $("#overlay").fadeOut(500);
        })
    </script>
    
    <script>
        $("#styleNum").change(function() {
            
            var id = $(this).val();
            if(id=="") {
                $("#prtN").html('');
            }
            var data = 'id=' + id;
            
            $("#overlay").fadeIn(100);
            $.ajax({
                type : 'POST',
                url : 'ajax_search.php?getMultiBoStylePart',
                data: data,
                success : function(msg) {
                    
                    $("#overlay").fadeOut(500);
                    var json = $.parseJSON(msg);
                    $("#prtN").html(json.option);
                }
            });

            $.ajax({
                type : 'POST',
                url : 'ajax_search.php?getApprovedBudProcess',
                data: data,
                success : function(msg) {
                    
                    var json = $.parseJSON(msg);
                    $("#process_name").html(json.option);
                }
            });
        })
    </script>

	<script type="text/javascript">
		$(function () {
			$('#add-company').validate({
				errorClass: "help-block",
				rules: {
					company_name: {
						required: true
					},
					company_code: {
						required: true
					}
				},
				errorPlacement: function (label, element) {
					label.addClass('mt-2 text-danger');
					label.insertAfter(element);
				},
				highlight: function (element, errorClass) {
					$(element).parent().addClass('has-danger')
					$(element).addClass('form-control-danger')
				}
			});
		});
	</script>

</body>

</html>