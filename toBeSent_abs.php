<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - To Be Sent Orders</title>

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

    <!-- sweetalert -->
    <link rel="stylesheet" type="text/css" href="src/plugins/sweetalert2/sweetalert2.css">

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

<style>
    .dhide {
        display:none;
    }
</style>

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                
                <div class="card-box mb-30">
					<div class="pd-20">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-blue">Order In Hand - Buyer Wise</h4>
                            </div>

                            <div class="col-md-6">
                                <a class="btn btn-light float-right" onclick="divToPrint('divToPrint')"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                <div class="btn-group mr-2 float-right" role="group" aria-label="First group">
                                    <a class="btn btn-info" href="toBeSent_abs.php"><i class="fa fa-file" aria-hidden="true"></i> Buyer Wise</a>
                                    <a class="btn btn-outline-info" href="toBeSent.php" ><i class="fa fa-file" aria-hidden="true"></i> Summary</a>
                                    <a class="btn btn-outline-info" href="order-in-hand.php" ><i class="fa fa-file" aria-hidden="true"></i> Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box mb-30">
                    <?php if(TOBE_SENT!=1) { action_denied(); exit; } ?>    
                </div>

                <!-- Export Datatable start -->
                <div class="card-box mb-30" id="divToPrint">
                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Order In Hand - Buyer Wise</h4>
                    </div>
                    <div class="pd-20" style="overflow-y:auto">
                        <table class="table hover table-bordered" id="example" border="1" style="border-collapse: collapse" style="width:100%">
                            
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Brand</th>
                                    <th>No of Orders</th>
                                    <th>PO Qty</th>
                                    <th>PO in Pcs</th>
                                    <th class="text-center">Exchange Value</th>
                                    <th class="text-center">INR Value (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                $qry = "SELECT a.*, b.brand_name, c.currency_name, c.currency_value, count(a.id) as tot_order, sum(d.total_excess) as total_excess_qty, sum(d.total_excess*e.part_count) as total_excess_unit_qty, sum(d.total_excess*d.price) as exchange_value,
                                        sum(d.total_excess*(d.price*f.currency_value)) as inr_value ";
                                $qry .= " FROM sales_order a ";
                                $qry .= " LEFT JOIN brand b ON a.brand = b.id ";
                                $qry .= " LEFT JOIN mas_currency c ON a.currency = c.id ";
                                $qry .= " LEFT JOIN sales_order_detalis d ON a.id = d.sales_order_id ";
                                $qry .= " LEFT JOIN unit e ON d.unit_id = e.id ";
                                $qry .= " LEFT JOIN mas_currency f ON a.currency = f.id ";
                                
                                $qry .= " WHERE a.is_dispatch IS NULL ";
                                
                                $qry .= " GROUP BY a.brand ORDER BY b.id DESC ";
                                $query = mysqli_query($mysqli, $qry);
                                
                                $x = 1;
                                $po = '';
                                $poo = $poo1 = $tt_ord = 0;
                                while ($sql = mysqli_fetch_array($query)) {
                                    
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><a class="custom_a brand_cls" data-brand="<?= $sql['brand']; ?>"><i class="icon-copy ion-chevron-right"></i> <?= $sql['brand_name']; ?></a></td>
                                        <td><?= $sql['tot_order']; $tt_ord += $sql['tot_order']; ?></td>
                                        <td><?= number_format($aa1 = $sql['total_excess_qty'], 0, '.', ','); $poo1 += $aa1; ?></td>
                                        <td><?= number_format($aa = $sql['total_excess_unit_qty'], 0, '.', ','); $poo += $aa; ?></td>
                                        <td class="text-right"><?= number_format($tott1[] = $sql['exchange_value'],2); ?></td>
                                        <td class="text-right"><?= number_format($tott2[] = $sql['inr_value'],2); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #f8f9fac7;" colspan="7" class="d-none brand_html<?= $sql['brand']; ?>">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sl.No</th>
                                                        <th>BO</th>
                                                        <th>Style</th>
                                                        <th>Style Image</th>
                                                        <th>Delivery Date</th>
                                                        <th>Price</th>
                                                        <th>Po Qty</th>
                                                        <th>PO in Pcs</th>
                                                        <th>Exchange Value</th>
                                                        <th>INR Value (₹)</th>
                                                </thead>
                                                <tbody class="resp_brand<?= $sql['brand']; ?>"></tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php $x++; } ?>
                            <!-- </tbody>
                            <tbody> -->
                                <tr>
                                    <th colspan="2" style="text-align:right">Total</th>
                                    <th><?= $tt_ord; ?></th>
                                    <th><?= number_format($poo1,2); ?></th>
                                    <th><?= number_format($poo,2); ?></th>
                                    <th class="text-right"><?= number_format(array_sum($tott1),2); ?></th>
                                    <th class="text-right">₹<?= number_format(array_sum($tott2),2); ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>
    
    
	
	<script>
        $(".brand_cls").click(function() {
            
            $("#overlay").fadeIn(100);
            
            var brand = $(this).data('brand');
            var ic = $(this).find('i');
            
            if(ic.hasClass('ion-chevron-right') == true) {
                
                var data = {
                    brand: brand,
                };
                
                $.post('ajax_search3.php?type=get_send_orders', data, function(msg) {
                    var j = $.parseJSON(msg);
                
                    setTimeout(function(){
                        $(".brand_html" + brand).removeClass('d-none');
                        $(".resp_brand" + brand).html(j.tbody);
                        ic.removeClass('ion-chevron-right').addClass('ion-chevron-down');
                        $("#overlay").fadeOut(500);
                    },300);
                });
            } else {
                setTimeout(function(){
                    $(".brand_html" + brand).addClass('d-none');
                    ic.removeClass('ion-chevron-down').addClass('ion-chevron-right');
                    $("#overlay").fadeOut(500);
                },200);
            }
	    });
	</script>

    <!-- <script type="text/javascript">
        function divToPrint() {
            var divToPrint = document.getElementById('divToPrint');
            var popupWin = window.open();
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();
        }
    </script> -->

<script type="text/javascript">

</html>