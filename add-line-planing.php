<?php
include("includes/connection.php");
include("includes/function.php");
include("includes/perm.php");
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
    <title>BENSO - Line Planning</title>

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
        .accordion span {
            color: #e83e8c;
            /* font-size: 20px; */
        }
        
        .card-body h5 {
            color: #1b00ff;
            text-decoration: underline;
            text-transform: uppercase;
        }
    </style>


    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Line Planning Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Line Planning Updated.
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
                    <?php page_spinner(); if(PROD_PLANNING!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary showmodal" href="line-planning.php" style="float: right;" ><i class="fa fa-list" aria-hidden="true"></i> Line Planning List</a>
                        <h4 class="text-blue h4">Add Line Planning</h4>
                    </div>
                    <div class="pb-20">
                        <form id="planningFrom" method="post">                        
                            <div class="accordion" id="accordionExample" style="padding: 25px;">
                                <?php
                                $qry = "SELECT * FROM sales_order_detalis WHERE id='" . $id . "' ORDER BY id ASC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    $style = $sql['id'];
                                    ?>
                                    <div class="card">
                                        <div class="card-header" id="heading<?= $x; ?>">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $x; ?>" aria-expanded="true" aria-controls="collapse<?= $x; ?>">
                                                    <i class="icon-copy dw dw-right-arrow-4"></i>Planning For The Style of <span><?= $sql['style_no']; ?></span>
                                                </button>
                                            </h2>
                                        </div>                                        
                                        <div id="collapse<?= $x; ?>" class="collapse show <?= ($x == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?= $x; ?>" data-parent="#accordionExample">
                                            <div class="card-body" style="overflow-y: auto;">
                                                    <div class="row" style="padding: 20px;">
                                                        <div class="col-md-2">
                                                            <label for="">Planning Type :</label>
                                                            <select name="planning_type" id="planning_type" class="custom-select2 form-control planning_type">
                                                                <option value="order">Order Quantity</option>
                                                                <option value="cutting" <?= ($_GET['typ']=='cutting') ? 'selected' : ''; ?>>Cutting Output</option>                                                               
                                                            </select>                                                            
                                                            <input type="hidden" name="order_id" value="<?= $sql['sales_order_id']; ?>">
                                                            <input type="hidden" name="style_id" value="<?= $sql['id']; ?>">
                                                        </div>
                                                    </div>                                                  
                                                    <table class="table table-bordered">
                                                        <thead style="background-color: #f7f7f7;">
                                                            <tr>
                                                                <th>Combo & Part & Color</th>
                                                                <th>Size</th>
                                                                <th>Qty</th>
                                                                <th>Type</th>
                                                                <th>Line/ Employee/ Unit</th>                                                              
                                                            </tr>
                                                        </thead>
                                                        <tbody>                                                            
                                                        <?php
                                                            $sel = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = '". $style ."'");
                                                            $x = 1;
                                                            while($result = mysqli_fetch_array($sel)) {
                                                                $line = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM line_planning WHERE sod_part = ". $result['id']));
                                                                $sp = $result['id'];

                                                                $cut_q = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE sod_part = ". $result['id']));
                                                        ?>
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="line_planned[]" value="<?= $line['id']; ?>">
                                                                        <input type="hidden" name="sod_part[]" value="<?= $result['id']; ?>">
                                                                        <?php echo color_name($result['combo_id']).' || '. part_name($result['part_id']) .' || '. color_name($result['color_id']);?>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control custom-select2" name="planningtype<?= $result['id']; ?>" id="planningtype<?= $x; ?>" style="width:100%;" onchange="change_supplier_unit(<?= $x; ?>)">
                                                                            <option value="Full">Full</option>
                                                                            <option value="Partial" <?= ($line['planning_type']=='Partial') ? 'selected' : ''; ?>>Partial</option>                                                                                    
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <div class="qty-div<?= $x; ?> <?= ($line['planning_type']=='Partial') ? 'd-none' : ''; ?>">
                                                                            <?php 
                                                                            $totalSizeQty = 0;
                                                                            $ro1 = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $result['sod_combo'] ."'");
                                                                            while($row = mysqli_fetch_array($ro1)) { 
                                                                                $totalSizeQty += $row['size_qty'];
                                                                            }
                                                                            echo  ($_GET['typ']=='order') ?$totalSizeQty : $cut_q['pcs_per_bundle'];
                                                                            ?>
                                                                            <input type="hidden" name="sod_part_qty<?= $result['id']; ?>" value="<?= ($_GET['typ']=='order') ?$totalSizeQty : $cut_q['pcs_per_bundle']; ?>">
                                                                        </div>
                                                                    </td>                                                                        
                                                                    <td>
                                                                        <div class="ass_type-div<?= $x; ?> <?= ($line['planning_type']=='Partial') ? 'd-none' : ''; ?>">
                                                                            <select class="form-control custom-select2" name="assign_type<?= $result['id']; ?>" id="assign_type_for_<?= $x; ?>" style="width:100%;" onchange="part_plan_for(<?= $x;  ?>, 'assign_type_')">
                                                                                <option value="line">Line</option>
                                                                                <option value="employee" <?= ($line['assign_type']=='employee') ? 'selected' : ''; ?>>Employee</option>
                                                                                <option value="unit" <?= ($line['assign_type']=='unit') ? 'selected' : ''; ?>>Unit</option>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                                                                                                
                                                                    <td>
                                                                        <div class="ass_to-div<?= $x; ?> <?= ($line['planning_type']=='Partial') ? 'd-none' : ''; ?>">
                                                                            <select class="form-control custom-select2" name="assign_to<?= $result['id']; ?>" id="assign_type_for_to<?= $x; ?>" style="width:100%;">
                                                                                <option value="">Select <?= $line['assign_type'] ? $line['assign_type'] : 'Line'; ?></option>
                                                                                <?php if($line['assign_type']=='employee') { print select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $line['assign_to'], 'WHERE is_active="active"', '`'); } 
                                                                                    else if($line['']== 'unit') { print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $line['assign_to'], 'WHERE type = 2 AND is_active="active"', '`'); } 
                                                                                    else { print select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', $line['assign_to'], 'WHERE is_active="active"', '`'); }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr id="subtr<?= $x; ?>" class="<?= ($line['planning_type']=='Partial') ? '' : 'd-none'; ?>">
                                                                    <td colspan="5">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <td>Size</td>
                                                                                    <td>Order Qty</td>
                                                                                    <td>Plan Qty</td>
                                                                                    <td>Plan For</td>
                                                                                    <td>Plan To</td>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php
                                                                            $ro = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = '". $result['sod_combo'] ."'");
                                                                                while($row = mysqli_fetch_array($ro)) {
                                                                                $ssd = $row['id'];
                                                                                $nrw = mysqli_query($mysqli, "SELECT * FROM line_planning_size WHERE line_planning_id = '". $line['id'] ."' AND sod_size = ". $row['id']);

                                                                                $siqq = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE sod_size = '". $row['id'] ."' AND sod_part = ". $result['id']));

                                                                                if(mysqli_num_rows($nrw) > 0) {
                                                                                while($sizz = mysqli_fetch_array($nrw)) {
                                                                                ?>
                                                                                <tr id="size_tr_<?= $ssd; ?>">
                                                                                    <td id="variation_value_name<?= $ssd; ?>">
                                                                                        <input type="hidden" name="size_planned<?= $sp; ?>[]" value="<?= $sizz['id']; ?>" >
                                                                                        <input type="hidden" name="sod_size<?= $sp; ?>[]" id="sod_size<?= $ssd; ?>" value="<?= $row['id']; ?>" >
                                                                                        <input type="hidden" name="variation_value<?= $sp; ?>[]" id="variation_value<?= $ssd; ?>" value="<?= $row['variation_value']; ?>" >
                                                                                        <input type="hidden" name="order_qty<?= $sp; ?>[]" class="size_qty<?= $ssd; ?>" id="size_qty<?= $ssd; ?>" value="<?= $row['size_qty']; ?>">
                                                                                        <?= variation_value($row['variation_value']); ?></td>
                                                                                    <td><input type="hidden" name="order_qty<?= $sp; ?>[]" class="size_qty<?= $ssd; ?>" id="size_qty<?= $ssd; ?>" value="<?= $row['size_qty']; ?>"><?= $row['size_qty']; ?></td>
                                                                                    <td>
                                                                                        <input class="form-control plan_qty sameSize<?= $sp.$ssd; ?>" type="number" id="plan_qty<?= $ssd; ?>"  name="plan_qty<?= $sp; ?>[]" value="<?= $sizz['plan_qty'] ? $sizz['plan_qty'] : $row['size_qty']; ?>" onkeyup="sameSize(<?= $ssd; ?>, <?= $sp; ?>)" style="width: 50%;" required></td>
                                                                                    <td>
                                                                                        <select class="form-control custom-select2 assign_typeadd"  id=""  name="assign_type_sub<?= $sp; ?>[]" style="width:100%;" required>
                                                                                            <option value="line">Line</option>
                                                                                            <option value="employee" <?= ($sizz['assign_type'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
                                                                                            <option value="unit" <?= ($sizz['assign_type'] == 'unit') ? 'selected' : ''; ?>>Unit</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="form-control custom-select2 assign_toadd" name="assign_to_sub<?= $sp; ?>[]" style="width:80%;" required>
                                                                                            <option value="">Select <?= $sizz['assign_type'] ? $sizz['assign_type'] : 'Line'; ?></option>
                                                                                            <?php if($sizz['assign_type']=='employee') { print select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sizz['assign_to'], 'WHERE is_active="active"', '`'); } 
                                                                                                else if($sizz['assign_type']== 'unit') { print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sizz['assign_to'], 'WHERE type = 2 AND is_active="active"', '`'); } 
                                                                                                else { print select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', $sizz['assign_to'], 'WHERE is_active="active"', '`'); }
                                                                                            ?>
                                                                                             </select>                                                                                        
                                                                                       
                                                                                    <a class="border border-secondary rounded text-secondary" onclick="add_newsize_row(<?= $ssd; ?>, <?= $sp; ?>)">
                                                                                        <i class="fa fa-plus"></i>
                                                                                    </a>    
                                                                                                                                             
                                                                                    </td>                                                                                  
                                                                                </tr>
                                                                                <?php } } else { ?>
                                                                                <tr id="size_tr_<?= $ssd; ?>">
                                                                                    <td id="variation_value_name<?= $ssd; ?>">
                                                                                        <input type="hidden" name="size_planned<?= $sp; ?>[]" value="<?= $sizz['id']; ?>" >
                                                                                        <input type="hidden" name="sod_size<?= $sp; ?>[]" id="sod_size<?= $ssd; ?>" value="<?= $row['id']; ?>" >
                                                                                        <input type="hidden" name="variation_value<?= $sp; ?>[]" id="variation_value<?= $ssd; ?>" value="<?= $row['variation_value']; ?>" >
                                                                                        <input type="hidden" name="order_qty<?= $sp; ?>[]" class="size_qty<?= $ssd; ?>" id="size_qty<?= $ssd; ?>" value="<?= ($_GET['typ']=='order') ? $row['size_qty'] : $siqq['pcs_per_bundle']; ?>">
                                                                                        <?= variation_value($row['variation_value']); ?></td>
                                                                                    <td><?= ($_GET['typ']=='order') ? $row['size_qty'] : $siqq['pcs_per_bundle']; ?></td>
                                                                                    <td><input class="form-control plan_qty sameSize<?= $sp.$ssd; ?>" type="number" id="plan_qty<?= $ssd; ?>"  name="plan_qty<?= $sp; ?>[]" value="<?= $sizz['plan_qty'] ? $sizz['plan_qty'] : $row['size_qty']; ?>" onkeyup="sameSize(<?= $ssd; ?>, <?= $sp; ?>)" style="width: 50%;"></td>
                                                                                    <td>
                                                                                        <select class="form-control custom-select2 assign_typeadd"  id=""  name="assign_type_sub<?= $sp; ?>[]" style="width:100%;">
                                                                                            <option value="line">Line</option>
                                                                                            <option value="employee" <?= ($sizz['assign_type'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
                                                                                            <option value="unit" <?= ($sizz['assign_type'] == 'unit') ? 'selected' : ''; ?>>Unit</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="form-control custom-select2 assign_toadd" name="assign_to_sub<?= $sp; ?>[]" style="width:80%;">
                                                                                            <option value="">Select <?= $sizz['assign_type'] ? $sizz['assign_type'] : 'Line'; ?></option>
                                                                                            <?php if($sizz['assign_type']=='employee') { print select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $sizz['assign_to'], 'WHERE is_active="active"', '`'); } 
                                                                                                else if($sizz['assign_type']== 'unit') { print select_dropdown('company', array('id', 'company_name'), 'company_name ASC', $sizz['assign_to'], 'WHERE type = 2 AND is_active="active"', '`'); } 
                                                                                                else { print select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', $sizz['assign_to'], 'WHERE is_active="active"', '`'); }
                                                                                            ?>
                                                                                        </select>                                                                                        
                                                                                        <a class="border border-secondary rounded text-secondary" onclick="add_newsize_row(<?= $ssd; ?>, <?= $sp; ?>)"><i class="fa fa-plus"></i></a>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php }} ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            <?php $x++; }  ?>
                                                                  <tr>
                                                                    <td colspan="5" class="text-center">
                                                                        <a class="btn btn-outline-primary saveBtn"><i class="fa fa-save"></i> Save Line Planning</a>
                                                                    </td>
                                                                </tr>
                                                        </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $x++;
                                } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php
                include('includes/footer.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $("#planning_type").change(function() {

            var nn =  $(this).val();
            
            var currentURL = window.location.href;

            var nkk = currentURL.split('&typ=');
            
            if(nkk[1]!=nn) {
                $("#overlay").fadeIn(100);
                setTimeout(function() {
                    var updatedURL = currentURL.replace(nkk[1], nn);
                    window.location.href = updatedURL;
                }, 500);
            }
        });
    </script>

    <script>
        function change_supplier_unit(id) {
            
            $("#overlay").fadeIn(100);
            var planningtype = $("#planningtype" + id).val();
            if(planningtype=='Full') {
                $("#subtr" + id).addClass('d-none');
                $(".qty-div" + id).removeClass('d-none');
                $(".ass_to-div" + id).removeClass('d-none');
                $(".ass_type-div" + id).removeClass('d-none');
            } else if(planningtype=='Partial') {
                $("#subtr" + id).removeClass('d-none');
                $(".qty-div" + id).addClass('d-none');
                $(".ass_to-div" + id).addClass('d-none');
                $(".ass_type-div" + id).addClass('d-none');
            }
            
            $("#overlay").fadeOut(500);
        }
    </script>

    <script>
        function sameSize(ssd, sp) {
            var max_qty = $(".size_qty" + ssd).val();
          //  alert(max_qty);
            
            var actual = 0;
            
            $(".sameSize" + sp + ssd).each(function() {
                actual += parseInt($(this).val());
                //alert(actual);
                if(actual>max_qty) {
                    $(this).val(0).select().focus();
                    message_noload('error', 'Quantity Exceed!', 2000);
                    return false;
                }
            });
        }
    </script>

    
    <script>
        $("#plan_qty").keyup(function() {
            
            var val = $(this).val();
            var max = $("#sod_part option:selected").val();
            var six = $("#sod_size option:selected").data('qty');
            
            if(max=="" || six=="") {
                $(this).val('');
                return false;
            } else {
                if(parseInt(val)>parseInt(six)) {                    
                    $(this).val(six);
                    $(this).select();
                    message_noload('error', 'Quantity Exceed!');
                    return false;
                }
            }
        });

               
    </script>
    
    <script>
        $(".saveBtn").click(function() {           
         
            var form = $("#planningFrom").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax_action2.php?save_linePlanning=1',
                data: form,                
                success: function(msg) {
                    var json = $.parseJSON(msg);                    
                    if(json.result==0) {
                        // $("#overlay").fadeOut(500);
                        message_reload('success', 'Saved', 2000);
                        return false;
                    }
                }
            });
        });
    </script>
    
    <script>
        $("#assign_type").change(function() {
            var val = $(this).val();
            
            var line = '<option value="">Select Line</option><?= select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
            var empl = '<option value="">Select Employee</option><?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
            var unit = '<option value="">Select Unit</option><?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', 'WHERE type = 2 AND is_active="active"', '`'); ?>';            
            if(val=='line') {
                $("#assign_to").html(line);
            } else if(val=='employee') {
                $("#assign_to").html(empl);
            } else if(val=='unit') {
                $("#assign_to").html(unit);
            } else {
                $("#assign_to").html('');
            }
        });
    </script>

<script>
        $(".assign_typeadd").change(function() {
        var val = $(this).val();
        var container = $(this).closest('tr');
        var selectTo = container.find(".assign_toadd");

        var line = '<?= select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
        var empl = '<?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
        var unit = '<?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', 'WHERE type = 2 AND is_active="active"', '`'); ?>';

        if(val == 'line') {
            selectTo.html('<option value="">Select Line</option>' + line);
        } else if(val == 'employee') {
            selectTo.html('<option value="">Select Employee</option>' + empl);
        } else if(val == 'unit') {
            selectTo.html('<option value="">Select Unit</option>' + unit);
        } else {
            selectTo.html('');
        }
    });
    </script>
    
    <script>
        function part_plan_addfornew(element) {
            var val = $(element).val();
            
            var container = $(element).closest('tr');
            var selectTo = container.find(".assign_toadd_for_new");

            var unit = '<?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', 'WHERE type=2 AND is_active="active"', '`'); ?>';
            var line = '<?= select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
            var employee = '<?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', 'WHERE is_active="active"', '`'); ?>';

            if(val == 'line') {
                selectTo.html('<option value="">Select Line</option>' + line);
            } else if(val == 'unit') {
                selectTo.html('<option value="">Select Unit</option>' + unit);
            } else if(val == 'employee') {
                selectTo.html('<option value="">Select Employee</option>' + employee);
            }
        }

    </script>
    
    <script>
        function part_plan_for(yid, idd) {
            
            var p_for = $("#"+ idd +"for_" + yid).val();
            
            var unit = '<option value="">Select Unit</option><?php echo select_dropdown('company', array('id', 'company_name'), 'company_name ASC', '', 'WHERE type=2 AND is_active="active"', '`'); ?>';
            
            var line = '<option value="">Select Line</option><?php echo select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
            
            var employee = '<option value="">Select Employee</option><?php echo select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', 'WHERE is_active="active"', '`'); ?>';
            
            if(p_for == 'line') {
                $("#"+ idd +"for_to" + yid).html(line);
            } else if(p_for == 'unit') {
                $("#"+ idd +"for_to" + yid).html(unit);
            } else if(p_for == 'employee') {
                $("#"+ idd +"for_to" + yid).html(employee);
            }
        }
    </script>

<script>
    function add_newsize_row(id, sp) {
    var size_qty_add = $("#size_qty" + id).val();
    var plan_qty_add = $("#plan_qty" + id).val();

    if (parseInt(size_qty_add) > parseInt(plan_qty_add)) {
        $("#overlay").fadeIn(100);

        var variation_value_add = $("#variation_value" + id).val();
        var variation_value_name = $("#variation_value_name" + id).text();
        var sod_size = $("#sod_size" + id).val();
        var rowId = randd();

        var html = '<tr id="' + rowId + '">';
        html += '<td>' + variation_value_name;
        html += '<input type="hidden" class="form-control" name="variation_value' + sp + '[]" value="' + variation_value_add + '">';
        html += '<input type="hidden" name="size_planned' + sp + '[]" value="">';
        html += '<input type="hidden" name="sod_size' + sp + '[]" value="' + sod_size + '">';
        html += '<input type="hidden" class="size_qty' + id + '" name="order_qty' + sp + '[]" value="' + size_qty_add + '"></td>';
        html += '<td>' + size_qty_add + '</td>';
        html += '<td><input class="form-control sameSize' + sp + id + '" type="number" name="plan_qty' + sp + '[]" onkeyup="sameSize(' + id + ', ' + sp + ')" placeholder="Planned Qty" style="width: 50%;"></td>';
        html += '<td><select onchange="part_plan_addfornew(this)" name="assign_type_sub' + sp + '[]" class="form-control custom-select2 assign_typeaddnew"><option value="">select</option><option value="line">Line</option><option value="employee">Employee</option><option value="unit">Unit</option></select></td>';
        html += '<td><select class="form-control custom-select2 assign_toadd_for_new" type="text" name="assign_to_sub' + sp + '[]" style="width: 80%;"></select> <a href="#" class="delete-btn border border-danger rounded text-danger text-center" title="Delete"><i class="fa fa-trash"></i></a></td>';
        html += '</tr>';

        $("#size_tr_" + id).after(html);
        $("#overlay").fadeOut(500);

        // Initialize select2 for the new row
        $(".custom-select2").select2();

        // Attach event listener to the delete button of the newly added row
        $("#" + rowId + " .delete-btn").on("click", function(e) {
            e.preventDefault(); // Prevent default anchor behavior
            $(this).closest("tr").remove();
        });
    } else {
        message_noload('info', 'Quantity Reached!', 2000);
        return false;
    }
}

function randd() {
    return Math.floor(10000 + Math.random() * 90000);
}


</script>



</html>

















