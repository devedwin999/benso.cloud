<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['saveBtn'])) {

    $data = array(
        'department' => filter_var($_POST['department'], FILTER_SANITIZE_STRING),
        'process_name' => filter_var($_POST['process_name'], FILTER_SANITIZE_STRING),
        'process_code' => filter_var($_POST['process_code'], FILTER_SANITIZE_STRING),
        'process_price' => filter_var($_POST['process_price'], FILTER_SANITIZE_STRING),
        'process_type' => filter_var($_POST['process_type'], FILTER_SANITIZE_STRING),
        'budget_type' => filter_var($_POST['budget_type'], FILTER_SANITIZE_STRING),
        'qc_approval' => filter_var($_POST['qc_app'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );
    
    if($_POST['process_type']=='Fabric') {
        $ndta = array(
            'process_type_name' => $_POST['process_type_name'],
            );
    } else {
        $ndta = array();
    }
    
    $num = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM process WHERE process_name = '". $_POST['process_name'] ."'"));
    if($num==0) {
        $qry = Insert('process', array_merge($data, $ndta));
        timeline_history('Insert', 'process', mysqli_insert_id($mysqli), 'Process Added. Ref: '. $_POST['process_name']);
        $_SESSION['msg'] = "saved";
    } else {
        $_SESSION['msg'] = "duplicate";
    }
    
    header("Location:process.php");

    exit;
}

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'department' => filter_var($_POST['edit_department'], FILTER_SANITIZE_STRING),
        'process_name' => filter_var($_POST['edit_process_name'], FILTER_SANITIZE_STRING),
        'process_code' => filter_var($_POST['edit_process_code'], FILTER_SANITIZE_STRING),
        'process_type' => filter_var($_POST['edit_process_type'], FILTER_SANITIZE_STRING),
        'budget_type' => filter_var($_POST['edit_budget_type'], FILTER_SANITIZE_STRING),
        'process_price' => filter_var($_POST['edit_process_price'], FILTER_SANITIZE_STRING),
        'qc_approval' => filter_var($_POST['edit_qc_app'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Update('process', $data, " WHERE id = '" . $_REQUEST['edit_process_id'] . "'");

    $_SESSION['msg'] = "updated";

    header("Location:process.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Process</title>

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
                <strong>Success!</strong> Process Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Process Updated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php } else if ($_SESSION['msg'] == 'duplicate') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Process Already Found!.
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
                    
                    <?php if(MAS_PROCESS!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add
                            New</a>
                        <h4 class="text-blue h4">Manage Process
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
                                    <th>Process Name</th>
                                    <th>Process Code</th>
                                    <th>Process Price</th>
                                    <th>Process Type</th>
                                    <th>Budget Type</th>
                                    <th>Quality Approval</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.department_name FROM process a LEFT JOIN department b ON a.department = b.id ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['department_name']; ?></td>
                                        <td><?= $sql['process_name']; ?></td>
                                        <td><?= $sql['process_code']; ?></td>
                                        <td><?= $sql['process_price']; ?></td>
                                        <td><?= $sql['process_type']; ?></td>
                                        <td><?= $sql['budget_type'] ? $sql['budget_type'] : '-'; ?></td>
                                        <td><?= strtoupper($sql['qc_approval']); ?></td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'process','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'process','active')">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if($sql['can_edit']=='yes') {  if(MAS_PROCESS_EDIT==1) {?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(MAS_PROCESS_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'process')"><i class="dw dw-delete-3"></i> Delete</a>
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

                <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">New Process</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="process">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Department <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="custom-select2 form-control d-cursor" name="department"
                                                    id="department" required="" style="width:100% !important">
                                                    <?php print select_dropdown('department', array('id', 'department_name'), 'id ASC', '', '', ''); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Process Name <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="process_name"
                                                    id="process_name" placeholder="Process Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Process Code </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="process_code"
                                                    id="process_code" placeholder="Process Name">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Process Price </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="process_price"
                                                    id="process_price" placeholder="Process Price">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Process Type</label>
                                            <div class="form-group">
                                                <select name="process_type" id="process_type" class="form-control custom-select2" style="width:100%">
                                                    <option value="production">Production Process</option>
                                                    <option value="Fabric">Fabric Process</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Budget Type </label>
                                            <div class="form-group">
                                                <select name="budget_type" id="budget_type" class="form-control custom-select2" style="width:100%">
                                                    <option value="">Select</option>
                                                    <option value="Fabric">Fabric</option>
                                                    <option value="Yarn">Yarn</option>
                                                    <option value="Dyeing Color">Dyeing Color</option>
                                                    <option value="AOP Design">AOP Design</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12 d-none" id="process_type_nameDiv" style="display:none" >
                                            <label>Fabric Type </label>
                                            <div class="form-group">
                                                <select name="process_type_name" id="process_type_name" class="form-control custom-select2" style="width:100%">
                                                    <option value="Solid">Solid</option>
                                                    <option value="Y/D">Y/D</option>
                                                    <option value="Melange">Melange</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Quality Approval </label>
                                            <div class="form-group">
                                                <select name="qc_app" id="qc_app" class="form-control custom-select2" style="width:100%">
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
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
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Process</h4>
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

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        $("#process_type").change(function() {
            var asd = $(this).val();
            if(asd=="Production") {
                
                $("#process_type_nameDiv").hide();
            } else if(asd=="Fabric")
            {
                $("#process_type_nameDiv").show();
            }
        })
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
                url: 'ajax_search.php?getprocessedit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                }
            })

            $("#edit-modal").modal('show');
        })
    </script>

</html>