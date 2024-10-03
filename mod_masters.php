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
    <title>BENSO GARMENTING - Master</title>

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
            color: #28a745 !important;
        }
        
        .padd {
            padding: 10px !important;
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
                    
                    <?php if(MOD_MASTER!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        
                        <h4 class="text-blue h4">Masters
                            <p class="mb-30 text-danger"><i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info" style="font-size: 15px;"></i> Click on the Status To change</p>
                        </h4>


                        <div class="accordion" id="accordionExample" style="padding: 25px;">
                            <?php if(ADMIN_MASTER == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#production_rep" aria-expanded="true" aria-controls="production_rep">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Admin Master
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production_rep" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row" style="font-weight:bold;font-style: italic;">
                                                <?php if(MAS_COMPANY==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="view-company.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning text-warning"></span> Company</a>
                                                    </div>
                                                <?php } if(MAS_BRAND==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="brand.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Brand </a>
                                                    </div>
                                                <?php } if(MAS_CUSTOMER==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="customer.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Customer </a>
                                                    </div>
                                                <?php } if(MAS_SUPPLIER==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="supplier.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Supplier </a>
                                                    </div>
                                                <?php } if(MAS_UNIT==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="unit.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Unit</a>
                                                    </div>
                                                <?php } if(MAS_DEPARTMENT==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="department.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Department</a>
                                                    </div>
                                                <?php } if(MAS_PROCESS==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="process.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Process</a>
                                                    </div>
                                                <?php } if(MAS_SUBPROCESS==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="sub_process.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Sub Process</a>
                                                    </div>
                                                <?php } if(MAS_SEL_TYPE==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="selection_type.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Selection Type </a>
                                                    </div>
                                                <?php } if(MAS_TASK==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_task.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Task</a>
                                                    </div>
                                                <?php } if(MAS_DESIGNATION==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_designation.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Designation</a>
                                                    </div>
                                                <?php } if(MAS_STYLE==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="_style.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Style</a>
                                                    </div>
                                                <?php } if(MAS_BANK==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_bank.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Bank</a>
                                                    </div>
                                                <?php } if(MAS_NOTES==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_notes.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Notes</a>
                                                    </div>
                                                <?php } if(MAS_STG_ITEM==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_stockgroup_item.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Stock Group Item</a>
                                                    </div>
                                                <?php } if(MAS_UOM==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_uom.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> UOM</a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php } if(MERCH_MASTER == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading1">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#merch_master" aria-expanded="true" aria-controls="merch_master">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Merch Masters
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="merch_master" class="collapse show" aria-labelledby="heading1" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row" style="font-weight:bold;font-style: italic;">
                                                <?php if(MAS_MERCHAND==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="merchandiser.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Merchandiser</a>
                                                    </div>
                                                <?php } if(MAS_SIZER==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="variation.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Size Range</a>
                                                    </div>
                                                <?php } if(MAS_COLOR==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="color.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Color</a>
                                                    </div>
                                                <?php } if(MAS_PART==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="part.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Part</a>
                                                    </div>
                                                <?php } if(MAS_CATEGORY==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="view-category.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Category</a>
                                                    </div>
                                                <?php } if(MAS_ITEM==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="item.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Item</a>
                                                    </div>
                                                <?php } if(MAS_TAXMAIN==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="tax_main.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Tax Main</a>
                                                    </div>
                                                <?php } if(MAS_TAXSUB==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="tax_sub.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Tax Sub</a>
                                                    </div>
                                                <?php } if(MAS_EXP_MAIN==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="expense_main.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Expense Main</a>
                                                    </div>
                                                <?php } if(MAS_EXP_SUB==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="expense_sub.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Expense Sub</a>
                                                    </div>
                                                <?php } if(MAS_MACHINE==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="machine.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Machine</a>
                                                    </div>
                                                <?php } if(MAS_APPROVAL==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_approval.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Approval</a>
                                                    </div>
                                                <?php } if(MAS_COMPONENT==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_component.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Component</a>
                                                    </div>
                                                <?php }  if(MAS_CURRENCY==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_currency.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Currency</a>
                                                    </div>
                                                <?php }  if(MAS_PACK==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_pack.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Pack Type</a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            <?php } if(FAB_MASTER == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading2">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#fabric_rep" aria-expanded="true" aria-controls="fabric_rep">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Fabric Masters
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="fabric_rep" class="collapse show" aria-labelledby="heading2" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row" style="font-weight:bold;font-style: italic;">
                                                <?php if(MAS_FABRIC==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="fabric.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Fabric</a>
                                                    </div>
                                                <?php } if(MAS_YARN==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_yarn.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Yarn</a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                 
                            <?php } if(STORE_MASTER == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading3">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#store_master" aria-expanded="true" aria-controls="store_master">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Store Masters
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="store_master" class="collapse show" aria-labelledby="heading3" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row" style="font-weight:bold;font-style: italic;">
                                                <?php if(MAS_ACCESSORIES_TYPE==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_access_type.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Accessories Type</a>
                                                    </div>
                                                <?php } if(MAS_ACCESSORIES==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_accessories.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Accessories</a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            <?php } if(PROD_MASTER == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading4">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#production_master" aria-expanded="true" aria-controls="production_master">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Production Masters
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production_master" class="collapse show" aria-labelledby="heading4" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row" style="font-weight:bold;font-style: italic;">
                                                <?php if(MAS_CHECKING==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_checking.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Checking Types</a>
                                                    </div>
                                                <?php } if(MAS_DEFECT==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_defect.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Defect</a>
                                                    </div>
                                                <?php }if(MAS_LINE==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="mas_line.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Line</a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            <?php } if(DEVELOPING == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading5">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#accounts_master" aria-expanded="true" aria-controls="accounts_master">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Accounts Masters
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="accounts_master" class="collapse show" aria-labelledby="heading5" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row" style="font-weight:bold;font-style: italic;">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>   
                            
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

</html>