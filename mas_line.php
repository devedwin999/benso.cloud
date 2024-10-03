<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'line_name' => filter_var($_POST['ed_line_name'], FILTER_SANITIZE_STRING),
        'process' => filter_var($_POST['ed_process'], FILTER_SANITIZE_STRING),
        'pay_type' => filter_var($_POST['ed_pay_type'], FILTER_SANITIZE_STRING),
        'cost_generator' => ($_POST['ed_cost_generator'] != "") ? implode(',', $_POST['ed_cost_generator']) : '',
    );

    $qry = Update('mas_line', $data, " WHERE id = '" . $_REQUEST['edit_id'] . "'");

    $_SESSION['msg'] = "updated";

    header("Location:mas_line.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Line</title>

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
                <strong>Success!</strong> Line Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Line Updated.
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
                    
                    <?php if(MAS_LINE!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <?php if(MAS_LINE_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php } ?>
                        <h4 class="text-blue h4">Manage Line
                            <p class="mb-30 text-danger">
                                <i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info" style="font-size: 15px;"></i> Click on the Status To change
                            </p>
                        </h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Line Name</th>
                                    <th>Process</th>
                                    <th>Pay Type</th>
                                    <th>Cost Generators</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM mas_line ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['line_name']; ?></td>
                                        <td><?php foreach(explode(',', $sql['process']) as $pss) { print process_name($pss).'<br>'; } ?></td>
                                        <td><?= ($sql['pay_type']==1) ? 'Shift' : 'Pcs Rate'; ?></td>
                                        <td><?= empty($sql['cost_generator']) ? '-' : implode('<br>', emp_name($sql['cost_generator'])); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" role="button" data-toggle="dropdown"> <i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(MAS_LINE_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(MAS_LINE_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'mas_line')"><i class="dw dw-delete-3"></i> Delete</a>
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
                
                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Line</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="mas_line">
                                <div class="modal-body">
                                    <div class="row" id="editmodaldetail"></div>
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
            $modals = ["line-add-modal"];
            include('modals.php');
            include('includes/footer.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $('#line-add-modal').on('shown.bs.modal', function () {
            $('#line_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#ed_line_name').focus();
        })


        $(".showmodal").click(function () {
            $("#line-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {
            
            var id = $(this).attr('data-id');
            $("#overlay").fadeIn(100);
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getLineedit=1&id=' + id,
                success: function (msg) {
                    $("#overlay").fadeOut(500);
                    $("#editmodaldetail").html(msg);
                    $("#ed_cost_generator").select2();
                    $("#ed_process").select2();
                    $("#ed_pay_type").select2();
                }
            })

            $("#edit-modal").modal('show');
        });
        
        
        function save_line() {
            if ($("#line_name").val() == "") {
                message_noload('warning', 'line Name Required!', 1000);
                return false;
            } else {
                var form = $("#lineForm").serialize();
                $("#overlay").fadeIn(100);
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?save_line=1',
                    data: form,
                    success: function (msg) {
                        
                        var json = $.parseJSON(msg);
                        $("#overlay").fadeOut(500);
                        if (json.result == 'success') {
                            message_reload('success', 'line Saved');
                        } else {
                            message_error();
                        }
                    }
                })
            }
        }
    </script>

</html>