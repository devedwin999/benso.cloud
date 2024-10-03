<?php include('includes/connection.php'); ?>
<?php include('includes/function.php'); ?>
<?php include('includes/perm.php'); ?>



<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
    integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

<!-- <script type="text/javascript">
    $(document).ready(function () {
        var divToPrint = document.getElementById('divToPrint');
        var popupWin = window.open();
        popupWin.document.open();
        popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
    })
</script> -->

<style>
    .d-flex {
        display: flex;
    }
</style>

<body>

    <?php
    $a = "SELECT a.*, b.order_code,b.order_date,b.type, c.brand_name, d.full_name ";
    $a .= " FROM sales_order_detalis a ";
    $a .= " LEFT JOIN sales_order b ON a.sales_order_id=b.id ";
    $a .= " LEFT JOIN brand c ON b.brand=c.id ";
    $a .= " LEFT JOIN unit d ON a.unit_id=d.id ";
    $a .= " WHERE  a.id='" . $_REQUEST['id'] . "'";

    $qry = mysqli_query($mysqli, $a);

    $sql = mysqli_fetch_array($qry);
    ?>

    <title><?= $sql['style_no']; ?> - Fabric Program Print</title>
    
    
    <div id="divToPrint" style="font-family: monospace;">
        
        <?php if(FABRIC_PROG_PRINT!=1) { action_denied(); exit; } ?>

        <div style="width:800px;display: flex;justify-content: space-between;">
            <h3>Benso Garmenting Pvt Ltd</h3>
            <h4 style="color:blue">Fabric Requirement</h4>
        </div>
        
        <table style="width:800px;border-collapse: collapse;" border="1">
            <tr>
                <td style="padding: 4px;text-align: right;color: blue;">BO.No :</td>
                <td><?= $sql['order_code']; ?></td>
                <td style="padding: 4px;text-align: left;color: blue;font-weight: bold;">Style No : <?= $sql['style_no']; ?></td>
                <td style="padding: 4px;text-align: right;color: blue;">O.Qty :</td>
                <td><?= $sql['total_qty']; ?></td>
                <td style="padding: 4px;text-align: right;color: blue;">CUT PLAN QTY</td>
                <td><?= round(($sql['total_qty'] * ($sql['excess']/100)) + $sql['total_qty']) ?></td>
            </tr>
            
            <tr>
                <td style="padding: 4px;text-align: right;color: blue;">Buyer :</td>
                <td><?= $sql['brand_name']; ?></td>
                <td style="padding: 4px;text-align: right;color: blue;">PART :</td>
                <td><?= $sql['full_name']; ?></td>
                <td style="padding: 4px;text-align: right;color: blue;font-weight: bold;">Fabric Inhouse Date :</td>
                <td></td>
                <td style="padding: 4px;text-align: right;background-color: #ffe4e8;font-weight: bold;">Entry Date</td>
            </tr>
            
            <tr>
                <td style="padding: 4px;text-align: right;color: blue;">Cad Pcs Wt :</td>
                <td></td>
                <td style="padding: 4px;text-align: right;color: blue;">Order Qty + <?= $sql['excess']; ?>% PCS Wt</td>
                <td></td>
                <td style="padding: 4px;text-align: right;color: blue;font-weight: bold;">Style GSM :</td>
                <td></td>
                <td style="padding: 4px;text-align: right;background-color: #ffe4e8;"></td>
            </tr>
            
            <tr>
                <td colspan="7" style="padding: 7px;"></td>
            </tr>
            
            <tr style="font-weight:bold;">
                <td style="padding: 4px;color: blue;padding-left: 8px;">Yarn Requierment</td>
                <td style="padding: 4px;color: blue;">Mixing %</td>
                <td style="padding: 4px;color: blue;">Requied Wt</td>
                <td style="padding: 4px;text-align: center;color: blue;" colspan="4">Notes</td>
            </tr>
            
            <?php
                $tdb = mysqli_query($mysqli, "SELECT b.yarn_name, sum(a.mixed) as mixed, c.color_name FROM sales_order_fabric_components_yarn a LEFT JOIN mas_yarn b ON a.yarn_id = b.id LEFT JOIN color c ON a.yarn_color=c.id WHERE a.sales_order_detalis_id = '". $sql['id'] ."' GROUP BY a.yarn_id ASC");
                while($yarn_arr = mysqli_fetch_array($tdb)) {
            ?>
                <tr>
                    <td style="padding-left: 8px;"><?= $yarn_arr['yarn_name']; ?></td>
                    <td style=""><?= $yarn_arr['mixed']; ?></td>
                    <td></td>
                </tr>
            <?php } ?>
            
            <tr>
                <td colspan="7" style="padding:7px"></td>
            </tr>
            
            <tr>
                <td colspan="7"  style="padding: 5px;text-align: center;color: blue;font-size: 17px;font-weight: bold;">Purchase/Process</td>
            </tr>
            
            <?php
                $iop = mysqli_query($mysqli, "SELECT a.*, b.process_name, b.budget_type FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id = b.id WHERE b.budget_type = 'Yarn' AND a.sales_order_detalis_id = '". $sql['id'] ."' GROUP BY a.process_id ORDER BY a.process_order ASC");
                    while($res = mysqli_fetch_array($iop)) {
                    ?>
                
                        <tr style="font-weight:bold;">
                            <td style="padding: 4px;color: pink;padding-left: 8px;"><?= $res['process_name']; ?></td>
                            <td style="padding: 4px;color: blue;text-align: center;" colspan="4">Yarn Details</td>
                            <td style="padding: 4px;color: blue;text-align: center;">Color</td>
                            <td style="padding: 4px;color: blue;text-align: center;">REQ WT</td>
                        </tr>
                    <?php
                    
                    $jvb = mysqli_query($mysqli, "SELECT fabric_program_id FROM sales_order_fabric_components_process WHERE sales_order_detalis_id = '". $sql['id'] ."' AND process_id='". $res['process_id'] ."' ");
                    while($rtt = mysqli_fetch_array($jvb)) {
                        $immmm[] = $rtt['fabric_program_id'];
                    }
                    
                        $qq = "SELECT b.yarn_name, c.color_name ";
                        $qq .= " FROM sales_order_fabric_components_yarn a ";
                        $qq .= " LEFT JOIN mas_yarn b ON a.yarn_id=b.id ";
                        $qq .= " LEFT JOIN color c ON a.yarn_color=c.id ";
                        $qq .= " WHERE a.sales_order_detalis_id = '". $sql['id'] ."' AND a.fabric_program_id IN (". implode(',', $immmm) .") GROUP BY a.yarn_id, a.yarn_color";
                        
                        $ry = mysqli_query($mysqli, $qq);
                        
                        $p=1;
                        while($nql2 = mysqli_fetch_array($ry)) {
                            
                        ?>
                            <tr>
                                <td></td>
                                <td colspan="4"><?= $nql2['yarn_name']; ?></td>
                                <td><?= $nql2['color_name']; ?></td>
                                <td></td>
                            </tr>
                        <?php }
                        
                        print '<tr> <td style="padding:10px" colspan="7"></td> </tr>';
                        
                }
                
                $iop = mysqli_query($mysqli, "SELECT a.*, b.process_name, b.budget_type, a.process_id FROM sales_order_fabric_components_process a LEFT JOIN process b ON a.process_id = b.id WHERE b.budget_type = 'Fabric' AND a.sales_order_detalis_id = '". $sql['id'] ."' GROUP BY a.process_id ORDER BY a.process_order ASC");
                while($res = mysqli_fetch_array($iop)) {
                ?>
                    <tr style="font-weight:bold;">
                        <td style="padding: 4px;color: pink;padding-left: 8px;"><?= $res['process_name']; ?></td>
                        <td style="padding: 4px;color: blue;text-align: center;" colspan="2">Yarn Mixing %</td>
                        <td style="padding: 4px;color: blue;text-align: center;">Loss %</td>
                        <td style="padding: 4px;color: blue;text-align: center;">Color</td>
                        <td style="padding: 4px;color: blue;text-align: center;">DIA/SIZE</td>
                        <td style="padding: 4px;color: blue;text-align: center;">REQ WT</td>
                    </tr>
                    <?php
                    
                        $qq = "SELECT a.*, b.fabric_name, c.color_name ";
                        $qq .= " FROM fabprogram_print a ";
                        $qq .= " LEFT JOIN fabric b ON a.fabric_id = b.id ";
                        $qq .= " LEFT JOIN color c ON a.dyeing_color = c.id ";
                        $qq .= " WHERE a.sales_order_detalis_id = '". $sql['id'] ."' AND a.process_id = '". $res['process_id'] ."' ";
                        
                        $ry = mysqli_query($mysqli, $qq);
                        
                        $p=1;
                        while($nql = mysqli_fetch_array($ry)) {
                            
                            $hb = mysqli_query($mysqli, "SELECT * FROM fabprogram_print WHERE temp_id = ". $nql['temp_id']);
                            $yn_[$p] = "";
                            // while($ppf = mysqli_fetch_array($hb)) {
                            foreach(json_decode($nql['yarn_mixing']) as $ynn) {
                                $ynn = explode('=', $ynn);
                                // $ynn = explode('=', $ppf['yarn_mixing']);
                                $nsql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT yarn_name FROM mas_yarn WHERE id = '". $ynn[0] ."'"));
                                
                                $yrClr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT color_name FROM color WHERE id = '". $ynn[1] ."'"));
                                
                                $ycrrr = $yrClr['color_name'] ? ' - '.$yrClr['color_name'] : '';
                                
                                $yn_[$p] .= $nsql['yarn_name'].$ycrrr.' - '. $ynn[2].'%, ';
                                
                                
                            }
                            
                            ?>
                                <tr>
                                    <td style="padding:2px;padding-left: 8px;"><?= $nql['fabric_name']; ?></td>
                                    <td colspan="2"><?= rtrim($yn_[$p], ", "); ?></td>
                                    <td><?= $nql['loss_per']; ?>%</td>
                                    <td><?= $nql['color_name']; ?></td>
                                    <td><?= $nql['dia_wt']; ?></td>
                                    <td><?= $nql['req_wtt']; ?></td>
                                </tr>
                                
                            <?php $p++; 
                        } 
                        
                        print '<tr> <td style="padding:10px" colspan="7"></td> </tr>';
                } ?>
        </table>
    </div>

</body>

<!-- <script type="text/javascript">
    window.print();
    window.onfocus = function () { window.close(); }
</script> -->

<script>
    $(document).ready(function() {
        $(".answer").each(function() {
            var main = $(this).attr('data-main');
            // alert(main);
            
            $(".lossp" + main).each(function() {
                
                var leng = $(".lossp" + main).length;
                var lossP = $(this).val();
                var reqWt = $(".reqWt" + main).val();
                
                // alert(leng);
            });
        });
        return false;
    })
</script>



















