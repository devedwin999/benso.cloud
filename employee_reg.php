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

    $qry = Update('employee_detail', $data, ' WHERE id = "' . $_REQUEST['edit_employee_id'] . '"');
    // exit;
    $_SESSION['msg'] = "updated";

    header("Location:employee.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Registered Employees</title>

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">


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

<style>
    #button{
      display:block;
      margin:20px auto;
      padding:10px 30px;
      background-color:#eee;
      border:solid #ccc 1px;
      cursor: pointer;
    }
    #overlay{	
      position: fixed;
      top: 0;
      z-index: 100;
      width: 100%;
      height:100%;
      display: none;
      background: rgba(0,0,0,0.6);
    }
    .cv-spinner {
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;  
    }
    .spinner {
      width: 40px;
      height: 40px;
      border: 4px #ddd solid;
      border-top: 4px #2e93e6 solid;
      border-radius: 50%;
      animation: sp-anime 0.8s infinite linear;
    }
    @keyframes sp-anime {
      100% { 
        transform: rotate(360deg); 
      }
    }
    .is-hide{
      display:none;
    }
    
    .contact-directory-box .contact-avatar span img, .profile-photo .avatar-photo, .profile-photo .edit-avatar {
         border-radius: 0% !important; 
        height: 175px  !important;
    }
</style>

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Employee Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Employee Updated.
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
                    
                    <?php if(UP_EMPLOYEE_TEMP!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        
                        <h4 class="text-blue h4">Registered Employees List
                            <p class="mb-30 text-danger">
                                <i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info"
                                    style="font-size: 15px;"></i> Click on the Status To change
                            </p>
                        </h4>
                    </div>
                    <div class="pb-20"  style="overflow-y:auto">
                        <table class="table hover multiple-select-row nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Registered Date</th>
                                    <th>Employee Photo</th>
                                    <th>Employee Name</th>
                                    <th>Employee Code</th>
                                    <th>Employee Type</th>
                                    <th>Mobile Number</th>
                                    <th>Working Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM employee_detail_temp WHERE is_approved IS NULL ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= date('d-m-y', strtotime($sql['created_date'])) ?></td>
                                        <td>
                                            <input type="hidden" value="<?= $sql['employee_photo']; ?>" name="" id="dsff<?= $sql['id']; ?>">
                                            <a data-img="" onclick="showimage(<?= $sql['id']; ?>)">
                                                <img src="<?= $sql['employee_photo']; ?>" alt="Image" width="50">
                                            </a>
                                        </td>
                                        <td><?= $sql['employee_name']; ?></td>
                                        <td><?= $sql['employee_code']; ?></td>
                                        <td><?= ($sql['type']=='user') ? 'Staff' : 'Worker'; ?></td>
                                        <td><?= $sql['mobile'] ? $sql['mobile'] : '-'; ?></td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'employee_detail_temp','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'employee_detail_temp','active')">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(UP_EMPLOYEE_TEMP_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
    
                                                    <?php } if(UP_EMPLOYEE_TEMP_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'employee_detail_temp')"><i class="dw dw-delete-3"></i> Delete</a>
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

                
                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Employee</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            
                            <form method="post" action="ajax_action.php?moveAs_employee" id="editEmpForm" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="insType" id="insType" value="move">
                                <input type="hidden" name="updId" id="updId">
                                
                                <input type="hidden" id="reject_alert">
                                <div class="modal-body">
                                    <div class="row" id="editmodaldetail">
                                        Loading..
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <textarea class="form-control d-none" id="reject_reason" placeholder="Reason For Rejection" style="height:60px"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="rejectEmployee()" class="btn btn-danger scbtnMove">Reject</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button class="btn btn-outline-primary movebtn d-none" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Updating...
                                        </button>
                                    <button type="button" onclick="save_employee('move')" class="btn btn-success scbtnMove">Approve & Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <?php
            include('includes/footer.php');
            include('modals.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        function rejectEmployee() {
            var id = $("#updId").val();
            
            if($("#reject_alert").val() == "") {
                $("#reject_reason").removeClass('d-none');
                $("#reject_reason").focus();
                
                $("#reject_alert").val(1);
            } else {
                var cmt = $("#reject_reason").val();
                
                if(cmt == "") {
                    $("#reject_reason").focus();
                    message_noload('warning', 'Reason Required!', 2000);
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?rejectRegisteredEmp=1&cmt=' + cmt + '&id=' + id,
                        success: function (msg) {
                            var json = $.parseJSON(msg);
                            
                            if(json.res==0) {
                                message_reload('success', 'User Register Request Rejected!', 2000);
                            } else {
                                message_noload('error', 'Something Went Wrong!', 2000);
                            }
                        }
                    })
                }
            }
            
            
        }
    </script>

    <script>
        $('#employeeNew-add-modal').on('shown.bs.modal', function () {
            $('#employee_code').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_employee_name').focus();
        })

        $(".showmodal-new").click(function () {
            $("#employeeNew-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');
            
            $("#updId").val(id);
            
            $("#editmodaldetail").load('/emp_edit.php?type=reg_edit&id=' + id);

            $("#edit-modal").modal('show');
        })
    </script>
    
    <script>
        function getState(cls) {
            var country = $("#country" + cls).val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_state=1&country=' + country,
                success: function (msg) {
                    $("#state" + cls).html(msg);
                }
            })
        }
    </script>

    <script>
        function getCity(cls) {
            var state = $("#state" + cls).val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_city=' + state,
                success: function (msg) {
                    $("#city" + cls).html(msg);
                }
            })
        }
    </script>
    
    
    <script>
        $("#dob").change(function() {
            dob = new Date($(this).val());
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            $('#age').val(age);
            
            // alert(age);
        })
    </script>
    
    <script>
    
        $("#dob_edit").change(function() {
            alert();
            dob = new Date($(this).val());
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            $('#age_edit').val(age);
            
            // alert(age);
        })
    </script>

</html>










































