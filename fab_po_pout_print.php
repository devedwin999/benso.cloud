<?php
include ("includes/connection.php");
include ("includes/function.php");

include ("includes/perm.php");
?>
<!DOCTYPE html>
<html>

<head>

    <style>
        .table_border {
            border: 1px solid #000000;
        }
    </style>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - FABRIC DC Print
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

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">


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

    <?php include ('includes/header.php'); ?>

    <?php include ('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="pd-20 card-box mb-30">
                    <?php //if(BUDGET_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Fabric Print </span>
                            <a class="btn btn-outline-primary" href="fab_po_pout_list.php" style="float: right;"><i
                                    class="fa fa-list" aria-hidden="true"></i>Process DC List</a>
                            <a class="btn btn-outline-primary" href="#" style="float: right;margin-right: 8%;"><i
                                    class="fa fa-print" aria-hidden="true"></i>Print</a>
                        </h4>
                    </div>

                    <div class="row">
                        <table class="table_border" width="100%">
                            <tr>
                                <td>
                                    <div> DC NO / DC DATE </div>
                                </td>
                                <td></td>
                                <td>
                                    <div> Process Name </div>
                                </td>
                                <td></td>
                                <td>
                                    <div> Delivery Date </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="table_border" colspan="3">
                                    <div>Company Name </div>
                                    <div>Address</div>
                                    <div>Phone No</div>
                                    <div>Email</div>
                                    <div>GSTIN</div>
                                </td>
                                <td class="table_border" colspan="3">
                                    <img src="vendors/images/favicon-32x32.png" width="50" height="60">
                                </td>
                            </tr>
                            <tr>
                                <td class="table_border" colspan="3">
                                    <div>Delivery Challan For</div>

                                </td>
                                <td class="table_border" colspan="3">
                                    <div>Shipping To </div>

                                </td>
                            </tr>
                            <tr>
                                <td class="table_border" colspan="3">
                                    <div>Party Name:</div>
                                    <div>Address: </div>
                                    <div>Phone No.:</div>
                                    <div>Email:</div>
                                    <div>GSTIN:</div>

                                </td>
                                <td class="table_border" colspan="3">
                                    <div>Shipping Name:</div>
                                    <div>Address: </div>
                                    <div>Phone No.:</div>
                                    <div>Email:</div>
                                    <div>GSTIN:</div>
                                </td>
                            </tr>
                            <tr>
                                <th>Sno </th>
                                <th>Material Name </th>
                                <th>DIA </th>
                                <th>NO OF ROLL /BAG </th>
                                <th>QTY / WT </th>
                            </tr>
                            <tr class="table_border">
                                <td>1</td>
                                <td>Item Name 1</td>
                                <td>123</td>
                                <td>10</td>
                                <td>10</td>

                            </tr>
                            <tr class="table_border">
                                <td>2</td>
                                <td>Item Name 1</td>
                                <td>123</td>
                                <td>10</td>
                                <td>10</td>

                            </tr>
                            <tr class="table_border">
                                <td>3</td>
                                <td>Item Name 1</td>
                                <td>123</td>
                                <td>10</td>
                                <td>10</td>
                            </tr>
                            <tr class="table_border">
                                <td></td>
                                <td><b>Total</b></td>
                                <td></td>
                                <td><b>30</b></td>
                                <td></td>
                            </tr>
                            <tr class="table_border" colspan="4">
                                <td>PROCESS PLAN FOR <br><br><br><br><br></td>
                            </tr>
                            <tr>
                                <td><br><br><br><br><br>Prepared By</td>
                                <td><br><br><br><br><br>Verifed By</td>
                                <td><br><br><br><br><br>Approved By</td>
                                <td><br><br><br><br><br>Received By</td>
                            </tr>
                            <table>
                    </div>
                </div>
            </div>

            <?php include ('includes/footer.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include ('includes/end_scripts.php'); ?>



</body>

</html>