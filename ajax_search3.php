<?php
include ("includes/connection.php");
include ("includes/function.php");

// if (!isset($_SESSION['login_id'])) {
// 	header('Location:index.php');
// }

$type = $_REQUEST['type'];

if (isset($type)) {

    if ($type == 'sizeWizeListfor_ironing') {

        $sod_combo = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sod_combo FROM sod_part WHERE id = " . $_POST['id']));

        $query = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = " . $sod_combo['sod_combo']);
        $sl = 1;
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_array($query)) {
                
                $sum = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(ironing_qty) as ironing_qty FROM ironing_detail WHERE sod_size = '". $row['id'] ."' AND variation_value = '". $row['variation_value'] ."'"));
                $data['tbody'][] = '
                    <tr>
                        <td>' . $sl . '</td>
                        <td>' . variation_value($row['variation_value']) . '</td>
                        <td>' . $row['excess_qty'] . '</td>
                        <td>' . ($allowed = $sum['ironing_qty'] ? $sum['ironing_qty'] : '0') . '</td>
                        <td>
                            <input type="hidden" name="ironing_det_id[]" value="">
                            <input type="hidden" name="sod_size[]" value="'. $row['id'] .'">
                            <input type="number" data-allowed="'. ($row['excess_qty'] - $allowed) .'" name="ironing_qty[]" style="max-width: 250px;" class="form-control" onkeyup="qty_validation(this)" placeholder="Ironing Qty">
                        </td>
                    </tr>
                ';
                $sl++;
            }
        } else {
            $data['tbody'][] = '<tr><td colspan="4" class="text-center">No data found</td></tr>';
        }

        echo json_encode($data);
    } else if ($type == 'sizeWizeListfor_packing') {

        $sod_combo = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sod_combo FROM sod_part WHERE id = " . $_POST['id']));

        $query = mysqli_query($mysqli, "SELECT * FROM sod_size WHERE sod_combo = " . $sod_combo['sod_combo']);
        $sl = 1;
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_array($query)) {
                
                $sum = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(packing_qty) as packing_qty FROM packing_detail WHERE sod_size = '". $row['id'] ."' AND variation_value = '". $row['variation_value'] ."'"));
                $data['tbody'][] = '
                    <tr>
                        <td>' . $sl . '</td>
                        <td>' . variation_value($row['variation_value']) . '</td>
                        <td>' . $row['excess_qty'] . '</td>
                        <td>' . ($allowed = $sum['packing_qty'] ? $sum['packing_qty'] : '0') . '</td>
                        <td>
                            <input type="hidden" name="packing_det_id[]" value="">
                            <input type="hidden" name="sod_size[]" value="'. $row['id'] .'">
                            <input type="number" data-allowed="'. ($row['excess_qty'] - $allowed) .'" name="packing_qty[]" style="max-width: 250px;" class="form-control" onkeyup="qty_validation(this)" placeholder="Packing Qty">
                        </td>
                    </tr>
                ';
                $sl++;
            }
        } else {
            $data['tbody'][] = '<tr><td colspan="4" class="text-center">No data found</td></tr>';
        }
        echo json_encode($data);
        
    } else if ($type == 'get_fabricfor_fabdelivery') {
        
        $style = $_POST['style'];
        $fetch = mysqli_query($mysqli, "SELECT * FROM fabric_consumption WHERE sales_order_detail_id = '". $style ."' ");
        $num = mysqli_num_rows($fetch);
        if($num>0) {
                $data['option'][] = '<option value="" data-req_wt="0">Select Fabric</option>';
            while($result = mysqli_fetch_array($fetch)) {
                $ff = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(del_wt) as del_wt FROM fabric_delivery_det WHERE fabric_consumption = '". $result['id'] ."'"));
                $data['option'][] = '<option value="'. $result['id'] .'" data-req_wt="'. $result['req_wt'] .'" data-del_bal="'. ($result['req_wt'] - $ff['del_wt']) .'">'. fabric_name($result['fabric']) .'</option>';
            }
        } else {
            $data['option'][] = '';
        }
        $data['count'] = $num;
        
        echo json_encode($data);
        
    } else if($type == 'get_send_orders') {
        
        $qry = "SELECT a.order_code, d.item_image, d.style_no, d.delivery_date, d.total_excess, d.price, b.brand_name, e.part_count, (d.price * d.total_excess) as exchange_val, ((d.price * f.currency_value) * d.total_excess) as inr_val ";
        $qry .= " FROM sales_order a ";
        $qry .= " LEFT JOIN brand b ON a.brand = b.id ";
        $qry .= " LEFT JOIN mas_currency c ON a.currency = c.id ";
        $qry .= " LEFT JOIN sales_order_detalis d ON a.id = d.sales_order_id ";
        $qry .= " LEFT JOIN unit e ON d.unit_id = e.id ";
        $qry .= " LEFT JOIN mas_currency f ON a.currency = f.id ";
        
        $qry .= " WHERE a.is_dispatch IS NULL AND a.brand = '". $_POST['brand'] ."' ";
        
        $qry .= " ORDER BY d.id ASC ";
        $query = mysqli_query($mysqli, $qry);
        
        $x = 1;
        if(mysqli_num_rows($query)>0) {
            $tt=1;
            while ($sql = mysqli_fetch_array($query)) {
                $data['tbody'][] = '
                                    <tr>
                                        <td>'. $tt .'</td>
                                        <td>'. $sql['order_code'] .'</td>
                                        <td>'. $sql['style_no'] .'</td>
                                        <td><img src="'. $sql['item_image'] .'" width="30"></td>
                                        <td>'. date('d-m-Y', strtotime($sql['delivery_date'])) .'</td>
                                        <td>'. $sql['price'] .'</td>
                                        <td>'. $sql['total_excess'] .'</td>
                                        <td>'. ($sql['total_excess']*$sql['part_count']) .'</td>
                                        <td>'. number_format($sql['exchange_val'], 2) .'</td>
                                        <td>'. number_format($sql['inr_val'], 2) .'</td>
                                    </tr>'; $tt++;
            }
        } else {
            $data['tbody'][] = '<tr><td colspan="8">Nothing Found!</td></tr>';
        }
        
        echo json_encode($data);
    }
}




























