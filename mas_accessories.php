<?php
include ("includes/connection.php");
include ("includes/function.php");

include ("includes/perm.php");

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'acc_name' => filter_var($_POST['edit_acc_name'], FILTER_SANITIZE_STRING),
        'acc_type' => filter_var($_POST['edit_acc_type'], FILTER_SANITIZE_STRING),
        'excess' => $_POST['edit_excess'],
        'purchase_uom' => $_POST['edit_purchase_uom'],
        'consumption_uom' => $_POST['edit_consumption_uom'],
        'purchase_unit' => $_POST['edit_purchase_unit'],
        'uom_qty' => $_POST['edit_uom_qty'],
    );

    $qry = Update('mas_accessories', $data, " WHERE id = '" . $_REQUEST['edit_id'] . "'");

    timeline_history('Update', 'mas_accessories', $_REQUEST['edit_id'], 'Accessories Updated.');

    $_SESSION['msg'] = "updated";

    header("Location:mas_accessories.php");

    exit;
}


?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Accessories</title>

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
    include ('includes/header.php');
    include ('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Accessories Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Accessories Updated.
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

                    <?php page_spinner();
                    if (MAS_ACCESSORIES != 1) {
                        action_denied();
                        exit;
                    } ?>

                    <div class="pd-20">

                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-info" href="mod_masters.php"><i class="fa fa-home"
                                    aria-hidden="true"></i> Masters</a>

                            <?php if (MAS_ACCESSORIES_ADD == 1) { ?>
                                <a class="btn btn-outline-primary showmodal" href="javascript:void(0)" style="float: right;"><i class="fa-plus fa"></i> Add New</a>
                            <?php } ?>
                        </div>

                        <h4 class="text-blue h4">Manage Accessories 
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
                                    <th>Accessories Type</th>
                                    <th>Accessories Name</th>
                                    <th>Purchase UOM</th>
                                    <th>Consumption UOM</th>
                                    <th>Purchase Unit</th>
                                    <th>Purchase UOM Qty</th>
                                    <th>Excess</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*,b.type_name FROM mas_accessories a LEFT JOIN mas_accessories_type b ON a.acc_type = b.id ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['type_name'] ? $sql['type_name'] : '-'; ?></td>
                                        <td><?= $sql['acc_name'] ? $sql['acc_name'] : '-'; ?></td>
                                        <td><?= $sql['purchase_uom'] ? uom_name($sql['purchase_uom']) : '-'; ?></td>
                                        <td><?= $sql['consumption_uom'] ? uom_name($sql['consumption_uom']) : '-'; ?></td>
                                        <td><?= $sql['purchase_unit'] ? $sql['purchase_unit'] : '-'; ?></td>
                                        <td><?= $sql['uom_qty'] ? $sql['uom_qty'] : '-'; ?></td>
                                        <td><?= $sql['excess'] ? $sql['excess'] : '-'; ?></td>
                                        <td>
                                            <?php if ($sql['can_edit_delete'] == 'yes') { ?>
                                                <div class="dropdown">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                        role="button" data-toggle="dropdown">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">

                                                        <?php if (MAS_ACCESSORIES_EDIT == 1) { ?>
                                                            <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>"
                                                                href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                        <?php }
                                                        if (MAS_ACCESSORIES_DELETE == 1) { ?>
                                                            <a class="dropdown-item"
                                                                onclick="delete_data(<?= $sql['id']; ?>, 'mas_accessories')"><i
                                                                    class="dw dw-delete-3"></i> Delete</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } else {
                                                print '<p>-</p>';
                                            } ?>
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
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Accessories</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">

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
            $modals = ["accessories-add-modal"];
            include ('modals.php');
            include ('includes/footer.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include ('includes/end_scripts.php'); ?>

    <script>
        $('#accessories-add-modal').on('shown.bs.modal', function () {
            $('#acc_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_acc_name').focus();
        })


        $(".showmodal").click(function () {
            $("#accessories-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getAccessoriesedit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);

                    $(".sel2").each(function () {
                        $(this).select2({
                            dropdownParent: $('#edit-modal')
                        });
                    });
                    $("#edit-modal").modal('show');
                }
            })

        })
    </script>

    <script>
        function save_accessories1(element) {

            if ($("#acc_name").val() == "") {
                $("#acc_name").focus();
                message_noload('warning', 'Accessories Name Required!', 1000);
                return false;
            } else if ($("#acc_type").val() == "") {
                message_noload('warning', 'Accessories Type Required!', 1000);
                return false;
            } else if ($("#excess").val() == "") {
                $("#excess").focus();
                message_noload('warning', 'Excess Percentage Required!', 1000);
                return false;
            } else if ($("#purchase_uom").val() == "") {
                $("#purchase_uom").focus();
                message_noload('warning', 'Purchase UOM Required!', 1000);
                return false;
            } else if ($("#consumption_uom").val() == "") {
                $("#consumption_uom").focus();
                message_noload('warning', 'Consumption UOM Required!', 1000);
                return false;
            } else if ($("#uom_qty").val() == "") {
                $("#uom_qty").focus();
                message_noload('warning', 'UOM Qty Required!', 1000);
                return false;
            } else {
                $(element).html('<i class="fa fa-spinner"></i> Saving..').attr('disabled', true);

                $("#overlay").fadeIn(100);
                var form = $("#accessories_addForm").serialize()
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?save_accessories=1',
                    data: form,
                    success: function (msg) {
                        $("#overlay").fadeOut(500);

                        var json = $.parseJSON(msg);
                        if (json.result == 'success') {
                            $(element).html('Saved!').attr('disabled', false);
                            message_reload('success', 'Accessories Saved');
                        } else {
                            message_error();
                        }
                    }
                })
            }
        }
    </script>

    <script>
        $("#purchase_uom, #consumption_uom").change(function () {

            var consumption_uom = $("#consumption_uom").val();
            var purchase_uom = $("#purchase_uom").val();

            if (purchase_uom == consumption_uom) {
                $("#uom_qty").val(1).attr('readonly', true);
            } else {
                $("#uom_qty").attr('readonly', false);
            }
        });

        function edit_uom_valid() {
            var consumption_uom = $("#edit_consumption_uom").val();
            var purchase_uom = $("#edit_purchase_uom").val();

            if (purchase_uom == consumption_uom) {
                $("#edit_uom_qty").val(1).attr('readonly', true);
            } else {
                $("#edit_uom_qty").attr('readonly', false);
            }
        };
    </script>

</html>