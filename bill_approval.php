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
    <title>BENSO GARMENTING - Bill Approval</title>

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

                <div class="card-box mb-30">
                    <?php if(BILL_APPROVAL!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a class="btn btn-outline-info" href="mod_accounts.php"><i class="fa fa-home" aria-hidden="true"></i> Accounts</a>

                            <?php 
                            
                            $ff = 10;
$oo = range(1, $ff);
$pa = array_map(fn($opp) => "$ff-$opp", $oo);

print_r($oo);
print_r($pa);
                            
                            ?>
                        </div>
                        <h4 class="text-blue h4">Bill Approval List</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Entry Number</th>
                                    <th>Entry Date</th> 
                                    <th>Bill Type</th>
                                    <th>Bill Number</th>
                                    <th>Bill Date</th>
                                    <th>Supplier</th>
                                    <th>Bill Amount</th>
                                    <th>Bill Image</th>
                                    <th>Rate Approved Image</th>
                                    <th>Remarks</th>
                                    <th>Approval Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.supplier_name ";
                                $qry .= " FROM bill_receipt a ";
                                $qry .= " LEFT JOIN supplier b ON a.supplier = b.id ";
                                $qry .= " WHERE status='passed' ";
                                $qry .= " ORDER BY a.id DESC ";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    
                                    $bord = array('pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger');
                                    $stat = array('pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger');
                                    ?>
                                    <tr class="d-none">
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['entry_number']; ?></td>
                                        <td><?= date('y-m-d', strtotime($sql['entry_date'])); ?></td>
                                        <td><?= $sql['bill_type']; ?></td>
                                        <td><?= $sql['bill_number']; ?></td>
                                        <td><?= date('y-m-d', strtotime($sql['bill_date'])); ?></td>
                                        <td><?= $sql['supplier_name']; ?></td>
                                        <td><?= $sql['bill_amount']; ?></td>
                                        <td><?php if($sql['bill_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['bill_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?php if($sql['approved_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['approved_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?= $sql['comments'] ?  $sql['comments'] : '-'; ?></td>
                                        <td>
                                            <span class="border border-<?= $bord[$sql['approval_status']]; ?> rounded text-<?= $stat[$sql['approval_status']]; ?>"><?= $sql['approval_status']; ?></span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"> <i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <?php if(BILL_APPROVAL_VIEW==1) { ?>
                                                    <a class="dropdown-item" href="add-prod-bill.php?id=<?= $sql['cost_id'] ?>&bid=<?= $sql['id']; ?>&typ=approve&from=bill_approval"><i class="fa fa-eye"></i> View & Approve</a>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $x++;
                                } ?>

                                <?php
                                
                                $qry = "SELECT *, sum(bill_amount) as bill_amount ";
                                $qry .= " FROM bill_passing a ";
                                $qry .= " LEFT JOIN bill_passing_det b ON b.bill_passing_id = a.id ";
                                $qry .= " ORDER BY a.id DESC ";
                                
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    
                                    $bord = array('Passed' => 'warning', 'Approved' => 'success');
                                    $stat = array('Passed' => 'warning', 'Approved' => 'success');
                                    
                                    if($sql['bill_type'] == 'Cost Generate') {
                                        $bill_from = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.entry_number, a.employee, b.entry_date FROM cost_generation_det a LEFT JOIN cost_generation b ON b.id = a.cost_generation_id WHERE a.id = '". $sql['bill_id'] ."'"));

                                        $bill_number = $bill_from['entry_number'];
                                        $bill_date = $bill_from['entry_date'];
                                        $supplier = employee_name($bill_from['employee']);
                                    } else {
                                        $bill_number = 0;
                                        $bill_date = 0;
                                        $supplier = 0;
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['entry_number']; ?></td>
                                        <td><?= date('d-m-Y', strtotime($sql['entry_date'])); ?></td>
                                        <td><?= $sql['bill_type']; ?></td>
                                        <td><?= $bill_number; ?></td>
                                        <td><?= date('d-m-Y', strtotime($bill_date)); ?></td>
                                        <td><?= $supplier; ?></td>
                                        <td><?= $sql['bill_amount']; ?></td>
                                        <td><?php if($sql['bill_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['bill_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?php if($sql['approved_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['approved_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?= $sql['comments'] ?  $sql['comments'] : '-'; ?></td>
                                        <td>
                                            <span class="border border-<?= $bord[$sql['bill_status']]; ?> rounded text-<?= $stat[$sql['bill_status']]; ?>"><?= $sql['bill_status']; ?></span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"> <i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item" onclick="is_approved_id(<?= $sql['id']; ?>, '<?= $sql['entry_number']; ?>')"><i class="icon-copy dw dw-checked"></i> Approve</a>
                                                    <a class="dropdown-item" href="bill_passing-view.php?id=<?= $sql['id'] ?>&from=bill_approval"><i class="fa fa-eye"></i> View</a>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve Sales Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="is_approved_id" id="is_approved_id">
                    <input type="hidden" name="is_approved_ref" id="is_approved_ref">
                    <select name="is_approved" id="is_approved" class="custom-select2 form-control"
                        style="width:100% !important">
                        <option value="">Waiting</option>
                        <option value="Approved">Approve Bill</option>
                        <option value="Rejected">Reject Bill</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-primary billapprove">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        function is_approved_id(id, ref) {
            $("#is_approved_id").val(id);
            $("#is_approved_ref").val(ref);
            $("#exampleModal").modal('show');
        }

        $(".billapprove").click(function () {

            var id = $("#is_approved_id").val();
            var is_approved = $("#is_approved").val();
            var ref_id = $("#is_approved_ref").val();

            var data = 'id=' + id + '&is_approved=' + is_approved +'&ref_id=' + ref_id;

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?approve_bill',
                data: data,

                success: function (msg) {
                    var json = $.parseJSON(msg);

                    if (json.result == 'saved') {
                        swal({ type: 'success', text: 'Status Changed!', timer: 1500, }).then(function () { location.reload(); })
                    } else {
                        swal({ type: 'error', text: 'Something Went Wrong!', timer: 1500 })
                    }
                }
            })
        });
    </script>

</body>

</html>