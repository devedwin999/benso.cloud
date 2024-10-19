<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'brand_name' => filter_var($_POST['edit_brand_name'], FILTER_SANITIZE_STRING),
        'brand_code' => filter_var($_POST['edit_brand_code'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Update('brand', $data, " WHERE id = '" . $_REQUEST['edit_brand_id'] . "'");

    $_SESSION['msg'] = "updated";

    header("Location:brand.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Bundle Tracking</title>

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

<style>
    input {
        width: 10px !important;
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
                
                    <?php if(BUNDLE_TRACK!=1) { action_denied(); exit; } ?>
                
                    <div class="pd-20">
                        <h4 class="text-blue h4">Bundle Tracker</h4>
                    </div>
                    <div class="pd-20">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="fieldrequired">Bundle QR <span class="text-danger">*</span></label>
                                
                                <input type="text" class="form-control" name="" id="bundleQR" placeholder="Enter Bundle QR" style="width: 100% !important;">
                            </div>
                            <div class="col-md-3">
                                <label for="">&nbsp;</label>
                                <br>
                                <a class="btn btn-outline-primary" onclick="get_bundleTrack()">Track</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pd-20" id="divQRreff"></div>
                </div>
            </div>
            <?php
            include('includes/footer.php');
            ?>
        </div>
    </div>

    <?php include('includes/end_scripts.php'); ?>

    <script>

        function get_bundleTrack() {
            
            $("#divQRreff").html('<p class="text-center">Data Fetching..</p>');

            var qr = $("#bundleQR").val();

            if(qr=="")
            {
                $("#bundleQR").focus();
                $("#divQRreff").html('');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?get_bundleTrack=1&qr=' + qr,

                success: function (msg) {
                    
                    var json = $.parseJSON(msg);
                    
                    if(json.err=="yes") {
                        message_title('info', ''+ json.title + '', ''+ json.message + '');
                        $("#divQRreff").html('<p class="text-center">Enter Valid QR.</p>');
                        return false;
                        
                    } else if(json.err=='no') {
                        
                        $("#divQRreff").html(json.content);
                    }
                }
            })
        }
    </script>

</html>
































