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
    <title>BENSO - Bill Passing</title>

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

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'updated') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Processing Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'added') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Processing Saved.
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
                    <?php if(BILL_PASSING!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary float-right" href="bill_passing-add.php"><i class="fa fa-plus"></i> Add New</a>
                        <h4 class="text-blue h4">Production Bill Passing List</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Entry Number</th>
                                    <th>Entry Date</th>
                                    <th>Bill Type</th>
                                    <th>Bill Number</th>
                                    <th>Bill Date</th>
                                    <th>Supplier</th>
                                    <th>Bill Amount</th>
                                    <th>Bill Image</th>
                                    <th>Rate Approved Image</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.supplier_name ";
                                $qry .= " FROM bill_receipt a ";
                                $qry .= " LEFT JOIN supplier b ON a.supplier = b.id ";
                                $qry .= " ORDER BY a.id DESC ";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    
                                    $bord = array('pending' => 'warning', 'passed' => 'success');
                                    $stat = array('pending' => 'warning', 'passed' => 'success');
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['entry_number']; ?></td>
                                        <td><?= date('y-m-d', strtotime($sql['entry_date'])); ?></td>
                                        <td><?= $sql['bill_type']; ?></td>
                                        <td><?= $sql['bill_number']; ?></td>
                                        <td><?= date('y-m-d', strtotime($sql['bill_date'])); ?></td>
                                        <td><?= $sql['supplier_name']; ?></td>
                                        <td><?= $sql['bill_amount']; ?></td>
                                        <td><?php if($sql['bill_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['bill_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?php if($sql['approved_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['approved_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?= $sql['comments'] ?  $sql['comments'] : '-'; ?></td>
                                        <td>
                                            <span class="border border-<?= $bord[$sql['status']]; ?> rounded text-<?= $stat[$sql['status']]; ?>"><?= $sql['status']; ?></span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"> <i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if($sql['status']=='passed') {
                                                     if(BILL_PASSING_EDIT==1) {
                                                    ?>
                                                        <a class="dropdown-item" href="add-prod-bill.php?id=<?= $sql['cost_id'] ?>&bid=<?= $sql['id']; ?>&from=bill_passing"><i class="fa fa-pencil"></i> Edit</a>
                                                    <?php } if(BILL_PASSING_VIEW==1) { ?>
                                                        <a class="dropdown-item" href="add-prod-bill.php?id=<?= $sql['cost_id'] ?>&bid=<?= $sql['id']; ?>&typ=view&from=bill_passing"><i class="fa fa-eye"></i> View</a>
                                                    <?php } } else { 
                                                     if(BILL_PASSING_ADD==1) {
                                                    ?>
                                                        <a class="dropdown-item" href="add-prod-bill.php?id=<?= $sql['cost_id'] ?>&bid=<?= $sql['id']; ?>&from=bill_passing"><i class="dw dw-edit2"></i> Create</a>
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
                <!-- Export Datatable End -->
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

</body>

</html>