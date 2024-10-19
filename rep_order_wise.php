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
    <title>BENSO - Order Wise Production Report</title>

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
    
    .bold {
        font-weight: bold;
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
                    <?php page_spinner(); if(RP_PRODUCTION_REGISTER!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="row">
                            <div class="col-md-12 d-flex" style="justify-content: space-between ;">
                                <h5>Order & Size Report</h5>
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
                        <h4 class="text-blue h4">Order & Size Wise Production Report</h4>
                        
                        <div class="row" style="overflow-y:auto">
                            <div class="col-md-12" id="orderWiseBody"></div>
                        </div>
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

</body>


<script>
    $(".filtttr_search").click(function() {
        
        var order_bdg = [];
        $(".order_bdg:checked").map(function() { order_bdg.push($(this).val()); });
        
        $("#overlay").fadeIn(100);
        $.ajax({
            type : 'POST',
            url : 'ajax_search.php?orderWiseProductionReport=1&order_id=' +order_bdg,
            success : function(msg) {
                
                $("#orderWiseBody").html(msg);
                $("#report_filter-modal").modal('hide');
                $("#overlay").fadeOut(500);
            }
        });
    });
</script>

<script>
    function orderWiseProductionReport() {
        
        var boNmber = $("#boNmber").val();
        
        var styleNum = $("#styleNum").val();
        
        var prtN = $("#prtN").val();
        
        
        if(boNmber=="") {
            $("#boNmber").focus();
            message_noload('warning', 'Select BO Number!', 2000);
            return false;
        } else {
            
            $("#orderWiseBody").html('<i class="icon-copy fa fa-spinner" aria-hidden="true"></i> Loading..');
            
            $("#startFilter").val('Loading..');
            $("#startFilter").prop('disabled', true);
            
            setTimeout(function() {
                $.ajax({
                    type : 'POST',
                    url : 'ajax_search.php?orderWiseProductionReport=1&part=' + prtN + '&style=' + styleNum + '&order_id=' +boNmber,
                    success : function(msg) {
                        
                        $("#orderWiseBody").html(msg);
                    }
                })
                
                $("#startFilter").val('Filter');
                $("#startFilter").prop('disabled', false);
            }, 1000)
        }
    }
</script>

<script>
    $("#boNmber").change(function() {
        
        var id = $(this).val();
        
        $.ajax({
            type : 'POST',
            url : 'ajax_search.php?getMultiBoStyle=1&id=' + id,
            success : function(msg) {
                
                var json = $.parseJSON(msg);
                
                $("#styleNum").html(json.option);
            }
        })
    })
</script>

<script>
    $("#styleNum").change(function() {
        
        var id = $(this).val();
        
        $.ajax({
            type : 'POST',
            url : 'ajax_search.php?getMultiBoStylePart=1&id=' + id,
            success : function(msg) {
                
                var json = $.parseJSON(msg);
                
                $("#prtN").html(json.option);
            }
        })
    })
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

<script>
    $(document).ready(function() {
        $("#report_filter-modal").modal('show');
    });
</script>

</html>



























