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
    <title>BENSO GARMENTING - Fabric Purchase Receipt List</title>

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
                <strong>Success!</strong> Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'added') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Saved.
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
                    <?php if(FAB_PO_RECEIPT!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <?php if(FAB_PO_RECEIPT_ADD==1) { ?>
                                <a class="btn btn-outline-primary" href="fab_poReceipt.php" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                                <?php } ?>
                                <a class="btn btn-outline-info" href="mod_fabric.php"><i class="fa fa-home" aria-hidden="true"></i> Fabric</a>
						</div>
                        <h4 class="text-blue h4">Fabric Purchase Receipt List</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>GRN No</th>
                                    <th>GRN Date</th>
                                    <th>Supplier</th>
                                    <th>Supp.DC Number</th>
                                    <th>Supp.DC date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.supplier_name FROM fabric_po_receipt a LEFT JOIN supplier b ON a.supplier=b.id ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['grn_number'] ? $sql['grn_number'] : '-'; ?></td>
                                        <td><?= $sql['grn_date'] ? date('d-M, Y', strtotime($sql['grn_date'])) : '-'; ?></td>
                                        <td><?= $sql['supplier_name'] ? $sql['supplier_name'] : '-'; ?></td>
                                        <td><?= $sql['sup_dc_number'] ? $sql['sup_dc_number'] : '-'; ?></td>
                                        <td><?= $sql['sup_dc_date'] ? date('d-M, Y', strtotime($sql['sup_dc_date'])) : '-'; ?></td>

                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(FAB_PO_RECEIPT_EDIT==1) { ?>
                                                        <a class="dropdown-item" data-id="<?= $sql['id']; ?>" href="fab_poReceipt.php?id=<?= $sql['id'] ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                     <?php } if(FAB_PO_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_poreceipt(<?= $sql['id']; ?>)"><i class="fa fa-trash"></i> Delete</a>
                                                    <?php } ?>
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

    <div class="modal fade bs-example-modal-lg" id="viewModal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Processing List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Sl.No</th>
                                <th>So.No</th>
                                <th>Style No</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Boundle No</th>
                                <th>Boundle QR</th>
                            </tr>
                        </tbody>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getProcessingDet(id) {

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getProcessingDet=1&id=' + id,
                success: function (msg) {
                    $("#tableBody").html(msg);
                }
            })
            $("#viewModal").modal('show');
        }
    </script>
    <script>
        function delete_poreceipt(id) {
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
                        url: 'ajax_action2.php?delete_poreceipt=1&id=' + id,
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

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

</body>

</html>