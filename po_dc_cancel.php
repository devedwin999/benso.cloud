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
    <title>BENSO GARMENTING - PO / DC List</title>

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
                    
                    <?php if(FAB_CANCEL!=1) { action_denied(); exit; } ?>
                   
                    <div class="pd-20">
                        <?php if(FAB_CANCEL_ADD == 1) { ?>
                            <a class="btn btn-outline-primary" href="po_dc_canceladd.php" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                        <?php } ?>
                        <h4 class="text-blue h4">Fabric PO / DC List</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Entry No</th>
                                    <th>Entry Date</th>                                   
                                    <th>Cancel Type</th>                                   
                                    <th>Cancel From</th>                                   
                                    <th>Entry no</th>                                   
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM  podc_cancel  ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['podc_entry']; ?></td>
                                        <td><?= date('d-m-y H:i', strtotime($sql['podc_date'])); ?></td>
                                        <td><?= ucfirst($sql['opdc_canceltype']); ?></td>
                                        <td><?= ($sql['opdc_cancelto']=='fabric_po') ? 'PO' : 'DC'; ?></td>
                                        <td><?= ($sql['opdc_cancelto']=='fabric_po') ? fabric_po_ref($sql['podc_entryno']) : fabric_dc_ref($sql['podc_entryno']); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(FAB_CANCEL_EDIT == 1) { ?>
                                                        <a class="dropdown-item podc_edit" data-id="<?= $sql['id']; ?>" href="po_dc_canceladd.php?id=<?= $sql['id'] ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(FAB_CANCEL_DELETE == 1) { ?>
                                                        <a class="dropdown-item" onclick="delete_podc_cancel(<?= $sql['id']; ?>)"><i class="fa fa-trash"></i> Delete</a>
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
    <script>
        function delete_podc_cancel(id) {
            
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
                        url: 'ajax_action2.php?delete_podc_cancel=1&id=' + id,
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

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

</body>

</html>