<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$data = array();

if (isset($_POST['savebudget'])) {
    
    echo '<pre>', print_r($_POST, 1); exit;

    for ($k = 0; $k < count($_REQUEST['process_id']); $k++) {
        $data = array(
            'so_id' => $_GET['id'],
            'process' => $_REQUEST['process_id'][$k],
            'category' => $_REQUEST['category_id'],
            'rate' => $_REQUEST['prate'][$k],
            'revised_rate' => $_REQUEST['revised_rate'][$k],
            'rework_rate' => $_REQUEST['rework_rate'][$k],
            'budget_for' => 'Production Budget',
        );
        
        $in = $up = 0;
        if (!empty($_REQUEST['budget_process'][$k])) {
            $qry = Update('budget_process', $data, " WHERE id = '" . $_REQUEST['budget_process'][$k] . "'");
            $up++;
        } else {
            if($_REQUEST['process_id'][$k]!="") {
                $qry = Insert('budget_process', $data);
                $in++;
            }
        }
    }
        
    if($up>0) {
        timeline_history('Update', 'budget_process', $_GET['id'], 'Production Budget Updated. Ref-Bo:'. $_REQUEST['timeline_ref']);
    }
    if($in>0) {
        timeline_history('Insert', 'budget_process', $_GET['id'], 'Production Budget Inserted. Ref-Bo:'. $_REQUEST['timeline_ref']);
    }

    for ($l = 0; $l < count($_REQUEST['sub_id']); $l++) {
        $subdata = array(
            'so_id' => $_GET['id'],
            'process' => $_REQUEST['pro_id'][$l],
            'subprocess' => $_REQUEST['sub_id'][$l],
            'price' => $_REQUEST['sub_price'][$l],
            'created_date' => date('Y-m-d H:i:s'),
        );

        if (empty($_REQUEST['budget_subprocess_id'][$l])) {
            $qry = Insert('budget_subprocess', $subdata);
        } else {
            $qry = Update('budget_subprocess', $subdata, " WHERE id = '" . $_REQUEST['budget_subprocess_id'][$l] . "'");
        }
    }
    
    for ($k = 0; $k < count($_REQUEST['newProcess']); $k++) {
        
        // $ndt = array(
        //     'category' => $_REQUEST['category_id'],
        //     'process_name' => $_REQUEST['newProcess'][$k],
        // );
        
        // $qry = Insert('process', $ndt);
        
        // $pinid = mysqli_insert_id($mysqli);
        
        // $inid = mysqli_insert_id($mysqli);
            
        $data = array(
            'so_id' => $_GET['id'],
            'process' => $_REQUEST['newProcess'][$k],
            'category' => $_REQUEST['category_id'],
            'rate' => $_REQUEST['rate1'][$k],
            'revised_rate' => $_REQUEST['rate2'][$k],
            'rework_rate' => $_REQUEST['rate3'][$k],
            'budget_for' => 'Production Budget',
        );
        
        if($_REQUEST['newProcess'][$k]!="") {
            $qry = Insert('budget_process', $data);
        }
        
        // timeline_history('Insert', 'budget_process', $pinid, 'New Process Added From Budget Entry');
    }
    

    $_SESSION['msg'] = "Production Budget Saved";

    header("Location:add-budget.php?id=". $_GET['id']);

    // header("Location:cmt_budget.php");
    
    exit;
} else if (isset($_POST['saveFabricBudget'])) {
    // echo '<pre>'.print_r($_POST, 1); die;
    
    for($m=0;$m<count($_REQUEST['fabric_process_id']); $m++) {
        
        $np = $_REQUEST['fabric_process_id'][$m];
        $typ = $_REQUEST['fabric_budget_type'][$m];
        
        for($p=0; $p<count($_REQUEST['fabric_Rate'.$np]); $p++) {
        
            if($typ=='Yarn') {
                $typ_arr = array(
                    'yarn_id' =>   $_REQUEST['fabric_yarn'.$np][$p],
                    'req_wt' =>   $_REQUEST['fabric_mixedPer'.$np][$p],
                );
            } else if($typ=='Fabric') {
                $typ_arr = array(
                    'fabric' =>   $_REQUEST['fabric_fabric'.$np][$p],
                );
            } else if($typ=='Dyeing Color') {
                $typ_arr = array(
                    'dyeing_color' =>   $_REQUEST['fabric_dyeing_color'.$np][$p],
                    'fabric' =>   $_REQUEST['fabric_id'.$np][$p],
                );
            } else {
                $typ_arr = array();
            }
            
            $indata = array(
                'so_id' => $_GET['id'],
                'budget_for' => 'Fabric Budget',
                'process' => $np,
                'budget_process_type' => $typ,
                'rate' => $_REQUEST['fabric_Rate'.$np][$p],
                'revised_rate' => $_REQUEST['fabric_Revised'.$np][$p],
                'rework_rate' => $_REQUEST['fabric_Rework'.$np][$p],
            );
            
            $finArr = array_merge($typ_arr, $indata);
            
            if($_REQUEST['inidFabBud'.$np][$p]=="") {
                $qry = Insert('budget_process', $finArr);
            } else {
                $qry = Update('budget_process', $finArr, " WHERE id = '" . $_REQUEST['inidFabBud'.$np][$p] . "'");
            }
        }
    }
    
    timeline_history('Insert', 'budget_process', $_GET['id'], 'Fabric Budget Inserted or Updated.');

    $_SESSION['msg'] = "Fabric Budget Saved";

    // header("Location:cmt_budget.php");
    header("Location:add-budget.php?id=". $_GET['id']);

    exit;
} else if (isset($_POST['saveAccessoriesBudget'])) {
    // echo '<pre>'.print_r($_POST, 1); die;
    
    for($m=0;$m<count($_REQUEST['access_']); $m++) {
        
            $indata = array(
                'so_id' => $_GET['id'],
                'budget_for' => 'Accessories Budget',
                'accessories' => $_REQUEST['access_'][$m],
                'rate' => $_REQUEST['access_Rate'][$m],
                'revised_rate' => $_REQUEST['access_Revised'][$m],
                'rework_rate' => $_REQUEST['access_Rework'][$m],
            );
            
            if($_REQUEST['inidAccessBud'][$m]=="") {
                $qry = Insert('budget_process', $indata);
            } else {
                $qry = Update('budget_process', $indata, " WHERE id = '" . $_REQUEST['inidAccessBud'][$m] . "'");
            }
    }
    
    timeline_history('Insert', 'budget_process', $_GET['id'], 'Accessories Budget Inserted or Updated.');

    $_SESSION['msg'] = "Accessories Budget Saved";

    // header("Location:cmt_budget.php");
    header("Location:add-budget.php?id=". $_GET['id']);

    exit;
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id=" . $id));
} else {
    $id = '';
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
    <title>BENSO - Add Budget
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
        
        <?php  if ($_SESSION['msg'] != '') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?= $_SESSION['msg']; ?>.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php }
        $_SESSION['msg'] = '';
        ?>
        
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <?php if(BUDGET_ADD!=1 || BUDGET_EDIT!=1) { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Important!</strong> 
                        <?= (BUDGET_ADD==1) ? '' : "You Don't Have a Permission To Add Budget."; ?> <?= (BUDGET_EDIT==1) ? '' : "You Don't Have a Permission To Edit Budget."; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="pd-20 card-box mb-30">
                    <?php //if(BUDGET_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Budget for sales order of <span style="color:red"><?= $sql['order_code']; ?></span>
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
								    <h5 style="color: #a5a5a5;">Fabric Budget</h5>
								</div>
								<?php if(BUDGET_FABRIC!=1) { action_denied(); } else { ?>
								    <form id="fabricForm" method="POST" enctype="multipart/form-data">
								        <?php
								        
								        $fnm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE so_id = '". $_GET['id'] ."' AND budget_for = 'Fabric Budget' "));
								        
								        
								            // $pss = mysqli_query($mysqli, "SELECT b.process_name, b.budget_type, a.process_id FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id = b.id WHERE a.sales_order_id = '". $_GET['id'] ."' GROUP BY a.process_id ORDER BY a.process_order ASC");
								            
								            $pss = mysqli_query($mysqli, "SELECT * FROM fabric_requirements WHERE order_id = '". $_GET['id'] ."'");
								            if(mysqli_num_rows($pss)>0) {
							            ?>
        								    <div class="accordion" id="accordionExample" style="padding: 25px;">
            								    <?php
            								        $x = 1;
            								        while($uiop = mysqli_fetch_array($pss)) {
            								    ?>
            								    <div class="card">
                                                    <div class="card-header" id="heading<?= $x; ?>">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#processNum<?= $x; ?>" aria-expanded="true" aria-controls="processNum<?= $x; ?>">
                                                                <i class="icon-copy dw dw-right-arrow-4"></i> <?= $uiop['process_name'].' - '.$uiop['budget_type']; ?>
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    
                                                    <div id="processNum<?= $x; ?>" class="collapse <?= ($x==1) ? 'show' : ''; ?>" aria-labelledby="heading<?= $x; ?>" data-parent="#accordionExample">
                                                        <div class="card-body" style="overflow-y:auto;">
                                                            <input type="hidden" name="fabric_process_id[]" value="<?= $pid = $uiop['process_id']; ?>">
                                                            <input type="hidden" name="fabric_budget_type[]" value="<?= $uiop['budget_type']; ?>">
                                                            
                                                            <?php if($uiop['budget_type']=='Yarn') { ?>
                                                                <table class="table table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Yarn</th>
                                                                            <th>Req Wt <small>(Kg)</small></th>
                                                                            <th>Rate</th>
                                                                            <th>Revised Rate</th>
                                                                            <th>Rework Rate</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            $fab_yrn = mysqli_query($mysqli, "SELECT a.yarn_id, b.yarn_name, sum(a.mixed) as mixedPer FROM sales_order_fabric_components_yarn a LEFT JOIN mas_yarn b ON a.yarn_id = b.id WHERE a.sales_order_id='". $_GET['id'] ."' GROUP BY a.yarn_id");
                                                                            while($yarnFth = mysqli_fetch_array($fab_yrn)) {
                                                                                
                                                                                $req = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE so_id = '". $_GET['id'] ."' AND budget_process_type = 'Yarn' AND yarn_id = '". $yarnFth['yarn_id'] ."' AND process = '". $uiop['process_id'] ."'"));
                                                                        ?>
                                                                            <tr>
                                                                                <td><input type="hidden" name="fabric_yarn<?= $pid; ?>[]" value="<?= $yarnFth['yarn_id']; ?>"> <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req['id']; ?>"> <?= $yarnFth['yarn_name']; ?></td>
                                                                                <td><input type="hidden" name="fabric_mixedPer<?= $pid; ?>[]" value="<?= $yarnFth['mixedPer']; ?>"><?= $yarnFth['mixedPer']; ?></td>
                                                                                <td><input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req['rate']=='0.00') ? '' : $req['rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req['revised_rate']=='0.00') ? '' : $req['revised_rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req['rework_rate']=='0.00') ? '' : $req['rework_rate']; ?>"></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php } else if($uiop['budget_type']=='Fabric') { ?>
                                                                <table class="table table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Fabric</th>
                                                                            <th>Rate</th>
                                                                            <th>Revised Rate</th>
                                                                            <th>Rework Rate</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            $fab_fab = mysqli_query($mysqli, "SELECT a.fabric, b.fabric_name FROM sales_order_fabric_program a LEFT JOIN fabric b ON a.fabric = b.id WHERE a.sales_order_id='". $_GET['id'] ."' GROUP BY a.fabric");
                                                                            while($fabFth = mysqli_fetch_array($fab_fab)) {
                                                                                
                                                                                $req1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE so_id = '". $_GET['id'] ."' AND budget_process_type = 'Fabric' AND fabric = '". $fabFth['fabric'] ."' AND process = '". $uiop['process_id'] ."'"));
                                                                        ?>
                                                                            <tr>
                                                                                <td><input type="hidden" name="fabric_fabric<?= $pid; ?>[]" value="<?= $fabFth['fabric']; ?>">  <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req1['id']; ?>">  <?= $fabFth['fabric_name']; ?></td>
                                                                                <td><input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req1['rate']=='0.00') ? '' : $req1['rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req1['revised_rate']=='0.00') ? '' : $req1['revised_rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req1['rework_rate']=='0.00') ? '' : $req1['rework_rate']; ?>"></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php } else if($uiop['budget_type']=='Dyeing Color') { ?>
                                                                <table class="table table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Dyeing Color</th>
                                                                            <th>Rate</th>
                                                                            <th>Revised Rate</th>
                                                                            <th>Rework Rate</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            $fab_fab = mysqli_query($mysqli, "SELECT a.dyeing_color, a.fabric, b.color_name FROM sales_order_fabric_program a LEFT JOIN color b ON a.dyeing_color = b.id WHERE a.sales_order_id='". $_GET['id'] ."' AND a.dyeing_color != '' GROUP BY a.dyeing_color");
                                                                            while($colorFth = mysqli_fetch_array($fab_fab)) {
                                                                                
                                                                                $req2 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE so_id = '". $_GET['id'] ."' AND budget_process_type = 'Dyeing Color' AND dyeing_color = '". $colorFth['dyeing_color'] ."' AND process = '". $uiop['process_id'] ."'"));
                                                                        ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="hidden" name="fabric_id<?= $pid; ?>[]" value="<?= $colorFth['fabric']; ?>">
                                                                                    <input type="hidden" name="fabric_dyeing_color<?= $pid; ?>[]" value="<?= $colorFth['dyeing_color']; ?>">
                                                                                    <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req2['id']; ?>"> <?= $colorFth['color_name']; ?></td>
                                                                                <td><input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req2['rate']=='0.00') ? '' : $req2['rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req2['revised_rate']=='0.00') ? '' : $req2['revised_rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req2['rework_rate']=='0.00') ? '' : $req2['rework_rate']; ?>"></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php } else if($uiop['budget_type']=='AOP Design') { ?>
                                                                <table class="table table-striped table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Dyeing Color</th>
                                                                            <th>Rate</th>
                                                                            <th>Revised Rate</th>
                                                                            <th>Rework Rate</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            $fab_fab = mysqli_query($mysqli, "SELECT a.dyeing_color, b.color_name FROM sales_order_fabric_program a LEFT JOIN color b ON a.dyeing_color = b.id WHERE a.sales_order_id='". $_GET['id'] ."' AND a.dyeing_color != '' GROUP BY a.dyeing_color");
                                                                            while($colorFth = mysqli_fetch_array($fab_fab)) {
                                                                                
                                                                                $req2 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE so_id = '". $_GET['id'] ."' AND budget_process_type = 'AOP Design' AND dyeing_color = '". $colorFth['dyeing_color'] ."' AND process = '". $uiop['process_id'] ."'"));
                                                                        ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="hidden" name="fabric_id<?= $pid; ?>[]" value="<?= $colorFth['fabric']; ?>">
                                                                                    <input type="hidden" name="fabric_dyeing_color<?= $pid; ?>[]" value="<?= $colorFth['dyeing_color']; ?>">
                                                                                    <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req2['id']; ?>"> <?= $colorFth['color_name']; ?></td>
                                                                                <td><input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req2['rate']=='0.00') ? '' : $req2['rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req2['revised_rate']=='0.00') ? '' : $req2['revised_rate']; ?>"></td>
                                                                                <td><input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req2['rework_rate']=='0.00') ? '' : $req2['rework_rate']; ?>"></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php } ?>
                        							    </div>
                        							</div>
                    							</div>
                    							<?php $x++; } ?>
            								</div>
            								
            								<div style="text-align:center;" class="mainRow">
            								    <?php if($fnm==0 && BUDGET_ADD==1) { ?>
                                                    <input type="submit" name="saveFabricBudget" id="saveFabricBudget" class="btn btn-outline-primary" value="Save Fabric Budget">
                                                <?php } else if($fnm>0 && BUDGET_EDIT==1) { ?>
                                                    <input type="submit" name="saveFabricBudget" id="saveFabricBudget" class="btn btn-outline-primary" value="Update Fabric Budget">
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="pd-20" style="text-align:center; color: red; text-decoration:underline;">
            								    <p>Fabric Program Not Started!</p>
            								</div>
        								<?php } ?>
        							</form>
								<?php } ?> 
							</div>
							
							<div class="tab-pane fade" id="budAccessories" role="tabpanel">
								<div class="pd-20">
								    <h5 style="color: #a5a5a5;">Accessories Budget</h5>
								</div>
								<?php if(BUDGET_ACCESSORIES!=1) { action_denied(); } else { ?>
								    <form id="AccessoriesForm" method="POST" enctype="multipart/form-data">
								        <?php
								        
								        $fnm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE so_id = '". $_GET['id'] ."' AND budget_for = 'Accessories Budget' "));
								        
								        
								            $pss = mysqli_query($mysqli, "SELECT a.id, a.accessories, b.acc_name FROM sales_order_accessories_program a LEFT JOIN mas_accessories b ON a.accessories = b.id WHERE a.sales_order_id = '". $_GET['id'] ."' GROUP BY a.accessories ");
								            if(mysqli_num_rows($pss)>0) {
							            ?>
        								    <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Accessories</th>
                                                        <th>Rate</th>
                                                        <th>Revised Rate</th>
                                                        <th>Rework Rate</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        while($accessRow = mysqli_fetch_array($pss)) {
                                                            
                                                            $req = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Accessories Budget' AND so_id = '". $_GET['id'] ."' AND accessories = '". $accessRow['accessories'] ."'"));
                                                    ?>
                                                        <tr>
                                                            <td><input type="hidden" name="access_<?= $pid; ?>[]" value="<?= $accessRow['accessories']; ?>"> <input type="hidden" name="inidAccessBud<?= $pid; ?>[]" value="<?= $req['id']; ?>"> <?= $accessRow['acc_name']; ?></td>
                                                            <td><input type="text" name="access_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req['rate']=='0.00') ? '' : $req['rate']; ?>"></td>
                                                            <td><input type="text" name="access_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req['revised_rate']=='0.00') ? '' : $req['revised_rate']; ?>"></td>
                                                            <td><input type="text" name="access_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req['rework_rate']=='0.00') ? '' : $req['rework_rate']; ?>"></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
            								
            								<div style="text-align:center;" class="mainRow">
            								    <?php if($fnm==0 && BUDGET_ADD==1) { ?>
                                                    <input type="submit" name="saveAccessoriesBudget" id="saveAccessoriesBudget" class="btn btn-outline-primary" value="Save Accessories Budget">
                                                <?php } else if($fnm>0 && BUDGET_EDIT==1) { ?>
                                                    <input type="submit" name="saveAccessoriesBudget" id="saveAccessoriesBudget" class="btn btn-outline-primary" value="Update Accessories Budget">
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="pd-20" style="text-align:center; color: red; text-decoration:underline;">
            								    <p>Accessories Program Not Started!</p>
            								</div>
        								<?php } ?>
        							</form>
								<?php } ?>
							</div>
							
							<div class="tab-pane fade" id="budProduction" role="tabpanel">
								<div class="pd-20">
								    <h5 style="color: #a5a5a5;">Production Budget</h5>
								</div>
							    <?php if(BUDGET_PRODUCTION!=1) { action_denied(); } else { 
							        $iic = mysqli_query($mysqli, "SELECT category FROM budget_process WHERE budget_for='Production Budget' AND so_id = '". $_GET['id'] ."'");
							        $pnm = mysqli_num_rows($iic);
							        $iub = mysqli_fetch_array($iic);
							    ?>
    							    <div class="row">
                                        <div class="col-md-3" style="margin-left:1% !important;color:red;padding: 25px;">
                                            <label><u>Category</u> :</label>
                                            <select name="category" id="category" class="custom-select2 form-control" style="width:100%" onchange="changeCat()">
                                                <?= select_dropdown('category', array('id', 'category_name'), 'category_name ASC', $iub['category'], ' WHERE is_active="active"', '') ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <form id="budForm" method="POST" enctype="multipart/form-data">
								        <input type="hidden" name="timeline_ref" value="<?= $sql['order_code']; ?>">
                                        
                                        <div class="row mainRow d-none">
                                            <div class="col-md-12" style="overflow-y: auto;">
                                                <h5 style="padding: 20px;">Process</h5>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="min-width: 150px;max-width:300px">Process</th>
                                                            <th>Rate</th>
                                                            <th>Revised Rate</th>
                                                            <th>Rework Rate</th>
                                                            <th><i class="icon-copy fa fa-plus-circle" aria-hidden="true" onclick="addmoreQrydetail()"></i> <input type="hidden" id="addmoreQrydetail" value="101"> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="processBody"></tbody>
                                                </table>
                                            </div>
            
                                        </div>
            
                                        <div style="text-align:center;" class="mainRow d-none">
                                            <?php if($pnm==0 && BUDGET_ADD==1) { ?>
                                                <input type="submit" name="savebudget" id="productionBudget" class="btn btn-outline-primary" value="Save Production Budget">
                                            <?php } else if($pnm>0 && BUDGET_EDIT==1) { ?>
                                                <input type="submit" name="savebudget" id="productionBudget" class="btn btn-outline-primary" value="Update Production Budget">
                                            <?php } ?>
                                        </div>
                                    </form>
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

    <script>
    
        function addmoreQrydetail()
        {
            var row = $("#addmoreQrydetail").val();
            var a = '<tr id="tr'+row+'"> <td> <input type="text" name="newProcess[]" class="form-control"> </td> <td><input type="text" name="rate1[]" class="form-control"></td> <td><input type="text" name="rate2[]" class="form-control"></td> <td><input type="text" name="rate3[]" class="form-control"></td><td><i class="icon-copy fa fa-trash" aria-hidden="true" onclick="removeRow('+ row +')"></i> </td> </tr>';
            
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?addmoreQrydetail=1&row=' + row,
                
                success : function(msg) {
                    var json = $.parseJSON(msg);
                    
                    $("#processBody").append(json.tbody);
                    
                    $("#newProcess_s2" + row).select2();
                }
            });
            
            
         $("#addmoreQrydetail").val((parseInt(row)+1));
        }
    
    
        function removeRow(id)
        {
            $("#tr"+id).remove();
        }
    </script>

    <script>
        function changeCat() {
            var catId = $("#category").val();
            var id = <?= $_GET['id']; ?>;
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getBudCategory_process=1&catId=' + catId + '&id=' + id,
                
                success : function(msg) {
                    
                    $("#processBody").html(msg);
                    
                    $(".mainRow").removeClass('d-none');
                }
            });
        }

        function autocalculation(id) {
            var a = 0;
            $(".subprss" + id).each(function () {
                a += parseInt($(this).val());
            })

            $("#prate" + id).val(a);
        }
    </script>


    <script type="text/javascript">
        $(function () {
            $('#soForm').validate({
                errorClass: "help-block",
                rules: {
                    department: {
                        required: true
                    },
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
    </script>
    
    
                                    
    <?php if($pnm>0) { ?>
        <script>
            $(document).ready(function() {
                $('#category').attr("disabled", true);
                // $('#productionBudget').attr("value", 'Update Production Budget');
                changeCat();
            })
        </script>
    <?php } ?>
                                    
    <?php if($fnm>0) { ?>
        <script>
            $(document).ready(function() {
                // $('#saveFabricBudget').attr("value", 'Update Fabric Budget');
            })
        </script>
    <?php } ?>

</body>

</html>