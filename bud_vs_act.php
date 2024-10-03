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
	<title>BENSO - Budget Vs Actual Dashboard</title>

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
        
        .dataTables_filter, .dataTables_paginate {
            float: right !important;
        }
    </style>

</head>

<body>
    
	<?php include('includes/header.php'); ?>
	<?php include('includes/sidebar.php'); ?>

	<div class="main-container nw-cont">
		<div class="pd-ltr-20">
		    
            <div class="card-box mb-30">
                <?php //if(WIP_DASH!=1) { action_denied(); exit; } ?>
            </div>
            
            <div class="page-header" style="height: 52px;">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4 class="text-center text-muted" style="color:;margin-top: -7px;">Budget Vs Actual</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
                
                $prod = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(b.rate * a.order_qty) as tot_budd FROM sales_order a LEFT JOIN budget_process b ON b.so_id = a.id WHERE a.is_dispatch IS NULL AND b.budget_for = 'Production Budget'"));
                $fabr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(b.rate * a.order_qty) as tot_budd FROM sales_order a LEFT JOIN budget_process b ON b.so_id = a.id WHERE a.is_dispatch IS NULL AND b.budget_for = 'Fabric Budget'"));
                $acce = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(b.rate * a.order_qty) as tot_budd FROM sales_order a LEFT JOIN budget_process b ON b.so_id = a.id WHERE a.is_dispatch IS NULL AND b.budget_for = 'Accessories Budget'"));
            ?>
            
            <div class="row">
                <div class="col-xl-3 mb-30 fetch_data" data-tab="Fabric">
                    <div class="card-box height-100-p widget-style1">
                        <div class="row text-center">
                            <div class="col-md-12 col-12 h4 text-info">Fabric<hr></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Budget</span><br><p><?= $fabr['tot_budd'] ? $fabr['tot_budd'] : 0; ?></p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Running</span><br><p>0</p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Actual</span><br><p>0</p></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 mb-30 fetch_data" data-tab="Accessories">
                    <div class="card-box height-100-p widget-style1">
                        <div class="row text-center">
                            <div class="col-md-12 col-12 h4 text-info">Accessories<hr></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Budget</span><br><p><?= $acce['tot_budd'] ? $acce['tot_budd'] : 0; ?></p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Running</span><br><p>0</p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Actual</span><br><p>0</p></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 mb-30 fetch_data" data-tab="Production">
                    <div class="card-box height-100-p widget-style1">
                        <div class="row text-center">
                            <div class="col-md-12 col-12 h4 text-info">Production<hr></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Budget</span><br><p><?= $prod['tot_budd'] ? $prod['tot_budd'] : 0; ?></p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Running</span><br><p>0</p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Actual</span><br><p>0</p></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 mb-30 fetch_data" data-tab="Others">
                    <div class="card-box height-100-p widget-style1">
                        <div class="row text-center">
                            <div class="col-md-12 col-12 h4 text-info">Others<hr></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Budget</span><br><p>0</p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Running</span><br><p>0</p></div>
                            <div class="col-md-4 col-4"><span class="h6 u">Actual</span><br><p>0</p></div>
                        </div>
                    </div>
                </div>
			</div>
				
				
            <div class="page-header row buyer_div">
                <div class="col-md-12 text-center">
                    <h4><span id="t-name">Production</span> <small>(Budget Vs Actual)</small></h4>
                    <hr>
                </div>
                <div class="col-md-12" style="overflow-y:auto;">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>                                   
                                <th style="min-width: 100px;">BO</th>
                                <th>Brand</th>
                                <th>Order Qty</th>
                                <th>Budget Cost</th>
                                <th>Running Cost</th>
                                <th>Actual Cost</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr><td class="text-center" colspan="6">No Data Available</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
        $modals[] = "employee-edit-modal";
        include('modals.php');
        include('includes/end_scripts.php');
    ?>
    <script>
        
        function fun(element) {
            var bo = $(element).data('bo');
            var type = $(element).data('type');
            var ic = $(element).find('i');
            
            $("#overlay").fadeIn(100);
            
            if(ic.hasClass('ion-chevron-right') == true) {
                
                var data = {
                    bo: bo,
                    type: type,
                };
                
                $.post('ajax_search2.php?budget_actual_bo', data, function(msg) {
                    var j = $.parseJSON(msg);
                    
                    setTimeout(function(){
                        $(".new_tr" + bo).removeClass('d-none');
                        $("#new_tbody" + bo).html(j.new_tr);
                        // $(".resp_brand" + brand).html(j.html);
                        ic.removeClass('ion-chevron-right').addClass('ion-chevron-down');
                        $("#overlay").fadeOut(500);
                    },300);
                });
            } else {
                setTimeout(function(){
                    $(".new_tr" + bo).addClass('d-none');
                    ic.removeClass('ion-chevron-down').addClass('ion-chevron-right');
                    $("#overlay").fadeOut(500);
                },200);
            }
        }
        
	</script>
	
    <script>
        $(".fetch_data").click(function() {
            
            var name =  $(this).data('tab');
            
            $("#overlay").fadeIn(100);
            $("#t-name").text(name);
            
            var data = {
                type : name,
            }
            
            $.post('ajax_search2.php?budget_vs_actual', data, function(resp) {
                var j = $.parseJSON(resp);
                
                $("#tbody").html(j.tbody);
                $("#overlay").fadeOut(500);
            });
            
        });
    </script>
    
</body>
</html>










