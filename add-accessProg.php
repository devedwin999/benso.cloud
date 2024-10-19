<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$style_id = $_GET['id'];

if (isset($_REQUEST['saveForm'])) {
    // echo "<pre>", print_r($_POST, 1); die;
    
    $soId = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sales_order_id FROM sales_order_detalis WHERE id=".$style_id));
    
    // for($iop=0; $iop<count($_REQUEST['variation_det']); $iop++) {
    //     $component_detail[] = $_REQUEST['variation_det'][$iop].'--'.$_REQUEST['FinishingDia'][$iop].'--'.$_REQUEST['pieceWt'][$iop].'--'.$_REQUEST['reqPieceWt'][$iop];
    // }
    
    $sData = array(
        'sales_order_id' => $soId['sales_order_id'],
        'sales_order_detalis_id' => $style_id,
        'acc_type' => $_REQUEST['accType'],
        'accessories' => $_REQUEST['accessoriesM'],
        'acc_ref' => $_REQUEST['acc_ref'],
        'part' => implode(',', $_REQUEST['part_']),
        'size_wise' => $_REQUEST['size_wise'],
        'color_wise' => $_REQUEST['color_wise'],
        'excess' => $_REQUEST['excessP'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );
    
    if($_GET['type']=='edit')
    {
        $ins = Update('sales_order_accessories_program', $sData, " WHERE id = '" . $_GET['pid'] . "'");
        
        Delete('sales_order_accessories_det', 'WHERE program_id='. $_GET['pid']);
        
        $programId = $_GET['pid'];
        
        timeline_history('Update', 'sales_order_accessories_program', $programId, 'Accessories Program Updated with details');
    } else {
        $ins = Insert('sales_order_accessories_program', $sData);
        $programId = mysqli_insert_id($mysqli);
        
        timeline_history('Insert', 'sales_order_accessories_program', $programId, 'Accessories Program Added with details');
    }
    
    $size_wise = $_REQUEST['size_wise'];
    $color_wise = $_REQUEST['color_wise'];
    
    for($i=0; $i<count($_REQUEST['det_req']); $i++) {
        if($size_wise == 'no' && $color_wise == 'no') {
            $sarr = array(
                'program_id' => $programId,
                'det_req' => $_REQUEST['det_req'][$i],
                'det_pcs' => $_REQUEST['det_pcs'][$i],
            );
        } else if($size_wise == 'yes' && $color_wise == 'no') {
            $sarr = array(
                'program_id' => $programId,
                'sod_size' => $_REQUEST['sod_size'][$i],
                'det_req' => $_REQUEST['det_req'][$i],
                'det_pcs' => $_REQUEST['det_pcs'][$i],
                'variation_value' => $_REQUEST['sizee'][$i],
            );
        } else if($size_wise == 'no' && $color_wise == 'yes') {
            $sarr = array(
                'program_id' => $programId,
                'sod_size' => $_REQUEST['sod_size'][$i],
                'det_req' => $_REQUEST['det_req'][$i],
                'det_pcs' => $_REQUEST['det_pcs'][$i],
                'color' => $_REQUEST['colorr'][$i],
            );
        } else if($size_wise == 'yes' && $color_wise == 'yes') {
            $sarr = array(
                'program_id' => $programId,
                'sod_size' => $_REQUEST['sod_size'][$i],
                'det_req' => $_REQUEST['det_req'][$i],
                'det_pcs' => $_REQUEST['det_pcs'][$i],
                'variation_value' => $_REQUEST['sizee'][$i],
                'color' => $_REQUEST['mat_color'][$i],
            );
        }
        
        Insert('sales_order_accessories_det', $sarr);
    }
    
    if($_GET['type']=='edit')
    {
        $_SESSION['msg'] = "updated";
    } else {
        $_SESSION['msg'] = "saved";
    }

    header("Location:add-accessProg.php?id=".$style_id);


    exit;
}

if (isset($_GET["id"])) {
    $id = $style_id;
} else {
    $id = '';
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Add Accessories Program</title>

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
    </style>
    
    
    <style>
        #button{
          display:block;
          margin:20px auto;
          padding:10px 30px;
          background-color:#eee;
          border:solid #ccc 1px;
          cursor: pointer;
        }
        #overlay{	
          position: fixed;
          top: 0;
          z-index: 100;
          width: 100%;
          height:100%;
          display: none;
          background: rgba(0,0,0,0.6);
        }
        .cv-spinner {
          height: 100%;
          display: flex;
          justify-content: center;
          align-items: center;  
        }
        .spinner {
          width: 40px;
          height: 40px;
          border: 4px #ddd solid;
          border-top: 4px #2e93e6 solid;
          border-radius: 50%;
          animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
          100% { 
            transform: rotate(360deg); 
          }
        }
        .is-hide{
          display:none;
        }
    </style>


    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Accessories Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Accessories Updated.
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
                    <?php if(FABRIC_PROG_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary showmodal" href="accessProg.php" style="float: right;">Accessories List</a>
                        <h4 class="text-blue h4">Accessories Program</h4>
                    </div>
                    <div class="pb-20">

                        <div class="accordion" id="accordionExample" style="padding: 25px;">
                            <?php
                            $qry = "SELECT a.*, b.order_code FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id=b.id WHERE a.id='" . $id . "' ORDER BY a.id ASC";
                            $query = mysqli_query($mysqli, $qry);
                            $x = 1;
                            $sql = mysqli_fetch_array($query);
                                ?>
                                <div class="card">
                                    <div class="card-header" id="heading<?= $x; ?>">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $x; ?>" aria-expanded="true" aria-controls="collapse<?= $x; ?>">
                                                <i class="icon-copy dw dw-right-arrow-4"></i>Accessories Program For The Style of <span style="color:red"> <?= $sql['style_no']; ?></span>
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapse<?= $x; ?>" class="collapse show <?= ($x == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?= $x; ?>" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y: scroll;">
                                            
                                            <div class="row tcr">
                                                <div class="col-md-3">
                                                    <h6>BO No/ Order Number : <?= $sql['order_code']; ?></h6>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>PO Number : <?= $sql['po_num']; ?></h6>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Order Qty : <?= $sql['total_qty']; ?></h6>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Cut Plan Qty : <?= round($sql['total_qty'] + (($sql['excess']/100)*$sql['total_qty'])) ?></h6>
                                                </div>
                                                <div class="col-md-12">
                                                <hr>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                
                                                <div class="col-md-12">
    											    
    											    <form method="POST" id="programForm" enctype= multipart/form-data>
    											        
                                                            <input type="hidden" name="saveForm" value="">
    											        
        												<div class="pd-20">
        												    
                                                            
                                                            <?php if($_GET['type']=='edit') {
                                                                $mm = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order_accessories_program WHERE id = '". $_GET['pid'] ."'"))
                                                            ?>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label class="fieldrequired">Acc.Type</label>
                                                                        
                                                                        <select class="custom-select2 form-control" name="accType" id="accType" onchange="getAccessoriesTyp()" style="width:100%">
                                                                            <?= select_dropdown('mas_accessories_type', array('id', 'type_name'), 'type_name ASC', $mm['acc_type'], '', ''); ?>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-2">
                                                                        <label class="fieldrequired">Accessories</label>
                                                                        <select class="custom-select2 form-control" name="accessoriesM" id="accessoriesM" style="width:100%">
                                                                            <?= select_dropdown('mas_accessories', array('id', 'acc_name'), 'acc_name ASC', $mm['accessories'], ' WHERE acc_type = '. $mm['acc_type'], ''); ?>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-2">
                                                                        <label class="fieldrequired">Acc.Ref</label>
                                                                        <input type="text" class="form-control" name="acc_ref" id="acc_ref" placeholder="Accessories Ref" value="<?= $mm['acc_ref']; ?>">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Part</label><br>
                                                                        <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#part-addModal" onclick="" style="width:100%;"><i class="fa fa-pencil"></i> Edit</a>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Size Wise</label>
                                                                        <select class="custom-select2 form-control" name="size_wise" id="size_wise" style="width:100%">
                                                                            <option value="no">No</option>
                                                                            <option value="yes" <?= ($mm['size_wise']=='yes')? 'selected' : ''; ?>>Yes</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Color Wise</label>
                                                                        <select class="custom-select2 form-control" name="color_wise" id="color_wise" style="width:100%">
                                                                            <option value="no">No</option>
                                                                            <option value="yes" <?= ($mm['color_wise']=='yes')? 'selected' : ''; ?>>Yes</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Excess</label>
                                                                        <input type="number" class="form-control" name="excessP" id="excessP" placeholder="Excess %" value="<?= $mm['excess']; ?>">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Details</label><br>
                                                                        <a class="btn btn-outline-secondary" onclick="getDetails(<?= $style_id; ?>, 'show')" style="width:100%;"><i class="fa fa-pencil"></i> Edit</a>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-2 tcr">
                                                                        <label>&nbsp;</label><br>
                                                                        <a class="btn btn-outline-primary" onclick="SaveProgram('', 'edit')" style="color: #1b00ff;"><i class="icon-copy fa fa-save" aria-hidden="true"></i> Update</a>
                                                                        
                                                                        <a class="btn btn-outline-secondary" onclick="window.location.href='add-accessProg.php?id=<?= $style_id; ?>'">Cancel</a>
                                                                    </div>
                                                                </div>
                                                                
                                                            <?php } else { ?>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label class="fieldrequired">Acc.Type</label>
                                                                        
                                                                        <select class="custom-select2 form-control" name="accType" id="accType" onchange="getAccessoriesTyp()" style="width:100%">
                                                                            <?= select_dropdown('mas_accessories_type', array('id', 'type_name'), 'type_name ASC', '', '', ''); ?>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-2">
                                                                        <label class="fieldrequired">Accessories</label>
                                                                        <select class="custom-select2 form-control" name="accessoriesM" id="accessoriesM" style="width:100%">
                                                                            
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-2">
                                                                        <label class="fieldrequired">Acc.Ref</label>
                                                                        <input type="text" class="form-control" name="acc_ref" id="acc_ref" placeholder="Accessories Ref">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Part</label><br>
                                                                        <a class="btn btn-outline-secondary" data-toggle="modal" data-target="#part-addModal" onclick="" style="width:100%;"><i class="fa fa-plus"></i> Add</a>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Size Wise</label>
                                                                        <select class="custom-select2 form-control" name="size_wise" id="size_wise" style="width:100%">
                                                                            <option value="no">No</option>
                                                                            <option value="yes">Yes</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Color Wise</label>
                                                                        <select class="custom-select2 form-control" name="color_wise" id="color_wise" style="width:100%">
                                                                            <option value="no">No</option>
                                                                            <option value="yes">Yes</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Excess</label>
                                                                        <input type="number" class="form-control" name="excessP" id="excessP" placeholder="Excess %">
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1">
                                                                        <label class="fieldrequired">Details</label><br>
                                                                        <a class="btn btn-outline-secondary" onclick="getDetails(<?= $style_id; ?>, 'show')" style="width:100%;"><i class="fa fa-plus"></i> Add</a>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-1 tcr">
                                                                        <label>&nbsp;</label><br>
                                                                        <a class="btn btn-outline-primary" onclick="SaveProgram('', 'add')" style="color: #1b00ff;"><i class="icon-copy fa fa-save" aria-hidden="true"></i> Save</a>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
        												</div>
        												
        												<div style="overflow-y:auto">
                                                            <br><hr><br>
                                                            <table class="table table-bordered table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accessories</th>
                                                                        <th>Acc.Ref</th>
                                                                        <th>Part</th>
                                                                        <th>Size Wise</th>
                                                                        <th>Color Wise</th>
                                                                        <th>Excess</th>
                                                                        <th>Details</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if($_GET['type'] == 'edit') {
                                                                        $pid = "AND a.id != '". $_GET['pid'] ."'";
                                                                    } else {
                                                                        $pid = "";
                                                                    }
                                                                        $ujn = mysqli_query($mysqli, "SELECT a.*, b.acc_name FROM sales_order_accessories_program a LEFT JOIN mas_accessories b ON a.accessories=b.id WHERE a.sales_order_detalis_id='". $style_id ."' ". $pid ." ");
                                                                        if(mysqli_num_rows($ujn)>0) {
                                                                            while($row = mysqli_fetch_array($ujn)) {
                                                                                
                                                                                $hb = mysqli_query($mysqli, "SELECT part_name FROM part WHERE id IN (". $row['part'] .")"); 
                                                                                while($frw = mysqli_fetch_array($hb)) {
                                                                                    $compNm[$row['id']][] = $frw['part_name'];
                                                                                }
                                                                    ?>
                                                                            <tr id="addedFabTr<?= $row['id']; ?>">
                                                                                <td><?= $row['acc_name']; ?></td>
                                                                                <td><?= $row['acc_ref']; ?></td>
                                                                                <td><?= implode(',', $compNm[$row['id']]); ?></td>
                                                                                <td><?= strtoupper($row['size_wise']); ?></td>
                                                                                <td><?= strtoupper($row['color_wise']); ?></td>
                                                                                <td><?= $row['excess']; ?></td>
                                                                                <td><a onclick="addedAccProg(<?= $row['id']; ?>)"><i class="fa fa-eye"></i></a></td>
                                                                                <td>
                                                                                    <?php if(ACCESSORIES_PROG_EDIT==1) { ?>
                                                                                        <a onclick="EditAccessProgram(<?= $style_id; ?>, <?= $row['id']; ?>)" class="border border-secondary rounded text-secondary"><i class="icon-copy fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                                                    <?php } if(ACCESSORIES_PROG_DELETE==1) { ?>
                                                                                        <a onclick="delete_accessProgram(<?= $row['id']; ?>)" class="border border-secondary rounded text-secondary"><i class="icon-copy fa fa-trash" aria-hidden="true"></i></a>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                    <?php } } else {
                                                                        print '<tr><td colspan="8" class="tcr">Nothing Found</td></tr>';
                                                                    }?>
                                                                </tbody>
                                                            </table>
                                                        </div>
        												
        												<?php if($_GET['type']=='edit') { ?>
        												    
                            							    <div class="modal fade bs-example-modal-lg" id="part-addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                								<div class="modal-dialog modal-dialog-centered">
                                									<div class="modal-content">
                                										<div class="modal-header">
                                											<h4 class="modal-title" id="myLargeModalLabel">Edit Part List</h4>
                                											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                										</div>
                                										<div class="modal-body">
                                											
                                											<div style="overflow-y:auto">
                                    											<table class="table table-bordered">
                                                                                    <thead style="background-color: #f7f7f7;">
                                                                                        <tr>
                                                                                            <th>Combo</th>
                                                                                            <th>Part</th>
                                                                                            <th>Color</th>
                                                                                            <th>Select</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                            $ptt = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = ". $style_id);
                                                                                            $zz = 1;
                                                                                            while($sod_part = mysqli_fetch_array($ptt)) {
                                                                                        ?>
                                                                                            <!-- <tr>
                                                                                                <td><?= part_name($sod_part['part_id']); ?></td>
                                                                                                <td><input type="checkbox" class="cbx" name="part_[]" id="" value="<?= $part[1]; ?>" <?= (in_array($part[1], explode(',', $mm['part']))) ? 'checked' : ''; ?>></td>
                                                                                            </tr> -->
                                                                                        <?php $zz++; } ?>

                                                                                        <?php
                                                                                            $ptt = mysqli_query($mysqli, "SELECT part_id, combo_id, color_id FROM sod_part WHERE sales_order_detail_id = ". $style_id);
                                                                                            $zz = 1;
                                                                                            while($sod_part = mysqli_fetch_array($ptt)) {
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td><?= color_name($sod_part['combo_id']); ?></td>
                                                                                                <td><?= part_name($sod_part['part_id']); ?></td>
                                                                                                <td><?= color_name($sod_part['color_id']); ?></td>
                                                                                                <td><input type="checkbox" class="cbx" name="part_[]" id="" checked value="<?= $sod_part['part_id']; ?>" <?= (in_array($sod_part['part_id'], explode(',', $mm['part']))) ? 'checked' : ''; ?>></td>
                                                                                            </tr>
                                                                                        <?php $zz++; } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                										</div>
                                										<div class="modal-footer">
                                											<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                											<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Ok</button>
                                										</div>
                                									</div>
                                								</div>
                                							</div>
                											
                											<div class="modal fade bs-example-modal-lg" id="detail-addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                								<div class="modal-dialog modal-dialog-centered">
                                									<div class="modal-content">
                                										<div class="modal-header">
                                											<h4 class="modal-title" id="myLargeModalLabel">Edit Details</h4>
                                											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                										</div>
                                										<div class="modal-body" style="overflow-y:auto">
                                											<table class="table table-striped">
                                                                                <thead id="det_thead"></thead>
                                                                                <tbody id="det_tbody"></tbody>
                                                                            </table>
                                										</div>
                                										<div class="modal-footer">
                                											<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                											<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Ok</button>
                                										</div>
                                									</div>
                                								</div>
                                							</div>
                                							
                            							<?php } else { ?>
                            							    
                            							    <div class="modal fade bs-example-modal-lg" id="part-addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                								<div class="modal-dialog modal-dialog-centered">
                                									<div class="modal-content">
                                										<div class="modal-header">
                                											<h4 class="modal-title" id="myLargeModalLabel">Part List</h4>
                                											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                										</div>
                                										<div class="modal-body">
                                											
                                											<div style="overflow-y:auto">
                                    											<table class="table table-bordered">
                                                                                    <thead style="background-color: #f7f7f7;">
                                                                                        <tr>
                                                                                            <th>Combo</th>
                                                                                            <th>Part</th>
                                                                                            <th>Color</th>
                                                                                            <th>Select</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                            $ptt = mysqli_query($mysqli, "SELECT part_id, combo_id, color_id FROM sod_part WHERE sales_order_detail_id = ". $style_id);
                                                                                            $zz = 1;
                                                                                            while($sod_part = mysqli_fetch_array($ptt)) {
                                                                                        ?>
                                                                                            <tr>
                                                                                                <td><?= color_name($sod_part['combo_id']); ?></td>
                                                                                                <td><?= part_name($sod_part['part_id']); ?></td>
                                                                                                <td><?= color_name($sod_part['color_id']); ?></td>
                                                                                                <td><input type="checkbox" class="cbx" name="part_[]" id="" checked value="<?= $sod_part['part_id']; ?>"></td>
                                                                                            </tr>
                                                                                        <?php $zz++; } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                										</div>
                                										<div class="modal-footer">
                                											<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                											<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Ok</button>
                                										</div>
                                									</div>
                                								</div>
                                							</div>
                											
                											<div class="modal fade bs-example-modal-lg" id="detail-addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                								<div class="modal-dialog modal-dialog-centered lgmodal">
                                									<div class="modal-content">
                                										<div class="modal-header">
                                											<h4 class="modal-title" id="myLargeModalLabel">Enter Details</h4>
                                											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                										</div>
                                										<div class="modal-body" style="overflow-y:auto">
                                											<table class="table table-striped table-bordered">
                                                                                <thead id="det_thead"></thead>
                                                                                <tbody id="det_tbody"></tbody>
                                                                            </table>
                                										</div>
                                										<div class="modal-footer">
                                											<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                											<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Ok</button>
                                										</div>
                                									</div>
                                								</div>
                                							</div>
                            							<?php } ?>
                        							</form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="pb-20 tcr">
                            <!--<button id="btnSave" name="btnSave" type="submit" class="btn btn-outline-primary">Save</button>-->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade bs-example-modal-lg" id="addedAccProg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" id="myLargeModalLabel">Details</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<div style="overflow-y:auto">
								<table class="table table-bordered">
                                    <thead style="background-color: #f7f7f7;" id="addedThead">
                                    </thead>
                                    <tbody id="addedTbody">
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
    
    <?php if($_GET['type']=='edit') { ?>
    
        <script> var pids = <?= $_GET['pid'] ?>; </script>
    
    <?php } else { ?>
    
        <script> var pids = ''; </script>
    
    <?php } ?>
    
    <script>
        function getDetails(id, typ) {
            
            var sizeWise = $("#size_wise").val();
            var colorWise = $("#color_wise").val();
            
            if(sizeWise == 'yes' && colorWise == 'yes') {
                $(".lgmodal").addClass('modal-lg');
            } else {
                $(".lgmodal").removeClass('modal-lg');
            }

            var data = {
                sizeWise : sizeWise,
                colorWise : colorWise,
                id : id,
                pid : pids,
            };
            
            $.ajax({
                type : 'POST',
                url : 'ajax_search.php?getAccessDetails=1',
                data: data,
                
                success : function(msg) {
                    
                    var j = $.parseJSON(msg);
                    
                    $("#det_thead").html(j.thead);
                    $("#det_tbody").html(j.tbody);
                    
                    if(typ == 'show') {
                        $(document).ready(function() {
        
                            $(".sel2").each(function(){
                                $(this).select2({
                                    dropdownParent:$("#detail-addModal")
                                });
                            });
                        });
        
                        $("#detail-addModal").modal('show');
                    }
                }
            })
            
        }
    </script>
    
    <script>
        function getAccessoriesTyp() {
            var typ = $("#accType").val();
            
            $.ajax({
                type : 'POST',
                url : 'ajax_search.php?getAccessoriesTyp=1&type=' + typ,
                
                success : function(msg) {
                    
                    var j = $.parseJSON(msg);
                    
                    $("#accessoriesM").html(j.acc_name);
                }
            })
        }
    </script>
    
    <script>
        function SaveProgram(id, typ){
            
            if($("#accType").val()=="")
            {
                message_noload('warning', 'Accessories Type Required!', 1500);
                return false;
            } else if($("#accessoriesM").val()=="")
            {
                message_noload('warning', 'Accessories Required!', 1500);
                return false;
            } else if($("#acc_ref").val()=="")
            {
                message_noload('warning', 'Accessories Ref Required!', 1500);
                return false;
            }else if($("#excessP").val()=="")
            {
                message_noload('warning', 'Excess Required!', 1500);
                return false;
            }
            
            var s = 0;
            $(".cbx").each(function() {
                var n = $(this).is(':checked');
                
                if(n==true) {
                    s++;
                }
            });
            
            if(s==0) {
                message_noload('warning', 'Select Atleast One Part!', 1500);
                return false;
            }
            
                
            if(typ=='add') {
                $("#overlay").fadeIn(300);
                $("#programForm").submit();
            } else if(typ == 'edit') {
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
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
                        $("#programForm").submit();
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
        function EditAccessProgram(id, pid) {
            $("#overlay").fadeIn(300);
            window.location.href="add-accessProg.php?id="+ id +"&type=edit&pid="+ pid +"";
        }
    </script>
    
    <script>
        function addedAccProg(id) {
            
            $.ajax({
                type:'POST',
                url:'ajax_search.php?GetAddedaccDet=1&id=' + id,
                success: function(msg) {
                    var json = $.parseJSON(msg);
                    setTimeout(function () {
                        $("#addedThead").html(json.thead);
                        $("#addedTbody").html(json.tbody);
                    }, 500)
                }
            })
            
            $("#addedAccProg").modal('show');
        }
        
        $('#addedAccProg').on('hidden.bs.modal', function (e) {
            $("#addedThead").html('');
            $("#addedTbody").html('<tr><td colspan="6" style="text-align: center;">Loading <i class="icon-copy fa fa-spinner" aria-hidden="true"></i></td></tr>');
        })
    </script>
    
    <script>
        function delete_accessProgram(id) {
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
                        url: 'ajax_action.php?delete_accessProgram=1&id=' + id,
                        success: function (msg) {
                            
                            if (msg == 0) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    showConfirmButton: true,
                                    timer: 1500
                                }).then(
                                    function () {
                                        $("#addedFabTr" + id).remove();
                                    })
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
                    swal(
                        'Cancelled',
                        '',
                        'error'
                    )
                }
            })
        }
    </script>
    
    
<?php if($_GET['type']=='edit') { ?>
<script>
    var id = <?= $style_id; ?>;
    
    $(document).ready(function(){
        getDetails(id, 'not');
    })
    
</script>
    
<?php } ?>

</html>