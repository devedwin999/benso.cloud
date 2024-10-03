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
    <title>BENSO GARMENTING - Accounts Menus</title>

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
                    
                    <?php if(MOD_ACCOUNTS!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        
                        <h4 class="text-dark h4">Accounts</h4><br>
                        
                        <div class="accordion" id="accordionExample">
                            <?php if(MOD_ACCOUNTS == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-dark" type="button" data-toggle="collapse" data-target="#production" aria-expanded="true" aria-controls="production">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> <b style="font-size:22px;">Accounts Entry's</b>
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row text-blue" style="font-weight:bold;">
                                                 <?php if(BILL_RECEIPT==1) { ?>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="bill_receipt.php" class="d-block">
                                                                    <!-- <img src="src/icons/receipt.png" width="70" alt="Bill Receipt"> -->
                                                                    <h5 class="text-black-50 mt-2">👉 Bill Receipt</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="bill_receipt.php" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php } if(BILL_PASSING==1) { ?>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="javascript: void(0);" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Fabric Bill Passing</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                                                                    
                                                <?php } ?>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="javascript: void(0);" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Store Bill Passing</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="bill_passing.php" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Production Bill Passing</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="bill_passing-add.php" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="bill_passing.php" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="javascript: void(0);" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Maintanance Bill Passing</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } if(BILL_APPROVAL==1) { ?>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="bill_approval.php" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Bill Approval</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="bill_approval.php" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="payment.php" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Payment Outward</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="payment.php" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="javascript: void(0);" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Payment Inward</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <div class="mb-3">
                                                                <a href="javascript: void(0);" class="d-block">
                                                                    <h5 class="text-black-50 mt-2">👉 Seconds Sales</h5>
                                                                </a>
                                                            </div>
                                                            
                                                            <div class="d-flex justify-content-center">
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/add.png" width="30" alt="Add">
                                                                </a>
                                                                <a href="javascript: void(0);" class="mx-2">
                                                                    <img src="src/icons/list.png" width="30" alt="List">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>                                                  
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-dark" type="button" data-toggle="collapse" data-target="#production_rep" aria-expanded="true" aria-controls="production_rep">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> <b style="font-size:22px;">Accounts Report's</b>
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production_rep" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row text-blue" style="font-weight:bold;">
                                                <?php //if(e==1) { ?>
                                                    <!-- <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="rep_order_wise.php" class="nw_a"><i class="icon-copy dw dw-file-117 text-warning"></i> Accounts Report</a>
                                                        </div>
                                                    </div> -->

                                                    <div class="col-md-3">
                                                        <div class="page-header text-center">
                                                            <a href="dashboard.php">
                                                                <img src="src/icons/reports.png" width="70">
                                                                <h4 class="text-black-50">Accounts Report</h4>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php //}  ?>
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