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
    <title>BENSO - Add Fabric Program</title>

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

    <style>
        .accordion span {
            color: #e83e8c;
            /* font-size: 20px; */
        }

        .card-body h5 {
            color: #1b00ff;
            text-decoration: underline;
            text-transform: uppercase;
        }
        
        
        .nav.vtabs.customtab .nav-item.show .nav-link, .nav.vtabs.customtab .nav-link.active {
            border-color: #d1d1d3;
        }
    </style>


    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Planning Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Planning Updated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php } else if ($_SESSION['msg'] == 'error') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Something Went Wrong!.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
        <?php }
        $_SESSION['msg'] = '';
        ?>

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <form method="POST">
                    <div class="card-box mb-30">
                        <?php //if(PROD_PLANNING!=1) { action_denied(); exit; } ?>
                        
                        <div class="pb-20">
                            <div class="accordion" id="accordionExamples" style="padding: 25px;">
                                <?php
                                $qry = "SELECT a.*, b.order_code FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id=b.id WHERE a.id='" . $id . "' ORDER BY a.id ASC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                $sql = mysqli_fetch_array($query);
                                    ?>
                                    <div class="card">
                                        <div class="card-header" id="heading<?= $x; ?>">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $x; ?>" aria-expanded="true" aria-controls="collapse<?= $x; ?>" style="font-size:25px">Production Dashboard On <span style="font-size:25px;color:green"><?= date('d-m-y'); ?></span></button>
                                            </h2>
                                        </div>

                                        <div id="collapse<?= $x; ?>" class="collapse show <?= ($x == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?= $x; ?>" data-parent="#accordionExample">
                                            <div class="card-body" style="overflow-y: auto;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered">
                                                            <thead style="background-color: #f7f7f7;">
                                                                <tr>
                                                                    <th>Line</th>
                                                                    <th>Planned Qty</th>
                                                                    <th>Input Qty</th>
                                                                    <th>Output Qty</th>
                                                                    <th>Output Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $x = 1;
                                                                    $ihb = mysqli_query($mysqli, "SELECT b.employee_name,a.assigned_emp FROM processing_list a LEFT JOIN employee_detail b ON a.assigned_emp=b.id WHERE a.type='sewing_input' AND a.created_date LIKE '%". date('Y-m-d') ."%'");
                                                                    while($roww = mysqli_fetch_array($ihb)) {
                                                                        
                                                                        $totSw_in = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a WHERE a.line='". $roww['assigned_emp'] ."' AND a.in_sewing='yes' AND a.in_sewing_date LIKE '%". date('Y-m-d') ."%'"));
                                                                        
                                                                        $totSw_out = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.pcs_per_bundle) as pcs_per_bundle FROM bundle_details a WHERE a.line='". $roww['assigned_emp'] ."' AND a.complete_sewing='yes' AND a.comp_sewing_date LIKE '%". date('Y-m-d') ."%'"));
                                                                ?>
                                                                <tr>
                                                                    <td><?= $roww['employee_name']; ?></td>
                                                                    <td>-</td>
                                                                    <td><?= $totSw_in['pcs_per_bundle']; ?></td>
                                                                    <td><?= $totSw_out['pcs_per_bundle']; ?></td>
                                                                </tr>
                                                                <?php $x++; } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                                

                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </form>
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