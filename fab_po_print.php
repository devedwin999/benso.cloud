<?php
include ("includes/connection.php");
include ("includes/function.php");

include ("includes/perm.php");


$sql_main = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM fabric_po WHERE id=" . $_GET['id']));
?>
<!DOCTYPE html>
<html>

<head>

    <style>
        .table th {
            border-top: 0px solid #dee2e6 !important;
        }

        .table_border {
            border: 1px solid #dee2e6;
        }

        .bold {
            font-weight: 900;
        }

        .background {
            background-color: blue;
        }
    </style>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Add Budget
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

    <?php include ('includes/header.php'); ?>

    <?php include ('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="pd-20 card-box mb-30">
                    <?php //if(BUDGET_ADD!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Fabric Print </span>
                            <a class="btn btn-outline-primary" href="fab_po_list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i>Process PO List</a>&nbsp;
                            <button class="btn btn-outline-primary" href="#" style="float: right;margin-right: 4%;"><i
                                    class="fa fa-print" onclick="window.print()" aria-hidden="true"></i>Print</button>
                        </h4>
                    </div>
                    <?php
                    $qry = "SELECT * FROM  company  WHERE id = '". $logUnit ."'";
                    $query = mysqli_query($mysqli, $qry);
                    $sql = mysqli_fetch_array($query)
                        ?>
                    <div class="row">
                        <table class="table_border table" width="100%">
                            <tr>
                                <td colspan="3">
                                    <div class="bold text-blue h4"><?= $sql['company_name']; ?> </div>
                                    <div> <?= $sql['address1']; ?> <br><?= $sql['address2']; ?> </div>
                                    <div> Phone : <?= $sql['mobile']; ?> / <?= $sql['phone1']; ?> </div>
                                    <div> GSTIN : <?= $sql['gst_no']; ?> </div>
                                    <div> Email : <?= $sql['mail_id']; ?> </div>
                                </td>
                                                               
                                <td colspan="3">
                                    <div> <b>PO No :</b> <?= $sql_main['entry_number']; ?>  </div>
                                    <div> <b>PO Date :</b><?= date('d-m- Y', strtotime($sql_main['entry_date'])); ?>  </div>
                                </td>
                            </tr>
                      
                            <tr>
                                <?php
                                    $supp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM supplier WHERE id = '". $sql_main['supplier'] ."'"));
                                ?>
                                <td colspan="3">
                                    <div class="background" style="width: 100%;color:white;padding: 5px 0px 5px 2px;"> PURCHASE ORDER TO : <?= $supp['supplier_name']; ?></div>
                                    <div> Address : <?= $supp['address1']; ?></div>
                                    <div> Phone : <?= $supp['phone1'] ? $supp['phone1'] : '-'; ?></div>
                                    <div> GSTIN : <?= $supp['gst_no'] ? $supp['gst_no'] : '-'; ?></div>
                                    <div> Email : <?= $supp[''] ? $supp[''] : '-'; ?></div>
                                    <div> Place of Supply : <?= $supp[''] ? $supp[''] : '-'; ?></div>
                                </td>
                                <?php
                                    if($sql_main['ship_to']!="") {
                                    
                                    $comp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM company WHERE id = '". $sql_main['ship_to'] ."'"));
                                ?>
                                    <td colspan="3">
                                        <div class="background" style="width: 100%;color:white;padding: 5px 0px 5px 2px;">SHIP TO : <?= $comp['company_name']; ?></div>
                                        <div>Address : <?= $comp['address1']; ?></div>
                                        <div>Phone &nbsp;&nbsp;&nbsp;&nbsp;: <?= $comp['phone1']; ?></div>
                                        <div>GSTIN &nbsp;&nbsp;&nbsp;&nbsp;: <?= $comp['gst_no']; ?></div>
                                        <div>Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: -</div>
                                    </td>
                                <?php } ?>
                            </tr>


                            <tr class="background" style="color:white;">
                                <th> Sno </th>
                                <th> Item </th>
                                <th> Quantity</th>
                                <th> Price per Unit</th>
                                <th> Tax Unit</th>
                                <th> Amount</th>
                            </tr>

                            <?php
                            if (isset($_GET['id'])) {

                                $qry1 = "SELECT a.*, b.order_code, c.process_name, f.full_name, c.budget_type, a.material_name, a.color_ref, a.tax_per, d.yarn_id, d.fabric_id, d.color as color_id, d.dia_size, d.yarn_mixing ";
                                $qry1 .= " FROM fabric_po_det a ";
                                $qry1 .= " LEFT JOIN sales_order b ON b.id = a.order_id ";
                                $qry1 .= " LEFT JOIN process c ON c.id = a.po_stage ";
                                $qry1 .= " LEFT JOIN fabric_requirements d ON d.id = a.material_name ";
                                // $qry1 .= " LEFT JOIN color e ON e.id = a.color_ref ";
                                $qry1 .= " LEFT JOIN tax_main f ON f.id = a.tax_per ";
                                $qry1 .= " WHERE a.fab_po = '" . $_GET['id'] . "' ";

                                $temp1 = mysqli_query($mysqli, $qry1);

                                $i = 1;
                                while ($row = mysqli_fetch_array($temp1)) {
                                    if($row['stock_bo']=='bo') {
                                        if($row['budget_type'] == 'Yarn') {
                                            
                                            $td3 = mas_yarn_name($row['yarn_id']);
                                            
                                        } else if($row['budget_type'] == 'Fabric') {
                                            
                                            $opp = '';
                                            foreach(json_decode($row['yarn_mixing']) as $expp) {
                                                $exp = explode('=', $expp);
                                                
                                                $opp .= " || ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                                            }
                                            
                                            $td3 = fabric_name($row['fabric_id']) .'|| '. $opp .' || Dia: '. $row['dia_size'] .'.';
                                        } else {
                                            $td3 = '';
                                        }
                                        $order_code = sales_order_code($row['order_id']);
                                    } else {
                                        $order_code = stockgroup_name($row['order_id']);
                                        
                                        if(in_array($row['po_stage'], array('26', '27', '28'))) {
                                            $td3 = mas_yarn_name($row['material_name']);
                                        } else {
                                            $stk = mysqli_fetch_array(mysqli_query($mysqli, "SELECT fabric_name, yarn_mixing FROM mas_stockitem WHERE id = '". $row['material_name'] ."'"));
                                                
                                            $opp = '';
                                            foreach(json_decode($stk['yarn_mixing']) as $expp) {
                                                $exp = explode('=', $expp);
                                                
                                                $opp .= " || ". mas_yarn_name($exp[0]) .' - '. color_name($exp[1]) .' - '. $exp[2] .'%';
                                            }
                                            
                                            $diaa = ($row['stock_dia']>0) ? ' Dia : '. $row['stock_dia'] : '';
                                            
                                            $td3 = fabric_name($stk['fabric_name']) . $opp . $diaa;
                                            
                                        }
                                        
                                    }
                                    
                                    $gf = $row['id'] . ",'fabric_po_det'";
                                    $gf1 = $row['id'];
                                    $unity = $row['full_name'] ? $row['full_name'] : '-';
                                    print '<tr class="td_edDl"><td>' . $i . '</td>                                                    
                                                    <td>' . $td3 . '</td>
                                                    <td>'. $poq[] = $row['po_qty_wt'] .'</td>
                                                    <td>'. $rat[] = $row['rate'] .'</td>
                                                    <td>' . $unity . '</td>                                                    
                                                    <td style="text-align:right">' . number_format($row['po_qty_wt'] * $row['rate'], 2) . '</td></tr>';

                                                    $amt[] = $row['po_qty_wt'] * $row['rate'];
                                    $i++;
                                }

                                ?>
                                <tr>
                                <td   class="background"></td>
                                    <td class="background" style="color:white;" style="text-align:right">Total:</td>
                                    <td class="background" style="color:white;" style=""><?= number_format(array_sum($poq),2); ?></td>
                                    <td  class="background" style="color:white;"><?= number_format(array_sum($rat),2); ?></td>
                                    <td  class="background"></td>
                                    
                                    <td class="background" style="color:white;"style="text-align:right"><?= number_format($amtt = array_sum($amt), 2); ?></td>
                                </tr>

                                <tr>
                                    <td  colspan="2"></td>
                                    <td  colspan="4">
                                        <table class="table">
                                            <?php

                                            $gt = [];
                                            $rew = "SELECT sum((a.percentage/100)*b.amount) as pamt, a.full_name, a.percentage ";
                                            $rew .= " FROM tax_sub a ";
                                            $rew .= " LEFT JOIN fabric_po_det b ON a.tax_main=b.tax_per ";
                                            $rew .= " WHERE b.fab_po = '" . $_GET['id'] . "' GROUP BY a.id ASC";
                                            // print $rew;
                                            $ff = mysqli_query($mysqli, $rew);
                                            if (mysqli_num_rows($ff) > 0) {
                                                while ($po = mysqli_fetch_array($ff)) {
                                                    print '<tr><td>' . $po['full_name'] . ' - ' . $po['percentage'] . ' %</td><td style="text-align:right">' . number_format($po['pamt'], 2) . '</td></tr>';
                                                    $gt[] = $po['pamt'];
                                                }
                                            }


                                            $rew = "SELECT b.expense_name, a.expense_amount ";
                                            $rew .= " FROM fabric_po_expense a ";
                                            $rew .= " LEFT JOIN expense_main b ON a.expense_name=b.id ";
                                            $rew .= " WHERE a.fabric_po = '" . $_GET['id'] . "' ";
                                            // print $rew;
                                            $ff = mysqli_query($mysqli, $rew);
                                            if (mysqli_num_rows($ff) > 0) {
                                                while ($po = mysqli_fetch_array($ff)) {
                                                    print '<tr><td>' . $po['expense_name'] . '</td><td style="text-align:right">' . number_format($po['expense_amount'], 2) . '</td></tr>';
                                                    $gt[] = $po['expense_amount'];
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td style="font-size:20px;">Grand Total :</td>
                                                <td style="text-align:right;font-size:20px;">
                                                    <?= number_format((array_sum($gt) + $amtt), 2); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                               
                            <?php } ?>
                            <tr>
                                <td>
                                    <div> Note </div>
                                    <div> 1. Note is benso </div>
                                </td>
                            </tr>
                            <tr>
                                <td> <br>
                                    <div> Note </div>
                                    <div> 1. Note is benso </div>
                                    <div> 2. Note is benso </div>
                                    <div> 3. Note is benso </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <div>Customer Signature </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td><br>
                                    <div>Authorised Signatory For </div>
                                    <div>Benson </div><br><br>
                                    <div> Thank You For Your Business! </div>
                                </td>
                            </tr>

                            <table>
                    </div>
                </div>
            </div>

            <?php include ('includes/footer.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include ('includes/end_scripts.php'); ?>



</body>

</html>