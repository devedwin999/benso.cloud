<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['updateForm'])) {

    $data = array(
        'brand_name' => filter_var($_POST['edit_brand_name'], FILTER_SANITIZE_STRING),
        'brand_code' => filter_var($_POST['edit_brand_code'], FILTER_SANITIZE_STRING),
        'username' => filter_var($_POST['edit_user_name'], FILTER_SANITIZE_STRING),
        'password' => filter_var($_POST['edit_password'], FILTER_SANITIZE_STRING),
        'approvals' => implode(',', $_POST['edit_approvals']),
    );

    $qry = Update('brand', $data, " WHERE id = '" . $_REQUEST['edit_brand_id'] . "'");

    timeline_history('Update', 'brand', $_REQUEST['edit_brand_id'], 'Brand Details Updated.');
    
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
    <title>BENSO GARMENTING - Brand</title>

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
                <strong>Success!</strong> Brand Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Brand Updated.
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
                    
                    <?php if(MAS_BRAND!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <?php if(MAS_BRAND_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php } ?>
                        <h4 class="text-blue h4">Manage Brands
                            <p class="mb-30 text-danger">
                                <i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info"
                                    style="font-size: 15px;"></i> Click on the Status To change
                            </p>
                        </h4>
                    </div>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap dataTable no-footer dtr-inline collapsed">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Brand Name</th>
                                    <th>Brand Code</th>
                                    <th>Approvals</th>
                                    <th>User Name</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM brand ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    if(!empty($sql['approvals'])) {
                                    $bnf = mysqli_query($mysqli, "SELECT * FROM mas_approval WHERE id IN (". $sql['approvals'] .")");
                                    $op = 1;
                                    while($app = mysqli_fetch_array($bnf)) {
                                        $uii[$x][] = $op.'. '.$app['name'];
                                    $op++; }
                                } else { $uii[$x] = ''; }
                                ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['brand_name']; ?></td>
                                        <td><?= $sql['brand_code']; ?></td>
                                        <td><?= $uii[$x] ? implode('<br>', $uii[$x]): '-'; ?></td>
                                        <td><?= $sql['username'] ? $sql['username'] : '-'; ?></td>
                                        <td><?= $sql['password'] ? $sql['password'] : '-'; ?></td>
                                        <td>
                                            <?php if ($sql['is_active'] == 'active') { ?>
                                                <span class="badge badge-success" onclick="changeStatus(<?= $sql['id']; ?>,'brand','inactive')">Active</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger" onclick="changeStatus(<?= $sql['id']; ?>,'brand','active')">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(MAS_BRAND_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    
                                                    <?php } if(MAS_BRAND_DELETE==1) { if ($sql['can_delete'] == 'yes') { ?>
                                                            <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'brand')"><i class="dw dw-delete-3"></i> Delete</a>
                                                    <?php } } ?>
                                                </div>
                                            </div>
                                            
                                            <div class="modal fade" id="edit-modal<?= $sql['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myLargeModalLabel">Edit Brand</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        </div>
                                                        <form method="post" autocomplete="off" enctype="multipart/form-data">
                                                            
                                                            <div class="modal-body">
                                                                <div class="row" id="editmodaldetail">
                                                                    <?php
                                                                    
                                                                        $ass = "SELECT * FROM brand WHERE id='" . $sql['id'] . "'";
                                                                        $asss = mysqli_query($mysqli, $ass);
                                                                        $asd = mysqli_fetch_array($asss);
                                                                    
                                                                    
                                                                        print '<div class="col-md-12">';
                                                                        print '<label>Brand Name <span class="text-danger">*</span></label>';
                                                                        print '<div class="form-group">';
                                                                        print '<input type="text" class="form-control d-cursor" name="edit_brand_name" id="edit_brand_name" placeholder="Brand Name" value="' . $asd['brand_name'] . '" required>';
                                                                        print '</div>';
                                                                        print '</div>';
                                                                    
                                                                        print '<div class="col-md-12">';
                                                                        print '<label>Brand Code <span class="text-danger">*</span></label>';
                                                                        print '<div class="form-group">';
                                                                        print '<input type="text" class="form-control" name="edit_brand_code" id="edit_brand_code" placeholder="Brand Code" value="' . $asd['brand_code'] . '" required>';
                                                                        print '<input type="hidden" name="edit_brand_id" id="edit_brand_id" value="' . $asd['id'] . '">';
                                                                        print '</div>';
                                                                        print '</div>';
                                                                    
                                                                        print '<div class="col-md-12">';
                                                                        print '<label>Brand Code <span class="text-danger">*</span></label>';
                                                                        print '<div class="form-group">';
                                                                        print '<select name="edit_approvals[]" id="edit_approvals'. $sql['id'] .'" class="form-control custom-select2" style="width:100%" required multiple>';
                                                                        print select_dropdown_multiple('mas_approval', array('id', 'name'), 'id ASC', $sql['approvals'], '', '1');
                                                                        print '</select>';
                                                                        print '</div>';
                                                                        print '</div>';
                                                                    
                                                                        print '<div class="col-md-12">';
                                                                        print '<label>User Name <span class="text-danger">*</span></label>';
                                                                        print '<div class="form-group">';
                                                                        print '<input type="text" class="form-control" name="edit_user_name" id="edit_user_name" placeholder="User Name" value="' . $asd['username'] . '" required>';
                                                                        print '</div>';
                                                                        print '</div>';
                                                                    
                                                                        print '<div class="col-md-12">';
                                                                        print '<label>Password <span class="text-danger">*</span></label>';
                                                                        print '<div class="form-group">';
                                                                        print '<input type="text" class="form-control" name="edit_password" id="edit_password" placeholder="Password" value="' . $asd['password'] . '" required>';
                                                                        print '</div>';
                                                                        print '</div>';
                                                                        
                                                                        ?>
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
                                        </td>
                                    </tr>
                                    <?php $x++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                

            </div>
            <?php
            $modals = ["brand-add-modal"];
            include('modals.php');
            include('includes/footer.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $('#brand-add-modal').on('shown.bs.modal', function () {
            $('#brand_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_brand_name').focus();
        })


        $(".showmodal").click(function () {
            $("#brand-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');

            // $.ajax({
            //     type: 'POST',
            //     url: 'ajax_search.php?getbrandedit=1&id=' + id,
            //     success: function (msg) {
            //         $("#editmodaldetail").html(msg);
            //     }
            // })

            $("#edit-modal" + id).modal('show');
        })
    </script>
    
    

</html>