<?php include('includes/connection.php'); ?>
<?php include('includes/function.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
    integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

<style>
    .d-flex {
        display: flex;
    }
</style>

<body>

    <?php
    $a = "SELECT a.*,b.company_name, b.address1, c.process_name ";
    $a .= " FROM processing_list a ";
    $a .= " LEFT JOIN company b ON a.production_unit=b.id ";
    $a .= " LEFT JOIN process c ON a.process_id=c.id ";
    $a .= " WHERE  a.id='" . $_REQUEST['id'] . "'";

    $qry = mysqli_query($mysqli, $a);

    $sql = mysqli_fetch_array($qry);
    ?>
    
    <div id="divToPrint">
        <table style="font-family: Calibri;border: 1px solid grey;padding: 10px;">
            <tr>
                <td colspan="8">
                    <center><b><?= $sql['process_name']; ?></b></center>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table
                        style="font-size: ;width:1000px !important; padding-top: 15px;border-bottom: 1px solid gray;">
                        <tr>
                            <td style="border: 1px solid gray;padding:5px;" colspan="4">
                                <p>From: </p>
                                <?= company_address($sql['created_unit']); ?>
                            </td>
                            <td style="border: 1px solid gray;padding:5px;" colspan="4">
                                <p>To: </p>
                                <?php if($sql['input_type'] == 'Supplier') { 
                                    print supplier_address($sql['assigned_emp']);
                                } else if($sql['input_type'] == 'Employee') { 
                                    print employee_name($sql['assigned_emp']);
                                } else if($sql['input_type'] == 'Unit') { 
                                    print company_address($sql['assigned_emp']);
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">DC NO </td>
                            <td style="border-right: 1px solid gray;">: <?= $sql['processing_code'] ?></td>
                            <td></td>
                            <td style="font-weight: bold;"></td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: bold;"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <?php
                            foreach (explode(',', $sql['boundle_id']) as $k => $v) {
                                $qry = "SELECT a.* ";
                                $qry .= "FROM bundle_details a ";
                                $qry .= "WHERE a.id='" . $v . "' ";

                                $sql1 = mysqli_fetch_array(mysqli_query($mysqli, $qry));

                                $ct[] = $sql1['pcs_per_bundle'];
                                $bn[] = $sql1['bundle_number'];
                            }
                            ?>
                            <td style="font-weight: bold;">DC Date</td>
                            <td style="border-right: 1px solid gray;">:
                                <?= $sql['entry_date'] ?>
                            </td>
                            <td></td>
                            <td style="font-weight: bold;">No. of Bundle :
                                <?= count($bn); ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td style="font-weight: bold;">No. of Pcs :
                                <?= array_sum($ct); ?>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td colspan="8">
                    <table>
                        <?php
                        $ol = "SELECT a.*, count(b.style) as noOf ";
                        $ol .= " FROM bundle_details a ";
                        $ol .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                        $ol .= " WHERE a.id IN (" . $sql['boundle_id'] . ")";
                        
                        $hn = mysqli_query($mysqli, $ol);
                        while ($iop = mysqli_fetch_array($hn)) {
                            // print '<br>.' . $iop['style'];
                            ?>
                            <tr>
                                <td>
                                    <div style="border: 1px solid gray;">
                                        <div style="width:1000px; display:flex;border-bottom: 1px solid gray;">
                                            <div style="width:25%;padding: 10px;"> <b>BO NO :</b><?= sales_order_code($iop['order_id']); ?></div>
                                            <div style="width:25%;padding: 10px;"> <b>STYLE :</b><?= sales_order_style($iop['style_id']); ?></div>
                                            <div style="width:25%;padding: 10px;"> <b>Part :</b><?= part_name($iop['part']); ?></div>
                                            <div style="width:25%;padding: 10px;"> <b>No OF Bundle :</b><?= $iop['noOf'] ?></div>
                                        </div>
                                        
                                        <div style="width: 100%;padding: 8px;">
                                            <div style="display:flex">
                                                <div style="width: 70px;"> <b>Color</b> </div>
                                                <?php
                                                $ol1 = "SELECT a.*,c.type, sum(a.pcs_per_bundle) as total ";
                                                $ol1 .= " FROM bundle_details a ";
                                                $ol1 .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                                $ol1 .= " LEFT JOIN variation_value c ON a.variation_value=c.id ";
                                                $ol1 .= " WHERE a.order_id='" . $iop['order_id'] . "' AND a.style_id='" . $iop['style_id'] . "' AND a.part='" . $iop['part'] . "' AND a.id IN (" . $sql['boundle_id'] . ") GROUP BY a.variation_value ";
                                                
                                                $ret = mysqli_query($mysqli, $ol1);
                                                
                                                while ($fgh = mysqli_fetch_array($ret)) {
                                                    print '<div style="width: 70px;">' . $fgh['type'] . '</div>';
                                                    
                                                    $total[$fgh['type']] = $fgh['total'];
                                                }
                                                
                                                ?>
                                            </div>
                                            <div style="display:flex">
                                                <div style="width: 70px;"><?= color_name($iop['color']); ?></div> 
                                                <?php
                                                $ol1 = "SELECT a.*,c.type, sum(a.pcs_per_bundle) as total ";
                                                $ol1 .= " FROM bundle_details a ";
                                                $ol1 .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                                $ol1 .= " LEFT JOIN variation_value c ON a.variation_value=c.id ";
                                                $ol1 .= " WHERE a.order_id='" . $iop['order_id'] . "' AND a.style_id='" . $iop['style_id'] . "' AND a.part='" . $iop['part'] . "' AND a.id IN (" . $sql['boundle_id'] . ") GROUP BY a.variation_value ";

                                                $ret = mysqli_query($mysqli, $ol1);

                                                while ($fgh = mysqli_fetch_array($ret)) {
                                                    print '<div style="width: 70px;">' . $fgh['total'] . '</div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td><br><br><br><br>Prepared By</td>
                <td></td>
                <td><br><br><br><br>Verfied By</td>
                <td></td>
                <td><br><br><br><br>Approved By</td>
                <td></td>
                <td><br><br><br><br>Receiver'S Sign</td>
                <td></td>
            </tr>
        </table>
    </div>
</body>

<!-- <script type="text/javascript">
    window.print();
    window.onfocus = function () { window.close(); }
</script> -->