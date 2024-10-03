<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $id));
} else {
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Sewing Input</title>

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
    
    /*#reader__status_span {*/
    /*    font-size:25px !important;*/
    /*}*/
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <?php page_spinner(); if(SEW_INPUT_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="view-sewingInput.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Sewing Input List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                New Sewing Input
                            </h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="input-form" method="post" autocomplete="off">

                        <input type="hidden" name="process_type" id="process_type" value="sewing_input">

                        <div class="row">

                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['processing_code'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM processing_list WHERE processing_code LIKE '%SI-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'SI-1';
                                } else {
                                    $ex = explode('-', $sqql['processing_code']);
    
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
                                    <input type="text" readonly name="processing_code" class="form-control" value="<?= $code; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="entry_date" class="form-control" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2 bodiv <?= isset($_GET['id']) ? 'pe-none' : ''; ?>">
                                <label for="">BO <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="order_id" id="order_id">
                                        <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', $sql['order_id'], ' WHERE is_dispatch IS NULL', ''); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Input type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="input_type" id="input_type" class="custom-select2 form-control" style="width:100%">
                                        <option value="Line">Line</option>
                                        <option value="Employee" <?= ($sql['input_type'] == 'Employee') ? 'selected' : ''; ?>>Employee</option>
                                        <option value="Unit" <?= ($sql['input_type'] == 'Unit') ? 'selected' : ''; ?>>Unit</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label fieldrequired line_label"><?= $sql['input_type'] ? $sql['input_type'] : 'Line'; ?></label>
                                <div class="form-group">
                                    <select name="line" id="line" class="custom-select2 form-control" style="width:100%">
                                        <option value="">Select <?= $sql['input_type'] ? $sql['input_type'] : 'Line'; ?></option>
                                        <?php
                                            if($sql['input_type']=='Employee') {
                                                print select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sql['assigned_emp'], ' WHERE is_active="active"', '`');
                                            } else if($sql['input_type']=='Unit') {
                                                print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sql['assigned_emp'], ' WHERE type = 2', '`');
                                            } else {
                                                print select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', $sql['assigned_emp'], '', '1');
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="col-form-label">Scan Type <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <!--<input type="radio" value="Mobile" id="sc_Type_mobile" name="scanning_using"> <label for="sc_Type_mobile">Mobile</label>-->
                                    <!--<input type="radio" value="Device" id="sc_Type_device" name="scanning_using"> <label for="sc_Type_device">Device</label>-->
                                    <input type="radio" value="Manual" id="sc_Type_manual" name="scanning_using" checked> <label for="sc_Type_manual">Manual</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 text-center scanDiv <?= isset($_GET['id']) ? 'd-none' : ''; ?>"><a class="btn btn-outline-secondary" onclick="startScanning()">Start Scanning</a></div>
                        </div>
                        
                        <div class="row styleDiv <?= isset($_GET['id']) ? '' : 'd-none'; ?>">
                            
                            <div class="col-md-2">
                                <label class="fieldrequired">Style :</label>
                                <select class="custom-select2 form-control" name="style_id" id="style_id" style="width:100%;">
                                    <?= isset($_GET['id']) ? select_dropdown('sales_order_detalis', array('id', 'style_no'), 'id ASC', '', ' WHERE sales_order_id = '. $sql['order_id'], '') : ''; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="fieldrequired">Combo || Part || Color</label>
                                <select class="custom-select2 form-control" name="sod_part" id="sod_part" style="width:100%;"></select>
                            </div>
                            
                            <div class="col-md-4">
                                <label>Bundle Number :</label>
                                <div class="input-group mb-3">
                                    <input type="hidden" name="processing_id" id="processing_id" value="<?= $_GET['id']; ?>">
                                    <input type="hidden" name="already_scanned" id="already_scanned" value="">
                                    <select class="custom-select2 form-control" name="multibundle[]" id="multibundle" multiple style="width:70%;"></select>
                                    
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" onclick="add_bundle()" type="button">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mainDiv <?= isset($_GET['id']) ? '' : 'd-none'; ?>">
                            
                            <div class="col-md-12" style="text-align:center">Added Bundle Count : <span id="scanedCnt"><?= ($sql['boundle_id'] != "") ? count(explode(',', $sql['boundle_id'])) : '0'; ?></span></div>
                            
                            <div class="col-md-12" style="overflow-y: auto;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>BO</th>
                                            <th>Cutting Ref</th>
                                            <th>Style No</th>
                                            <th>Combo</th>
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
                                    <tfoot>
                                        <?php if($sql['boundle_id']!="") { foreach(explode(',', $sql['boundle_id']) as $bundle) {
                                            $bk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE id = '". $bundle ."' "));
                                        ?>
                                            <tr id="trr_new<?= $bundle; ?>" class="old_tr">
                                                <td><?= sales_order_code($bk['order_id']); ?></td>
                                                <td><?= cutting_entry_number($bk['cutting_barcode_id']); ?></td>
                                                <td><?= sales_order_style($bk['style_id']); ?></td>
                                                <td><?= color_name($bk['combo']); ?></td>
                                                <td><?= part_name($bk['part']); ?></td>
                                                <td><?= color_name($bk['color']); ?></td>
                                                <td><?= variation_value($bk['variation_value']); ?></td>
                                                <td><?= $bk['bundle_number']; ?></td>
                                                <td><?= $bk['pcs_per_bundle']; ?></td>
                                                <td><?= $bk['boundle_qr']; ?></td>
                                                <td><a class="text-danger" onclick="remove_bundle(<?= $bundle; ?>)"><i class="fa fa-trash"></i></a></td>
                                            </tr>
                                        <?php } } ?>
                                    </tfoot>
                                </table>
                            </div>
                            
                            
                            <div class="col-md-12"> 
                                <div class="form-group" style="text-align: center;">
                                    <a class="btn btn-outline-secondary" href="view-sewingInput.php">Go Back</a>
                                    <input type="button" class="btn btn-outline-success saveBttn" name="SaveBtn" value="Save Input">
                                </div>
                            </div>
                        </div>
                        
                        
                        <!--<input type="" name="multibundle">-->
                        <!--<input type="" name="head">-->
                        <!--<input type="" name="scanner">-->
                    </form>
                </div>

            </div>
            <?php include('includes/footer.php'); include('modals.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        $("#input_type").change(function() {
            
            var input_type = $("#input_type").val();
            
            $.ajax({
                type:'POST',
                url:'ajax_search2.php?input_type_for_si=1&input_type='+ input_type,
                success:function(msg){
                    var json = $.parseJSON(msg);
                    
                    $(".line_label").text(json.label);
                    $("#line").html(json.option);
                }
            })
        });
    </script>

    <script>
        function remove_bundle(id) {
            
            swal({
                title: 'Confirm Remove?',
                text: "",
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    
                    $("#overlay").fadeIn(100);
                    $.post('ajax_action2.php?remove_bundle=1&id=' + id, $("#input-form").serialize(), function(res) {
                        let json = $.parseJSON(res);
                        
                        if (json.result == 0) {
                            $("#overlay").fadeOut(200);
                            
                            var scanedCnt = $("#scanedCnt").text();
                            $("#scanedCnt").text((scanedCnt-1));
                            $("#trr_new" + id).remove();
                        } else {
                            message_error();
                        }
                    });
                } else {
                    swal('Scanning Cancelled!', '', 'error' )
                }
            })
        }
    </script>

    <script>
        $(".saveBttn").click(function() {
            
            var a = $(".bundles").length;
            var b = $(".old_tr").length;
            
            if(a == 0 && b == 0) {
                message_noload('error', 'Add Bundles To save!', 2000);
                return false;
            } else {
                $("#overlay").fadeIn(100);
                var form = $("#input-form").serialize();
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action2.php?save_sewing_input=1',
                    data: form,
                    success: function (res) {
                        var json = $.parseJSON(res);
                        if(json.result==0) {
                            $("#overlay").fadeOut(200);
                            message_redirect('success', 'Sewing Input Saved.', 1500, 'view-sewingInput.php');
                            return false;
                        } else {
                            $("#overlay").fadeOut(200);
                            message_error();
                        }
                    }
                });
            }
        });
    </script>
    
    <script>
        function removeRow(id) {
            
            var input = $('#already_scanned').val();
            var array = input.split(',').map(Number);
            var filteredArray = $.grep(array, function(value) {
                return value != id;
            });
            $('#already_scanned').val(filteredArray.join(','));
            $("#tbTr" + id).remove();
        }
    </script>
    
    <script>
        function add_bundle() {
            var line = $("#line").val();
            var input_type = $("#input_type").val();
            var multibundle = $("#multibundle").val();
            
            if(line == "") {
                message_noload('error', 'Select ' + input_type + ' to add Bundle!', 2000);
                return false;
            } else if(multibundle == "" || multibundle == "all") {
                message_noload('error', 'Select Bundle!', 2000);
                return false;
            } else {
                $("#overlay").fadeIn(100);
                var already_scanned = $("#already_scanned").val();
                
                var data = 'bundles='+ multibundle +'&already_scanned=' + already_scanned;
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search2.php?get_temp_bundle=1',
                    data: data,
                    success: function (res) {
                        var json = $.parseJSON(res);
                        
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Bundle Added',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(function () {
                                $(".mainDiv").removeClass('d-none');
                                $("#tableBody").html(json.tbody);
                                $("#already_scanned").val(json.old_array);
                                
                                var scanedCnt = $("#scanedCnt").text();
                                $("#scanedCnt").text((parseInt(scanedCnt)+parseInt(json.bdl_count)));
                                $("#overlay").fadeOut(500);
                            });
                    }
                });
            }
        }
    </script>

    <script>
        $("#multibundle").change(function () {
            
            if($(this).val() == 'all'){
                $('#multibundle option').prop('selected', true);
            } else {
                $('#multibundle option[value="all"]').prop('selected', false);
            }
        })
    </script>
    
    <script>
        $("#sod_part").change(function() {
            var id = $(this).val();
            $("#overlay").fadeIn(100);
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?get_bundle_for_swInput=1&id=' + id,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    $("#multibundle").html(json.option);
                    $("#overlay").fadeOut(500);
                }
            });
        });
    </script>
    
    <script>
        $("#style_id").change(function() {
            var id = $(this).val();
            $("#overlay").fadeIn(100);
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?get_combo_details_for_style=1&id=' + id,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    $("#sod_part").html(json.option);
                    $("#overlay").fadeOut(500);
                }
            });
        });
    </script>
    
    <script>
        $("#order_id").change(function () {
            var a = $(this).val();
            $("#overlay").fadeIn(100);
            if (a == "") {
                $("#style_id").html('').trigger('change');
                $("#sod_part").html('').trigger('change');
                $("#multibundle").html('').trigger('change'); 
                $("#overlay").fadeOut(500);
                return false;
            }
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getStyleNoforProcess=1&id=' + a,
                success: function (msg) {
                    $("#style_id").html(msg);
                    $("#overlay").fadeOut(500);
                }
            });
        })
    </script>
    
    <script>
        function startScanning() {
            
            var order_id = $("#order_id").val();
            
            if(order_id=="") {
                message_noload('error', "Select Bo!");
                return false;
            } else {
                
                swal({
                    title: 'Confirm start?',
                    text: "You are not able to change BO after confirm this!",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Start!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success margin-5',
                    cancelButtonClass: 'btn btn-danger margin-5',
                    buttonsStyling: false
                }).then(function (dd) {
                    if (dd['value'] == true) {
                        $("#overlay").fadeIn(100);
                        $(".bodiv").addClass('pe-none');
                        $(".scanDiv").addClass('d-none');
                        $(".styleDiv").removeClass('d-none');
                        
                        $("#overlay").fadeOut(500);
                    } else {
                        swal('Scanning Cancelled!', '', 'success' )
                    }
                });
            }
        }
    </script>

</body>

</html>