<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'employee_name' => filter_var($_POST['edit_employee_name'], FILTER_SANITIZE_STRING),
        'employee_code' => filter_var($_POST['edit_employee_code'], FILTER_SANITIZE_STRING),
        'mobile' => filter_var($_POST['mobile_edit'], FILTER_SANITIZE_STRING),
        'company' => filter_var($_POST['unitt_edit'], FILTER_SANITIZE_STRING),
        'process' => filter_var($_POST['process_edit'], FILTER_SANITIZE_STRING),
        'sub_billname' => filter_var($_POST['subBill_edit'], FILTER_SANITIZE_STRING),
        'username' => filter_var($_POST['uname_edit'], FILTER_SANITIZE_STRING),
        'password' => filter_var($_POST['password_edit'], FILTER_SANITIZE_STRING),
        'user_group' => filter_var($_POST['user_group_edit'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Update('employee_detail', $data, " WHERE id = '" . $_REQUEST['edit_employee_id'] . "'");
    // exit;
    $_SESSION['msg'] = "updated";

    header("Location:user.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - User</title>

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
                <strong>Success!</strong> User Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> User Updated.
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
                    
                    <?php if(UP_USER!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <?php if(UP_USER_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php } ?>
                        <h4 class="text-blue h4">Manage User
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
                                    <th>User Name</th>
                                    <th>Mobile Number</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM employee_detail WHERE type='user' ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x; ?>
                                        </td>
                                        <td>
                                            <?= $sql['employee_name']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['mobile']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['username']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['password']; ?>
                                        </td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'employee_detail','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'employee_detail','active')">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <!-- <a class="dropdown-item" onclick="getcompanydetails(<?= $sql['id']; ?>)"><i class="dw dw-eye"></i> View</a> -->
                                                    <?php if(UP_USER_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(UP_USER_DELETE==1) { ?>
                                                        <form id="delete-company" method="post" autocomplete="off">
                                                            <input type="hidden">
                                                            <a class="dropdown-item"
                                                                onclick="delete_data(<?= $sql['id']; ?>, 'employee_detail')"><i
                                                                    class="dw dw-delete-3"></i> Delete</a>
                                                        </form>
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

                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit User</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="employee_detail">
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
                
                <div class="modal fade" id="user-add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">New User</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" id="employeeForm" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="employee_detail">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>User Name <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="employee_name" id="employee_name"
                                                    placeholder="User Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-none">
                                            <label>User Code </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="employee_code" id="employee_code"
                                                    placeholder="User Code">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Mobile Number </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="mobile" id="mobile"
                                                    placeholder="Mobile Number">
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-none">
                                            <label>Sub contract bill name </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="sub_billname" id="sub_billname"
                                                    placeholder="Sub contract bill name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-none">
                                            <label>Process </label>
                                            <div class="form-group">
                                                <select name="process" id="process" class="form-control custom-select2"
                                                    style="width:100%">
                                                    <?= select_dropdown('process', array('id', 'process_name'), 'process_name ASC', '', '', ''); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Company </label>
                                            <div class="form-group">
                                                <select name="unitt" id="unitt" class="form-control custom-select2" style="width:100%">
                                                    <?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', '', ''); ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label>Username </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="username" id="username"
                                                    placeholder="Username">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label>Password </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="password" id="password"
                                                    placeholder="Password">
                                            </div>
                                        </div>
                    
                                        <div class="col-md-6">
                                            <label>User Group </label>
                                            <div class="form-group">
                                                <select name="user_group" id="user_group" class="form-control custom-select2" style="width:100%">
                                                    <?= select_dropdown('user_group', array('id', 'group_name'), 'group_name ASC', '', '', ''); ?>
                                                </select>
                                                
                                                 <input type="hidden" value="user" name="emp_type" id="emp_type">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" onclick="save_employee()" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
            <?php
            include('includes/footer.php');
            // include('modals.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $('#user-add-modal').on('shown.bs.modal', function () {
            $('#employee_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_employee_name').focus();
        })


        $(".showmodal").click(function () {
            $("#user-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getUseredit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                }
            })

            $("#edit-modal").modal('show');
        })
    </script>

</html>