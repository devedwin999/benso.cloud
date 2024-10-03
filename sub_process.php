<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['saveBtn'])) {

    $data = array(
        'department' => filter_var($_POST['department'], FILTER_SANITIZE_STRING),
        'process_id' => filter_var($_POST['process_list'], FILTER_SANITIZE_STRING),
        'sub_process_name' => filter_var($_POST['process_name'], FILTER_SANITIZE_STRING),
        'sub_process_code' => filter_var($_POST['process_code'], FILTER_SANITIZE_STRING),
        'price' => filter_var($_POST['price'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Insert('sub_process', $data);
    $inid = mysqli_insert_id($mysqli);

    $ndata = array(
        'sub_process' => $inid,
        'old_price' => 0,
        'new_price' => filter_var($_POST['price'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $rtee = Insert('sp_rate_history', $ndata);

    $_SESSION['msg'] = "saved";

    header("Location:sub_process.php");

    exit;
}

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'department' => filter_var($_POST['edit_department'], FILTER_SANITIZE_STRING),
        'process_id' => filter_var($_POST['edit_process_id'], FILTER_SANITIZE_STRING),
        'sub_process_name' => filter_var($_POST['edit_process_name'], FILTER_SANITIZE_STRING),
        'sub_process_code' => filter_var($_POST['edit_process_code'], FILTER_SANITIZE_STRING),
        'price' => filter_var($_POST['edit_price'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $nqy = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sub_process WHERE id=" . $_REQUEST['edit_process_id']));

    if ($nqy['price'] != $_POST['edit_price']) {
        $ndata = array(
            'sub_process' => $_REQUEST['edit_process_id'],
            'old_price' => $nqy['price'],
            'new_price' => $_POST['edit_price'],
            'created_date' => date('Y-m-d H:i:s')
        );

        $rtee = Insert('sp_rate_history', $ndata);
    }

    $qry = Update('sub_process', $data, " WHERE id = '" . $_REQUEST['edit_process_id'] . "'");

    $_SESSION['msg'] = "updated";

    header("Location:sub_process.php");

    exit;
}

if (isset($_REQUEST['updateHist'])) {
    $nqy = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sub_process WHERE id=" . $_REQUEST['sub_process_idd']));

    if ($nqy['price'] != $_POST['edit_price']) {
        $ndata = array(
            'sub_process' => $_REQUEST['sub_process_idd'],
            'old_price' => $nqy['price'],
            'new_price' => $_POST['upd_price'],
            'created_date' => date('Y-m-d H:i:s')
        );

        $data = array(
            'price' => $_POST['upd_price'],
        );

        $rtee = Insert('sp_rate_history', $ndata);

        $qry = Update('sub_process', $data, " WHERE id = '" . $_REQUEST['sub_process_idd'] . "'");

        $_SESSION['msg'] = "updated";

        header("Location:sub_process.php");

        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Sub Process</title>

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
                <strong>Success!</strong> Sub Process Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Sub Process Updated.
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
                    
                    <?php if(MAS_SUBPROCESS!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add
                                New</a>
                        <h4 class="text-blue h4">Manage Sub Process
                            <p class="mb-30 text-danger">
                                <i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info"
                                    style="font-size: 15px;"></i> Click on the Status To change
                            </p>
                        </h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Department </th>
                                    <th>Process </th>
                                    <th>Sub Process Name</th>
                                    <th>Sub Process Code</th>
                                    <th>Rate</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.department_name, c.process_name FROM sub_process a LEFT JOIN department b ON a.department = b.id LEFT JOIN process c ON c.id=a.process_id ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x; ?>
                                        </td>
                                        <td>
                                            <?= $sql['department_name']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['process_name']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['sub_process_name']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['sub_process_code']; ?>
                                        </td>
                                        <td><?= $sql['price']; ?></td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'sub_process','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'sub_process','active')">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item" onclick="rateHistory(<?= $sql['id']; ?>)"><i
                                                            class="dw dw-eye"></i> Rate Change History</a>
                                                    <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>"
                                                        href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>

                                                    <form id="delete-company" method="post" autocomplete="off">
                                                        <input type="hidden">
                                                        <a class="dropdown-item"
                                                            onclick="delete_data(<?= $sql['id']; ?>, 'sub_process')"><i
                                                                class="dw dw-delete-3"></i> Delete</a>
                                                    </form>
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

                <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">New Sub Process</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="process">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Select Department <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="custom-select2 form-control d-cursor" name="department" id="department"
                                                    required="" style="width:100% !important">
                                                    <?php print select_dropdown('department', array('id', 'department_name'), 'department_name ASC', '', '', ''); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Select Process <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="custom-select2 form-control" name="process_list" id="process_list"
                                                    required="" style="width:100% !important">
                                                    <?php print select_dropdown('process', array('id', 'process_name'), 'process_name ASC', '', '', ''); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Sub Process Name <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="process_name"
                                                    id="process_name" placeholder="Sub Process Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Sub Process Code </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="process_code"
                                                    id="process_code" placeholder="Sub Process Name">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Price </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="price" id="price"
                                                    placeholder="Price">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="saveBtn" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Sub Process</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                
                                <div class="modal-body">
                                    <div class="row" id="editmodaldetail">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="updateForm" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="history-modal" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Rate Change History</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12" id="historymodaldetail" style="padding:20px;"></div>
                                        <hr>
                                        <table class="table" style="padding: 20px;">
                                            <thead>
                                                <tr>
                                                    <th>Previous Price</th>
                                                    <th>Changed Price</th>
                                                    <th>Date Changed</th>
                                                </tr>
                                            <tbody id="historytable"></tbody>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="updateHist" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        function rateHistory(id) {
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?rateHistory=1&id=' + id,
                success: function (msg) {
                    var json = $.parseJSON(msg);

                    $("#historytable").html(json.tbody);
                    $("#historymodaldetail").html(json.div);
                }
            })
            $("#history-modal").modal('show');
        }

        $('#history-modal').on('shown.bs.modal', function () {
            $('#upd_price').focus();
        });
    </script>

    <script>
        $('#add-modal').on('shown.bs.modal', function () {
            $('#department').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_department').focus();
        })


        $(".showmodal").click(function () {
            $("#add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getsub_processedit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                }
            })

            $("#edit-modal").modal('show');
        })
    </script>

</html>