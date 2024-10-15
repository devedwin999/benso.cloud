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
    <title>BENSO GARMENTING - Sales Order List</title>

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
                    
                    <?php page_spinner(); if(SALES_ORDER!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">List Of Orders
                            <?php if(SALES_ORDER_ADD==1) { ?>
                                <a class="btn btn-outline-primary showmodal" href="sales_order.php" style="float: right;">+ Add an Orders</a>
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
                                    <th>Brand </th>
                                    <th>Po Qty </th>
                                    <th>Delivery Date</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.brand_name FROM sales_order a LEFT JOIN brand b ON a.brand = b.id WHERE a.is_dispatch IS NULL ORDER BY a.id DESC";
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
                                        <td><?= $x; ?></td>
                                        <td><?= viewImage($po, 50); ?></td>
                                        <td><?= $sql['order_code']; ?></td>
                                        <td><?= $sql['brand_name']; ?></td>
                                        <td><?= number_format($mlp['qttt'], 0, '.', ','); ?></td>
                                        <td><?= date('d M, Y', strtotime($sql['delivery_date'])); ?></td>
                                        <td><?= company_code($sql['created_unit']); ?></td>
                                        <td>
                                            <?php
                                            if ($sql['is_approved'] == NULL) {
                                                print '<span class="border border-warning rounded text-warning">Waiting</span>';
                                            } else if ($sql['is_approved'] == 'approved') {
                                                print '<span class="border border-success rounded text-success">Approved</span>';
                                            } else if ($sql['is_approved'] == 'rejected') {
                                                print '<span class="border border-danger rounded text-danger">Rejected</span>';
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
                                                        <a class="dropdown-item" onclick="orderfile(<?= $sql['id']; ?>)"><i class="icon-copy fa fa-eye"></i> Order Files</a>
                                                        <a class="dropdown-item" href="so_print.php?id=<?= $sql['id'] ?>" target="_blank"><i class="icon-copy fa fa-print" aria-hidden="true"></i> Print Invoive</a>
                                                    <?php if(SALES_ORDER_EDIT==1) { ?>
                                                        <a class="dropdown-item" data-id="<?= $sql['id']; ?>" href="sales_order.php?id=<?= $sql['id'] ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(TIME_SHEET_CREATION==1) { ?>
                                                        <a class="dropdown-item" data-id="<?= $sql['id']; ?>" href="time_sheet.php?id=<?= $sql['id'] ?>"><i class="icon-copy dw dw-sheet"></i> Time Sheet</a>
                                                    <?php }
                                                    
                                                    if ($sql['is_approved'] != 'approved') {
                                                        if(SALES_ORDER_DELETE==1) { ?>
                                                            <a class="dropdown-item" onclick="delete_salesOrder(<?= $sql['id']; ?>)"><i class="dw dw-delete-3"></i> Delete</a>
                                                        <?php } } else {
                                                            print '<a class="dropdown-item" onclick="is_dispatch('. $sql['id'].')"> <i class="icon-copy fa fa-list-alt" aria-hidden="true"></i> Dispatch </a>';
                                                        }
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
                
            </div>
            <?php
                $modals = ["image-modal"];
                
                include('modals.php');
                include('includes/footer.php');
            ?>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve Sales Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="is_approved_id" id="is_approved_id">
                    <select name="is_approved" id="is_approved" class="custom-select2 form-control"
                        style="width:100% !important">
                        <option value="">Waiting</option>
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary soapprove">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderfile-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Order Files</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form method="post" id="var_modalform" enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="row" id="">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Download</th>
                                    </tr>
                                </thead>
                                <tbody id="orderfile_space"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        function delete_salesOrder(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?delete_salesOrder=1&id=' + id,
                        
                        success: function (msg) {
                            var json = $.parseJSON(msg);
                            
                            if (json.result == 0) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
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

    <script>
        function orderfile(id) {
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?sorderfile=1&id=' + id,

                success: function (msg) {
                    $("#orderfile_space").html(msg);
                }
            })

            $("#orderfile-modal").modal('show');
        }
    </script>


    <script>
        function is_dispatch(id) {
            swal({
                title: 'Are you sure?',
                text: "Confirm Dispatch?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes Dispatch!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?is_dispatch=1&id=' + id ,
                        success: function (msg) {
                            // alert(msg);
                            // var json = $.parseJSON(msg);
                            // if (json.res == 'success') {
                                swal({
                                    type: 'success',
                                    title: 'Dispatched',
                                    showConfirmButton: true,
                                    timer: 1500
                                }).then(
                                    function () {
                                        location.reload();
                                    })
                            // } else {
                            //     swal(
                            //         'Something went wrong',
                            //         '',
                            //         'error'
                            //     )
                            // }
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

    <script>

        function is_approved_id(id) {
            $("#is_approved_id").val(id);
            $("#exampleModal").modal('show');

        }


        $(".soapprove").click(function () {

            var id = $("#is_approved_id").val();
            var is_approved = $("#is_approved").val();

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?approve_so=1&id=' + id + '&is_approved=' + is_approved,
                success: function (msg) {
                    var json = $.parseJSON(msg);

                    if (json.result == 'saved') {
                        swal(
                            {
                                type: 'success',
                                text: 'Status Changed!',
                                timer: 1500
                            }
                        ).then(
                            function () {
                                location.reload();
                            })
                    } else {
                        swal(
                            {
                                type: 'error',
                                text: 'Something Went Wrong!',
                                timer: 1500
                            }
                        )
                    }
                }
            })
        })
    </script>
</body>

</html>