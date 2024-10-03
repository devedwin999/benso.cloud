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
    <title>BENSO GARMENTING - Production Menus</title>

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
        .nw_a {
            color: #1b00ff !important;
            font-size:18px;
           ing-left: 10px;
        }
        
        .padd a:nth-of-type(2) {
            margin-left: 20px;
        }
        
        .padd {
           ing: 10px !important;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="card-box mb-30">
                    
                    <?php if(MOD_PRODUCTION!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        
                        <h4 class="text-dark h4">Production</h4><br>
                        
                        <div class="accordion" id="accordionExample">
                            <?php if(MOD_PRODUCTION == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-dark" type="button" data-toggle="collapse" data-target="#production" aria-expanded="true" aria-controls="production">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> <b style="font-size:22px;">Production Entry's</b>
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row text-blue" style="font-weight:bold;">
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="#" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Fabric Inward</a><br>
                                                            <a href="#"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="#"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="#" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Cutting Consumption</a><br>
                                                            <a href="#"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="#"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php if(CUTTING_QR==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="view-barcode.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Cutting</a><br>
                                                            <a href="barcode.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="view-barcode.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="dev_garment_process.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Garment Process</a><br>
                                                            <a href="#"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="dev_garment_process.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(P_OUTWARD==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="view-processing.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Process Outward</a><br>
                                                            <a href="add-processing.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="view-processing.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(P_INWARD==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="inward-list.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Process Inward</a><br>
                                                            <a href="inward.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="inward-list.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php //} if(P_INWARD==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="javascript:;" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Unit in | out</a><br>
                                                            <a href="in_house.php"><small><i class="fa fa-plus"></i> In</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="in_house_daily_status.php?dc"><small><i class="fa fa-list"></i> Out</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(SEW_INPUT==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="view-sewingInput.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Sewing Input</a><br>
                                                            <a href="s-input.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="view-sewingInput.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(SEW_OUTPUT==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="view-sewingOutput.php?ps=all" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Sewing Output</a><br>
                                                            <a href="s-output.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="view-sewingOutput.php?ps=all"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(CHECKING==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="checking-list.php?ps=all" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Checking Entry</a><br>
                                                            <a href="checking-new.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="checking-list.php?ps=all"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="component-process-list.php?ps=all" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Component Process</a><br>
                                                            <a href="#"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="component-process-list.php?ps=all"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="rework-list.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Rework Entry</a><br>
                                                            <a href="rework-new.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="rework-list.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(IRONING==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="ironing.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Ironing</a><br>
                                                            <a href="ironing-add.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="ironing.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(PACKING==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="packing.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Packing</a><br>
                                                            <a href="packing-add.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="packing.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(COST_GENERATION==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="sub-contract-bill.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Cost Generation</a><br>
                                                            <a href="new-costing.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="sub-contract-bill.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(DISPATCH==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="dispatch.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Dispatch</a><br>
                                                            <a href="#"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="dispatch.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(OCR==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="ocr.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> OCR</a><br>
                                                            <a href="#"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="ocr.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                    
                                                    <!--<li><a href="in_house.php">Process Inward</a></li>-->
                                                    <!--<li><a href="in_house_daily_status.php">Daily Status</a></li>-->
                                                    <!--<li><a href="in_house_daily_status.php?dc">Create To DC</a></li>-->
                                                    
                                                    
                                                    <!--<li><a href="dev_sewingout.php?dc">Sewing Out</a></li>-->
                                                    <!--<li><a href="dev_checking.php?dc">Checking</a></li>-->
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-dark" type="button" data-toggle="collapse" data-target="#production_rep" aria-expanded="true" aria-controls="production_rep">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> <b style="font-size:22px;">Production Report's</b>
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production_rep" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row text-blue" style="font-weight:bold;">
                                                <?php if(RP_ORDER_WISE_PRODUCTION==1) { ?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="rep_order_wise.php">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Order Wise Production Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_PRODUCTION_REGISTER==1) { ?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="rep_dailyProduction.php">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Production Register</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_PROD_CUTTING_LEDGER==1) { ?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="rep_cutting_ledger.php">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Cutting Ledger</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_CT_BUNDLE_DETAILS==1) { ?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="rep_bundle_det.php">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Cutting Bundle Details</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_SEWING==1) { ?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="rep_sewing_summary.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Sewing Report - Summary</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_CHECKING==1) { ?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="rep_checking.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Checking Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_FINISHING==1) { ?>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="rep_finishing.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Finishing Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_LEFT_OVER==1) { ?>
                                                        
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="javascipt:void(0);">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Left Over Garment Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_BILL==1) { ?>
                                                        
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="javascipt:void(0);">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Bill Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_PAYMENT==1) { ?>
                                                        
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="javascipt:void(0);">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Payment Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(RP_PROD_STOCK==1) { ?>
                                                        
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="javascipt:void(0);">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50" style="padding-top: 15px;">Production Stock Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        $(".show_filter").click(function() {
            $("#report_filter-modal").modal('show');
        });
    </script>

</html>