<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_GET['id'])) {
    $ID = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id=" . $ID));
} else {
    $ID = '';
}
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
    <title>BENSO GARMENTING - View Budget
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

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="pd-20 card-box mb-30">
                    <?php if(BUDGET_VIEW!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Budget for sales order of <span style="color:red">
                                <?= $sql['order_code']; ?>
                            </span>
                            <a class="btn btn-outline-primary" href="cmt_budget.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Budget List</a>
                        </h4>
                    </div>

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
								<div class="pd-20">
								    <h5 style="color: #a5a5a5;">Fabric Budget View</h5>
								</div>
								<?php if(BUDGET_ACCESSORIES!=1) { action_denied(); } else { ?>
								
							        <?php
                                    $qyy = "SELECT a.*, b.budget_type ";
                                    $qyy .= " FROM budget_process a ";
                                    $qyy .= " LEFT JOIN process b ON a.process = b.id ";
                                    $qyy .= " WHERE a.so_id = '". $ID ."' AND a.budget_for = 'Fabric Budget' GROUP BY a.process ORDER BY a.id ASC ";

							        $pss = mysqli_query($mysqli, $qyy);
							        
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
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $fab_q = "SELECT * ";    
                                                                    $fab_q .= " FROM fabric_requirements a ";    
                                                                    $fab_q .= " WHERE a.process_id = '". $main['process'] ."' AND a.order_id = '". $ID  ."' ";
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
    								<?php } ?>
        								
                                <?php } ?>
							</div>
							
							<div class="tab-pane fade" id="budAccessories" role="tabpanel">
								<div class="pd-20">
								    <h5 style="color: #a5a5a5;">Accessories Budget View</h5>
								</div>
							    
							    <?php if(BUDGET_ACCESSORIES!=1) { action_denied(); } else { ?>
						    
                                    <div class="row">
                                        <div class="col-md-12" style="overflow-y: auto;">
                                            <h5 style="padding: 20px;">Process</h5>
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
                                                    
                                                    $opid = mysqli_query($mysqli, "SELECT a.*, b.acc_name FROM budget_process a LEFT JOIN mas_accessories b ON a.accessories=b.id WHERE a.budget_for = 'Accessories Budget' AND a.so_id='" . $ID . "'");
                                                    if (mysqli_num_rows($opid) > 0) {
                                                        while ($row = mysqli_fetch_array($opid)) {
            
                                                            ?>
                                                            <tr>
                                                                <td><?= $row['acc_name']; ?></td>
                                                                <td><?= $row['rate']; ?></td>
                                                                <td><?= $row['revised_rate']; ?></td>
                                                                <td><?= $row['rework_rate']; ?></td>
                                                                <td>
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
								    <h5 style="color: #a5a5a5;">Production Budget View</h5>
								</div>
							    <?php if(BUDGET_PRODUCTION!=1) { action_denied(); } else { ?>
						    
                                    <div class="row">
                                        <div class="col-md-12" style="overflow-y: auto;">
                                            <h5 style="padding: 20px;">Process</h5>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Process</th>
                                                        <th>Rate</th>
                                                        <th>Revised Rate</th>
                                                        <th>Rework Rate</th>
                                                        <th class="table-plus datatable-nosort prevent-select">Approve Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="processBody">
                                                    <?php
                                                    
                                                    $opid = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM budget_process a LEFT JOIN process b ON a.process=b.id WHERE a.budget_for = 'Production Budget' AND a.so_id='" . $ID . "'");
                                                    if (mysqli_num_rows($opid) > 0) {
                                                        while ($row = mysqli_fetch_array($opid)) {
            
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="budget_process[]" id=""
                                                                        value="<?= $row['id']; ?>">
                                                                    <input type="hidden" name="process_id[]" id=""
                                                                        value="' . $row['id'] . '">
                                                                    <?= $row['process_name']; ?>
                                                                    <i class="icon-copy fa fa-eye showSubprocess"
                                                                        data-id="<?= $row['process']; ?>" aria-hidden="true"
                                                                        style="float: right;font-size: 20px;" title="Sub Process List"></i>
                                                                </td>
                                                                <td>
                                                                    <?= $row['rate']; ?>
                                                                </td>
                                                                <td>
                                                                    <?= $row['revised_rate']; ?>
                                                                </td>
                                                                <td>
                                                                    <?= $row['rework_rate']; ?>
                                                                </td>
                                                                <td>
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
						</div>
					</div>
                </div>
            </div>

            <?php include('includes/footer.php');
            include('modals.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

</body>

</html>