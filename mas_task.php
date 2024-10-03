<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'task_name' => filter_var($_POST['edit_task_name'], FILTER_SANITIZE_STRING),    
        'task_process_id' => $_REQUEST['ed_process'],
        'daily_followup_task' => $_REQUEST['edit_daily_followup_task'],
        'daily_followup_duration_task' => $_REQUEST['edit_daily_followup_duration_task'],
        'end_followup_task' => $_REQUEST['edit_end_followup_task'],
        'end_followup_duration_task' => $_REQUEST['edit_end_followup_duration_task'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Update('mas_task', $data, " WHERE id = '" . $_REQUEST['edit_task_id'] . "'");

    timeline_history('Update', 'mas_task', $_REQUEST['edit_task_id'], 'Task Master Updated.');
    
    $_SESSION['msg'] = "updated";

    header("Location:mas_task.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Task</title>

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
                <strong>Success!</strong> Task Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Task Updated.
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
                    
                    <?php if(MAS_TASK!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <?php if(MAS_TASK_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php } ?>
                        
                        <h4 class="text-blue h4">Manage Tasks
                            <p class="mb-30 text-danger">
                                <i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info" style="font-size: 15px;"></i> Click on the Status To change
                            </p>
                        </h4>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Task Name</th>
                                    <th>Daily FollowUp Duration</th>
                                    <th>End Followup Duration</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM mas_task ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['task_name']; ?></td>
                                        <td><?= $sql['daily_followup_task']; ?>&nbsp;<?= $sql['daily_followup_duration_task']; ?></td>
                                        <td><?= $sql['end_followup_task']; ?>&nbsp;<?= $sql['end_followup_duration_task']; ?></td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success" onclick="changeStatus(<?= $sql['id']; ?>,'mas_task','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger" onclick="changeStatus(<?= $sql['id']; ?>,'mas_task','active')">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(MAS_TASK_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(MAS_TASK_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'mas_task')"><i class="dw dw-delete-3"></i> Delete</a>
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
                                                    
                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Task</h4>
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
            <?php
            $modals = ["task-add-modal"];
            include('modals.php');
            include('includes/footer.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        $("#task_type").change(function() {
            
            var val = $(this).val();
// alert(val);
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?getTaskTypes=1&value=' + val,
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);
                    
                    if(val == '') {
                        $(".commonDiv").addClass('d-none');
                        $(".otherDiv").addClass('d-none');
                    } else if(val == 'other_task') {
                        $("#task_name").val('');
                        $(".commonDiv").addClass('d-none');
                        $(".otherDiv").removeClass('d-none');
                    } else {
                        
                        $("#task_process_id").html(json.optionn);
                        $(".commonDiv").removeClass('d-none');
                        $(".otherDiv").addClass('d-none');
                    }
                    $("#editmodaldetail").html(msg);
                }
            })
        });
    </script>

    <script>
        $('#task-add-modal').on('shown.bs.modal', function () {
            $('#task_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_task_name').focus();
        })


        $(".showmodal").click(function () {
            $("#task-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?gettaskedit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                }
            })

            $("#edit-modal").modal('show');
        });
        
        
$("#task_process_id").change(function() {

    var vall = $(this).find('option:selected').text();

    $("#task_name").val(vall);
})
        

function save_task() {
   var a = 0;

    if ($("#task_type").val() == "other_task") {
         if ($("#task_name").val() == "") {
            a++;
            message_noload('warning', 'Task Name Required!', 1000);
            return false;
        } 
    } else if ($("#task_process_id").val() == "") {
        a++;
        message_noload('warning', 'Task Name Required!', 1000);
        return false;
    }
    
        if(a==0) {
            var form = $("#task_addForm").serialize()
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?save_task=1',
                data: form,
                success: function (msg) {

                    var json = $.parseJSON(msg);
                    if (json.result == 'success') {
                        message_reload('success', 'Task Saved');
                    } else {
                        message_error();
                    }
                }
            })
        }
    }
</script>

</html>