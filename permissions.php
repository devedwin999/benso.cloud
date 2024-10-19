<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'brand_name' => filter_var($_POST['edit_brand_name'], FILTER_SANITIZE_STRING),
        'brand_code' => filter_var($_POST['edit_brand_code'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Update('brand', $data, " WHERE id = '" . $_REQUEST['edit_brand_id'] . "'");

    $_SESSION['msg'] = "updated";

    header("Location:brand.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - User Permissions</title>

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
    input {
        width: 10px !important;
    }
</style>

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <form method="POST" id="pForm">
                    <div class="card-box mb-30">
                    
                        <?php if(APP_PERMISSIONS!=1) { action_denied(); exit; } ?>
                    
                        <div class="pd-20">
                            <h4 class="text-blue h4">Manage User Permissions
                            </h4>
                        </div>
                        <div class="pd-20">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="fieldrequired">User Group * <i class="icon-copy fa fa-plus-circle" data-toggle="modal" data-target="#add-Modal"></i></label>
                                    
                                    <select name="user_group" id="user_group1" class="custom-select2 multiple-select" style="width: 100%" onchange="getUserPerms()">
                                        <?= select_dropdown('user_group', array('id', 'group_name'), 'group_name ASC', '', '', ''); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">&nbsp;</label>
                                    <br>
                                    <input type="button" class="btn btn-primary" onclick="save_permissions()" value="Update">
                                </div>
                                
                                <?php if($logUser == 102) { ?>
                                    <div class="col-md-3">
                                        <label for="fieldrequired">Permission Name</label>
                                        
                                        <input type="text" id="new_perm" class="form-control w-100" placeholder="Permission Name" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">&nbsp;</label>
                                        <br>
                                        <input type="button" class="btn btn-primary" onclick="new_permission()" value="Add Permission">
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
    
                        <div class="pd-20" id="divPermissionItems">
    
                        </div>
                    </div>
                </form>
                
                <div class="modal fade" id="add-Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-top">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">New User Group</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" id="var_modalform" enctype="multipart/form-data">
            
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Group Name :</label>
                                            <input type="text" class="form-control" id="group_name" autocomplete="off" style="width: 100% !important;">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-outline-primary" onclick="saveUserGroup()" >Save</button>
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

    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        function new_permission() {
            
            var vl = $("#new_perm").val();
            
            if(vl=="") {
                message_noload('error', 'Enter Permission Name!');
                return false;
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action2.php?newPermission=1&perm='+ vl,
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        
                        if(json.result==0) {
                            message_reload('success', 'Permission Created!', 2500);
                        } else {
                            message_reload('error', 'Error!', 2500);
                        }
                    }
                })
            }
        }
    </script>
    
    <script>
        function main_cbox() {
            var a = $(".main_cbox").is(':checked');
            if(a==true) {
                $(".cbox").prop('checked', true);
            } else {
                $(".cbox").prop('checked', false);
            }
        }
    </script>
    
    <script>
        function saveUserGroup() {
            var name = $("#group_name").val();
            $.ajax({
                type: 'POST',
                url: 'permission_ajax.php?search_type=saveUserGroup&name='+name,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                        if (json.msg == 'saved') {
                            swal({
                                type: 'success',
                                title: 'Saved',
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
        }
    </script>
    
    <script>
        function save_permissions() {
            
            if($("#user_group1").val()=="")
            {
                message_noload('warning', 'Select User Group!', 1000);
                return false;
            }
            
            // var form = $("#pForm").serialize();
            swal({
                title: 'Are you sure?',
                text: "User can access all the checked permissions!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Proceed!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    
                    $("#overlay").fadeIn(100);
                    
                    var trueVal = [];
                    var falsVal = [];
                    $(".all_cbox").each(function() {
                        
                        var ch = $(this).is(':checked');
                        var value = $(this).val();
                        if(ch == true) {
                            trueVal.push(value);
                        } else {
                            falsVal.push(value);
                        }
                    });

                    var user_group = $("#user_group1").val();

                    var form = 'falsVal=' + falsVal + '&trueVal=' + trueVal + '&user_group=' + user_group;

                    $.ajax({
                        type: 'POST',
                        url: 'permission_ajax.php?search_type=savePermissions',
                        data : form,
                        success: function (msg) {
                            
                            var json = $.parseJSON(msg);
                            if (json.msg == 'saved') {
                                $("#overlay").fadeOut(100);
                                swal({
                                    type: 'success',
                                    title: 'Saved',
                                    showConfirmButton: true,
                                    timer: 1500
                                }).then(function () { location.reload();});
                            } else {
                                $("#overlay").fadeOut(100);
                                swal( 'Something went wrong', '', 'error');
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
        }
    </script>

    <script>

        // $(document).ready(function() {
        //     getUserPerms();
        // })


        function getUserPerms() {

            var id = $("#user_group1").val();
            
            $("#divPermissionItems").html('<div class="spinner-grow" role="status"></div> Fetching Permissions...');
            $("#overlay").fadeIn(100);
            if(id=="") {
                $("#divPermissionItems").html('');
                $("#overlay").fadeOut(300);
                return false;
            }
            $.ajax({
                type: 'POST',
                url: 'permission_ajax.php?search_type=getPermissionList&id=' + id,

                success: function (msg) {
                    
                    setTimeout(function() {
                        $("#divPermissionItems").html(msg);
                        $("#overlay").fadeOut(300);
                    }, 300);
                }
            })
        }
    </script>
    
    <script>
        function modClick(id) {
            var ch = $(".mod_"+ id).is(':checked');
            
            if(ch==true) {
                $(".mod_cs"+ id).prop('checked', true);
            } else {
                $(".mod_cs"+ id).prop('checked', false);
            }
        }
        
        function menuClick(id) {
            var ch = $(".sub_"+ id).is(':checked');
            
            if(ch==true) {
                $(".sub_cs"+ id).prop('checked', true);
            } else {
                $(".sub_cs"+ id).prop('checked', false);
            }
        }
    </script>
    
    <script>
        function checkDashboard(ch, not) {
            var ch = $("."+ ch + 'Dash').is(':checked');
            
            if(ch==true) {
                $("." + not + 'Dash').prop('checked', false);
            } else {
                $("." + not + 'Dash').prop('checked', true);
            }
        }
    </script>

</html>
































