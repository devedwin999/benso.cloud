<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['saveBtn'])) {

    $data = array(
        'note_name' => filter_var($_POST['note_name'], FILTER_SANITIZE_STRING),
        'invoice_note' => filter_var($_POST['invoice_note'], FILTER_SANITIZE_STRING),
        'note_name_multiple' => filter_var($_POST['note_name_multiple'], FILTER_SANITIZE_STRING),
        'created_by' => $logUser,
        'created_unit' => $logUnit
    );

    $qry = Insert('mas_notes', $data);

    $_SESSION['msg'] = "saved";

    header("Location:mas_notes.php");

    exit;
}

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'note_name' => filter_var($_POST['edit_note_name'], FILTER_SANITIZE_STRING),
        'invoice_note' => filter_var($_POST['edit_invoice_note'], FILTER_SANITIZE_STRING),
        'note_name_multiple' => filter_var($_POST['edit_note_name_multiple'], FILTER_SANITIZE_STRING),     
    );

    $qry = Update('mas_notes', $data, " WHERE id = '" . $_REQUEST['edit_note_id'] . "'");

    $_SESSION['msg'] = "updated";
    header("Location:mas_notes.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>KRV Technologies - Notes</title>

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
                <strong>Success!</strong> Notes Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Notes Updated.
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
                    <?php //if(MAS_NOTE!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <?php //if(MAS_NOTE_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php //} ?>
                        <h4 class="text-blue h4">Notes Details
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
                                    <th>Note Name</th> 
                                    <th>Invoice Note</th>
                                    <th>Notes</th>          
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM mas_notes ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['note_name']; ?></td>
                                        <td><?= $sql['invoice_note']; ?></td>
                                        <td><?= $sql['note_name_multiple']; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php //if(MAS_NOTE_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php //} if(MAS_NOTE_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'mas_notes')"><i class="dw dw-delete-3"></i> Delete</a>
                                                    <?php //} ?>
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
                                <h4 class="modal-title" id="myLargeModalLabel">Add New Notes</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="Note">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Notes Name <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                            <select class="custom-select2 form-control" id="note_name" name="note_name" style="width: 468px;">
                                                    <option value="Fabric">Fabric</option>
                                                    <option value="Store">Store</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Iinvoice <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                            <select class="custom-select2 form-control" id="invoice_note" name="invoice_note" style="width: 468px;">
                                                    <option value="DC">DC</option>
                                                    <option value="PO">PO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Notes Name<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="note_name_multiple" id="note_name_multiple" placeholder="Notes" required>
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
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Notes</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="note">
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
        $('#add-modal').on('shown.bs.modal', function () {
            $('#note_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_note_name').focus();
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
                url: 'ajax_search2.php?getnoteedit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                    
                    $("#edit_note_name").select2();
                    $("#edit_invoice_note").select2();
                }
            })

            $("#edit-modal").modal('show');
        })
    </script>
    <script>
        function delete_note(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action2.php?delete_note=1&id=' + id,
                        success: function (msg) {

                            var json = $.parseJSON(msg);

                            if (json.result == 0) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
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
                } else {
                    swal(
                        'Cancelled',
                        '',
                        'error'
                    )
                }
            })
        };
    </script>

</html>