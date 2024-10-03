<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$search_type = $_REQUEST['search_type'];

if ($search_type == 'getPermissionList') {

    $qryz =mysqli_query($mysqli, "SELECT * FROM user_group WHERE id=" . $_REQUEST['id']);
    $emp_det = mysqli_fetch_array($qryz);
    
    $numz = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM user_permissions WHERE value='1' AND user_group = '" . $_REQUEST['id'] ."'"));
    $groupId = $_REQUEST['id'];
    
    
    $perms = ["ADMIN_DASH", "EMPLOYEE_DASH", "PAYMENT_DASH", "WIP_DASH", "BUD_VS_ACT_DASH", "DEVELOPING", "APP_LOG", "BUNDLE_TRACK", "APP_PERMISSIONS", "MOD_TIME_MANAGEMENT", "MOD_SHEETS", "GROUP_SHEET", "TOBE_SENT", "MOD_APPROVALS", "BUDGET_APPROVAL",
            "SO_APPROVAL", "MOD_MERCHAND", "SALES_ORDER_ADD", "SALES_ORDER_EDIT", "SALES_ORDER_DELETE", "FABRIC_PROG", "FABRIC_PROG_ADD", "FABRIC_PROG_EDIT", "FABRIC_PROG_DELETE", "FABRIC_PROG_PRINT", "ACCESSORIES_PROG", "ACCESSORIES_PROG_ADD",
            "ACCESSORIES_PROG_EDIT", "ACCESSORIES_PROG_DELETE", "ACCESSORIES_PROG_PRINT", "BUDGET", "BUDGET_ADD", "BUDGET_EDIT", "BUDGET_DELETE", "BUDGET_VIEW", "BUDGET_FABRIC", "BUDGET_ACCESSORIES", "BUDGET_PRODUCTION", "MOD_PLANNING", "PROD_PLANNING",
            "MOD_FABRIC", "FAB_PO", "FAB_PO_ADD", "FAB_PO_EDIT", "FAB_PO_DELETE", "FAB_PO_RECEIPT", "FAB_PO_RECEIPT_ADD", "FAB_PO_RECEIPT_EDIT", "FAB_PO_RECEIPT_DELETE", "FAB_PO_POUT", "FAB_PO_POUT_ADD", "FAB_PO_POUT_EDIT", "FAB_PO_POUT_DELETE",
            "FAB_PO_INW", "FAB_PO_INW_ADD", "FAB_PO_INW_EDIT", "FAB_PO_INW_DELETE", "FAB_TRANSFER", "FAB_TRANSFER_ADD", "FAB_TRANSFER_EDIT", "FAB_TRANSFER_DELETE", "FAB_CANCEL", "FAB_CANCEL_ADD", "FAB_CANCEL_EDIT", "FAB_CANCEL_DELETE", "FAB_STOCK_GROUP",
            "FAB_STOCK_GROUP_ADD", "FAB_STOCK_GROUP_EDIT", "FAB_STOCK_GROUP_DELETE", "MOD_PRODUCTION", "CUTTING_QR", "CUTTING_QR_ADD", "CUTTING_QR_EDIT", "CUTTING_QR_DELETE", "CUTTING_QR_GENERATE", "CUTTING_QR_APPROVAL", "P_OUTWARD", "P_OUTWARD_BUN_SCAN",
            "P_OUTWARD_MAN_SCAN", "P_OUTWARD_ADD", "P_OUTWARD_EDIT", "P_OUTWARD_VIEW", "P_OUTWARD_DELETE", "INHOUSE", "INHOUSE_RECEIVE", "INHOUSE_DC_CREATE", "INHOUSE_DAILY_PROCESS", "INHOUSE_VIEW", "P_INWARD", "P_INWARD_ADD", "P_INWARD_EDIT",
            "P_INWARD_VIEW", "P_INWARD_DELETE", "SEW_INPUT", "SEW_INPUT_BUN_SCAN", "SEW_INPUT_MAN_SCAN", "SEW_INPUT_ADD", "SEW_INPUT_EDIT", "SEW_INPUT_VIEW", "SEW_OUTPUT", "SEW_OUTPUT_ADD", "SEW_OUTPUT_EDIT","CHECKING","CHECKING_ADD","CHECKING_EDIT",
            "IRONING", "IRONING_ADD", "IRONING_EDIT", "IRONING_VIEW", "PACKING", "PACKING_ADD", "PACKING_EDIT", "PACKING_VIEW", "COST_GENERATION", "COST_GENERATION_ADD", "COST_GENERATION_VIEW", "COST_GENERATION_EDIT", "COST_GENERATION_DELETE", "DISPATCH",
            "OCR", "MOD_QC", "QUALITY_APP", "MOD_HR", "UP_EMPLOYEE_TEMP", "UP_EMPLOYEE_TEMP_EDIT", "UP_EMPLOYEE_TEMP_DELETE", "UP_EMPLOYEE", "UP_EMPLOYEE_ADD", "UP_EMPLOYEE_EDIT", "UP_EMPLOYEE_DELETE", "UP_EMPLOYEE_LOGIN_INFO", "MOD_ACCOUNTS",
            "BILL_RECEIPT", "BILL_RECEIPT_ADD", "BILL_RECEIPT_EDIT", "BILL_RECEIPT_DELETE", "BILL_PASSING", "BILL_PASSING_ADD", "BILL_PASSING_EDIT", "BILL_PASSING_VIEW", "BILL_APPROVAL", "BILL_APPROVAL_VIEW", "BILL_APPROVAL_APPROVE", "PAYMENT_OUT",
            "PAYMENT_OUT_ADD", "PAYMENT_OUT_EDIT", "PAYMENT_OUT_DELETE", "MOD_REPORTS", "PROD_REPORTS", "RP_ORDER_TRACKING", "RP_ORDER_WISE_PRODUCTION", "RP_PRODUCTION_REGISTER", "RP_PROD_CUTTING_LEDGER", "RP_CT_BUNDLE_DETAILS", "RP_SEWING", "RP_CHECKING",
            "RP_FINISHING", "RP_LEFT_OVER", "RP_BILL", "RP_PAYMENT", "RP_PROD_STOCK", "FAB_REPORTS", "RP_FAB_STOCK", "HR_REPORTS", "RP_CHECK_INOUT", "MOD_MASTER", "MAS_COMPANY", "MAS_COMPANY_ADD", "MAS_COMPANY_EDIT", "MAS_COMPANY_VIEW", "MAS_COMPANY_DELETE",
            "MAS_CATEGORY", "MAS_CATEGORY_ADD", "MAS_CATEGORY_EDIT", "MAS_CATEGORY_VIEW", "MAS_CATEGORY_DELETE", "MAS_BRAND", "MAS_BRAND_ADD", "MAS_BRAND_EDIT", "MAS_BRAND_VIEW", "MAS_BRAND_DELETE", "MAS_SIZER", "MAS_SIZER_ADD", "MAS_SIZER_EDIT", 
            "MAS_SIZER_VIEW", "MAS_SIZER_DELETE", "MAS_COLOR", "MAS_COLOR_ADD", "MAS_COLOR_EDIT", "MAS_COLOR_VIEW", "MAS_COLOR_DELETE", "MAS_FABRIC", "MAS_FABRIC_ADD", "MAS_FABRIC_EDIT", "MAS_FABRIC_VIEW", "MAS_FABRIC_DELETE", "MAS_ITEM", "MAS_ITEM_ADD",
            "MAS_ITEM_EDIT", "MAS_ITEM_VIEW", "MAS_ITEM_DELETE", "MAS_SEL_TYPE", "MAS_SEL_TYPE_ADD", "MAS_SEL_TYPE_EDIT", "MAS_SEL_TYPE_VIEW", "MAS_SEL_TYPE_DELETE", "MAS_DEPARTMENT", "MAS_DEPARTMENT_ADD", "MAS_DEPARTMENT_EDIT", "MAS_DEPARTMENT_VIEW",
            "MAS_DEPARTMENT_DELETE", "MAS_PROCESS", "MAS_PROCESS_ADD", "MAS_PROCESS_EDIT", "MAS_PROCESS_VIEW", "MAS_PROCESS_DELETE", "MAS_SUBPROCESS", "MAS_SUBPROCESS_ADD", "MAS_SUBPROCESS_EDIT", "MAS_SUBPROCESS_VIEW", "MAS_SUBPROCESS_DELETE",
            "MAS_CUSTOMER", "MAS_CUSTOMER_ADD", "MAS_CUSTOMER_EDIT", "MAS_CUSTOMER_VIEW", "MAS_CUSTOMER_DELETE", "MAS_SUPPLIER", "MAS_SUPPLIER_ADD", "MAS_SUPPLIER_EDIT", "MAS_SUPPLIER_VIEW", "MAS_SUPPLIER_DELETE", "MAS_TAXMAIN", "MAS_TAXMAIN_ADD",
            "MAS_TAXMAIN_EDIT", "MAS_TAXMAIN_VIEW", "MAS_TAXMAIN_DELETE", "MAS_TAXSUB", "MAS_TAXSUB_ADD", "MAS_TAXSUB_EDIT", "MAS_TAXSUB_VIEW", "MAS_TAXSUB_DELETE", "MAS_UNIT", "MAS_UNIT_ADD", "MAS_UNIT_EDIT", "MAS_UNIT_VIEW", "MAS_UNIT_DELETE",
            "MAS_EXP_MAIN", "MAS_EXP_MAIN_ADD", "MAS_EXP_MAIN_EDIT", "MAS_EXP_MAIN_VIEW", "MAS_EXP_MAIN_DELETE", "MAS_EXP_SUB", "MAS_EXP_SUB_ADD", "MAS_EXP_SUB_EDIT", "MAS_EXP_SUB_VIEW", "MAS_EXP_SUB_DELETE", "MAS_MACHINE", "MAS_MACHINE_ADD",
            "MAS_MACHINE_EDIT", "MAS_MACHINE_VIEW", "MAS_MACHINE_DELETE", "MAS_MERCHAND", "MAS_MERCHAND_ADD", "MAS_MERCHAND_EDIT", "MAS_MERCHAND_VIEW", "MAS_MERCHAND_DELETE", "MAS_PART", "MAS_PART_ADD", "MAS_PART_EDIT", "MAS_PART_VIEW", "MAS_PART_DELETE",
            "MAS_APPROVAL", "MAS_APPROVAL_ADD", "MAS_APPROVAL_EDIT", "MAS_APPROVAL_VIEW", "MAS_APPROVAL_DELETE", "MAS_COMPONENT", "MAS_COMPONENT_ADD", "MAS_COMPONENT_EDIT", "MAS_COMPONENT_VIEW", "MAS_COMPONENT_DELETE", "MAS_YARN", "MAS_YARN_ADD",
            "MAS_YARN_EDIT", "MAS_YARN_VIEW", "MAS_YARN_DELETE", "MAS_ACCESSORIES_TYPE", "MAS_ACCESSORIES_TYPE_ADD", "MAS_ACCESSORIES_TYPE_EDIT", "MAS_ACCESSORIES_TYPE_DELETE", "MAS_ACCESSORIES", "MAS_ACCESSORIES_ADD", "MAS_ACCESSORIES_EDIT",
            "MAS_ACCESSORIES_DELETE", "MAS_TASK", "MAS_TASK_ADD", "MAS_TASK_EDIT", "MAS_TASK_DELETE", "MAS_DESIGNATION", "MAS_DESIGNATION_ADD", "MAS_DESIGNATION_EDIT", "MAS_DESIGNATION_DELETE", "MAS_CURRENCY", "MAS_CURRENCY_ADD", "MAS_CURRENCY_EDIT",
            "MAS_CURRENCY_DELETE", "MAS_CHECKING", "MAS_CHECKING_ADD", "MAS_CHECKING_EDIT", "MAS_CHECKING_DELETE", "MAS_QC", "MAS_QC_ADD", "MAS_QC_EDIT", "MAS_QC_DELETE", "MAS_DEFECT", "MAS_DEFECT_ADD", "MAS_DEFECT_EDIT", "MAS_DEFECT_DELETE", "MAS_STYLE",
            "MAS_STYLE_ADD", "MAS_STYLE_EDIT", "MAS_STYLE_DELETE", "MAS_LINE", "MAS_LINE_ADD", "MAS_LINE_EDIT", "MAS_LINE_DELETE", "MAS_BANK", "MAS_BANK_ADD", "MAS_BANK_EDIT", "MAS_BANK_DELETE", "MAS_NOTES", "MAS_NOTES_ADD", "MAS_NOTES_EDIT", 
            "MAS_NOTES_DELETE", "MAS_STG_ITEM", "MAS_STG_ITEM_ADD", "MAS_STG_ITEM_EDIT", "MAS_STG_ITEM_DELETE", "MAS_PACK", "MAS_PACK_ADD", "MAS_PACK_EDIT", "MAS_PACK_DELETE", "MOD_SETTINGS", "SET_TIMETEMP", "SET_TIMETEMP_ADD", "SET_TIMETEMP_EDIT",
            "SET_TIMETEMP_DELETE", "SET_ORBIDX_CONFIG", "ORBIDX_CONFIG_ADD", "ORBIDX_CONFIG_EDIT", "ORBIDX_CONFIG_DELETE", "PROD_DASH", "TEAM_TASK_CREATION", "ADMIN_MASTER", "MERCH_MASTER", "FAB_MASTER", "STORE_MASTER", "PROD_MASTER", "FAB_STOCK_OPENING",
            "FAB_STOCK_OPENING_ADD", "FAB_STOCK_OPENING_EDIT", "FAB_STOCK_OPENING_DELETE", "FAB_UNIT_DELIVERY", "FAB_UNIT_DELIVERY_ADD", "FAB_UNIT_DELIVERY_EDIT", "FAB_UNIT_DELIVERY_DELETE", "MAS_UOM_DELETE", "MAS_UOM_EDIT", "MAS_UOM_ADD", "MAS_UOM",
            "SET_APP_LOGO", "SET_FINANCEYEAR", "SET_TEAMTASK", "SET_DEPT_HEAD", "SET_CONFIG", "TEAM_TASK_LIST" ];
            
            
            
            // // Define arrays for sections and sub-sections
            // $sections = [
            //     ['id' => 'M011', 'name' => 'Dashboard'],
            //     ['id' => 'M01', 'name' => 'Approvals'],
            //     ['id' => 'M1', 'name' => 'Merchandiser'],
            //     ['id' => 'M2', 'name' => 'Planning'],
            //     ['id' => 'M02', 'name' => 'Fabric'],
            //     // Add more sections as needed
            // ];
            
            // $subSections = [
            //     'M011' => ['01' => 'Group Sheet', '02' => 'To be Sent Orders'],
            //     'M01' => ['01' => 'Budget Approval', '02' => 'Sales Order Approval'],
            //     'M1' => ['01' => 'Sales Order', '02' => 'Fabric Program', '03' => 'Accessories Program', '04' => 'Budget'],
            //     'M2' => ['01' => 'Fabric Planning', '02' => 'Production Planning', '03' => 'Store Planning', '04' => 'Line Planning'],
            //     'M02' => ['01' => 'Purchase Order', '02' => 'Purchase Receipt', '03' => 'Process Outward', '04' => 'Process Inward', '05' => 'Transfer', '06' => 'PO/DC Cancel', '07' => 'Stock Group'],
            //     // Add more sub-sections as needed
            // ];
            
            // // Function to generate checkboxes for sub-sections
            // function generateSubCheckboxes($mainId, $subId, $subName, $permissions) {
            //     $html = '<input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs'.$mainId.' sub_'.$subId.'" onclick="menuClick(\''.$subId.'\')" value="'.$permissions[$subId].'"'.check_perm($permissions[$subId], $groupId).'> '.$subName.'<br>';
            //     return $html;
            // }
            
            // // Generate HTML for sections and sub-sections
            // foreach ($sections as $section) {
            //     $mainId = $section['id'];
            //     $mainName = $section['name'];
            
            //     echo '<tr>';
            //     echo '<td rowspan="'.(count($subSections[$mainId]) + 1).'">';
            //     echo '<input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_'.$mainId.'" onclick="modClick(\''.$mainId.'\')" value="MOD_'.$mainName.'"'.check_perm('MOD_'.$mainName, $groupId).'> '.$mainName;
            //     echo '</td>';
            
            //     foreach ($subSections[$mainId] as $subId => $subName) {
            //         echo '<tr>';
            //         echo '<td>';
            //         echo generateSubCheckboxes($mainId, $subId, $subName, $permissions);
            //         echo '</td>';
            //         echo '<td>-</td>';
            //         echo '</tr>';
            //     }
            
            //     echo '</tr>';
            // }
        ?>
        
        <div class="row">
            <div class="col-md-6">
                <p>Assigned Permissions for <u><?= strtoupper($emp_det['group_name']) ?></u> Group</p>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <input type="checkbox" class="main_cbox" onclick="main_cbox()" id="cAll"> <label for="cAll">Check All</label>
                
                <p><span style="color: #b866ff;"><?= $numz; ?>/376</span> Assigned.</p>
            </div>

            <div class="col-md-12" style="overflow-y:auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="min-width: 150px;">Module Name</th>
                            <th style="min-width: 200px;">Menu Permission</th>
                            <th style="min-width: 300px;">Function Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dashboard</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="adminDash all_cbox" onclick="checkDashboard('admin', 'emp')" value="ADMIN_DASH" <?= check_perm('ADMIN_DASH', $groupId); ?>>Admin Dashboard <br>
                                <input type="checkbox" name="permission_cbox[]" class="empDash all_cbox" onclick="checkDashboard('emp', 'admin')" value="EMPLOYEE_DASH" <?= check_perm('EMPLOYEE_DASH', $groupId); ?>>Employee Dashboard <br><br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox" value="PAYMENT_DASH" <?= check_perm('PAYMENT_DASH', $groupId); ?>>Payment Dashboard
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox" value="PROD_DASH" <?= check_perm('PROD_DASH', $groupId); ?>>Production Dashboard<br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox" value="WIP_DASH" <?= check_perm('WIP_DASH', $groupId); ?>>WIP Dashboard
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox" value="BUD_VS_ACT_DASH" <?= check_perm('BUD_VS_ACT_DASH', $groupId); ?>>Budget Vs Actual<br>
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox" value="DEVELOPING" <?= check_perm('DEVELOPING', $groupId); ?>>Developing <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox" value="APP_LOG" <?= check_perm('APP_LOG', $groupId); ?>>App Log
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox" value="BUNDLE_TRACK" <?= check_perm('BUNDLE_TRACK', $groupId); ?>>Bundle Track
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox" value="APP_PERMISSIONS" <?= check_perm('APP_PERMISSIONS', $groupId); ?>> App Permissions
                            </td>
                        </tr>
                        
                        <!-- <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox" value="MOD_TIME_MANAGEMENT" <?= check_perm('MOD_TIME_MANAGEMENT', $groupId); ?>> Time Management</td>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox" value="TEAM_TASK_CREATION" <?= check_perm('TEAM_TASK_CREATION', $groupId); ?>> Team Task Creation</td>
                            <td>-</td>
                        </tr> -->

                        <?php $main = 'M0110'; $sub = $main.'01'; ?>
                        <!--time Management Start-->
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" value="MOD_TIME_MANAGEMENT" <?= check_perm('MOD_TIME_MANAGEMENT', $groupId); ?>> Time Management
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')"  value="TEAM_TASK_CREATION" <?= check_perm('TEAM_TASK_CREATION', $groupId); ?>> Team Task Creation
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="TEAM_TASK_LIST" <?= check_perm('TEAM_TASK_LIST', $groupId); ?>> Team Task Report
                            </td>
                        </tr>
                        
                        <?php $main = 'M011'; $sub = $main.'01'; ?>
                        <!--approvals Start-->
                        <tr>
                            <td rowspan="2">
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" value="MOD_SHEETS" <?= check_perm('MOD_SHEETS', $groupId); ?>> Sheets
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')"  value="GROUP_SHEET" <?= check_perm('GROUP_SHEET', $groupId); ?>> Group Sheet
                            </td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')"  value="TOBE_SENT" <?= check_perm('TOBE_SENT', $groupId); ?>> To be Sent Orders
                            </td>
                            <td>-</td>
                        </tr>
                        
                        <!--approvals end-->
                        
                        
                        <?php $main = 'M01'; $sub = $main.'01'; ?>
                        <!--approvals Start-->
                        <tr>
                            <td rowspan="2">
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" value="MOD_APPROVALS" <?= check_perm('MOD_APPROVALS', $groupId); ?>> Approvals
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')"  value="BUDGET_APPROVAL" <?= check_perm('BUDGET_APPROVAL', $groupId); ?>> Budget Approval
                            </td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')"  value="SO_APPROVAL" <?= check_perm('SO_APPROVAL', $groupId); ?>> Sales Order Approval
                            </td>
                            <td>-</td>
                        </tr>
                        
                        <!--approvals end-->
                        
                        <?php $main = 'M1'; $sub = $main.'01'; ?>
                        <!--Merchandiser Start-->
                        <tr>
                            <td rowspan="4">
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" value="MOD_MERCHAND" <?= check_perm('MOD_MERCHAND', $groupId); ?>> Merchandiser
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SALES_ORDER" <?= check_perm('SALES_ORDER', $groupId); ?>> Sales Order
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SALES_ORDER_ADD" <?= check_perm('SALES_ORDER_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SALES_ORDER_EDIT" <?= check_perm('SALES_ORDER_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SALES_ORDER_DELETE" <?= check_perm('SALES_ORDER_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox  mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FABRIC_PROG" <?= check_perm('FABRIC_PROG', $groupId); ?>> Fabric Program</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FABRIC_PROG_ADD" <?= check_perm('FABRIC_PROG_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FABRIC_PROG_EDIT" <?= check_perm('FABRIC_PROG_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FABRIC_PROG_DELETE" <?= check_perm('FABRIC_PROG_DELETE', $groupId); ?>> Delete
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FABRIC_PROG_PRINT" <?= check_perm('FABRIC_PROG_PRINT', $groupId); ?>> Print
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox  mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="ACCESSORIES_PROG" <?= check_perm('ACCESSORIES_PROG', $groupId); ?>> Accessories Program</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="ACCESSORIES_PROG_ADD" <?= check_perm('ACCESSORIES_PROG_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="ACCESSORIES_PROG_EDIT" <?= check_perm('ACCESSORIES_PROG_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="ACCESSORIES_PROG_DELETE" <?= check_perm('ACCESSORIES_PROG_DELETE', $groupId); ?>> Delete
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="ACCESSORIES_PROG_PRINT" <?= check_perm('ACCESSORIES_PROG_PRINT', $groupId); ?>> Print
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox  mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="BUDGET" <?= check_perm('BUDGET', $groupId); ?>> Budget
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BUDGET_ADD" <?= check_perm('BUDGET_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BUDGET_EDIT" <?= check_perm('BUDGET_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BUDGET_DELETE" <?= check_perm('BUDGET_DELETE', $groupId); ?>> Delete
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BUDGET_VIEW" <?= check_perm('BUDGET_VIEW', $groupId); ?>> View <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BUDGET_FABRIC" <?= check_perm('BUDGET_FABRIC', $groupId); ?>> Fabric Budget
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BUDGET_ACCESSORIES" <?= check_perm('BUDGET_ACCESSORIES', $groupId); ?>> Accessories Budget
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BUDGET_PRODUCTION" <?= check_perm('BUDGET_PRODUCTION', $groupId); ?>> Production Budget
                            </td>
                        </tr>
                        <!--Merchandiser end -->
                        
                        <tr><td colspan="3"></td></tr>
                        
                        <!--planning start-->
                        <?php $main = 'M2'; $sub = $main.'01'; ?>
                        
                        <tr>
                            <td rowspan="4"><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" value="MOD_PLANNING" <?= check_perm('MOD_PLANNING', $groupId); ?>> Planning</td>
                            <td>Fabric Planning</td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="PROD_PLANNING" <?= check_perm('PROD_PLANNING', $groupId); ?>> Production Planning
                            </td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        
                        <tr>
                            <td>Store Planning</td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="LINE_PLANNING" <?= check_perm('LINE_PLANNING', $groupId); ?>> Line Planning</td>
                            <td>-</td>
                        </tr>
                        <!--planning end-->
                        
                        <tr><td colspan="3"></td></tr>
                        
                        <!--FABRIC start-->
                        <?php $main = 'M02'; $sub = $main.'01'; ?>
                        
                        <tr>
                            <td rowspan="9"><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" value="MOD_FABRIC" <?= check_perm('MOD_FABRIC', $groupId); ?>> Fabric</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_PO" <?= check_perm('FAB_PO', $groupId); ?>> Purchase Order
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_ADD" <?= check_perm('FAB_PO_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_EDIT" <?= check_perm('FAB_PO_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_DELETE" <?= check_perm('FAB_PO_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_PO_RECEIPT" <?= check_perm('FAB_PO_RECEIPT', $groupId); ?>> Purchase Receipt
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_RECEIPT_ADD" <?= check_perm('FAB_PO_RECEIPT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_RECEIPT_EDIT" <?= check_perm('FAB_PO_RECEIPT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_RECEIPT_DELETE" <?= check_perm('FAB_PO_RECEIPT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_PO_POUT" <?= check_perm('FAB_PO_POUT', $groupId); ?>> Process Outward
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_POUT_ADD" <?= check_perm('FAB_PO_POUT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_POUT_EDIT" <?= check_perm('FAB_PO_POUT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_POUT_DELETE" <?= check_perm('FAB_PO_POUT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_PO_INW" <?= check_perm('FAB_PO_INW', $groupId); ?>> Process Inward
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_INW_ADD" <?= check_perm('FAB_PO_INW_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_INW_EDIT" <?= check_perm('FAB_PO_INW_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_PO_INW_DELETE" <?= check_perm('FAB_PO_INW_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'05'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_TRANSFER" <?= check_perm('FAB_TRANSFER', $groupId); ?>> Transfer
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_TRANSFER_ADD" <?= check_perm('FAB_TRANSFER_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_TRANSFER_EDIT" <?= check_perm('FAB_TRANSFER_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_TRANSFER_DELETE" <?= check_perm('FAB_TRANSFER_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'06'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_CANCEL" <?= check_perm('FAB_CANCEL', $groupId); ?>> PO/ DC Cancel
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_CANCEL_ADD" <?= check_perm('FAB_CANCEL_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_CANCEL_EDIT" <?= check_perm('FAB_CANCEL_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_CANCEL_DELETE" <?= check_perm('FAB_CANCEL_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'07'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_STOCK_GROUP" <?= check_perm('FAB_STOCK_GROUP', $groupId); ?>> Stock Group
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_STOCK_GROUP_ADD" <?= check_perm('FAB_STOCK_GROUP_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_STOCK_GROUP_EDIT" <?= check_perm('FAB_STOCK_GROUP_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_STOCK_GROUP_DELETE" <?= check_perm('FAB_STOCK_GROUP_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'08'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_STOCK_OPENING" <?= check_perm('FAB_STOCK_OPENING', $groupId); ?>> Stock Opening
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_STOCK_OPENING_ADD" <?= check_perm('FAB_STOCK_OPENING_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_STOCK_OPENING_EDIT" <?= check_perm('FAB_STOCK_OPENING_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="FAB_STOCK_OPENING_DELETE" <?= check_perm('FAB_STOCK_OPENING_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'09'; $p_name = 'FAB_UNIT_DELIVERY'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="<?= $p_name ?>" <?= check_perm($p_name, $groupId); ?>> Fabric Delivery
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="<?= $p_name; ?>_ADD" <?= check_perm('<?= $p_name; ?>_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="<?= $p_name; ?>_EDIT" <?= check_perm('<?= $p_name; ?>_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="<?= $p_name; ?>_DELETE" <?= check_perm('<?= $p_name; ?>_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        <!--fabric end-->
                        
                        <tr><td colspan="3"></td></tr>
                        
                        <!--production start-->
                        <?php $main = 'M3'; $sub = $main.'01'; ?>
                        
                        <tr>
                            <td rowspan="13"><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" value="MOD_PRODUCTION" <?= check_perm('MOD_PRODUCTION', $groupId); ?>> Procuction</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="CUTTING_QR" <?= check_perm('CUTTING_QR', $groupId); ?>> Cutting Barcode
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="CUTTING_QR_ADD" <?= check_perm('CUTTING_QR_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="CUTTING_QR_EDIT" <?= check_perm('CUTTING_QR_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="CUTTING_QR_DELETE" <?= check_perm('CUTTING_QR_DELETE', $groupId); ?>> Delete
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="CUTTING_QR_GENERATE" <?= check_perm('CUTTING_QR_GENERATE', $groupId); ?>> Generate QR
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="CUTTING_QR_APPROVAL" <?= check_perm('CUTTING_QR_APPROVAL', $groupId); ?>> Cutting Approval
                            </td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="P_OUTWARD" <?= check_perm('P_OUTWARD', $groupId); ?>> Process Outward
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_OUTWARD_BUN_SCAN" <?= check_perm('P_OUTWARD_BUN_SCAN', $groupId); ?>> Bundle Scanning
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_OUTWARD_MAN_SCAN" <?= check_perm('P_OUTWARD_MAN_SCAN', $groupId); ?>> Manual Entery <br> &nbsp; <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_OUTWARD_ADD" <?= check_perm('P_OUTWARD_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_OUTWARD_EDIT" <?= check_perm('P_OUTWARD_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_OUTWARD_VIEW" <?= check_perm('P_OUTWARD_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_OUTWARD_DELETE" <?= check_perm('P_OUTWARD_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="INHOUSE" <?= check_perm('INHOUSE', $groupId); ?>> Inhouse Process
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="INHOUSE_RECEIVE" <?= check_perm('INHOUSE_RECEIVE', $groupId); ?>> In-house Receive
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="INHOUSE_DC_CREATE" <?= check_perm('INHOUSE_DC_CREATE', $groupId); ?>> Create to DC
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="INHOUSE_DAILY_PROCESS" <?= check_perm('INHOUSE_DAILY_PROCESS', $groupId); ?>> Daily Process
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="INHOUSE_VIEW" <?= check_perm('INHOUSE_VIEW', $groupId); ?>> View
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'05'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="P_INWARD" <?= check_perm('P_INWARD', $groupId); ?>> Process Inward
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_INWARD_ADD" <?= check_perm('P_INWARD_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_INWARD_EDIT" <?= check_perm('P_INWARD_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_INWARD_VIEW" <?= check_perm('P_INWARD_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="P_INWARD_DELETE" <?= check_perm('P_INWARD_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'06'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SEW_INPUT" <?= check_perm('SEW_INPUT', $groupId); ?>> Sewing Input
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SEW_INPUT_BUN_SCAN" <?= check_perm('SEW_INPUT_BUN_SCAN', $groupId); ?>> Bundle Scanning
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SEW_INPUT_MAN_SCAN" <?= check_perm('SEW_INPUT_MAN_SCAN', $groupId); ?>> Manual Entery <br> &nbsp; <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SEW_INPUT_ADD" <?= check_perm('SEW_INPUT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SEW_INPUT_EDIT" <?= check_perm('SEW_INPUT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SEW_INPUT_VIEW" <?= check_perm('SEW_INPUT_VIEW', $groupId); ?>> View
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'07'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SEW_OUTPUT" <?= check_perm('SEW_OUTPUT', $groupId); ?>> Sewing Output
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SEW_OUTPUT_ADD" <?= check_perm('SEW_OUTPUT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SEW_OUTPUT_EDIT" <?= check_perm('SEW_OUTPUT_EDIT', $groupId); ?>> Edit
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'08'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="CHECKING" <?= check_perm('CHECKING', $groupId); ?>> Checking Entry
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="CHECKING_ADD" <?= check_perm('CHECKING_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="CHECKING_EDIT" <?= check_perm('CHECKING_EDIT', $groupId); ?>> Edit
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'09'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="IRONING" <?= check_perm('IRONING', $groupId); ?>> Ironing
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="IRONING_ADD" <?= check_perm('IRONING_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="IRONING_EDIT" <?= check_perm('IRONING_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="IRONING_VIEW" <?= check_perm('IRONING_VIEW', $groupId); ?>> View
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'10'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="PACKING" <?= check_perm('PACKING', $groupId); ?>> Packing
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="PACKING_ADD" <?= check_perm('PACKING_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="PACKING_EDIT" <?= check_perm('PACKING_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="PACKING_VIEW" <?= check_perm('PACKING_VIEW', $groupId); ?>> View
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'11'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="COST_GENERATION" <?= check_perm('COST_GENERATION', $groupId); ?>>Cost Generation</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="COST_GENERATION_ADD" <?= check_perm('COST_GENERATION_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="COST_GENERATION_VIEW" <?= check_perm('COST_GENERATION_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="COST_GENERATION_EDIT" <?= check_perm('COST_GENERATION_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="COST_GENERATION_DELETE" <?= check_perm('COST_GENERATION_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'12'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="DISPATCH" <?= check_perm('DISPATCH', $groupId); ?>>Dispatch</td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'13'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="OCR" <?= check_perm('OCR', $groupId); ?>>OCR</td>
                            <td>-</td>
                        </tr>
                        <!--Production end-->
                        
                        <!--QC start-->
                        <?php $main = 'M4'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="2"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="MOD_QC" <?= check_perm('MOD_QC', $groupId); ?>>QC</td>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="QUALITY_APP" <?= check_perm('QUALITY_APP', $groupId); ?>>Production QC</td>
                            <td>-</td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>Store QC</td>
                            <td>-</td>
                        </tr>
                        <!--QC end-->
                        
                        <!--hr start-->
                        <?php $main = 'M5'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="2"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="MOD_HR" <?= check_perm('MOD_HR', $groupId); ?>> HR</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="UP_EMPLOYEE_TEMP" <?= check_perm('UP_EMPLOYEE_TEMP', $groupId); ?>> Registered Employee
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="UP_EMPLOYEE_TEMP_EDIT" <?= check_perm('UP_EMPLOYEE_TEMP_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="UP_EMPLOYEE_TEMP_DELETE" <?= check_perm('UP_EMPLOYEE_TEMP_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="UP_EMPLOYEE" <?= check_perm('UP_EMPLOYEE', $groupId); ?>> Employee</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="UP_EMPLOYEE_ADD" <?= check_perm('UP_EMPLOYEE_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="UP_EMPLOYEE_EDIT" <?= check_perm('UP_EMPLOYEE_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="UP_EMPLOYEE_DELETE" <?= check_perm('UP_EMPLOYEE_DELETE', $groupId); ?>> Delete
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="UP_EMPLOYEE_LOGIN_INFO" <?= check_perm('UP_EMPLOYEE_LOGIN_INFO', $groupId); ?>> Login Info
                            </td>
                        </tr>
                        <!--hr end-->
                        
                        <!--Accounts Start-->
                        <?php $main = 'M6'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="4"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="MOD_ACCOUNTS" <?= check_perm('MOD_ACCOUNTS', $groupId); ?>>Accounts</td>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="BILL_RECEIPT" <?= check_perm('BILL_RECEIPT', $groupId); ?>>Bill Receipt</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_RECEIPT_ADD" <?= check_perm('BILL_RECEIPT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_RECEIPT_EDIT" <?= check_perm('BILL_RECEIPT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_RECEIPT_DELETE" <?= check_perm('BILL_RECEIPT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="BILL_PASSING" <?= check_perm('BILL_PASSING', $groupId); ?>>Production Bill Passing</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_PASSING_ADD" <?= check_perm('BILL_PASSING_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_PASSING_EDIT" <?= check_perm('BILL_PASSING_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_PASSING_VIEW" <?= check_perm('BILL_PASSING_VIEW', $groupId); ?>> View
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="BILL_APPROVAL" <?= check_perm('BILL_APPROVAL', $groupId); ?>>Bill Approval</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_APPROVAL_VIEW" <?= check_perm('BILL_APPROVAL_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="BILL_APPROVAL_APPROVE" <?= check_perm('BILL_APPROVAL_APPROVE', $groupId); ?>> Edit
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        <tr>
                            <td><input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="PAYMENT_OUT" <?= check_perm('PAYMENT_OUT', $groupId); ?>>Payment</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="PAYMENT_OUT_ADD" <?= check_perm('PAYMENT_OUT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="PAYMENT_OUT_EDIT" <?= check_perm('PAYMENT_OUT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="PAYMENT_OUT_DELETE" <?= check_perm('PAYMENT_OUT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <!--Reports-->
                        <?php $main = 'M7'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="3"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="MOD_REPORTS" <?= check_perm('MOD_REPORTS', $groupId); ?>>Reports</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="PROD_REPORTS" <?= check_perm('PROD_REPORTS', $groupId); ?>> Production Reports
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_ORDER_TRACKING" <?= check_perm('RP_ORDER_TRACKING', $groupId); ?>> Order Tracking <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_ORDER_WISE_PRODUCTION" <?= check_perm('RP_ORDER_WISE_PRODUCTION', $groupId); ?>> Order Wise Production Report <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_PRODUCTION_REGISTER" <?= check_perm('RP_PRODUCTION_REGISTER', $groupId); ?>> Production Register <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_PROD_CUTTING_LEDGER" <?= check_perm('RP_PROD_CUTTING_LEDGER', $groupId); ?>> Cutting Ledger <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_CT_BUNDLE_DETAILS" <?= check_perm('RP_CT_BUNDLE_DETAILS', $groupId); ?>> Cutting Bundle Details <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_SEWING" <?= check_perm('RP_SEWING', $groupId); ?>> Sewing Report <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_CHECKING" <?= check_perm('RP_CHECKING', $groupId); ?>> Checking Report <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_FINISHING" <?= check_perm('RP_FINISHING', $groupId); ?>> Finishing Report <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_LEFT_OVER" <?= check_perm('RP_LEFT_OVER', $groupId); ?>> Left Over Garment Report <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_BILL" <?= check_perm('RP_BILL', $groupId); ?>> Bill Report <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_PAYMENT" <?= check_perm('RP_PAYMENT', $groupId); ?>> Payment Report <br>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_PROD_STOCK" <?= check_perm('RP_PROD_STOCK', $groupId); ?>> Payment Report <br>
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="FAB_REPORTS" <?= check_perm('FAB_REPORTS', $groupId); ?>> Fabric Reports
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_FAB_STOCK" <?= check_perm('RP_FAB_STOCK', $groupId); ?>> Stock Report <br>
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="HR_REPORTS" <?= check_perm('HR_REPORTS', $groupId); ?>> HR Reports
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="RP_CHECK_INOUT" <?= check_perm('RP_CHECK_INOUT', $groupId); ?>> Check IN?OUT  <br>
                            </td>
                        </tr>
                        <!--reports end-->
                        
                        
                        <?php $main = 'M80'; $sub = $main.'01'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="MOD_MASTER" <?= check_perm('MOD_MASTER', $groupId); ?>>Masters <br>
                            </td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        
                        <!--admin masters start-->
                        <?php $main = 'M8'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="16">
                                <input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="ADMIN_MASTER" <?= check_perm('ADMIN_MASTER', $groupId); ?>>Admin Masters
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_COMPANY" <?= check_perm('MAS_COMPANY', $groupId); ?>> Company
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPANY_ADD" <?= check_perm('MAS_COMPANY_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPANY_EDIT" <?= check_perm('MAS_COMPANY_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPANY_VIEW" <?= check_perm('MAS_COMPANY_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPANY_DELETE" <?= check_perm('MAS_COMPANY_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_BRAND" <?= check_perm('MAS_BRAND', $groupId); ?>> Brand
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_BRAND_ADD" <?= check_perm('MAS_BRAND_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_BRAND_EDIT" <?= check_perm('MAS_BRAND_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_BRAND_VIEW" <?= check_perm('MAS_BRAND_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_BRAND_DELETE" <?= check_perm('MAS_BRAND_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_CUSTOMER" <?= check_perm('MAS_CUSTOMER', $groupId); ?>> Customer
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CUSTOMER_ADD" <?= check_perm('MAS_CUSTOMER_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CUSTOMER_EDIT" <?= check_perm('MAS_CUSTOMER_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CUSTOMER_VIEW" <?= check_perm('MAS_CUSTOMER_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CUSTOMER_DELETE" <?= check_perm('MAS_CUSTOMER_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_SUPPLIER" <?= check_perm('MAS_SUPPLIER', $groupId); ?>> Supplier
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUPPLIER_ADD" <?= check_perm('MAS_SUPPLIER_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUPPLIER_EDIT" <?= check_perm('MAS_SUPPLIER_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUPPLIER_VIEW" <?= check_perm('MAS_SUPPLIER_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUPPLIER_DELETE" <?= check_perm('MAS_SUPPLIER_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'05'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_UNIT" <?= check_perm('MAS_UNIT', $groupId); ?>> Unit
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_UNIT_ADD" <?= check_perm('MAS_UNIT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_UNIT_EDIT" <?= check_perm('MAS_UNIT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_UNIT_VIEW" <?= check_perm('MAS_UNIT_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_UNIT_DELETE" <?= check_perm('MAS_UNIT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'06'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_DEPARTMENT" <?= check_perm('MAS_DEPARTMENT', $groupId); ?>> Department
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DEPARTMENT_ADD" <?= check_perm('MAS_DEPARTMENT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DEPARTMENT_EDIT" <?= check_perm('MAS_DEPARTMENT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DEPARTMENT_VIEW" <?= check_perm('MAS_DEPARTMENT_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DEPARTMENT_DELETE" <?= check_perm('MAS_DEPARTMENT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'07'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_PROCESS" <?= check_perm('MAS_PROCESS', $groupId); ?>> Process
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PROCESS_ADD" <?= check_perm('MAS_PROCESS_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PROCESS_EDIT" <?= check_perm('MAS_PROCESS_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PROCESS_VIEW" <?= check_perm('MAS_PROCESS_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PROCESS_DELETE" <?= check_perm('MAS_PROCESS_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'08'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_SUBPROCESS" <?= check_perm('MAS_SUBPROCESS', $groupId); ?>> Sub Process
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUBPROCESS_ADD" <?= check_perm('MAS_SUBPROCESS_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUBPROCESS_EDIT" <?= check_perm('MAS_SUBPROCESS_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUBPROCESS_VIEW" <?= check_perm('MAS_SUBPROCESS_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SUBPROCESS_DELETE" <?= check_perm('MAS_SUBPROCESS_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'09'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_SEL_TYPE" <?= check_perm('MAS_SEL_TYPE', $groupId); ?>> Selection Type
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SEL_TYPE_ADD" <?= check_perm('MAS_SEL_TYPE_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SEL_TYPE_EDIT" <?= check_perm('MAS_SEL_TYPE_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SEL_TYPE_VIEW" <?= check_perm('MAS_SEL_TYPE_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SEL_TYPE_DELETE" <?= check_perm('MAS_SEL_TYPE_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'10'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_TASK" <?= check_perm('MAS_TASK', $groupId); ?>> Task
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TASK_ADD" <?= check_perm('MAS_TASK_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TASK_EDIT" <?= check_perm('MAS_TASK_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TASK_DELETE" <?= check_perm('MAS_TASK_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'11'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_DESIGNATION" <?= check_perm('MAS_DESIGNATION', $groupId); ?>> Designation
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DESIGNATION_ADD" <?= check_perm('MAS_DESIGNATION_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DESIGNATION_EDIT" <?= check_perm('MAS_DESIGNATION_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DESIGNATION_DELETE" <?= check_perm('MAS_DESIGNATION_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'12'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_STYLE" <?= check_perm('MAS_STYLE', $groupId); ?>> Style
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_STYLE_ADD" <?= check_perm('MAS_STYLE_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_STYLE_EDIT" <?= check_perm('MAS_STYLE_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_STYLE_DELETE" <?= check_perm('MAS_STYLE_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'13'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_BANK" <?= check_perm('MAS_BANK', $groupId); ?>> Bank
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_BANK_ADD" <?= check_perm('MAS_BANK_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_BANK_EDIT" <?= check_perm('MAS_BANK_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_BANK_DELETE" <?= check_perm('MAS_BANK_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'14'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_NOTES" <?= check_perm('MAS_NOTES', $groupId); ?>> Notes
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_NOTES_ADD" <?= check_perm('MAS_NOTES_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_NOTES_EDIT" <?= check_perm('MAS_NOTES_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_NOTES_DELETE" <?= check_perm('MAS_NOTES_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'15'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_STG_ITEM" <?= check_perm('MAS_STG_ITEM', $groupId); ?>> Stock Group Item
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_STG_ITEM_ADD" <?= check_perm('MAS_STG_ITEM_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_STG_ITEM_EDIT" <?= check_perm('MAS_STG_ITEM_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_STG_ITEM_DELETE" <?= check_perm('MAS_STG_ITEM_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'16'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_UOM" <?= check_perm('MAS_UOM', $groupId); ?>> UOM
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_UOM_ADD" <?= check_perm('MAS_UOM_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_UOM_EDIT" <?= check_perm('MAS_UOM_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_UOM_DELETE" <?= check_perm('MAS_UOM_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        <!--admin masters end-->

                        
                        <!--Merch masters Start-->
                        <?php $main = 'M9'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="15"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="MERCH_MASTER" <?= check_perm('MERCH_MASTER', $groupId); ?>>Merch Master</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_MERCHAND" <?= check_perm('MAS_MERCHAND', $groupId); ?>> Merchandiser
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MERCHAND_ADD" <?= check_perm('MAS_MERCHAND_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MERCHAND_EDIT" <?= check_perm('MAS_MERCHAND_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MERCHAND_VIEW" <?= check_perm('MAS_MERCHAND_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MERCHAND_DELETE" <?= check_perm('MAS_MERCHAND_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>

                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_SIZER" <?= check_perm('MAS_SIZER', $groupId); ?>> Size Range
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SIZER_ADD" <?= check_perm('MAS_SIZER_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SIZER_EDIT" <?= check_perm('MAS_SIZER_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SIZER_VIEW" <?= check_perm('MAS_SIZER_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_SIZER_DELETE" <?= check_perm('MAS_SIZER_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        <?php $sub = $main.'03'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_COLOR" <?= check_perm('MAS_COLOR', $groupId); ?>> Color
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COLOR_ADD" <?= check_perm('MAS_COLOR_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COLOR_EDIT" <?= check_perm('MAS_COLOR_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COLOR_VIEW" <?= check_perm('MAS_COLOR_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COLOR_DELETE" <?= check_perm('MAS_COLOR_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_PART" <?= check_perm('MAS_PART', $groupId); ?>> Part
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PART_ADD" <?= check_perm('MAS_PART_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PART_EDIT" <?= check_perm('MAS_PART_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PART_VIEW" <?= check_perm('MAS_PART_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PART_DELETE" <?= check_perm('MAS_PART_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'05'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_CATEGORY" <?= check_perm('MAS_CATEGORY', $groupId); ?>> Category
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CATEGORY_ADD" <?= check_perm('MAS_CATEGORY_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CATEGORY_EDIT" <?= check_perm('MAS_CATEGORY_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CATEGORY_VIEW" <?= check_perm('MAS_CATEGORY_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CATEGORY_DELETE" <?= check_perm('MAS_CATEGORY_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>      
                        
                        <?php $sub = $main.'06'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_ITEM" <?= check_perm('MAS_ITEM', $groupId); ?>> Item
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ITEM_ADD" <?= check_perm('MAS_ITEM_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ITEM_EDIT" <?= check_perm('MAS_ITEM_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ITEM_VIEW" <?= check_perm('MAS_ITEM_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ITEM_DELETE" <?= check_perm('MAS_ITEM_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>

                        <?php $sub = $main.'07'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_TAXMAIN" <?= check_perm('MAS_TAXMAIN', $groupId); ?>> Tax Main
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXMAIN_ADD" <?= check_perm('MAS_TAXMAIN_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXMAIN_EDIT" <?= check_perm('MAS_TAXMAIN_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXMAIN_VIEW" <?= check_perm('MAS_TAXMAIN_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXMAIN_DELETE" <?= check_perm('MAS_TAXMAIN_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>


                        <?php $sub = $main.'08'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_TAXSUB" <?= check_perm('MAS_TAXSUB', $groupId); ?>> Tax Sub
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXSUB_ADD" <?= check_perm('MAS_TAXSUB_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXSUB_EDIT" <?= check_perm('MAS_TAXSUB_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXSUB_VIEW" <?= check_perm('MAS_TAXSUB_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_TAXSUB_DELETE" <?= check_perm('MAS_TAXSUB_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'09'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_EXP_MAIN" <?= check_perm('MAS_EXP_MAIN', $groupId); ?>> Expense Main
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_MAIN_ADD" <?= check_perm('MAS_EXP_MAIN_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_MAIN_EDIT" <?= check_perm('MAS_EXP_MAIN_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_MAIN_VIEW" <?= check_perm('MAS_EXP_MAIN_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_MAIN_DELETE" <?= check_perm('MAS_EXP_MAIN_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'10'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_EXP_SUB" <?= check_perm('MAS_EXP_SUB', $groupId); ?>> Expense Sub
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_SUB_ADD" <?= check_perm('MAS_EXP_SUB_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_SUB_EDIT" <?= check_perm('MAS_EXP_SUB_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_SUB_VIEW" <?= check_perm('MAS_EXP_SUB_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_EXP_SUB_DELETE" <?= check_perm('MAS_EXP_SUB_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>


                        <?php $sub = $main.'11'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_MACHINE" <?= check_perm('MAS_MACHINE', $groupId); ?>> Machine
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MACHINE_ADD" <?= check_perm('MAS_MACHINE_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MACHINE_EDIT" <?= check_perm('MAS_MACHINE_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MACHINE_VIEW" <?= check_perm('MAS_MACHINE_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_MACHINE_DELETE" <?= check_perm('MAS_MACHINE_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        
                        <?php $sub = $main.'12'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_APPROVAL" <?= check_perm('MAS_APPROVAL', $groupId); ?>> Approval
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_APPROVAL_ADD" <?= check_perm('MAS_APPROVAL_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_APPROVAL_EDIT" <?= check_perm('MAS_APPROVAL_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_APPROVAL_VIEW" <?= check_perm('MAS_APPROVAL_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_APPROVAL_DELETE" <?= check_perm('MAS_APPROVAL_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'13'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_COMPONENT" <?= check_perm('MAS_COMPONENT', $groupId); ?>> Component
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPONENT_ADD" <?= check_perm('MAS_COMPONENT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPONENT_EDIT" <?= check_perm('MAS_COMPONENT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPONENT_VIEW" <?= check_perm('MAS_COMPONENT_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_COMPONENT_DELETE" <?= check_perm('MAS_COMPONENT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>

                        <?php $sub = $main.'14'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_CURRENCY" <?= check_perm('MAS_CURRENCY', $groupId); ?>> Currency
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CURRENCY_ADD" <?= check_perm('MAS_CURRENCY_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CURRENCY_EDIT" <?= check_perm('MAS_CURRENCY_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CURRENCY_DELETE" <?= check_perm('MAS_CURRENCY_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'15'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_PACK" <?= check_perm('MAS_PACK', $groupId); ?>> Pack Type
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PACK_ADD" <?= check_perm('MAS_PACK_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PACK_EDIT" <?= check_perm('MAS_PACK_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_PACK_DELETE" <?= check_perm('MAS_PACK_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>

                        <!--Merch masters end-->

                        
                        <!--fabric masters Start-->
                        <?php $main = 'M10'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="2"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="FAB_MASTER" <?= check_perm('FAB_MASTER', $groupId); ?>>Fabric Masters</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_FABRIC" <?= check_perm('MAS_FABRIC', $groupId); ?>> Fabric
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_FABRIC_ADD" <?= check_perm('MAS_FABRIC_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_FABRIC_EDIT" <?= check_perm('MAS_FABRIC_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_FABRIC_VIEW" <?= check_perm('MAS_FABRIC_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_FABRIC_DELETE" <?= check_perm('MAS_FABRIC_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>                       
                                              
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_YARN" <?= check_perm('MAS_YARN', $groupId); ?>> Yarn
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_YARN_ADD" <?= check_perm('MAS_YARN_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_YARN_EDIT" <?= check_perm('MAS_YARN_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_YARN_VIEW" <?= check_perm('MAS_YARN_VIEW', $groupId); ?>> View
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_YARN_DELETE" <?= check_perm('MAS_YARN_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        <!--fabric masters end-->

                        
                        <!--store masters Start-->
                        <?php $main = 'M11'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="2"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="STORE_MASTER" <?= check_perm('STORE_MASTER', $groupId); ?>>Store Masters</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_ACCESSORIES_TYPE" <?= check_perm('MAS_ACCESSORIES_TYPE', $groupId); ?>> Accessories Type
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ACCESSORIES_TYPE_ADD" <?= check_perm('MAS_ACCESSORIES_TYPE_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ACCESSORIES_TYPE_EDIT" <?= check_perm('MAS_ACCESSORIES_TYPE_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ACCESSORIES_TYPE_DELETE" <?= check_perm('MAS_ACCESSORIES_TYPE_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'26'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_ACCESSORIES" <?= check_perm('MAS_ACCESSORIES', $groupId); ?>> Accessories
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ACCESSORIES_ADD" <?= check_perm('MAS_ACCESSORIES_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ACCESSORIES_EDIT" <?= check_perm('MAS_ACCESSORIES_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_ACCESSORIES_DELETE" <?= check_perm('MAS_ACCESSORIES_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        <!--store masters end-->

                        
                        <!--production masters Start-->
                        <?php $main = 'M12'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="4"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="PROD_MASTER" <?= check_perm('PROD_MASTER', $groupId); ?>>Production Masters</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_CHECKING" <?= check_perm('MAS_CHECKING', $groupId); ?>> Checking Type
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CHECKING_ADD" <?= check_perm('MAS_CHECKING_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CHECKING_EDIT" <?= check_perm('MAS_CHECKING_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_CHECKING_DELETE" <?= check_perm('MAS_CHECKING_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_QC" <?= check_perm('MAS_QC', $groupId); ?>> QC Checklist
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_QC_ADD" <?= check_perm('MAS_QC_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_QC_EDIT" <?= check_perm('MAS_QC_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_QC_DELETE" <?= check_perm('MAS_QC_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_DEFECT" <?= check_perm('MAS_DEFECT', $groupId); ?>> Defect
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DEFECT_ADD" <?= check_perm('MAS_DEFECT_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DEFECT_EDIT" <?= check_perm('MAS_DEFECT_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_DEFECT_DELETE" <?= check_perm('MAS_DEFECT_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'04'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="MAS_LINE" <?= check_perm('MAS_LINE', $groupId); ?>> Line
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_LINE_ADD" <?= check_perm('MAS_LINE_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_LINE_EDIT" <?= check_perm('MAS_LINE_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="MAS_LINE_DELETE" <?= check_perm('MAS_LINE_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>                  
                                              
                        <!--masters end-->
                        
                        <!--SETTINGS start-->
                        <?php $main = 'M13'; $sub = $main.'01'; ?>
                        <tr>
                            <td rowspan="3"><input type="checkbox" class="all_cbox cbox mod_<?= $main; ?>" onclick="modClick('<?= $main; ?>')" name="permission_cbox[]" value="MOD_SETTINGS" <?= check_perm('MOD_SETTINGS', $groupId); ?>>Settings</td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SET_TIMETEMP" <?= check_perm('SET_TIMETEMP', $groupId); ?>> Time Management Template
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SET_TIMETEMP_ADD" <?= check_perm('SET_TIMETEMP_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SET_TIMETEMP_EDIT" <?= check_perm('SET_TIMETEMP_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="SET_TIMETEMP_DELETE" <?= check_perm('SET_TIMETEMP_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'02'; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SET_ORBIDX_CONFIG" <?= check_perm('SET_ORBIDX_CONFIG', $groupId); ?>> ORBIDX Device Config
                            </td>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="ORBIDX_CONFIG_ADD" <?= check_perm('ORBIDX_CONFIG_ADD', $groupId); ?>> Add
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="ORBIDX_CONFIG_EDIT" <?= check_perm('ORBIDX_CONFIG_EDIT', $groupId); ?>> Edit
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_cs<?= $sub; ?>" value="ORBIDX_CONFIG_DELETE" <?= check_perm('ORBIDX_CONFIG_DELETE', $groupId); ?>> Delete
                            </td>
                        </tr>
                        
                        <?php $sub = $main.'03'; ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SET_CONFIG" <?= check_perm('SET_CONFIG', $groupId); ?>> Configuration
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SET_DEPT_HEAD" <?= check_perm('SET_DEPT_HEAD', $groupId); ?>> Department Head
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SET_TEAMTASK" <?= check_perm('SET_TEAMTASK', $groupId); ?>> Team Task Notification
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SET_FINANCEYEAR" <?= check_perm('SET_FINANCEYEAR', $groupId); ?>> Financial Year
                                <input type="checkbox" name="permission_cbox[]" class="all_cbox cbox mod_cs<?= $main; ?> sub_<?= $sub; ?>" onclick="menuClick('<?= $sub; ?>')" value="SET_APP_LOGO" <?= check_perm('SET_APP_LOGO', $groupId); ?>> App Logo
                            </td>
                            <td>-</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>

<?php
    
} else if($search_type == 'savePermissions') {

    foreach(explode(',', $_REQUEST['trueVal']) as $trueVal) {
        $dataa = array(
            'user_group' => $_REQUEST['user_group'],
            'permission_name' => $trueVal,
            'value' => 1,
            'created_user' => $_SESSION['login_id'],
        );
        
        $fth = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM user_permissions WHERE user_group = '". $_REQUEST['user_group'] ."' AND permission_name = '". $trueVal ."'"));
        if($fth['id']=='') {
            $save = Insert('user_permissions', $dataa);
        } else {
            $save = Update('user_permissions', $dataa, ' WHERE id = '. $fth['id']);
        }
    }

    foreach(explode(',', $_REQUEST['falsVal']) as $falsVal) {
        $dataa = array(
            'user_group' => $_REQUEST['user_group'],
            'permission_name' => $falsVal,
            'value' => 0,
            'created_user' => $_SESSION['login_id'],
        );
        
        $fth = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM user_permissions WHERE user_group = '". $_REQUEST['user_group'] ."' AND permission_name = '". $falsVal ."'"));
        if($fth['id']=='') {
            $save = Insert('user_permissions', $dataa);
        } else {
            $save = Update('user_permissions', $dataa, ' WHERE id = '. $fth['id']);
        }
    }
    
    if($save) {
        $dta['msg'] = 'saved';
    } else {
        $dta['msg'] = 'error';
    }
    
    echo json_encode($dta);
    
} else if($search_type == 'saveUserGroup') {
    
    $arr = array(
        'group_name' => $_REQUEST['name'],
        'created_by' => $_SESSION['login_id'],
        'created_unit' => $_SESSION['loginCompany']
        );
        
        $save = Insert('user_group', $arr);
        
        if($save) {
            $dta['msg'] = 'saved';
        } else {
            $dta['msg'] = 'error';
        }
        
        echo json_encode($dta);
}
?>