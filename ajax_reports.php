<?php
include ("includes/connection.php");
include ("includes/function.php");
include ("includes/perm.php");

if (!isset($_SESSION['login_id'])) {
	header('Location:index.php');
}

if (isset($_REQUEST['production_register'])) {

    // echo '<pre>', print_r($_REQUEST, 1);exit;

    $qry = "SELECT a.order_id, a.style_id, a.combo, a.part, a.color, a.sod_part, sum(a.pcs_per_bundle) ct_qty, f.item_image, f.total_qty, f.excess, f.is_dispatch, e.brand ";
    $qry .= " FROM bundle_details a ";
    $qry .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
    $qry .= " LEFT JOIN sales_order e ON b.order_id = e.id ";
    $qry .= " LEFT JOIN sales_order_detalis f ON b.style = f.id ";

    $qry .= " WHERE ";
    if ($_REQUEST['order_type'] == 'Running') {
        $qry .= " f.is_dispatch IS NULL ";
    } else if ($_REQUEST['order_type'] == 'Completed') {
        $qry .= " f.is_dispatch = 'yes' ";
    } else {
        $qry .= ' 1 ';
    }
    if (isset($_REQUEST['style_id'])) {
        $qry .= " AND a.style_id IN (" . implode(',', $_REQUEST['style_id']) . ") ";
    }
    if (isset($_REQUEST['brand'])) {
        $qry .= " AND e.brand IN (" . implode(',', $_REQUEST['brand']) . ") ";
    }
    if (isset($_REQUEST['unit'])) {
        $qry .= " AND b.created_unit  IN (" . implode(',', $_REQUEST['unit']) . ") ";
    }

    if ($_REQUEST['del_dt_bdg'] == 'true') {
        $qry .= " AND f.delivery_date BETWEEN '" . $_REQUEST['del_dt_start'] . "'  AND '" . $_REQUEST['del_dt_end'] . "' ";
    }

    if ($_SESSION['login_role'] != '1') {
        $qry .= " AND a.created_unit= '" . $logUnit . "'";
    }

    $qry .= " GROUP BY a.sod_part ";
    $qry .= " ORDER BY f.id DESC ";

    $query = mysqli_query($mysqli, $qry);

    $num_ = mysqli_num_rows($query);
    if ($num_ > 0) {
        $pp = 1;
        while ($sql = mysqli_fetch_array($query)) {

            $data['table_tr'][] = '<tr>
                                        <td>' . $pp . '</td>
                                        <td>-</td>
                                        <td>' . brand_name($sql['brand']) . '</td>
                                        <td>' . sales_order_code($sql['order_id']) . '</td>
                                        <td>' . sales_order_style($sql['style_id']) . '</td>
                                        <td>' . color_name($sql['combo']) . '</td>
                                        <td>' . part_name($sql['part']) . ' | ' . color_name($sql['color']) . '</td>
                                        <td>' . $sql['total_qty'] . '</td>
                                        <td>' . round($sql['total_qty'] + (($sql['excess'] / 100) * $sql['total_qty'])) . '</td>
                                        <td>' . tot_cutting_qty_part($sql['style_id'], $sql['sod_part'], 'today') . '</td>
                                        <td>' . tot_cutting_qty_part($sql['style_id'], $sql['sod_part'], 'all') . '</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>' . tot_sewing_in_part($sql['style_id'], $sql['sod_part'], 'today') . '</td>
                                        <td>' . tot_sewing_in_part($sql['style_id'], $sql['sod_part'], 'all') . '</td>
                                        <td>' . tot_sewing_out_part($sql['style_id'], $sql['sod_part'], 'today') . '</td>
                                        <td>' . tot_sewing_out_part($sql['style_id'], $sql['sod_part'], 'all') . '</td>
                                        <td>' . tot_checking_part($sql['style_id'], $sql['sod_part'], 'today') . '</td>
                                        <td>' . tot_checking_part($sql['style_id'], $sql['sod_part'], 'all') . '</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>';
            $pp++;
        }
    }

    $data['row_count'][] = $num_;

    echo json_encode($data);

} else if (isset($_REQUEST['cutting_ledger'])) {

    if (isset($_REQUEST['style_id'])) {
        ?>
            <table class="table table-bordered" width="100%" cellspacing="0" border="1" style="border-collapse: collapse">
                <?php
                $qry1 = "SELECT a.*, b.brand, c.size_detail ";
                $qry1 .= " FROM cutting_barcode a ";
                $qry1 .= " LEFT JOIN sales_order b ON a.order_id = b.id ";
                $qry1 .= " LEFT JOIN sales_order_detalis c ON a.style = c.id ";
                $qry1 .= " WHERE c.id IN (" . implode(',', $_REQUEST['style_id']) . ") ORDER BY a.id ASC";
                $temp1 = mysqli_query($mysqli, $qry1);
                $i = 1;
                while ($row = mysqli_fetch_array($temp1)) {
                    ?>
                    <tr style="border-top: 2px solid black;border-left: 2px solid black;border-right: 2px solid black;">
                        <td><b>Buyer</b></td>
                        <td><?= brand_name($row['brand']); ?></td>
                        <td><b>Combo</b></td>
                        <td><?= color_name($row['combo_id']); ?></td>
                        <td><b>Lay Ref</b></td>
                        <td><?= $row['entry_number']; ?></td>
                        <td><b>No Of Lay</b></td>
                        <td><?= $row['lay_number']; ?></td>
                        <td><b>Order Qty</b></td>
                        <td><?= $row['']; ?></td>
                    </tr>
                    <tr style="border-left: 2px solid black;border-right: 2px solid black;">
                        <td style="border-left: 2px solid black;"><b>BO No </b></td>
                        <td> <?= sales_order_code($row['order_id']); ?></td>
                        <td><b>Part | Color </b></td>
                        <td> <?= part_name($row['part_id']) . ' | ' . color_name($row['color_id']); ?></td>
                        <td><b>Lay Date </b></td>
                        <td><?= $row['entry_date']; ?></td>
                        <td><b>Lay Wt </b></td>
                        <td><?= $row['']; ?></td>
                        <td><b>Cut Plan Qty </b></td>
                        <td><?= $row['']; ?></td>
                    </tr>
                    <tr style="border-left: 2px solid black;border-right: 2px solid black;border-bottom: 2px solid black;">
                        <td colspan="10" style="border-botttom: 2px solid black;">
                            <table class="table table-bordered" cellspacing="0" border="1" style="border-collapse: collapse">
                                <thead>
                                    <tr>
                                        <th width="7%">
                                            <div>Bundle No</div>
                                        </th>
                                        <?php
                                        $rk = mysqli_query($mysqli, "SELECT variation_value FROM sod_size WHERE sod_combo = '" . $row['sod_combo'] . "'");
                                        while ($siz = mysqli_fetch_array($rk)) {
                                            print '<th>' . variation_value($siz['variation_value']) . '</th>';
                                            $vvl[] = $siz['variation_value'];
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    $bn = mysqli_query($mysqli, "SELECT bundle_number, variation_value, pcs_per_bundle FROM bundle_details WHERE lay_length = '" . $row['lay_number'] . "' AND cutting_barcode_id = '" . $row['id'] . "'");
                                    while ($res = mysqli_fetch_array($bn)) {
                                        print '<tr><td>' . $res['bundle_number'] . '</td>';
                                        foreach ($vvl as $vvl1) {
                                            $tt = ($res['variation_value'] == $vvl1) ? $res['pcs_per_bundle'] : '';
                                            print '<td>' . $tt . '</td>';
                                        }
                                        print '</tr>';
                                    }
                                    ?>
                                    <tr>
                                        <th>Size Wize Total</th>
                                        <?php
                                        foreach ($vvl as $vvl2) {
                                            $expp = explode(',,', $siz);
                                            $size = explode('=', $expp[0]);

                                            $tto = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details 
                                        WHERE variation_value = '" . $vvl2 . "' AND lay_length = '" . $row['lay_number'] . "' AND cutting_barcode_id = '" . $row['id'] . "' "));

                                            $tty = $tto['pcs_per_bundle'] ? $tto['pcs_per_bundle'] : 0;
                                            print '<th>' . $tty . '</th>';
                                        }
                                        ?>
                                    </tr>
                                </thead>
                            </table>
                        </td>
                    </tr>
                <?php $i++;
                unset($vvl);
                } ?>
            </table>

            <table class="table table-bordered" width="100%" cellspacing="0" border="1" style="border-collapse: collapse">
                <tr>
                    <th colspan="10">Over All Summary</th>
                </tr>
                <?php
                $qry1 = "SELECT a.* ";
                $qry1 .= " FROM sod_part a ";
                $qry1 .= " WHERE a.sales_order_detail_id IN (" . implode(',', $_REQUEST['style_id']) . ") ORDER BY a.id ASC";
                $temp1 = mysqli_query($mysqli, $qry1);
                $i = 1;
                while ($row = mysqli_fetch_array($temp1)) {
                    ?>
                    <tr style="border-top: 2px solid black;border-left: ;">
                        <td><b>Bo No</b></td>
                        <td><?= sales_order_code($row['sales_order_id']); ?></td>
                        <td><b>Style</b></td>
                        <td><?= sales_order_style($row['sales_order_detail_id']); ?></td>
                        <td><b>Combo</b></td>
                        <td><?= color_name($row['combo_id']); ?></td>
                        <td><b>Part</b></td>
                        <td><?= part_name($row['part_id']); ?></td>
                        <td><b>Color</b></td>
                        <td><?= color_name($row['color_id']); ?></td>
                    </tr>
                    <tr>
                        <th width="7%">
                            <div>Size</div>
                        </th>
                        <?php

                        $rk = mysqli_query($mysqli, "SELECT id,variation_value,excess_qty FROM sod_size WHERE sod_combo = '" . $row['sod_combo'] . "'");
                        $pp = $i . '0';
                        while ($siz = mysqli_fetch_array($rk)) {
                            print '<td>' . variation_value($siz['variation_value']) . '</td>';

                            $oq[$pp][] = $siz['excess_qty'];
                            $sizo[$pp][] = $siz['id'] . '--' . $row['id'];
                            $pp++;
                        }
                        ?>
                    </tr>
                    <tr>
                        <th>Order Qty</th>
                        <?php
                        foreach ($oq as $key => $opq) {
                            print '<td>' . $opq[0] . '</td>';
                        }
                        ?>
                    </tr>
                    <tr>
                        <th>Cutting Qty</th>
                        <?php
                        foreach ($sizo as $keys => $nss) {
                            $expp = explode('--', $nss[0]);

                            $tto = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details 
                                        WHERE variation_value = '" . $expp[0] . "' AND sod_part = '" . $expp[1] . "' "));

                            $tty = $tto['pcs_per_bundle'] ? $tto['pcs_per_bundle'] : 0;
                            print '<td>' . $tty . '</td>';
                        }
                        ?>
                    </tr>

                <?php unset($oq);
                unset($sizo);
                } ?>

            </table>
    <?php } else {
        print '<p class="">Choose Order details in Filter</p>';
    }
} else if (isset($_REQUEST['cutting_bundle_detail'])) {

    if (isset($_REQUEST['style_id'])) {
        ?>
                <table class="table table-bordered" width="100%" cellspacing="0" border="1" style="border-collapse: collapse">
                <?php
                $qry1 = "SELECT a.*, b.brand, c.size_detail, c.style_no ";
                $qry1 .= " FROM cutting_barcode a ";
                $qry1 .= " LEFT JOIN sales_order b ON a.order_id = b.id ";
                $qry1 .= " LEFT JOIN sales_order_detalis c ON a.style = c.id ";
                $qry1 .= " WHERE c.id IN (" . implode(',', $_REQUEST['style_id']) . ") GROUP BY a.style ORDER BY a.id ASC";
                $temp1 = mysqli_query($mysqli, $qry1);
                $i = 1;
                while ($row = mysqli_fetch_array($temp1)) {
                    ?>
                        <tr>
                            <td><b>Part</b></td>
                            <td><?= part_name($row['part']); ?></td>
                            <td><b>Color</b></td>
                            <td><?= color_name($row['color']); ?></td>
                            <td><b>Size</b></td>
                            <td><?= $row['style_no']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                        <?php
                                        $rk = mysqli_query($mysqli, "SELECT variation_value FROM sod_size WHERE sod_combo = '" . $row['sod_combo'] . "'");
                                        while ($siz = mysqli_fetch_array($rk)) {
                                            print '<th colspan="2" style="border-left: 2px solid black;border-right: 2px solid black;border-top: 2px solid black;">' . variation_value($siz['variation_value']) . '<br><hr><span>Bundle No</span><span style="margin-left: 30%;">
                                        Psc</span></th>';
                                            $vvl[] = $siz['variation_value'];
                                        }
                                        ?>
                                        </tr>
                                    <?php
                                    function fetchBundleDetails($mysqli, $cuttingBarcodeId)
                                    {
                                        $bundleDetails = array();
                                        $query = "SELECT bundle_number, pcs_per_bundle, variation_value FROM bundle_details WHERE cutting_barcode_id = ? ORDER BY id ASC";
                                        $stmt = mysqli_prepare($mysqli, $query);
                                        mysqli_stmt_bind_param($stmt, "s", $cuttingBarcodeId);
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_bind_result($stmt, $bundleNumber, $pcsPerBundle, $variationValue);
                                        while (mysqli_stmt_fetch($stmt)) {
                                            $bundleDetails[$variationValue][] = array('bundle_number' => $bundleNumber, 'pcs_per_bundle' => $pcsPerBundle);
                                        }
                                        mysqli_stmt_close($stmt);
                                        return $bundleDetails;
                                    }

                                    $aq = array();
                                    $sqliii = mysqli_query($mysqli, "SELECT variation_value, count(bundle_number) as bundle_number FROM bundle_details WHERE cutting_barcode_id = '" . $row['id'] . "' GROUP BY variation_value");
                                    while ($row3 = mysqli_fetch_array($sqliii)) {
                                        $aq[$row3[0]] = $row3[1];
                                    }
                                    $maxValue = max($aq);
                                    $bundleDetails = fetchBundleDetails($mysqli, $row['id']);

                                    for ($mk = 0; $mk < $maxValue; $mk++) {
                                        print '<tr style="border-left: 2px solid;">';
                                        foreach ($vvl as $variationValue) {
                                            $gg = isset($bundleDetails[$variationValue][$mk]) ? $bundleDetails[$variationValue][$mk] : array('bundle_number' => '', 'pcs_per_bundle' => '');
                                            $br = ($mk == $maxValue) ? 'border-bottom: 2px; solid black;' : '';
                                            print '<td>' . $gg['bundle_number'] . '</td><td style="border-right: 2px solid black; ' . $br . '">' . $gg['pcs_per_bundle'] . '</td>';
                                        }
                                        print '</tr>';
                                    }
                                    ?>
                                    </thead>
                                </table>
                            </td>
                        </tr>
                <?php $i++;
                } ?>
                
                </table>
    <?php } else {
        print '<p class="">Choose Order details in Filter</p>';
    }

} else if (isset($_REQUEST['prod_stock_report'])) {

    if (isset($_REQUEST['style_id'])) {

        $qry = "SELECT *, sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE style_id IN (" . implode(',', $_REQUEST['style_id']) . ") GROUP BY sod_part, sod_size, in_sewing ORDER BY id DESC";
        $query = mysqli_query($mysqli, $qry);
        $x = 1;
        while ($sql = mysqli_fetch_array($query)) {
            $ctt_rate = mysqli_fetch_array(mysqli_query($mysqli, "SELECT price FROM sales_order_detalis WHERE id = '" . $sql['style_id'] . "'"));
            if ($sql['checking_complete'] == 'yes' || $sql['ch_good_pcs'] != '') {
                $p_stage = 'Checking';
                $s_stage = 'Checking';
            } else if ($sql['complete_sewing'] == 'yes' || $sql['s_out_complete'] != '') {
                $p_stage = 'Sewing Output';
                $s_stage = 'Sewing';
            } else if ($sql['in_sewing'] == 'yes') {
                $p_stage = 'Sewing Input';
                if ($sql['input_type'] == 'Line') {
                    $location = line_name($sql['line']);
                } else if ($sql['input_type'] == 'Employee') {
                    $location = employee_name($sql['line']);
                } else if ($sql['input_type'] == 'Unit') {
                    $location = company_code($sql['line']);
                }
                $s_stage = 'Cutting';
            } else {
                $p_stage = $s_stage = 'Cutting';
                $location = company_code($sql['created_unit']);
            }
            ?>
                        <tr>
                            <td><?= $x; ?></td>
                            <td><?= $location; ?></td>
                            <td><?= $s_stage; ?></td>
                            <td><?= $p_stage; ?></td>
                            <td><?= sales_order_code($sql['order_id']); ?></td>
                            <td><?= sales_order_style($sql['style_id']); ?></td>
                            <td><?= color_name($sql['combo']); ?></td>
                            <td><?= part_name($sql['part']); ?></td>
                            <td><?= color_name($sql['color']); ?></td>
                            <td><?= variation_value($sql['variation_value']); ?></td>
                            <td><?= '-'; ?></td>
                            <td><?= '-'; ?></td>
                            <td><?= $sql['pcs_per_bundle']; ?></td>
                            <td><?= $ctt_rate['price']; ?></td>
                            <td><?= ($sql['pcs_per_bundle'] * $ctt_rate['price']) ?></td>
                        </tr>
            <?php $x++;
        }
    } else {
        print '<tr><td colspan="15" class="text-center">Choose Order details in Filter</td></tr>';
    }
    
} else if (isset($_REQUEST['production_order_tracking'])) {
    
    $qry = "SELECT a.*, b.item_image, b.total_qty, b.excess, b.is_dispatch, b.delivery_date, c.brand ";
    $qry .= " FROM sod_part a ";
    $qry .= " LEFT JOIN sales_order_detalis b ON a.sales_order_detail_id = b.id ";
    $qry .= " LEFT JOIN sales_order c ON a.sales_order_id = c.id ";

    $qry .= " WHERE ";
    if ($_REQUEST['order_type'] == 'Running') {
        $qry .= " b.is_dispatch IS NULL ";
    } else if ($_REQUEST['order_type'] == 'Completed') {
        $qry .= " b.is_dispatch = 'yes' ";
    } else {
        $qry .= ' 1 ';
    }
    if (isset($_REQUEST['style_id'])) {
        $qry .= " AND a.sales_order_detail_id IN (" . implode(',', $_REQUEST['style_id']) . ") ";
    }
    if (isset($_REQUEST['brand'])) {
        $qry .= " AND c.brand IN (" . implode(',', $_REQUEST['brand']) . ") ";
    }
    if (isset($_REQUEST['unit'])) {
        $qry .= " AND c.created_unit  IN (" . implode(',', $_REQUEST['unit']) . ") ";
    }
    if ($_REQUEST['del_dt_bdg'] == 'true') {
        $qry .= " AND b.delivery_date BETWEEN '" . $_REQUEST['del_dt_start'] . "'  AND '" . $_REQUEST['del_dt_end'] . "' ";
    }
    if ($_SESSION['login_role'] != '1') {
        $qry .= " AND c.created_unit= '" . $logUnit . "'";
    }
    
    $qry .= " ORDER BY a.id DESC ";

    $query = mysqli_query($mysqli, $qry);

    $num_ = mysqli_num_rows($query);
    if ($num_ > 0) {
        $pp = 1;
        while ($sql = mysqli_fetch_array($query)) {
            
            $data['tbody'][] = '<tr>
                                        <td>'. $pp .'</td>
                                        <td>'. viewImage($sql['item_image'], 30) .'</td>
                                        <td>'. brand_name($sql['brand']) .'</td>
                                        <td>'. sales_order_code($sql['sales_order_id']) .'</td>
                                        <td>'. sales_order_style($sql['sales_order_detail_id']) .'</td>
                                        <td>'. color_name($sql['combo_id']) .'</td>
                                        <td>'. part_name($sql['part_id']) .'</td>
                                        <td>'. color_name($sql['color_id']) .'</td>
                                        <td>'. $sql['delivery_date'] .'</td>
                                        <td>'. $sql['total_qty'] .'</td>
                                        <td>'. round($sql['total_qty'] + (($sql['excess'] / 100) * $sql['total_qty'])) .'</td>
                                        <td>'. tot_cutting_qty_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                        <td>'. tot_sewing_in_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                        <td>'. tot_sewing_out_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                        <td>'. tot_checking_part($sql['sales_order_detail_id'], $sql['id'], 'all') .'</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>
                                            <i class="icon-copy ion-plus-round 1conA1'. $pp .'" onclick="addIcon('. $pp .')"></i> &nbsp;
                                            <span><textarea class="form-control dhide 1conA2'. $pp .'" id="comment'. $pp .'" style="height: 50px;"></textarea></span>
                                            <span class="1conA1'. $pp .' txt'. $pp .'"  title="By : '. $cmt['employee_name'] .'"> '. $cmt['comment'] .'</span>
                                            <i class="icon-copy ion-checkmark dhide 1conA2'. $pp .'" onclick="saveIcon('. $pp .','.$sql['id'].')"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <i class="icon-copy ion-close-round dhide 1conA2'. $pp .'" onclick="cancelIcon('. $pp .')"></i>
                                        </td>
                                    </tr>';
            $pp++;
        }
    } else {
        $data['tbody'][] = '<tr><td colspan="20" class="text-center">--No data found--</td></tr>';
    }

    $data['row_count'][] = $num_;

    echo json_encode($data);

} else if(isset($_REQUEST['sewing_out_summary'])) {
    
    // echo '<pre>', print_r($_REQUEST, 1); exit;
    
    $qry = "SELECT a.*, SUM(a.scanned_count) as output_qty, b.total_qty ";
    $qry .= " FROM orbidx_sewingout a ";
    $qry .= " LEFT JOIN sales_order_detalis b ON a.style_id = b.id ";
    $qry .= " LEFT JOIN sales_order c ON b.sales_order_id = c.id ";
    $qry .= " LEFT JOIN processing_list d ON b.sales_order_id = d.id ";
    $qry .= " WHERE ";
    
    if ($_REQUEST['order_type'] == 'Running') {
        $qry .= " b.is_dispatch IS NULL ";
    } else if ($_REQUEST['order_type'] == 'Completed') {
        $qry .= " b.is_dispatch = 'yes' ";
    } else {
        $qry .= ' 1 ';
    }
    if (isset($_REQUEST['style_id'])) {
        $qry .= " AND a.style_id IN (" . implode(',', $_REQUEST['style_id']) . ") ";
    }
    if (isset($_REQUEST['brand'])) {
        $qry .= " AND c.brand IN (" . implode(',', $_REQUEST['brand']) . ") ";
    }
    // if (isset($_REQUEST['unit'])) {
    //     $qry .= " AND c.created_unit  IN (" . implode(',', $_REQUEST['unit']) . ") ";
    // }
    // if ($_REQUEST['del_dt_bdg'] == 'true') {
    //     $qry .= " AND b.delivery_date BETWEEN '" . $_REQUEST['del_dt_start'] . "'  AND '" . $_REQUEST['del_dt_end'] . "' ";
    // }
    // if ($_SESSION['login_role'] != '1') {
    //     $qry .= " AND c.created_unit= '" . $logUnit . "'";
    // }
    
    if($_REQUEST['inp_date_bdg'] == 'true') {
        $qry .= " AND d.entry_date BETWEEN '" . $_REQUEST['inp_date_start'] . "'  AND '" . $_REQUEST['inp_date_end'] . "' ";
    }
    
    if($_REQUEST['out_date_bdg'] == 'true') {
        $qry .= " AND a.date BETWEEN '" . $_REQUEST['out_date_start'] . "'  AND '" . $_REQUEST['out_date_end'] . "' ";
    }
    
    $qry .= " GROUP BY a.date, a.line, a.sod_part ";
    
    $query = mysqli_query($mysqli, $qry);
    if(mysqli_num_rows($query)>0) {
        $p=1;
        while($row = mysqli_fetch_array($query)) {
            
            $pln = mysqli_fetch_array(mysqli_query($mysqli, "
                    SELECT a.id, a.planning_type, a.assign_to, a.assign_type, a.plan_qty FROM line_planning a
                    WHERE style_id = '". $row['style_id'] ."'"));
            
            if($pln['planning_type']=='Full' && $pln['assign_type']=='line' && $pln['assign_to']==$row['line']) {
                $plan_qty = $pln['plan_qty'] ? $pln['plan_qty'] : 0;
            } else if($pln['planning_type']=='Partial') {
                $plan_q = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(a.plan_qty) as plan_qty FROM 
                    line_planning_size a WHERE a.line_planning_id = '". $pln['id'] ."' AND a.assign_type = 'line' AND assign_to = '". $row['line'] ."'
                "));
                
                $plan_qty = $plan_q['plan_qty'] ? $plan_q['plan_qty'] : 0;
            }
            
            $inp_qty = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE in_sewing = 'yes' AND input_type = 'line' AND line = '". $row['line'] ."' AND  style_id = '". $row['style_id'] ."'"));
            $inp_qty1 = $inp_qty['pcs_per_bundle'] ? $inp_qty['pcs_per_bundle'] : 0;
            
            $data['table_tr'][] = '<tr>
                                    <td>'. $p .'</td>
                                    <td>'. $row['date'] .'</td>
                                    <td>'. line_name($row['line']) .'</td>
                                    <td>-</td>
                                    <td>'. sales_order_code($row['order_id']) .'</td>
                                    <td>'. sales_order_style($row['style_id']) .'</td>
                                    <td>'. color_name($row['combo']) .'</td>
                                    <td>'. part_name($row['part']) .' | '. color_name($row['color']) .'</td>
                                    <td>'. process_name($row['process']) .'</td>
                                    <td>'. $row['total_qty'] .'</td>
                                    <td>'. $plan_qty .'</td>
                                    <td>'. $inp_qty1 .'</td>
                                    <td>'. $row['output_qty'] .'</td>
                                    <td>'. round(($inp_qty1/$row['output_qty'])*100) .' %</td>
                                </tr>';
            $p++;
        }
    } else {
        $data['table_tr'][] = '<tr><td class="text-center" colspan="14">No Data Found</td></tr>';
    }
    
    echo json_encode($data);
}


















// timeline_history('Insert', 'employee_detail_temp', $_REQUEST['id'], 'Employee Request Rejected.');
?>