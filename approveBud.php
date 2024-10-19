<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


$style_id = $_GET['id'];
$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_detalis WHERE id=" . $style_id));
?>
<!DOCTYPE html>
<html>

<head>

    <style>
        .table td,
        .table th {
            border-top: 0px solid #dee2e6 !important;
        }
    </style>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Budget Approval
    </title>

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

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">


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
    .btn-outline-success {
        color: #28a745 !important;
        border-color: #28a745;
    }
    
    .btn-outline-danger {
        color: #dc3545 !important;
    }
    
    .btn-outline-danger:hover {
        color: #fff !important;
    }

    .btn-outline-success:hover {
        color: #fff !important;
        background-color: #28a745;
        border-color: #28a745;
    }
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="card-box mb-30">
					<div class="pd-20">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="text-blue">Budget Approval</h5>
                            </div>
                            <div class="col-md-3"><p class="text-blue-50">BO: <span class="text-danger u"><?= sales_order_code($sql['sales_order_id']); ?></span> </p></div>
                            <div class="col-md-3"><p class="text-blue-50">Style: <span class="text-danger u"><?= sales_order_style($sql['id']); ?></span> </p></div>

                            <div class="col-md-3">
                                <div class="btn-group mr-2 float-right" role="group" aria-label="First group">
                                    <a class="btn btn-outline-primary" href="budget_app.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Approval List</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pd-20 card-box mb-30">
                    <?php if(BUDGET_APPROVAL!=1) { action_denied(); exit; } ?>

                    <div class="tab">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active text-blue" data-toggle="tab" href="#budFabric" role="tab" aria-selected="false">Fabric Budget</a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-blue" data-toggle="tab" href="#budAccessories" role="tab" aria-selected="false">Accessories Budget</a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-blue" data-toggle="tab" href="#budProduction" role="tab" aria-selected="true">Production Budget</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="budFabric" role="tabpanel">
                            
							    <?php if(BUDGET_FABRIC!=1) { action_denied(); } else {
							    
							    // $pss = mysqli_query($mysqli, "SELECT b.process_name, b.budget_type, a.process_id FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id = b.id WHERE a.sales_order_id = '". $style_id ."' GROUP BY a.process_id ORDER BY a.process_order ASC");
						        $qyy = "SELECT a.*, b.budget_type ";
                                $qyy .= " FROM budget_process a ";
                                $qyy .= " LEFT JOIN process b ON a.process = b.id ";
                                $qyy .= " WHERE a.style_id = '". $style_id ."' AND a.budget_for = 'Fabric Budget' GROUP BY a.process ORDER BY a.id ASC ";

                                $pss = mysqli_query($mysqli, $qyy);
                                
						        ?>
								<div class="pd-20">
								    <h5 style="color: #a5a5a5;">Fabric Budget Approval</h5>
								    
    								<div style="float: right;padding: 12px;">
    								    <?php if(mysqli_num_rows($pss)>0) { ?>
                                            <a class=""><input type="checkbox" id="bud_fabric_main" class="bud_fabric_main" onclick="CheckAllcoxes('bud_fabric')"> <label for="bud_fabric_main">Check All</label>&nbsp;&nbsp;&nbsp;</a>
                                            <a class="btn btn-outline-success" onclick="saveApproval('bud_fabric', 'true')">Approve</a>
                                            <a class="btn btn-outline-danger" onclick="saveApproval('bud_fabric', 'false')">Reject</a>
                                        <?php } ?>
                                    </div>
                                        <br>
								</div>
						        <?php
						            $fnm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE style_id = '". $style_id ."' AND budget_for = 'Fabric Budget' "));
						        
						            if(mysqli_num_rows($pss)>0) {
					            ?>
								    <div class="accordion" id="accordionExample" style="padding: 25px;">
    								    <?php
    								        $x = 1;
    								        while($main = mysqli_fetch_array($pss)) {
    								    ?>
    								    <div class="card">
                                            <div class="card-header" id="heading<?= $x; ?>">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#processNum<?= $x; ?>" aria-expanded="true" aria-controls="processNum<?= $x; ?>">
                                                        <i class="icon-copy dw dw-right-arrow-4"></i> <?= process_name($main['process']).' - '.$main['budget_type']; ?>
                                                    </button>
                                                </h2>
                                            </div>
                                            
                                            <div id="processNum<?= $x; ?>" class="collapse <?= ($x==1) ? 'show' : 'show'; ?>" aria-labelledby="heading<?= $x; ?>" data-parent="#accordionExample">
                                                <div class="card-body" style="overflow-y:auto;">

                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <?php if($main['budget_type']=='Yarn') { ?>
                                                                    <th>Yarn</th>
                                                                    <th>Req Wt <small>(Kg)</small></th>
                                                                    <th>Rate</th>
                                                                    <th>Revised Rate</th>
                                                                    <th>Rework Rate</th>
                                                                <?php } else if($main['budget_type']=='Fabric') { ?>
                                                                    <th>Fabric</th>
                                                                    <th>Req Wt <small>(Kg)</small></th>
                                                                    <th>Rate</th>
                                                                    <th>Revised Rate</th>
                                                                    <th>Rework Rate</th>
                                                                <?php } else if($main['budget_type']=='Dyeing Color') { ?>
                                                                    <th>Fabric</th>
                                                                    <th>Dyeing Color</th>
                                                                    <th>Req Wt <small>(Kg)</small></th>
                                                                    <th>Rate</th>
                                                                    <th>Revised Rate</th>
                                                                    <th>Rework Rate</th>
                                                                <?php } else if($main['budget_type']=='AOP Design') { ?>
                                                                    <th>Fabric</th>
                                                                    <th>AOP</th>
                                                                    <th>Req Wt <small>(Kg)</small></th>
                                                                    <th>Rate</th>
                                                                    <th>Revised Rate</th>
                                                                    <th>Rework Rate</th>
                                                                <?php } ?>
                                                                    <th>Approve Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $fab_q = "SELECT * ";    
                                                                $fab_q .= " FROM fabric_requirements a ";    
                                                                $fab_q .= " WHERE a.process_id = '". $main['process'] ."' AND a.style_id = '". $style_id  ."' ";
                                                                $fabQ = mysqli_query($mysqli, $fab_q);
                                                                $m=1;
                                                                while($fab_res = mysqli_fetch_array($fabQ)) {
                                                                    $req = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE requirement_id = " . $fab_res['id']));
                                                            ?>
                                                            <tr>
                                                                <?php if($main['budget_type']=='Yarn') { ?>
                                                                    <td><?= mas_yarn_name($fab_res['yarn_id']); ?></td>
                                                                    <td><?= $fab_res['req_wt']; ?></td>
                                                                    <td><?= ($req['rate']=='0.00') ? '-' : $req['rate']; ?></td>
                                                                    <td><?= ($req['revised_rate']=='0.00') ? '-' : $req['revised_rate']; ?></td>
                                                                    <td><?= ($req['rework_rate']=='0.00') ? '-' : $req['rework_rate']; ?></td>
                                                                <?php } else if($main['budget_type']=='Fabric') { ?>
                                                                    <td><?= fabric_name($fab_res['fabric_id']); ?></td>
                                                                    <td><?= $fab_res['req_wt']; ?></td>
                                                                    <td><?= ($req['rate']=='0.00') ? '-' : $req['rate']; ?></td>
                                                                    <td><?= ($req['revised_rate']=='0.00') ? '-' : $req['revised_rate']; ?></td>
                                                                    <td><?= ($req['rework_rate']=='0.00') ? '-' : $req['rework_rate']; ?></td>
                                                                <?php } else if($main['budget_type']=='Dyeing Color') { ?>
                                                                    <td><?= fabric_name($fab_res['fabric_id']); ?></td>
                                                                    <td><?= color_name($fab_res['color']); ?></td>
                                                                    <td><?= $fab_res['req_wt']; ?></td>
                                                                    <td><?= ($req['rate']=='0.00') ? '-' : $req['rate']; ?></td>
                                                                    <td><?= ($req['revised_rate']=='0.00') ? '-' : $req['revised_rate']; ?></td>
                                                                    <td><?= ($req['rework_rate']=='0.00') ? '-' : $req['rework_rate']; ?></td>
                                                                <?php } else if($main['budget_type']=='AOP Design') { ?>
                                                                    <td><?= fabric_name($fab_res['fabric_id']); ?></td>
                                                                    <td><?= $fab_res['aop_name']; ?></td>
                                                                    <td><?= $fab_res['req_wt']; ?></td>
                                                                    <td><?= ($req['rate']=='0.00') ? '-' : $req['rate']; ?></td>
                                                                    <td><?= ($req['revised_rate']=='0.00') ? '-' : $req['revised_rate']; ?></td>
                                                                    <td><?= ($req['rework_rate']=='0.00') ? '-' : $req['rework_rate']; ?></td>
                                                                <?php } ?>
                                                                    <td>
                                                                        <input type="checkbox" value="<?= $req['id']; ?>" name="processbox[]" class="bud_fabric">
                                                                        <?php
                                                                        if ($req['is_approved'] == 'true') {
                                                                            print '<span class="border border-success rounded text-success">Approved</span>';
                                                                        } else if ($req['is_approved'] == 'false') {
                                                                            print '<span class="border border-danger rounded text-danger">Rejected</span>';
                                                                        } else {
                                                                            print '<span class="border border-warning rounded text-warning">Waiting</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>

                							    </div>
                							</div>
            							</div>
            							<?php $x++; } ?>
    								</div>
    								
                                <?php } else { ?>
                                    <div class="pd-20" style="text-align:center; color: red; text-decoration:underline;">
    								    <p>Fabric Program Not Started!</p>
    								</div>
								<?php } } ?>
							</div>
							
							<div class="tab-pane fade" id="budAccessories" role="tabpanel">
								<div class="pd-20">
								    <h5 style="color: #a5a5a5;">Accessories Budget Approval</h5>
								</div>
                                
							    <?php if(BUDGET_ACCESSORIES!=1) { action_denied(); } else { 
							    
							    $opid = mysqli_query($mysqli, "SELECT a.*, b.acc_name FROM budget_process a LEFT JOIN mas_accessories b ON a.accessories=b.id WHERE a.budget_for = 'Accessories Budget' AND a.style_id='" . $style_id . "'");
							    ?>
                                <div class="row">
                                    <div class="col-md-12" style="overflow-y: auto;">
                                        <div style="float: right;padding: 12px;">
                                            <?php if (mysqli_num_rows($opid) > 0) { ?>
                                                <a class=""><input type="checkbox" id="bud_accessories_main" class="bud_accessories_main" onclick="CheckAllcoxes('bud_accessories')"> <label for="bud_accessories_main">Check All</label>&nbsp;&nbsp;&nbsp;</a>
                                                <a class="btn btn-outline-success" onclick="saveApproval('bud_accessories', 'true')">Approve</a>
                                                <a class="btn btn-outline-danger" onclick="saveApproval('bud_accessories', 'false')">Reject</a>
                                            <?php } ?>
                                        </div>
                                        
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Accessories</th>
                                                    <th>Rate</th>
                                                    <th>Revised Rate</th>
                                                    <th>Rework Rate</th>
                                                    <th class="table-plus datatable-nosort prevent-select">Approve Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="processBody">
                                                <?php
                                                
                                                if (mysqli_num_rows($opid) > 0) {
                                                    while ($row = mysqli_fetch_array($opid)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $row['acc_name']; ?></td>
                                                            <td><?= $row['rate']; ?></td>
                                                            <td><?= $row['revised_rate']; ?></td>
                                                            <td><?= $row['rework_rate']; ?></td>
                                                            <td>
                                                                    <input type="checkbox" value="<?= $row['id']; ?>" name="processbox[]" class="bud_accessories">
                                                                <?php
                                                                if ($row['is_approved'] == 'true') {
                                                                    print '<span class="border border-success rounded text-success">Approved</span>';
                                                                } else if ($row['is_approved'] == 'false') {
                                                                    print '<span class="border border-danger rounded text-danger">Rejected</span>';
                                                                } else {
                                                                    print '<span class="border border-warning rounded text-warning">Waiting</span>';
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } else {
                                                    print '<tr><td colspan="5" align="center">Budget Not Created</td></tr>';
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php } ?>
							</div>
							
							<div class="tab-pane fade" id="budProduction" role="tabpanel">
								<div class="pd-20">
								    <h5 style="color: #a5a5a5;">Production Budget Approval</h5>
								</div>
                                <?php if(BUDGET_PRODUCTION!=1) { action_denied(); } else {
                                    $opid = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM budget_process a LEFT JOIN process b ON a.process=b.id WHERE a.budget_for = 'Production Budget' AND a.style_id='" . $style_id . "'");
                                ?>
                                    <div class="row">
                                        <div class="col-md-12" style="overflow-y: auto;">
                                            
                                            <div style="float: right;padding: 12px;">
                                                <?php if (mysqli_num_rows($opid) > 0) { ?>
                                                    <a class=""><input type="checkbox" id="bud_production_main" class="bud_production_main" onclick="CheckAllcoxes('bud_production')"> <label for="bud_production_main">Check All</label>&nbsp;&nbsp;&nbsp;</a>
                                                    <a class="btn btn-outline-success" onclick="saveApproval('bud_production', 'true')">Approve</a>
                                                    <a class="btn btn-outline-danger" onclick="saveApproval('bud_production', 'false')">Reject</a>
                                                <?php } ?>
                                            </div>
                                            
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr style="border-bottom: 1px solid #dee2e6;">
                                                        <th>Process</th>
                                                        <th>Rate</th>
                                                        <th>Revised Rate</th>
                                                        <th>Rework Rate</th>
                                                        <th>Scanning Type</th>
                                                        <th class="table-plus datatable-nosort prevent-select">Approve Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="processBody">
                                                    <?php
                                                    if (mysqli_num_rows($opid) > 0) {
                                                        while ($row = mysqli_fetch_array($opid)) {
                                                            
                                                            if($row['bud_type'] == 'all') {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="budget_process[]" id="" value="<?= $row['id']; ?>">
                                                                        <input type="hidden" name="process_id[]" id="" value="' . $row['id'] . '">
                                                                        <?= $row['process_name']; ?>
                                                                        <!--<i class="icon-copy fa fa-eye showSubprocess" data-id="<?= $row['process']; ?>" aria-hidden="true" style="float: right;font-size: 20px;" title="Sub Process List"></i>-->
                                                                    </td>
                                                                    <td><?= $row['rate']; ?></td>
                                                                    <td><?= $row['revised_rate']; ?></td>
                                                                    <td><?= $row['rework_rate']; ?></td>
                                                                    <td>
                                                                        <input type="radio" name="scan_type<?= $row['id']; ?>" id="pcs<?= $row['id']; ?>" value="Piece" <?= ($row['scanning_type'] == 'Piece') ? 'checked' : ''; ?> onclick="update_type('Piece', <?= $row['id']; ?>)">
                                                                        <label for="pcs<?= $row['id']; ?>">Piece</label>
                                                                        <input type="radio" name="scan_type<?= $row['id']; ?>" id="bundle<?= $row['id']; ?>" value="Bundle" <?= ($row['scanning_type'] == 'Bundle') ? 'checked' : ''; ?> onclick="update_type('Bundle', <?= $row['id']; ?>)">
                                                                        <label for="bundle<?= $row['id']; ?>">Bundle</label>
                                                                    </td>
                                                                    <td>
                                                                        <?php if (!isset($_GET['view'])) { ?>
                                                                            <input type="checkbox" value="<?= $row['id']; ?>" name="processbox[]" class="bud_production">
                                                                        <?php }
                                                                        if ($row['is_approved'] == 'true') {
                                                                            print '<span class="border border-success rounded text-success">Approved</span>';
                                                                        } else if ($row['is_approved'] == 'false') {
                                                                            print '<span class="border border-danger rounded text-danger">Rejected</span>';
                                                                        } else {
                                                                            print '<span class="border border-warning rounded text-warning">Waiting</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                        <?php } else { ?>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <p><?= $row['process_name']; ?></p>
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Combo</th>
                                                                                    <?= ($row['bud_type']=='combo_part') ? '<th>Part | Color</th>' : ''; ?>
                                                                                    <th>Rate</th>
                                                                                    <th>Revised Rate</th>
                                                                                    <th>Rework Rate</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                    $ff = mysqli_query($mysqli, "SELECT * FROM budget_process_partial WHERE budget_process = '". $row['id'] ."' AND bud_type = '".$row['bud_type']."'");
                                                                                    while($res = mysqli_fetch_array($ff)) {
                                                                                        if($res['bud_type'] == 'combo') {
                                                                                            $cm = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_combo WHERE id = ". $res['sod_combo']));
                                                                                            $combo = color_name($cm['combo_id']);
                                                                                        } else {
                                                                                            $cm = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id = ". $res['sod_part']));
                                                                                            $combo = color_name($cm['combo_id']);
                                                                                            $ptt = '<td>'. part_name($cm['part_id']).' | '.color_name($cm['color_id']) .'</td>';
                                                                                        }
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?= $combo ?></td>
                                                                                        <?= ($res['bud_type'] == 'combo_part') ? $ptt : ''; ?>
                                                                                        <td><?= $res['rate']; ?></td>
                                                                                        <td><?= $res['revised_rate']; ?></td>
                                                                                        <td><?= $res['rework_rate']; ?></td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <input type="radio" name="scan_type<?= $row['id']; ?>" id="pcs<?= $row['id']; ?>" value="Piece" <?= ($row['scanning_type'] == 'Piece') ? 'checked' : ''; ?> onclick="update_type('Piece', <?= $row['id']; ?>)">
                                                                        <label for="pcs<?= $row['id']; ?>">Piece</label>
                                                                        <input type="radio" name="scan_type<?= $row['id']; ?>" id="bundle<?= $row['id']; ?>" value="Bundle" <?= ($row['scanning_type'] == 'Bundle') ? 'checked' : ''; ?> onclick="update_type('Bundle', <?= $row['id']; ?>)">
                                                                        <label for="bundle<?= $row['id']; ?>">Bundle</label>
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <?php if (!isset($_GET['view'])) { ?>
                                                                            <input type="checkbox" value="<?= $row['id']; ?>" name="processbox[]" class="bud_production">
                                                                        <?php }
                                                                        if ($row['is_approved'] == 'true') {
                                                                            print '<span class="border border-success rounded text-success">Approved</span>';
                                                                        } else if ($row['is_approved'] == 'false') {
                                                                            print '<span class="border border-danger rounded text-danger">Rejected</span>';
                                                                        } else {
                                                                            print '<span class="border border-warning rounded text-warning">Waiting</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                        <?php } }
                                                    } else {
                                                        print '<tr><td colspan="5" align="center">Budget Not Created</td></tr>';
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
							</div>
						</div>
					</div>
                </div>
            </div>
            <?php
                include('includes/footer.php');
                include('modals.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        function update_type(val, id) {
            
            var data = { value: val, id: id};

            $.post('ajax_action.php?update_scantype', data, function(resp){

            });
        }
    </script>
    
    <script>
        function saveApproval(cls, val) {
            
            var namee = (cls=="bud_production") ? 'Production' : ((cls=="bud_accessories") ? 'Accessories' : 'Fabric');

            if(val=='true') {
                var nvl = 'Approve';
                var nvl1 = 'Approved';
            } else {
                var nvl = 'Reject';
                var nvl1 = 'Rejected';
            }
            
            swal({
                title: '' + nvl + ' '+ namee +' Budget?',
                text: "You want to " + nvl + " This?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, ' + nvl + ' it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    
                    var lnt = $('input.'+ cls +':checkbox:checked').length;
                    if(lnt==0) {
                        message_noload('warning', 'Check Checkbox to '+ nvl +'!', 1500);
                        return false;
                    }
                    
                    var checkedVals = $('.'+ cls +':checkbox:checked').map(function() {
                        return this.value;
                    }).get();
                    
                    var values = checkedVals.join(",");
                    var pid = <?= $style_id; ?>;

                    var data = {
                        id : values,
                        value : val,
                        pid : pid,
                        namee : namee,
                    };
                    
                    $.ajax({
                        type : 'POST',
                        url : 'ajax_action.php?updateBudgetApproval',
                        data : data,
                        
                        success : function(msg) {
                            message_reload('success', namee +' Budget '+nvl1, 1500);
                        }
                    })
                } else {
                    swal( 'Cancelled', '', 'error')
                }
            })
        }
    </script>
    
    <script>
        function CheckAllcoxes(cls) {
            var a = $("."+ cls +"_main").is(":checked");
            
            $("." + cls).attr('checked', a);
        }
    </script>

</body>

</html>