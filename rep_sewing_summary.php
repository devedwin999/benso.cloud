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
    <title>BENSO - Sewing Report - Summary</title>

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

    <!-- sweetalert -->
    <link rel="stylesheet" type="text/css" href="src/plugins/sweetalert2/sweetalert2.css">

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
</head>

<style>
    .dhide {
        display:none;
    }
</style>

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                
                <div class="card-box mb-30">
                    <?php // if(RP_PRODUCTION_REGISTER!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="row">
                            <div class="col-md-12 d-flex" style="justify-content: space-between ;">
                                <h5>Sewing Report</h5>
                                <a class="btn btn-outline-primary show_filter"><i class="icon-copy fi-filter"></i> Filter</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-box mb-5" style="float:right">
                    <i class="fa fa-print" style="padding: 15px;" onclick="divToPrint()"></i>
                </div>
                
                <!-- Export Datatable start -->
                <div class="card-box mb-30" id="divToPrint">
                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Sewing Report - Summary</h4>
                    </div>
                    <div class="pb-20" style="overflow-y: auto;">
                        
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Date</th>
                                    <th>Line</th>
                                    <th>No of Employee</th>
                                    <th>BO</th>
                                    <th>Style</th>
                                    <th>Combo</th>
                                    <th>Part | Color</th>
                                    <th>Process</th>
                                    <th>Order Qty</th>
                                    <th>Plan Qty</th>
                                    <th>Input Qty</th>
                                    <th>Output Qty</th>
                                    <th>Finished %</th>
                                </tr>
                            </thead>
                            <tbody id="report_tbody">
                                <tr>
                                    <td class="text-center" colspan="24">-- Nothing Found --</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
                $modals = ["report_filter-modal"];
                include('includes/footer.php');
                include('modals.php');
            ?>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>
    

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
            
            order_type: $("input[name='type_completed_report']:checked").val(),
            
            inp_date_bdg: $(".inp_date_bdg").is(':checked'),
            inp_date_start: $(".inp_date_start").val(),
            inp_date_end: $(".inp_date_end").val(),
            
            out_date_bdg: $(".out_date_bdg").is(':checked'),
            out_date_start: $(".out_date_start").val(),
            out_date_end: $(".out_date_end").val(),
            
            output_status: $("input[name='output_status']:checked").val(),
        };
        
        $.post('ajax_reports.php?sewing_out_summary', data, function(msg) {
            
            var j = $.parseJSON(msg);
            $("#report_tbody").html(j.table_tr);
            $("#report_filter-modal").modal('hide');
            $("#overlay").fadeOut(500);
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#report_filter-modal").modal('show');
    });
</script>

<script type="text/javascript">
    function divToPrint() {
        var divToPrint = document.getElementById('divToPrint');
        var popupWin = window.open();
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
    }
</script>
</body>

</html>