<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['saveBtn'])) {

    $data = array(
        'bank_name' => filter_var($_POST['bank_name'], FILTER_SANITIZE_STRING),
        'account_no' => filter_var($_POST['account_no'], FILTER_SANITIZE_STRING),
        'bank_ifsc' => filter_var($_POST['bank_ifsc'], FILTER_SANITIZE_STRING),
        'bank_branch' => filter_var($_POST['bank_branch'], FILTER_SANITIZE_STRING),
        'bank_other' => filter_var($_POST['bank_other'], FILTER_SANITIZE_STRING),
        'created_by' => $logUser,
        'created_unit' => $logUnit
    );

    $qry = Insert('mas_bank', $data);

    $_SESSION['msg'] = "saved";

    header("Location:mas_bank.php");

    exit;
}

if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'bank_name' => filter_var($_POST['edit_bank_name'], FILTER_SANITIZE_STRING),
        'account_no' => filter_var($_POST['edit_account_no'], FILTER_SANITIZE_STRING),
        'bank_ifsc' => filter_var($_POST['edit_bank_ifsc'], FILTER_SANITIZE_STRING),
        'bank_branch' => filter_var($_POST['edit_bank_branch'], FILTER_SANITIZE_STRING),
        'bank_other' => filter_var($_POST['edit_bank_other'], FILTER_SANITIZE_STRING), 
    );

    $qry = Update('mas_bank', $data, " WHERE id = '" . $_REQUEST['edit_bank_id'] . "'");

    $_SESSION['msg'] = "updated";
    header("Location:mas_bank.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>KRV Technologies - Bank</title>

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
                <strong>Success!</strong> Bank Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Bank Updated.
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
                    <?php if(MAS_BANK!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <?php if(MAS_BANK_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php } ?>
                        <h4 class="text-blue h4">Bank Details
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
                                    <th>Bank Name</th>
                                    <th>Account No</th>
                                    <th>Bank IFSC</th>                                   
                                    <th>Bank Branch</th>
                                    <th>Bank Other</th>                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM mas_bank ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $x; ?>
                                        </td>
                                       
                                        <td>
                                            <?= $sql['bank_name']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['account_no']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['bank_ifsc']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['bank_branch']; ?>
                                        </td>
                                        <td>
                                            <?= $sql['bank_other']; ?>
                                        </td>                                       
                                        
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(MAS_BANK_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(MAS_BANK_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'mas_bank')"><i class="dw dw-delete-3"></i> Delete</a>
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

                <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Add New Bank</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="bank">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Bank Name <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Account No <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account No" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Bank IFSC<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="bank_ifsc" id="bank_ifsc" placeholder="Bank ifsc" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Bank Branch<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="bank_branch" id="bank_branch" placeholder="Bank Branch" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Other<span class="text-danger"></span></label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="bank_other" id="bank_other" placeholder="other" required>
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
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Bank</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="bank">
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
            $('#bank_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_bank_name').focus();
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
                url: 'ajax_search2.php?getbankedit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                }
            })

            $("#edit-modal").modal('show');
        })
    </script>
    

</html>