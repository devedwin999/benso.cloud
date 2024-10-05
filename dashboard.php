<?php 
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (!isset($_SESSION['login_id'])) {
	header('Location:index.php');
}

if(EMPLOYEE_DASH == 1) {  ?>
<script>
    window.location.href='empDash.php';
</script>
<?php exit; } else { ?>

<!DOCTYPE html>
<html> 

<head>
	<!-- Basic Page Info --> 
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Main Dashboard</title>

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
    
    .main-hedd {
        height: 60px;
    }
    
    .row-n {
        margin-top:-10px;
    }
    
    @media (max-width: 479px) {
        .main-hedd {
            height: 90px;
        }
        
        .title {
            display: none;
        }
    }
    
    .datepick {
        border: none;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        text-align: left;
        /*color: #00b9c5;*/
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
    
	<?php include('includes/header.php'); ?>
	<?php include('includes/sidebar.php'); ?>

	<div class="main-container nw-cont"> 
        <div class="pd-ltr-20">
            <div class="card-box mb-30">
                <?php if(ADMIN_DASH!=1) { action_denied(); exit; } ?>
            </div>
            <div class="page-header main-hedd">
                <div class="row row-n">
                    <div class="col-md-4 col-sm-4 d-flex">
                        <label style="margin-top: 8px;font-size: 18px;font-weight: bold;">Work Date:</label>
                        <input type="date" value="<?= date('Y-m-d'); ?>" id="filter_date" class="form-control datepick col-6" placeholder="<?= date('d F Y'); ?>">
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <div class="title">
                            <h4 class="" style="color: #1b00ff;margin-top: -7px;">Benso Units Production Status</h4>
                        </div>
                    </div>
                </div>
            </div>
            
			<div class="row">
				<div class="col-xl-2 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data text-center">
							    <img src="src/icons/cutting.png" width="50">
							</div>
							<div class="widget-data">
								<a class="tabclass load_data" data-tgt="cutting" data-toggle="tab" href="#cutting" role="tab" aria-selected="true">
									<div class="h4 mb-0 text-blue cutting_out">0</div>
									<div class="weight-600 font-14 text-blue">Cutting Output <br><span class="out_date"></span></div>
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xl-2 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data text-center">
							    <img src="src/icons/printing.png" width="50">
							</div>
							<div class="widget-data">
								<a class="tabclass load_data" data-tgt="printing" data-toggle="tab" href="#printing" role="tab" aria-selected="false">
									<div class="h4 mb-0 text-light-green printing_out">0</div>
									<div class="weight-600 font-14 text-light-green">Printing Output <br><span class="out_date"></span></div>
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xl-2 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data text-center">
							    <img src="src/icons/sw-in.png" width="50">
							</div>
							<div class="widget-data">
								<a class="tabclass load_data" data-tgt="sewingInp" data-toggle="tab" href="#sewingInp" role="tab" aria-selected="false">
									<div class="h4 mb-0 text-light-orange sewing_inp">0</div>
									<div class="weight-600 font-14 text-light-orange">Sewing Input <br><span class="out_date"></span></div>
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xl-2 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data text-center">
							    <img src="src/icons/sw-out.png" width="50">
							</div>
							<div class="widget-data">
								<a class="tabclass load_data" data-tgt="sewingOut" data-toggle="tab" href="#sewingOut" role="tab" aria-selected="false">
									<div class="h4 mb-0 text-warning sewing_out">0</div>
									<div class="weight-600 font-14 text-warning">Sewing Output <br><span class="out_date"></span></div>
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xl-2 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data text-center">
							    <img src="src/icons/qc.png" width="50">
							</div>
							<div class="widget-data">
								<a class="tabclass load_data" data-tgt="checking" data-toggle="tab" href="#checking" role="tab" aria-selected="false">
									<div class="h4 mb-0 text-light-purple checking_out">
									    
                                    </div>
								</a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xl-2 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data text-center">
							    <img src="src/icons/ironing.png" width="50">
							</div>
							<div class="widget-data">
								<a class="tabclass load_data" data-tgt="iron_pack" data-toggle="tab" href="#iron_pack" role="tab" aria-selected="false">
									<div class="h4 mb-0 text-success">0</div>
									<div class="weight-600 font-14 text-success">Iron & Pack<br><span class="out_date"></span></div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="card-box mb-30">
				<h2 class="h4 pd-20">Order Tracking</h2>
				
				<div class="tab">
					<ul class="nav nav-tabs customtab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#tan_main" role="tab" aria-selected="false">Order Tracking</a>
						</li>
						<li class="nav-item">
							<a class="nav-link load_data cuttingtab" data-toggle="tab" href="#cutting" role="tab" aria-selected="true">Cutting Output</a>
						</li>
						<li class="nav-item">
							<a class="nav-link load_data printingtab" data-toggle="tab" href="#printing" role="tab" aria-selected="false">Printing Output</a>
						</li>
						<li class="nav-item">
							<a class="nav-link load_data sewingInptab" data-toggle="tab" href="#sewingInp" role="tab" aria-selected="false">Sewing Input</a>
						</li>
						<li class="nav-item">
							<a class="nav-link load_data sewingOuttab" data-toggle="tab" href="#sewingOut" role="tab" aria-selected="false">Sewing Output</a>
						</li>
						<li class="nav-item">
							<a class="nav-link load_data checkingtab" data-toggle="tab" href="#checking" role="tab" aria-selected="false">Checking Passed</a>
						</li>
						<li class="nav-item">
							<a class="nav-link load_data iron_packtab" data-toggle="tab" href="#iron_pack" role="tab" aria-selected="false">Iron & Pack</a>
						</li>
					</ul>
					
					<div class="tab-content">
					    
						<div class="tab-pane fade show active" id="tan_main" role="tabpanel">
							<div class="pd-20">
                                <div  style="overflow-y: auto;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="20"><a class="btn btn-outline-primary show_filter"><i class="icon-copy fi-filter"></i> Filter</a></th>
                                            </tr>
                                            <tr>
                                                <th>Sl.No</th>
                                                <th>Style Image</th>
                                                <th>Brand</th>
                                                <th>BO</th>
                                                <th>Style</th>
                                                <th>Combo</th>
                                                <th>Part</th>
                                                <th>Color</th>
                                                <th>Delivery Date</th>
                                                <th>Order Qty</th>
                                                <th>Cut PlanQty</th>
                                                <th>Cutting Qty</th>
                                                <th>Sewing In</th>
                                                <th>Sewing Out</th>
                                                <th>Checking Qty</th>
                                                <th>Ironing Qty</th>
                                                <th>Packing Qty</th>
                                                <th>Dispatch Qty</th>
                                                <th>Balance Qty</th>
                                                <th>Meeting Review</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order_track_tbody">
                                            <?php
                                                
                                                $qry = "SELECT a.*, b.item_image, b.total_qty, b.excess, b.is_dispatch, b.delivery_date, c.brand ";
                                                $qry .= " FROM sod_part a ";
                                                $qry .= " LEFT JOIN sales_order_detalis b ON a.sales_order_detail_id = b.id ";
                                                $qry .= " LEFT JOIN sales_order c ON a.sales_order_id = c.id ";
                                                
                                                $qry .= " WHERE b.delivery_date > '". date("Y-m-t") ."' AND b.is_dispatch IS NULL ";
                                                
                                                $qry .= " ORDER BY a.id ASC ";
                                                
                                                $query = mysqli_query($mysqli, $qry);
                                                
                                                $num_ = mysqli_num_rows($query);
                                                if ($num_ > 0) {
                                                    $pp = 1;
                                                    while ($sql = mysqli_fetch_array($query)) { 
                                                        
                                                        print '<tr>
                                                                    <td>'. $pp .'</td>
                                                                    <td>'. viewImage($sql['item_image'], 30) .'</td>
                                                                    <td>'. brand_name($sql['brand']) .'</td>
                                                                    <td>'. sales_order_code($sql['sales_order_id']) .'</td>
                                                                    <td>'. sales_order_style($sql['sales_order_detail_id']) .'</td>
                                                                    <td>'. color_name($sql['combo_id']) .'</td>
                                                                    <td>'. part_name($sql['part_id']) .'</td>
                                                                    <td>'. color_name($sql['color_id']) .'</td>
                                                                    <td>'. $sql['delivery_date'] .'</td>
                                                                    <td>'. $sql['total_qty'] .'</td>
                                                                    <td>'. round($sql['total_qty'] + (($sql['excess'] / 100) * $sql['total_qty'])) .'</td>
                                                                    <td>'. tot_cutting_qty_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                                                    <td>'. tot_sewing_in_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                                                    <td>'. tot_sewing_out_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                                                    <td>'. tot_checking_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                                                    <td>-</td>
                                                                    <td>-</td>
                                                                    <td>-</td>
                                                                    <td>-</td>
                                                                    <td>
                                                                        <i class="icon-copy ion-plus-round 1conA1'. $pp .'" onclick="addIcon('. $pp .')"></i> &nbsp;
                                                                        <span><textarea class="form-control dhide 1conA2'. $pp .'" id="comment'. $pp .'" style="height: 50px;"></textarea></span>
                                                                        <span class="1conA1'. $pp .' txt'. $pp .'"  title="By : '. $cmt['employee_name'] .'"> '. $cmt['comment'] .'</span>
                                                                        <i class="icon-copy ion-checkmark dhide 1conA2'. $pp .'" onclick="saveIcon('. $pp .','.$sql['id'].')"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <i class="icon-copy ion-close-round dhide 1conA2'. $pp .'" onclick="cancelIcon('. $pp .')"></i>
                                                                    </td>
                                                                </tr>';
                                                        $pp++;
                                                    }
                                                } else {
                                                    print '<tr><td colspan="20" class="text-center">--No data found--</td></tr>';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
							</div>
						</div>
                        
						<div class="tab-pane fade cuttingdiv" id="cutting" role="tabpanel">

							<div class="pd-20">
								<hr>

								<div class="row">
									<div class="col-md-9"></div>
									<div class="col-md-2">
										<!-- <label for=""></label> -->
										 <select class="form-control custom-select2" id="cutting_based" style="width:100%">
											<option value="order">BO wise Cutting</option>
											<option value="unit">Unit wise Cutting</option>
										 </select>
									</div>
									<div class="col-md-1">
										<a class="btn-outline-secondary btn load_data" href="#cutting"><i class="fa-filter fa"></i></a>
									</div>
								</div>
							</div>

							<div class="pd-20" style="overflow-y: auto;">
								<table class="table hover table-bordered data-table-export nowrap">
									<thead>
										<tr>
											<th>BO NO</th>
											<th>Style</th>
											<th>Style Image</th>
											<th>Combo</th>
											<th>Part</th>
											<th>Color</th>
											<th>Unit</th>
											<th>Order Qty</th>
											<th>Today Cutting Qty</th>
											<th>Total Cutting Qty</th>
											<th>Cutting Percentage</th>
										</tr>
									</thead>
									<tbody>
									    <tr><td class="text-center" colspan="11">-- No Data Found --</td></tr>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="tab-pane fade sewingInpdiv" id="sewingInp" role="tabpanel">
							<div class="pd-20" style="overflow-y: auto;">
								<table class="table hover multiple-select-row data-table-export nowrap">
									<thead>
										<tr>
											<th>BO NO</th>
											<th>Style</th>
											<th>Style Image</th>
											<th>Combo</th>
											<th>Part</th>
											<th>Color</th>
											<th>Order Qty</th>
											<th>Cutting Qty</th>
											<th>Today Sewing Input Qty</th>
											<th>Total Sewing Input Qty</th>
											<th>Sewing Input Percentage</th>
											<th>Cutting Percentage</th>
										</tr>
									</thead>
									<tbody>
									    <tr><td class="text-center" colspan="12">-- No Data Found --</td></tr>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="tab-pane fade sewingOutdiv" id="sewingOut" role="tabpanel">
							<div class="pd-20" style="overflow-y: auto;">
								<table class="table hover multiple-select-row data-table-export nowrap">
									<thead>
										<tr>
											<th>BO NO</th>
											<th>Style</th>
											<th>Style Image</th>
											<th>Combo</th>
											<th>Part | Color</th>
											<th>Order Qty</th>
											<th>Cutting Qty</th>
											<th>Output Date</th>
											<th>Unit</th>
											<th>Today Sewing Output Qty</th>
											<th>Total Sewing Output Qty</th>
											<th>Sewing Output Percentage</th>
										</tr>
									</thead>
									<tbody>
									    <tr><td class="text-center" colspan="10">-- No Data Found --</td></tr>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="tab-pane fade checkingdiv" id="checking" role="tabpanel">
							<div class="pd-20" style="overflow-y: auto;">
								<table class="table hover ">
									<thead>
										<tr>
											<th rowspan="2">BO NO</th>
											<th rowspan="2">Style</th>
											<th rowspan="2">Style Image</th>
											<th rowspan="2">Combo</th>
											<th rowspan="2">Part</th>
											<th rowspan="2">Color</th>
											<th rowspan="2">Order Qty</th>
											<th rowspan="2">Unit</th>
											<th class="d-none" rowspan="2">Sewing Output Qty</th>
											<th colspan="2">Good Pcs</th>
											<th colspan="2">Rework Pcs</th>
											<th colspan="2">Rejection Pcs</th>
											<th colspan="2">TTL Checking</th>
										</tr>
										<tr>
										    <td>Tdy</td>
										    <td>TTL</td>
										    <td>Tdy</td>
										    <td>TTL</td>
										    <td>Tdy</td>
										    <td>TTL</td>
										    <td>Tdy</td>
										    <td>TTL</td>
										</tr>
									</thead>
									<tbody>
									    <tr><td class="text-center" colspan="17">-- No Data Found --</td></tr>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="tab-pane fade printingdiv" id="printing" role="tabpanel">
							<div class="pd-20" style="overflow-y: auto;">
								<table class="table hover multiple-select-row data-table-export nowrap">
									<thead>
										<tr>
											<th>BO NO</th>
											<th>Style</th>
											<th>Style Image</th>
											<th>Combo</th>
											<th>Part</th>
											<th>Color</th>
											<th>Order Qty</th>
											<th>Today Printing Qty</th>
											<th>Total Printing Qty</th>
											<th>Printing Percentage</th>
										</tr>
									</thead>
									<tbody>
									    <tr><td class="text-center" colspan="10">-- No Data Found --</td></tr>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="tab-pane fade checkingdiv" id="iron_pack" role="tabpanel">
							<div class="pd-20" style="overflow-y: auto;">
								<p>Iron & Pack Under Developing</p>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
			<div class="row">
				<div class="col-xl-12 mb-30">
					<div class="card-box height-100-p pd-20">
						<h2 class="h4 mb-20">Activity (<?= date('d-m-y') ?>)</h2>
						<div id="chart5"></div>
					</div>
				</div>
				
			</div>
			<?php
			    $modals = ["report_filter-modal", "image-modal"];
			    include('modals.php');
			    include('includes/footer.php');
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
</body>

<script>
    function addIcon(id) {
        
        $(".1conA2"+id).show();
        $(".1conA1"+id).hide();
    }
    
    function cancelIcon(id) {
        
        $(".1conA2"+id).hide();
        $(".1conA1"+id).show();
    }
    
    function saveIcon(e, id) {
        $("#overlay").fadeIn(100);
        var cmt = $("#comment"+e).val();
        
        var data = {
            comment_from : 'Order Tracking',
            table : 'sod_part',
            primary_id : 'id',
            comment : cmt,
        }
        
        $.post('ajax_action.php?common_comment', data, function(resp) {
            var j = $.parseJSON(resp);
            
            $("#overlay").fadeOut(500);
            if(j.result==0) {
                message_noload('success', 'Comment Added!', 1500);
                $("#comment"+e).val(cmt);
                $(".txt"+e).text(cmt);
                cancelIcon(e);
            } else {
                message_error()
            }
        });
    }
</script>

<script>
    $(".filtttr_search").click(function() {
        $("#overlay").fadeIn(100);
        
        var data = {
            style_id: $(".order_bdg:checked").map(function() { return $(this).val(); }).get(),
            brand: $(".buyer_bdg:checked").map(function() { return $(this).val(); }).get(),
            employee: $(".emplo_bdg:checked").map(function() { return $(this).val(); }).get(),
            unit: $(".unitt_bdg:checked").map(function() { return $(this).val(); }).get(),
            supplier: $(".supp_bdg:checked").map(function() { return $(this).val(); }).get(),
            del_dt_bdg: $(".del_dt_bdg").is(':checked'),
            del_dt_start: $(".del_dt_start").val(),
            del_dt_end: $(".del_dt_end").val(),
            ent_dt_bdg: $(".ent_dt_bdg").is(':checked'),
            ent_dt_start: $(".ent_dt_start").val(),
            ent_dt_end: $(".ent_dt_end").val(),
            order_type: $("input[name='type_completed_report']:checked").val()
        };
        
        $.post('ajax_reports.php?production_order_tracking', data, function(msg) {
            var j = $.parseJSON(msg);
            
            $("#order_track_tbody").html(j.tbody);
            $("#report_filter-modal").modal('hide');
            $("#overlay").fadeOut(500);
        });
    });
</script>

<script>
    $(".load_data").click(function() {
        $("#overlay").fadeIn(100);
        
        var href = $(this).attr('href');
        href = href.substring(1);
        
        var data = {
            type : href,
			cutting_based: $("#cutting_based").val(),
            filter_date : $("#filter_date").val(),
        }
        
        $.post('ajax_search2.php?get_daily_prodiction_status_details', data, function(msg) {
            var j = $.parseJSON(msg);
            
			if(href=='cutting') {

				$("#overlay").fadeOut(500);
				$("." + href +'div').find('tbody').html(j.tbody);
				$("." + href +'div').find('thead').html(j.thead);
			} else {
				$("#overlay").fadeOut(500);
				$("." + href +'div').find('tbody').html(j.tbody);
			}
        });
    });
</script>

<script>
    function daily_status() {
        
        var data = {
            filter_date : $("#filter_date").val(),
        }
        
        $.post('ajax_search2.php?get_daily_prodiction_status', data, function(msg) {
            
            var j = $.parseJSON(msg);
            $(".cutting_out").text(j.cutting_out);
            $(".sewing_inp").text(j.sewing_inp);
            $(".sewing_out").text(j.sewing_out);
            $(".printing_out").text(j.printing_out);
            $(".checking_out").html(j.checking_out);
        });
    }
</script>

<script>
	$(document).ready(function() {
		$('a.margin-5').data('data-content', 20);
		filter_fun_date();
		daily_status();
	});
	
// 	setInterval(daily_status, 5000);
</script>


<script>
    
    $("#filter_date").change(function() {
        filter_fun_date();
        daily_status();
    });
    
    function filter_fun_date() {
        
        $("#overlay").fadeIn(100);
            var filter_date = $("#filter_date").val();
            
            var parts = filter_date.split('-');
            var year = parts[0];
            var month = parts[1];
            var day = parts[2];
            var newDateFormat = day + '-' + month + '-' + year;
            
            
            $(".out_date").text(newDateFormat);
        $("#overlay").fadeOut(500);
    }
    
</script>


<script>
	$(".tabclass").click(function () {

		$(".nav-link").removeClass('active');
		$(".tab-pane").removeClass('show active');

		var sd = $(this).attr('data-tgt');
		$("." + sd + "tab").addClass('active');
		$("." + sd + "div").addClass('show active');
	})
	
	$(document).ready(function() {
        searchNoti();
    })
</script>

</html>

<?php } ?>