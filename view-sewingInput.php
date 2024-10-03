<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Sewing Input List</title>

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

    <!-- sweetalert -->
    <link rel="stylesheet" type="text/css" href="src/plugins/sweetalert2/sweetalert2.css">

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
        if ($_SESSION['msg'] == 'updated') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Processing Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'added') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Processing Saved.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php }
        $_SESSION['msg'] = '';
        ?>
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Export Datatable start -->
                <div class="card-box mb-30">
                    <?php if(P_OUTWARD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <?php if(P_OUTWARD_ADD==1) { ?>
                            <a class="btn btn-outline-primary" href="s-input.php" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                        <?php } ?>
                        <h4 class="text-blue h4">Processing List</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>BO</th>
                                    <th>DC Number</th>
                                    <th>DC Date</th>
                                    <th>BO Number</th>
                                    <th>Input Type</th>
                                    <th>Input To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 if ($_SESSION['login_role'] != 1) {
                                    $wh = ' AND created_unit="' . $_SESSION['loginCompany'] . '"';
                                } else {
                                    $wh = '';
                                }
                                
                                $qry = "SELECT a.* FROM processing_list a WHERE a.type='sewing_input' $wh ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    if($sql['input_type']=='Supplier') {
                                        $typ = 'Supplier: '. supplier_name($sql['assigned_emp']);
                                    } else if($sql['input_type']=='Employee') {
                                        $typ = 'Employee: '. employee_name($sql['assigned_emp']);
                                    } else if($sql['input_type']=='Unit') {
                                        $typ = 'Unit: '. Company_name($sql['assigned_emp']);
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= sales_order_code($sql['order_id']); ?></td>
                                        <td><?= $sql['processing_code']; ?></td>
                                        <td><?= date('Y-m-d', strtotime($sql['created_date'])); ?></td>
                                        <td><?= sales_order_code($sql['order_id']); ?></td>
                                        <td><?= $sql['input_type']; ?></td>
                                        <td><?= ($sql['input_type']=='Line') ? line_name($sql['assigned_emp']) : ''; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    
                                                    <a class="dropdown-item" href="sinp_print.php?id=<?= $sql['id'] ?>" target="_blank"> <i class="icon-copy fa fa-print" aria-hidden="true"></i> Print Invoive</a>
                                                    <?php if(P_OUTWARD_EDIT==1) { ?>
                                                        <a class="dropdown-item" data-id="<?= $sql['id']; ?>" href="s-input.php?id=<?= $sql['id'] ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(P_OUTWARD_VIEW==1) { ?>
                                                        <!--<a class="dropdown-item" onclick="getProcessingDet(<?= $sql['id']; ?>)"><i class="dw dw-eye"></i> View</a>-->
                                                    <?php } if(P_OUTWARD_DELETE==1) {
                                                            if($sql['is_inhouse'] == NULL ) { ?>
                                                        <!--<a class="dropdown-item" onclick="delete_entry(<?= $sql['id']; ?>)"><i class="fa fa-trash"></i> Delete</a>-->
                                                    <?php } } ?>
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
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="viewModal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Processing List</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Sl.No</th>
                                <th>Bo.No</th>
                                <th>Style No</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Boundle No</th>
                                <th>Boundle Qty</th>
                                <th>Boundle QR</th>
                            </tr>
                        </tbody>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        function delete_entry(id)
        {
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
                            type:'POST',
                            url:'ajax_action.php?delete_processing_list=1&id='+id + '&type=process_outward',
                            success:function(msg){
                                swal(
                                    {
                                        position: 'center',
                                        type: 'success',
                                        title: 'Scanning cancelled!', 
                                        showConfirmButton: true,
                                        timer: 2000
                                    }
                                ).then(
                                    function () {
                                        window.location.href="view-processing.php";
                                    })
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
        function getProcessingDet(id) {

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getProcessingDet=1&id=' + id,
                success: function (msg) {
                    $("#tableBody").html(msg);
                }
            })
            $("#viewModal").modal('show');
        }
    </script>

    <!-- js -->

</body>

</html>