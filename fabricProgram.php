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
    <title>BENSO - Fabric Program</title>

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
        <?php
        if ($_SESSION['msg'] == 'added') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Sales Order Saved.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Sales Order Updated.
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

                <div class="card-box mb-30">
                    
                    <?php page_spinner(); if(FABRIC_PROG!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">Fabric Program
                            <?php if(SALES_ORDER_ADD==1) { ?>
                                <!--<a class="btn btn-outline-primary showmodal" href="sales_order.php" style="float: right;">+ Add an Orders</a>-->
                            <?php } ?>
                        </h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Style Image</th>
                                    <th>BO No</th>
                                    <th>Style</th>
                                    <th>Type</th>
                                    <th>Brand </th>
                                    <th>Po Qty </th>
                                    <th>Delivery Date</th>
                                    <th>Program Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, c.brand_name, b.order_code, d.type_name FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id LEFT JOIN brand c ON b.brand = c.id LEFT JOIN selection_type d ON b.type = d.id WHERE b.is_approved='approved' ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                
                                if (!$query) {
                                    print("Error : " . mysqli_error($mysqli));
                                }
                                
                                $x = 1;
                                $po = '';
                                while ($sql = mysqli_fetch_array($query)) {
                                    $mlp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(total_qty) as qttt FROM sales_order_detalis WHERE sales_order_id=" . $sql['id']));
                                    
                                    $prog = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_program WHERE sales_order_detalis_id=" . $sql['id']));
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x; ?>
                                        </td>
                                        <td><?= viewImage($sql['item_image'], 50); ?></td>
                                        <td><?= $sql['order_code']; ?></td>
                                        <td><?= $sql['style_no']; ?></td>
                                        <td><?= $sql['type_name']; ?></td>
                                        <td><?= $sql['brand_name']; ?></td>
                                        <td>
                                            <?= number_format($mlp['qttt'], 0, '.', ','); ?>
                                        </td>
                                        <td>
                                            <?= date('d M, Y', strtotime($sql['delivery_date'])); ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($prog>0) {
                                                print '<span class="border border-success rounded text-success">Created</span>';
                                            } else {
                                                print '<span class="border border-danger rounded text-danger">Not Created</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(FABRIC_PROG_ADD==1 && $prog==0) { ?>
                                                        <a class="dropdown-item" href="add-fabProgram.php?id=<?= $sql['id'] ?>"><i class="icon-copy fa fa-plus"></i> Create Program</a>
                                                    <?php } if(FABRIC_PROG_EDIT==1 && $prog>0) { ?>
                                                        <a class="dropdown-item" href="add-fabProgram.php?id=<?= $sql['id'] ?>"><i class="icon-copy fa fa-pencil"></i> Edit Program</a>
                                                    <?php } if(FABRIC_PROG_PRINT==1) {  
                                                            if ($prog>0) { ?>
                                                        <a class="dropdown-item" href="fabProgram_print.php?id=<?= $sql['id'] ?>"><i class="icon-copy fa fa-print"></i> Print</a>
                                                    <?php } } ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $x++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            $modals = ["image-modal"];
            
            include('modals.php');
            include('includes/footer.php');
            ?>
        </div>
    </div>

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    

</body>

</html>