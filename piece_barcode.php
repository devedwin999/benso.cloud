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
    <title>BENSO - Piece Barcode Print</title>

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
                    
                    <?php if(CUTTING_QR_GENERATE!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">Piece Barcode Print</h4>
                        <input type="hidden" value="<?= $_GET['dta']; ?>" id="url_data">
                        
                        <!--<a class="btn btn-outline-primary printBtn d-none" style="float:right;"><i class="fa fa-print"></i> Print</a>-->
                        <a class="btn btn-light float-right" onclick="divToPrint1('divToPrint')"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                        <small>BO | Lay Number | Size | Bundle No | Piece No</small>
                    </div>
                    <div class="pb-20">
                        
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 text-center" id="content_body" style="min-height: 500px;">
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        function divToPrint1(id) {
        
            var css= '';
            var printContent = document.getElementById(id);
        
            var WinPrint = window.open('', '', 'width=1500,height=1000');
            WinPrint.document.write(printContent.innerHTML);
            WinPrint.document.head.innerHTML = css;
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }
    </script>
    
    <script>
        $(document).ready(function() {
                
            var data = {
                data : $("#url_data").val(),
            }
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?total_pieces_toprint=1',
                data: data,
                
                success: function(msg) {
                    
                    var json = $.parseJSON(msg);
                    var btn = '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...</button>';
                    $("#content_body").html('<div class="col-md-12 text-center"><h5 style="margin-top: 100px">Barcode Generating..</h5><p>Generating '+ json.pcs +' Barcodes. It will take some times.</p> '+ btn +'</div>');
                    
                    $.ajax({
                        type: 'POST',
                        url: 'pieceqr_new.php',
                        data: data,
                        // url: 'qrpiece-print-ajax.php?data=' + data,
                        
                        success: function(msg) {
                            // $(".printBtn").removeClass('d-none');
                            $("#content_body").html(msg);
                        }
                    });
                }
            });
        });
    </script>

</html>