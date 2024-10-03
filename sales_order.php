<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Customer';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id=" . $id));
} else {
    $comp = 'Add Customer';
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>

    <style>
        .head-wt td {
            width: 20% !important;
        }

        .table td,
        .table th {
            border-top: 0px solid #dee2e6 !important;
        }


        #tot_countBtn {
            display: block;
            position: fixed;
            top: 25%;
            right: 40px;
            z-index: 99;
            font-size: 25px;
            font-weight: bold;
            border: none;
            outline: none;
            background-color: #e6eaee;
            color: black;
            cursor: pointer;
            padding: 15px;
            border-radius: 4px;
        }

        .addicon {
            font-size: 17px !important;
            color: #5e5e5e;
        }

        input.master:focus {
            border-color: #d7c214 !important;
        }

        input {
            min-width: 100px !important;
        }

        .custom-select2 {
            min-width: 100px !important;
        }
        
        .btn-outline-info {
            color: #17a2b8 !important;
        }
        
        .btn-outline-info:hover {
            color: #fff !important;
        }
    </style>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Sales Order
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
                    
                    <?php page_spinner(); if(SALES_ORDER_ADD!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">Sales Order
                            <!-- <span>85</span> -->
                            <a class="btn btn-outline-primary" href="sales_order_list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Order List</a>
                        </h4>
                        <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        <button onclick="topFunction()" id="tot_countBtn" title="Go to top">0</button>
                    </div>
                    
                    <?php
                    if (isset($_GET['id'])) {
                        $code = $sql['order_code'];
                    } else {
                        // print "SELECT order_code FROM sales_order WHERE fin_year = '". get_setting_val('FIN_YEAR') ."' ORDER BY id DESC";
                        $qryz = mysqli_query($mysqli, "SELECT order_code FROM sales_order WHERE fin_year = '". get_setting_val('FIN_YEAR') ."' ORDER BY id DESC");
                        $sqql = mysqli_fetch_array($qryz);
                        $numm = mysqli_num_rows($qryz);
                        if ($numm == 0) {
                            $code = '1';
                        } else {
                            $ex = explode('-', $sqql['order_code']);
                            
                        // //     // $value = $ex[1];
                        // //     // $intValue = (int) $value;
                        // //     // $newValue = $intValue + 1;
                        // //     // $code = $ex[0] . '-' . $newValue;
                            
                            if($ex[0]=='BO') {
                                $code = $ex[1]+1;
                            } else {
                                $code = $sqql['order_code']+1;
                            }
                            
                        }
                    }
                    ?>
                    
                    <form id="soForm" method="POST" enctype="multipart/form-data" action="ajax_action.php?saveSO">
                        <input type="hidden" name="salesOrderId" id="salesOrderId" value="<?= $sql['id'] ? $sql['id'] : ''; ?>">
                            
                        <div class="row">
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">BO No :</span>
                                <input type="text" name="so_id" id="so_id" onchange="ValidateOrderId()" value="<?= $sql['order_code'] ? $sql['order_code'] : $code; ?>" class="form-control" placeholder="Order Id" <?= (get_setting_val('FIN_YEAR') == 2023) ? '' : 'readonly'; ?> required>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">BO Date :</span> 
                                    <input type="date" value="<?= $sql['order_date'] ? $sql['order_date'] : date('Y-m-d'); ?>" name="order_date" id="order_date" class="form-control" onchange="calDycnt()" required>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">Delivery Date :</span>
                                <input type="date" name="deliveryDate" id="deliveryDate" class="form-control" autocomplete="off" value="<?= $sql['delivery_date'] ? $sql['delivery_date'] : ''; ?>" placeholder="Delivery Date" onchange="calDycnt()" required>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">Brand :</span>
                                
                                <?php if(MAS_BRAND_ADD==1) { ?>
                                    <!-- <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal" data-target="#brand-add-modal"></i> -->
                                <?php } ?>
                                
                                <select name="brand" id="brand" class="custom-select2 form-control" required>
                                    <?= select_dropdown('brand', array('id', 'brand_name'), 'brand_name ASC', $sql['brand'] ? $sql['brand'] : '', '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">Type :</span>
                                <?php if(MAS_SEL_TYPE_ADD==1) { ?>
                                    <!-- <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal" data-target="#selection-type-add-modal"></i> -->
                                <?php } ?>
                                <select name="type" id="type_" class="custom-select2 form-control" required>
                                    <?= select_dropdown('selection_type', array('id', 'type_name'), 'type_name ASC', $sql['type'] ? $sql['type'] : '', '', ''); ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">Buyer Approvals :</span>
                                <select name="buy_approvals[]" id="buy_approvals" class="custom-select2 form-control" required multiple>
                                    <?php
                                        $h = mysqli_query($mysqli, "SELECT * FROM mas_approval ORDER BY name ASC");
                                        while($ui0o = mysqli_fetch_array($h)) {
                                            $rt = in_array($ui0o['id'], explode(',', $sql['approvals'])) ? 'selected' : '';
                                            print '<option value="'. $ui0o['id'] .'" '. $rt .'>'. $ui0o['name'] .'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">Merchandiser :</span>
                                <!-- <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal" data-target="#merchand-add-modal"></i> -->
                                
                                <select name="merchand" id="merchand" class="custom-select2 form-control" required>
                                    <? //= select_dropdown('merchand_detail', array('id', 'merchand_name'), 'merchand_name ASC', $sql['merchandiser'] ? $sql['merchandiser'] : '', '', ''); ?>
                                    <?php
                                        $mch = "SELECT a.id as ids, a.merchand_code, b.id, b.employee_name ";
                                        $mch .= " FROM merchand_detail a ";
                                        $mch .= " LEFT JOIN employee_detail b ON a.merchand_name = b.id ";
                                        $mchQ = mysqli_query($mysqli, $mch);
                                        
                                        print '<option value="">Select</option>';
                                        while($merch = mysqli_fetch_array($mchQ)) {
                                            $sell = ($merch['ids']==$sql['merchandiser']) ? 'selected' : '';
                                            print '<option value="'. $merch['ids'] .'" data-merch_id="'. $merch['ids'] .'" '. $sell .'>'. $merch['employee_name'] .' - '. $merch['merchand_code'] .'</option>';
                                        }
                                    ?>
                                </select>
                                <input type="hidden" name="merch_id" id="merch_id" value="<?= $sql['merch_id']; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <span class="">Delivery Days :</span>
                                <input type="text" name="del_days" id="del_days" class="form-control" placeholder="Delivery Days" readonly style="background-color:white" value="<?= $sql['order_days']; ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">Time Sheet Template :</span>
                                <select name="template_id" id="template_id" class="custom-select2 form-control">
                                    <?= select_dropdown('time_management_template', array('id', 'temp_name'), 'temp_name ASC', $sql['template_id'], '', '') ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="fieldrequired">Currency :</span>
                                <select name="currency" id="currency" class="custom-select2 form-control" required>
                                    <?= select_dropdown('mas_currency', array('id', 'currency_name'), 'currency_name ASC', $sql['currency'], '', '') ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <span>Order File :</span>
                                <div class="d-flex">
                                    <input type="file" name="imagefile[]" id="imagefile" value="" class="form-control" style="width: 75%;" multiple=""> &nbsp;&nbsp;
                                    
                                    <?php if (!empty($sql['order_image'])) { ?>
                                        <a data-img="" onclick="orderfile(<?= $sql['id']; ?>)"><i class="icon-copy fa fa-eye" aria-hidden="true" title="View Order File"></i></a>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <span class="">Season :</span>
                                <input type="text" name="season" id="season" class="form-control" placeholder="Season" value="<?= $sql['season']; ?>">
                            </div>
                            
                            <div class="col-md-12" style="overflow-y: auto;">
                                
                                <hr>
                                <table class="table">
                                    <thead style="background-color: #d7d7d7;">
                                        <tr>
                                            <th>Style No</th>
                                            <th style="width: 8%;">Unit
                                                <?php if(MAS_UNIT_ADD==1) { ?>
                                                    <!-- <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal" data-target="#unit-add-modal"></i> -->
                                                <?php } ?>
                                            </th>
                                            <th>Excess %</th>
                                            <th>Qty Detail</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>PO Number</th>
                                            <th>Delivery Date</th>
                                            <th style="width: 8% !important;">Style Image</th>
                                            <th>Main Fabric</th>
                                            <th>GSM</th>
                                            <th>Style Desc</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['id'])) {
                                            
                                            $uii = "SELECT a.*, d.full_name, f.order_code, b.color_name ";
                                            $uii .= " FROM sales_order_detalis a ";
                                            $uii .= " LEFT JOIN unit d ON a.unit_id = d.id ";
                                            $uii .= " LEFT JOIN sales_order f ON a.sales_order_id=f.id ";
                                            $uii .= " LEFT JOIN color b ON a.color=b.id ";
                                            $uii .= " WHERE a.sales_order_id = '" . $_GET['id'] . "' ORDER BY a.id ASC ";
                                            
                                            $ui = mysqli_query($mysqli, $uii);
                                            
                                            $numm = mysqli_num_rows($ui);
                                            while ($uio = mysqli_fetch_array($ui)) {
                                                
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><?= $uio['style_no'] ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <input type="text" name="ed_style_no[]" id="ed_style_no<?= $uio['id'] ?>" class="form-control" value="<?= $uio['style_no'] ?>">
                                                        </div>
                                                    </td>
                                                    <td><?= $uio['full_name'] ?><input type="hidden" name="ed_unit[]" value="<?= $uio['unit_id']; ?>"></td>
                                                    <td>
                                                        <?= $uio['excess']; ?>
                                                        <input type="hidden" name="ed_excess[]" id="ed_excess<?= $uio['id'] ?>" class="form-control" value="<?= $uio['excess'] ?>">
                                                    </td>
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><a onclick="getviewdetails(<?= $uio['id'] ?>)"><span class="badge badge-secondary">View</span></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <a onclick="geteditdetails(<?= $uio['id'] ?>)"><span class="badge badge-secondary">Edit Size</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><input type="hidden" class="addedqty" value="<?= $uio['total_qty'] ?>" id=""><?= $uio['total_qty'] ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <input type="text" name="ed_qtty[]" id="ed_qtty<?= $uio['id'] ?>" class="form-control" value="<?= $uio['total_qty'] ?>" readonly>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><?= $uio['price'] ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <input type="text" name="ed_price[]" id="ed_price<?= $uio['id'] ?>" class="form-control" value="<?= $uio['price'] ?>" onkeyup="calEdamt(<?= $uio['id'] ?>)">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><?= $uio['po_num'] ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <input type="text" name="ed_po_num[]" id="ed_po_num<?= $uio['id'] ?>" class="form-control" value="<?= $uio['po_num'] ?>">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><?= $uio['delivery_date'] ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <input type="date" name="editdeliveryDate[]" id="editdeliveryDate" class="form-control" value="<?= $uio['delivery_date'] ?>">
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="Idivedit<?= $uio['id'] ?> image_head_tag" style="display:none" style="width:50%;">
                                                            <input type="file" name="edit_itenPic[]" class="form-control imagefield" id="edit_itenPic<?= $uio['id'] ?>" accept="image/*">
                                                            <small class="imagename">Accept Images Only</small>
                                                        </div>
                                                        <?= (!empty($uio['item_image'])) ? viewImage($uio['item_image'], 30) : '-'; ?>
                                                    </td>
                                                    
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><?= $uio['main_fabric'] ? fabric_name($uio['main_fabric']) : '-'; ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <select name="ed_main_fabric[]" id="ed_main_fabric" class="custom-select2 form-control" style="width:100%">
                                                                <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', $uio['main_fabric'], '', '') ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><?= $uio['gsm'] ? $uio['gsm'] : '-'; ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <input type="number" name="ed_gsm[]" id="ed_gsm" class="form-control" value="<?= $uio['gsm'] ?>">
                                                        </div>
                                                    </td>
                                                    
                                                    <td>
                                                        <div class="Idivshow<?= $uio['id'] ?>"><?= $uio['style_des'] ? $uio['style_des'] : '-'; ?></div>
                                                        <div class="Idivedit<?= $uio['id'] ?>" style="display:none">
                                                            <textarea class="form-control" name="ed_style_des[]" id="ed_style_des" placeholder="Style Description" style="height: 45px;width: 150px;"><?= $uio['style_des'] ?></textarea>
                                                        </div>
                                                    </td>
                                                    
                                                    <td style="width: 8%;">
                                                        <input type="hidden" name="can_update[]" id="can_update<?= $uio['id']; ?>">
                                                        <input type="hidden" name="editItemId[]" id="editItemId<?= $uio['id']; ?>" value="<?= $uio['id']; ?>">
                                                        
                                                        <?php //$nnm = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM bundle_details WHERE style=".$uio['id'] )); if($nnm == 0) { ?>
                                                            <i class="icon-copy fa fa-check correctIcon<?= $uio['id']; ?>" aria-hidden="true" style="display:none" onclick="onSave(<?= $uio['id']; ?>)"></i>
                                                            &nbsp;
                                                            <i class="icon-copy fa fa-close cancelIcon<?= $uio['id']; ?>" aria-hidden="true" style="display:none" title="Cancel" onclick="onClose(<?= $uio['id']; ?>)"></i>
                                                            &nbsp;
                                                            <i class="icon-copy fa fa-edit editicon<?= $uio['id']; ?>" aria-hidden="true" onclick="onEdit(<?= $uio['id']; ?>)" title="Edit" style="color: rgba(0, 249, 62, 0.95);"></i>
                                                            &nbsp;
                                                            <i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="delete_data(<?= $uio['id']; ?>, 'sales_order_detalis')" title="Delete" style="color: #ff5f5f;"></i>
                                                            &nbsp;
                                                        <?php //} ?>
                                                        <i class="icon-copy dw dw-copy" aria-hidden="true" onclick="onClone(<?= $uio['id']; ?>)" title="Clone" style="color: #ff33df;float:right;"></i>
                                                    </td>
                                                </tr>
                                                
                                            <?php }
                                        } else {
                                            $numm = 0;
                                        } ?>
                                        <tr id="fixedtr">
                                            <td><input type="text" class="form-control" placeholder="Style" name="styleno" id="styleno"></td>
                                            <td>
                                                <select name="unitt" id="unitts" class="custom-select2 form-control">
                                                    <?= select_dropdown('unit', array('id', 'full_name'), 'full_name ASC', '', '', ''); ?>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control" name="excess" placeholder="Excess %" id="excess"></td>
                                            <td><a class="sizemodal"><span class="badge badge-secondary">Add Size</span></a></td>
                                            <td><input type="text" name="quantity" id="quantity" class="form-control" value="0" readonly></td>
                                            <td><input type="number" name="price" placeholder="Price" id="price" class="form-control"></td>
                                            <td><input type="text" name="poNum" placeholder="PO Number" id="poNum" class="form-control"></td>
                                            <td><input type="text" name="deliv_date" placeholder="Delivery Date" autocomplete="off" id="deliv_date" class="form-control date-picker"></td>
                                            <td class="text-center image_head_tag">
                                                <input type="file" name="item_image" id="item_image" class="imagefield" style="max-width:100px" accept="image/*">
                                                <small class="imagename">Accept Images Only</small>
                                            </td>
                                            <td>
                                                <select name="main_fabric" id="main_fabric" class="custom-select2 form-control" style="width:100%">
                                                    <?= select_dropdown('fabric', array('id', 'fabric_name'), 'fabric_name ASC', '', '', '') ?>
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control" placeholder="GSM" name="gsm" id="gsm"></td>
                                            <td><textarea class="form-control" name="style_des" id="style_des" placeholder="Style Description" style="height: 45px;width: 150px;"></textarea></td>
                                            <td><input type="button" name="saveOrderNew" value="Add" onclick="return additem()" class="btn btn-secondary"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                        <!-- add qty detail modal -->
                        <div style="">
                            <div class="modal fade" id="size-modal" tabindex="-1" role="dialog"
                                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p class="modal-title" id="myLargeModalLabel">Quantity Detail Form</p>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <input type="hidden" name="form_id" id="form_id" value="1">
                                        </div>
                                        <div class="modal-body" id="modal-body" style="padding: 20px;">
                                            <div class="row" id="cloneBody" style="padding: 10px;border: 1px solid #d3cfcf;">
                                                <div class="col-md-12 text-center"><h5>Combo 1</h5><br></div>
                                                <div class="col-md-6">
                                                    <p id="newheading">Combo Name :</p>
                                                    <select class="form-control custom-select2 compNm" onchange="duplicate_check('compNm', 'Combo Name Already Selected!')" name="combo_name1" id="" style="width:100%">
                                                        <?= select_dropdown('color', array('id', 'color_name'), 'color_name ASC', '', '', ''); ?>
                                                    </select>
                                                    <input type="hidden" name="combo_id[]" value="1">
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <p id="newheading">Pack Type :</p>
                                                    <select class="form-control custom-select2 packNm" name="pack_name1" id="" style="width:100%">
                                                        <?= select_dropdown('mas_pack', array('id', 'pack_name'), 'pack_name ASC', '', '', ''); ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-12"><hr></div>
                                                <div class="col-md-6" id="partnamediv"></div>
                                                <div class="col-md-6" id="partcolordiv"></div>
                                                
                                                <div class="col-md-12 removeclone" style="padding: 20px;">
                                                    <hr>
                                                    <label for="">Size Range :</label>
                                                    <select name="variation_name" id="variation_name1" class="form-control custom-select2 sizz_rang" onchange="v_name(1)" style="width:100%">
                                                        <?= select_dropdown('variation', array('id', 'variation_name'), 'variation_name ASC', '', 'WHERE is_active="active"', ''); ?>
                                                    </select>
                                                    
                                                    <input type="hidden" name="size_range_id" id="size_range_id1">
                                                </div>
                                                <div style="overflow-y: auto;border: 1px solid #dbdbdb;width: 100%;">
                                                    <table class="table">
                                                        <thead style="background-color: #d7d7d7;">
                                                            <tr>
                                                                <td colspan="2">Size</td>
                                                                <td>Qty</td>
                                                                <td>Excess %</td>
                                                                <td class="removeclone"> <i class="icon-copy fa fa-plus-circle" aria-hidden="true" onclick="addmoreQrydetail(1)"></i>
                                                                    <input type="hidden" id="new_sizeCount1" value="100">
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="getsizeRange1">
                                                            <tr><td colspan="4" class="text-center">No result found!</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="newComboo"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="temp_combo" id="temp_combo" value="2">
                                            <button type="button" class="btn btn-outline-info" onclick="cloneBody()"><i class="icon-copy fa fa-plus" aria-hidden="true"></i> Combo</button>
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" id="updateForm" class="btn btn-outline-primary">Submit</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <a href="sales_order_list.php" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</a>
                                <?php if ($numm == 0) { ?>
                                    <input type="button" onclick="return additem()" value="Save BO" class="btn btn-outline-primary">
                                <?php } else { ?>
                                <?php if(DEVELOPING==1) { ?>
                                    <a onclick="openTimeSheet()" class="btn btn-outline-info timesheerBtn">Create Time Sheet</a>
                                    <?php } ?>
                                    <input type="button" onclick=" return save_orderuiuu()" value="Save" class="btn btn-outline-primary">
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="modal fade" id="editsize-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myLargeModalLabel">Edit Quantity Detail</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">×</button>
                                    </div>
                                    <form method="POST" id="edit_var_modal_form" enctype="multipart/form-data">
                                        <div class="modal-body" id="edit_size_modal">
                                            <div class="row" >
                                                <table class="table">
                                                    <thead style="background-color: #d7d7d7;">
                                                        <tr>
                                                            <td>Part Name</td>
                                                            <td>Part Color</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="edit_table1"></tbody>
                                                </table>
                                                
                                                <table class="table">
                                                    <thead style="background-color: #d7d7d7;">
                                                        <tr>
                                                            <td>Size</td>
                                                            <td></td>
                                                            <td>Qty</td>
                                                            <td>Excess %</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="edit_var_modal"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" id="edititembtn" class="btn btn-success">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
            
            <!-- data-keyboard="false" data-backdrop="static" -->
            
            <div class="modal fade" id="sizeshow-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-top">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Quantity Detail</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form method="post" id="var_modalform" enctype="multipart/form-data">
                            
                            <div class="modal-body" id="sizeshow-body">
                                <div class="row" style="padding: 20px !important;">
                                    <table class="table">
                                        <thead style="background-color: #d7d7d7;">
                                            <th>Part Name</th>
                                            <th>Part Color</th>
                                        </thead>
                                        <tbody id="table1"></tbody>
                                    </table>
                                    <table class="table">
                                        <thead style="background-color: #d7d7d7;">
                                            <tr>
                                                <th>Size</th>
                                                <th>Qty</th>
                                                <th>Excess %</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sizze_dett"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="orderfile-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-top">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Order Files</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form method="post" id="var_modalform" enctype="multipart/form-data">
                            
                            <div class="modal-body">
                                <div class="row" id="">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody id="orderfile_space"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="timesheetModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-top">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Order Time Sheet</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form method="post" id="var_modalform" enctype="multipart/form-data">
                            
                            <div class="modal-body">
                                <div class="row" id="">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody id=""></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="color-add-modal-so" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add New Color</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form method="post" id="colorForm" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="table_name" id="table_name" value="color">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Color Name <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="color_name" id="color_name" placeholder="Color Name" required>
                                            <input type="hidden" id="color_name_for">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" onclick="save_color_so()" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="part-add-modal-so" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">New Part</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form method="post" id="part_addForm" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="table_name" id="table_name" value="part">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Part Name <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control valid_part_name" name="part_name" id="part_name" placeholder="Part Name" required>
                                            <input type="hidden" id="part_name_for">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" onclick="save_part_so()" class="btn btn-success btnPart">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php
            $modals = ["image-modal"];
            
            include('includes/footer.php');
            include('modals.php');
            ?>
            
        </div>
    </div>

    <?php include('includes/end_scripts.php'); ?>
    
    
    
    <script>
        $('#color-add-modal-so').on('shown.bs.modal', function () {
            $('#color_name').focus();
        });
        
        $('#part-add-modal-so').on('shown.bs.modal', function () {
            $('#part_name').focus();
        });
        
        function save_color_so() {
            
            var temp = $("#color_name_for").val();
            
            if ($("#color_name").val() == "") {
                message_noload('warning', 'Color Name Required!', 1000);
                return false;
            } else {
                var form = $("#colorForm").serialize()
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?save_color=1',
                    data: form,
                    success: function (msg) {
                        
                        var json = $.parseJSON(msg);
                        if (json.result == 'success') {
                            message_noload('success', 'Color Saved!', 1500);
                            
                            $("#color_name").val('');
                            $("#color_name_for").val('');
                            $("#color-add-modal-so").modal('hide');
                            
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_action.php?get_AddedColorVia_select=1&id=' + json.inid,
                                data: form,
                                success: function (msg) {
                                    
                                    $("#pack_color" + temp).html(msg);
                                }
                            })
                            
                        } else if (json.result == 'exists') {
                            message_noload('warning', 'Color Already Exists!', 1500);
                        }else {
                            message_error();
                        }
                    }
                })
            }
        }
        
        function save_part_so() {
            
            var temp = $("#part_name_for").val();
            
            if ($("#part_name").val() == "") {
                message_noload('warning', 'Part Name Required!', 1000);
                return false;
            } else {
                
                $(".btnPart").prop('disabled', true).html('<i class="fa fa-spinner"></i> Saving..');
                
                
                var form = $("#part_addForm").serialize()
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?save_part=1',
                    data: form,
                    success: function (msg) {
                        
                        var json = $.parseJSON(msg);
                        if (json.result == 'success') {
                            
                            message_noload('success', 'Part Saved!', 1500);
                            
                            $("#part_name").val('');
                            $("#part_name_for").val('');
                            $("#part-add-modal-so").modal('hide');
                            
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_action.php?get_AddedPartVia_select=1&id=' + json.inid,
                                data: form,
                                success: function (msg) {
                                    
                                    $("#pack_name" + temp).html(msg);
                                }
                            })
                            
                        } else if (json.result == 'exists') {
                            message_noload('warning', 'Part Already Exists');
                        } else {
                            message_error();
                        }
                    }
                })
            }
        }
        
        
        function showColorModal(id) {
            $("#color_name_for").val(id);
            $("#color-add-modal-so").modal('show');
        }
        
        function showPartModal(id) {
            $("#part_name_for").val(id);
            $("#part-add-modal-so").modal('show');
        }
    </script>
    
    <script>
        function openTimeSheet() {
            
            var app = $("#buy_approvals").val();
            var temp = $("#template_id").val();
            var order_id = <?= $_GET['id']; ?>;
            
            if(app=="") {
                message_noload('error', 'Select Approvals!', 1500);
                return false;
            } else if(temp=="") {
                message_noload('error', 'Select Time Sheet!', 1500);
                return false;
            } else {
                
                $(".timesheerBtn").text('Creating...');
                
                $.ajax({
                    type:'POST',
                    url:'ajax_action.php?createTimeSheet=1&temp_id='+ temp +'&approvals=' + app +'&order_id=' + order_id,
                    success : function(msg) {
                        alert(msg);
                    }
                })
                
                
                setTimeout(function() {
                    $(".timesheerBtn").text('Created');
                    // $("#timesheetModal").modal('show');
                },2000);
            }
            
        }
    </script>

    <script>
        function cloneBody() {
            
            $("#overlay").fadeIn(100);
            var temp_combo = $("#temp_combo").val();
            
            var form = $("#soForm").serialize();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?add_new_combo',
                data: form,
                
                success: function(msg) {
                    $(".newComboo").before(msg);
                    $("#temp_combo").val((parseInt(temp_combo) + 1));
                    
                    // $('.select22'+ temp_combo).each(function() {
                    //     $(this).select2();
                    // });

                    // $(".select22"+ temp_combo).each(function() {
                    $(".partName, .compNm, .packNm, .colorName, .sizz_rang").each(function() {
                        $(this).select2({
                            dropdownParent: $('#trid'+ temp_combo +'121'),
                            // dropdownParent: $('#size-modal'),
                        });
                    });
                    
                    $("#overlay").fadeOut(300);
                }
            })
        }
    </script>

    <script>
        function addmoreQrydetail(iid) {
            var cnt = $("#new_sizeCount" + iid).val();
            var i = parseInt(cnt) + 1;
            
            var variation_value = '<?= select_dropdown('variation_value', array('id', 'type'), 'type ASC', '', '', ''); ?>';
            
            var html = '<tr id="trid' + i + '"><td><select class="form-control custom-select2 select22 nw_sizee" name="variation_value_id' + iid + '[]" id="">'+ variation_value +'</select></td> <td>:</td> <td><input type="text" class="form-control varvalue valid" onkeyup="varvalue(' + i + ')" name="varvalue' + iid + '[]" id="varvalue' + i + '" value="0"></td> <td><input type="text" name="excess_per' + iid + '[]" id="excess_per' + i + '" placeholder="Excess %" class="form-control"></td> <td><i class="icon-copy fa fa-trash-o" aria-hidden="true" onclick="removeRow(' + i + ')" title="Remove"></i></td> </tr>';
            
            if ($("#size_range_id" + iid).val() == "") {
                message_noload('warning', 'Size Range Required!', 1000);
            } else {
                $("#getsizeRange" + iid).append(html);
                $("#new_sizeCount" + iid).val(i);
                
                $(".select22").each(function() {
                    $(this).select2();
                });
            }
        }
    </script>

    <script>
        function v_name(iid) {
            
            $("#overlay").fadeIn(100);
            var id = $("#variation_name" + iid).val();
            var excess = $("#excess").val();
            
            $("#size_range_id" + iid).val(id);
            
            var temp_id = $("#new_sizeCount" + iid).val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getsizeRange=1&id=' + id + '&excess=' + excess + '&temp_id=' + temp_id + '&iid=' + iid,
                
                success: function (msg) {
                    
                    $("#getsizeRange" + iid).html(msg);
                    
                    $("#overlay").fadeOut(500);
                    
                    $("#varvalue1").select();
                    $("#varvalue1").focus();
                }
            })
        }
    </script>

    <script>
        function removeRow(id) {
            $("#overlay").fadeIn(100);
            $("#trid" + id).remove();
            $("#overlay").fadeOut(500);
        }
    </script>

    <script>
        function onClone(id) {
            swal({
                title: 'Are you sure?',
                text: "Do you want to clone this Style?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Clone it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?clone_row=1&id=' + id + '&table=sales_order_detalis',
                        success: function (msg) {
                            var json = $.parseJSON(msg);
                            if (json.result == 'success') {
                                swal({
                                    type: 'success',
                                    title: 'Style Cloned',
                                    showConfirmButton: true,
                                    timer: 1500
                                }).then(
                                    function () {
                                        location.reload();
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

    <script>
        function calEdamt(id) {
            var a = $("#ed_qtty" + id).val();
            var b = $("#ed_price" + id).val();

            sd = a * b;

            $("#ed_total" + id).val(sd);
        }
    </script>

    <script>
        $("#edititembtn").click(function () {

            // var a = $("#edit_var_modal_form").serialize();

            // $.ajax({
            //     type: 'POST',
            //     url: 'ajax_action.php?edititembtn=1',
            //     data: a,
            //     success: function (msg) {
            //         var json = $.parseJSON(msg);
            //         if (json.result == 'saved') {
                        $("#editsize-modal").modal('hide');
                //     } else {
                //         alert('2');
                //     }
                // }
            // })
        })
    </script>

    <script>
        function geteditdetails(id) {
            
            $("#overlay").fadeIn(100);
            var style_id = $("#ed_styleno" + id).val();
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getedit_mod=1&id=' + id + '&style_id=' + style_id,
                success: function (msg) {
                    
                    $("#edit_size_modal").html(msg);
                    
                    $(".select22").each(function() {
                        $(this).select2();
                    });
                    
                    // var json = $.parseJSON(msg);
                    // $("#edit_var_modal").html(json.table2);
                    // $("#edit_table1").html(json.table1);
                }
            })

            if (id != "") {
                $("#overlay").fadeOut(500);
                $("#editsize-modal").modal('show');
            }
        }
    </script>

    <script>
        function onSave(id) {
            swal({
                title: 'Are you sure?',
                text: "Confirm Save this Style!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    u = $("#can_update" + id).val('1');
                    if (u) {
                        $("#overlay").fadeIn(100);
                        $("#soForm").submit();
                    }
                } else {
                    swal(
                        'Cancelled',
                        '',
                        'error'
                    )
                }
            })
        }

        function onEdit(id) {
            geteditdetails(id);
            $(".editicon" + id).hide();
            $(".cancelIcon" + id).show();
            $(".correctIcon" + id).show();

            $(".Idivshow" + id).hide();
            $(".Idivedit" + id).show();
        }

        function onClose(id) {
            $(".editicon" + id).show();
            $(".cancelIcon" + id).hide();
            $(".correctIcon" + id).hide();

            $(".Idivshow" + id).show();
            $(".Idivedit" + id).hide();

            $("#can_update" + id).val('');
        }
    </script>

    <script>
        function showimage(id) {
            var val = $("#dsff" + id).val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?showimage=1&img=' + val,

                success: function (msg) {
                    $("#img_space").html(msg);
                }
            })

            $("#image-modal").modal('show');
        };
    </script>

    <script>
        function orderfile(id) {
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?sorderfile=1&id=' + id,

                success: function (msg) {
                    $("#orderfile_space").html(msg);
                }
            })

            $("#orderfile-modal").modal('show');
        }
    </script>

    <script>
        function save_orderuiuu() {
            var styleno = $("#styleno").val();
            var quantity = $("#quantity").val();
            var price = $("#price").val();
            var total = $("#total").val();
            var unitt = $("#unitts").val();
            var deliv_date = $("#deliv_date").val();
            var salesOrderId = $("#salesOrderId").val();
            var aa = $("#item_id").val();

            if (styleno != "") {
                if (unitt == "") {
                    errormessage('error', 'Unit Required!');
                    $("#styleno").focus();
                    return false;
                } else if (quantity == 0) {
                    errormessage('error', 'Add Quantity!');
                    $('#size-modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                    $("#size-modal").modal('show');
                    return false;
                } else if (price == "") {
                    errormessage('error', 'Price Required!');
                    $("#price").focus();
                    return false;
                } else if (deliv_date == "") {
                    errormessage('error', 'Delivery Date Required!');
                    $("#deliv_date").focus();
                    return false;
                } else {
                    $("#soForm").submit();
                }
            } else {
                $("#overlay").fadeIn(100);
                $("#soForm").submit();
            }
        }
    </script>

    <script>
        function getviewdetails(id) {

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getviewdetails=1&id=' + id,

                success: function (msg) {
                    // var json = $.parseJSON(msg);

                    $("#sizeshow-body").html(msg);
                    
                    // $("#sizze_dett").html(json.table2);
                    // $("#table1").html(json.table1);
                }
            })

            $("#sizeshow-modal").modal('show');
        }

        $(document).ready(function () {
            tot_countBtn();
        });

        function tot_countBtn() {
            var a = $(".varvalue");
            
            var asc = 0;
            $(".addedqty").each(function () {
                asc += +$(this).val();
            });
            
            $("#tot_countBtn").html(asc);
        }
        
    </script>

    <script>
        
        $(".varvalue").keyup(function() {
            alert();
        });
    </script>

    <script>
        $("#updateForm").click(function () {
            check_size_modal();
        });
        
        function check_size_modal() {
            
            var i = 0;
            
            var array = { 'partName': 'Part Name', 'colorName': 'Color Name', 'varvalue': 'Qty' };
            
            Object.entries(array).forEach(function([key, value]) {
                $("." + key).each(function() {
                    var val = $(this).val();
                    
                    if (val == "") {
                        i++;
                        $(this).focus();
                        message_noload('error', '' + value + ' Required!', 1500);
                        return false;
                    }
                });
            });
            
            var varvalue = $(".varvalue").length;
            
            if(varvalue==0) {
                i++;
                message_noload('error', 'Add Size Details!', 2000);
                return false;
            }
            
            $(".varvalue").each(function() {
                
                var varvalue = $(this).val();
                
                if(varvalue <= 0) {
                    i++;
                    $(this).focus();
                    message_noload('error', 'Enter Correct Quantity!', 2000);
                    return false;
                }
            });
            
            $(".compNm").each(function() {
                var compNm = $(this).val();
                
                if(compNm=="") {
                    i++;
                    message_noload('error', 'Select Combo Name!', 2000);
                    return false;
                }
            });
            
            $(".packNm").each(function() {
                var packNm = $(this).val();
                
                if(packNm=="") {
                    i++;
                    message_noload('error', 'Select Pack Type!', 2000);
                    return false;
                }
            });
            
            if(i==0) {
                
                $("#size-modal").modal('hide');
                $("#unitts").focus();
            }
        }
    </script>
    
    <script>
        function validate_part(combo) {
            
            duplicate_check('dup_part' + combo, 'Part Already Selected!');
        }
        
        function duplicate_check(clas, noti) {
            
            var arr = [];
            var duplicatesFound = false;
            $("." + clas).each(function() {
                var value = $(this).val();
                if (arr.includes(value)) {
                    $(this).val('').trigger('change');
                    duplicatesFound = true;
                    return false;
                }
                arr.push(value);
            });
            
            if (duplicatesFound) {
                message_noload('error', '' + noti + '');
            }
        }
    </script>

    <script>
        $("#styleno").change(function () {
            var id = $(this).val();
            // getsizemodal(id);
        });

        $(".sizemodal").click(function () {
            var id = $("#unitts").val();
            if (id == "") {
                swal('Unit Required!', '', 'warning' ).then( function () { swal.close(); $("#unitts").focus(); });
            } else {
                getsizemodal(id);
            }
        })

        function getsizemodal(id) {
            var so_id = $("#so_id").val();
            var item_id = $("#item_id").val();
            var style_id = $("#styleno").val();

            var tmp = $("#unitTemp_id").val();
            if (id != tmp) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?getvariationmod=1&id=' + id + '&so_id=' + so_id + '&item_id=' + item_id + '&style_id=' + style_id,
                    success: function (msg) {
                        var json = $.parseJSON(msg);

                        $("#partnamediv").html(json.name);
                        $("#partcolordiv").html(json.color);
                        
                        $(".partName, .compNm, .packNm, .colorName, .sizz_rang").each(function() {
                            $(this).select2({
                                dropdownParent: $('#size-modal')
                            });
                        });
                        
                        // $("#variation_name").select2();
                    }
                })
            }
            if (id != "") {
                $("#overlay").fadeIn(100);
                
                $('#size-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });


                
                // $("#size-modal").modal('show');
                $("#overlay").fadeOut(500);
            }
        }


        $('#size-modal').on('shown.bs.modal', function () {
            $('#varvalue1').focus();
            $('#varvalue1').select();
        });


        function varvalue(id) {
            var a = $(".varvalue");

            var sum = 0;
            $(".varvalue").each(function () {
                sum += +$(this).val();
            });

            var c = $("#price").val();

            d = c * sum;

            $("#quantity").val(sum);
            $("#total").val(d);

            // calculateExcess(id);
        };

        function calculateExcess(id) {
            var excess = $("#excess").val();

            var a = $("#varvalue" + id).val();

            per = (excess / 100) * a;

            $("#excess_per" + id).val(per);
        }

        function edit_varvalue(id) {
            var a = $(".edit_varvalue" + id);

            var sum = 0;
            $(".edit_varvalue" + id).each(function () {
                sum += +$(this).val();
            });

            var c = $("#ed_price" + id).val();

            d = c * sum;

            $("#ed_qtty" + id).val(sum);
            $("#ed_total" + id).val(d);
        };
    </script>

    <script>
        $("#price").keypress(function (e) {
            if (e.which == 13) {
                additem();
            }
        });

        $("#deliv_date").keypress(function (e) {
            if (e.which == 13) {
                additem();
            }
        });

        // function itemrow() {
        //     var id = $("#salesOrderId").val();

        //     $.ajax({
        //         type: 'POST',
        //         url: 'ajax_search.php?itemrow=1&id=' + id,
        //         success: function (msg) {
        //             $("#fixedtr").before(msg);
        //         }
        //     })
        // }

        function errormessage(type, text) {
            swal({
                type: type,
                text: text,
                timer: 1500
            }).then(
                function () {
                    swal.close();
                })
        }

        function additem() {
            
            var styleno = $("#styleno").val();
            var quantity = $("#quantity").val();
            var price = $("#price").val();
            var total = $("#total").val();
            var unitt = $("#unitts").val();
            var deliv_date = $("#deliv_date").val();
            var salesOrderId = $("#salesOrderId").val();
            var item_image = $("#item_image").val();

            if (styleno == "") {
                $("#styleno").focus();
                errormessage('error', 'Style Number Required!');
                return false;
            } else if (item_image == "") {
                $("#item_image").focus();
                errormessage('error', 'Style Image Required!');
                return false;
            } else if (unitt == "") {
                $("#unitts").focus();
                errormessage('error', 'Unit Required!');
                return false;
            } else if (quantity == 0) {
                getsizemodal(unitt);
                errormessage('error', 'Add Quantity!');
                // $("#size-modal").modal('show');
                return false;
            } else if (price == "") {
                $("#price").focus();
                errormessage('error', 'Price Required!');
                return false;
            } else if (deliv_date == "") {
                $("#deliv_date").focus();
                errormessage('error', 'Delivery Date Required!');
                return false;
            } else {
                
                var i = 0;
                var array = { 'partName': 'Part Name', 'colorName': 'Color Name', 'varvalue': 'Qty' };
                Object.entries(array).forEach(function([key, value]) {
                    $("." + key).each(function() {
                        var val = $(this).val();
                        if (val == "") {
                            i++;
                            $(this).focus();
                            $("#size-modal").modal('show');
                            message_noload('error', '' + value + ' Required!', 1500);
                            return false;
                        }
                    });
                });
                
                var varvalue = $(".varvalue").length;
                
                if(varvalue==0) {
                    i++;
                    $("#size-modal").modal('show');
                    message_noload('error', 'Add Size Details!', 2000);
                    return false;
                }
                
                $(".varvalue").each(function() {
                    
                    var varvalue = $(this).val();
                    
                    if(varvalue <= 0) {
                        i++;
                        $(this).focus();
                        $("#size-modal").modal('show');
                        message_noload('error', 'Enter Correct Quantity!', 2000);
                        return false;
                    }
                });
                
                $(".compNm").each(function() {
                    var compNm = $(this).val();
                    
                    if(compNm=="") {
                        i++;
                        $("#size-modal").modal('show');
                        message_noload('error', 'Select Combo Name!', 2000);
                        return false;
                    }
                });
                
                $(".packNm").each(function() {
                    var packNm = $(this).val();
                    
                    if(packNm=="") {
                        i++;
                        $("#size-modal").modal('show');
                        message_noload('error', 'Select Pack Type!', 2000);
                        return false;
                    }
                });
                
                if(i==0) {
                    
                    $("#size-modal").modal('hide');
                    
                    var req = required_validation('soForm');
                    
                    if(req==0) {
                        $("#overlay").fadeIn(100);
                    }
                    $("#soForm").submit();
                    return true;
                }
            }
        }

        $("#quantity").keyup(function () {
            calculateTotal();
        });
        $("#price").keyup(function () {
            calculateTotal();
        });

        // function calculateTotal() {
        //     var a = $("#quantity").val();
        //     var b = $("#price").val();
        //     c = a * b;
        //     if (a > 0 && b > 0) {
        //         $("#total").val(c);
        //     }
        // }
    </script>

    <script>
        $(function () {
            $("#customer_name").autocomplete({
                source: "fetchData.php",
                select: function (event, ui) {
                    event.preventDefault();
                    $("#customer_id").val(ui.item.id);
                    $("#customer_name").val(ui.item.value);
                    $("#address1").val(ui.item.address1);
                    $("#address2").val(ui.item.address2);
                    $("#area").val(ui.item.area);
                    $("#state_name").val(ui.item.state_name);
                    $("#cities_name").val(ui.item.cities_name);
                    $("#gst_no").val(ui.item.gst_no);
                    $('#item_name').focus();

                    // save_order_();
                }
            });
        });
    </script>

    <script>
        $("#state").change(function () {
            var state = $("#state").val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_city=' + state,
                success: function (msg) {
                    $("#city").html(msg);
                }
            })
        })
    </script>

    <script>
        $(function () {
            $('#soForm').validate({
                errorClass: "help-block",
                rules: {
                    deliveryDate: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    brand: {
                        required: true
                    },
                    pack_type: {
                        required: true
                    },
                    po_number: {
                        required: true
                    },
                    order_date: {
                        required: true
                    },
                    so_id: {
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


    <script>
        function delete_data(id, table) {
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
                        url: 'ajax_action.php?delete_data=' + id + '&table=' + table,
                        success: function (msg) {
                            if (msg == 0) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    showConfirmButton: true,
                                    timer: 1500
                                }).then(
                                    function () {
                                        location.reload();
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
        };
    </script>


    <script>
        // Get the button
        let mybutton = document.getElementById("tot_countBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "block";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    
    <script>
        $("#brand").change(function() {
            var brand = $(this).val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getBrand_Approval=1&brand=' + brand,
                success: function (msg) {
                    
                    var json = $.parseJSON(msg);
                    
                    $("#buy_approvals").html(json.select);
                    
                    $("#template_id").html(json.temp_name);
                }
            })
        })
    </script>

    <script>
        function calDycnt() {
            var s = moment($("#order_date").val());
            var e = moment($("#deliveryDate").val());
            var m = e.diff(s, "days");
            
            $("#del_days").val(m);
        }
    </script>
    
    <script>
        function ValidateOrderId() {
            var val = $("#so_id").val();
            $.ajax({
                type:'POST',
                url : 'ajax_search.php?ValidateOrderId=' + val, 
                success : function(msg) {
                    var json = $.parseJSON(msg);
                    
                    if(json.msg == 1) {
                        message_noload('warning', 'BO Number Already Exist.', 1500);
                        $("#so_id").val('');
                        $("#so_id").focus();
                    }
                }
            })
        }
    </script>
    
    <script>
        $("#merchand").change(function() {
            
            var merch_id = $("#merchand option:selected").data('merch_id');
            
            $("#merch_id").val(merch_id);
        })
    </script>
    
</body>

</html>