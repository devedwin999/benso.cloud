<?php
include ("includes/connection.php");
include ("includes/function.php");

include ("includes/perm.php");

$qryz = mysqli_query($mysqli, "SELECT * FROM stockgroup WHERE id ='" . $_GET['id'] . "' ORDER BY id DESC");
$sql = mysqli_fetch_array($qryz);
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Stock Group</title>

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
    .ui-menu-item-wrapper {
        background-color: #eae8e8 !important;
        padding: 10px;
        width: 20% !important;
        border-bottom: 1px solid #c6c5c5;
    }


    .mw-150 {
        min-width: 150px
    }

    .hov_show {
        display: none;
    }

    .td_edDl:hover .hov_show {
        display: block;
    }

    /*.td_edDl {*/
    /*    display: flex;*/
    /*}*/


    @media (max-width: 479px) {
        /*.td_edDl {*/
        /*    min-width: 50px;*/
        /*}*/
    }
</style>

<body>

    <?php include ('includes/header.php'); ?>

    <?php include ('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    
                   <?php if(FAB_STOCK_GROUP_ADD!=1) { action_denied(); exit; } ?>
                   
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="stockgroup.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Stock Group List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left"><h4 class="text-blue h4">Stock Group </h4></div>
                    </div>
                    <form id="stockgroupform" method="post" autocomplete="off">

                        <input type="hidden" name="stockgroupid" id="stockgroupid" value="<?= $sql['id']; ?>">
                        
                        <div class="row">                           
                            <div class="col-md-3">
                                <label class="col-form-label">Group Name<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="groupname" id="groupname" class="form-control" value="<?= $sql['groupname']; ?>" placeholder="Group Name" required>
                                    <input type="hidden" name="stockgroup_id" id="stockgroup_id" value="<?= isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-form-label">Assigned Users<span class="text-danger">*</span></label>
                                <div class="form-group">                                   
                                    <select name="assigneduser[]" id="assigneduser" class="custom-select2 form-control" style="width:100%"  required multiple>
                                        <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['assigneduser'], 'WHERE is_active="active"', '`'); ?>                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="col-form-label">&nbsp;</label>
                                <div class="form-group">
                                    <a class="btn btn-outline-primary stockgroup_add" id="stockgroup" >Save Stock Group Cancel</a>
                                    <a class="btn btn-outline-secondary" onclick="window.location.href='stockgroup.php'">Go Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                <hr>
                     
            </div>
            <?php include ('includes/footer.php'); ?>
        </div>
    </div>

    <?php include ('includes/end_scripts.php'); ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.stockgroup_add').click(function () {
                
                if($("#groupname").val() == "") {
                    $("#groupname").focus();
                    message_noload('error', 'Group Name Required!', 2000);
                    return false;
                } else if($("#assigneduser").val() == "") {
                    $("#assigneduser").focus();
                    message_noload('error', 'Select Users!', 2000);
                    return false;
                } else {
                    var form = $('#stockgroupform').serialize();
                    
                    $.ajax({
                        url: 'ajax_action2.php?stockgroup_add=1',
                        data : form,
                        type: 'post',
                        success: function (response) {
                            var json = $.parseJSON(response);
                            
                            if(json.result==0) {
                                message_redirect('success', 'Group Name Added!', 2000, 'stockgroup.php');
                            } else {
                                message_error();
                            }
                        }
                    });
                }
            });

        });
    </script>


</body>

</html>