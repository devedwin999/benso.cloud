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
    <title>BENSO - Dashboard Menus</title>

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
            padding-left: 10px;
            font-weight:bold;
        }
        
        .page-header a:nth-of-type(2) {
            margin-left: 20px;
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
                    
                    <?php //if(MOD_MERCHAND!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-dark h4">Dashboad</h4><br>
                        
                        <div class="accordion" id="accordionExample">
                            <?php //if(MOD_MERCHAND == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-dark" type="button" data-toggle="collapse" data-target="#production" aria-expanded="true" aria-controls="production">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> <b style="font-size:22px;">Dashboad List</b>
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row" style="">
                                                <?php if(BUD_VS_ACT_DASH==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="bud_vs_act.php">
                                                                <img src="src/icons/bud-vs-act.png" width="70">
                                                                <h4 class="text-black-50">Budget Vs Actual</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="dashboard.php">
                                                                <img src="src/icons/main-dash.png" width="70">
                                                                <h4 class="text-black-50">Production Dashboard</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                <?php if(WIP_DASH==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="wip_dash.php">
                                                                <img src="src/icons/wip-dash.png" width="70">
                                                                <h4 class="text-black-50">Production WIP</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(PROD_DASH==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="production_dash.php">
                                                                <img src="src/icons/prod-dash.png" width="70">
                                                                <h4 class="text-black-50">Line Dashboard</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(PAYMENT_DASH==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="payment_dash.php">
                                                                <img src="src/icons/pay-dash.png" width="70">
                                                                <h4 class="text-black-50">Payment Dashboard</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } if(MOD_TIME_MANAGEMENT==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="timeDashboard.php">
                                                                <img src="src/icons/time-dash.png" width="70">
                                                                <h4 class="text-black-50">Time Management</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php //} ?>
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