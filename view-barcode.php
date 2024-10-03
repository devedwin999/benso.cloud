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
    <title>Benso Garmenting - Barcode List</title>

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
                <strong>Success!</strong> Barcode Saved.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Barcode Updated.
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
                    <?php if(CUTTING_QR!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">List Of Barcode
                        <?php if(CUTTING_QR_ADD ==1) { ?>
                            <a class="btn btn-outline-primary showmodal" href="barcode.php" style="float: right;">+ Add an Barcode</a>
                        <?php } ?>
                        </h4>
                    </div>

                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Style Image</th>
                                    <th>Entry Number</th>
                                    <th>Entry Date</th>
                                    <th>BO</th>
                                    <th>Style No</th>
                                    <th>Combo</th>
                                    <th>Part</th>
                                    <th>Color</th>
                                    <th>Lay</th>
                                    <th>Employee</th>
                                    <th>Fabric</th>
                                    <th>Cutting Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.color_id, b.part_id, b.combo_id, c.item_image ";
                                $qry .= " FROM cutting_barcode a ";
                                $qry .= " LEFT JOIN sod_part b ON a.sod_part = b.id ";
                                $qry .= " LEFT JOIN sales_order_detalis c ON a.style = c.id ";
                                
                                
                                if ($_SESSION['login_role'] != 1) {
                                    $qry .= " WHERE a.created_unit='" . $logUnit . "' ";
                                }
                                
                                $qry .= " ORDER BY a.id DESC ";
                                
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['item_image'] ? viewImage($sql['item_image'], 35) : '-'; ?></td>
                                        <td><?= $sql['entry_number']; ?></td>
                                        <td><?= date('d-m-y', strtotime($sql['entry_date'])); ?></td>
                                        <td><?= sales_order_code($sql['order_id']); ?></td>
                                        <td><?= sales_order_style($sql['style']); ?></td>
                                        <td><?= color_name($sql['combo_id']); ?></td>
                                        <td><?= part_name($sql['part_id']); ?></td>
                                        <td><?= color_name($sql['color_id']); ?></td>
                                        <td><?= $sql['lay_number']; ?></td>
                                        <td><?= ($sql['employee']>0) ? employee_name($sql['employee']) : '-'; ?></td>
                                        <td><?= ($sql['fabric']>0) ? fabric_name($sql['fabric']) : '-'; ?></td>
                                        <td><?= company_code($sql['created_unit']); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" role="button" data-toggle="dropdown"> <i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <?php if(CUTTING_QR_GENERATE ==1) { ?>
                                                    <a class="dropdown-item" href="generate-barcode.php?id=<?= $sql['id'] ?>&lay=<?= $sql['lay_number'] ?>"> <i class="icon-copy fa fa-qrcode" aria-hidden="true"></i> Generate QR Code</a>
                                                <?php } if(CUTTING_QR_EDIT==1) { ?>
                                                    <a class="dropdown-item" href="edit-barcode.php?id=<?= $sql['id'] ?>&lay=<?= $sql['lay_number'] ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                <?php } if(CUTTING_QR_DELETE==1) { ?>
                                                    <a class="dropdown-item" onclick="delete_cutting_barcode(<?= $sql['id']; ?>)"><i class="dw dw-delete-3"></i> Delete</a>
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

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve Sales Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="is_approved_id" id="is_approved_id">
                    <select name="is_approved" id="is_approved" class="form-control">
                        <option value=""></option>
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary soapprove">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <?php
        
        $modals = ["image-modal"];
        include('modals.php'); 
        include('includes/end_scripts.php');
    ?>

    <script>
        function delete_cutting_barcode(id)
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
                            type : 'POST',
                            url : 'ajax_action.php?delete_cutting_barcode=1&id=' + id,
                            
                            success : function(msg)
                            {
                                var json = $.parseJSON(msg);
                                
                                if(json.res=='success')
                                {
                                    message_reload('success', 'Barcode Deleted');
                                } else {
                                    message_noload('warning', 'Somethig Went Wrong!', 1000);
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
        $(".is_approved_id").click(function () {
            $("#is_approved_id").val($(this).attr('data-id'));
            $("#exampleModal").modal('show');
        });
        
        $(".soapprove").click(function () {

            var id = $("#is_approved_id").val();
            var is_approved = $("#is_approved").val();

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?approve_so=1&id=' + id + '&is_approved=' + is_approved,
                success: function (msg) {
                    var json = $.parseJSON(msg);

                    if (json.result == 'saved') {
                        swal(
                            {
                                type: 'success',
                                text: 'Action Changed!',
                                timer: 1500
                            }
                        ).then(
                            function () {
                                location.reload();
                            })
                    } else {
                        swal(
                            {
                                type: 'error',
                                text: 'Something Went Wrong!',
                                timer: 1500
                            }
                        )
                    }
                }
            })
        });
    </script>

    <script>
        function showimage(id) {
            var val = $("#dsff" + id).val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?showimage=1&img=' + val,
                
                success: function (msg) {
                    $("#img_space").html(msg);
                }
            })
            
            $("#image-modal").modal('show');
        };
    </script>
</body>

</html>