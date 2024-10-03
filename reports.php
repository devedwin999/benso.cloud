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
    <title>BENSO GARMENTING - Reports</title>

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
            color: #32d1ac !important;
        }
        
        .padd {
            padding: 10px !important;
        }
        
        input[type="checkbox"] {
            height:20px !important;
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
                    
                    <?php if(MOD_REPORTS!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        
                        <h4 class="text-blue h4">Reports
                            <p class="mb-30 text-danger"><i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info" style="font-size: 15px;"></i> Click on the Status To change</p>
                        </h4>


                        <div class="accordion" id="accordionExample" style="padding: 25px;">
                            <?php if(PROD_REPORTS == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#production_rep" aria-expanded="true" aria-controls="production_rep">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Production Reports
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="production_rep" class="collapse show" aria-labelledby="heading" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row">
                                                <?php if(RP_ORDER_WISE_PRODUCTION==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="rep_order_wise.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning text-warning"></span> Order Wise Production Report</a>
                                                    </div>
                                                <?php } if(RP_PRODUCTION_REGISTER==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="rep_dailyProduction.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Production Register</a>
                                                    </div>
                                                <?php //} if(RP_CT_LAY_DETAILS==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="#" class="nw_a show_filter"><span class="icon-copy ti-hand-point-right text-warning" ></span> Cutting Ledger</a>
                                                    </div>
                                                <?php } if(RP_CT_BUNDLE_DETAILS==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="#" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Cutting Bundle Details</a>
                                                    </div>
                                                <?php } if(RP_SEWING==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="rep_sewing.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Sewing Report</a>
                                                    </div>
                                                <?php } if(RP_CHECKING==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="rep_checking.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Checking Report</a>
                                                    </div>
                                                <?php } if(RP_FINISHING==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="rep_finishing.php?fdt=<?= date('Y-m-01'); ?>&tdt=<?= date('Y-m-t'); ?>" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Finishing Report</a>
                                                    </div>
                                                <?php } if(RP_LEFT_OVER==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="#" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Left Over Garment Report</a>
                                                    </div>
                                                <?php } if(RP_BILL==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="#" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Bill Report</a>
                                                    </div>
                                                <?php } if(RP_PAYMENT==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="#" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Payment Report</a>
                                                    </div>
                                                <?php } if(RP_PROD_STOCK==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="rep_stock_production.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" ></span> Production Stock Report</a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php } if(FAB_REPORTS == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading1">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#fabric_rep" aria-expanded="true" aria-controls="fabric_rep">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> Fabric Reports
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div id="fabric_rep" class="collapse show" aria-labelledby="heading1" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row">
                                                <div class="col-md-3 padd">
                                                    <a href="rep_stock_fabric.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning"></span> Fabric Stock Report</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    
                            <?php } if(HR_REPORTS == 1) { ?>
                                <div class="card">
                                    <div class="card-header" id="heading2">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#f" aria-expanded="true" aria-controls="f">
                                                <i class="icon-copy dw dw-right-arrow-4"></i> HR Reports
                                            </button>
                                        </h2>
                                    </div>
                                    
                                    <div class="collapse show" aria-labelledby="heading2" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y:auto;">
                                            <div class="row">
                                                <?php if(RP_CHECK_INOUT==1) { ?>
                                                    <div class="col-md-3 padd">
                                                        <a href="check_in_out.php" class="nw_a"><span class="icon-copy ti-hand-point-right text-warning" style="margin-left: 1%;"></span> Check In / Out Reports</a>  
                                                    </div>                          
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    
                </div>
                
                <div class="modal fade" id="report_filter-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width:1000px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myLargeModalLabel">Filter Options</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            
                            <div class="modal-body" style="max-height:700px;">
                                <form method="post" id="task_addForm" autocomplete="off" enctype="multipart/form-data">
                                    <div class="row" style="overflow-y:auto;">
                                        
                                        <table class="table table-bordered">
                                            <tr>
                                                <td style="min-width:250px;">
                                                    <label><b>Type</b></label>
                                                    <select name="" id="" class="custom-select2 form-control" style="width:100%">
                                                        <?= select_dropdown('selection_type', array('id', 'type_name'), 'type_name ASC', $sql['selection_type'], '', '`'); ?>
                                                    </select>
                                                </td>
                                                <td style="min-width:250px;" rowspan="2">
                                                    <input type="text" placeholder="Search Buyer" id="buyer_search" class="form-control">
                                                    <br>
                                                    <lable><b>Buyer Select</b></lable>
                                                    <div style="height:210px; overflow-y:auto;">
                                                        <table class="table hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Buyer</th>
                                                                    <th>Select </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="buyer_tbody">
                                                                <?php                                    
                                                                $qry = "SELECT * FROM sales_order  where is_dispatch IS NULL group by brand ORDER BY id DESC";
                                                                $query = mysqli_query($mysqli, $qry);
                                                                $x = 1;
                                                                while ($sql1 = mysqli_fetch_array($query)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= brand_name($sql1['brand']); ?></td>
                                                                        <td><input type="checkbox"></td>
                                                                    </tr>
                                                                    <?php $x++;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                                <td style="min-width:250px;" rowspan="4">
                                                    <input type="text" placeholder="Search BO" id="bo_search" class="form-control">
                                                    <br>
                                                    <div style="height:520px;overflow-y:auto;">
                                                        <table class="table hover">
                                                            <thead>
                                                                <lable><b>Order No</b></lable>
                                                                <tr>
                                                                    <th>BO No | Style</th>
                                                                    <th>Select </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="bo_tbody">
                                                                <?php
                                                                $qry = "SELECT a.id, a.style_no, b.order_code FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id= b.id  where b.is_dispatch IS NULL ORDER BY b.id DESC";
                                                                $query = mysqli_query($mysqli, $qry);
                                                                $x = 1;
                                                                while ($sql2 = mysqli_fetch_array($query)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $sql2['order_code'].' | '.$sql2['style_no']; ?></td>
                                                                        <td><input type="checkbox" class="order_id" value="<?= $sql2['id']; ?>"></td>
                                                                    </tr>
                                                                    <?php $x++;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    <a class="btn btn-outline-secondary" data-dismiss="modal" style="float:left;">Close</a>
                                                    <a class="btn btn-outline-primary generate_print" style="float:right;"><i class="fa fa-print"></i> Preview</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">
                                                    <label><b>Date Range</b></label> <br>
                                                    <label class="col-form-label">From Date <span class="text-danger"></span></label>
                                                    <div class="form-group">
                                                        <input type="date" name="from_date" class="form-control"
                                                            value="<?= date('Y-m-d'); ?>">
                                                    </div>
                                                    <label class="col-form-label">To Date <span class="text-danger"></span></label>
                                                    <div class="form-group">
                                                        <input type="date" name="to_date" class="form-control"
                                                            value="<?= date('Y-m-d'); ?>">
                                                    </div>    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">
                                                    <input type="text" placeholder="Search Line/ Employee" id="emp_search" class="form-control">
                                                    <br>
                                                    <lable><b>Line/ Employee</b></lable>
                                                    <div style="height:210px; overflow-y:auto;">
                                                        <table class="table hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Select</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="emp_tbody">
                                                                <?php                                    
                                                                $qry = "SELECT * FROM sales_order  where is_dispatch IS NULL group by brand ORDER BY id DESC";
                                                                $query = mysqli_query($mysqli, $qry);
                                                                $x = 1;
                                                                while ($sql = mysqli_fetch_array($query)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= brand_name($sql['brand']); ?></td>
                                                                        <td><input type="checkbox"></td>
                                                                    </tr>
                                                                    <?php $x++;
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label><b>Order Status</b></label><br>
                                                        <input class="cutting_model" type="radio" id="running" name="type_completed_report" value="Running" checked>
                                                        <label for="running">Running</label><br>
                                                        <input class="cutting_model"  type="radio" id="completed" name="type_completed_report" value="Completed">
                                                        <label for="completed">Completed</label><br>
                                                        <input class="cutting_model" type="radio" id="all" name="type_completed_report" value="all">
                                                        <label for="all">All</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            include('includes/footer.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        
        $(".generate_print").click(function() {
            
            var value = null;
            $(".order_id").each(function() {
                if ($(this).is(':checked')) {
                    value = $(this).val();
                    return false;
                }
            });
            
            if (value !== null) {
                window.open('rep_cutting_ledger.php?id=' + value, '_blank');
            }
        });

    </script>
    
    <script>
        document.getElementById('bo_search').addEventListener('input', function() {
            var searchText = this.value.toLowerCase();
            var rows = document.querySelectorAll('#bo_tbody tr');
            
            rows.forEach(function(row) {
                var name = row.querySelector('td').textContent.toLowerCase();
                if (name.includes(searchText)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
    
    <script>
        document.getElementById('emp_search').addEventListener('input', function() {
            var searchText = this.value.toLowerCase();
            var rows = document.querySelectorAll('#emp_tbody tr');
            
            rows.forEach(function(row) {
                var name = row.querySelector('td').textContent.toLowerCase();
                if (name.includes(searchText)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        document.getElementById('buyer_search').addEventListener('input', function() {
            var searchText = this.value.toLowerCase();
            var rows = document.querySelectorAll('#buyer_tbody tr');
            
            rows.forEach(function(row) {
                var name = row.querySelector('td').textContent.toLowerCase();
                if (name.includes(searchText)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // document.getElementById('buyer_search').addEventListener('input', function() {
        //     var searchText = this.value.toLowerCase();
        //     var rows = document.querySelectorAll('#buyer_tbody tr');
        //     var noResultsMessage = document.getElementById('noResults');
        
        //     var anyMatchFound = false;
        
        //     rows.forEach(function(row) {
        //         var name = row.querySelector('td').textContent.toLowerCase();
        //         if (name.includes(searchText)) {
        //             row.style.display = 'table-row';
        //             anyMatchFound = true;
        //         } else {
        //             row.style.display = 'none';
        //         }
        //     });
        
        //     if (anyMatchFound) {
        //         noResultsMessage.style.display = 'none';
        //     } else {
        //         noResultsMessage.style.display = 'block';
        //     }
        // });
    </script>
    
    <script>
        $(".show_filter").click(function() {
            $("#report_filter-modal").modal('show');
        });
    </script>

</html>