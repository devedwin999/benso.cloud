<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['updateForm'])) {


    $data = array(
        'checking_name' => filter_var($_POST['edit_checking_name'], FILTER_SANITIZE_STRING),
        'is_rework' => $_POST['is_rework'],
        'checking_color' => $_POST['edit_checking_color'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );
    
    $qry = Update('mas_checking', $data, " WHERE id = '" . $_REQUEST['edit_checking_id'] . "'");

    timeline_history('Update', 'mas_checking', $_REQUEST['edit_checking_id'], 'checking Master Updated.');
    
    $_SESSION['msg'] = "updated";

    header("Location:mas_checking.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Checking</title>

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
    
	<link rel="stylesheet" type="text/css" href="src/plugins/jquery-asColorPicker/dist/css/asColorPicker.css">

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
                <strong>Success!</strong> Checking Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Checking Updated.
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
                    
                    <?php if(MAS_CHECKING!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <?php if(MAS_CHECKING_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php } ?>
                        
                        <h4 class="text-blue h4">Manage checking Types
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
                                    <th>Checking Name</th>
                                    <th>Rework Applicable</th>
                                    <th>Checking Color</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM mas_checking ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['checking_name']; ?></td>
                                        <td><?= $sql['is_rework']; ?></td>
                                        <td>
                                            <span style="border: 1px solid <?= $sql['checking_color']; ?>;padding: 5px;border-radius: 5%;background: <?= $sql['checking_color']; ?>;color:#fff; border-radius:.25rem;">&nbsp;#&nbsp;</span>
                                            <span style="border: 1px solid <?= $sql['checking_color']; ?>;padding: 5px;border-radius: 5%;color: <?= $sql['checking_color']; ?>; border-radius:.25rem;"><?= $sql['checking_color']; ?></span>
                                        </td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success" onclick="changeStatus(<?= $sql['id']; ?>,'mas_checking','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger" onclick="changeStatus(<?= $sql['id']; ?>,'mas_checking','active')">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(MAS_CHECKING_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(MAS_CHECKING_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'mas_checking')"><i class="dw dw-delete-3"></i> Delete</a>
                                                    <?php } ?>     
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <div class="modal fade" id="edit-modal<?= $sql['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myLargeModalLabel">Edit Checking</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <form method="post" id="form<?= $sql['id']; ?>" autocomplete="off" enctype="multipart/form-data">
                                                    
                                                    <div class="modal-body">
                                                        <div class="row" id="editmodaldetail">
                                                            <?php
                                                            
                                                            $ass = "SELECT * FROM mas_checking WHERE id='" . $sql['id'] . "'";
                                                            $asss = mysqli_query($mysqli, $ass);
                                                            $asd = mysqli_fetch_array($asss);
                                                        
                                                        
                                                            print '<div class="col-md-12">';
                                                            print '<label>Checking Name <span class="text-danger">*</span></label>';
                                                            print '<div class="form-group">';
                                                            $read = ($sql['id']== 1 || $sql['id']==6) ? 'readonly' : '';
                                                            print '<input type="text" class="form-control d-cursor" name="edit_checking_name" id="edit_checking_name" placeholder="Checking Name" value="' . $asd['checking_name'] . '" required '. $read .'>';
                                                            print '<input type="hidden" name="edit_checking_id" id="edit_checking_id" value="' . $asd['id'] . '" required>';
                                                            print '</div>';
                                                            print '</div>';
                                                        
                                                            print '<div class="col-md-12">';
                                                            print '<label>Rework Applicable <span class="text-danger">*</span></label>';
                                                            print '<div class="form-group">';
                                                            print '<select class="form-control custom-select2" name="is_rework" id="is_rework' . $asd['id'] . '" style="width:100%">';
                                                            $ys = ($asd['is_rework']=='Yes') ? 'selected' : '';
                                                            print '<option value="No">No</option><option value="Yes" '. $ys .'>Yes</option>';
                                                            print '</select>';
                                                            print '</div>';
                                                            print '</div>';
                                                            
                                                        
                                                            print '<div class="col-md-12">';
                                                            print '<label>Checking Color <span class="text-danger">*</span></label>';
                                                            print '<div class="form-group">';
                                                            print '<input type="hidden" name="edit_checking_color" id="edit_checking_color' . $asd['id'] . '" value="' . $asd['checking_color'] . '" >';
                                                            
                                                            print '<input type="text" name="" id="tempclr' . $asd['id'] . '" oninput="changasc(' . $asd['id'] . ')" class="complex-colorpicker form-control asColorPicker-input" placeholder="Checking Color" value="' . $asd['checking_color'] . '" required>';
                                                            print '</div>';
                                                            print '</div>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" name="updateForm" onclick="updateForm(<?= $sql['id']; ?>)" class="btn btn-success">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $x++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input id="tempModId" type="hidden">
            </div>
            <?php
            include('includes/footer.php');
            include('modals.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    
	<script src="src/plugins/jquery-asColor/dist/jquery-asColor.js"></script>
	<script src="src/plugins/jquery-asGradient/dist/jquery-asGradient.js"></script>
	<script src="src/plugins/jquery-asColorPicker/jquery-asColorPicker.js"></script>
	<script src="vendors/scripts/colorpicker.js"></script>

    <script>
        $('#checking-add-modal').on('shown.bs.modal', function () {
            $('#checking_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_checking_name').focus();
        })


        $(".showmodal").click(function () {
            $("#checking-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');

            // $.ajax({
            //     type: 'POST',
            //     url: 'ajax_search.php?getcheckingedit=1&id=' + id,
            //     success: function (msg) {
            //         $("#editmodaldetail").html(msg);
            //     }
            // })
            
            $("#tempModId").val(id);
            
            $("#edit-modal" + id).modal('show');
        });
        
        function updateForm(id) {
            $("#form" + id ).submit();
        }
        
        function changasc() {
            var id = $("#tempModId").val();
            
            var val = $("#tempclr" + id).val();
            
            $("#edit_checking_color" + id).val(val);
        }
        
        
        $("body").click(function() {
            changasc()
        });
        
        
        
        function save_checking() {
            if ($("#checking_name").val() == "") {
                $("#checking_name").focus();
                message_noload('warning', 'Checking Name Required!', 1000);
                return false;
            } else {
                var form = $("#checking_addForm").serialize()
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?save_checking=1',
                    data: form,
                    success: function (msg) {
        
                        var json = $.parseJSON(msg);
                        if (json.result == 'success') {
                            message_reload('success', 'Checking Saved');
                        } else {
                            message_error();
                        }
                    }
                })
            }
        }
    </script>

</html>