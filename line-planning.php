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
    <title>BENSO - Line Planing</title>

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
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Line Planning Saved.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Line Planning Updated.
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
        <?php } else if ($_SESSION['msg'] == 'created') { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Process Created.
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
                    <?php page_spinner(); if(LINE_PLANNING!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Line Planning Area</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Order Image</th>
                                    <th>BO</th>
                                    <th>Style</th>
                                    <th>Brand </th>
                                    <th>Po Qty </th>
                                    <th>Delivery Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*,b.brand FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id = b.id WHERE b.is_dispatch IS NULL ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= viewImage($sql['item_image'], 50); ?></td>
                                        <td><?= sales_order_code($sql['sales_order_id']); ?></td>
                                        <td><?= $sql['style_no']; ?></td>
                                        <td><?= brand_name($sql['brand']); ?></td>
                                        <td><?= $sql['total_qty']; ?></td>
                                        <td><?= date('d-m-y', strtotime($sql['delivery_date'])); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item" href="add-line-planing.php?id=<?= $sql['id']; ?>&typ=order"><i class="icon-copy fa fa-user-plus" aria-hidden="true"></i> Add Line Planning</a>
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

    <script>
        function orderfile(id) {
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?sorderfile=1&id=' + id,
                
                success: function (msg) {
                    $("#orderfile_space").html(msg);
                }
            });
            
            $("#orderfile-modal").modal('show');
        }
    </script>


    <script>
        function delete_data(id) {
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
                        url: 'ajax_action.php?delete_data=' + id + '&table=sales_order',
                        success: function (msg) {
                            if (msg == 0) {
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
        $(".is_approved_id").click(function () {
            $("#is_approved_id").val($(this).attr('data-id'));
            $("#exampleModal").modal('show');
        })
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
                                text: 'Action Changed!',
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

</html>