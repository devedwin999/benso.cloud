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
    <title>BENSO GARMENTING - Device Checking Output</title>

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
                    
                    <?php if(APP_LOG!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">Checking Output</h4>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Scanned Date</th>
                                    <th>BO</th>
                                    <th>Style</th>
                                    <th>Part</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Bundle No</th>
                                    <th>Scanned Device</th>
                                    <th>Scanning Type</th>
                                    <th>Scanned Qty</th>
                                    <th>Type</th>
                                    <th>Pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.bundle_number ";
                                $qry .= " FROM orbidx_checking a ";
                                $qry .= " LEFT JOIN bundle_details b ON a.bundle_details_id = b.id ";
                                $qry .= " ORDER BY a.id DESC ";
                                
                                
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    switch ($sql['mode']) {
                                        case 'PASSED':
                                            $id = 1;
                                            break;
                                        case 'REWORK':
                                            $id = 2;
                                            break;
                                        case 'FAILED':
                                            $id = 3;
                                            break;
                                        default:
                                            // Default case, if $sql['mode'] doesn't match any of the above cases
                                            // You might want to handle this differently based on your requirements
                                            // For example, setting $id to a default value or showing an error message
                                            break;
                                    }
                                    $fa = mysqli_fetch_array(mysqli_query($mysqli, "SELECT checking_color, checking_name FROM mas_checking WHERE id = ". $id));
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= date('d-M, Y', strtotime($sql['date'])); ?></td>
                                        <td><?= sales_order_code($sql['order_id']); ?></td>
                                        <td><?= sales_order_style($sql['style_id']); ?></td>
                                        <td><?= part_name($sql['part']); ?></td>
                                        <td><?= color_name($sql['color']); ?></td>
                                        <td><?= variation_value($sql['variation_value']); ?></td>
                                        <td><?= $sql['bundle_number']; ?></td>
                                        <td><?= $sql['device_name']; ?></td>
                                        <td><?= ucfirst($sql['scan_type']); ?></td>
                                        <td><?= $sql['scanned_count']; ?></td>
                                        <td style="color:<?= $fa['checking_color']; ?>;"><?= $fa['checking_name']; ?></td>
                                        <td><?= $sql['piece_id']; ?></td>
                                    </tr>
                                    <?php $x++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                location.reload();
            }, 10000);
        });
    </script>
    

</html>