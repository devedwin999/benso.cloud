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
    <title>BENSO - Dispatch List</title>

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
                <strong>Success!</strong> Dispatch List Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'added') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Dispatch List Saved.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php }
        $_SESSION['msg'] = '';
        ?>
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Export Datatable start -->
                <div class="card-box mb-30">
                    <?php if(DISPATCH!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Dispatch List</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Style Image</th>
                                    <th>BO No</th>
                                    <th>Brand </th>
                                    <th>Po Qty </th>
                                    <th>Delivery Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.brand_name FROM sales_order a LEFT JOIN brand b ON a.brand = b.id WHERE a.is_dispatch = 'yes' ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                $po = '';
                                while ($sql = mysqli_fetch_array($query)) {
                                    $mlp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(total_qty) as qttt FROM sales_order_detalis WHERE sales_order_id=" . $sql['id']));
                                    $imggg = mysqli_query($mysqli, "SELECT * FROM sales_order_detalis WHERE sales_order_id=" . $sql['id']);
                                    while ($iop = mysqli_fetch_array($imggg)) {
                                        if (!empty($iop['item_image'])) {
                                            $po = $iop['item_image'];
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x; ?>
                                        </td>
                                        <td>
                                            <input type="hidden" value="uploads/so_img/<?= $sql['order_code'] . '/' . $po; ?>" name="" id="dsff<?= $sql['id']; ?>">
                                            <a data-img="" onclick="showimage(<?= $sql['id']; ?>)">
                                                <img src="uploads/so_img/<?= $sql['order_code'] . '/' . $po; ?>" alt="Image" style="height: 50px !important;" width="50">
                                            </a>
                                        </td>
                                        <td>
                                            <?= $sql['order_code']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['brand_name']; ?>
                                        </td>
                                        <td>
                                            <?= number_format($mlp['qttt'], 0, '.', ','); ?>
                                        </td>
                                        <td>
                                            <?= date('d M, Y', strtotime($sql['delivery_date'])); ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($sql['is_approved'] == NULL) {
                                                print '<span class="border border-warning rounded text-warning" onclick="is_approved_id(' . $sql['id'] . ')">Waiting</span>';
                                            } else if ($sql['is_approved'] == 'approved') {
                                                print '<span class="border border-success rounded text-success">Approved</span>';
                                            } else if ($sql['is_approved'] == 'rejected') {
                                                print '<span class="border border-danger rounded text-danger" onclick="is_approved_id(' . $sql['id'] . ')">Rejected</span>';
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
                                                    <?php
                                                        print '<a class="dropdown-item" onclick="is_dispatch('. $sql['id'].')"> <i class="icon-copy dw dw-undo2"></i> Revert Dispatch </a>';
                                                    ?>
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
    
    <script>
        function is_dispatch(id) {
            swal({
                title: 'Are you sure?',
                text: "Confirm Dispatch?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes Revert!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?revert_dispatch_det=1&id=' + id ,
                        success: function (msg) {
                            // alert(msg);
                            var json = $.parseJSON(msg);
                            if (json.res == 'success') {
                                swal({
                                    type: 'success',
                                    title: 'Revert Completed',
                                    showConfirmButton: true,
                                    timer: 1500
                                }).then(
                                    function () {
                                        location.reload();
                                    })
                            } else {
                                swal(
                                    'Something went wrong',
                                    '',
                                    'error'
                                )
                            }
                        }
                    })
                } else {
                    swal(
                        'Cancelled',
                        '',
                        'error'
                    )
                }
            })
        };
    </script>


    <?php include('includes/end_scripts.php'); ?>

</body>

</html>