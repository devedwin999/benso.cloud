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
    <title>BENSO GARMENTING - In-house Process</title>

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
                    <?php if(INHOUSE!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">In-house Process</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>DC Number</th>
                                    <th>DC Date</th>
                                    <th>Process Type</th>
                                    <th>Process Name</th>
                                    <th>In-house Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.process_name FROM processing_list a LEFT JOIN process b ON a.process_id=b.id 
                                        WHERE a.input_type= 'Unit' AND a.assigned_emp='" . $logUnit . "' AND a.type='process_outward' AND a.is_inwarded IS NULL AND a.complete_inhouse IS NULL ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['processing_code']; ?></td>
                                        <td><?= date('d-m-y', strtotime($sql['created_date'])); ?></td>
                                        <td><?= $sql['p_type']; ?> Process</td>
                                        <td><?= $sql['process_name'] ? $sql['process_name'] : '-'; ?></td>
                                        <td>
                                            <?php
                                            if ($sql['is_inhouse'] == 'approved') {
                                                print '<span class="border border-success rounded text-success">Received</span>';
                                            } else if ($sql['is_inhouse'] == 'rejected') {
                                                print '<span class="border border-danger rounded text-danger" onclick="is_approved_id(' . $sql['id'] . ')">Rejected</span>';
                                            } else {
                                                print '<span class="border border-warning rounded text-warning" onclick="is_approved_id(' . $sql['id'] . ')">Waiting</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if ($sql['is_inhouse'] != 'approved') { if(INHOUSE_RECEIVE==1) { ?>
                                                        <a class="dropdown-item" onclick="is_approved_id(<?= $sql['id']; ?>)"><i class="icon-copy fa fa-list-alt" aria-hidden="true"></i> In-house Received</a>
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

    <div class="modal fade bs-example-modal-lg" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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

    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Received Bundles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="is_approved_id" id="is_approved_id">
                    <select name="is_approved" id="is_approved" class="custom-select2 form-control"
                        style="width:100% !important">
                        <option value="">Pending</option>
                        <option value="approved">Received</option>
                        <option value="rejected">Reject</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="soapprove()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function completed_mark(id) {
            swal({
                title: 'Are you sure?',
                text: "Completed the process!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Completed!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?completed_mark=1&id=' + id,
                        success: function (msg) {
                            if (msg == 1) {
                                swal({
                                    type: 'success',
                                    title: 'Completed',
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
        function is_approved_id(id) {
            $("#is_approved_id").val(id);
            $("#approveModal").modal('show');

        }

        function soapprove() {
            var id = $("#is_approved_id").val();
            var is_approved = $("#is_approved").val();

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?approve_inHouse=1&id=' + id + '&is_approved=' + is_approved,
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
        }
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