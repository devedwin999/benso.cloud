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
    <title>BENSO GARMENTING - Payment List</title>

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
    
    <style>
        .com-md-3 {
            padding:2px !important;
        }
    </style>
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
                    <?php if(PAYMENT_OUT!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                    <?php if(PAYMENT_OUT_ADD==1) { ?>
                        <a class="btn btn-outline-primary showModal" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                    <?php } ?>
                        <h4 class="text-blue h4">Payment List</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Entry Number</th>
                                    <th>Entry Date</th>
                                    <th>Bill Type</th>
                                    <th>Supplier</th>
                                    <th>Bill Ref</th>
                                    <th>Bill Value</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Method</th>
                                    <th>Payment Ref Image</th>
                                    <th>Payment Ref Detail</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM payments";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['entry_number']; ?></td>
                                        <td><?= date('d-m-y', strtotime($sql['created_date'])); ?></td>
                                        <td><?= $sql['bill_type']; ?></td>
                                        <td><?= $sql['']; ?></td>
                                        <td><?= $sql['bill_ref']; ?></td>
                                        <td><?= $sql['bill_value']; ?></td>
                                        <td><?= $sql['pay_amount']; ?></td>
                                        <td><?= $sql['pay_method']; ?></td>
                                        <td><?= $sql['']; ?></td>
                                        <td><?= $sql['pay_ref_detail']; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i>Edit</a>
                                                    <!--<a class="dropdown-item" onclick="getProcessingDet(<?= $sql['id']; ?>)"><i class="dw dw-eye"></i> View</a>-->
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
    
    
    <div class="modal fade bs-example-modal-lg" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-top " style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Payment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="paymentForm" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <?php
    							$qryz = mysqli_query($mysqli, "SELECT entry_number FROM payments WHERE entry_number LIKE '%PY-%' ORDER BY id DESC");
    							$sqql = mysqli_fetch_array($qryz);
    							$numm = mysqli_num_rows($qryz);
    							if ($numm == 0) {
    								$code = 'PY-1';
    							} else {
    								$ex = explode('-', $sqql['entry_number']);
    
    								$value = $ex[1];
    								$intValue = (int) $value;
    								$newValue = $intValue + 1;
    						
    								$code = $ex[0] . '-' . $newValue;
    							}
    						?>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="entry_number">Entry No</label>
                                <input type="text" class="form-control" name="entry_number" id="entry_number" readonly value="<?= $code; ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="entry_date">Entry Date</label>
                                <input type="date" class="form-control" name="entry_date" id="entry_date" value="<?= date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="bill_type">Bill Type</label>
                                <select class="custom-select2 form-control" name="bill_type" id="bill_type" style="width:100%" required>
                                    <option value="">Select</option>
                                    <option value="Fabric">Fabric</option>
                                    <option value="Store">Store</option>
                                    <option value="Production">Production</option>
                                    <option value="CostGenerate">Cost Generate</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="supplier">Supplier</label>
                                <select class="custom-select2 form-control" name="supplier" id="supplier" style="width:100%" required>
                                    <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-12"><hr style="border-top: 1px solid rgb(155 155 155 / 10%);"></div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="payment_type">Payment Type</label>
                                <select class="custom-select2 form-control" name="payment_type" id="payment_type" style="width:100%" onchange="payment_type1()" required>
                                    <option value="bill_against">Bill Against</option>
                                    <option value="advance">Advance</option>
                                    <option value="pi">Pi</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="tot_outstanding">Total Outstanding</label>
                                <input type="text" class="form-control" name="tot_outstanding" id="tot_outstanding" placeholder="Total Outstanding" readonly>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="bill_ref_sel">Bill Ref</label>
                                
                                <input type="text" class="form-control d-none" name="bill_ref" id="bill_ref_inp" placeholder="Bill Ref">
                                
                                <select class="custom-select2 form-control" name="bill_receipt_[]" id="bill_ref_sel" style="width:100%" multiple required></select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="bill_value">Bill Value</label>
                                <input type="text" class="form-control" name="bill_value" id="bill_value" placeholder="Bill Value" required>
                            </div>
                            
                            <div class="col-md-12"><hr style="border-top: 1px solid rgb(155 155 155 / 10%);"></div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="payment_method">Payment Method</label>
                                <select class="custom-select2 form-control" name="payment_method" id="payment_method" style="width:100%" required>
                                    <option value="NEFT">NEFT</option>
                                    <option value="RTGS">RTGS</option>
                                    <option value="CHEQUE">CHEQUE</option>
                                    <option value="CASH">CASH</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="" for="ref_file">Payment Ref File</label>
                                <input type="file" class="form-control" name="ref_file" id="ref_file">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="" for="ref_file">Payment Ref Details</label>
                                <textarea class="form-control" name="ref_detail" id="ref_detail" placeholder="Payment Ref Details" style="height: 45px;"></textarea>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="fieldrequired" for="payment_amt">Payment Amount</label>
                                <input type="text" class="form-control" name="payment_amt" id="payment_amt" placeholder="Payment Amount" required>
                            </div>
                            
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onclick="_savePayment()">Save</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    

    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
        
    <script>
        function _savePayment() {
            
            var err = required_validation('paymentForm');
            
            if(err == 0) {
                
                
                swal({
                    title: 'Are you sure?',
                    text: "Process The Payment?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Process!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success margin-5',
                    cancelButtonClass: 'btn btn-danger margin-5',
                    buttonsStyling: false
                }).then(function (dd) {
                    if (dd['value'] == true) {
                        
                        var form = $("#paymentForm").serialize();
                        
                        var bill_ref_sel = $("#bill_ref_sel").val();
                        
                        var formData = new FormData();
                        
                        var ref_file = $("#ref_file")[0].files[0];
                        formData.append('ref_file', ref_file);
                        
                        // for(var i = 0; i < files.length; i++){
                        //     formData.append('files[]', files[i]);
                        // }
                        
                        $.each(form.split('&'), function(index, item) {
                            var splitItem = item.split('=');
                            formData.append(splitItem[0], decodeURIComponent(splitItem[1]));
                        });
                        
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_action.php?process_payment=1&bill_RefNew='+bill_ref_sel,
                            data : formData,
                            contentType : false,
                            processData : false,
                            
                            success : function(msg) {
                                
                                var json = $.parseJSON(msg);
                                if(json.res == 0 ) {
                                    message_reload('success', 'Payment Entry Saved!', 1500);
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
        }
        
    </script>
    
    <script>
        function payment_type1() {
            
            var payment_type = $("#payment_type").val();
            
            if(payment_type=='bill_against') {
                $("#bill_ref_inp").addClass('d-none').removeAttr('required');
                $("#bill_ref_sel").select2().removeClass('d-none').prop('required', true);
                
            } else {
                $('#bill_ref_sel').select2('destroy').removeClass('select2-hidden-accessible').removeAttr('aria-hidden required').addClass('d-none').removeAttr('required').val('');
                $("#bill_ref_inp").removeClass('d-none').prop('required', true);
            }
        }
    </script>
    
    <script>
        $("#bill_ref_sel").change(function() {
            
            var id = $(this).val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?calculate_bill_receipt_Total=1&id=' + id,
                
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    
                    $("#bill_value").val(json.bill_amount);
                }
            });
        });
    </script>
    
    <script>
        $("#bill_type").change(function() {
            
            var type = $(this).val();
                
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_supplier_unpaid_names=1&type=' + type,
                
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    
                    $("#supplier").html(json.option);
                }
            });
        });
    </script>
    
    <script>
        
        $("#supplier").change(function() {
                
            var val = $(this).val();
            var type = $("#bill_type").val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_supplier_unpaid_bills=1&id=' + val +'&type=' + type,
                
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);
                    
                    $("#bill_ref_sel").html(json.option);
                    $("#tot_outstanding").val(json.outstand);
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

</body>

</html>