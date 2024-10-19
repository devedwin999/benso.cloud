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
    <title>BENSO - Fabric Menus</title>

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
        }
        
        .padd a:nth-of-type(2) {
            margin-left: 20px;
        }
        
        .padd {
            padding: 10px !important;
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
                    
                    <?php if(MOD_FABRIC!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        
                        <h4 class="text-dark h4">Fabric</h4><br>
                        
                        <div class="accordion" id="accordionExample">
                            <?php if(MOD_FABRIC == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-dark" type="button" data-toggle="collapse" data-target="#production" aria-expanded="true" aria-controls="production">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> <b style="font-size:22px;">Fabric Entry's</b>
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row text-blue" style="font-weight:bold;">
                                                <?php if(FAB_PO==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="fab_po_list.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Purchase Order</a><br>
                                                            <a href="fab_po.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="fab_po_list.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                     <?php } if(FAB_PO_RECEIPT==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="fab_poReceipt_list.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Purchase Receipt</a><br>
                                                            <a href="fab_poReceipt.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="fab_poReceipt_list.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(FAB_PO_POUT==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="fab_po_pout_list.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Process Outward </a><br>
                                                            <a href="fab_po_pout.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="fab_po_pout_list.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                     <?php } if(FAB_PO_INW==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="fab_po_inw_list.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i>  Process Inward </a><br>
                                                            <a href="fab_po_inw.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="fab_po_inw_list.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                               <?php } if(FAB_TRANSFER==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="fab_transfer.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Transfer </a><br>
                                                            <a href="fab_transfer.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="fab_transfer.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(FAB_CANCEL==1) { ?>
                                                    <div class="col-md-3 d-none">
                                                        <div class="page-header">
                                                            <a href="po_dc_cancel.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> PO / DC Cancel </a><br>
                                                            <a href="po_dc_canceladd.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="po_dc_cancel.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                               <?php } if(FAB_STOCK_GROUP==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="stockgroup.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Stock Group </a><br>
                                                            <a href="stockgroupadd.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="stockgroup.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                               <?php } if(FAB_STOCK_OPENING==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="fab_opening_list.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Stock Opening </a><br>
                                                            <a href="fab_opening.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="fab_opening_list.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
                                                <?php } if(FAB_UNIT_DELIVERY==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="fabric_delivery.php" class="nw_a"><i class="icon-copy fa fa-tags text-success" aria-hidden="true"></i> Fabric Delivery </a><br>
                                                            <a href="add-fabdelivery.php"><small><i class="fa fa-plus"></i> New</small></a>&nbsp;&nbsp;&nbsp;
                                                            <a href="fabric_delivery.php"><small><i class="fa fa-list"></i> List</small></a>
                                                        </div>
                                                    </div>
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
                                                <i class="icon-copy dw dw-right-arrow-4"></i> <b style="font-size:22px;">Fabric Report's</b>
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production_rep" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row text-blue" style="font-weight:bold;">
                                                <?php if(FAB_REPORTS==1) { ?>
                                                    <div class="col-md-3">
                                                        <div class="page-header">
                                                            <a href="rep_stock_fabric.php" class="nw_a"><i class="icon-copy dw dw-file-117 text-warning"></i> Fabric Stock Report </a>
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