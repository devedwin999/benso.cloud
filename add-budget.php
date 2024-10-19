<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$style_id = $_GET['id'];
$sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_detalis WHERE id=" . $style_id));

$data = array();

if (isset($_POST['save_prod_budget'])) {
    
    if(isset($_REQUEST['process_id']) && count($_REQUEST['process_id'])>0) {
        
        for ($k = 0; $k < count($_REQUEST['process_id']); $k++) {
            
            $bud_type = $_REQUEST['bud_type'][$k];
            
            if($bud_type == 'all') {
                
                $data = array(
                    'so_id' => $sql['sales_order_id'],
                    'style_id' => $style_id,
                    'bud_type' => $bud_type,
                    'process' => $_REQUEST['process_id'][$k],
                    'category' => $_REQUEST['category_id'],
                    'rate' => $_REQUEST['prate'][$k],
                    'revised_rate' => $_REQUEST['revised_rate'][$k],
                    'rework_rate' => $_REQUEST['rework_rate'][$k],
                    'budget_for' => 'Production Budget',
                );
            } else {
                $data = array(
                    'so_id' => $sql['sales_order_id'],
                    'style_id' => $style_id,
                    'bud_type' => $bud_type,
                    'process' => $_REQUEST['process_id'][$k],
                    'category' => $_REQUEST['category_id'],
                    'budget_for' => 'Production Budget',
                );
            }
            
            $in = $up = 0;
            if (!empty($_REQUEST['budget_process'][$k])) {
                $qry = Update('budget_process', $data, " WHERE id = '" . $_REQUEST['budget_process'][$k] . "'");
                $inid = $_REQUEST['budget_process'][$k];
                $up++;
            } else {
                if($_REQUEST['process_id'][$k]!="") {
                    $qry = Insert('budget_process', $data);
                    $inid = mysqli_insert_id($mysqli);
                    $in++;
                }
            }
            
            $process_id = $_REQUEST['process_id'][$k];
            
            if($bud_type == 'combo') {

                Delete('budget_process_partial', 'WHERE bud_type = "combo_part" AND process = "'. $process_id .'" AND style_id = "'. $style_id .'"');
                
                for($io=0; $io<count($_REQUEST['sod_combo'.$process_id]); $io++) {
                    
                    $com = array(
                        'budget_process' => $inid,
                        'order_id' => $sql['sales_order_id'],
                        'style_id' => $style_id,
                        'bud_type' => $bud_type,
                        'process' => $process_id,
                        'sod_combo' => $_REQUEST['sod_combo'.$process_id][$io],
                        'rate' => $_REQUEST['combo_rate'.$process_id][$io],
                        'revised_rate' => $_REQUEST['combo_revised'.$process_id][$io],
                        'rework_rate' => $_REQUEST['combo_rework'.$process_id][$io],
                        'is_approved' => 'false',
                    );
                    
                    if($_REQUEST['partial_id'.$process_id][$io] == "") {
                        $qry = Insert('budget_process_partial', $com);
                    } else {
                        $qry = Update('budget_process_partial', $com, ' WHERE id = '. $_REQUEST['partial_id'.$process_id][$io]);
                    }
                }
                
            } else if($bud_type == 'combo_part') {

                Delete('budget_process_partial', 'WHERE bud_type = "combo" AND process = "'. $process_id .'" AND style_id = "'. $style_id .'"');
                
                for($op=0; $op<count($_REQUEST['sod_part'.$process_id]); $op++) {
                    
                    $com = array(
                        'budget_process' => $inid,
                        'order_id' => $sql['sales_order_id'],
                        'style_id' => $style_id,
                        'bud_type' => $bud_type,
                        'process' => $process_id,
                        'sod_part' => $_REQUEST['sod_part'.$process_id][$op],
                        'rate' => $_REQUEST['part_rate'.$process_id][$op],
                        'revised_rate' => $_REQUEST['part_revised'.$process_id][$op],
                        'rework_rate' => $_REQUEST['part_rework'.$process_id][$op],
                        'is_approved' => 'false',
                    );
                    
                    if($_REQUEST['partial_id'.$process_id][$op] == "") {
                        $qry = Insert('budget_process_partial', $com);
                    } else {
                        $qry = Update('budget_process_partial', $com, ' WHERE id = '. $_REQUEST['partial_id'.$process_id][$op]);
                    }
                }
            }
        }
            
        if($up>0) {
            timeline_history('Update', 'budget_process', $style_id, 'Production Budget Updated. Ref-Bo:'. $_REQUEST['timeline_ref']);
        }
        if($in>0) {
            timeline_history('Insert', 'budget_process', $style_id, 'Production Budget Inserted. Ref-Bo:'. $_REQUEST['timeline_ref']);
        }
        
        if(isset($_REQUEST['sub_id']) && count($_REQUEST['sub_id'])>0) {
            for ($l = 0; $l < count($_REQUEST['sub_id']); $l++) {
                $subdata = array(
                    'so_id' => $sql['sales_order_id'],
                    'style_id' => $style_id,
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
        }
    }
    
    if(isset($_REQUEST['newProcess'])) {
        for ($k = 0; $k < count($_REQUEST['newProcess']); $k++) {
                
            $data = array(
                'so_id' => $sql['sales_order_id'],
                'style_id' => $style_id,
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
        }
    }

    Update('sales_order_detalis', array('prod_bud_status' => 1), 'WHERE id = '. $style_id);
    timeline_history('Insert', 'budget_process', $style_id, 'Production Budget Inserted or Updated.');
    $_SESSION['msg'] = "Production Budget Saved";
    header("Location:add-budget.php?id=". $style_id);

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
                'so_id' => $sql['sales_order_id'],
                'style_id' => $style_id,
                'budget_for' => 'Fabric Budget',
                'process' => $np,
                'budget_process_type' => $typ,
                'requirement_id' => $_REQUEST['requirement_id'.$np][$p],
                'rate' => $_REQUEST['fabric_Rate'.$np][$p],
                'revised_rate' => $_REQUEST['fabric_Revised'.$np][$p],
                'rework_rate' => $_REQUEST['fabric_Rework'.$np][$p],
            );
            
            // $finArr = array_merge($typ_arr, $indata);
            
            if($_REQUEST['inidFabBud'.$np][$p]=="") {
                $qry = Insert('budget_process', $indata);
            } else {
                $qry = Update('budget_process', $indata, " WHERE id = '" . $_REQUEST['inidFabBud'.$np][$p] . "'");
            }
        }
    }

    Update('sales_order_detalis', array('fabric_bud_status' => 1), 'WHERE id = '. $style_id);
    timeline_history('Insert', 'budget_process', $style_id, 'Fabric Budget Inserted or Updated.');

    $_SESSION['msg'] = "Fabric Budget Saved";

    // header("Location:cmt_budget.php");
    header("Location:add-budget.php?id=". $style_id .'&ret='. $_GET['ret']);

    exit;
} else if (isset($_POST['saveAccessoriesBudget'])) {
    // echo '<pre>'.print_r($_POST, 1); die;
    
    for($m=0;$m<count($_REQUEST['access_']); $m++) {
        
            $indata = array(
                'so_id' => $sql['sales_order_id'],
                'style_id' => $style_id,
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

    Update('sales_order_detalis', array('access_bud_status' => 1), 'WHERE id = '. $style_id);
    
    timeline_history('Insert', 'budget_process', $style_id, 'Accessories Budget Inserted or Updated.');
    $_SESSION['msg'] = "Accessories Budget Saved";
    // header("Location:cmt_budget.php");
    header("Location:add-budget.php?id=". $style_id);
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Add Budget</title>

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
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                <?php } ?>

                <div class="card-box mb-30">
					<div class="pd-20">
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="text-blue">Budget Entry</h5>
                            </div>
                            <div class="col-md-3"><p class="text-blue-50">BO: <span class="text-danger u"><?= sales_order_code($sql['sales_order_id']); ?></span> </p></div>
                            <div class="col-md-3"><p class="text-blue-50">Style: <span class="text-danger u"><?= sales_order_style($sql['id']); ?></span> </p></div>

                            <div class="col-md-3">
                                <div class="btn-group mr-2 float-right" role="group" aria-label="First group">
                                    <a class="btn btn-outline-primary" href="<?= ($_GET['ret']=='cmt_budget') ? 'cmt_budget' : 'budget_app'; ?>.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Budget List</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pd-20 card-box mb-30">                    
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
                                            $fnm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE style_id = '". $style_id ."' AND budget_for = 'Fabric Budget' "));
                                            
                                            $qrt = "SELECT * ";
                                            $qrt .= " FROM fabric_requirements a ";
                                            $qrt .= " LEFT JOIN process b ON a.process_id = b.id ";
                                            $qrt .= " WHERE a.style_id = '". $style_id ."' GROUP BY a.process_id ORDER BY a.process_order ASC ";
                                            
                                            $pss = mysqli_query($mysqli, $qrt);
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
                                                                <i class="icon-copy dw dw-right-arrow-4"></i> <?= process_name($main['process_id']) .' - '.$main['budget_type']; ?>
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    
                                                    <div id="processNum<?= $x; ?>" class="collapse <?= ($x==1) ? 'show' : ''; ?>" aria-labelledby="heading<?= $x; ?>" data-parent="#accordionExample">
                                                        <div class="card-body" style="overflow-y:auto;">
                                                            <input type="hidden" name="fabric_process_id[]" value="<?= $pid = $main['process_id']; ?>">
                                                            <input type="hidden" name="fabric_budget_type[]" value="<?= $main['budget_type']; ?>">
                                                            
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
                                                                        $fab_q .= " WHERE a.process_id = '". $main['process_id'] ."' AND a.style_id = '". $style_id  ."' ";
                                                                        $fabQ = mysqli_query($mysqli, $fab_q);
                                                                        $m=1;
                                                                        while($fab_res = mysqli_fetch_array($fabQ)) {
                                                                            $req = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE requirement_id = " . $fab_res['id']));
                                                                    ?>
                                                                    <tr>
                                                                        <?php if($main['budget_type']=='Yarn') { ?>
                                                                            <td><?= mas_yarn_name($fab_res['yarn_id']); ?></td>
                                                                            <td><?= $fab_res['req_wt']; ?></td>
                                                                            <td>
                                                                                <input type="hidden" name="requirement_id<?= $pid; ?>[]" value="<?= $fab_res['id']; ?>">
                                                                                <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req['id']; ?>">
                                                                                <input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req['rate']=='0.00') ? '' : $req['rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req['revised_rate']=='0.00') ? '' : $req['revised_rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req['rework_rate']=='0.00') ? '' : $req['rework_rate']; ?>">
                                                                            </td>
                                                                        <?php } else if($main['budget_type']=='Fabric') { ?>
                                                                            <td><?= fabric_name($fab_res['fabric_id']); ?></td>
                                                                            <td><?= $fab_res['req_wt']; ?></td>
                                                                            <td>
                                                                                <input type="hidden" name="requirement_id<?= $pid; ?>[]" value="<?= $fab_res['id']; ?>">
                                                                                <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req['id']; ?>">
                                                                                <input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req['rate']=='0.00') ? '' : $req['rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req['revised_rate']=='0.00') ? '' : $req['revised_rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req['rework_rate']=='0.00') ? '' : $req['rework_rate']; ?>">
                                                                            </td>
                                                                        <?php } else if($main['budget_type']=='Dyeing Color') { ?>
                                                                            <td><?= fabric_name($fab_res['fabric_id']); ?></td>
                                                                            <td><?= color_name($fab_res['color']); ?></td>
                                                                            <td><?= $fab_res['req_wt']; ?></td>
                                                                            <td>
                                                                                <input type="hidden" name="requirement_id<?= $pid; ?>[]" value="<?= $fab_res['id']; ?>">
                                                                                <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req['id']; ?>">
                                                                                <input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req['rate']=='0.00') ? '' : $req['rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req['revised_rate']=='0.00') ? '' : $req['revised_rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req['rework_rate']=='0.00') ? '' : $req['rework_rate']; ?>">
                                                                            </td>
                                                                        <?php } else if($main['budget_type']=='AOP Design') { ?>
                                                                            <td><?= fabric_name($fab_res['fabric_id']); ?></td>
                                                                            <td><?= $fab_res['aop_name']; ?></td>
                                                                            <td><?= $fab_res['req_wt']; ?></td>
                                                                            <td>
                                                                                <input type="hidden" name="requirement_id<?= $pid; ?>[]" value="<?= $fab_res['id']; ?>">
                                                                                <input type="hidden" name="inidFabBud<?= $pid; ?>[]" value="<?= $req['id']; ?>">
                                                                                <input type="text" name="fabric_Rate<?= $pid; ?>[]" class="form-control" placeholder="Rate" value="<?= ($req['rate']=='0.00') ? '' : $req['rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Revised<?= $pid; ?>[]" class="form-control" placeholder="Revised Rate" value="<?= ($req['revised_rate']=='0.00') ? '' : $req['revised_rate']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="fabric_Rework<?= $pid; ?>[]" class="form-control" placeholder="Rework Rate" value="<?= ($req['rework_rate']=='0.00') ? '' : $req['rework_rate']; ?>">
                                                                            </td>
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
            								
            								<div style="text-align:center;" class="mainRow">
            								    <?php if($fnm==0 && BUDGET_ADD==1) { ?>
                                                    <input type="submit" name="saveFabricBudget" id="saveFabricBudget" class="btn btn-outline-primary sv_btnn" value="Save Fabric Budget">
                                                <?php } else if($fnm>0 && BUDGET_EDIT==1) { ?>
                                                    <input type="submit" name="saveFabricBudget" id="saveFabricBudget" class="btn btn-outline-primary sv_btnn" value="Update Fabric Budget">
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
								<div class="pd-20"><h5 style="color: #a5a5a5;">Accessories Budget</h5></div>
								<?php if(BUDGET_ACCESSORIES!=1) { action_denied(); } else { ?>
								    <form id="AccessoriesForm" method="POST" enctype="multipart/form-data">
								        <?php								        
								        $fnm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE style_id = '". $style_id ."' AND budget_for = 'Accessories Budget' "));
								        
								            $pss = mysqli_query($mysqli, "SELECT a.id, a.accessories, b.acc_name FROM sales_order_accessories_program a LEFT JOIN mas_accessories b ON a.accessories = b.id WHERE a.sales_order_detalis_id = '". $style_id ."' GROUP BY a.accessories ");
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
                                                            
                                                            $req = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_process WHERE budget_for = 'Accessories Budget' AND style_id = '". $style_id ."' AND accessories = '". $accessRow['accessories'] ."'"));
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="access_[]" value="<?= $accessRow['accessories']; ?>">
                                                                <input type="hidden" name="inidAccessBud[]" value="<?= $req['id']; ?>">
                                                                <?= $accessRow['acc_name']; ?></td>
                                                            <td><input type="text" name="access_Rate[]" class="form-control" placeholder="Rate" value="<?= ($req['rate']=='0.00') ? '' : $req['rate']; ?>"></td>
                                                            <td><input type="text" name="access_Revised[]" class="form-control" placeholder="Revised Rate" value="<?= ($req['revised_rate']=='0.00') ? '' : $req['revised_rate']; ?>"></td>
                                                            <td><input type="text" name="access_Rework[]" class="form-control" placeholder="Rework Rate" value="<?= ($req['rework_rate']=='0.00') ? '' : $req['rework_rate']; ?>"></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
            								
            								<div style="text-align:center;" class="mainRow">
            								    <?php if($fnm==0 && BUDGET_ADD==1) { ?>
                                                    <input type="submit" name="saveAccessoriesBudget" id="saveAccessoriesBudget" class="btn btn-outline-primary sv_btnn" value="Save Accessories Budget">
                                                <?php } else if($fnm>0 && BUDGET_EDIT==1) { ?>
                                                    <input type="submit" name="saveAccessoriesBudget" id="saveAccessoriesBudget" class="btn btn-outline-primary sv_btnn" value="Update Accessories Budget">
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
                                    $iic = mysqli_query($mysqli, "SELECT category FROM budget_process WHERE budget_for='Production Budget' AND style_id = '". $style_id ."'");
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
                                                <table class="table table-bordered">
                                                    <thead style="background: #dadada;">
                                                        <tr>
                                                            <th style="min-width: 150px;max-width:300px">Process</th>
                                                            <th>Budget Type</th>
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
                                                <input type="submit" name="save_prod_budget" id="productionBudget" class="btn btn-outline-primary sv_btnn" value="Save Production Budget">
                                            <?php } else if($pnm>0 && BUDGET_EDIT==1) { ?>
                                                <input type="submit" name="save_prod_budget" id="productionBudget" class="btn btn-outline-primary sv_btnn" value="Update Production Budget">
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
        $(".sv_btnn").click(function() {
            $("#overlay").fadeIn(100);
        });
    </script>
        
    <script>
        function change_type(id) {
            
            var type = $("#bud_type" + id).val();
            var data = { type: type, process_id: id, order_id: <?= $_GET['id'] ?>, style_id: <?= $_GET['id'] ?>, }
            
            $("#overlay").fadeIn(100);
            $.post('ajax_search2.php?show_cmtbudget_type', data, function(resp) {
                var j = $.parseJSON(resp);
                
                if(type=='all') {
                    $(".all_" + id).removeClass('d-none');
                    $("#type_tr" + id).closest('tr').addClass('d-none');
                    $("#type_tr" + id).find('thead').html('');
                    $("#type_tr" + id).find('tbody').html('');
                    $("#overlay").fadeOut(500);
                } else {
                    $(".all_" + id).addClass('d-none');
                    $("#type_tr" + id).closest('tr').removeClass('d-none');
                    $("#type_tr" + id).find('thead').html(j.thead);
                    $("#type_tr" + id).find('tbody').html(j.tbody);
                    $("#overlay").fadeOut(500);
                }
            });
        }
    </script>
        
    <script>
        function addmoreQrydetail() {
            
            var row = $("#addmoreQrydetail").val();
            var a = '<tr id="tr'+row+'"> <td> <input type="text" name="newProcess[]" class="form-control"> </td> <td><input type="text" name="rate1[]" class="form-control"></td> <td><input type="text" name="rate2[]" class="form-control"></td> <td><input type="text" name="rate3[]" class="form-control"></td><td><i class="icon-copy fa fa-trash" aria-hidden="true" onclick="removeRow('+ row +')"></i> </td> </tr>';
            
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?addmoreQrydetail=1&row=' + row,
                
                success : function(msg) {
                    var json = $.parseJSON(msg);
                    
                    $("#processBody").append(json.tbody);
                    $(".custom-select2").each(function() {
                        $(this).select2();
                    });
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
            var id = <?= $style_id; ?>;
            var data = {
                catId : catId,
                id : id,
            };
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getBudCategory_process',
                data: data,
                
                success : function(msg) {
                    
                    $("#processBody").html(msg);
                    $(".custom-select2").each(function() {
                        $(this).select2();
                    });
                    $(".mainRow").removeClass('d-none');
                    
                    $(".trigger_type").each(function() {
                        $(this).trigger('change');
                    });
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