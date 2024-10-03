<?php
include ("includes/connection.php");
include ("includes/function.php");

include ("includes/perm.php");

$qryz = mysqli_query($mysqli, "SELECT * FROM podc_cancel WHERE id ='" . $_GET['id'] . "' ORDER BY id DESC");
$sql = mysqli_fetch_array($qryz);
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Fabric Purchase Order</title>

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


    .mw-150 {
        min-width: 150px
    }

    .hov_show {
        display: none;
    }

    .td_edDl:hover .hov_show {
        display: block;
    }

    /*.td_edDl {*/
    /*    display: flex;*/
    /*}*/


    @media (max-width: 479px) {
        /*.td_edDl {*/
        /*    min-width: 50px;*/
        /*}*/
    }
</style>

<body>

    <?php include ('includes/header.php'); ?>

    <?php include ('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <?php if (FAB_CANCEL_ADD != 1 || FAB_CANCEL_EDIT != 1) { action_denied(); exit; } ?>

                    <div class="alert alert-warning fade show d-none" role="alert">
                        <a onclick="showImpt()" class="a_click"><i class="icon-copy ion-chevron-right icc_imp"></i>
                            <strong>Important! <i class="icon-copy fa fa-hand-o-down"
                                    aria-hidden="true"></i></strong></a><br>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                            style="margin-top: -25px;"><span aria-hidden="true">×</span></button>
                    </div>


                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="po_dc_cancel.php" style="float: right;"><i
                                class="fa fa-list" aria-hidden="true"></i> PO / DC List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">New PO / DC Cancel</h4>
                        </div>
                    </div>
                    <form id="podcform" method="post" autocomplete="off">

                        <input type="hidden" name="opdc_id" id="opdc_id" value="<?= $sql['id']; ?>">
                        <div class="row">
                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['podc_entry'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM podc_cancel WHERE podc_entry LIKE '%CA-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'CA-1';
                                } else {
                                    $ex = explode('-', $sqql['podc_entry']);
                                    //print_r($ex);     
                                    $value = $ex[1];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);

                                    $code = $ex[0] . '-' . $nnum;
                                }
                            }
                            ?>
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="podc_entry" class="form-control" value="<?= $code ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">Entry Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="podc_date" id="podc_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-2 <?= isset($_GET['id']) ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Cancel Type <span class="text-danger"></span></label>
                                <div class="form-group">
                                    <select name="opdc_canceltype" id="opdc_canceltype" class="form-control custom-select2" style="width:100%">
                                        <option value="full">Full</option>
                                        <option value="part" <?= ($sql['opdc_canceltype'] == 'part') ? 'selected' : ''; ?>>Part</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 <?= isset($_GET['id']) ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Cancel To<span class="text-danger"></span></label>
                                <div class="form-group">
                                    <select name="opdc_cancelto" id="opdc_cancelto" class="form-control custom-select2" onchange="handleClick()" style="width:100%">
                                        <option value="">Select</option>
                                        <option value="fabric_po" <?= ($sql['opdc_cancelto'] == 'fabric_po') ? 'selected' : ''; ?>>Purchase Order(PO)</option>
                                        <option value="fabric_dc" <?= ($sql['opdc_cancelto'] == 'fabric_dc') ? 'selected' : ''; ?>>Process Outward(DC)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 <?= isset($_GET['id']) ? 'pe-none' : ''; ?>">
                                <label class="col-form-label">Entry NO <span class="text-danger"></span></label>
                                <div class="form-group">
                                    <select name="podc_entryno" id="podc_entryno" class="form-control custom-select2" style="width:100%">
                                        <?php if($_GET['id']) {
                                            $tyy = ($sql['opdc_cancelto'] == 'fabric_po') ? 'entry_number' : 'dc_number';
                                                print select_dropdown_multiple($sql['opdc_cancelto'], array('id', $tyy), $tyy .' ASC', $sql['podc_entryno'], 'WHERE id='. $sql['podc_entryno'], '`');
                                            }
                                        ?>
                                    </select>
                                    
                                    <input type="hidden" name="podc_cancel_id" id="podc_cancel_id" value="<?= $_GET['id']; ?>">
                                </div>
                            </div>
                        </div>
                        <div style="overflow-y: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>BO</th>
                                        <th>Purchase Stage</th>
                                        <th>Material Name</th>
                                        <th>Cancel QTY/Wt</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php if($_GET['id']) {
                                        $nc = mysqli_query($mysqli, "SELECT * FROM podc_cancel_det WHERE podc_cancel_id = '". $_GET['id'] ."' ");
                                        while($roww = mysqli_fetch_array($nc)) {
                                            
                                            if($roww['cancel_from'] == 'fabric_po') {
                                                
                                                $f = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.order_id, a.po_stage, b.yarn_id, b.fabric_id FROM fabric_po_det a LEFT JOIN fabric_requirements b ON a.material_name = b.id WHERE a.id = '". $roww['cancel_id'] ."'"));
                                            } else {
                                                
                                                $f = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.order_id, a.process_id, b.yarn_id, b.fabric_id FROM fabric_dc_det a LEFT JOIN fabric_requirements b ON a.fab_req_id = b.id WHERE a.id = '". $roww['cancel_id'] ."'"));
                                            }
                                            
                                            
                                            if($f[1]==26) {
                                                $matt = mas_yarn_name($f['yarn_id']);
                                            } else {
                                                $matt = fabric_name($f['fabric_id']);
                                            }
                                    ?>
                                        <tr>
                                            <td><?= sales_order_code($f[0]); ?></td>
                                            <td><?= process_name($f[1]); ?></td>
                                            <td><?= $matt; ?></td>
                                            <td>
                                                <input type="hidden" name="podc_cancel_det_id[]" id="podc_cancel_det" value="<?= $roww['id']; ?>">
                                                <input type="hidden" name="cancel_id[]" id="cancel_id" value="<?= $roww['cancel_id']; ?> ">
                                                <input type="text" name="cancel_qty[]" id="cancel_qty" class="form-control mw-200" value="<?= $roww['cancel_qty']; ?>">
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                                
                                <tfoot class="tfoot <?= isset($_GET['id']) ? '' : 'd-none'; ?>">
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <a class="btn btn-outline-secondary" onclick="window.location.href='po_dc_cancel.php'">Go Back</a>
                                            <a class="btn btn-outline-primary opdcsaveorder" >Save PO / DC Cancel</a>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        
                    </form>
                </div>

            </div>
            <?php include ('includes/footer.php'); ?>
        </div>
    </div>

    <?php include ('includes/end_scripts.php'); ?>
    
    <script>
        $("#opdc_canceltype").change(function(){
            $("#tableBody").html('');
            
            $('#podc_entryno').trigger('change');
        });
    </script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            $('.opdcsaveorder').click(function () {
                
                $(this).addClass('pe-none').text('Updating');
                var opdc_entery = $('#podc_entry').val();
                var podc_date = $('#podc_date').val();
                var opdc_canceltype = $('#opdc_canceltype').val();
                var podc_entryno = $('#podc_entryno').val();
                
                var form = $('#podcform').serialize();
                
                $.ajax({
                    url: 'ajax_action2.php?opdcsaveorder=1',
                    type: 'post',
                    data: form,
                    success: function (response) {
                        
                        var json = $.parseJSON(response);
                        
                        if(json.result==0) {
                            message_redirect('success', 'Success', 2000, 'po_dc_cancel.php');
                        } else {
                            message_error();
                        }
                        // message_noload('success', 'PO / DC CANCEL Saved!');
                        // setTimeout(function () { window.location.href = "po_dc_cancel.php"; }, 1500);
                    }
                });
            });
        });
    </script>

    <script>
        $("#podc_entryno").change(function() {
            
            var id = $(this).val();
            var type = $("#opdc_cancelto").val();
            var opdc_canceltype = $("#opdc_canceltype").val();
            
            if(id=="") {
                $(".tfoot").addClass('d-none');
                $("#tableBody").html('');
            } else {
            
                var data = 'id=' + id + '&type=' + type + '&opdc_canceltype=' + opdc_canceltype;
                
                $.ajax({
                    url: 'ajax_search2.php?getCancel_details=1',
                    type: 'post',
                    data : data,
                    
                    success: function (response) {
                        
                        var json = $.parseJSON(response);
                        
                        $("#tableBody").html(json.tbody);
                        
                        $(".tfoot").removeClass('d-none');
                    }
                });
            }
        });
    </script>
    
    <script>
        function handleClick() {

            var opdccancel = $('#opdc_cancelto').val();
            
            $("#tableBody").html('');
            
            if(opdccancel=="") {
                $("#podc_entryno").html('');
                $(".tfoot").addClass('d-none');
            } else {
                $.ajax({
                    url: 'ajax_search2.php?purchase_ordertest=1&value=' + opdccancel,
                    type: 'post',
                    success: function (response) {
                        var json = $.parseJSON(response);
                        $("#podc_entryno").html(json.optionn);
                        $(".tfoot").addClass('d-none');
                    }
                });
            }
        }
    </script>

</body>

</html>