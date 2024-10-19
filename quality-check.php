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
    <title>BENSO - Quality Approval</title>

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
                <strong>Success!</strong> Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'added') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Saved.
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
                    <?php if(QUALITY_APP!=1) { action_denied(); exit; } ?>

						<div class="pd-20 card-box">
							<h5 class="h4 text-blue mb-20">Quality Approval</h5>
							<div class="tab">
								<ul class="nav nav-tabs customtab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tab_inward" role="tab" aria-selected="true">Process Inward</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab_sewing" role="tab" aria-selected="false">Sewing Output</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab_checking" role="tab" aria-selected="false">Checking Entey</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab_ironing" role="tab" aria-selected="false">Ironing</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab_packing" role="tab" aria-selected="false">Packing</a>
									</li>
								</ul>
								
								
								<div class="tab-content">
								    <?php $tab = 1; ?>
									<div class="tab-pane fade show active" id="tab_inward" role="tabpanel">
										<div class="pd-20">
										    <div style="padding: 15px;" class="text-center u">
										        <h5 style="color: #564747;">Process Inward</h5>
										    </div>
											<table class="table hover nowrap data-table">
                                                <thead>
                                                    <tr>
                                                        <th class="table-plus datatable-nosort">S.No</th>
                                                        <th>BO</th>
                                                        <th>Processing Code</th>
                                                        <th>DC Number</th>
                                                        <th>DC Date</th>
                                                        <th>Process Name</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $qry = "SELECT a.*, b.process_name, c.supplier_name, d.order_code ";
                                                    $qry .= " FROM processing_list a ";
                                                    $qry .= " LEFT JOIN process b ON a.process_id=b.id ";
                                                    $qry .= " LEFT JOIN supplier c ON a.supplier_id=c.id ";
                                                    $qry .= " LEFT JOIN sales_order d ON a.order_id=d.id ";
                                                    $qry .= " WHERE a.type='process_outward' AND a.is_inwarded=1 AND b.qc_approval = 'yes' ORDER BY a.id DESC ";
                                                    
                                                    $query = mysqli_query($mysqli, $qry);
                                                    $x = 1;
                                                    while ($sql = mysqli_fetch_array($query)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $x; ?></td>
                                                            <td><?= $sql['order_code']; ?></td>
                                                            <td><?= $sql['processing_code']; ?></td>
                                                            <td><?= $sql['dc_num']; ?></td>
                                                            <td><?= $sql['dc_date']; ?></td>
                                                            <td><?= $sql['process_name'] ? $sql['process_name'] : '-'; ?></td>
                                                            <td>
                                                                <?php if($sql['qc_approval']=='approved') { ?>
                                                                    <button type="button" class="btn btn-outline-success btn-sm">Approved</button>
                                                                <?php } else { ?>
                                                                    <button type="button" class="btn btn-outline-warning btn-sm">Pending</button>
                                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openQCmodal(<?= $sql['id']; ?>, <?= $tab; ?>)"><i class="icon-copy fa fa-info-circle" aria-hidden="true"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
										</div>
									</div>
									
								    <?php $tab = 2; ?>
									<div class="tab-pane fade" id="tab_sewing" role="tabpanel">
										<div class="pd-20">
										    <div style="padding: 15px;" class="text-center u">
										        <h5 style="color: #564747;">Sewing Out</h5>
										    </div>
										    <table class="table hover nowrap data-table">
                                                <thead>
                                                    <tr>
                                                        <th class="table-plus datatable-nosort">S.No</th>
                                                        <th>BO</th>
                                                        <th>Reference Code</th>
                                                        <th>DC Number</th>
                                                        <th>DC Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $qry = "SELECT a.*, d.order_code ";
                                                    $qry .= " FROM processing_list a ";
                                                    $qry .= " LEFT JOIN sales_order d ON a.order_id=d.id ";
                                                    $qry .= " WHERE a.type='sewing_output' ORDER BY a.id DESC ";
                                                    
                                                    $query = mysqli_query($mysqli, $qry);
                                                    $x = 1;
                                                    while ($sql = mysqli_fetch_array($query)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $x; ?></td>
                                                            <td><?= $sql['order_code']; ?></td>
                                                            <td><?= $sql['processing_code']; ?></td>
                                                            <td><?= $sql['dc_num'] ? $sql['dc_num'] : '-'; ?></td>
                                                            <td><?= $sql['dc_date'] ? $sql['dc_date'] : '-'; ?></td>
                                                            <td>
                                                                <?php if($sql['qc_approval']=='approved') { ?>
                                                                    <button type="button" class="btn btn-outline-success btn-sm">Approved</button>
                                                                <?php } else { ?>
                                                                    <button type="button" class="btn btn-outline-warning btn-sm">Pending</button>
                                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openQCmodal(<?= $sql['id']; ?>, <?= $tab; ?>)"><i class="icon-copy fa fa-info-circle" aria-hidden="true"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
										</div>
									</div>
									
								    <?php $tab = 3; ?>
									<div class="tab-pane fade" id="tab_checking" role="tabpanel">
										<div class="pd-20">
										    <div style="padding: 15px;" class="text-center u">
										        <h5 style="color: #564747;">Checking Out</h5>
										    </div>
										    
										    <table class="table hover nowrap data-table">
                                                <thead>
                                                    <tr>
                                                        <th class="table-plus datatable-nosort">S.No</th>
                                                        <th>BO</th>
                                                        <th>Reference Code</th>
                                                        <th>DC Number</th>
                                                        <th>DC Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $qry = "SELECT a.*, d.order_code ";
                                                    $qry .= " FROM processing_list a ";
                                                    $qry .= " LEFT JOIN sales_order d ON a.order_id=d.id ";
                                                    $qry .= " WHERE a.type='checking_list' ORDER BY a.id DESC ";
                                                    
                                                    $query = mysqli_query($mysqli, $qry);
                                                    $x = 1;
                                                    while ($sql = mysqli_fetch_array($query)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $x; ?></td>
                                                            <td><?= $sql['order_code']; ?></td>
                                                            <td><?= $sql['processing_code']; ?></td>
                                                            <td><?= $sql['dc_num'] ? $sql['dc_num'] : '-'; ?></td>
                                                            <td><?= $sql['dc_date'] ? $sql['dc_date'] : '-'; ?></td>
                                                            <td>
                                                                <?php if($sql['qc_approval']=='approved') { ?>
                                                                    <button type="button" class="btn btn-outline-success btn-sm">Approved</button>
                                                                <?php } else { ?>
                                                                    <button type="button" class="btn btn-outline-warning btn-sm">Pending</button>
                                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="openQCmodal(<?= $sql['id']; ?>, <?= $tab; ?>)"><i class="icon-copy fa fa-info-circle" aria-hidden="true"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
										</div>
									</div>
									
								    <?php $tab = 4; ?>
									<div class="tab-pane fade" id="tab_ironing" role="tabpanel">
										<div class="pd-20">
										    <div style="padding: 15px;">
										        <h5 style="color: #564747;">Ironing Out</h5>
										    </div>
										    <table class="table hover nowrap">
                                                <thead>
                                                    <tr>
                                                        <th class="table-plus datatable-nosort">S.No</th>
                                                        <th>Order Id</th>
                                                        <th>Order Qty</th>
                                                        <th>Production Qty</th>
                                                        <th>Checked Qty</th>
                                                        <th>Accepted Qty</th>
                                                        <th>Balance Qty</th>
                                                        <th>Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $qry = "SELECT a.*, b.order_code
                                                    FROM ironing_detail a  
                                                    LEFT JOIN sales_order b ON a.order_id = b.id
                                                    GROUP BY a.order_id
                                                    ORDER BY a.id DESC";
                                                    $query = mysqli_query($mysqli, $qry);
                                                    $x = 1;
                                                    while ($sql = mysqli_fetch_array($query)) {
                                                        
                                                        $mcvs = mysqli_fetch_array(mysqli_query($mysqli,"SELECT sum(total_qty) as total_qty, sum(total_excess) as total_excess FROM sales_order_detalis WHERE sales_order_id = ".$sql['order_id']));
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?= $x; ?>
                                                            </td>
                                                            <td>
                                                                <?= $sql['order_code']; ?>
                                                            </td>
                                                            <td><?= $mcvs['total_qty']; ?></td>
                                                            <td class="tot_ironing<?= $x; ?>"><?= ($mcvs['total_qty']+$mcvs['total_excess']); ?></td>
                                                            <td><input type="number" name="" class="form-control ch_ironing<?= $x; ?>" onkeyup="checked_qty('ironing', <?= $x; ?>)" placeholder="Checked Qty"></td>
                                                            <td><input type="number" name="" class="form-control acc_ironing<?= $x; ?>" onkeyup="checked_qty('ironing', <?= $x; ?>)" placeholder="Accepted Qty"></td>
                                                            <td><input type="number" name="" class="form-control bal_ironing<?= $x; ?>" placeholder="Balance Qty" readonly></td>
                                                            <td><input type="text" name="" class="form-control" placeholder="Remark"></td>
                                                        </tr>
                                                        <?php $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
										</div>
									</div>
									
								    <?php $tab = 5; ?>
									<div class="tab-pane fade" id="tab_packing" role="tabpanel"> 
										<div class="pd-20">
										    <div style="padding: 15px;">
										        <h5 style="color: #564747;">Packing Out</h5>
										    </div>
										    <table class="table hover nowrap">
                                                <thead>
                                                    <tr>
                                                        <th class="table-plus datatable-nosort">S.No</th>
                                                        <th>Order Id</th>
                                                        <th>Order Qty</th>
                                                        <th>Production Qty</th>
                                                        <th>Checked Qty</th>
                                                        <th>Accepted Qty</th>
                                                        <th>Balance Qty</th>
                                                        <th>Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $qry = "SELECT a.*, b.order_code
                                                    FROM packing_detail a  
                                                    LEFT JOIN sales_order b ON a.order_id = b.id
                                                    GROUP BY a.order_id
                                                    ORDER BY a.id DESC";
                                                    $query = mysqli_query($mysqli, $qry);
                                                    $x = 1;
                                                    while ($sql = mysqli_fetch_array($query)) {
                                                        
                                                        $mcvs = mysqli_fetch_array(mysqli_query($mysqli,"SELECT sum(total_qty) as total_qty, sum(total_excess) as total_excess FROM sales_order_detalis WHERE sales_order_id = ".$sql['order_id']));
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?= $x; ?>
                                                            </td>
                                                            <td>
                                                                <?= $sql['order_code']; ?>
                                                            </td>
                                                            <td><?= $mcvs['total_qty']; ?></td>
                                                            <td class="tot_packing<?= $x; ?>"><?= ($mcvs['total_qty']+$mcvs['total_excess']); ?></td>
                                                            <td><input type="number" name="" class="form-control ch_packing<?= $x; ?>" onkeyup="checked_qty('packing', <?= $x; ?>)" placeholder="Checked Qty"></td>
                                                            <td><input type="number" name="" class="form-control acc_packing<?= $x; ?>" onkeyup="checked_qty('packing', <?= $x; ?>)" placeholder="Accepted Qty"></td>
                                                            <td><input type="number" name="" class="form-control bal_packing<?= $x; ?>" placeholder="Balance Qty" readonly></td>
                                                            <td><input type="text" name="" class="form-control" placeholder="Remark"></td>
                                                        </tr>
                                                        <?php $x++;
                                                    } ?>
                                                </tbody>
                                            </table>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						
						
						<div class="modal fade bs-example-modal-lg" id="QC_statusModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-top " style="max-width: 1200px;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myLargeModalLabel">Quality Control</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <form method="POST" id="QC_form">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Type :</label>
                                                    <select class="custom-select2 form-control" style="width:100%" name="s_type" id="s_type" onchange="updateQC_status()">
                                                        <option value="total">Inward Total Wise</option>
                                                        <option value="part_color">Part & Color Wise</option>
                                                        <option value="size">Part & Color & Size Wise</option>
                                                    </select>
                                                    
                                                    <input type="hidden" id="t_id">
                                                    <input type="hidden" id="t_tab">
                                                </div>
                                                
                                                <div class="col-md-3"></div>
                                                <div class="col-md-3">
                                                    <label>QC File :</label>
                                                    
                                                    <input type="file" class="form-control" name="qc_file" id="qc_file" multiple>
                                                </div>
                                            </div>
                                            <br>
                                            
                                            <div id="QC_tbody" style="overflow-y:auto;"></div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-primary addbtn" onclick="saveQC()">Save QC</button>
                                            <button type="button" class="btn btn-outline-secondary closebtn" data-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        function saveQC() {
            var form = $("#QC_form").serialize();
            
            var formData = new FormData();
                
            var qc_file = $("#qc_file")[0].files[0];
            formData.append('qc_file', qc_file);
            
            // $.each(form.split('&'), function(index, item) {
            //     var splitItem = item.split('=');
            //     data.append(splitItem[0], decodeURIComponent(splitItem[1]));
            // });
            
            $.ajax({
                type :'POST',
                url :'ajax_action.php?saveQC=1',
                data : formData,
                contentType: false,
                processData: false,
                
                success:function(msg) {
                    var json = $.parseJSON(msg);
                    if(json.res==0) {
                        message_reload('success', 'QC Saved!', 1500);
                    } else {
                        message_error();
                    }
                }
            });
        }
    </script>
    
    <script>
        function qcValicate(tid) {
            var max_q = $("#max_q" + tid).val();
            
            var app_q = $("#app_q" + tid).val();
            var cri_q = $("#cri_q" + tid).val();
            var maj_q = $("#maj_q" + tid).val();
            var min_q = $("#min_q" + tid).val();
            
            var sum = 0;
            $(".sub_t" + tid).each(function() {
                
                var val = ($(this).val() == "") ? 0 : $(this).val();
                
                if(max_q < (parseInt(sum) + parseInt(val))) {
                    message_noload('error', 'Quantity exceed!', 700);
                    $(this).val('');
                } else {
                    sum += parseInt(val);
                }
            })
        }
    </script>
    
    <script>
        function updateQC_status() {
            
            $("#QC_tbody").html('');
            
            var id =$("#t_id").val();
            var tab = $("#t_tab").val();
            var type = $("#s_type").val();
            
            $.ajax({
                type :'POST',
                url :'ajax_search.php?getQC_status=1&type=' + type + '&id=' + id + '&tab=' + tab,
                
                success:function(msg) {
                    var json = $.parseJSON(msg);
                    
                    $("#QC_tbody").html(json.body);
                    
                    var a = 0;
                    $('.custom-select2').each(function() {
                        
                        var id = $(this).attr('id');
                        
                        $("#" + id).select2();
                        
                        a++;
                    })
                }
            })
            
            $("#QC_statusModal").modal('show');
        }
    </script>
    
    <script>
        function openQCmodal(id, tab) {
            
            $("#t_id").val(id);
            $("#t_tab").val(tab);
            
            updateQC_status();
            
            $("#QC_statusModal").modal('show');
        }
    </script>
    
    <script>
        function checked_qty(type, id) {
            
            var tot_qty = $(".tot_"+type+id).text();
            var ch_qty = $(".ch_"+type+id).val();
            var acc_qty = $(".acc_"+ type + id).val();
            
            val = parseInt(tot_qty) - (parseInt(ch_qty) + parseInt(acc_qty));
            $(".bal_"+ type + id).val(val);
        }
    </script>
    
    <script>
        $(document).ready(function(){
            $('#exampleModal').on('hidden.bs.modal', function (e) {
              console.log('Modal is closed');
              yourFunction();
            });
        
            function yourFunction() {
              alert('Your function is called when modal is closed');
            }
        });
    </script>
    
</body>

</html>