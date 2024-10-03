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
    <title>BENSO GARMENTING - Bill Receipt</title>

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
                    <?php page_spinner(); if(BILL_RECEIPT!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <?php if(BILL_RECEIPT_ADD == 1) { ?>
                                <a class="btn btn-outline-primary showModal" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                            <?php } ?>
                            <a class="btn btn-outline-info" href="mod_accounts.php"><i class="fa fa-home" aria-hidden="true"></i> Accounts</a>
						</div>
                        <h4 class="text-blue h4">Bill Receipt List</h4>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.supplier_name ";
                                $qry .= " FROM bill_receipt a ";
                                $qry .= " LEFT JOIN supplier b ON a.supplier = b.id ";
                                $qry .= " ORDER BY a.id DESC ";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    
                                    if($sql['bill_type']=="CostGenerate")
                                    {
                                        $h = mysqli_fetch_array(mysqli_query($mysqli, "SELECT cg_name FROM employee_detail WHERE id=". $sql['supplier']));
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['entry_number']; ?></td>
                                        <td><?= date('y-m-d', strtotime($sql['entry_date'])); ?></td>
                                        <td><?= $sql['bill_type']; ?></td>
                                        <td><?= $sql['bill_number']; ?></td>
                                        <td><?= date('y-m-d', strtotime($sql['bill_date'])); ?></td>
                                        <td><?= ($sql['bill_type']=="CostGenerate") ? $h['cg_name'] : $sql['supplier_name']; ?></td>
                                        <td><?= $sql['bill_amount']; ?></td>
                                        <td><?php if($sql['bill_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['bill_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?php if($sql['approved_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['approved_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                        <td><?= $sql['comments'] ?  $sql['comments'] : '-'; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown"> <i class="dw dw-more"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(BILL_RECEIPT_EDIT == 1) { ?>
                                                        <a class="dropdown-item" onclick="showEditModal(<?= $sql['id'] ?>)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(BILL_RECEIPT_DELETE == 1) { ?>
                                                        <a class="dropdown-item" onclick="deleteBillReceipt(<?= $sql['id'] ?>)"><i class="fa fa-trash"></i> Delete</a>
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
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Bill Receipt</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="bill_receiptForm" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            
                            <?php
    							$qryz = mysqli_query($mysqli, "SELECT entry_number FROM bill_receipt WHERE entry_number LIKE '%BR-%' ORDER BY id DESC");
    							$sqql = mysqli_fetch_array($qryz);
    							$numm = mysqli_num_rows($qryz);
    							if ($numm == 0) {
    								$code = 'BR-1';
    							} else {
    								$ex = explode('-', $sqql['entry_number']);
    
    								$value = $ex[1];
    								$intValue = (int) $value;
    								$newValue = $intValue + 1;
    						
    								$code = $ex[0] . '-' . $newValue;
    							}
    						?>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="entry_num">Entry No</label>
                                <input type="text" class="form-control" name="entry_number" id="entry_number" readonly value="<?= $code; ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="entry_date">Entry Date</label>
                                <input type="date" class="form-control" name="entry_date" id="entry_date" value="<?= date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="bill_type">Bill Type</label>
                                <select class="custom-select2 form-control" name="bill_type" id="bill_type" style="width:100%" required>
                                    <option value="">Select</option>
                                    <option value="Fabric">Fabric</option>
                                    <option value="Store">Store</option>
                                    <option value="Production">Production</option>
                                    <option value="CostGenerate">Cost Generate</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="supplier">Supplier</label>
                                <select class="custom-select2 form-control" name="supplier" id="supplier" style="width:100%" required>
                                    <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="bill_num">Bill No</label>
                                <input type="text" class="form-control" name="bill_number" id="bill_number" placeholder="Bill Number" required>
                                
                                <select class="d-none form-control" name="cost_id" id="cost_id" style="width:100%" required></select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="bill_date">Bill Date</label>
                                <input type="date" class="form-control" name="bill_date" id="bill_date" placeholder="bill date" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="bill_amount">Bill Amount</label>
                                <input type="text" class="form-control" name="bill_amount" id="bill_amount" placeholder="Bill Amount" required>
                            </div>
                            
                            <div class="col-md-4 image_head_tag">
                                <label class="fieldrequired" for="bill_image">Bill Image</label>
                                <input type="file" class="form-control imagefield" name="bill_image" id="bill_image" placeholder="Bill Image" required>
                                <small class="imagename">Accept Images Only</small>
                            </div>
                            
                            <div class="col-md-4 image_head_tag">
                                <label class="" for="approved_image">Rate Approved Image</label>
                                <input type="file" class="form-control imagefield" name="approved_image" id="approved_image" placeholder="Rate Approved Image">
                                <small class="imagename">Accept Images Only</small>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="" for="comments">Remarks</label>
                                <textarea class="form-control" name="comments" id="comments" placeholder="Remarks" style="height:50px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onclick="saveBill()">Save</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Bill Receipt</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="ed_bill_receiptForm" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_entry_num">Entry No</label>
                                <input type="text" class="form-control" name="entry_number" id="ed_entry_number" readonly>
                                <input type="hidden" class="form-control" name="ed_id" id="ed_id">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_entry_date">Entry Date</label>
                                <input type="date" class="form-control" name="entry_date" id="ed_entry_date" value="<?= date('Y-m-d'); ?>" readonly>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_bill_type">Bill Type</label>
                                <select class="pe-none form-control" name="bill_type" id="ed_bill_type" style="width:100%" readonly>
                                    <option value="">Select</option>
                                    <option value="Fabric">Fabric</option>
                                    <option value="Store">Store</option>
                                    <option value="Production">Production</option>
                                    <option value="CostGenerate">Cost Generate</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_supplier">Supplier</label>
                                <select class="form-control pe-none" name="supplier" id="ed_supplier" style="width:100%" required readonly>
                                    <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_bill_num">Bill No</label>
                                <input type="text" class="form-control" name="bill_number" id="ed_bill_number" placeholder="Bill Number" readonly>
                                <input type="hidden" class="form-control" name="cost_id" id="ed_cost_id">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_bill_date">Bill Date</label>
                                <input type="date" class="form-control" name="bill_date" id="ed_bill_date" placeholder="bill date" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_bill_amount">Bill Amount</label>
                                <input type="text" class="form-control" name="bill_amount" id="ed_bill_amount" placeholder="Bill Amount" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fieldrequired" for="ed_bill_image">Bill Image</label>
                                <input type="file" class="form-control" name="bill_image" id="ed_bill_image" placeholder="Bill Image">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="" for="ed_approved_image">Rate Approved Image</label>
                                <input type="file" class="form-control" name="approved_image" id="ed_approved_image" placeholder="Rate Approved Image">
                            </div>
                            
                            <div class="col-md-12">
                                <label class="" for="ed_comments">Remarks</label>
                                <textarea class="form-control" name="comments" id="ed_comments" placeholder="Remarks" style="height:50px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onclick="updateBill()">Save</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    
    <script>
        $("#bill_type").change(function() {
            
            var type = $(this).val();
            
            if(type=='CostGenerate') {
                $("#overlay").fadeIn(100);
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?not_receipted_Cost=1',
                    
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        $("#overlay").fadeOut(500);
                        $("#supplier").html(json.option);
                    }
                });
                
                $("#bill_number").addClass('d-none');
                $('#bill_number').removeAttr('required');
                $("#cost_id").removeClass('d-none');
                $('#cost_id').prop('required', true);
                $("#cost_id").select2();
            } else {
                $("#overlay").fadeIn(100);
                $("#bill_number").removeClass('d-none');
                $('#bill_number').prop('required', true);
                
                $('#cost_id').select2('destroy');
                $('#cost_id').removeClass('select2-hidden-accessible');
                $('#cost_id').removeAttr('aria-hidden required');
                $("#cost_id").addClass('d-none');
                $("#overlay").fadeOut(500);
            }
        });
        
        
        $("#supplier").change(function() {
            
            var type = $("#bill_type").val();
            
            if(type=='CostGenerate') {
                $("#overlay").fadeIn(100);
                var val = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?getNotreceipted_bill=1&id=' + val,
                    
                    success: function (msg) {
                        
                        var json = $.parseJSON(msg);
                        $("#overlay").fadeOut(500);
                        $("#cost_id").html(json.option);
                    }
                });
            }
        });
        
        
        $("#cost_id").change(function() {
            
            var val = $(this).val();
            $("#overlay").fadeIn(100);
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getNotreceipted_bill_detail=1&id=' + val,
                
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);
                    $("#overlay").fadeOut(500);
                    $("#bill_amount").val(json.bill_amount);
                    $("#bill_date").val(json.entry_date);
                    $("#bill_number").val(json.entry_number);
                }
            });
        });
    </script>
    
    <script>
        $(".showModal").click(function() {
            
            $('#viewModal').modal({
                backdrop: 'static',
                keyboard: false
            })
            
            $("#viewModal").modal('show');
        });
        
        $('#viewModal').on('shown.bs.modal', function () {
            $('#bill_num').focus();
        })
    </script>

    <script>
        function saveBill() {
            
            var err = required_validation('bill_receiptForm');
            
            if(err == 0) {
                $("#overlay").fadeIn(100);
                var serializedData = $("#bill_receiptForm").serialize();
                
                var formData = new FormData();
                
                var bill_image = $("#bill_image")[0].files[0];
                formData.append('bill_image', bill_image);
                
                var approved_image = $("#approved_image")[0].files[0];
                formData.append('approved_image', approved_image);
                
                $.each(serializedData.split('&'), function(index, item) {
                    var splitItem = item.split('=');
                    formData.append(splitItem[0], decodeURIComponent(splitItem[1]));
                });
                
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?saveBill_receipt=1',
                    data : formData,
                    contentType: false,
                    processData: false,
                    
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        $("#overlay").fadeOut(500);
                        if(json.res == 0 ) {
                            message_reload('success', 'Bill Receipt Saved!', 1500);
                        } else {
                            message_noload('error', 'Something Went Wrong!', 1500);
                        }
                    }
                });
            }
        }
        
    </script>

    <script>
        function updateBill() {
            
            var err = required_validation('ed_bill_receiptForm');
            
            if(err == 0) {
                $("#overlay").fadeIn(100);
                var serializedData = $("#ed_bill_receiptForm").serialize();
                
                var formData = new FormData();
                
                var bill_image = $("#bill_image")[0].files[0];
                formData.append('bill_image', bill_image);
                
                var approved_image = $("#approved_image")[0].files[0];
                formData.append('approved_image', approved_image);
                
                // for(var i = 0; i < files.length; i++){
                //     formData.append('files[]', files[i]);
                // }
                
                $.each(serializedData.split('&'), function(index, item) {
                    var splitItem = item.split('=');
                    formData.append(splitItem[0], decodeURIComponent(splitItem[1]));
                });
                
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?saveBill_receipt=1',
                    data : formData,
                    contentType: false,
                    processData: false,
                    
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        $("#overlay").fadeOut(500);
                        if(json.res == 0 ) {
                            message_reload('success', 'Bill Receipt Updated!', 1500);
                        } else {
                            message_noload('error', 'Something Went Wrong!', 1500);
                        }
                    }
                })
            }
        }
        
    </script>
    
    <script>
        function showEditModal(id) {
            
            $("#overlay").fadeIn(100);
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getBill_receiptEdit=1&id='+ id,
                success : function(msg) {
                    
                    var json = $.parseJSON(msg);
                    $("#overlay").fadeOut(500);
                    $("#ed_comments").val(json.comments);
                    $("#ed_supplier").html(json.supplier_n);
                    $("#ed_bill_type").html(json.type_n);
                    
                    for(var key in json) {
                        if(json.hasOwnProperty(key)) {
                            
                            $("#ed_" + key).val(json[key]);
                        }
                    }
                }
            })
            
            $('#EditModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            
            $("#EditModal").modal('show');
        }
        
        $('#viewModal').on('shown.bs.modal', function () {
            $('#bill_num').focus();
        })
    </script>
    
    <script>
        function deleteBillReceipt(id) {
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
                        url: 'ajax_action.php?deleteBillReceipt=1&id='+ id,
                        success : function(msg) {
                            
                            var json = $.parseJSON(msg);
                            if(json.res == 0 ) {
                                message_reload('success', 'Bill Receipt Deleted!', 1500);
                            } else {
                                message_noload('error', 'Something Went Wrong!', 1500);
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
</body>

</html>