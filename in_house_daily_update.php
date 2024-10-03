<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_POST['SaveBtn'])) {
    
    
    $bval = $_REQUEST['box_value'];
    
    
    $_SESSION['msg'] = "added";

    header("Location:in_house_daily_status.php");

    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM inhouse_daily_status WHERE id=" . $_GET['sid']));
} else {
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - In-house Daily Status Updation</title>

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

<style>
    .ui-menu-item-wrapper {
        background-color: #eae8e8 !important;
        padding: 10px;
        width: 20% !important;
        border-bottom: 1px solid #c6c5c5;
    }
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <?php if(SEW_OUTPUT_ADD!=1) { action_denied(); exit; } ?>
            <div class="min-height-200px">
                
                <div class="alert alert-warning fade show" role="alert">
					    <a onclick="showImpt()" class="a_click"><i class="icon-copy ion-chevron-right icc_imp"></i> <strong>Important! <i class="icon-copy fa fa-hand-o-down" aria-hidden="true"></i></strong></a><br>
					    
					    <span class="sub d-none">
        					<i class="icon-copy fa fa-hand-o-right" aria-hidden="true"></i> Scanned bundle details are saved <b>Temporarily</b>. <br>
        					<i class="icon-copy fa fa-hand-o-right" aria-hidden="true"></i> To save, click the <b>Save</b> button .<br>
        					<i class="icon-copy fa fa-hand-o-right" aria-hidden="true"></i> To skip saving, click the <b>Cancel Scanning</b> button.
    					</span>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-top: -25px;"><span aria-hidden="true">×</span></button>
				</div>
				
				<script>
				    function showImpt() {
				        var a = $(".icc_imp").hasClass('ion-chevron-right');
				        if(a==true) {
				            $(".icc_imp").removeClass('ion-chevron-right');
				            $(".icc_imp").addClass('ion-chevron-down');
				            $(".sub").fadeIn();
				            $(".sub").removeClass('d-none');
				        } else {
				            $(".icc_imp").addClass('ion-chevron-right');
				            $(".icc_imp").removeClass('ion-chevron-down');
				            $(".sub").fadeOut();
				        }
				    }
				</script>

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="pd-20">
                        <!--<a class="btn btn-outline-primary" href="in_house_daily_status.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> In-house List</a>-->
                        <!--<a class="btn btn-outline-primary" href="in_house_daily_status.php" style="float: right;"><i class="fa fa-plus" aria-hidden="true"></i> Today Status</a>-->
                        
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <?php if(isset($_GET['wh'])) { ?>
							    <button type="button" class="btn btn-outline-primary" onclick="window.location.href='in_house_daily_update.php?id=<?= $_GET['id']; ?>'"><i class="fa fa-plus" aria-hidden="true"></i> Add Status</button>
                            <?php } else { ?>
							    <button type="button" class="btn btn-outline-primary" onclick="window.location.href='in_house_daily_update.php?id=<?= $_GET['id']; ?>&wh=list'"><i class="fa fa-list" aria-hidden="true"></i> Daily Status</button>
							<?php } ?>
							<button type="button" class="btn btn-outline-primary" onclick="window.location.href='in_house_daily_status.php'"><i class="fa fa-list" aria-hidden="true"></i> In-house List</button>
						</div>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">In-house Daily Status Updation</h4>
                        </div>
                    </div>
                    <?php if(!isset($_GET['wh'])) { ?>
                        <form id="add-process" method="post" autocomplete="off">
    
                            <input type="hidden" name="process_type" id="process_type" value="inhouse_dailyStatus">
    
                            <div class="row">
    
                                <?php
                                if (isset($_GET['sid'])) {
                                    $code = $sql['entry_number'];
                                } else {
                                    $qryz = mysqli_query($mysqli, "SELECT * FROM inhouse_daily_status WHERE entry_number LIKE '%IP-%' ORDER BY id DESC");
                                    $sqql = mysqli_fetch_array($qryz);
                                    $numm = mysqli_num_rows($qryz);
                                    if ($numm == 0) {
                                        $code = 'IP-1';
                                    } else {
                                        $ex = explode('-', $sqql['entry_number']);
        
                                        $value = $ex[1];
                                        $intValue = (int) $value;
                                        $newValue = $intValue + 1;
                                        $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
        
                                        $code = $ex[0] . '-' . $nnum;
                                    }
                                }
                                ?>
                                <div class="col-md-3">
                                    <label class="col-form-label">Entry Number <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="text" readonly name="entry_number" class="form-control" value="<?= $code; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="col-form-label">Entry Date <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                                        <input type="hidden" name="processing_id" id="processing_id" value="<?= $_GET['id']; ?>">
                                        <input type="hidden" name="daily_status_id" id="daily_status_id" value="<?= $_GET['sid']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="col-form-label">Scan Type <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <select name="scanType" id="scanType" class="custom-select2 form-control" style="width:100%">
                                            <option value="bundle">Bundle Scanning</option>
                                            <?php if(!isset($_GET['sid'])) { ?>
                                            <option value="piece">Piece Scanning</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
    
                                <div class="col-md-2">
                                    <label for="">BO <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        
                                        <?php $nq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT order_id FROM processing_list WHERE id=". $_GET['id'])); ?>
                                        <select class="custom-select2 form-control" <?= $_GET['id'] ? 'disabled' : 'name="order_id" id="order_id"'; ?>>
                                            <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', $nq['order_id'], '', ''); ?>
                                        </select>
                                        
                                        <input type="hidden" value="<?= $nq['order_id']; ?>" <?= $_GET['id'] ? 'name="order_id" id="order_id"' : ''; ?>>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="col-form-label">Scan Using <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <?php if(!isset($_GET['sid'])) { ?>
                                            <input type="radio" value="Mobile" id="sc_Type_mobile" name="scanUsing" checked> <label for="sc_Type_mobile">Mobile</label>
                                            <input type="radio" value="Device" id="sc_Type_device" name="scanUsing"> <label for="sc_Type_device">Device</label>
                                            <input type="radio" value="Manual" id="sc_Type_manual" name="scanUsing"> <label for="sc_Type_manual">Manual</label>
                                        <?php } else { ?>
                                            <input type="radio" value="<?= $sql['scanUsing'] ?>" id="sc_Type_" name="scanUsing" checked> <label for="sc_Type_"><?= $sql['scanUsing'] ?></label><br>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                var scanCount = 0;
                            </script>
                            
                            <?php if($sql['scanUsing']=='Mobile') { ?>
                                <script src="src/html5-qrcode.min.js"></script>
        
                                <div class="row" align="center" style="display:<?= isset($_GET['sid']) ? 'block' : 'none'; ?>">
                                    <div class="col">
                                        <div style="width:100% !important;" id="reader"></div>
                                    </div>
                                </div>
        
                                <script type="text/javascript">
        
        
                                    function onScanSuccess(qrCodeMessage) {
                                        // alert(qrCodeMessage);
        
                                        $.ajax({
                                            type: 'POST',
                                            url: 'ajax_search.php?validateQR=1&qr=' + qrCodeMessage +'&type=sewing_output',
                                            success: function (msg) {
                                                var json = $.parseJSON(msg);
                                                if(parseInt(scanCount) == 0){
            
                                                    if (json.result == 'notFound') {
                                                        scanCount++;
            
                                                        var popConfirm = confirm(json.message); 
                                                        if(popConfirm){
                                                            setTimeout(function () {
                                                                scanCount = 0;
                                                            }, 2000);
                                                        } else {
                                                            setTimeout(function () {
                                                                scanCount = 0;
                                                            }, 2000);
                                                        }
                                                        return false;
                                                    } else {
                                                        scanCount++;
                                                        // alert(qrCodeMessage);
                                                        swal({
                                                            title: 'Are you sure?',
                                                            text: "Confirm Add Bundle!",
                                                            type: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Yes, Proceed!',
                                                            cancelButtonText: 'No, cancel!',
                                                            confirmButtonClass: 'btn btn-success margin-5',
                                                            cancelButtonClass: 'btn btn-danger margin-5',
                                                            buttonsStyling: false
                                                        }).then(function (dd) {
                                                            if (dd['value'] == true) {
                                                                addrow_multi(json.result, '');
                                                            setTimeout(function () {
                                                                scanCount = 0;
                                                            }, 2000);
                                                            } else {
                                                                swal(
                                                                    'Cancelled',
                                                                    '',
                                                                    'error'
                                                                )
                                                            setTimeout(function () {
                                                                scanCount = 0;
                                                            }, 2000);
                                                            }
                                                        })
            
                                                    }
                                                }
                                            }
                                        })
                                    }
        
                                    function onScanError(errorMessage) {
                                        //handle scan error
                                    }
        
                                    var html5QrcodeScanner = new Html5QrcodeScanner(
                                        "reader", { fps: 10, qrbox: 250 });
                                    html5QrcodeScanner.render(onScanSuccess, onScanError);
        
                                </script>
                            <?php } ?>
                                
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-2 text-center" style="display:<?= isset($_GET['sid']) ? 'none' : 'block'; ?>">
                                    <a class="btn btn-outline-secondary" onclick="save_inhouse_Status()">Start Scanning</a>
                                </div>
                            </div>
    
                            <div style="padding: 40px;display:<?= isset($_GET['sid']) ? 'block' : 'none'; ?>">
    
                                <div class="row">
                                    <?php //if(P_OUTWARD_MAN_SCAN==1) 
                                    if($sql['scanUsing'] == 'Device') { ?>
                                    
                                        <div class="col-md-4"></div>
                                        
                                        <div class="col-md-4">
        
                                            <input type="hidden" name="processing_id" id="processing_id" value="<?= $_GET['id']; ?>">
                                            <input type="hidden" name="bundle_in" id="bundle_in" value="<?= $sql['boundle_id']; ?>">
                                            
                                            <input type="text" class="form-control" name="" id="device_bundle" value="" onkeyup="return getDeviceVal()">
                                        </div>
                                        
                                        <div class="col-md-4"></div>
        
                                        <script>
                                            function getDeviceVal() {
                                                
                                                $("#add-process").submit(function(){
                                                    return false;
                                                });
                                                
                                                setTimeout(function() {
                                                    var val = $("#device_bundle").val();
                                                    
                                                    onScanSuccess(val);
                                                }, 200);
                                            };
                                            
                                        </script>
                                        
                                        <script type="text/javascript">
        
                                            function onScanSuccess(qrCodeMessage) {
                                                var order_id = $("#order_id").val();
                
                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'ajax_search.php?validateQR=1&qr=' + qrCodeMessage + '&type=sewing_output&order_id=' + order_id ,
                                                    success: function (msg) {
                                                        // alert(msg);
                                                        var json = $.parseJSON(msg);
                                                        if(parseInt(scanCount) == 0){
                    
                                                            if (json.result == 'notFound') {
                                                                scanCount++;
                                                                
                                                                $("#device_bundle").val('');
                                                                var popConfirm = confirm(json.message);
                                                                if(popConfirm){
                                                                    setTimeout(function () {
                                                                        scanCount = 0;
                                                                    }, 1000);
                                                                } else {
                                                                    setTimeout(function () {
                                                                        scanCount = 0;
                                                                    }, 1000);
                                                                }
                                                                return false;
                                                            } else {
                                                                scanCount++;
                                                                addrow_multi(json.result, '');
                                                                $("#device_bundle").val('');
                                                                setTimeout(function () {
                                                                    scanCount = 0;
                                                                }, 1000);
                                                            }
                                                        }
                                                    }
                                                })
                                            }
                
                
                                            var html5QrcodeScanner = new Html5QrcodeScanner(
                                                "reader", { fps: 10, qrbox: 250 });
                                            html5QrcodeScanner.render(onScanSuccess, onScanError);
                                        </script>
                                    <?php } ?>
                                    
                                    
                                    <?php if($sql['scanUsing']=='Manual') { ?>
                                    
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="input-group mb-3">
                                                
                                                <select class="custom-select2 form-control" name="multibundle[]" id="multibundle" multiple>
                                                    <?php
                                                        $qry = mysqli_fetch_array(mysqli_query($mysqli, "SELECT boundle_id FROM processing_list WHERE id=". $_GET['id']));
    
                                                        foreach(explode(',', $qry['boundle_id']) as $val) {
                                                            $opt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id, boundle_qr FROM bundle_details WHERE id=". $val));
                                                            
                                                            print '<option value="'. $opt['id'] .'">'. $opt['boundle_qr'] .'</option>';
                                                        }
                                                    ?>
                                                </select>
        
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" onclick="Save_inhouse_completed_bundles()" type="button">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                    <div class="col-md-12" style="text-align:center">Scanned Bundle Count : <span id="scanedCnt">0</span></div>
                                    
                                    <div class="col-md-12" style="overflow-y: auto;">
                                        
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>So.No</th>
                                                    <th>Style No</th>
                                                    <th>Part</th>
                                                    <th>Color</th>
                                                    <th>Size</th>
                                                    <th>Boundle No</th>
                                                    <th>Pieces</th>
                                                    <th>Boundle QR</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody"></tbody>
                                        </table>
                                    </div>
                                </div>
    
                                <hr>
    
                                <div class=" row">
                                    <div class="col-md-12">
                                        <div class="form-group" style="text-align: center;">
                                            <!--<a class="btn btn-warning" onclick="delete_entry(<?= $_GET['id']; ?>)">Cancel Scanning</a>-->
                                            <a class="btn btn-outline-secondary" href="in_house_daily_status.php">Go Back</a>
                                            <input type="submit" class="btn btn-success" name="SaveBtn" value="Save">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                    <?php } if(isset($_GET['wh'])) { ?>
                        
                        <div class="tab">
    						<ul class="nav nav-tabs" role="tablist">
    						    <?php
    						        $p = 0;
                                    $qry = mysqli_query($mysqli, "SELECT * FROM inhouse_daily_status WHERE processing_id='". $_GET['id']. "' ORDER BY id DESC");
                                    while($tabb = mysqli_fetch_array($qry)) {
                                        
                                    $rach[] = $tabb['id'];
    						    ?>
        							<li class="nav-item">
        								<a class="nav-link text-blue <?= ($p==0) ? 'active': ''; ?>" data-toggle="tab" href="#tab<?= $p; ?>" role="tab" aria-selected="true"><?= $tabb['entry_number']; ?> <small>(<?= date('d-M Y', strtotime($tabb['entry_date'])); ?>)</small></a>
        							</li>
    							<?php $p++; } ?>
    						</ul>
    						<div class="tab-content">
    						    <?php $q=0; foreach($rach as $value) { ?>
        							<div class="tab-pane fade <?= ($q==0) ? 'show active' : ''; ?>" id="tab<?= $q; ?>" role="tabpanel">
        								<div class="pd-20">
        								   <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                    							<button type="button" class="btn btn-outline-info" onclick="window.location.href='in_house_daily_update.php?&id=<?= $_GET['id']; ?>&sid=<?= $value; ?>'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
                    							<button type="button" class="btn btn-outline-info" onclick="deleteInhouse_status(<?= $value; ?>)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                    						</div>
                                            <table class="table data-table">
                                                <thead>
                                                    <tr>
                                                        <th>So.No</th>
                                                        <th>Style No</th>
                                                        <th>Part</th>
                                                        <th>Color</th>
                                                        <th>Size</th>
                                                        <th>Boundle No</th>
                                                        <th>Pieces</th>
                                                        <th>Boundle QR</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $qry = "SELECT a.id, a.completed_qty, b.boundle_qr, b.bundle_number, c.style_no, d.part_name, e.color_name, f.type ";
                                                        $qry .= " FROM inhouse_process a ";
                                                        $qry .= " LEFT JOIN bundle_details b ON b.id = a.bundle_id ";
                                                        $qry .= " LEFT JOIN sales_order_detalis c ON c.id = b.style ";
                                                        $qry .= " LEFT JOIN part d ON d.id = b.part ";
                                                        $qry .= " LEFT JOIN color e ON e.id = b.color ";
                                                        $qry .= " LEFT JOIN variation_value f ON f.id = b.variation_value ";
                                                        $qry .= " WHERE a.daily_status_id = '". $value ."' ORDER BY a.id DESC ";
                                                        
                                                        $temp = mysqli_query($mysqli, $qry); 
                                                        
                                                        $mp = 0;
                                                        while($row = mysqli_fetch_array($temp)) { 
                                                        $mp++; ?>
                                                            <tr>
                                                                <td><?= $mp; ?></td>
                                                                <td><?= $row['style_no']; ?></td>
                                                                <td><?= $row['part_name']; ?></td>
                                                                <td><?= $row['color_name']; ?></td>
                                                                <td><?= $row['type']; ?></td>
                                                                <td><?= $row['bundle_number']; ?></td>
                                                                <td><?= $row['completed_qty']; ?></td>
                                                                <td><?= $row['boundle_qr']; ?></td>
                                                                <td><a class="border border-secondary rounded text-secondary" onclick="delete_data(<?= $row['id']; ?>, 'inhouse_process')"><i class="fa fa-trash"></i></a></td>
                                                            </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
        								   <?php ?>
        								</div>
        							</div>
        						<?php $q++; } ?>
    						</div>
    					</div>
    				<?php } ?>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>
     
    
    <script>
        function deleteInhouse_status(id)
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
                        url:'ajax_action.php?deleteInhouse_status=1&id='+id,
                        success:function(msg){
                            var json = $.parseJSON(msg);
                            if(json.res==0) {
                                swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Deleted!',
                                    showConfirmButton: false,
                                    timer: 1000
                                }).then(function() {
                                    location.reload();
                                })
                            } else {
                                message_noload('error', 'Something went wrong!');
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
        function removeRow(id) {
            // $("#tbTr" + id).remove();

            var pid = $("#processing_id").val();
            var cid = $("#scanedCnt").text();

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?UpdBundlereturn=1&id=' + id + '&pid=' + pid +'&type=sewing_output',
                success: function (msg) {

                    var json = $.parseJSON(msg);

                    swal(
                        {
                            position: 'top-end',
                            type: 'success',
                            title: 'Bundle Removed',
                            showConfirmButton: false,
                            timer: 700
                        }
                    ).then(
                        function () {

                            $("#bundle_in").val(json.boundle_id);
                            $("#tbTr" + id).remove();
                            $("#scanedCnt").text((cid - 1));
                        })
                }
            });
        }
    </script>
    
    <script>
        function Save_inhouse_completed_bundles() {
            
            var val = $("#multibundle").val();
            
            if(val=="") {
                message_noload('error', 'Select Bundle!', 1500);
                return false;
            } else {
                
                var form = $("#add-process").serialize();
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?Save_inhouse_completed_bundles=1',
                    data: form,
                    
                    success: function(msg) {
                        
                        var json = $.parseJSON(msg);
                        if(json.res==0) {
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Bundle Added',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(function() {
                                savedBundles();
                            })
                        } else {
                            message_noload('error', 'Something went wrong!');
                        }
                    }
                })
            }
        }
    </script>

    <script>
        function save_inhouse_Status() {
            
            var form = $("#add-process").serialize();
            
            var scanType = $("#scanType").val();
            var pid = $("#processing_id").val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?save_inhouse_Status=1',
                data: form,
                success: function (msg) {

                    var json = $.parseJSON(msg);

                    if (json.res == 0) {
                        if (scanType == "bundle") {
                            window.location.href = 'in_house_daily_update.php?&id=' + pid +'&sid=' + json.inid;
                        } else if (scanType == "piece") { 
                            window.location.href = 's-outputPiece.php?id=' + json.inid;
                        }
                    } else {
                        
                        swal(
                        {
                            position: 'top-end',
                            type: 'success',
                            title: 'Bundle Added',
                            showConfirmButton: false,
                            timer: 1000
                        }
                    ).then(
                        function () {
                            savedBundles(json.inid);
                            $("#bundle_in").val(json.bundle_in);
                        })
                    }
                }
            })
        }
    </script>
    
    <script>
        $(document).ready(function() {
        //     var id = <?= $_GET['id']; ?>;
        
            savedBundles();
        })
    
    
        function savedBundles()
        {
            
            var id = $("#processing_id").val();
            var sid = $("#daily_status_id").val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getInhouse_completed=1&id='+ id + '&sid=' + sid,
                
                success: function (res) {
                    var njs = $.parseJSON(res);
                    
                    $("#scanedCnt").text(njs.cntt);
                    $("#tableBody").html(njs.tbody);
                        
                }
            });
        }
    </script>


    <script type="text/javascript">
        $(function () {
            $('#add-process').validate({
                errorClass: "help-block",
                rules: {
                    p_type: {
                        required: true
                    },
                    process_id: {
                        required: true
                    },
                    supplier_id: {
                        required: true
                    }
                },
                errorPlacement: function (label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
                highlight: function (element, errorClass) {
                    $(element).parent().addClass('has-danger')
                    $(element).addClass('form-control-danger')
                }
            });
        });
        
        // $("body").click(function() {
        //     device_focus()
        // });
        
        $(document).ready(function() {
            device_focus()
        });
        
        function device_focus() {
            $("#device_bundle").focus();
        };
    </script>

</body>

</html>