<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


$sql_main = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_delivery WHERE id=" . $_GET['id']));
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Fabric Delivery Print</title>

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

<style>
    .table th {
        border-top: 0px solid #dee2e6 !important;
    }

    .table_border {
        border: 1px solid #dee2e6;
    }

    .bold {
        font-weight: 900;
    }

    p {
        margin: 0 0 5px;
    }
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="pd-20 card-box mb-30">
                    <div class="pd-20">
                        <h4 class="text-blue h4">Fabric Delivery Print </span>
                            <button class="btn btn-outline-primary" onclick="divToPrint('print_body')" style="float: right;"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                        </h4>
                    </div>
                    <?php
                    $qry = "SELECT * FROM  company  WHERE id = '" . $sql_main['created_unit'] . "'";
                    $query = mysqli_query($mysqli, $qry);
                    $sql = mysqli_fetch_array($query)
                        ?>
                    <div class="row" style="justify-content: center;" id="print_body">
                        <!-- <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12"> -->
                            <table class="table_border table-bordered table" style="max-width:1100px;">
                                <tr><td colspan="5" class="text-center"><h4 class="text-blue">Fabric Delivery Challan - Cutting</h4></td></tr>
                                <tr>
                                    <td colspan="3">
                                        <p class="bold text-blue h4"><?= $sql['company_name']; ?> </p>
                                        <p><?= $sql['address1']; ?> <br><?= $sql['address2']; ?> </p>
                                        <p>Phone : <?= $sql['mobile']; ?> <?= $sql['phone1'] ? '/' . $sql['phone1'] : ''; ?>
                                        </p>
                                        <p>GSTIN : <?= $sql['gst_no']; ?> </p>
                                        <p>Email : <?= $sql['mail_id']; ?> </p>
                                    </td>

                                    <td colspan="2">
                                        <p><b>DC No :</b> <?= $sql_main['dc_number']; ?></p>
                                        <p><b>DC Date :</b><?= date('d-m- Y', strtotime($sql_main['dc_date'])); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <?php
                                    $delivery_type = $sql_main['delivery_type'];
                                    if ($delivery_type == 'Unit') {
                                        $ss = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM company WHERE id = '" . $sql_main['delivery_to'] . "'"));

                                        $d_name = $ss['company_name'];
                                        $d_add = $ss['address1'] . '' . ($ss['address2'] ? ' <br> ' . $ss['address2'] : '');
                                        $d_mbl = $ss['mobile'] . '' . ($ss['phone1'] ? ' / ' . $ss['phone1'] : '');
                                        $d_gst = $ss['gst_no'];
                                        $d_mail = $ss['mail_id'];
                                    } else if ($delivery_type == 'Supplier') {
                                        $ss = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM supplier WHERE id = '" . $sql_main['delivery_to'] . "'"));

                                        $d_name = $ss['supplier_name'];
                                        $d_add = $ss['address1'] . '' . ($ss['address2'] ? ' <br> ' . $ss['address2'] : '');
                                        $d_mbl = $ss['mobile'] . '' . ($ss['phone1'] ? ' / ' . $ss['phone1'] : '');
                                        $d_gst = $ss['gst_no'];
                                        $d_mail = $ss['emailid'];
                                    }
                                    ?>
                                    <td colspan="5">
                                        <div style="background-color: blue;width: 100%;color:white;padding: 5px 0px 5px 2px;">
                                            Fabric Delivery To</div>
                                        <p> <?= $delivery_type; ?> : <?= $d_name; ?></p>
                                        <p> Address : <?= $d_add ? $d_add : '-'; ?></p>
                                        <p> Phone : <?= $d_mbl ? $d_mbl : '-'; ?></p>
                                        <p> Email : <?= $d_mail ? $d_mail : '-'; ?></p>
                                        <p> GSTIN : <?= $d_gst ? $d_gst : '-'; ?></p>
                                    </td>
                                </tr>


                                <tr class="background" style="color:white;">
                                    <th> Sno </th>
                                    <th> BO | Style </th>
                                    <th> Fabric Name</th>
                                    <th> Bag/ Roll</th>
                                    <th> Delivery Wt</th>
                                </tr>

                                <?php
                                if (isset($_GET['id'])) {
                                    $qy = mysqli_query($mysqli, "SELECT * FROM fabric_delivery_det WHERE fabric_delivery=" . $_GET['id']);
                                    $p = 1;
                                    while ($result = mysqli_fetch_array($qy)) {
                                        print '
                                                <tr>
                                                    <td>' . $p . '</td>
                                                    <td>' . sales_order_code($result['sales_order_id']) . ' | ' . sales_order_style($result['sales_order_detail_id']) . '</td>
                                                    <td>' . fabric_name($result['fabric']) . '</td>
                                                    <td>' . ($result['bag_roll'] ? $result['bag_roll'] : '-') . '</td>
                                                    <td>' . ($result['del_wt'] ? $result['del_wt'] : '-') . '</td>
                                                </tr>
                                            ';
                                        $p++;
                                    }
                                }

                                $num = mysqli_num_rows($qy);
                                ?>
                                <tr><td colspan="5" style="padding: <?= (20 - $num) ?>rem;"></td></tr>
                                <tr>
                                    <td colspan="5">
                                        <div class="row text-center">
                                            <div style="padding-top: 50px;width: 33%;"><p>Receiver Signature</p></div>
                                            <div style="padding-top: 50px;width: 33%;"><p>Verified By</p></div>
                                            <div style="padding-top: 50px;width: 33%;"><p>Authorised Signatory</p></div>
                                        </div>
                                    </td>
                                </tr>

                            </table>
                        <!-- </div> -->
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

</body>

</html>