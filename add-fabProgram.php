<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$ID = $_GET['id'];

if (isset($_REQUEST['saveForm'])) {
    
    if($_REQUEST['aop_']=="yes") {
        if (!empty($_FILES['aop_image']['name'])) {
            
            $fille = explode('.', $_FILES['aop_image']['name']);
            
            $newName = round(microtime(true)) .rand(10000,100000). '.' . end($fille);
            $uploaddir = 'uploads/Fabric_planing/';
            $uploadfile = $uploaddir . $newName;
            move_uploaded_file($_FILES['aop_image']['tmp_name'], $uploadfile);
            $pics = $newName;
        } else {
            if(isset($_GET['type']) && $_GET['type']=='edit') {
                $pics = $_REQUEST['aop_Old'];
            } else {
                $pics = '';
            }
        }
        $aop_name = $_REQUEST['aop_name'];
    } else {
        $aop_name = '';
        $pics = '';
    }
    $soId = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sales_order_id FROM sales_order_detalis WHERE id=".$ID));
    
    for($iop=0; $iop<count($_REQUEST['sod_size']); $iop++) {
        $component_detail[] = $_REQUEST['sod_size'][$iop].'--'.$_REQUEST['FinishingDia'][$iop].'--'.$_REQUEST['pieceWt'][$iop].'--'.$_REQUEST['reqPieceWt'][$iop];
    }
    
    for($noc=0; $noc<count($_REQUEST['YarnId']); $noc++) {
        if($_REQUEST['YarnId'][$noc]!="" && $_REQUEST['mixedPer'][$noc]>0) {
            $yrn[] = $_REQUEST['YarnId'][$noc].'='.$_REQUEST['YarnColor'][$noc].'='.$_REQUEST['mixedPer'][$noc];
        }
    }
    
    $new = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_part WHERE id = '". $_REQUEST['sod_part'] ."'"));
    
    $sData = array(
        'sales_order_id' => $soId['sales_order_id'],
        'sales_order_detalis_id' => $ID,
        'sod_part' => $new['id'],
        'sod_combo' => $new['sod_combo'],
        'combo_id' => $new['combo_id'],
        'part_id' => $new['part_id'],
        'part_color' => $new['color_id'],
        'fabric_type' => $_REQUEST['fabric_type'],
        'fabric' => $_REQUEST['fabric'],
        'gsm' => $_REQUEST['gsm_'],
        'dyeing_color' => $_REQUEST['dyeing_color'],
        'aop' => $_REQUEST['aop_'],
        'aop_name' => $aop_name,
        'aop_image' => $pics,
        'created_by' => $logUser,
        'created_unit' => $logUnit,
        
        'component' => implode(',', $_REQUEST['component']),
        'component_detail' => json_encode($component_detail),
        
        'yarn_detail' => json_encode($yrn),
        'tot_finishingDia' => array_sum($_REQUEST['FinishingDia']),
        'tot_pieceWt' => array_sum($_REQUEST['pieceWt']),
        'tot_reqWt' => array_sum($_REQUEST['reqPieceWt']),
    );
    
    if(isset($_GET['type']) && $_GET['type']=='edit')
    {
        $ins = Update('sales_order_fabric_program', $sData, " WHERE id = '" . $_GET['pid'] . "'");
        
        Delete('sales_order_fabric_components_process', 'WHERE fabric_program_id='. $_GET['pid']);
        Delete('sales_order_fabric_components_yarn', 'WHERE fabric_program_id='. $_GET['pid']);
        // Delete('sales_order_fabric_components', 'WHERE fabric_program_id='. $_GET['pid']);
        
        $programId = $_GET['pid'];
        
        timeline_history('Update', 'sales_order_fabric_program', $programId, 'Fabric Program Updated with components, yarn details, and process details');
    } else {
        $ins = Insert('sales_order_fabric_program', $sData);
        $programId = mysqli_insert_id($mysqli);
        
        timeline_history('Insert', 'sales_order_fabric_program', $programId, 'Fabric Program Added with components, yarn details, and process details');
    }
    
    
    for($n1=0; $n1<count($_REQUEST['ProcessId']); $n1++) {
        
        $process = array(
            'sales_order_id' => $soId['sales_order_id'],
            'sales_order_detalis_id' => $ID,
            
            'sod_part' => $new['id'],
            'sod_combo' => $new['sod_combo'],
            'combo_id' => $new['combo_id'],
            'part_id' => $new['part_id'],
            'part_color' => $new['color_id'],
            
            'fabric_program_id' => $programId,
            'process_id' => $_REQUEST['ProcessId'][$n1],
            'process_order' => $_REQUEST['ProcessOrder'][$n1],
            'lossPer' => $_REQUEST['lossPer'][$n1],    
        );
        
        Insert('sales_order_fabric_components_process', $process);
    }
    
    for($op=0; $op<count($_REQUEST['sod_size']); $op++) {
        
        // $yui = explode('--', $_REQUEST['sod_size'][$op]);
        
        $sixx = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_size WHERE id = '". $_REQUEST['sod_size'][$op] ."'"));
        
        $component = array(
            'fabric_program_id' => $programId,
            'sales_order_id' => $soId['sales_order_id'],
            'sales_order_detalis_id' => $ID,
            
            'sod_part' => $new['id'],
            'sod_combo' => $new['sod_combo'],
            'sod_size' => $sixx['id'],
            'combo_id' => $new['combo_id'],
            'part_id' => $new['part_id'],
            'part_color' => $new['color_id'],
            
            'variation_value' => $sixx['variation_value'],
            'order_qty' => $sixx['size_qty'],
            'excess' => $sixx['excess_per'],
            'excess_qty' => $sixx['excess_qty'],
            
            'fabric' => $_REQUEST['fabric'],
            'finishing_dia' => $_REQUEST['FinishingDia'][$op],
            'piece_wt' => $_REQUEST['pieceWt'][$op],
            'req_wt' => $_REQUEST['reqPieceWt'][$op],   
        );
        
        if($_GET['type']=='edit') {
            
            $components = array(
                'finishing_dia' => $_REQUEST['FinishingDia'][$op],
                'piece_wt' => $_REQUEST['pieceWt'][$op],
                'req_wt' => $_REQUEST['reqPieceWt'][$op],   
            );
            
            Update('sales_order_fabric_components', $components, 'WHERE id = '. $_REQUEST['components_id'][$op]);
        } else {
            Insert('sales_order_fabric_components', $component);
        }
    }

    for($n=0; $n<count($_REQUEST['YarnId']); $n++) {
        
        $yarn = array(
            'sales_order_id' => $soId['sales_order_id'],
            'sales_order_detalis_id' => $ID,
            
            'sod_part' => $new['id'],
            'sod_combo' => $new['sod_combo'],
            'combo_id' => $new['combo_id'],
            'part_id' => $new['part_id'],
            'part_color' => $new['color_id'],
            
            'fabric_program_id' => $programId,
            'fabric' => $_REQUEST['fabric'],
            'yarn_id' => $_REQUEST['YarnId'][$n],   
            'yarn_color' => $_REQUEST['YarnColor'][$n],
            'mixed' => $_REQUEST['mixedPer'][$n],    
        );
        
        if($_REQUEST['YarnId'][$n]!="" && $_REQUEST['mixedPer'][$n]!="") {
            Insert('sales_order_fabric_components_yarn', $yarn);
        }
    }
    
    if(isset($_GET['type']) && $_GET['type']=='edit')
    {
        $_SESSION['msg'] = "updated";
    } else {
        $_SESSION['msg'] = "saved";
    }

    header("Location:add-fabProgram.php?id=".$ID."&tab=".$_REQUEST['saveForm']);


    exit;
}

if (isset($_GET["id"])) {
    $id = $ID;
} else {
    $id = '';
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Add Fabric Program</title>

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
        /*.accordion span {*/
        /*    color: #e83e8c;*/
            /* font-size: 20px; */
        /*}*/

        .card-body h5 {
            color: #1b00ff;
            text-decoration: underline;
            text-transform: uppercase;
        }
        
        
        .nav.vtabs.customtab .nav-item.show .nav-link, .nav.vtabs.customtab .nav-link.active {
            border-color: #d1d1d3;
        }
        
        .tcr {
            text-align:center;
        }
        
        .btn-outline-primary:hover {
            color: #fff !important;
        }
        
        .theadInput {
            border-top: none;
            border-right: none;
            border-left: none;
            background-color: #f7f7f7;
            border-radius: inherit;
        }
        
        .theadInput:focus {
                background-color: #f7f7f7;
                border-color: #0a0a0a;
        }
        
        .tableNew th {
            min-width:180px;
        }

        .tab-pane {
            border-bottom: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            border-left: 1px solid #dee2e6;
        }
    </style>

    
<?php
    $qry = "SELECT a.* FROM sales_order_detalis a ";
    $qry .= " WHERE a.id='" . $id . "' ORDER BY a.id ASC ";
    $query = mysqli_query($mysqli, $qry);
    $x = 1;
    $sql = mysqli_fetch_array($query);
?>


    <div class="main-container">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Component Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Component Updated.
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
        <?php }
        $_SESSION['msg'] = '';
        ?>
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
            
                <div class="card-box mb-30">
                    <?php page_spinner(); if(FABRIC_PROG_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary showmodal" href="fabricProgram.php" style="float: right;">Program List</a>
                        <h4 class="text-blue h4">Fabric Program for <span class="text-danger u"><?= $sql['style_no']; ?></span></h4>
                    </div>
                    <div class="pd-20">
                        
                        <div class="tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                    $x = 1;
                                    $nql = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = '". $sql['id'] ."' ORDER BY id ASC");
                                    while($ress = mysqli_fetch_array($nql)) {
                                    $tbb = isset($_GET['tab']) ? $_GET['tab'] : 1;
                                ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ($x == $tbb) ? 'active' : ''; ?>" data-toggle="tab" href="#tab<?= $x; ?>" role="tab" aria-selected="true"><?= part_name($ress['part_id']); ?>  - <?= color_name($ress['color_id']); ?></a>
                                    </li>
                                <?php $x++; } ?>
                            </ul>
                            <div class="tab-content">
                                <?php
                                    $xx = 1;
                                    $opl = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = '". $sql['id'] ."' ORDER BY id ASC");
                                    while($sod_part = mysqli_fetch_array($opl)) {
                                    
                                    $tbb = isset($_GET['tab']) ? $_GET['tab'] : 1;
                                ?>
                                    <div class="tab-pane fade <?= ($xx == $tbb) ? 'show active' : ''; ?>" id="tab<?= $xx; ?>" role="tabpanel">
                                        <form method="POST" id="programForm<?= $xx; ?>" enctype= multipart/form-data>
                                            
                                            <input type="hidden" name="saveForm" value="<?= $xx ?>">
                                            <input type="hidden" name="sod_part" id="sod_part<?= $xx ?>" value="<?= $sod_part['id']; ?>">
                                            
                                            <div class="pd-20">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <table class="table">
                                                            <tr>
                                                                <td style="border-top: none;text-decoration: underline;">BO: <?= sales_order_code($sql['sales_order_id']); ?></td>
                                                                <td style="border-top: none;text-decoration: underline;">PO Number: <?= $sql['po_num']; ?></td>
                                                                <td style="border-top: none;text-decoration: underline;">Order Qty: <?= $sql['total_qty']; ?></td>
                                                                <td style="border-top: none;text-decoration: underline;">Cut Plan Qty: <?= round($sql['total_qty'] + (($sql['excess']/100)*$sql['total_qty'])) ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span class="border border-secondary rounded text-secondary">Part Color : <?= color_name($sod_part['color_id']); ?></span>
                                                    </div>
                                                    <?php if($sql['edit_fab_requirement'] == 'yes') { ?>
                                                    <div class="col-md-3">
                                                        <select class="custom-select2 form-control" name="" id="from_part<?= $xx ?>" style="width:100%;" onchange="show_copyBtn(<?= $xx ?>)">
                                                            <option value="">Select BO details to Copy Program </option>
                                                            <?php
                                                            
                                                            $ppo = "SELECT * ";
                                                            $ppo .= " FROM sales_order_fabric_program a ";
                                                            $ppo .= " GROUP BY a.part_color, a.part_id, a.sales_order_detalis_id, a.sales_order_id ";
                                                            
                                                            $qio = mysqli_query($mysqli, $ppo);
                                                            
                                                            while($fthh = mysqli_fetch_array($qio)) {
                                                            ?>
                                                                <option value="<?= $fthh['sod_part']; ?>" ><?= sales_order_code($fthh['sales_order_id']) .' || '. sales_order_style($fthh['sales_order_detalis_id']) .' || '. part_name($fthh['part_id']) .' || '. color_name($fthh['part_color']); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a class="btn btn-outline-secondary c-Btn<?= $xx ?> d-none" onclick="copyPart(<?= $xx ?>, <?=  $sod_part['id']; ?>, <?= $_GET['id']; ?>)">Copy Part</a>
                                                    </div>
                                                    <div class="col-md-12"><br></div>
                                                    <?php } ?>
                                                </div>

                                                <?php if($sql['edit_fab_requirement'] == 'yes') { ?>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="fieldrequired">Fabric Type</th>
                                                            <th class="fieldrequired">Fabric</th>
                                                            <th class="fieldrequired">GSM</th>
                                                            <th class="fieldrequired">Dyeing Color</th>
                                                            <th class="fieldrequired">AOP</th>
                                                            <th class="fieldrequired">Component</th>
                                                            <th class="fieldrequired">Yarn</th>
                                                            <th class="fieldrequired">Process</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($_GET['type']=='edit' && $_GET['tab']==$xx) {
                                                            $IVB = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_program WHERE id = '". $_GET['pid'] ."'"))
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="fabric_type" id="fabric_type<?= $xx; ?>" onchange="fabric_typeChange(<?= $xx; ?>)" style="width:100%">
                                                                        <option value="">Select Type</option>
                                                                        <option value="FAB_SOLID" <?= ($IVB['fabric_type']=='FAB_SOLID') ? 'selected' : ''; ?>>Solid</option>
                                                                        <option value="FAB_YANDD" <?= ($IVB['fabric_type']=='FAB_YANDD') ? 'selected' : ''; ?>>Y/D</option>
                                                                        <option value="FAB_MELANGE" <?= ($IVB['fabric_type']=='FAB_MELANGE') ? 'selected' : ''; ?>>Melange</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="fabric" id="fabric<?= $xx; ?>" style="width:100%">
                                                                        <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', $IVB['fabric'], '', ''); ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="gsm_" placeholder="GSM" id="gsm_<?= $xx; ?>" value="<?= $IVB['gsm']; ?>" style="max-width: 100px !important;">
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="dyeing_color" id="dyeing_color<?= $xx; ?>" style="width:100%">
                                                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $IVB['dyeing_color'], '', ''); ?>
                                                                    </select>
                                                                </td>
                                                                <td class="d-flex">
                                                                    <select class="custom-select2 form-control" name="aop_" id="aop_<?= $xx; ?>" onchange="OpenAOPmodal(<?= $xx; ?>)" style="width:80%">
                                                                        <option value="no">No</option>
                                                                        <option value="yes" <?= ($IVB['aop']=='yes') ? 'selected' : ''; ?>>Yes</option>
                                                                    </select>&nbsp;
                                                                    <a onclick="OpenAOPmodal(<?= $xx; ?>)" class="border border-secondary rounded text-secondary <?= ($IVB['aop']=='yes') ? '' : 'd-none'; ?> aop_eye"><i class="icon-copy fa fa-eye" aria-hidden="true"></i></a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#component-addModal<?= $xx; ?>" style="width:100%;"><i class="fa fa-plus"></i> Edit</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" onclick="shoeYarnAddModal(<?= $xx; ?>)" style="width:100%;"><i class="fa fa-plus"></i> Edit</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#process-addModal<?= $xx; ?>" style="width:100%;"><i class="fa fa-plus"></i> Edit</a>
                                                                </td>


                                                                <td>
                                                                    <a class="btn btn-outline-primary" onclick="SaveProgram(<?= $xx; ?>, 'edit')" style="color: #1b00ff;"><i class="icon-copy fa fa-save" aria-hidden="true"></i> Update</a>
                                                                    <a class="btn btn-outline-secondary" onclick="window.location.href='add-fabProgram.php?id=<?= $ID; ?>&tab=<?= $xx; ?>'">Cancel</a>
                                                                </td>
                                                            </tr>
                                                        <?php } else  if($_GET['type']=='copy' && $_GET['tab']==$xx) {
                                                            $IVB = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_program WHERE id = '". $_GET['pid'] ."'"))
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="fabric_type" id="fabric_type<?= $xx; ?>" onchange="fabric_typeChange(<?= $xx; ?>)" style="width:100%">
                                                                        <option value="">Select Type</option>
                                                                        <option value="FAB_SOLID" <?= ($_GET['ul_fabTyp']=='true' && $IVB['fabric_type']=='FAB_SOLID') ? 'selected' : ''; ?>>Solid</option>
                                                                        <option value="FAB_YANDD" <?= ($_GET['ul_fabTyp']=='true' && $IVB['fabric_type']=='FAB_YANDD') ? 'selected' : ''; ?>>Y/D</option>
                                                                        <option value="FAB_MELANGE" <?= ($_GET['ul_fabTyp']=='true' && $IVB['fabric_type']=='FAB_MELANGE') ? 'selected' : ''; ?>>Melange</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="fabric" id="fabric<?= $xx; ?>" style="width:100%">
                                                                        <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', ($_GET['ul_fabric']=='true') ? $IVB['fabric'] : '', '', ''); ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="gsm_" id="gsm_<?= $xx; ?>" value="<?= ($_GET['ul_gsm'] == 'true') ? $IVB['gsm'] : ''; ?>" placeholder="GSM" style="max-width: 100px !important;">
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="dyeing_color" id="dyeing_color<?= $xx; ?>" style="width:100%">
                                                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', ($_GET['ul_dyClr']=='true') ? $IVB['dyeing_color'] : '', '', ''); ?>
                                                                    </select>
                                                                </td>
                                                                <td class="d-flex">
                                                                    <select class="custom-select2 form-control" name="aop_" id="aop_<?= $xx; ?>" onchange="OpenAOPmodal(<?= $xx; ?>)" style="width:80%">
                                                                        <option value="no">No</option>
                                                                        <option value="yes" <?= ($_GET['ul_aop']=='true' && $IVB['aop']=='yes') ? 'selected' : ''; ?>>Yes</option>
                                                                    </select>&nbsp;
                                                                    <a onclick="OpenAOPmodal(<?= $xx; ?>)" class="border border-secondary rounded text-secondary <?= ($_GET['ul_aop']=='true' && $IVB['aop']=='yes') ? '' : 'd-none'; ?> aop_eye"><i class="icon-copy fa fa-eye" aria-hidden="true"></i></a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#component-addModal<?= $xx; ?>" style="width:100%;"><i class="fa fa-plus"></i> Edit</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" onclick="shoeYarnAddModal(<?= $xx; ?>)" style="width:100%;"><i class="fa fa-plus"></i> Edit</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#process-addModal<?= $xx; ?>" style="width:100%;"><i class="fa fa-plus"></i> Edit</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-primary" onclick="SaveProgram(<?= $xx; ?>, 'add')" style="color: #1b00ff;"><i class="icon-copy dw dw-copy"></i> Copy</a>
                                                                    <a class="btn btn-outline-secondary" onclick="window.location.href='add-fabProgram.php?id=<?= $ID; ?>&tab=<?= $xx; ?>'">Cancel</a>
                                                                </td>
                                                            </tr>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="fabric_type" id="fabric_type<?= $xx; ?>" onchange="fabric_typeChange(<?= $xx; ?>)" style="width:100%">
                                                                        <option value="">Select Type</option>
                                                                        <option value="FAB_SOLID">Solid</option>
                                                                        <option value="FAB_YANDD">Y/D</option>
                                                                        <option value="FAB_MELANGE">Melange</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="fabric" id="fabric<?= $xx; ?>" style="width:100%">
                                                                        <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', '', '', ''); ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control" name="gsm_" placeholder="GSM" id="gsm_<?= $xx; ?>" style="max-width: 100px !important;">
                                                                </td>
                                                                <td>
                                                                    <select class="custom-select2 form-control" name="dyeing_color" id="dyeing_color<?= $xx; ?>" style="width:100%">
                                                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', ''); ?>
                                                                    </select>
                                                                </td>
                                                                <td class="d-flex">
                                                                    <select class="custom-select2 form-control" name="aop_" id="aop_<?= $xx; ?>" onchange="OpenAOPmodal(<?= $xx; ?>)" style="width:80%">
                                                                        <option value="no">No</option>
                                                                        <option value="yes">Yes</option>
                                                                    </select>&nbsp;
                                                                    <a onclick="OpenAOPmodal(<?= $xx; ?>)" class="border border-secondary rounded text-secondary d-none aop_eye"><i class="icon-copy fa fa-eye" aria-hidden="true"></i></a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#component-addModal<?= $xx; ?>" style="width:100%;"><i class="fa fa-plus"></i> Add</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" onclick="shoeYarnAddModal(<?= $xx; ?>)" style="width:100%;"><i class="fa fa-plus"></i> Add</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-secondary" onclick="OpenProcessModal(<?= $xx; ?>)" style="width:100%;"><i class="fa fa-plus"></i> Add</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-outline-primary" onclick="SaveProgram(<?= $xx; ?>, 'add')" style="color: #1b00ff;"><i class="icon-copy fa fa-save" aria-hidden="true"></i> Save</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="9">
                                                                    <a class="btn btn-outline-info text-info copyBtn d-none" onclick="copyBtn(<?= $ID; ?>,<?= $xx; ?>)" ><i class="icon-copy dw dw-copy"></i> Copy</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                <?php } else {
                                                    print '<p class="text-danger">Fabric Process Started for this style. Unable to add or edit.</p>';
                                                } ?>
                                            </div>
                                            
                                            <div style="overflow-y:auto">
                                                <br><br>
                                                <table class="table table-bordered table-striped tableNew">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" value="" id="ul_fabTyp<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> Fabric Type</th>
                                                            <th><input type="checkbox" value="" id="ul_fabric<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> Fabric</th>
                                                            <th><input type="checkbox" value="" id="ul_gsm<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> GSM</th>
                                                            <th><input type="checkbox" value="" id="ul_dyClr<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> Dying Color</th>
                                                            <th><input type="checkbox" value="" id="ul_aop<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> AOP</th>
                                                            <th><input type="checkbox" value="" id="ul_comp<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> Component</th>
                                                            <th><input type="checkbox" value="" id="ul_yarn<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> Yarn - Mixed %</th>
                                                            <th><input type="checkbox" value="" id="ul_process<?= $xx; ?>" class="mainHead <?= ($_GET['type']=='edit') ? 'd-none' : ''; ?>"> Process - Loss %</th>
                                                            <?php if($sql['edit_fab_requirement'] == 'yes') { print '<th>Action</th>'; } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if($_GET['type']=='edit') {
                                                            $pid = "AND a.id != '". $_GET['pid'] ."'";
                                                        } else {
                                                            $pid = "";
                                                        }
                                                        
                                                            
                                                            $iqy = "SELECT a.* ";
                                                            $iqy .= " FROM sales_order_fabric_program a ";
                                                            $iqy .= " WHERE a.sales_order_detalis_id='". $ID ."' AND a.sod_part='". $sod_part['id'] ."' $pid ";
                                                            
                                                            $ujn = mysqli_query($mysqli, $iqy);
                                                            $row_sum[] = mysqli_num_rows($ujn);
                                                            if(mysqli_num_rows($ujn)>0) {
                                                                while($row = mysqli_fetch_array($ujn)) {
                                                                    
                                                                    $hb = mysqli_query($mysqli, "SELECT component_name FROM mas_component WHERE id IN (". $row['component'] .")"); 
                                                                    while($frw = mysqli_fetch_array($hb)) {
                                                                        $compNm[$xx][$row['id']][] = $frw['component_name'];
                                                                        
                                                                        $fabric_component_id[$xx][$row['id']][] = $row['id'];
                                                                    }
                                                                    
                                                                    $processSel = mysqli_query($mysqli, "SELECT b.process_name, a.process_order ,a.lossPer FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id=b.id WHERE a.fabric_program_id='". $row['id'] ."' ORDER BY a.process_order ASC");
                                                                    while($row_processSel = mysqli_fetch_array($processSel)) {
                                                                        $processNm[$xx][$row['id']][] = $row_processSel['process_order'].'. '.$row_processSel['process_name'].' - '.$row_processSel['lossPer'].' %';
                                                                    }
                                                                    
                                                                    $ic_typ = array(
                                                                        'FAB_SOLID' => 'Solid',
                                                                        'FAB_YANDD' => 'Y/D',
                                                                        'FAB_MELANGE' => 'Melange',
                                                                    );
                                                                ?>
                                                                <tr id="addedFabTr<?= $row['id']; ?>">
                                                                    <td>
                                                                        <?php if($_GET['type']!='edit') { ?>
                                                                            <input type="checkbox" value="<?= $row['id']; ?>" class="row_copy">
                                                                        <?php } ?>
                                                                        <?= $ic_typ[$row['fabric_type']]; ?></td>
                                                                    <td><?= fabric_name($row['fabric']); ?></td>
                                                                    <td><?= $row['gsm']; ?></td>
                                                                    <td><?= color_name($row['dyeing_color']); ?></td>
                                                                    <td>
                                                                        <?php if($row['aop']=='no') { print '-';} else { print $row['aop_name']; ?>
                                                                        
                                                                            <a href="download.php?f=uploads/Fabric_planing/<?= $row['aop_image']; ?>" target="_blank" style="float: right;"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i></a>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <div style="display: flex;justify-content: space-between;">
                                                                            <span><?= implode(',', $compNm[$xx][$row['id']]); ?></span>
                                                                            <span><a onclick="AddedComponentDetails(<?= $row['id']; ?>)"><i class="fa fa-eye"></i></a></span>
                                                                        </div>
                                                                        <br>
                                                                        In-House Wt: <?= $row['tot_reqWt']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                            $yannSel = mysqli_query($mysqli, "SELECT a.mixed, a.yarn_color, a.yarn_id FROM sales_order_fabric_components_yarn a WHERE a.fabric_program_id='". $row['id'] ."'");
                                                                            if(mysqli_num_rows($yannSel)>0) {
                                                                                while($row_yannSel = mysqli_fetch_array($yannSel)) {
                                                                                    
                                                                                    $ycNm = ($row_yannSel['yarn_color']>0) ? ' - '. color_name($row_yannSel['yarn_color']) : '';
                                                                                    print mas_yarn_name($row_yannSel['yarn_id']).$ycNm.' - '.$row_yannSel['mixed'].' %<br>';
                                                                                }
                                                                            } else { echo '-'; }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                            $processSel = mysqli_query($mysqli, "SELECT a.process_id, a.process_order ,a.lossPer FROM sales_order_fabric_components_process a WHERE a.fabric_program_id='". $row['id'] ."' ORDER BY a.process_order ASC");
                                                                            if(mysqli_num_rows($processSel)>0) {
                                                                                while($row_processSel = mysqli_fetch_array($processSel)) {
                                                                                    print $row_processSel['process_order'].'. '. process_name($row_processSel['process_id']) .' - '.$row_processSel['lossPer'].' %<br>';
                                                                                }
                                                                            } else { print '-'; }
                                                                        ?>
                                                                    </td>
                                                                    
                                                                    <?php if($sql['edit_fab_requirement'] == 'yes') { print '<td>'; if(FABRIC_PROG_EDIT==1) { ?>
                                                                        <a onclick="EditFabProgram(<?= $ID; ?>,<?= $xx; ?>, <?= $row['id']; ?>)" class="border border-secondary rounded text-secondary"><i class="icon-copy fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                                    <?php } if(FABRIC_PROG_DELETE==1) { ?>
                                                                        <a onclick="deleteFabProgram(<?= $row['id']; ?>)" class="border border-secondary rounded text-secondary"><i class="icon-copy fa fa-trash" aria-hidden="true"></i></a>
                                                                    <?php } print '</td>'; } ?>
                                                                    </td>
                                                                </tr>
                                                        <?php } } else {
                                                            print '<tr><td colspan="9" class="tcr">-- Nothing Found --</td></tr>';
                                                        }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <?php if($_GET['type']=='edit' && $_GET['tab']==$xx) { ?>
                                            
                                                <div class="modal fade bs-example-modal-lg" id="component-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Edit Component Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Component</label>
                                                                        <select class="custom-select2 form-control" name="component[]" id="component<?= $xx; ?>" style="width:100%" multiple>
                                                                            <?= select_dropdown_multiple('mas_component', array('id', 'component_name'), 'component_name ASC', $IVB['component'], '', '1'); ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <br>
                                                                <div style="overflow-y:auto">
                                                                    <table class="table table-bordered">
                                                                        <thead style="background-color: #f7f7f7;">
                                                                            <tr>
                                                                                <th>Size</th>
                                                                                <th>Order Qty</th>
                                                                                <th>Cut PlanQty</th>
                                                                                <th>Finishing Dia <input class="form-control theadInput" placeholder="Common Dia" id="FinishingDia_main<?= $xx ?>" onkeyup="changeFinishingDia(<?= $xx; ?>)"></th>
                                                                                <th>Piece Wt <input class="form-control theadInput" placeholder="Common Dia" id="pieceWt_main<?= $xx ?>" onkeyup="changepieceWt(<?= $xx; ?>)"></th>
                                                                                <th>Req Wt</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                                $zz = 1;
                                                                                
                                                                                $cmt = mysqli_query($mysqli, "SELECT * FROM sales_order_fabric_components WHERE fabric_program_id = '". $_GET['pid'] ."'");
                                                                                while($cmbtt = mysqli_fetch_array($cmt)) {
                                                                            ?>
                                                                                <tr>
                                                                                    <td><?= variation_value($cmbtt['variation_value']); ?></td>
                                                                                    <td><?= $oqTot[$xx][] = $cmbtt['order_qty']; ?></td>
                                                                                    <td>
                                                                                        <input type="hidden" name="components_id[]" value="<?= $cmbtt['id']; ?>">
                                                                                        <input type="hidden" name="sod_size[]" value="<?= $cmbtt['sod_size']; ?>">
                                                                                        <input type="hidden" class="cutplanZ<?= $xx.$zz; ?>" value="<?= $cmbtt['excess_qty']; ?>">
                                                                                        <?= $exTot[$xx][] = $cmbtt['excess_qty']; ?>
                                                                                    </td>
                                                                                    <td><input type="text" name="FinishingDia[]" placeholder="Finishing Dia" placeholder="Finishing Dia" id="FinishingDia<?= $xx; ?>" class="form-control FinishingDia<?= $xx; ?>" value="<?= $cmbtt['finishing_dia']; ?>"></td>
                                                                                    <td><input type="text" name="pieceWt[]" placeholder="Piece Wt" id="pieceWt<?= $xx; ?>" onkeyup="calculateReqWt(<?= $xx.$zz; ?>)" data-zid="<?= $xx.$zz; ?>" class="form-control pieceWtZ<?= $xx.$zz; ?> pieceWt<?= $xx; ?>" value="<?= $cmbtt['piece_wt']; ?>"></td>
                                                                                    <td><input type="text" name="reqPieceWt[]" placeholder="Req Wt" id="" class="form-control req_WtZ<?= $xx.$zz; ?> req_Wt<?= $xx; ?>" data-xxid="<?= $xx; ?>" readonly value="<?= $sm[] = $cmbtt['req_wt']; ?>"></td>
                                                                                </tr>
                                                                            <?php $zz++; } ?>
                                                                                <tr>
                                                                                    <td>Total</td>
                                                                                    <td><?= array_sum($oqTot[$xx]); ?></td>
                                                                                    <td><?= array_sum($exTot[$xx]); ?></td>
                                                                                    <td colspan="2" style="text-align:right">Cutting In-house Wt</td>
                                                                                    <td id="inhouseWt<?= $xx; ?>" style="text-align: center;"><?= array_sum($sm); ?></td>
                                                                                </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="yarn-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Edit Yarn Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                
                                                                <input type="hidden" id="tempYarnFabType<?= $xx; ?>">
                                                                
                                                                <br>
                                                                
                                                                <table class="table table-striped">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th>Yarn</th>
                                                                            <th class="yrn_color<?= $xx; ?>">Yarn Color</th>
                                                                            <th>Mixed %</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            $edYrn = mysqli_query($mysqli, "SELECT a.id, a.yarn_color, a.yarn_id, a.mixed, b.yarn_name FROM sales_order_fabric_components_yarn a LEFT JOIN mas_yarn b ON a.yarn_id=b.id WHERE a.fabric_program_id='". $_GET['pid'] ."'");
                                                                            while($yarnRow = mysqli_fetch_array($edYrn)) {
                                                                        ?>
                                                                            <tr id="yarnAddedtr<?= $xx.$yarnRow['id'].$xx; ?>">
                                                                                <td><?= $yarnRow['yarn_name']; ?></td>
                                                                                <td class="yrn_color<?= $xx; ?>">
                                                                                    <input type="hidden" value="<?= $yarnRow['yarn_id']; ?>" class="YarnId<?= $xx; ?>" name="YarnId[]">
                                                                                    
                                                                                    <select class="custom-select2 form-control" name="YarnColor[]" id="yarn_color<?= $xx.$yarnRow['id']; ?>" style="width:100%">
                                                                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $yarnRow['yarn_color'], '', ''); ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" name="mixedPer[]" value="<?= $yarnRow['mixed']; ?>" class="form-control mixed_perClass<?= $xx; ?>">
                                                                                </td>
                                                                                <td><a onclick="deleteYarn(<?= $xx.$yarnRow['id'].$xx; ?>)" class="border border-secondary rounded text-secondary"><i class="fa fa-trash"></i></a></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <tr id="yarn_tbody<?= $xx; ?>">
                                                                            <td style="width: 30%;">
                                                                                <select class="custom-select2 form-control" name="" id="yarn_list<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('mas_yarn', array('id', 'yarn_name'), 'yarn_name ASC', '', '', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 30%;" class="yrn_color<?= $xx; ?>">
                                                                                <select class="custom-select2 form-control" name="" id="yarn_color<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 30%;">
                                                                                <input type="number" name="" id="mixed_per<?= $xx; ?>" class="form-control mixed_perClass<?= $xx; ?>" placeholder="Max Value 100">
                                                                                <input type="hidden" name="" id="yarn_count<?= $xx; ?>" value="0" class="form-control">
                                                                            </td>
                                                                            <td style="width: 10%;"><a onclick="saveYarndet(<?= $xx; ?>)" class="border border-secondary rounded text-secondary "><i class="fa fa-plus yarnPlus<?= $xx; ?>"></i></a></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary validate_yarnTot" data-temid="<?= $xx; ?>">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="aop_modal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Enter AOP Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                
                                                                <input type="hidden" id="tempYarnFabType<?= $xx; ?>">
                                                                
                                                                <br>
                                                                
                                                                <table class="table table-bordered">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th class="fieldrequired">AOP Name</th>
                                                                            <th colspan="2">AOP File</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr id="yarn_tbody<?= $xx; ?>">
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="AOP Name" name="aop_name" id="aop_name<?= $xx; ?>" value="<?= $IVB['aop_name']; ?>" style="width: 100%;">
                                                                            </td>
                                                                            <td>
                                                                                <input type="file" class="form-control" name="aop_image" id="aop_image<?= $xx; ?>" style="width: 100%;">
                                                                                <input type="hidden" class="form-control" name="aop_Old" id="aop_Old<?= $xx; ?>" value="<?= $IVB['aop_image']; ?>" style="width: 100%;">
                                                                            </td>
                                                                            <td><?= viewImage('uploads/Fabric_planing/'. $row['aop_image'], 30); ?></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="process-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Edit Process Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                <input type="hidden" id="tempProcessFabType<?= $xx; ?>">
                                                                <br>
                                                                <table class="table table-striped">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th>Process</th>
                                                                            <th style="text-align:center;">Process Order <a onclick="reserPorder(<?= $xx; ?>)" style="color: #2da594;text-decoration: underline;" title="Reset Process Order">Reset <i class="fa fa-refresh" aria-hidden="true"></i></a></th>
                                                                            <th>Loss %</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="process_tbody<?= $xx; ?>">
                                                                        <?php
                                                                            $maxprocess_order = mysqli_fetch_array(mysqli_query($mysqli, "SELECT max(a.process_order) as maxprocess_order FROM sales_order_fabric_components_process a WHERE a.fabric_program_id='". $_GET['pid'] ."'"));
                                                                            print '<input type="hidden" id="processOrderTDFrom'. $xx .'" value="'. ($maxprocess_order['maxprocess_order']+1) .'">';
                                                                            
                                                                            $pss = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id = b.id WHERE a.fabric_program_id='". $_GET['pid'] ."' ORDER BY a.process_order ASC");
                                                                            while($npss = mysqli_fetch_array($pss)) {
                                                                        ?>
                                                                            <tr id="procesAddedtr<?= $xx.$npss['id']; ?>">
                                                                                <td style="width: 35%;">
                                                                                    <input type="checkbox" id="processOrder<?= $xx.$npss['id']; ?>" class="processOrderC<?= $xx; ?>" onclick="processOrderCK(<?= $xx; ?>,<?= $npss['id']; ?>)" <?= ($npss['process_order']!="") ? 'checked' : ''; ?>><?= $npss['process_name']; ?>
                                                                                </td>
                                                                                <td id="processOrderTD<?= $xx.$npss['id']; ?>" class="processOrderxx<?= $xx; ?>" style="text-align:center;"><?= $npss['process_order']; ?></td>
                                                                                <td style="width: 35%;">
                                                                                    
                                                                                    <input type="hidden" id="processOrderTDInp<?= $xx.$npss['id']; ?>" class="processOrderTDInpC<?= $xx; ?>" name="ProcessOrder[]" value="<?= $npss['process_order']; ?>">
                                                                                    <input type="hidden" value="<?= $npss['process_id']; ?>" name="ProcessId[]">
                                                                                    <input type="number" name="lossPer[]" class="form-control lossPer<?= $xx; ?>" placeholder="Loss %" value="<?= $npss['lossPer']; ?>">
                                                                                </td>
                                                                                <td style="width: 10%;">
                                                                                    <a onclick="deleteProcess(<?= $xx.$npss['id']; ?>)" class="border border-secondary rounded text-secondary"><i class="fa fa-trash"></i></a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        
                                                                        <tr id="process_Fixtr<?= $xx; ?>">
                                                                            <td style="width: 35%;"> 
                                                                                <select class="custom-select2 form-control" name="" id="process_list<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('process', array('id', 'process_name'), 'process_name ASC', '', 'WHERE process_type="Fabric"', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 20%;"></td>
                                                                            <td style="width: 35%;">
                                                                                <input type="number" name="" id="loss_per<?= $xx; ?>" class="form-control loss_perClass11<?= $xx; ?>" placeholder="Loss %">
                                                                                <input type="hidden" name="" id="process_count<?= $xx; ?>" value="0" class="form-control">
                                                                            </td>
                                                                            <td style="width: 10%;"><a onclick="saveProcessDet(<?= $xx; ?>)" class="border border-secondary rounded text-secondary "><i class="fa fa-plus processPlus<?= $xx; ?>"></i></a></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else  if($_GET['type']=='copy' && $_GET['tab']==$xx) { ?>
                                            
                                                <div class="modal fade bs-example-modal-lg" id="component-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Copy Component Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Component</label>
                                                                        <select class="custom-select2 form-control" name="component[]" id="component<?= $xx; ?>" style="width:100%" multiple>
                                                                            <?= select_dropdown_multiple('mas_component', array('id', 'component_name'), 'component_name ASC', ($_GET['ul_comp']=='true') ? $IVB['component'] : '', '', '1'); ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <br>
                                                                <div style="overflow-y:auto">
                                                                    <table class="table table-bordered">
                                                                        <thead style="background-color: #f7f7f7;">
                                                                            <tr>
                                                                                <th>Size</th>
                                                                                <th>Order Qty</th>
                                                                                <th>Cut PlanQty</th>
                                                                                <th>Finishing Dia <input class="form-control theadInput" placeholder="Common Dia" id="FinishingDia_main<?= $xx ?>" onkeyup="changeFinishingDia(<?= $xx; ?>)"></th>
                                                                                <th>Piece Wt <input class="form-control theadInput" placeholder="Common Dia" id="pieceWt_main<?= $xx ?>" onkeyup="changepieceWt(<?= $xx; ?>)"></th>
                                                                                <th>Req Wt</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                                $zz = 1;
                                                                                $cpy = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $sod_part['sod_combo'] ."'");
                                                                                while($copy_row = mysqli_fetch_array($cpy)) {
                                                                                // foreach(json_decode($IVB['component_detail']) as $value) {
                                                                                    // $exp = explode('--', $value);
                                                                                    // $nval = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id='". $exp[0] ."'"));
                                                                            ?>
                                                                                <tr>
                                                                                    <td><?= variation_value($copy_row['variation_value']); ?></td>
                                                                                    <td><?= $oqTot[$xx][] = $copy_row['size_qty']; ?></td>
                                                                                    <td>
                                                                                        <input type="hidden" name="sod_size[]" value="<?= $copy_row['id']; ?>">
                                                                                        <input type="hidden" class="cutplanZ<?= $xx.$zz; ?>" value="<?= $copy_row['excess_qty']; ?>">
                                                                                        <?= $exTot[$xx][] = $copy_row['excess_qty']; ?>
                                                                                    </td>
                                                                                    <td><input type="text" name="FinishingDia[]" placeholder="Finishing Dia" placeholder="Finishing Dia" id="FinishingDia<?= $xx; ?>" class="form-control FinishingDia<?= $xx; ?>" value="<?= ($_GET['ul_comp'] == 'true') ? $exp[3] : ''; ?>"></td>
                                                                                    <td><input type="text" name="pieceWt[]" placeholder="Piece Wt" id="pieceWt<?= $xx; ?>" onkeyup="calculateReqWt(<?= $xx.$zz; ?>)" data-zid="<?= $xx.$zz; ?>" class="form-control pieceWtZ<?= $xx.$zz; ?> pieceWt<?= $xx; ?>" value="<?= ($_GET['ul_comp'] == 'true') ? $exp[4] : ''; ?>"></td>
                                                                                    <td><input type="text" name="reqPieceWt[]" placeholder="Req Wt" id="" class="form-control req_WtZ<?= $xx.$zz; ?> req_Wt<?= $xx; ?>" data-xxid="<?= $xx; ?>" readonly value="<?= ($_GET['ul_comp'] == 'true') ? $exp[5] : ''; ?>"></td>
                                                                                </tr>
                                                                            <?php $zz++; } ?>
                                                                                <tr>
                                                                                    <td>Total</td>
                                                                                    <td><?= array_sum($oqTot[$xx]); ?></td>
                                                                                    <td><?= array_sum($exTot[$xx]); ?></td>
                                                                                    <td colspan="2" style="text-align:right">Cutting In-house Wt</td>
                                                                                    <td id="inhouseWt<?= $xx; ?>" style="text-align: center;">0</td>
                                                                                </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="yarn-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Copy Yarn Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                
                                                                <input type="hidden" id="tempYarnFabType<?= $xx; ?>">
                                                                
                                                                <br>
                                                                
                                                                <table class="table table-striped">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th>Yarn</th>
                                                                            <th class="yrn_color<?= $xx; ?>">Yarn Color</th>
                                                                            <th>Mixed %</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        if($_GET['ul_yarn'] == 'true') {
                                                                            $edYrn = mysqli_query($mysqli, "SELECT a.id, a.yarn_color, a.yarn_id, a.mixed, b.yarn_name FROM sales_order_fabric_components_yarn a LEFT JOIN mas_yarn b ON a.yarn_id=b.id WHERE a.fabric_program_id='". $_GET['pid'] ."'");
                                                                            while($yarnRow = mysqli_fetch_array($edYrn)) {
                                                                        ?>
                                                                            <tr id="yarnAddedtr<?= $xx.$yarnRow['id'].$xx; ?>">
                                                                                <td><?= $yarnRow['yarn_name']; ?></td>
                                                                                <td class="yrn_color<?= $xx; ?>">
                                                                                    <input type="hidden" value="<?= $yarnRow['yarn_id']; ?>" class="YarnId<?= $xx; ?>" name="YarnId[]">
                                                                                    
                                                                                    <select class="custom-select2 form-control" name="YarnColor[]" id="yarn_color<?= $xx.$yarnRow['id']; ?>" style="width:100%">
                                                                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', $yarnRow['yarn_color'], '', ''); ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" name="mixedPer[]" value="<?= $yarnRow['mixed']; ?>" class="form-control mixed_perClass<?= $xx; ?>">
                                                                                </td>
                                                                                <td><a onclick="deleteYarn(<?= $xx.$yarnRow['id'].$xx; ?>)" class="border border-secondary rounded text-secondary"><i class="fa fa-trash"></i></a></td>
                                                                            </tr>
                                                                        <?php } } ?>
                                                                        <tr id="yarn_tbody<?= $xx; ?>">
                                                                            <td style="width: 30%;">
                                                                                <select class="custom-select2 form-control" name="" id="yarn_list<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('mas_yarn', array('id', 'yarn_name'), 'yarn_name ASC', '', '', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 30%;" class="yrn_color<?= $xx; ?>">
                                                                                <select class="custom-select2 form-control" name="" id="yarn_color<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 30%;">
                                                                                <input type="number" name="" id="mixed_per<?= $xx; ?>" class="form-control mixed_perClass<?= $xx; ?>" placeholder="Max Value 100">
                                                                                <input type="hidden" name="" id="yarn_count<?= $xx; ?>" value="0" class="form-control">
                                                                            </td>
                                                                            <td style="width: 10%;"><a onclick="saveYarndet(<?= $xx; ?>)" class="border border-secondary rounded text-secondary "><i class="fa fa-plus yarnPlus<?= $xx; ?>"></i></a></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary validate_yarnTot" data-temid="<?= $xx; ?>">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="aop_modal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Copy AOP Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                
                                                                <input type="hidden" id="tempYarnFabType<?= $xx; ?>">
                                                                
                                                                <br>
                                                                
                                                                <table class="table table-bordered">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th class="fieldrequired">AOP Name</th>
                                                                            <th class="">AOP File</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr id="yarn_tbody<?= $xx; ?>">
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="AOP Name" name="aop_name" id="aop_name<?= $xx; ?>" value="<?= $IVB['aop_name']; ?>" style="width: 100%;">
                                                                            </td>
                                                                            <td>
                                                                                <input type="file" class="form-control" name="aop_image" id="aop_image<?= $xx; ?>" style="width: 100%;">
                                                                                <input type="hidden" class="form-control" name="aop_Old" id="aop_Old<?= $xx; ?>" value="<?= $IVB['aop_image']; ?>" style="width: 100%;">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="process-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Copy Process Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                <input type="hidden" id="tempProcessFabType<?= $xx; ?>">
                                                                <br>
                                                                <table class="table table-striped">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th>Process</th>
                                                                            <th style="text-align:center;">Process Order <a onclick="reserPorder(<?= $xx; ?>)" style="color: #2da594;text-decoration: underline;" title="Reset Process Order">Reset <i class="fa fa-refresh" aria-hidden="true"></i></a></th>
                                                                            <th>Loss %</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="process_tbody<?= $xx; ?>">
                                                                        <?php
                                                                        if($_GET['ul_process'] == 'true') {
                                                                            $maxprocess_order = mysqli_fetch_array(mysqli_query($mysqli, "SELECT max(a.process_order) as maxprocess_order FROM sales_order_fabric_components_process a WHERE a.fabric_program_id='". $_GET['pid'] ."'"));
                                                                            print '<input type="hidden" id="processOrderTDFrom'. $xx .'" value="'. ($maxprocess_order['maxprocess_order']+1) .'">';
                                                                            
                                                                            $pss = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id = b.id WHERE a.fabric_program_id='". $_GET['pid'] ."' ORDER BY a.process_order ASC");
                                                                            while($npss = mysqli_fetch_array($pss)) {
                                                                        ?>
                                                                            <tr id="procesAddedtr<?= $xx.$npss['id']; ?>">
                                                                                <td style="width: 35%;">
                                                                                    <input type="checkbox" id="processOrder<?= $xx.$npss['id']; ?>" class="processOrderC<?= $xx; ?>" onclick="processOrderCK(<?= $xx; ?>,<?= $npss['id']; ?>)" <?= ($npss['process_order']!="") ? 'checked' : ''; ?>><?= $npss['process_name']; ?>
                                                                                </td>
                                                                                <td id="processOrderTD<?= $xx.$npss['id']; ?>" class="processOrderxx<?= $xx; ?>" style="text-align:center;"><?= $npss['process_order']; ?></td>
                                                                                <td style="width: 35%;">
                                                                                    
                                                                                    <input type="hidden" id="processOrderTDInp<?= $xx.$npss['id']; ?>" class="processOrderTDInpC<?= $xx; ?>" name="ProcessOrder[]" value="<?= $npss['process_order']; ?>">
                                                                                    <input type="hidden" value="<?= $npss['process_id']; ?>" name="ProcessId[]">
                                                                                    <input type="number" name="lossPer[]" class="form-control lossPer<?= $xx; ?>" placeholder="Loss %" value="<?= $npss['lossPer']; ?>">
                                                                                </td>
                                                                                <td style="width: 10%;">
                                                                                    <a onclick="deleteProcess(<?= $xx.$npss['id']; ?>)" class="border border-secondary rounded text-secondary"><i class="fa fa-trash"></i></a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } } ?>
                                                                        
                                                                        <tr id="process_Fixtr<?= $xx; ?>">
                                                                            <td style="width: 35%;"> 
                                                                                <select class="custom-select2 form-control" name="" id="process_list<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('process', array('id', 'process_name'), 'process_name ASC', '', 'WHERE process_type="Fabric"', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 20%;"></td>
                                                                            <td style="width: 35%;">
                                                                                <input type="number" name="" id="loss_per<?= $xx; ?>" class="form-control loss_perClass11<?= $xx; ?>" placeholder="Loss %">
                                                                                <input type="hidden" name="" id="process_count<?= $xx; ?>" value="0" class="form-control">
                                                                            </td>
                                                                            <td style="width: 10%;"><a onclick="saveProcessDet(<?= $xx; ?>)" class="border border-secondary rounded text-secondary "><i class="fa fa-plus processPlus<?= $xx; ?>"></i></a></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="component-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Enter Component Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Component</label>
                                                                        <select class="custom-select2 form-control" name="component[]" id="component<?= $xx; ?>" style="width:100%" multiple>
                                                                            <?= select_dropdown('mas_component', array('id', 'component_name'), 'component_name ASC', '', '', '1'); ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <br>
                                                                <div style="overflow-y:auto">
                                                                    <table class="table table-bordered">
                                                                        <thead style="background-color: #f7f7f7;">
                                                                            <tr>
                                                                                <th>Size</th>
                                                                                <th>Order Qty</th>
                                                                                <th>Cut PlanQty</th>
                                                                                <th>Finishing Dia <input class="number_input form-control theadInput" placeholder="Common Dia" id="FinishingDia_main<?= $xx ?>" onkeyup="changeFinishingDia(<?= $xx; ?>)"></th>
                                                                                <th>Piece Wt <input class="number_input form-control theadInput" placeholder="Common Dia" id="pieceWt_main<?= $xx ?>" onkeyup="changepieceWt(<?= $xx; ?>)"></th>
                                                                                <th>Req Wt</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                                $zz = 1;
                                                                                
                                                                                $sizuu = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $sod_part['sod_combo'] ."'");
                                                                                while($sod_size = mysqli_fetch_array($sizuu)) {
                                                                            ?>
                                                                                    <tr>
                                                                                        <td><?= variation_value($sod_size['variation_value']); ?></td>
                                                                                        <td><?= $oqTot[$xx][] = $sod_size['size_qty']; ?></td>
                                                                                        <td>
                                                                                            <input type="hidden" name="sod_size[]" value="<?= $sod_size['id']; ?>">
                                                                                            <input type="hidden" class="cutplanZ<?= $xx.$zz; ?>" value="<?= $sod_size['excess_qty']; ?>">
                                                                                            <?= $exTot[$xx][] = $sod_size['excess_qty']; ?>
                                                                                        </td>
                                                                                        <td><input type="text" name="FinishingDia[]" placeholder="Finishing Dia" placeholder="Finishing Dia" id="FinishingDia<?= $xx; ?>" class="number_input form-control FinishingDia<?= $xx; ?>"></td>
                                                                                        <td><input type="text" name="pieceWt[]" placeholder="Piece Wt" id="pieceWt<?= $xx; ?>" onkeyup="calculateReqWt(<?= $xx.$zz; ?>)" data-zid="<?= $xx.$zz; ?>" class="number_input form-control pieceWtZ<?= $xx.$zz; ?> pieceWt<?= $xx; ?>"></td>
                                                                                        <td><input type="text" name="reqPieceWt[]" placeholder="Req Wt" id="" class="form-control req_WtZ<?= $xx.$zz; ?> req_Wt<?= $xx; ?>" data-xxid="<?= $xx; ?>" readonly></td>
                                                                                    </tr>
                                                                            <?php $zz++; } ?>
                                                                                    <tr>
                                                                                        <td>Total</td>
                                                                                        <td><?= array_sum($oqTot[$xx]); ?></td>
                                                                                        <td><?= array_sum($exTot[$xx]); ?></td>
                                                                                        <td colspan="2" style="text-align:right">Cutting In-house Wt</td>
                                                                                        <td id="inhouseWt<?= $xx; ?>" style="text-align: center;">0</td>
                                                                                    </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="yarn-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Enter Yarn Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                
                                                                <input type="hidden" id="tempYarnFabType<?= $xx; ?>">
                                                                
                                                                <br>
                                                                
                                                                <table class="table table-striped">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th>Yarn</th>
                                                                            <th class="yrn_color<?= $xx; ?>">Yarn Color</th>
                                                                            <th>Mixed %</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr id="yarn_tbody<?= $xx; ?>">
                                                                            <td style="width: 30%;">
                                                                                <select class="custom-select2 form-control" name="YarnId[]" id="yarn_list<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('mas_yarn', array('id', 'yarn_name'), 'yarn_name ASC', '', '', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 30%;" class="yrn_color<?= $xx; ?>">
                                                                                <select class="custom-select2 form-control" name="YarnColor[]" id="yarn_color<?= $xx; ?>" style="width:100%">
                                                                                    <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', ''); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td style="width: 30%;">
                                                                                <input type="number" name="mixedPer[]" id="mixed_per<?= $xx; ?>" class="form-control mixed_perClass<?= $xx; ?>" placeholder="Max Value 100">
                                                                                <input type="hidden" name="" id="yarn_count<?= $xx; ?>" value="0" class="form-control">
                                                                            </td>
                                                                            <td style="width: 10%;"><a onclick="saveYarndet(<?= $xx; ?>)" class="border border-secondary rounded text-secondary "><i class="fa fa-plus yarnPlus<?= $xx; ?>"></i></a></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary validate_yarnTot" data-temid="<?= $xx; ?>">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="aop_modal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Enter AOP Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                
                                                                <input type="hidden" id="tempYarnFabType<?= $xx; ?>">
                                                                
                                                                <br>
                                                                
                                                                <table class="table table-bordered">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th class="fieldrequired">AOP Name</th>
                                                                            <th class="">AOP File</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr id="yarn_tbody<?= $xx; ?>">
                                                                            <td>
                                                                                <input type="text" class="form-control" placeholder="AOP Name" name="aop_name" id="aop_name<?= $xx; ?>" style="width: 100%;">
                                                                            </td>
                                                                            <td>
                                                                                <input type="file" class="form-control" name="aop_image" id="aop_image<?= $xx; ?>" style="width: 100%;">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="modal fade bs-example-modal-lg" id="process-addModal<?= $xx; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Enter Process Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body" style="overflow-y:auto">
                                                                <input type="hidden" id="tempProcessFabType<?= $xx; ?>">
                                                                <input type="hidden" id="processOrderTDFrom<?= $xx; ?>" value="1">
                                                                <br>
                                                                <table class="table table-striped">
                                                                    <thead style="background-color: #f7f7f7;">
                                                                        <tr>
                                                                            <th>Process</th>
                                                                            <th style="text-align:center;">Process Order <a onclick="reserPorder(<?= $xx; ?>)" style="color: #2da594;text-decoration: underline;">Reset <i class="fa fa-refresh" aria-hidden="true"></i></a></th>
                                                                            <th>Loss %</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="process_tbody<?= $xx; ?>">
                                                                        
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                <?php $xx++; } ?>
                                <?php if(array_sum($row_sum)>0 && $sql['edit_fab_requirement'] == 'yes') { ?>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <br>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <a class="btn btn-outline-primary gbtn" onclick="GenerateFabricPrint(<?= $ID; ?>)">Generate Program Print</a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade bs-example-modal-lg" id="AddedComponentDetails" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="myLargeModalLabel">Component Details</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<div style="overflow-y:auto">
								<table class="table table-bordered">
                                    <thead style="background-color: #f7f7f7;">
                                        <tr>
                                            <th>Size</th>
                                            <th>Order Qty</th>
                                            <th>Cut PlanQty</th>
                                            <th>Finishing Dia</th>
                                            <th>Piece Wt</th>
                                            <th>Req Wt</th>
                                        </tr>
                                    </thead>
                                    <tbody id="AddedComponentDetails_tbody">
                                        <tr><td colspan="6" style="text-align: center;">Loading <i class="icon-copy fa fa-spinner" aria-hidden="true"></i></td></tr>
                                    </tbody>
                                </table>
                            </div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
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
        
        function copyPart(tab, to_part, style) {
            
            var from_part = $("#from_part" + tab ).val();
            
            var to_part = $("#sod_part" + tab ).val();
            
            if(from_part == ""){
                message_noload('error', 'Select Part To Copy!');
                return false;
            } else {
                
                var data = 'to_part=' + to_part + '&style=' + style + '&from_part=' + from_part;
                
                swal({
                    title: 'Are you sure?',
                    text: "You Want to Copy this Component?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Copy this!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success margin-5',
                    cancelButtonClass: 'btn btn-danger margin-5',
                    buttonsStyling: false
                }).then(function (dd) {
                    if (dd['value'] == true) {
                        
                        $(".c-Btn" + tab).html('<i class="fa fa-spinner"></i> Copying..').addClass('pe-none');
                        $("#overlay").fadeIn(100);
                        $.ajax({
                            type : 'POST',
                            url : 'ajax_action2.php?copyPart',
                            data: data,
                            
                            success : function(msg) {
                                var json = $.parseJSON(msg);
                                    
                                if(json.notFound==1) {
                                    $(".c-Btn" + tab).html('Copy Part').removeClass('pe-none');
                                    message_noload('info', 'No Rows found in this Part!');
                                } else {
                                    if(json.result == 0) {
                                        // setTimeout(function() {
                                            $("#overlay").fadeOut(500);
                                            message_reload('success', 'Program Copied!', 1500);
                                        // }, 1500);
                                    } else {
                                        $(".c-Btn" + tab).html('Copy Part');
                                        message_noload('error', 'Something went wrong!');
                                        $("#overlay").fadeOut(500);
                                    }
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
        
        $(".row_copy").click(function() {
            var bx = $(".row_copy").is(':checked');
            (bx==true) ? $("#copyId").val($(this).val()) : $("#copyId").val('');
            $('.row_copy').not(this).prop('checked', false);
            (bx==true) ? $(".copyBtn").removeClass('d-none') : $(".copyBtn").addClass('d-none');
        });
        
        
        function copyBtn(id, tab) {
            
            var copyId = $("#copyId").val();
            
            if(copyId=="") {
                message_noload('error', 'Row Not Selected!');
                return false;
            } else {
                
                var ul_process = $("#ul_process" + tab).is(':checked');
                var ul_yarn = $("#ul_yarn" + tab).is(':checked');
                var ul_comp = $("#ul_comp" + tab).is(':checked');
                var ul_gsm = $("#ul_gsm" + tab).is(':checked');
                var ul_fabric = $("#ul_fabric" + tab).is(':checked');
                var ul_fabTyp = $("#ul_fabTyp" + tab).is(':checked');
                var ul_dyClr = $("#ul_dyClr" + tab).is(':checked');
                var ul_aop = $("#ul_aop" + tab).is(':checked');
                
                var url = '&ul_process=' + ul_process + '&ul_yarn=' + ul_yarn + '&ul_comp=' + ul_comp + '&ul_gsm=' + ul_gsm + '&ul_fabric=' + ul_fabric + '&ul_fabTyp=' + ul_fabTyp + '&ul_dyClr=' + ul_dyClr + '&ul_aop=' + ul_aop;
                
                window.location.href="add-fabProgram.php?id=" + id + "&tab=" + tab + "&type=copy&pid=" + copyId + url;
            }
            
        };
        
    </script>
    
    <script>
        function reserPorder(id) {
            
            $(".processOrderxx"+ id).text('-');
            $(".processOrderTDInpC"+ id).val('');
            $(".processOrderC"+ id).prop('checked', false);
            
            $("#processOrderTDFrom" + id).val(1);
        }
    </script>
    
    <script>
        function processOrderCK(id,sid) {
            
            var a = $("#processOrder" + id + sid).is(':checked');
            
            var val = $("#processOrderTDFrom" + id).val();
            
            if(a==true) {
                $("#processOrderTD"+ id + sid).text(val);
                $("#processOrderTDInp"+ id + sid).val(val);
                
                $("#processOrderTDFrom" + id).val((parseInt(val)+1));
            } else {
                $("#processOrderTD"+ id + sid).text('-');
                $("#processOrderTDInp"+ id + sid).val('');
            }
        }
    </script>
                                                                                            
    <script>
        function fabric_typeChange(id) {
            var val = $("#fabric_type" + id).val();
            
            if(val=='FAB_YANDD') {
                $(".dyingclr" + id).hide();
                $(".gsmDiv" + id).removeClass('col-md-1').addClass('col-md-2');
            } else {
                $(".dyingclr" + id).show();
                $(".gsmDiv" + id).removeClass('col-md-2').addClass('col-md-1');
            }
        }
    </script>
    
    <script>
        function EditFabProgram(id, tab, pid) {
            $("#overlay").fadeIn(300);
            window.location.href="add-fabProgram.php?id="+ id +"&tab="+ tab +"&type=edit&pid="+ pid +"";
        }
    </script>
    
    <script>
        function shoeYarnAddModal(id) {
            var fabric_type = $("#fabric_type" + id).val();
            
            if(fabric_type=='FAB_YANDD') {
                $(".yrn_color" + id).removeClass('d-none');
            } else {
                $(".yrn_color" + id).addClass('d-none');
            }
            
            var a = $("#tempYarnFabType" + id).val();
            
            if(a!=fabric_type) {
                $(".yarnAddedtr" + id).remove();
            }
            
            $("#yarn-addModal" + id).modal({
                backdrop: 'static',
                keyboard: false
            })
            
            $("#yarn_list"+ id ).each(function() {
                $(this).select2({
                    dropdownParent: $('#yarn-addModal' + id)
                });
            });
            
            $("#yarn-addModal" + id).modal('show');
            $("#tempYarnFabType" + id).val(fabric_type);
        }
    </script>
    
    <script>
        function GenerateFabricPrint(id) {
            
            $(".gbtn").addClass('pe-none').html('<i class="fa fa-spinner"></i> Generating..');
            $("#overlay").fadeIn(100);

            var data = {
                id: id,
            }
            
            $.ajax({
                type:'POST',
                url:'ajax_action.php?GenerateFabricPrint',
                data: data,
                
                success: function(msg) {
                    var json = $.parseJSON(msg);
                    $("#overlay").fadeOut(100);
                    if(json.result==0) {
                        message_noload('success', 'Program Saved!', 1500);
                        
                        setTimeout(function() {
                            $(".gbtn").removeClass('pe-none').html('Generate Program Print');
                            window.open('fabProgram_print.php?id='+ id, '_blank');
                        }, 1500);
                    } else {
                            $(".gbtn").removeClass('pe-none').html('Generate Program Print');
                            message_noload('error', 'Something Went Wrong!', 1500);
                    }
                }
            })
        }
        
    </script>
    
    <script>
        function AddedComponentDetails(id) {
            
            $.ajax({
                type:'POST',
                url:'ajax_search.php?GetAddedcomponent_det=1&id=' + id,
                success: function(msg) {
                    var json = $.parseJSON(msg);
                    setTimeout(function () {
                        $("#AddedComponentDetails_tbody").html(json.tbody);
                    }, 500)
                }
            })
            
            $("#AddedComponentDetails").modal('show');
        }
        
        $('#AddedComponentDetails').on('hidden.bs.modal', function (e) {
            $("#AddedComponentDetails_tbody").html('<tr><td colspan="6" style="text-align: center;">Loading <i class="icon-copy fa fa-spinner" aria-hidden="true"></i></td></tr>');
        })
    </script> 
    
    <script>
        function OpenAOPmodal(id) {
            var op = $("#aop_" + id).val();
            
            if(op=='yes') {
                $(".aop_eye").removeClass('d-none');
                $("#aop_name" + id).focus();
                $("#aop_modal" + id).modal('show');
            } else {
                $(".aop_eye").addClass('d-none');
            }
        }
    </script>
    
    <script>
        function SaveProgram(id, typ){
            
            var f = validate_yarnTot(id);
            if(f==true) {
                if($("#aop_"+ id).val()=="yes")
                {
                    if($("#aop_name"+ id).val()=="") {
                        $("#aop_modal" + id).modal('show');
                        message_noload('warning', 'AOP Name Required!', 1500);
                        return false;
                    } 
                    // else if($("#aop_image_old"+ id).val()=="" || $("#aop_image"+ id).val()=="") {
                    //     message_noload('warning', 'AOP Image Required!', 1500);
                    //     return false;
                    // }
                } else if($("#fabric_type"+ id).val()=="")
                {
                    message_noload('warning', 'Fabric Type Required!', 1500);
                    return false;
                } else if($("#fabric"+ id).val()=="")
                {
                    message_noload('warning', 'Fabric Required!', 1500);
                    return false;
                } else if($("#gsm_"+ id).val()=="")
                {
                    message_noload('warning', 'GSM Required!', 1500);
                    return false;
                }else if($("#component"+ id).val()=="")
                {
                        $("#component-addModal" + id).modal('show');
                    message_noload('warning', 'Select Component!', 1500);
                    return false;
                } else if($("#fabric_type"+ id).val()!="FAB_YANDD")
                {
                    if($("#dyeing_color"+ id).val()=="") {
                        message_noload('warning', 'Dyeing Color Required!', 1500);
                        return false;
                    }
                }
                
                var s = 0;
                
                $(".processOrderC" + id).each(function() {
                    
                    var ch = $(this).is(':checked');
                    if(ch==false) {
                        s++;
                        message_noload('error', 'Process Order Missing!');
                        return false;
                    }
                });
                
                
                $(".FinishingDia" + id).each(function() {
                    var n = $(this).val();
                    
                    if(n=="") {
                        s++;
                        $("#component-addModal" + id).modal('show');
                        message_noload('warning', 'Some Finishing Dia Value Missing!', 2000);
                        return false;
                    }
                });
                
                if(s==0) {
                    $(".pieceWt" + id).each(function() {
                        var n = $(this).val();
                        
                        if(n=="") {
                            s++;
                            $("#component-addModal" + id).modal('show');
                            message_noload('warning', 'Some Piece Wt Value Missing!', 2000);
                            return false;
                        }
                    });
                }
                
                if(s==0) {
                    if($(".YarnId" + id).length==0) {
                            $("#yarn-addModal" + id).modal('show');
                        message_noload('warning', 'Enter Yarn Details!', 2000);
                        return false;
                    }
                }
                
                if(s==0) {
                    if($(".lossPer" + id).length==0) {
                            // $("#process-addModal" + id).modal('show');
                        message_noload('warning', 'Enter Process Details!', 2000);
                        return false;
                    }
                }
                
                // $(".lossPer" + id).each(function() {
                //     var n = $(this).val();
                    
                //     if(n=="") {
                //         s++;
                //         message_noload('warning', 'Some Process Loss Percentage Missing in Process Modal!', 2000);
                //         return false;
                //     }
                // });
                
                if(s==0) {
                    
                    if(typ=='add') {
                        $("#overlay").fadeIn(300);
                        var form = $("#programForm" + id).submit();
                    } else if(typ == 'edit') {
                        swal({
                            title: 'Are you sure?',
                            text: "Confirm Update the fabric?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Update!',
                            cancelButtonText: 'No, cancel!',
                            confirmButtonClass: 'btn btn-success margin-5',
                            cancelButtonClass: 'btn btn-danger margin-5',
                            buttonsStyling: false
                        }).then(function (dd) {
                            if (dd['value'] == true) {
                                $("#overlay").fadeIn(300);
                                var form = $("#programForm" + id).submit();
                            } else {
                                swal(
                                    'Update Cancelled',
                                    '',
                                    'error'
                                )
                            }
                        })
                    }
                }
            }
        }
    </script>
    
    <script>
        function calculateReqWt(zzid) {
            var aa = $(".cutplanZ" + zzid).val();
            var bb = $(".pieceWtZ" + zzid).val();
            
            var cc = (parseFloat(aa)*parseFloat(bb))/1000;
            
            $(".req_WtZ" + zzid).val(cc);
            
            var nid = $(".req_WtZ" + zzid).attr('data-xxid');
            
            var abc = 0;
            
            $(".req_Wt" + nid).each(function() {
                abc += parseFloat($(this).val());
            })
            
            $("#inhouseWt" + nid).text(abc);
        }
    </script>
    
    <script>
        function changeFinishingDia(id) {
            
            var val = $("#FinishingDia_main" + id).val();
            
            $(".FinishingDia"+ id).each(function() {
                
                $(this).val(val);
            })
        }
    </script>
    
    <script>
        function changepieceWt(id) {
            
            var val = $("#pieceWt_main" + id).val();
            
            $(".pieceWt"+ id).each(function() {
                $(this).val(val);
                calculateReqWt($(this).attr('data-zid'));
            })
        }
    </script>
    
    <script>
        function saveProcessDet(id) {
            
            $(".processPlus"+ id).removeClass('fa-plus').addClass('fa-spinner');
            var process_count = $("#process_count" + id).val();
            
            var process_list = $("#process_list" + id).val();
            var loss_per = $("#loss_per" + id).val();
            
            if(process_list=="") {
                message_noload('warning', 'Select Proces!', '1500');
                $(".processPlus"+ id).addClass('fa-plus').removeClass('fa-spinner');
            }
            // else if(loss_per==0 || loss_per=="") {
            //     message_noload('warning', 'Enter Loss Percentage!', '1500');
            //     $(".processPlus"+ id).addClass('fa-plus').removeClass('fa-spinner');
            // }
            else {
            
                $.ajax({
                    type : 'POST',
                    url : 'ajax_search.php?getProces_name= ' + process_list,
                    success : function(msg) {
                        var json = $.parseJSON(msg);
                        
                        var html = '<tr id="procesAddedtr'+ id + process_count +'"><td><input type="checkbox" id="processOrder'+ id + process_list +'7" class="processOrderC'+ id +'" onclick="processOrderCK('+ id +','+ process_list +'7)">'+ json.process_name +'</td><td id="processOrderTD'+ id + process_list +'7" class="processOrderxx'+ id +'"  style="text-align:center;">-</td><td><input type="hidden" id="processOrderTDInp'+ id + process_list +'7" class="processOrderTDInpC'+ id +'" value="" name="ProcessOrder[]">  <input type="hidden" value="'+ process_list +'" name="ProcessId[]">  <input type="number" placeholder="Loss %" class="form-control lossPer'+ id +'" name="lossPer[]" value="'+ loss_per +'" class="form-control mixed_perClass'+ id +'"></td><td><a onclick="deleteProcess('+ id + process_count +')" class="border border-secondary rounded text-secondary"><i class="fa fa-trash"></i></a></td></tr>';
                        
                        $("#process_count" + id).val((parseInt(process_count)+1));
                        $("#loss_per" + id).val('');
                        $("#process_list" + id).val('').trigger('change');
                        $("#process_Fixtr" + id).before(html);
                        $(".processPlus"+ id).addClass('fa-plus').removeClass('fa-spinner');
                    }
                })
            }
        }
    </script>
    
    <script>
        function saveYarndet(id) {
            
            var aa = 0;
            $(".mixed_perClass"+ id).each(function() {
                
                aa += parseInt($(this).val());
            })
            
            if(aa>100) {
                $("#mixed_per" + id).select();
                message_noload('warning', 'Percentage Exceded!', '1500');
                return false;
            }
            
            
            $(".yarnPlus"+ id).removeClass('fa-plus').addClass('fa-spinner');
            var yarn_count = $("#yarn_count" + id).val();
            
            var yarn_list = $("#yarn_list" + id).val();
            var yarn_color = $("#yarn_color" + id).val();
            var mixed_per = $("#mixed_per" + id).val();
            
            var fabric_type = $("#fabric_type" + id).val();
            
            if(yarn_list=="") {
                message_noload('warning', 'Select Yarn!', '1500');
                $(".yarnPlus"+ id).addClass('fa-plus').removeClass('fa-spinner');
                return false;
            } else if(mixed_per==0 || mixed_per=="") {
                message_noload('warning', 'Enter Percentage!', '1500');
                $(".yarnPlus"+ id).addClass('fa-plus').removeClass('fa-spinner');
                return false;
            } else {
                
                if(fabric_type == "FAB_YANDD") {
                    if(yarn_color=="") {
                        message_noload('warning', 'Select Yarn Color!', '1500');
                        $(".yarnPlus"+ id).addClass('fa-plus').removeClass('fa-spinner');
                        return false;
                    }
                    var dNone = '';
                } else {
                    var dNone = 'd-none';
                }
            
                $.ajax({
                    type : 'POST',
                    url : 'ajax_search.php?getYarn_name= ' + yarn_list + '&colorName=' + yarn_color,
                    success : function(msg) {
                        var json = $.parseJSON(msg);
                        
                        var html = '<tr id="yarnAddedtr'+ id + yarn_count +'" class="yarnAddedtr'+ id +'"><td>'+ json.yarn_name +'</td><td class="'+ dNone +'">'+ json.color_name +'</td><td><input type="hidden" value="'+ yarn_list +'" class="YarnId'+ id +'" name="YarnId[]">  <input type="hidden" value="'+ yarn_color +'" name="YarnColor[]">  <input type="hidden" name="mixedPer[]" value="'+ mixed_per +'" class="mixed_perClass'+ id +'">'+ mixed_per +'</td><td><a onclick="deleteYarn('+ id + yarn_count +')" class="border border-secondary rounded text-secondary"><i class="fa fa-trash"></i></a></td></tr>';
                        
                        $("#yarn_count" + id).val((yarn_count+1));
                        $("#mixed_per" + id).val('');
                        $("#yarn_color" + id).val('').trigger('change');
                        $("#yarn_list" + id).val('').trigger('change');
                        $("#yarn_tbody" + id).before(html);
                        $(".yarnPlus"+ id).addClass('fa-plus').removeClass('fa-spinner');
                    }
                })
            }
        }
    </script>
    
    <script>
        function deleteYarn(trid) {
            
            $("#yarnAddedtr"+ trid).remove();
        }
        
        function deleteProcess(trid) {
            
            $("#procesAddedtr"+ trid).remove();
        }
    </script>
    
    <script>
        function OpenProcessModal(id) {
            
            alert(id);
            var ab = $("#fabric_type" + id).val();
            
            if(ab=="") {
                message_noload('warning', 'Select Fabric Type!', 1500);
                return false;
            }
            
            if($("#tempProcessFabType" + id).val()!=ab) {
            
                
                $.ajax({
                    type:'POST',
                    url:'ajax_search.php?getDefaultProcess=1&ref=' + ab + '&temp_id=' + id,
                    
                    success : function(msg) {
                        var json = $.parseJSON(msg);
                        
                        $("#process_tbody" +id).html(json.trVal);
                        
                        $(".custom-select2").each(function() {
                            $(this).select2();
                        });
                    }
                })
            }
            
            $("#tempProcessFabType" + id).val(ab);
            
            // $("#process_list" + id).each(function() {
            //     $(this).select2({
            //         dropdownParent: $('#process-addModal' + id)
            //     });
            // });
            
            
            $("#process-addModal" + id).modal('show');
        }
    </script>
    
    <script>
        $(".validate_yarnTot").click(function() {
            
            var temId = $(this).data('temid');
            
            validate_yarnTot(temId);
            
        })
        
        function validate_yarnTot(temId) {
            
            var yarn_list = $("#yarn_list" + temId).val();
            var yarn_color = $("#yarn_color" + temId).val();
            var mixed_per = $("#mixed_per" + temId).val();
            
            var fabric_type = $("#fabric_type" + temId).val();
            
            var tot = 0;
            $(".mixed_perClass" + temId).each(function() {
                var mx = $(this).val() ? $(this).val() : 0;
                tot += parseFloat(mx);
            });
            
            if(tot!=100) {
                message_noload('error', 'Enter correct yarn mixing percentage!');
                return false;

                $("#yarn-addModal" + temId).modal('show');
            } else if($("#mixed_per" + temId).val() != "" ) {
                
                if(yarn_list=="") {
                    message_noload('warning', 'Select Yarn!', '1500');
                    $(".yarnPlus"+ temId).addClass('fa-plus').removeClass('fa-spinner');
                    return false;
                } else if(mixed_per==0 || mixed_per=="") {
                    message_noload('warning', 'Enter Percentage!', '1500');
                    $(".yarnPlus"+ temId).addClass('fa-plus').removeClass('fa-spinner');
                    return false;
                } else if(fabric_type == "FAB_YANDD") {
                    if(yarn_color=="") {
                        message_noload('warning', 'Select Yarn Color!', '1500');
                        $(".yarnPlus"+ temId).addClass('fa-plus').removeClass('fa-spinner');
                        return false;
                    }
                } else {
                    $("#yarn-addModal" + temId).modal('hide');
                    return true;
                }
            } else {
                $("#yarn-addModal" + temId).modal('hide');
                return true;
            }
        }
    </script>
    
    <script>
        $(document).ready(function() {
          $(window).keydown(function(event){
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
          });
        });
    </script>
    
    <script>
        function deleteFabProgram(id) {
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
                        url: 'ajax_action.php?delete_FabricProgram=1&id=' + id,
                        success: function (msg) {
                            
                            if (msg == 0) {
                                swal({ type: 'success', title: 'Deleted', showConfirmButton: true, timer: 1500 }).then( function () { $("#addedFabTr" + id).remove(); })
                            } else {
                                swal(
                                    'Something went wrong',
                                    '',
                                    'error'
                                )
                            }
                        }
                    })
                } else {
                    swal( 'Cancelled', '', 'error')
                }
            })
        }
    </script>
    
    
    <script>
        function show_copyBtn(tab) {
            
            var val = $("#from_part" + tab).val();
            
            if(val=="") {
                $(".c-Btn" + tab).addClass('d-none');
            } else {
                $(".c-Btn" + tab).removeClass('d-none');
            }
        }
    </script>
    
    
    
<?php if(isset($_GET['type'])) { ?>
<script>
    var tab = <?= $_GET['tab']; ?>;
    
    $(document).ready(function(){
        fabric_typeChange(tab);
    })
    
</script>
    
<?php } ?>

</html>