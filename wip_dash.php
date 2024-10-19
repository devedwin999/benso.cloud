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
	<title>BENSO - WIP Report Dashboard</title>

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
	<style>
        .font{
            font-size: 17px;
        }
    </style>

<body>

	<?php include('includes/header.php'); ?>
	<?php include('includes/sidebar.php'); ?>

	<div class="main-container nw-cont">
		<div class="pd-ltr-20">
		    
                <div class="card-box mb-30">
                    <?php page_spinner(); if(WIP_DASH!=1) { action_denied(); exit; } ?>
                </div>
                
                <?php
                    $in_hand = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.total_excess * b.part_count) as in_hand FROM sales_order_detalis a LEFT JOIN unit b ON a.unit_id = b.id LEFT JOIN sales_order c ON a.sales_order_id = c.id WHERE c.is_approved = 'approved' AND a.is_dispatch IS NULL "));
                    
                    $uio = mysqli_query($mysqli, "SELECT a.id, a.total_excess FROM sales_order_detalis a LEFT JOIN sales_order c ON a.sales_order_id = c.id WHERE c.is_approved = 'approved' AND a.is_dispatch IS NULL");
                    $unit_plan = $line_plan = $cutting = 0;
                    while($styy = mysqli_fetch_array($uio)) {
                        
                        $fet = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id,plan_type FROM process_planing WHERE style_id = '". $styy['id'] ."' AND process_id = 1"));
                        
                        if($fet['plan_type']=='Partial') {
                            $ptl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(combo_part_qty) as combo_part_qty FROM cutting_partial_planning WHERE process_planing_id = '". $fet['id']. "'"));
                            $unit_plan += $ptl['combo_part_qty'];
                        } else if($fet['plan_type']=='Full') {
                            $unit_plan += $styy['total_excess'];
                        }
                        
                        
                        $ck = mysqli_query($mysqli, "SELECT id, plan_qty, planning_type FROM line_planning WHERE style_id =  '". $styy['id'] ."'");
                        while($line = mysqli_fetch_array($ck)) {
                            if($line['planning_type'] == 'Full') {
                                $line_plan += $line['plan_qty'];
                            } else {
                                $ck1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(order_qty) as order_qty FROM line_planning_size WHERE line_planning_id =  '". $line['id'] ."'"));
                                $line_plan += $ck1['order_qty'];
                            }
                        }
                        
                        $ctt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE style_id = '". $styy['id'] ."' "));
                        $cutting += $ctt['pcs_per_bundle'];
                    }
                ?>
                
                <div class="row">
                    <div class="col-xl-3 mb-30 tab_boxx" data-tab="buyer">
                        <div class="card-box height-100-p widget-style1">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="progress-data">
                                    <div id="chart111" style="text-align: center;font-size: 44px;">
                                        <img src="src/icons/order_in_hand.ico">
                                    </div>
                                </div>
                                <div class="widget-data text-center">
                                    <div class="h4 mb-0 text-blue"><?= $in_hand['in_hand'] ? number_format($in_hand['in_hand']) : 0; ?></div>
                                    <div class="weight-600 font-14 text-blue">Order In Hand</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 mb-30 tab_boxx" data-tab="unit">
                        <div class="card-box height-100-p widget-style1">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="progress-data">
                                    <div id="chart111" style="text-align: center;font-size: 44px;">
                                        <img src="src/icons/unit-planned.png" width="60">
                                    </div>
                                </div>
                                <div class="widget-data text-center">
                                    <div class="h4 mb-0 text-blue"><?= $unit_plan ? number_format($unit_plan) : 0; ?></div>
                                    <div class="weight-600 font-14 text-blue">Unit Planned</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 mb-30 tab_boxx" data-tab="buyer">
                        <div class="card-box height-100-p widget-style1">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="progress-data">
                                    <div id="chart111" style="text-align: center;font-size: 44px;">
                                        <img src="src/icons/line-planned.png" width="60">
                                    </div>
                                </div>
                                <div class="widget-data text-center">
                                    <div class="h4 mb-0 text-blue"><?= $line_plan ? $line_plan : 0; ?></div>
                                    <div class="weight-600 font-14 text-blue">Line Planned</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 mb-30 tab_boxx" data-tab="buyer">
                        <div class="card-box height-100-p widget-style1">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="progress-data">
                                    <div id="chart111" style="text-align: center;font-size: 44px;">
                                        <img src="src/icons/wip.png" width="60">
                                    </div>
                                </div>
                                <div class="widget-data text-center">
                                    <div class="h4 mb-0 text-blue"><?= $cutting; ?></div>
                                    <div class="weight-600 font-14 text-blue">Work In Progress</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
				</div>
				
				
            <div class="page-header buyer_div" style="overflow-y:auto;">
                
                <h4>Buyer Wise Details</h4>
                <table class="table table-bordered hover nowrap">
                    <thead>
                        <tr >                                   
                            <th>Buyer Name</th>
                            <th>In-hand Qty</th>
                            <th>Unit Planned</th>
                            <th>Line Planned</th>
                            <th>Work In Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $pp=1;
                            $innn = mysqli_query($mysqli, "SELECT a.id, a.total_excess, b.brand FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id WHERE b.is_approved = 'approved' AND a.is_dispatch IS NULL GROUP BY b.brand");
                            $unit_plan_brand = $line_plan_brand = $cutting_brand = 0; $styles = array();
                            while($row = mysqli_fetch_array($innn)) {
                                
                                $bd = mysqli_query($mysqli, "SELECT a.id FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id WHERE b.brand = '". $row['brand'] ."' ");
                                while($bnd = mysqli_fetch_array($bd)) {
                                    $styles[] = $bnd['id'];
                                }
                                
                                $style_ids = implode(',', $styles);
                                
                                $qyy = "SELECT sum(a.total_excess * b.part_count) as in_hand ";
                                $qyy .= " FROM sales_order_detalis a ";
                                $qyy .= " LEFT JOIN unit b ON a.unit_id = b.id ";
                                $qyy .= " LEFT JOIN sales_order c ON a.sales_order_id = c.id ";
                                $qyy .= " WHERE c.is_approved = 'approved' AND c.brand='". $row['brand'] ."' AND a.is_dispatch IS NULL";
                                $in_hand_brand = mysqli_fetch_array(mysqli_query($mysqli, $qyy));
                                
                                $ppf = mysqli_query($mysqli, "SELECT a.*, b.total_excess FROM process_planing a LEFT JOIN sales_order_detalis b ON a.style_id = b.id WHERE a.style_id IN (". $style_ids .") AND a.process_id = 1");
                                $unit_plan_brand = $line_plan_brand = $cutting_brand = 0;
                                while($fet = mysqli_fetch_array($ppf)) {
                                    if($fet['plan_type']=='Partial') {
                                        
                                        $ptl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(combo_part_qty) as combo_part_qty FROM cutting_partial_planning WHERE process_planing_id = '". $fet['id']. "'"));
                                        $unit_plan_brand += $ptl['combo_part_qty'];
                                    } else {
                                        $unit_plan_brand += $fet['total_excess'];
                                    }
                                }
                                
                                
                                $ck = mysqli_query($mysqli, "SELECT id, plan_qty, planning_type FROM line_planning WHERE style_id IN (". $style_ids .")");
                                while($line = mysqli_fetch_array($ck)) {
                                    if($line['planning_type'] == 'Full') {
                                        $line_plan_brand += $line['plan_qty'];
                                    } else {
                                        $ck1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(order_qty) as order_qty FROM line_planning_size WHERE line_planning_id =  '". $line['id'] ."'"));
                                        $line_plan_brand += $ck1['order_qty'];
                                    }
                                }
                                
                                $ctt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE style_id IN (". $style_ids .") "));
                                $cutting_brand += $ctt['pcs_per_bundle'];
                        ?>
                            <tr>
                                <td><a class="custom_a brand_cls" data-brand="<?= $row['brand']; ?>"><i class="icon-copy ion-chevron-right"></i> <?= brand_name($row['brand']); ?></a></td>
                                <td><?= number_format($in_hand_brand['in_hand']); ?></td>
                                <td><?= $unit_plan_brand ? $unit_plan_brand : 0; ?></td>
                                <td><?= $line_plan_brand ? $line_plan_brand : 0; ?></td>
                                <td><?= $cutting_brand ? $cutting_brand : 0; ?></td>
                            </tr>
                            <tr><td style="background: #f8f9fac7;" colspan="5" class="d-none brand_html<?= $row['brand']; ?>">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>BO</th>
                                            <th>Style</th>
                                            <th>Delivery Date</th>
                                            <th>In-hand</th>
                                            <th>Unit Planned</th>
                                            <th>Line Planned</th>
                                            <th>WIP</th>
                                        </tr>
                                    </thead>
                                    <tbody class="resp_brand<?= $row['brand']; ?>"></tbody>
                                </table>
                            </td></tr>
                        <?php $pp++; unset($styles); } ?>
                    </tbody>
                </table>
            </div>
            
            <div class="page-header unit_div d-none" style="overflow-y:auto;">
                
                <h4>Unit Wise Details</h4>
                <table class="table table-bordered hover nowrap">
                    <thead>
                        <tr >                                   
                            <th>Buyer Name</th>
                            <th>In-hand Qty</th>
                            <th>Unit Planned</th>
                            <th>Line Planned</th>
                            <th>Work In Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $pp=1;
                            $innn = mysqli_query($mysqli, "SELECT a.id, a.total_excess, b.brand, b.created_unit FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id WHERE b.is_approved = 'approved' AND a.is_dispatch IS NULL GROUP BY b.created_unit");
                            $unit_plan_brand = $line_plan_brand = $cutting_brand = 0; $styles = array();
                            while($row = mysqli_fetch_array($innn)) {
                                
                                $bd = mysqli_query($mysqli, "SELECT a.id FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id WHERE b.created_unit = '". $row['created_unit'] ."' ");
                                while($bnd = mysqli_fetch_array($bd)) {
                                    $styles[] = $bnd['id'];
                                }
                                
                                $style_ids = implode(',', $styles);
                                
                                $qyy = "SELECT sum(a.total_excess * b.part_count) as in_hand ";
                                $qyy .= " FROM sales_order_detalis a ";
                                $qyy .= " LEFT JOIN unit b ON a.unit_id = b.id ";
                                $qyy .= " LEFT JOIN sales_order c ON a.sales_order_id = c.id ";
                                $qyy .= " WHERE c.is_approved = 'approved' AND c.created_unit='". $row['created_unit'] ."' AND a.is_dispatch IS NULL";
                                $in_hand_brand = mysqli_fetch_array(mysqli_query($mysqli, $qyy));
                                
                                $ppf = mysqli_query($mysqli, "SELECT a.*, b.total_excess FROM process_planing a LEFT JOIN sales_order_detalis b ON a.style_id = b.id WHERE a.style_id IN (". $style_ids .") AND a.process_id = 1");
                                $unit_plan_brand = $line_plan_brand = $cutting_brand = 0;
                                while($fet = mysqli_fetch_array($ppf)) {
                                    if($fet['plan_type']=='Partial') {
                                        
                                        $ptl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(combo_part_qty) as combo_part_qty FROM cutting_partial_planning WHERE process_planing_id = '". $fet['id']. "'"));
                                        $unit_plan_brand += $ptl['combo_part_qty'];
                                    } else {
                                        $unit_plan_brand += $fet['total_excess'];
                                    }
                                }
                                
                                
                                $ck = mysqli_query($mysqli, "SELECT id, plan_qty, planning_type FROM line_planning WHERE style_id IN (". $style_ids .")");
                                while($line = mysqli_fetch_array($ck)) {
                                    if($line['planning_type'] == 'Full') {
                                        $line_plan_brand += $line['plan_qty'];
                                    } else {
                                        $ck1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(order_qty) as order_qty FROM line_planning_size WHERE line_planning_id =  '". $line['id'] ."'"));
                                        $line_plan_brand += $ck1['order_qty'];
                                    }
                                }
                                
                                $ctt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE style_id IN (". $style_ids .") "));
                                $cutting_brand += $ctt['pcs_per_bundle'];
                        ?>
                            <tr>
                                <td><a class="custom_a unit_cls" data-unit="<?= $row['created_unit']; ?>"><i class="icon-copy ion-chevron-right"></i> <?= company_code($row['created_unit']); ?></a></td>
                                <td><?= number_format($in_hand_brand['in_hand']); ?></td>
                                <td><?= $unit_plan_brand ? $unit_plan_brand : 0; ?></td>
                                <td><?= $line_plan_brand ? $line_plan_brand : 0; ?></td>
                                <td><?= $cutting_brand ? $cutting_brand : 0; ?></td>
                            </tr>
                            <tr><td style="background: #f8f9fac7;" colspan="5" class="d-none unit_html<?= $row['created_unit']; ?>">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Brand</th>
                                            <th>BO</th>
                                            <th>Style</th>
                                            <th>Delivery Date</th>
                                            <th>In-hand</th>
                                            <th>Unit Planned</th>
                                            <th>Line Planned</th>
                                            <th>WIP</th>
                                        </tr>
                                    </thead>
                                    <tbody class="resp_unit<?= $row['created_unit']; ?>"></tbody>
                                </table>
                            </td></tr>
                        <?php $pp++; unset($styles); } ?>
                    </tbody>
                </table>
        	</div>        	
        </div>
    </div>
    
<!--<div class="row">-->
<!--    <div class="col-md-6 mb-30"><br>-->
<!--		<div class="pd-20 card-box height-50-p"  style="width: 63%;margin-left:50%;">-->
<!--			<h4 class="h4 text-blue"> Plan Qty </h4>-->
<!--			<div id="chart8"></div>-->
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
    <?php
        $modals[] = "employee-edit-modal";
        include('modals.php');
        include('includes/end_scripts.php');
    ?>
    
    <script>
    
        $(".tab_boxx").click(function() {
            var tab = $(this).data('tab');
            
            $("#overlay").fadeIn(100);
            (tab == 'buyer') ? $(".buyer_div").removeClass('d-none') : $(".buyer_div").addClass('d-none');
            (tab == 'unit') ? $(".unit_div").removeClass('d-none') : $(".unit_div").addClass('d-none');
            $("#overlay").fadeOut(300);
        });

    </script>
	
	<script>
        $(".unit_cls").click(function() {
            
            $("#overlay").fadeIn(100);
            
            var unit = $(this).data('unit');
            var ic = $(this).find('i');
            
            if(ic.hasClass('ion-chevron-right') == true) {
                
                var data = {
                    unit: unit,
                };
                
                $.post('ajax_search2.php?get_unit_wip', data, function(msg) {
                    var j = $.parseJSON(msg);
                    
                    if(j.count>0) {
                        setTimeout(function(){
                            $(".unit_html" + unit).removeClass('d-none');
                            $(".resp_unit" + unit).html(j.html);
                            ic.removeClass('ion-chevron-right').addClass('ion-chevron-down');
                            $("#overlay").fadeOut(500);
                        },300);
                    }
                });
            } else {
                setTimeout(function(){
                    $(".unit_html" + unit).addClass('d-none');
                    ic.removeClass('ion-chevron-down').addClass('ion-chevron-right');
                    $("#overlay").fadeOut(500);
                },200);
            }
	    });
	</script>
	
	<script>
        $(".brand_cls").click(function() {
            
            $("#overlay").fadeIn(100);
            
            var brand = $(this).data('brand');
            var ic = $(this).find('i');
            
            if(ic.hasClass('ion-chevron-right') == true) {
                
                var data = {
                    brand: brand,
                };
                
                $.post('ajax_search2.php?get_brand_wip', data, function(msg) {
                    var j = $.parseJSON(msg);
                    
                    if(j.count>0) {
                        setTimeout(function(){
                            $(".brand_html" + brand).removeClass('d-none');
                            $(".resp_brand" + brand).html(j.html);
                            ic.removeClass('ion-chevron-right').addClass('ion-chevron-down');
                            $("#overlay").fadeOut(500);
                        },300);
                    }
                });
            } else {
                setTimeout(function(){
                    $(".brand_html" + brand).addClass('d-none');
                    ic.removeClass('ion-chevron-down').addClass('ion-chevron-right');
                    $("#overlay").fadeOut(500);
                },200);
            }
	    });
	</script>
</body>
</head>
</html>