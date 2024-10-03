<?php include('includes/connection.php'); ?>

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
                    <center><b>
                            <?= $sql['process_name']; ?> - Printing</center></b>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table
                        style="font-size: ;width:1000px !important; padding-top: 15px;border-bottom: 1px solid gray;">
                        <tr>
                            <td style="border: 1px solid gray;" colspan="4">
                                <table>
                                    <tr>
                                        <td style="font-weight:bold"> From : </td>
                                    </tr>
                                    <tr>
                                        <td> Benso Garmenting </td>
                                    </tr>
                                    <tr>
                                        <td> Tirpur </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="border: 1px solid gray;" colspan="4">
                                <table>
                                    <tr>
                                        <td style="font-weight:bold"> To : </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $sql['company_name']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $sql['address1']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">DC NO </td>
                            <td style="border-right: 1px solid gray;">:
                                <?= $sql['processing_code'] ?>
                            </td>
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
                                <?= date('d-m-y', strtotime($sql['created_date'])); ?>
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

            <tr style="display:none">
                <td colspan="8">
                    <div style="border: 2px solid gray;display: flex;flex-wrap: nowrap;padding:10px">
                        <table style="width: 100%;text-align: center;">
                            <thead>
                                <tr>
                                    <th>Bo.No</th>
                                    <th>Style No</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Boundle No</th>
                                    <th>Boundle Qty</th>
                                </tr>
                            </thead>
                            <?php
                            foreach (explode(',', $sql['boundle_id']) as $key => $value) {

                                $qry = "SELECT a.*, b.order_code, c.style_no, d.color_name, e.type, b.order_id, b.style ";
                                $qry .= "FROM bundle_details a ";
                                $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
                                $qry .= "LEFT JOIN color d ON b.color=d.id ";
                                $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
                                $qry .= "WHERE a.id='" . $value . "' ";

                                $sql1 = mysqli_fetch_array(mysqli_query($mysqli, $qry));

                                $x = $key + 1;
                                print '<tr>';
                                print '<td>' . $sql1['order_code'] . '</td>';
                                print '<td>' . $sql1['style_no'] . '</td>';
                                print '<td>' . $sql1['color_name'] . '</td>';
                                print '<td>' . $sql1['type'] . '</td>';
                                print '<td>' . $sql1['bundle_number'] . '</td>';
                                print '<td>' . $sql1['pcs_per_bundle'] . '</td>';
                                print '</tr>';

                                $oc[] = $sql1['order_id'] . '-' . $sql1['style'] . '-' . $sql1['variation_value'] . '-' . $sql1['bundle_number'] . '*';
                            }
                            ?>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table>
                        <?php
                        $ol = "SELECT a.*,count(b.style) as noOf , b.order_id, b.style, c.order_code, d.style_no, e.part_name, b.part, f.color_name ";
                        $ol .= " FROM bundle_details a ";
                        $ol .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                        $ol .= " LEFT JOIN sales_order c ON b.order_id=c.id ";
                        $ol .= " LEFT JOIN sales_order_detalis d ON b.style=d.id ";
                        $ol .= " LEFT JOIN part e ON b.part=e.id ";
                        $ol .= " LEFT JOIN color f ON b.color=f.id ";
                        $ol .= " WHERE a.id IN (" . $sql['boundle_id'] . ") GROUP BY b.part";
                        // print $ol;
                        
                        $hn = mysqli_query($mysqli, $ol);
                        while ($iop = mysqli_fetch_array($hn)) {
                            // print '<br>.' . $iop['style'];
                            ?>
                            <tr>
                                <td>
                                    <div style="border: 1px solid gray;">
                                        <div style="width:1000px; display:flex;border-bottom: 1px solid gray;">
                                            <div style="width:25%;padding: 10px;">
                                                <b>BO NO :</b>
                                                <?= $iop['order_code'] ?>
                                            </div>
                                            <div style="width:25%;padding: 10px;"> <b>STYLE :</b>
                                                <?= $iop['style_no'] ?>
                                            </div>
                                            <div style="width:25%;padding: 10px;"> <b>Part :</b>
                                                <?= $iop['part_name'] ?>
                                            </div>
                                            <div style="width:25%;padding: 10px;">
                                                <b>No OF Bundle :</b>
                                                <?= $iop['noOf'] ?>
                                            </div>
                                        </div>
                                        <div style="width: 100%;padding: 8px;">
                                            <div style="display:flex">
                                                <div style="width: 70px;"> <b>Color</b> </div>
                                                <?php
                                                $ol1 = "SELECT a.*,c.type, sum(a.pcs_per_bundle) as total ";
                                                $ol1 .= " FROM bundle_details a ";
                                                $ol1 .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                                $ol1 .= " LEFT JOIN variation_value c ON a.variation_value=c.id ";
                                                $ol1 .= " WHERE b.order_id='" . $iop['order_id'] . "' AND b.style='" . $iop['style'] . "' AND b.part='" . $iop['part'] . "' AND a.id IN (" . $sql['boundle_id'] . ") GROUP BY a.variation_value ";

                                                $ret = mysqli_query($mysqli, $ol1);

                                                while ($fgh = mysqli_fetch_array($ret)) {
                                                    print '<div style="width: 70px;">' . $fgh['type'] . '</div>';

                                                    $total[$fgh['type']] = $fgh['total'];
                                                }

                                                ?>
                                            </div>
                                            <div style="display:flex">
                                                <div style="width: 70px;"><?= $iop['color_name']; ?></div>
                                                <?php
                                                $ol1 = "SELECT a.*,c.type, sum(a.pcs_per_bundle) as total ";
                                                $ol1 .= " FROM bundle_details a ";
                                                $ol1 .= " LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                                $ol1 .= " LEFT JOIN variation_value c ON a.variation_value=c.id ";
                                                $ol1 .= " WHERE b.order_id='" . $iop['order_id'] . "' AND b.style='" . $iop['style'] . "' AND b.part='" . $iop['part'] . "' AND a.id IN (" . $sql['boundle_id'] . ") GROUP BY a.variation_value ";

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