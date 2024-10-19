<?php
include("includes/connection.php");
include("includes/function.php");

$id = $_GET['id'];
$comp = 'View ';
$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $id));


?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Processing List
    </title>

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
    .ui-menu-item-wrapper {
        background-color: #eae8e8 !important;
        padding: 10px;
        width: 20% !important;
        border-bottom: 1px solid #c6c5c5;
    }
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="pd-20">
                        <?php
                        if (isset($_GET['qc'])) {
                            ?>
                            <a class="btn btn-outline-primary" href="quality-check.php" style="float: right;"><i
                                    class="fa fa-list" aria-hidden="true"></i> Quality Check List</a>
                        <?php } else { ?>
                            <a class="btn btn-outline-primary" href="inward-list.php" style="float: right;"><i
                                    class="fa fa-list" aria-hidden="true"></i> Inward List</a>
                        <?php } ?>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                <?= $comp; ?>Inward Process
                            </h4>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">

                        <input type="hidden" value="<?= $sql['id']; ?>" name="processing_list" id="processing_list">

                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Boundle QR</th>
                                        <th>Boundle No</th>
                                        <th>Boundle Qty</th>
                                        <th>So.No</th>
                                        <th>Ref Code</th>
                                        <th>Style No</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody"></tbody>
                            </table>
                        </div>
                    </form>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>

    <script>
        function showPices(id) {
            if ($("#show_pieces" + id).val() == "") {
                $("#trcBoxx" + id).show();
                $("#show_pieces" + id).val('1');
                $("#iconn" + id).removeClass('ion-chevron-right').addClass('ion-chevron-down');
            } else {
                $("#trcBoxx" + id).hide();
                $("#show_pieces" + id).val('');
                $("#iconn" + id).removeClass('ion-chevron-down').addClass('ion-chevron-right');
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            var id = $("#processing_list").val();
            processing_list(id);
        })

        $("#processing_list").change(function () {
            var id = $(this).val();
            processing_list(id);
        })

        function processing_list(id) {

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getInward=1&id=' + id + '&readonly=true',

                success: function (msg) {
                    $("#tableBody").html(msg);
                }
            })

        }
    </script>

</body>

</html>