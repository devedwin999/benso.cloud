<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_REQUEST['btnSave'])) {
    
    
    for($io = 0; $io < count($_REQUEST['style_id']); $io++) {
        $style = $_REQUEST['style_id'][$io];
        $order_id = $_GET['id'];
        
        for($oo=0; $oo<count($_REQUEST['process_id'. $style]); $oo++) {
            $process = $_REQUEST['process_id'. $style][$oo];
            $pr_id = $style.$process;
            
            if($process == 1) {
                
                $dta = array(
                    'so_id' => $order_id,
                    'style_id' => $style,
                    'process_id' => $process,
                    'plan_type' => $_REQUEST['plan_type'. $pr_id],
                    'partial_type' => $_REQUEST['partial_type'. $pr_id],
                    'process_type' => $_REQUEST['typee'. $pr_id],
                    'processing_unit_id' => $_REQUEST['unitt'. $pr_id],
                );
                
                if($_REQUEST['typee'. $pr_id] == 'unit' && !empty($_REQUEST['unitt'. $pr_id]) && $_REQUEST['plan_type'. $pr_id] == 'Full') {
                    $dta1 = array(
                        'process_type' => $_REQUEST['typee'. $pr_id],
                        'processing_unit_id' => $_REQUEST['unitt'. $pr_id],
                    );
                    
                } else if($_REQUEST['typee'. $pr_id] == 'supplier' && !empty($_REQUEST['supplier'. $pr_id]) && $_REQUEST['plan_type'. $pr_id] == 'Full') {
                    $dta1 = array(
                        'process_type' => $_REQUEST['typee'. $pr_id],
                        'supplier_id' => $_REQUEST['supplier'. $pr_id],
                    );
                } else {
                    $dta1 = array();
                }
                
                $dta = array_merge($dta, $dta1);
                
                if(empty($_REQUEST['planning_id'. $pr_id])) {
                    $ins = Insert('process_planing', $dta);
                    
                    $ppid = mysqli_insert_id($mysqli);
                } else {
                
                    $ppooo = mysqli_fetch_array(mysqli_query($mysqli, "SELECT partial_type FROM process_planing WHERE id = ". $_REQUEST['planning_id'. $pr_id]));
                    
                    if($ppooo['partial_type'] != $_REQUEST['partial_type'. $pr_id]) {
                        Delete('cutting_partial_planning', 'WHERE process_planing_id='. $_REQUEST['planning_id'. $pr_id]);
                    }
                    
                    $ins = Update('process_planing', $dta, " WHERE id = '" . $_REQUEST['planning_id'. $pr_id] . "'");
                    
                    $ppid = $_REQUEST['planning_id'. $pr_id];
                }
                
                if($_REQUEST['plan_type'. $pr_id] == 'Partial') {
                    
                    if($_REQUEST['partial_type'. $pr_id] == 'size') {
                        
                        for($pt=0; $pt<count($_REQUEST['sod_part_id'. $pr_id]); $pt++) {
                            $si_id = $pr_id.$_REQUEST['sod_part_id'. $pr_id][$pt];
                            
                            for($ii=0; $ii<count($_REQUEST['sod_size_id'. $si_id]); $ii++) {
                                
                                $array = array(
                                    'order_id' => $order_id,
                                    'style_id' => $style,
                                    'process_planing_id' => $ppid,
                                    'type' => 'sod_size',
                                    'sod_part' => $_REQUEST['sod_part_id'. $pr_id][$pt],
                                    'combo_part_qty' => $_REQUEST['combo_part_qty'. $pr_id][$pt],
                                    'sod_size' => $_REQUEST['sod_size_id'. $si_id][$ii],
                                    'size_order_qty' => $_REQUEST['size_order_qty'. $si_id][$ii],
                                    'size_plan_qty' => $_REQUEST['size_plan_qty'. $si_id][$ii],
                                    'plan_for' => $_REQUEST['size_plan_for_'. $si_id][$ii],
                                    'plan_for_to' => $_REQUEST['size_plan_for_to'. $si_id][$ii],
                                );
                                // print_r($array);
                                
                                if($_REQUEST['size_plan_qty'. $si_id][$ii]>0) {
                                    if($_REQUEST['cutting_partial_size_id'. $si_id][$ii] == "") {
                                        $ins = Insert('cutting_partial_planning', $array);
                                    } else {
                                        $ins = Update('cutting_partial_planning', $array, 'WHERE id = '. $_REQUEST['cutting_partial_size_id'. $si_id][$ii]);
                                    }
                                }
                            }
                        }
                    } else if($_REQUEST['partial_type'. $pr_id] == 'part') {
                        
                        for($pt=0; $pt<count($_REQUEST['sod_part_id'. $pr_id]); $pt++) {
                            $si_id = $pr_id.$_REQUEST['sod_part_id'. $pr_id][$pt];
                                
                            $array = array(
                                'order_id' => $order_id,
                                'style_id' => $style,
                                'process_planing_id' => $ppid,
                                'type' => 'sod_part',
                                'sod_part' => $_REQUEST['sod_part_id'. $pr_id][$pt],
                                'combo_part_qty' => $_REQUEST['combo_part_qty'. $pr_id][$pt],
                                'plan_for' => $_REQUEST['combo_part_plan_for'. $pr_id][$pt],
                                'plan_for_to' => $_REQUEST['combo_part_plan_to'. $pr_id][$pt],
                            );
                            
                            if($_REQUEST['cutting_partial_id'. $pr_id][$pt] == "") {
                                $ins = Insert('cutting_partial_planning', $array);
                            } else {
                                $ins = Update('cutting_partial_planning', $array, 'WHERE id = '. $_REQUEST['cutting_partial_id'. $pr_id][$pt]);
                            }
                        }
                    }
                }
            } else {
                
                if($_REQUEST['typee'. $pr_id] == 'unit' && !empty($_REQUEST['unitt'. $pr_id])) {
                    $dta = array(
                        'so_id' => $order_id,
                        'style_id' => $style,
                        'process_id' => $process,
                        'process_type' => $_REQUEST['typee'. $pr_id],
                        'processing_unit_id' => $_REQUEST['unitt'. $pr_id],
                    );
                    
                    if(empty($_REQUEST['planning_id'. $pr_id])) {
                        $ins = Insert('process_planing', $dta);
                    } else {
                        $ins = Update('process_planing', $dta, " WHERE id = '" . $_REQUEST['planning_id'. $pr_id] . "'");
                    }
                    
                } else if($_REQUEST['typee'. $pr_id] == 'supplier' && !empty($_REQUEST['supplier'. $pr_id])) {
                    $dta = array(
                        'so_id' => $order_id,
                        'style_id' => $style,
                        'process_id' => $process,
                        'process_type' => $_REQUEST['typee'. $pr_id],
                        'supplier_id' => $_REQUEST['supplier'. $pr_id],
                    );
                    
                    if(empty($_REQUEST['planning_id'. $pr_id])) {
                        $ins = Insert('process_planing', $dta);
                    } else {
                        $ins = Update('process_planing', $dta, " WHERE id = '" . $_REQUEST['planning_id'. $pr_id] . "'");
                    }
                }
            }
        }
    }

if($ins) {
    timeline_history('Insert', 'process_planing', $order_id, 'Production Planning Updated. Ref: '. sales_order_style($_REQUEST['style_id']));
    $_SESSION['msg'] = "updated";
    header("Location:view-planing.php");
}
exit;
}

if(isset($_POST['btn_consumption'])) {

    for($p=0; $p<count($_POST['sod_combo']); $p++) {

        $val = array(
            'sales_order_id' => $_POST['order_id'],
            'sales_order_detail_id' => $_POST['style'],
            'sod_combo' => $_POST['sod_combo'][$p],
            'order_qty' => $_POST['order_qty'][$p],
            'fabric' => $_POST['fabric'][$p],
            'gsm' => $_POST['gsm'][$p],
            'color' => $_POST['color'][$p],
            'component' => $_POST['component'][$p],
            'finishing_dia' => $_POST['finishing_dia'][$p],
            'pcs_wt' => $_POST['pcs_wt'][$p],
            'req_wt' => $_POST['req_wt'][$p],
        );

        $ins = Insert('fabric_consumption', $val);
    }

    if($ins) {
        timeline_history('Insert', 'fabric_consumption', $order_id, 'Production Consumption Saved!. Ref: '. sales_order_style($_POST['style']));
        $_SESSION['msg'] = "consumpition_added";
        header("Location:add-planing.php?id=". $_GET['id']);
    }
    exit;
}

if (isset($_GET["id"])) {
    $id = $_GET['id'];
} else {
    $id = '';
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Production Planning</title>

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

    <style>
        /* .accordion span {
            color: #e83e8c;
            /* font-size: 20px; */
        }
        
        .card-body h5 {
            color: #1b00ff;
            text-decoration: underline;
            text-transform: uppercase;
        } */

        .tab-pane {
            border-bottom: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            border-left: 1px solid #dee2e6;
        }
    </style>


    <div class="main-container nw-cont">
        <?php if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Planning Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Planning Updated.
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
        <?php } ?>
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="card-box mb-30">
                    
                    <?php page_spinner(); if(PROD_PLANNING!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary showmodal" href="view-planing.php" style="float: right;">Planning List</a>
                        <h4 class="text-blue h4">Add Planning</h4>
                    </div>
                    <div class="pb-20">
                        <!-- <form id="planningFrom" method="post"> -->

                            <div class="tab pd-20">
								<ul class="nav nav-tabs" role="tablist">

                                    <?php
                                        $qry1 = "SELECT id, style_no FROM sales_order_detalis WHERE sales_order_id='" . $id . "' ORDER BY id ASC";
                                        $query1 = mysqli_query($mysqli, $qry1);
                                        $xx = 1;
                                        while ($sql1 = mysqli_fetch_array($query1)) {
                                            
                                        $style = $sql1['id'];
                                    ?>
									<li class="nav-item">
										<a class="nav-link text-blue <?= ($xx==1) ? 'active' : ''; ?>" data-toggle="tab" href="#tab<?= $sql1['id']; ?>" role="tab" aria-selected="true"><?= $sql1['style_no']; ?></a>
									</li>
                                    <?php $xx++; } ?>
								</ul>

								<div class="tab-content">
                                    <?php
                                        $qry = "SELECT * FROM sales_order_detalis WHERE sales_order_id='" . $id . "' ORDER BY id ASC";
                                        $query = mysqli_query($mysqli, $qry);
                                        $x = 1;
                                        while ($sql = mysqli_fetch_array($query)) {
                                            
                                        $style = $sql['id'];
                                    ?>
                                        <div class="tab-pane fade <?= ($x==1) ? 'show active' : ''; ?>" id="tab<?= $sql['id']; ?>" role="tabpanel">
                                            <div class="pd-20">

                                                <div class="tab">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active text-blue" data-toggle="tab" href="#fab_consumption<?= $x; ?>" role="tab" aria-selected="true">Fabric Consumption</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link text-blue" data-toggle="tab" href="#prod_planning<?= $x; ?>" role="tab" aria-selected="false">Production Planning</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content">

                                                        <div class="tab-pane fade show active" id="fab_consumption<?= $x; ?>" role="tabpanel">
                                                            <div class="pd-20">
                                                                <form id="consumptionFrom<?= $style; ?>" method="post">
                                                                    <p class="text-center text-danger">Fabric Consumption For The Style of <span class="u"><?= $sql['style_no']; ?></span></p>

                                                                    <?php if ($_SESSION['msg'] == 'consumpition_added') { ?>
                                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                                            <strong>Success!</strong> Consumption Saved!.
                                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Combo</th>
                                                                                <th>Cut Plan Qty</th>
                                                                                <th>Fabric</th>
                                                                                <th style="width: 9%;">GSM</th>
                                                                                <th>Color</th>
                                                                                <th>Component</th>
                                                                                <th style="width: 9%;">Finishing Dia</th>
                                                                                <th style="width: 9%;">Piece Wt</th>
                                                                                <th style="width: 9%;">Cutting Req Wt</th>
                                                                                <th style="width: 9%;">Action
                                                                                    <input type="hidden" name="style" value="<?= $style; ?>">
                                                                                    <input type="hidden" name="order_id" value="<?= $sql['sales_order_id']; ?>"></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="tbody<?= $style; ?>">
                                                                            <?php
                                                                                $qryz = mysqli_query($mysqli, "SELECT a.*, b.combo_id FROM fabric_consumption a LEFT JOIN sod_combo b ON a.sod_combo=b.id WHERE a.sales_order_detail_id = '". $style ."' ORDER BY a.id ASC");
                                                                                while($result = mysqli_fetch_array($qryz)) {
                                                                                    $del = $result['id'].", 'fabric_consumption'";
                                                                                    print '
                                                                                        <tr>
                                                                                            <td>'. (($result['sod_combo'] == 'all') ? 'All Combo' : color_name($result['combo_id'])) .'</td>
                                                                                            <td>'. ($result['order_qty'] ? $result['order_qty'] : '-') .'</td>
                                                                                            <td>'. ($result['fabric'] ? fabric_name($result['fabric']) : '-') .'</td>
                                                                                            <td>'. ($result['gsm'] ? $result['gsm'] : '-') .'</td>
                                                                                            <td>'. ($result['color'] ? color_name($result['color']) : '-') .'</td>
                                                                                            <td>'. ($result['component'] ? component_name($result['component']) : '-') .'</td>
                                                                                            <td>'. ($result['finishing_dia'] ? $result['finishing_dia'] : '-') .'</td>
                                                                                            <td>'. ($result['pcs_wt'] ? $result['pcs_wt'] : '-') .'</td>
                                                                                            <td>'. ($result['req_wt'] ? $result['req_wt'] : '-') .'</td>
                                                                                            <td><a class="btn text-danger" onclick="delete_data('. $del .')"><i class="fa fa-trash"></i></a></td>
                                                                                        </tr>
                                                                                    ';
                                                                                }
                                                                            ?>
                                                                        </tbody>
                                                                        <tfoot id="tfoot<?= $style; ?>">
                                                                            <tr>
                                                                                <td style="width:">
                                                                                    <select name="" id="combo<?= $style; ?>" class="fotm-control custom-select2 combo_cls" data-style="<?= $style; ?>" style="width:100%">
                                                                                        <option value="all" data-qty="<?= $sql['total_excess']; ?>">All Combo</option>
                                                                                        <?php
                                                                                            $ff = mysqli_query($mysqli, "SELECT a.id, a.combo_id, sum(b.excess_qty) as total_qty FROM sod_combo a LEFT JOIN sod_size b ON a.id=b.sod_combo WHERE a.sales_order_detail_id = '". $style ."' GROUP BY a.id");
                                                                                            while($ress = mysqli_fetch_array($ff)) {
                                                                                                print '<option value="'. $ress['id'] .'" data-qty="'. $ress['total_qty'] .'">'. color_name($ress['combo_id']) .'</option>';
                                                                                            }
                                                                                        ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="hidden" value="<?= $sql['total_excess']; ?>" name="" id="order_qty<?= $style; ?>">
                                                                                    <p id="order_qty_text<?= $style; ?>"><?= $sql['total_excess']; ?></p>
                                                                                </td>
                                                                                <td style="width:">
                                                                                    <select name="" id="fabric<?= $style; ?>" class="fotm-control custom-select2" style="width:100%">
                                                                                        <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', '', '', ''); ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control number_input" name="" id="gsm<?= $style; ?>" placeholder="GSM">
                                                                                </td>
                                                                                <td>
                                                                                    <select name="" id="color<?= $style; ?>" class="fotm-control custom-select2" style="width:100%">
                                                                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', ''); ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <select name="" id="component<?= $style; ?>" class="fotm-control custom-select2" style="width:100%">
                                                                                        <?= select_dropdown('mas_component', array('id', 'component_name'), 'component_name ASC', '', '', ''); ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control number_input" name="" id="finishing_dia<?= $style; ?>" placeholder="Finishing Dia">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control number_input pcs_wt" data-style="<?= $style; ?>" name="" id="pcs_wt<?= $style; ?>" placeholder="Piece Weight">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control number_input" name="" id="ctng_wt<?= $style; ?>" placeholder="Cutting Weight" readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <a class="btn btn-outline-primary add_btn" data-idd="<?= $style; ?>"><i class="fa fa-plus"></i> Add</a>
                                                                                </td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>

                                                                    <div class="pb-20 d-none cbt<?= $style; ?>" data-style="<?= $style; ?>" style="text-align:center;">
                                                                        <input type="hidden" name="btn_consumption">
                                                                        <a class="btn btn-outline-primary btn_consumption" data-style="<?= $style; ?>"><i class="fa-save fa"></i> Save Consumption</a>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane fade" id="prod_planning<?= $x; ?>" role="tabpanel">
                                                            <div class="pd-20">
                                                                <form id="planningFrom<?= $style; ?>" method="post">

                                                                    <p class="text-center text-danger">Planning For The Style of <span class="u"><?= $sql['style_no']; ?></span></p>
                                                                    <div class="row" style="padding: 20px;">
                                                                        <div class="col-md-2">
                                                                            
                                                                            <input type="hidden" name="so_id" id="so_id<?= $x; ?>" value="<?= $sql['sales_order_id']; ?>">
                                                                            <input type="hidden" name="style_id[]" id="style_id<?= $x; ?>" value="<?= $style; ?>">
                                                                            
                                                                            <label for="">Process List :</label>
                                                                            <div class="form-group">
                                                                                <select name="process_list[]" id="process_list<?= $x; ?>" class="custom-select2 form-control" multiple style="width:100%">
                                                                                    <?php
                                                                                    $vn = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM budget_process a LEFT JOIN process b ON a.process=b.id WHERE a.so_id='" . $_GET['id'] . "'");
                                                                                    while ($kl = mysqli_fetch_array($vn)) {
                                                                                        print '<option value="' . $kl['process'] . '">' . $kl['process_name'] . '</option>';
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-2">
                                                                            <label for="">Process Tye :</label>
                                                                                <select name="pp_type" id="typee<?= $x; ?>" class="custom-select2 form-control" style="width:100%" onchange="typeRsup(<?= $x; ?>)">
                                                                                    <?php
                                                                                $narr = array( 'unit' => 'Unit', 'supplier' => 'Supplier');
                                                                                foreach ($narr as $k => $v) {
                                                                                    print '<option value="' . $k . '">' . $v . '</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-2" id="tdunit<?= $x; ?>">
                                                                            <label for="">Unit :</label>
                                                                            <select name="unitt" id="unitt<?= $x; ?>" class="custom-select2 form-control" style="width:100%">
                                                                                <?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', 'WHERE type=2 AND is_active="active"', ''); ?>
                                                                            </select>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-2" id="tdsupp<?= $x; ?>" style="display:none">
                                                                            <label for="">Supplier :</label>
                                                                            <select name="supplier" id="supplier<?= $x; ?>" class="custom-select2 form-control" style="width:100%">
                                                                                <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', 'WHERE is_active="active"', ''); ?>
                                                                            </select>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-2">
                                                                            <input type="button" class="btn btn-outline-primary" style="margin-top: 33px;" onclick="formSubmit(<?= $x; ?>)" value="Save">
                                                                        </div>
                                                                    </div>

                                                                    <table class="table  table-bordered">
                                                                        <thead style="background-color: #f7f7f7;">
                                                                            <tr>
                                                                                <th style="width: 20%;">Process</th>
                                                                                <th style="width: 20%;">Planning Type</th>
                                                                                <th style="width: 20%;">Process Type</th>
                                                                                <th style="width: 20%;">Unit / Supplier</th>
                                                                                <th class="prevent-select">Budget Status</th>
                                                                                <th>Planning Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $opid = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM budget_process a LEFT JOIN process b ON a.process=b.id WHERE a.budget_for = 'Production Budget' AND a.so_id='" . $_GET['id'] . "'");
                                                                            if (mysqli_num_rows($opid) > 0) {
                                                                                while ($row = mysqli_fetch_array($opid)) {
                                                                                    $ppng = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM process_planing WHERE style_id='" . $style . "' AND process_id='" . $row['process'] . "'"));
                                                                                    $xid = $x . $row['id'];
                                                                                    
                                                                                    $pr_id = $style.$row['process'];
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="hidden" name="process_id<?= $style; ?>[]" id="" value="<?= $row['process']; ?>">
                                                                                            <input type="hidden" name="planning_id<?= $pr_id; ?>" id="" value="<?= $ppng['id']; ?>">
                                                                                            <?= $row['process_name']; ?>
                                                                                            <i class="icon-copy fa fa-eye showSubprocess" data-id="<?= $row['process']; ?>" aria-hidden="true" style="float: right;font-size: 15px;" title="Sub Process List"></i>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <?php if($row['process']==1) { ?>
                                                                                                <select name="plan_type<?= $pr_id; ?>" id="plan_type<?= $xid; ?>" onchange="change_supplier_unit(<?= $xid; ?>)" class="custom-select2 form-control" style="width:50%;">
                                                                                                    <option value="Full">Full</option>
                                                                                                    <option value="Partial" <?= ($ppng['plan_type']=='Partial') ? 'selected' : ''; ?>>Partial</option>
                                                                                                </select>
                                                                                            <?php } else { echo '-'; } ?>
                                                                                        </td>
                                                                                        
                                                                                        <td>
                                                                                            <div id="type_div<?= $xid; ?>" class="<?= ($ppng['plan_type']=='Partial') ? 'd-none' : ''; ?>">
                                                                                                <select name="typee<?= $pr_id; ?>" id="typee<?= $xid; ?>" class="custom-select2 form-control" style="width:100%" onchange="typeRsup(<?= $xid; ?>)">
                                                                                                    <?php
                                                                                                    $narr = array( 'unit' => 'Unit', 'supplier' => 'Supplier');
                                                                                                    foreach ($narr as $k => $v) {
                                                                                                        $sel = ($ppng['process_type'] == $k) ? 'selected' : '';
                                                                                                        print '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
                                                                                                    }
                                                                                                    ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </td>
                                                                                        
                                                                                        <td id="tdunit<?= $xid; ?>" style="<?= ($ppng['process_type'] == 'supplier') ? 'display:none' : ''; ?>">
                                                                                            <div id="unit_div<?= $xid; ?>" class="<?= ($ppng['plan_type']=='Partial') ? 'd-none' : ''; ?>">
                                                                                                <select name="unitt<?= $pr_id; ?>" id="unitt<?= $xid; ?>" class="custom-select2 form-control" style="width:100%">
                                                                                                    <?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $ppng['processing_unit_id'], 'WHERE type=2 AND is_active="active"', ''); ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </td>
                                                                                        
                                                                                        <td id="tdsupp<?= $xid; ?>" style="display:<?= ($ppng['process_type'] == 'supplier') ? 'block' : 'none'; ?>">
                                                                                            <div id="supp_div<?= $xid; ?>" class="<?= ($ppng['plan_type']=='Partial') ? 'd-none' : ''; ?>">
                                                                                                <select name="supplier<?= $pr_id; ?>" id="supplier<?= $xid; ?>" class="custom-select2 form-control" style="width:100%">
                                                                                                    <?= select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $ppng['supplier_id'], 'WHERE is_active="active"', ''); ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </td>
                                                                                        
                                                                                        <td>
                                                                                            <?php
                                                                                            if ($row['is_approved'] == 'true') {
                                                                                                $ppo = '<span class="border border-success rounded text-success">Approved</span>';
                                                                                            } else if ($row['is_approved'] == 'false') {
                                                                                                $ppo = '<span class="border border-danger rounded text-danger">Rejected</span>';
                                                                                            } else {
                                                                                                $ppo = '<span class="border border-info rounded text-info">Waiting</span>';
                                                                                            }
                                                                                            print $ppo; ?>
                                                                                        </td>
                                                                                        
                                                                                        <td>
                                                                                            <?php
                                                                                            if (!empty($ppng['id'])) {
                                                                                                $ppo = '<span class="border border-success rounded text-success">Planned</span>';
                                                                                            } else {
                                                                                                $ppo = '<span class="border border-danger rounded text-danger">Unplanned</span>';
                                                                                            }
                                                                                            print $ppo; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php if($row['process']==1) { ?>
                                                                                        <tr id="partial_tr<?= $xid; ?>" class="<?= ($ppng['plan_type']=='Partial') ? '' : 'd-none'; ?>">
                                                                                            <td colspan="6">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-3">
                                                                                                        <label>Partial Planning Type :</label>
                                                                                                        <select class="form-control custom-select2" onchange="partial_type_new(<?= $xid; ?>)" id="partial_type<?= $xid; ?>" name="partial_type<?= $pr_id; ?>" style="width:100%;">
                                                                                                            <option value="part">Combo & Part wise</option>
                                                                                                            <option value="size" <?= ($ppng['partial_type']=='size') ? 'selected': ''; ?>>Combo & Part & Size wise </option>
                                                                                                        </select>
                                                                                                        <br>
                                                                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                                                                    </div>
                                                                                                    
                                                                                                    <div class="col-md-9">
                                                                                                        <table class="table">
                                                                                                            <thead>
                                                                                                                <tr>
                                                                                                                    <th>Combo & Part & Color</th>
                                                                                                                    <th>Order Qty</th>
                                                                                                                    <th>Plan Qty</th>
                                                                                                                    <th>Plan for</th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody>
                                                                                                                <?php 
                                                                                                                    $ui = 0;
                                                                                                                    $h = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = '". $style ."' ");
                                                                                                                    
                                                                                                                    $ppf=0;
                                                                                                                    while($cmb = mysqli_fetch_array($h)) {
                                                                                                                        $part = $cmb['part_id'];
                                                                                                                        $color = $cmb['color_id'];
                                                                                                                        
                                                                                                                        $tqy = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(size_qty) as size_qty FROM sod_size WHERE sod_combo = '". $cmb['sod_combo'] ."'"));
                                                                                                                        
                                                                                                                        $com_id = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM cutting_partial_planning WHERE sod_part = '". $cmb['id'] ."'"));
                                                                                                                    $yid = $xid.$ui;
                                                                                                                    ?>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <input type="hidden" name="cutting_partial_id<?= $pr_id; ?>[]" value="<?= ($ppng['partial_type']=='part') ? $com_id['id'] : ''; ?>">
                                                                                                                            <input type="hidden" name="sod_part_id<?= $pr_id; ?>[]" value="<?= $cmb['id']; ?>">
                                                                                                                            <input type="hidden" name="combo_part_qty<?= $pr_id; ?>[]" value="<?= $tqy['size_qty']; ?>">
                                                                                                                            <u><?= color_name($cmb['combo_id']).'<br>'; ?></u>
                                                                                                                            <?= part_name($part) .' || '. color_name($color); ?></td>
                                                                                                                        <td><?= $tqy['size_qty']; ?></td>
                                                                                                                        <td><?= $tqy['size_qty']; ?></td>
                                                                                                                        <td>
                                                                                                                            <div class="partchange_div<?= $xid; ?> <?= ($ppng['partial_type']=='size') ? 'd-none': ''; ?>">
                                                                                                                                <select class="form-control custom-select2" name="combo_part_plan_for<?= $pr_id; ?>[]" id="part_plan_for_<?= $yid;  ?>" onchange="part_plan_for(<?= $yid;  ?>, 'part_plan_')" style="width:35%;">
                                                                                                                                    <option value="Unit">Unit</option>
                                                                                                                                    <option value="Supplier" <?= ($com_id['plan_for'] == 'Supplier')? 'selected': ''; ?>>Supplier</option>
                                                                                                                                </select>
                                                                                                                                
                                                                                                                                <select class="form-control custom-select2" name="combo_part_plan_to<?= $pr_id; ?>[]" id="part_plan_for_to<?= $yid;  ?>" style="width:60%;">
                                                                                                                                    <option value="">Select Unit</option>
                                                                                                                                    <?= ($com_id['plan_for'] == 'Supplier') ? 
                                                                                                                                    select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $com_id['plan_for_to'], 'WHERE is_active="active"', '`')
                                                                                                                                    :
                                                                                                                                    select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $com_id['plan_for_to'], 'WHERE type=2 AND is_active="active"', '`'); ?>
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                            <div class="sizechange_div<?= $xid; ?> <?= ($ppng['partial_type']=='size') ? '': 'd-none'; ?>">
                                                                                                                                <a class="btn btn-outline-info size-plan-modal" data-yid="<?= $yid; ?>" data-toggle="modal" data-target="#prod_planning-modal<?= $yid;  ?>" ><i class="fa fa-plus"></i> Add Plan</a>
                                                                                                                                
                                                                                                                                    <div class="modal fade bs-example-modal-lg" id="prod_planning-modal<?= $yid;  ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                                                                                                        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:1200px">
                                                                                                                                            <div class="modal-content">
                                                                                                                                                <div class="modal-header">
                                                                                                                                                    <p class="modal-title" id="myLargeModalLabel">Size Wise Planning</p>
                                                                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                                                                                </div>
                                                                                                                                                <div class="modal-body">
                                                                                                                                                    <p>Planning for <b class="text-danger"><?= part_name($part) .' - '. color_name($color); ?></b></p>
                                                                                                                                                    <table class="table">
                                                                                                                                                        <thead>
                                                                                                                                                            <tr>
                                                                                                                                                                <th>Size</th>
                                                                                                                                                                <th>Order Qty</th>
                                                                                                                                                                <th>Plan Qty</th>
                                                                                                                                                                <th colspan="">Plan For</th>
                                                                                                                                                            </tr>
                                                                                                                                                        </thead>
                                                                                                                                                        <tbody>
                                                                                                                                                            <?php
                                                                                                                                                            $yr = 1;
                                                                                                                                                            
                                                                                                                                                            $siz = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $cmb['sod_combo'] ."'");
                                                                                                                                                            while($srow = mysqli_fetch_array($siz)) {
                                                                                                                                                                
                                                                                                                                                                $size = $srow['variation_value'];
                                                                                                                                                                $qtyy = $srow['size_qty'];
                                                                                                                                                                $excs = $srow['excess_per'];
                                                                                                                                                                
                                                                                                                                                                $zid = $yid.$yr;
                                                                                                                                                                $si_id = $pr_id.$cmb['id'];
                                                                                                                                                                
                                                                                                                                                                $unic_sizId = $pr_id.$cmb['id'].$srow['id'];
                                                                                                                                                                
                                                                                                                                                                $iops = mysqli_query($mysqli, "SELECT * FROM cutting_partial_planning WHERE sod_part = '". $cmb['id'] ."' AND sod_size = '". $srow['id'] ."'");
                                                                                                                                                                if(mysqli_num_rows($iops)> 0) {
                                                                                                                                                                while($siii_id = mysqli_fetch_array($iops)) {
                                                                                                                                                                $ssd = $si_id.$srow['id'].$ppf;
                                                                                                                                                                ?>
                                                                                                                                                                <tr id="size_tr_<?= $ssd; ?>">
                                                                                                                                                                    <td id="sod_size_td<?= $ssd; ?>"><input type="hidden" name="cutting_partial_size_id<?= $si_id; ?>[]" value="<?= $siii_id['id']; ?>">
                                                                                                                                                                        <input type="hidden" name="sod_size_id<?= $si_id; ?>[]" id="sod_size<?= $ssd; ?>" value="<?= $srow['id']; ?>"> <?= variation_value($size); ?></td>
                                                                                                                                                                    <td><input type="hidden" name="size_order_qty<?= $si_id; ?>[]" id="size_order_qty<?= $ssd; ?>" value="<?= $qtyy; ?>" class="order_size_qty<?= $unic_sizId; ?>"><?= $qtyy; ?></td>
                                                                                                                                                                    <td><input type="number" name="size_plan_qty<?= $si_id; ?>[]" id="size_plan_qty<?= $ssd; ?>" data-sid="<?= $unic_sizId; ?>" onkeyup="sameSize(<?= $unic_sizId; ?>)" class="form-control validd<?= $si_id; ?> sameSize<?= $unic_sizId; ?>" value="<?= $siii_id['size_plan_qty'] ? $siii_id['size_plan_qty'] : $qtyy; ?>" style="max-width: 100px;"></td>
                                                                                                                                                                    <td>
                                                                                                                                                                        <select class="form-control custom-select2 validd<?= $si_id; ?>" name="size_plan_for_<?= $si_id; ?>[]" id="size_plan_for_<?= $ssd;  ?>" onchange="part_plan_for(<?= $ssd;  ?>, 'size_plan_')">
                                                                                                                                                                            <option value="Unit">Unit</option>
                                                                                                                                                                            <option value="Supplier" <?= ($siii_id['plan_for']=='Supplier') ? 'selected' : ''; ?>>Supplier</option>
                                                                                                                                                                        </select>
                                                                                                                                                                            
                                                                                                                                                                        <select class="form-control custom-select2 validd<?= $si_id; ?>" name="size_plan_for_to<?= $si_id; ?>[]" id="size_plan_for_to<?= $ssd;  ?>" style="width:60%;">
                                                                                                                                                                            <option value="">Select Unit</option>
                                                                                                                                                                            <?= ($siii_id['plan_for'] == 'Supplier') ? 
                                                                                                                                                                                select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $siii_id['plan_for_to'], 'WHERE is_active="active"', '`')
                                                                                                                                                                                :
                                                                                                                                                                                select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $siii_id['plan_for_to'], 'WHERE type=2 AND is_active="active"', '`'); ?>
                                                                                                                                                                        </select>
                                                                                                                                                                        
                                                                                                                                                                        <a class="border border-secondary rounded text-secondary" onclick="add_newsize_row(<?= $ssd.', '.$si_id; ?>)"><i class="fa fa-plus"></i></a>
                                                                                                                                                                    </td>
                                                                                                                                                                </tr>
                                                                                                                                                            <?php $ppf++; }} else { $ssd = $si_id.$srow['id'].$ppf; ?>
                                                                                                                                                                <tr id="size_tr_<?= $ssd; ?>">
                                                                                                                                                                    <td id="sod_size_td<?= $ssd; ?>"><input type="hidden" name="cutting_partial_size_id<?= $si_id; ?>[]" value="<?= $siii_id['id']; ?>">
                                                                                                                                                                        <input type="hidden" name="sod_size_id<?= $si_id; ?>[]" id="sod_size<?= $ssd; ?>" value="<?= $srow['id']; ?>"> <?= variation_value($size); ?></td>
                                                                                                                                                                    <td><input type="hidden" name="size_order_qty<?= $si_id; ?>[]" id="size_order_qty<?= $ssd; ?>" value="<?= $qtyy; ?>" class="order_size_qty<?= $unic_sizId; ?>"><?= $qtyy; ?></td>
                                                                                                                                                                    <td><input type="number" name="size_plan_qty<?= $si_id; ?>[]" id="size_plan_qty<?= $ssd; ?>" data-sid="<?= $unic_sizId; ?>" onkeyup="sameSize(<?= $unic_sizId; ?>)" class="form-control validd<?= $si_id; ?> sameSize<?= $unic_sizId; ?>" value="<?= $siii_id['size_plan_qty'] ? $siii_id['size_plan_qty'] : $qtyy; ?>" style="max-width: 100px;"></td>
                                                                                                                                                                    <td>
                                                                                                                                                                        <select class="form-control custom-select2 validd<?= $si_id; ?>" name="size_plan_for_<?= $si_id; ?>[]" id="size_plan_for_<?= $ssd;  ?>" onchange="part_plan_for(<?= $ssd;  ?>, 'size_plan_')">
                                                                                                                                                                            <option value="Unit">Unit</option>
                                                                                                                                                                            <option value="Supplier" <?= ($siii_id['plan_for']=='Supplier') ? 'selected' : ''; ?>>Supplier</option>
                                                                                                                                                                        </select>
                                                                                                                                                                            
                                                                                                                                                                        <select class="form-control custom-select2 validd<?= $si_id; ?>" name="size_plan_for_to<?= $si_id; ?>[]" id="size_plan_for_to<?= $ssd;  ?>" style="width:60%;">
                                                                                                                                                                            <option value="">Select Unit</option>
                                                                                                                                                                            <?= ($siii_id['plan_for'] == 'Supplier') ? 
                                                                                                                                                                                select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', $siii_id['plan_for_to'], 'WHERE is_active="active"', '`')
                                                                                                                                                                                :
                                                                                                                                                                                select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $siii_id['plan_for_to'], 'WHERE type=2 AND is_active="active"', '`'); ?>
                                                                                                                                                                        </select>
                                                                                                                                                                        
                                                                                                                                                                        <a class="border border-secondary rounded text-secondary" onclick="add_newsize_row(<?= $ssd.', '.$si_id; ?>)"><i class="fa fa-plus"></i></a>
                                                                                                                                                                    </td>
                                                                                                                                                                </tr>
                                                                                                                                                        <?php $ppf++; $yr++; } } ?>
                                                                                                                                                    </tbody>
                                                                                                                                                </table>
                                                                                                                                            </div>
                                                                                                                                            <div class="modal-footer">
                                                                                                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                                                                                                <button type="button" class="btn btn-outline-primary" onclick="validd_btn(<?= $si_id.', '.$yid; ?>)">Submit</button>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                <?php $ui++; } ?>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                <?php } }
                                                                            } else {
                                                                                print '<tr><td colspan="5" align="center">Budget Not Created</td></tr>';
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>

                                                                    <div class="pb-20" style="text-align:center;">
                                                                        <input type="hidden" name="btnSave">
                                                                        <a class="btn btn-outline-primary btnSave" data-style="<?= $style; ?>"><i class="fa-save fa"></i> Save Planning</a>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    <?php $x++; } ?>
								</div>
							</div>                            
                        <!-- </form> -->
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="prod_planning-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add Partial Planning</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form method="post" id="" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row" id="">
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
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
    <?php include('includes/end_scripts.php'); $_SESSION['msg'] = ''; ?>

    <script>
        $(document).ready(function() {

            function updateCtngWt(style) {
                var pcs_wt = parseFloat($("#pcs_wt" + style).val()) || 0;
                var qty = parseFloat($("#order_qty" + style).val()) || 0;
                var req_qty = pcs_wt * qty;
                $("#ctng_wt" + style).val(req_qty);
            }

            $(".combo_cls").change(function() {
                var style = $(this).data('style');
                var qtyy = $("#combo" + style + " option:selected").data('qty');
                
                $("#order_qty" + style).val(qtyy);
                $("#order_qty_text" + style).text(qtyy);
                updateCtngWt(style);
            });

            $(".pcs_wt").keyup(function() {
                var style = $(this).data('style');
                updateCtngWt(style);
            });
        });
    </script>

    <script>

        $(document).ready(function() {
            $(".add_btn").click(function(){
                var style = $(this).data('idd');
                
                var combo = $("#combo" + style).val();
                var combo_name = $("#combo" + style +" option:selected").text();
                var order_qty = $("#order_qty" + style).val();
                var fabric = $("#fabric" + style).val();
                var fabric_name = $("#fabric" + style +" option:selected").text();
                var gsm = $("#gsm" + style).val();
                var color = $("#color" + style).val();
                var color_name = $("#color" + style +" option:selected").text();
                var component = $("#component" + style).val();
                var component_name = $("#component" + style +" option:selected").text();
                var finishing_dia = $("#finishing_dia" + style).val();
                var pcs_wt = $("#pcs_wt" + style).val();
                var ctng_wt = $("#ctng_wt" + style).val();

                if(fabric=="") {
                    $("#fabric" + style).val();
                    message_noload('error', 'Fabric Required!', 1500);
                    return false;
                } else if(gsm=="") {
                    $("#gsm" + style).val();
                    message_noload('error', 'GSM Required!', 1500);
                    return false;
                } else if(color=="") {
                    $("#color" + style).val();
                    message_noload('error', 'Color Required!', 1500);
                    return false;
                } else if(component=="") {
                    $("#component" + style).val();
                    message_noload('error', 'Component Required!', 1500);
                    return false;
                } else if(finishing_dia=="") {
                    $("#finishing_dia" + style).val();
                    message_noload('error', 'Finishing Dia Required!', 1500);
                    return false;
                } else if(pcs_wt=="") {
                    $("#pcs_wt" + style).val();
                    message_noload('error', 'Piece Weight Required!', 1500);
                    return false;
                } else if(ctng_wt=="") {
                    $("#ctng_wt" + style).val();
                    message_noload('error', 'Cutting Required Weight Required!', 1500);
                    return false;
                } else {

                    var html = '<tr>';
                        html += '<td><input type="hidden" name="sod_combo[]" value="'+ combo +'">'+ combo_name +'</td>';
                        html += '<td><input type="hidden" name="order_qty[]" value="'+ order_qty +'">'+ order_qty +'</td>';
                        html += '<td><input type="hidden" name="fabric[]" value="'+ fabric +'">'+ fabric_name +'</td>';
                        html += '<td><input type="hidden" name="gsm[]" value="'+ gsm +'">'+ gsm +'</td>';
                        html += '<td><input type="hidden" name="color[]" value="'+ color +'">'+ color_name +'</td>';
                        html += '<td><input type="hidden" name="component[]" value="'+ component +'">'+ component_name +'</td>';
                        html += '<td><input type="hidden" name="finishing_dia[]" value="'+ finishing_dia +'">'+ finishing_dia +'</td>';
                        html += '<td><input type="hidden" name="pcs_wt[]" value="'+ pcs_wt +'">'+ pcs_wt +'</td>';
                        html += '<td><input type="hidden" name="req_wt[]" value="'+ ctng_wt +'">'+ ctng_wt +'</td>';
                        html += '<td><a class="btn" onclick="remove_tr(this)"><i class="fa fa-trash"></i></a></td>';
                        html +='</tr>';


                        $("#tbody" + style).append(html);
                        $("#tfoot" + style).find('input').val('');
                        $("#tfoot" + style).find('select').not("#combo" + style).val('').trigger('change');
                        // $("#combo" + style).val('all').trigger('change');

                        var rowCount = $("#tbody" + style + " tr").length;

                        (rowCount==0) ? $(".cbt" + style).addClass('d-none') : $(".cbt" + style).removeClass('d-none');
                }
            });
        });
    </script>
    
    <script>
        $(document).ready(function () {
            
            $(".btnSave").click(function() {
                $("#overlay").fadeIn(100);
                var style = $(this).data('style');
                $("#planningFrom" + style).submit();
            });

            $(".btn_consumption").click(function() {
                var style = $(this).data('style');
                
                var rowCount = $("#tbody" + style + " tr").length;
                
                if(rowCount==0) {
                    $(".cbt" + style).addClass('d-none');
                    message_noload('error', 'Select Fabric', 1500);
                    return false;
                } else {
                    $("#overlay").fadeIn(100);
                    $("#consumptionFrom" + style).submit();
                } 
            });

        });
        
        function validd_btn(name_id, yid) {
            
            var a = 0;
            $('.validd'+ name_id).each(function() {
                var cval = $(this).val();
                
                if(cval ==="") {
                    a++;
                    message_noload('error', 'Please fill in Plan Qty and Plan for options before Submit');
                    return false;
                }
            });
            
            if(a===0) {
                $("#prod_planning-modal" + yid).modal('hide');
            }
        }
        
        function sameSize(sid) {
            
            var max_qty = $(".order_size_qty" + sid).val();
            
            var actual = 0;
            
            $(".sameSize" + sid).each(function() {
                actual += parseInt($(this).val());
                
                if(actual>max_qty) {
                    $(this).val(0).select().focus();
                    message_noload('error', 'Quantity Exceed!', 2000);
                    return false;
                }
            });
        }
    </script>
    
    <script>
        function add_newsize_row(idd, name_id) {
            
            var size_order_qty = $("#size_order_qty" + idd).val();
            var size_plan_qty = $("#size_plan_qty" + idd).val();
            
            if(parseInt(size_order_qty)>parseInt(size_plan_qty)) {
                $("#overlay").fadeIn(100);
                
                var sod_size_name = $("#sod_size_td" + idd).text();
                    sod_size_name = $.trim(sod_size_name);
                var sod_size = $("#sod_size" + idd).val();
                var size_order_qty = $("#size_order_qty" + idd).val();
                var sid = $("#size_plan_qty" + idd).data('sid');
                
                var rand = idd+randd();
                
                var html = '<tr id="">';
                html += '<td><input type="hidden" name="cutting_partial_size_id'+ name_id +'[]" value=""><input type="hidden" name="sod_size'+ name_id +'[]" value="'+ sod_size +'"> '+ sod_size_name +'</td>';
                html += '<td><input type="hidden" name="size_order_qty'+ name_id +'[]" id="size_order_qty'+ rand +'" value="'+ size_order_qty +'">'+ size_order_qty +'</td>';
                html += '<td><input type="number" name="size_plan_qty'+ name_id +'[]" id="size_plan_qty'+ rand +'" onkeyup="sameSize('+ sid +')" class="form-control validd'+ name_id +' sameSize'+ sid +'" value="" placeholder="Plan Qty" style="max-width: 100px;"></td>';
                var jkk = "'size_plan_'";
                html += '<td><select class="form-control custom-select2 select22 validd'+ name_id +'" style="width:20%" name="size_plan_for_'+ name_id +'[]" id="size_plan_for_'+ rand +'" onchange="part_plan_for('+ rand +', '+ jkk +')">';
                html += '<option value="">Select</option><option value="Unit">Unit</option><option value="Supplier">Supplier</option></select>&nbsp;';
                html += '<select class="form-control custom-select2 select22 validd'+ name_id +'" name="size_plan_for_to'+ name_id +'[]" id="size_plan_for_to'+ rand +'" style="width:60%;"></select></td>';
                html += '</tr>';
                
                $("#size_tr_" + idd).after(html);
                
                $(".select22").each(function() {
                    $(this).select2();
                });
                $("#overlay").fadeOut(500);
            } else {
                message_noload('info', 'Ouantity Reached!', 2000);
                return false;
            }
        }
        
        function randd() {
            return Math.floor(10000 + Math.random() * 90000);
        }
    </script>
    
    <script>
        $(".size-plan-modal").click(function() {
            var yid = $(this).data('yid');
            $('#prod_planning-modal' + yid).modal({ backdrop: 'static', keyboard: false });
        });
    </script>
    
    <script>
        function partial_type_new(xid) {
            
            $("#overlay").fadeIn(100);
            
            var partial_type = $("#partial_type" + xid).val();
            
            if(partial_type == 'size') {
                $(".sizechange_div" + xid).removeClass('d-none');
                $(".partchange_div" + xid).addClass('d-none');
            } else {
                $(".sizechange_div" + xid).addClass('d-none');
                $(".partchange_div" + xid).removeClass('d-none');
            }
            
            $("#overlay").fadeOut(500);
        }
    </script>
    
    <script>
        function part_plan_for(yid, idd) {
            
            var p_for = $("#"+ idd +"for_" + yid).val();
            
            var unit = '<option value="">Select Unit</option><?php echo select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', 'WHERE type=2 AND is_active="active"', '`'); ?>';
            
            var sup = '<option value="">Select Supplier</option><?php echo select_dropdown('supplier', array('id', 'supplier_name'), 'supplier_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
            
            if(p_for == 'Supplier') {
                $("#"+ idd +"for_to" + yid).html(sup);
            } else if(p_for == 'Unit') {
                $("#"+ idd +"for_to" + yid).html(unit);
            } else {
                $("#"+ idd +"for_to" + yid).html('');
            }
        }
    </script>
    
    <script>
        function change_supplier_unit(xid) {
            
            $("#overlay").fadeIn(100);
            
            var plan_type = $("#plan_type" + xid).val();
            var style_id = $("#style_id" + xid).val();
                
            if(plan_type=="Partial") {
                
                $("#type_div" + xid).addClass('d-none');
                $("#unit_div" + xid).addClass('d-none');
                $("#supp_div" + xid).addClass('d-none');
                $("#partial_tr" + xid).removeClass('d-none');
            } else {
                $("#type_div" + xid).removeClass('d-none');
                $("#unit_div" + xid).removeClass('d-none');
                $("#supp_div" + xid).removeClass('d-none');
                $("#partial_tr" + xid).addClass('d-none');
            }
            
            $("#overlay").fadeOut(500);
            
        }
    </script>

    <script>
        function formSubmit(id) {

            var a = $("#process_list" + id).val();
            var b = $("#typee" + id).val();
            var c = $("#unitt" + id).val();
            var d = $("#supplier" + id).val();
            var e = $("#so_id" + id).val();
            var f = $("#style_id" + id).val();

            var form = 'process_list=' + a + '&so_id=' + e + '&style_id=' + f + '&pp_type=' + b + '&unitt=' + c + '&supplier=' + d;

            if (a == "") {
                message_noload('warning', 'Select Process List!', 2000);
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?savePlanning=1',
                data: form,
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);
                    
                    if(json.result==0) {
                        message_reload('success', 'Planning Saved');
                    } else {
                        message_error();
                    }
                }
            })
        }
    </script>

    <script>
        function typeRsup(id) {

            var val = $("#typee" + id).val();

            if (val == 'unit') {
                $("#tdunit" + id).show();
                $("#tdsupp" + id).hide();
            } else {
                $("#tdunit" + id).hide();
                $("#tdsupp" + id).show();
            }
        }
    </script>

</html>