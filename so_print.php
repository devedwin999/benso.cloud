<?php include('includes/connection.php'); ?>
<?php include('includes/function.php'); ?>

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
    $a = "SELECT a.*, b.brand_name, c.merchand_name, sum(d.gsm) as gsm  ";
    $a .= " FROM sales_order a ";
    $a .= " LEFT JOIN brand b ON a.brand=b.id ";
    $a .= " LEFT JOIN merchand_detail c ON a.merchandiser=c.id ";
    $a .= " LEFT JOIN sales_order_detalis d ON d.sales_order_id=a.id ";
    $a .= " WHERE  a.id='" . $_REQUEST['id'] . "'";

    $qry = mysqli_query($mysqli, $a);

    $sql = mysqli_fetch_array($qry);
    ?>

    <div id="divToPrint">
        <table style="font-family: Calibri;border: 1px solid grey;padding: 10px;">
            <tr>
                <td>
                    <div style="width:100%">
                        <h3>BENSO</h3>
                    </div>
                    <div
                        style="width:100%;display: flex;flex-wrap: nowrap;justify-content: space-between;border-bottom: 1px solid gray;">
                        <b>ORDER SHEET</b>
                        <span style="float:Left">Print Date :
                            <?= date('d-m-Y'); ?>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table
                        style="font-size: ;width:1000px !important; padding-top: 15px;border-bottom: 1px solid gray;">
                        <tr>
                            <td style="font-weight: bold;">BO NO </td>
                            <td>:
                                <?= $sql['order_code'] ?>
                            </td>
                            <td></td>
                            <td style="font-weight: bold;">BUYER</td>
                            <td>: <?= $sql['brand_name']; ?></td>
                            <td></td>
                            <td style="font-weight: bold;">Qty</td>
                            <td>:
                                <?= $sql['order_qty'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">ORDER NO</td>
                            <td>:
                                <?= $sql['order_code'] ?>
                            </td>
                            <td></td>
                            <td style="font-weight: bold;">SEASON</td>
                            <td>: <?= $sql['season'] ? $sql['season'] : '-'; ?></td>
                            <td></td>
                            <!--<td style="font-weight: bold;">GSM</td>-->
                            <!--<td>: <?= $sql['gsm'] ? $sql['gsm'] : '-'; ?></td>-->
                            <td style="font-weight: bold;">ORDER TYPE</td>
                            <td>: <?= selection_type_name($sql['type']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">ORDER DATE</td>
                            <td>:
                                <?= date('d-m-Y', strtotime($sql['order_date'])); ?>
                            </td>
                            <td></td>
                            <td style="font-weight: bold;">CURRENCY</td>
                            <td>: <?= mas_currency_name($sql['currency']); ?></td>
                            <td></td>
                            <td style="font-weight: bold;">DEL. DATE</td>
                            <td>: <?= date('d-m-Y', strtotime($sql['delivery_date'])); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">MERCHANDISER</td>
                            <td>:
                                <?= $sql['merchand_name'] ?>
                            </td>
                            <td></td>
                            <td style="font-weight: bold;">
                                <!--BUY.DEPT-->
                            </td>
                            <td>
                                <!--: ###-->
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <?php

            $p1=1;
            $qry = mysqli_query($mysqli, "SELECT * FROM sales_order_detalis WHERE sales_order_id = '" . $sql['id'] . "' ");
            while ($row = mysqli_fetch_array($qry)) {
                ?>
                <tr>
                    <td>
                        <div style="border: 2px solid gray;display: flex;flex-wrap: nowrap;padding:10px">
                            <table style="width: 80%;">
                                <tr>
                                    <td style="font-weight: bold;">STYLE NO</td>
                                    <td>:
                                        <?= $row['style_no'] ?>
                                    </td>
                                    <td></td>
                                    <td style="font-weight: bold;">STYLE Desc.</td>
                                    <td>: <?= $row['style_des'] ? $row['style_des'] : '-'; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">STYLE QTY</td>
                                    <td>:
                                        <?= $row['total_qty'] ?> PCS
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">PROD.EXS%</td>
                                    <td>:
                                        <?= $row['excess'] ?>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">FABRIC</td>
                                    <td>: <?= $row['main_fabric'] ? fabric_name($row['main_fabric']) : '-'; ?> <?= $row['gsm'] ? ' || ' .$row['gsm'].' GSM' : ''; ?></td>
                                    <td colspan="3"></td>
                                </tr>
                            </table>

                            <div>
                                <img src="uploads/so_img/<?= $sql['order_code'] . '/' . $row['item_image'] ?>" alt=""
                                    style="height: 100px;">
                            </div>
                        </div>

                        <div>
                            <h4 style="text-decoration: underline;">ORDER QTY DETAILS :-</h4>
                        </div>

                        <div style="display: flex;justify-content: space-between;border-top: 1px solid;border-bottom: 1px solid gray;padding: 10px;">
                            <div>
                                <b>STYLE NO & DESC </b>
                                <span>:
                                    <?= $row['style_no'] ?>
                                </span>
                            </div>
                            
                            <div>
                                <span>
                                    <?php
                                        foreach(json_decode($row['part_detail']) as $dett) { 
                                            $exp = explode(',,', $dett); 
                                            $part = explode('=', $exp[0]);
                                            $color = explode('=', $exp[1]);
                                            $p_c[$p1][] = part_name($part[1]) .' : '. color_name($color[1]);
                                        } 
                                        print implode(' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', $p_c[$p1]); 
                                    ?>
                                </span>
                            </div>

                            <div>
                                <b>Del Dt </b>
                                <span>:
                                    <?= date('d-M-Y', strtotime($row['delivery_date'])); ?>
                                </span>
                            </div>
                        </div>

                        <div style="border-bottom: 1px solid gray;padding: 5px;">
                            <table style="width: 100%;">
                                <tr style="font-weight: bold;">
                                    <td style="width:10%">ColorDesc</td>
                                    <?php
                                    $cnt = count(json_decode($row['size_detail']));
                                    $pr = 80 / $cnt;
                                    foreach (json_decode($row['size_detail']) as $variation) {
                                        $a = explode(',,', $variation);
                                        $b = explode('=', $a[0]);
                                        $c = explode('=', $a[1]);

                                        $var_val = mysqli_fetch_array(mysqli_query($mysqli, "SELECT type FROM variation_value WHERE id='" . $b[1] . "'"));
                                        print '<td style="width:' . $pr . '%">' . $var_val['type'] . '</td>';
                                    }
                                    ?>
                                    <td style="width:10%">TOTAL QTY</td>
                                </tr>
                            </table>
                        </div>

                        <div style="border-bottom: 1px solid gray;padding: 5px;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width:10%">---</td>
                                    <?php
                                    $sTq = 0;
                                    foreach (json_decode($row['size_detail']) as $variation) {
                                        $a = explode(',,', $variation);
                                        $b = explode('=', $a[0]);
                                        $c = explode('=', $a[1]);

                                        // $b_qty[] = $c[1];
                                
                                        $sTq += $c[1];

                                        print '<td style="width:' . $pr . '%">' . $c[1] . '</td>';
                                    }
                                    ?>
                                    <td>
                                        <?= $sTq; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style="border-bottom: 1px solid gray;padding: 5px;padding-bottom: 50px;">
                            <table style="width: 100%;">
                                <tr style="font-weight: bold;">
                                    <td style="width:10%">STYLE WISE TOTAL</td>
                                    <?php
                                    $sTq1 = 0;
                                    foreach (json_decode($row['size_detail']) as $variation) {
                                        $a = explode(',,', $variation);
                                        $b = explode('=', $a[0]);
                                        $c = explode('=', $a[1]);

                                        $sTq1 += $c[1];

                                        print '<td style="width:' . $pr . '%">' . $c[1] . '</td>';
                                    }
                                    ?>
                                    <td>
                                        <?= $sTq1; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style="border-bottom: 1px solid gray;padding: 5px;padding-bottom: 50px;">
                            <table style="width: 100%;">
                                <tr style="font-weight: bold;">
                                    <td style="width:10%">QTY WITH ALLOWANCE</td>
                                    <?php
                                    $exs_sm = 0;
                                    foreach (json_decode($row['size_detail']) as $variation) {
                                        $a = explode(',,', $variation);
                                        $b = explode('=', $a[0]);
                                        $c = explode('=', $a[1]);
                                        $exss = explode('=', $a[2]);

                                        $exs = $c[1] + round(($c[1] / 100) * $exss[1]);

                                        $exs_sm += $exs;

                                        $b_qty[] = $exs;

                                        print '<td style="width:' . $pr . '%">' . $exs . '</td>';
                                    }
                                    ?>
                                    <td>
                                        <?= $exs_sm; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            <?php  $p1++; } ?>

            <tr>
                <td>
                    <div
                        style="display: flex;justify-content: space-around;font-weight: bold;border-bottom: 1px solid gray;padding: 10px;">
                        <span>GRAND TOTAL QTY</span>
                        <span>
                            <?= array_sum($b_qty); ?>
                        </span>
                    </div>
                </td>
            </tr>

            <tr>
                <td style="padding-top: 50px;">
                    <div
                        style="display: flex;font-weight: bold;border: 1px solid gray;padding-top: 40px;padding-left: 50px;padding-bottom: 10px;">
                        <div style="width:33%">
                            <span>Prepared By</span>
                        </div>
                        <div style="width:33%">
                            <span>Verified By</span>
                        </div>
                        <div style="width:33%">
                            <span>Authorised Signatory</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

<!-- <script type="text/javascript">
    window.print();
    window.onfocus = function () { window.close(); }
</script> -->