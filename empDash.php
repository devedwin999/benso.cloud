<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (!isset($_SESSION['login_id'])) {
	header('Location:index.php');
}

if(ADMIN_DASH == 1) { ?>
<script>
    window.location.href='dashboard.php';
</script>
<?php exit; } ?>

<!DOCTYPE html>
<html> 

<head>
	<!-- Basic Page Info --> 
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Employee Dashboard</title>

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
	<link rel="stylesheet" type="text/css" href="src/plugins/jvectormap/jquery-jvectormap-2.0.3.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() { dataLayer.push(arguments); }
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>

<style>
	.nav-tabs {
		border-bottom: none !important;
	}
	
    .dhide {
        display:none;
    }
    
    .height-100-p {
        height: 100% !important;
    }
    
    .nw-cont {
        padding: 40px 20px 0 90px !important;
    }
</style>

<body>
    <!-- <div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo text-center"><img src="<?= get_setting_val('APPLICATION_LOGO'); ?>" alt="" width="200"></div>
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Dashboard Loading...
			</div>
		</div>
	</div> -->

	<?php
    	include('includes/header.php');
    	include('includes/sidebar.php');
    	
    	$emp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT department, employee_code, employee_photo, designation FROM employee_detail WHERE id = '". $logUser ."'"));
	?>

	<div class="main-container nw-cont"> 
	<!--<div class="main-container nw-cont" style="padding: 40px 20px 0 90px !important;">-->
		<div class="pd-ltr-20">
        <?php if(EMPLOYEE_DASH!=1) { action_denied(); exit; } ?>

            <div class="card-box pd-20 height-100-p mb-30">
				<div class="row align-items-center">
					<div class="col-md-4" style="text-align:right;">
						<?= viewImage($base_url.$emp['employee_photo'], 250); ?>
					</div>
					<div class="col-md-8">
						<h4 class="font-20 weight-500 mb-10 text-capitalize">
							Welcome back <div class="weight-600 font-30 text-blue"><?= employee_name($logUser); ?>!</div>
						</h4>
						<p class="font-18 max-width-600">At BENSO GARMENTING, our passion for quality and innovation drives us to craft exceptional garments. Together, we empower one another to reach new heights and shape the future of fashion.</p>
					</div>
				</div>
			</div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="page-header" style="height: 52px;">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title">
                                    <h4 class="text-monospace" style="margin-top: -7px;">Department : <?= department_name($emp['department']); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/plan-q.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info plan_qty">0</div>
                                        <div class="weight-600 font-14 text-info">Plan Qty<br><?= date('d-m-y'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/sw-out-q.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info output_today">0</div>
                                        <div class="weight-600 font-14 text-info">Output<br><?= date('d-m-y'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="page-header" style="height: 52px;">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title">
                                    <h4 class="text-monospace" style="margin-top: -7px;">Total</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/tot-plan-q.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info tot_plan_qty">0</div>
                                        <div class="weight-600 font-14 text-info">Plan Qty<br> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/tot-inp-q.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info sw_inp">0</div>
                                        <div class="weight-600 font-14 text-info">Input Qty<br></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/tot-out-q.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info sw_out_tot">0</div>
                                        <div class="weight-600 font-14 text-info">Output Qty<br></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/tot-bal-q.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info out_balance">0</div>
                                        <div class="weight-600 font-14 text-info">Output Balance<br></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="page-header" style="height: 52px;">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title">
                                    <h4 class="text-monospace" style="margin-top: -7px;">Today Working Cost (₹)</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/plan-cost.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info">0</div>
                                        <div class="weight-600 font-14 text-info">Plan Cost<br><?= date('d-m-y'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/act-cost.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info">0</div>
                                        <div class="weight-600 font-14 text-info">Actual Cost<br><?= date('d-m-y'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="col-md-8">
                    <div class="page-header" style="height: 52px;">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title">
                                    <h4 class="text-monospace" style="margin-top: -7px;">Payment Status</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/unbill-qty.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info">0</div>
                                        <div class="weight-600 font-14 text-info">Unbilled Qty<br> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/unbill-val.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info">0</div>
                                        <div class="weight-600 font-14 text-info">Unbilled Value<br></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/paid-amt.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info">0</div>
                                        <div class="weight-600 font-14 text-info">Paid Amount<br></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 mb-30">
                            <div class="card-box height-100-p widget-style1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="progress-data text-center">
                                        <img src="src/icons/unpaid.png" width="45">
                                    </div>
                                    <div class="widget-data">
                                        <div class="h4 mb-0 text-info">0</div>
                                        <div class="weight-600 font-14 text-info">Unpaid Amount<br></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
			<div class="card-box mb-30">
				<h2 class="h4 pd-20">Order Detail</h2>
				<div class="tab">
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#order_det" role="tab" aria-selected="true">Order Details</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#payment_det" role="tab" aria-selected="false">Payment Details</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="order_det" role="tabpanel">
                            <div class="pd-20">
                                <div class="tab">
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#od_today" role="tab" aria-selected="true">Today</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#od_total" role="tab" aria-selected="false">Total</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="od_today" role="tabpanel">
                                            <div class="pd-20" style="overflow-y:auto;">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.No</th>
                                                            <th>Style Image</th>
                                                            <th>BO</th>
                                                            <th>Style</th>
                                                            <th>Combo</th>
                                                            <th>Part</th>
                                                            <th>Color</th>
                                                            <th>Output Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i=1;
                                                        $line_ids = array();
                                                        
                                                        $lines = mysqli_query($mysqli, "SELECT id FROM mas_line WHERE FIND_IN_SET(". $logUser .", cost_generator)");
                                                        if(mysqli_num_rows($lines) > 0) {
                                                            while($res = mysqli_fetch_array($lines)) {
                                                                $line_ids[] = $res['id'];
                                                            }
                                                        } else {
                                                            $line_ids[] = 0;
                                                        }
                                                        $line_ids = $line_ids ? implode(',', $line_ids) : '';
                                                        
                                                        if($emp['department']==5) {
                                                            $fk = mysqli_query($mysqli, "SELECT a.*, sum(a.scanned_count) as scanned_count, b.item_image FROM orbidx_sewingout a LEFT JOIN sales_order_detalis b ON a.style_id = b.id
                                                                                        WHERE a.line IN (". $line_ids .") AND a.date ='". date('Y-m-d') ."' GROUP BY a.sod_part");
                                                            if(mysqli_num_rows($fk) > 0) {
                                                            while($tdy_output = mysqli_fetch_array($fk)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $i; ?></td>
                                                            <td><?= viewImage($tdy_output['item_image'], 30); ?></td>
                                                            <td><?= sales_order_code($tdy_output['order_id']); ?></td>
                                                            <td><?= sales_order_style($tdy_output['style_id']); ?></td>
                                                            <td><?= color_name($tdy_output['combo']); ?></td>
                                                            <td><?= part_name($tdy_output['part']); ?></td>
                                                            <td><?= color_name($tdy_output['color']); ?></td>
                                                            <td><?= $tdy_output['scanned_count']; ?></td>
                                                        </tr>
                                                        <?php
                                                            $i++; } } else { print '<tr><td colspan="8">No Data Found</td></tr>'; } 
                                                        } else if($emp['department']==1) {
                                                            
                                                            $pp  = "SELECT a.*, b.item_image, COALESCE(d.pcs_per_bundle, 0) AS pcs_per_bundle FROM cutting_barcode a 
                                                                    LEFT JOIN sales_order_detalis b ON a.style = b.id LEFT JOIN 
                                                                    (SELECT cutting_barcode_id, SUM(pcs_per_bundle) AS pcs_per_bundle FROM bundle_details GROUP BY cutting_barcode_id) d ON a.id = d.cutting_barcode_id 
                                                                    WHERE b.is_dispatch IS NULL AND a.created_unit = '2' AND a.created_date LIKE '%2024-06-12%';";
                                                            
                                                            $fk = mysqli_query($mysqli, $pp);
                                                            if(mysqli_num_rows($fk) > 0) {
                                                            while($tdy_output = mysqli_fetch_array($fk)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $i; ?></td>
                                                            <td><?= viewImage($tdy_output['item_image'], 30); ?></td>
                                                            <td><?= sales_order_code($tdy_output['order_id']); ?></td>
                                                            <td><?= sales_order_style($tdy_output['style']); ?></td>
                                                            <td><?= color_name($tdy_output['combo_id']); ?></td>
                                                            <td><?= part_name($tdy_output['part_id']); ?></td>
                                                            <td><?= color_name($tdy_output['color_id']); ?></td>
                                                            <td><?= $tdy_output['pcs_per_bundle']; ?></td>
                                                        </tr>
                                                        <?php
                                                            $i++; } } else { print '<tr><td colspan="8">No Data Found</td></tr>'; } 
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="od_total" role="tabpanel">
                                            <div class="pd-20" style="overflow-y:auto;">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.No</th>
                                                            <th>Style Image</th>
                                                            <th>BO</th>
                                                            <th>Style</th>
                                                            <th>Combo</th>
                                                            <th>Part</th>
                                                            <th>Color</th>
                                                            <th>Output Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i=1;
                                                        if($emp['department']==5) {
                                                            $fk = mysqli_query($mysqli, "SELECT a.*, sum(a.scanned_count) as scanned_count, b.item_image FROM orbidx_sewingout a LEFT JOIN sales_order_detalis b ON a.style_id = b.id WHERE a.line IN (". $line_ids .") GROUP BY a.sod_part");
                                                            if(mysqli_num_rows($fk) > 0) {
                                                            while($tdy_output = mysqli_fetch_array($fk)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $i; ?></td>
                                                            <td><?= viewImage($tdy_output['item_image'], 30); ?></td>
                                                            <td><?= sales_order_code($tdy_output['order_id']); ?></td>
                                                            <td><?= sales_order_style($tdy_output['style_id']); ?></td>
                                                            <td><?= color_name($tdy_output['combo']); ?></td>
                                                            <td><?= part_name($tdy_output['part']); ?></td>
                                                            <td><?= color_name($tdy_output['color']); ?></td>
                                                            <td><?= $tdy_output['scanned_count']; ?></td>
                                                        </tr>
                                                        <?php 
                                                            $i++; } } else { print '<tr><td colspan="8">No Data Found</td></tr>'; } 
                                                            } else if($emp['department']==1) {
                                                            $pp = "SELECT a.*, b.item_image, sum(c.pcs_per_bundle) as pcs_per_bundle ";
                                                            $pp .= " FROM cutting_barcode a ";
                                                            $pp .= " LEFT JOIN sales_order_detalis b ON a.style = b.id ";
                                                            $pp .= " LEFT JOIN bundle_details c ON a.id = c.cutting_barcode_id ";
                                                            $pp .= " WHERE b.is_dispatch IS NULL AND a.created_unit = '". $logUnit ."' ";
                                                            
                                                            $fk = mysqli_query($mysqli, $pp);
                                                            if(mysqli_num_rows($fk) > 0) {
                                                            while($tdy_output = mysqli_fetch_array($fk)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $i; ?></td>
                                                            <td><?= viewImage($tdy_output['item_image'], 30); ?></td>
                                                            <td><?= sales_order_code($tdy_output['order_id']); ?></td>
                                                            <td><?= sales_order_style($tdy_output['style']); ?></td>
                                                            <td><?= color_name($tdy_output['combo_id']); ?></td>
                                                            <td><?= part_name($tdy_output['part_id']); ?></td>
                                                            <td><?= color_name($tdy_output['color_id']); ?></td>
                                                            <td><?= $tdy_output['pcs_per_bundle']; ?></td>
                                                        </tr>
                                                        <?php
                                                            $i++; } } else { print '<tr><td colspan="8">No Data Found</td></tr>'; } 
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="payment_det" role="tabpanel">
                            <div class="pd-20">
                                <div class="tab">
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#pd_today" role="tab" aria-selected="true">Today</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#pd_total" role="tab" aria-selected="false">Total</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="pd_today" role="tabpanel">
                                            <div class="pd-20">Today Payment</div>
                                        </div>
                                        <div class="tab-pane fade" id="pd_total" role="tabpanel">
                                            <div class="pd-20">Total Payment</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
			
			<?php
			    include('includes/footer.php');
			    include('modals.php');
            ?>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/apexcharts/apexcharts.min.js"></script>
	<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<script src="vendors/scripts/dashboard.js"></script>
	
	<!-- sweetalert -->
	<script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
    <script src="src/plugins/sweetalert2/sweet-alert.init.js"></script>
    
    <script src="quickaction.js"></script>
    
    
    <script>
        $(document).ready(function() {
            emp_details();
        });
        
        
        function emp_details() {
            
            $.post('ajax_search2.php?get_emp_dash_details', '', function(res) {
                var j = $.parseJSON(res);
                
                $(".plan_qty").text(j.plan_qty);
                $(".output_today").text(j.output_today);
                $(".tot_plan_qty").text(j.tot_plan_qty);
                $(".sw_out_tot").text(j.sw_out_tot);
                $(".sw_inp").text(j.sw_inp);
                $(".out_balance").text(j.out_balance);
            });
        }
    </script>
</body>

























