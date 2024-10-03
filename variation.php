<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_REQUEST['saveBtn'])) {

    $data = array(
        'variation_name' => filter_var($_POST['variation_name'], FILTER_SANITIZE_STRING),
        'style_id' => filter_var($_POST['style_id'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Insert('variation', $data);

    $inid = mysqli_insert_id($mysqli);
    if ($qry) {
        for ($m = 0; $m < count($_REQUEST['variation_value']); $m++) {
            $data = array(
                'variation_id' => $inid,
                'style_id' => $_POST['edit_style_id'],
                'type' => $_REQUEST['variation_value'][$m],
                'created_date' => date('Y-m-d H:i:s'),
            );
            $qry = Insert('variation_value', $data);
        }
    }

    $_SESSION['msg'] = "saved";

    header("Location:variation.php");

    exit;
}

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'variation_name' => filter_var($_POST['edit_variation_name'], FILTER_SANITIZE_STRING),
        'style_id' => filter_var($_POST['edit_style_id'], FILTER_SANITIZE_STRING),
        'created_date' => date('Y-m-d H:i:s')
    );

    $qry = Update('variation', $data, " WHERE id = '" . $_REQUEST['edit_variation_id'] . "'");
    // print_r(count($_REQUEST['edit_variation_value']));
    if ($qry) {
        for ($mx = 0; $mx < count($_REQUEST['edit_variation_value']); $mx++) {
            if (!empty($_REQUEST['edit_variation_value'][$mx])) {
                $data = array(
                    'variation_id' => $_REQUEST['edit_variation_id'],
                    'style_id' => $_REQUEST['edit_style_id'],
                    'type' => $_REQUEST['edit_variation_value'][$mx],
                    'created_date' => date('Y-m-d H:i:s'),
                );
                if (!empty($_REQUEST['v_valueId'][$mx])) {
                    $qry = Update('variation_value', $data, " WHERE id = '" . $_REQUEST['v_valueId'][$mx] . "'");
                } else {
                    $qry = Insert('variation_value', $data);
                }
            }
        }
    }

    $_SESSION['msg'] = "updated";

    header("Location:variation.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Benso Garments - Variation</title>

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
                <strong>Success!</strong> Variation Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Variation Updated.
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
                    
                    <?php if(MAS_SIZER!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add
                            New</a>
                        <h4 class="text-blue h4">Manage Variation
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
                                    <th> Variation Name</th>
                                    <th> Variation Value</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM variation ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x; ?>
                                        </td>
                                        <td>
                                            <?= $sql['variation_name']; ?>
                                        </td>
                                        <td>
                                            <a onclick="getcompanydetails(<?= $sql['id']; ?>)"><i class="dw dw-eye"></i>
                                                View</a>
                                        </td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'variation','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger"
                                                    onclick="changeStatus(<?= $sql['id']; ?>,'variation','active')">Inactive</span>
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
                                                    <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>"
                                                        href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php //if ($sql['can_delete'] == 'yes') { 
                                                        ?>
                                                    <form id="delete-company" method="post" autocomplete="off">
                                                        <input type="hidden">
                                                        <a class="dropdown-item"
                                                            onclick="delete_data(<?= $sql['id']; ?>, 'variation')"><i
                                                                class="dw dw-delete-3"></i> Delete</a>
                                                    </form>
                                                    <?php //} 
                                                        ?>
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
                                <h4 class="modal-title" id="myLargeModalLabel">New Variation</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="variation">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Variation Name <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="variation_name"
                                                    id="variation_name" placeholder="Variation Name" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12 d-none">
                                            <label>Select Style <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="form-control d-cursor" name="style_id" id="style_id">
                                                    <?= select_dropdown('style', array('id', 'style_name'), 'style_name ASC', '', '', ''); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Variation Value </label>
                                            <input type="hidden" value="1" id="addcount">
                                            <div class="form-group d-flex">
                                                <input type="text" class="form-control" name="variation_value[]"
                                                    id="variation_value" placeholder="Variation Value"
                                                    style="width: 90%;">

                                                <button type="button" class="btn btn-secondary" onclick="addmore()"><i
                                                        class="fa fa-plus"></i> </button>
                                            </div>

                                            <div id="morediv"></div>
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

                <script>
                    function addmore() {
                        var a = $("#addcount").val();
                        var lab = '<div class="form-group d-flex" id="newadddiv' + a + '"> <input type="text" class="form-control" name="variation_value[]" id="variation_value" placeholder="Variation Value" style="width: 90%;"> <button type="button" class="btn btn-danger" onclick="deletediv(' + a + ')"><i class="fa fa-trash"></i> </button> </div>';

                        $("#morediv").before(lab);
                        x = parseFloat(a) + 1;
                        $("#addcount").val(x);
                    }

                    function deletediv(id) {
                        $("#newadddiv" + id).remove();
                    }
                </script>

                <script>
                    function addmoreedit() {
                        var a = $("#editcountlist").val();
                        var lab = '<div class="form-group d-flex" id="newadddiv' + a + '"> <input type="text" class="form-control" name="edit_variation_value[]" id="edit_variation_value" placeholder="Variation Value" style="width: 90%;"> <input type="hidden" name="v_valueId[]" id="v_valueId" value=""> <button type="button" class="btn btn-danger" onclick="deletediv(' + a + ')"><i class="fa fa-trash"></i> </button> </div>';

                        $("#moredivedit").before(lab);
                        x = parseFloat(a) + 1;
                        $("#addcount").val(x);
                    }

                    function deletedivedit(id) {
                        $("#newadddiv" + id).remove();
                    }
                </script>

                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Variation</h4>
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

                <div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Variation Values</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                
                                <div class="modal-body">
                                    <div class="row" id="viewmodaldetail">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        $('#add-modal').on('shown.bs.modal', function () {
            $('#variation_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_variation_name').focus();
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
                url: 'ajax_search.php?getvariationedit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                }
            })

            $("#edit-modal").modal('show');
        })
    </script>

    <script>
        function getcompanydetails(id) {


            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getvariationlist=1&id=' + id,
                success: function (msg) {
                    $("#viewmodaldetail").html(msg);
                }
            })

            $("#view-modal").modal('show');
        }
    </script>

</html>