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
    <title>BENSO GARMENTING - Dashboard</title>

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
    <link rel="stylesheet" type="text/css" href="src/plugins/jvectormap/jquery-jvectormap-2.0.3.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>

<style>
    .nav-tabs {
        border-bottom: none !important;
    }
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <?php
    $tott = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(pcs_per_bundle) as pcs_per_bundle FROM bundle_details WHERE entry_date ='" . date('Y-m-d') . "'"));

    ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 ">
            <div class="page-header" style="height: 65px;">
				<div class="row">
					<div class="col-md-10 col-sm-12">
						<div class="title">
							<h4 class="text-center" style="color: #1b00ff;">Payment Planning</h4>
						</div>
					</div>
					<div class="col-md-2 col-sm-12 text-right">
						<div class="dropdown">
							<a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown" style="margin-top:-6px;"><?= date('F Y'); ?></a>
							<!--<div class="dropdown-menu dropdown-menu-right">-->
							<!--	<a class="dropdown-item" href="#">Export List</a>-->
							<!--	<a class="dropdown-item" href="#">Policies</a>-->
							<!--	<a class="dropdown-item" href="#">View Assets</a>-->
							<!--</div>-->
						</div>
					</div>
				</div>
			</div>
            
            
            <div class="row">
                
                <div class="col-xl-2 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="" style="text-align: center;font-size: 45px;">
                                    <i class="icon-copy fa fa-rupee text-blue" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0 text-blue">0</div>
                                <input type="hidden" name="" value="13" id="chart1_percent">
                                <!--<div class="weight-600 font-14">Fabric</div>-->
                                <h5 class="text-blue padding-top-10 h5">Fabric</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="" style="text-align: center;font-size: 45px;">
                                    <i class="icon-copy fa fa-rupee text-light-green" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0 text-light-green">0</div>
                                <!--<div class="weight-600 font-14">Store</div>-->
                                <h5 class="text-light-green padding-top-10 h5">Store</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="" style="text-align: center;font-size: 45px;">
                                    <i class="icon-copy fa fa-rupee text-light-orange" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0 text-light-orange prod_tot">0</div>
                                <!--<div class="weight-600 font-14">Production</div>-->
                                <h5 class="text-light-orange padding-top-10 h5">Production</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="" style="text-align: center;font-size: 45px;">
                                    <i class="icon-copy fa fa-rupee text-warning" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0 text-warning">0</div>
                                <!--<div class="weight-600 font-14">Others</div>-->
                                <h5 class="text-warning padding-top-10 h5">Maintanance</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2 mb-30">
                    <div class="card-box height-100-p widget-style1">
                        <div class="d-flex flex-wrap align-items-center">
                            <div class="progress-data">
                                <div id="" style="text-align: center;font-size: 45px;">
                                    <i class="icon-copy fa fa-rupee text-light-purple" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="widget-data">
                                <div class="h4 mb-0 text-light-purple">0</div>
                                <!--<div class="weight-600 font-14">Others</div>-->
                                <h5 class="text-light-purple padding-top-10 h5">Others</h5>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Supplier Wise Bill Details</h2>
                <div class="tab">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#fabric" role="tab" aria-selected="true">Fabric</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Store" role="tab" aria-selected="false">Store</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light-orange" data-toggle="tab" href="#Production" role="tab" aria-selected="false">Production</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Maintanance" role="tab" aria-selected="false">Maintanance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Others" role="tab" aria-selected="false">Others</a>
                        </li>
                    </ul>




                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="fabric" role="tabpanel">
                            <div class="pd-20">
                                fabric
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="Store" role="tabpanel">
                            <div class="pd-20">
                                Store
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="Production" role="tabpanel">
                            <div class="pd-20">
                                <table class="table hover multiple-select-row data-table-export nowrap">
                                    <thead>
                                        <tr>
                                            <th class="table-plus datatable-nosort">S.No</th>
                                            <th>Entry Number</th>
                                            <th>Entry Date</th>
                                            <th>Bill Type</th>
                                            <th>Bill Number</th>
                                            <th>Bill Date</th>
                                            <th>Supplier</th>
                                            <th>Bill Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Bill Image</th>
                                            <th>Rate Approved Image</th>
                                            <th>Remarks</th>
                                            <th>Bill Passing Status</th>
                                            <th>Bill Approval Status</th>
                                            <th>Paid Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $qry = "SELECT a.*, b.supplier_name ";
                                        $qry .= " FROM bill_receipt a ";
                                        $qry .= " LEFT JOIN supplier b ON a.supplier = b.id ";
                                        $qry .= " WHERE a.payment_status != 'paid' ";
                                        $qry .= " ORDER BY a.id DESC ";
                                        $query = mysqli_query($mysqli, $qry);
                                        $x = 1;
                                        while ($sql = mysqli_fetch_array($query)) {
                                            
                                            $stat = array('pending' => 'warning', 'rejected' => 'warning', 'approved' => 'success', 'passed' => 'success', 'not paid' => 'warning');
                                            ?>
                                            <tr>
                                                <td><?= $x; ?></td>
                                                <td><?= $sql['entry_number']; ?></td>
                                                <td><?= date('y-m-d', strtotime($sql['entry_date'])); ?></td>
                                                <td><?= $sql['bill_type']; ?></td>
                                                <td><?= $sql['bill_number']; ?></td>
                                                <td><?= date('y-m-d', strtotime($sql['bill_date'])); ?></td>
                                                <td><?= $sql['supplier_name']; ?></td>
                                                <td><?= $bamt[] = $sql['bill_amount']; ?></td>
                                                <td><?= $sql['paid_amt'] ? $sql['paid_amt'] : '0.00'; ?></td>
                                                <td><?php if($sql['bill_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['bill_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                                <td><?php if($sql['approved_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['approved_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                                <td><?= $sql['comments'] ?  $sql['comments'] : '-'; ?></td>
                                                <td>
                                                    <span class="border border-<?= $stat[$sql['status']]; ?> rounded text-<?= $stat[$sql['status']]; ?>"><?= ucfirst($sql['status']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="border border-<?= $stat[$sql['approval_status']]; ?> rounded text-<?= $stat[$sql['approval_status']]; ?>"><?= ucfirst($sql['approval_status']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="border border-<?= $stat[$sql['payment_status']]; ?> rounded text-<?= $stat[$sql['payment_status']]; ?>"><?= ucfirst($sql['payment_status']); ?></span>
                                                </td>
                                            </tr>
                                            <?php $x++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Maintanance" role="tabpanel">
                            <div class="pd-20">
                                Maintanance
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Others" role="tabpanel">
                            <div class="pd-20">
                                Others
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" id="production_tot" value="<?= array_sum($bamt); ?>">
            </div>
            
        </div>
        
        <div class="pd-ltr-20 ">
            <div class="page-header" style="height: 65px;">
				<div class="row">
					<div class="col-md-10 col-sm-12">
						<div class="title">
							<h3 class="text-center" style="color: #23a305;">Unit Running Cost</h3>
						</div>
					</div>
				</div>
			</div>
            
            
            <div class="row">
                
                <?php
                
                $clr = array( 0 => 'blue', 1 => 'light-green', 2 => 'light-orange', 3 => 'warning', 4 => 'light-purple', 5 => 'blue', 6 => 'light-green', 7 => 'light-orange');
                
                    $unt = mysqli_query($mysqli, "SELECT * FROM company ORDER BY id ASC");
                    
                    $m=0;
                    while($row = mysqli_fetch_array($unt)) {
                ?>
                    
                    <div class="col-xl-2 mb-30">
                        <div class="card-box height-100-p widget-style1">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="progress-data">
                                    <div id="" style="text-align: center;font-size: 45px;">
                                        <i class="icon-copy fa fa-rupee text-<?= $clr[$m]; ?>" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="widget-data">
                                    <div class="h4 mb-0 text-<?= $clr[$m]; ?>">0</div>
                                    <h5 class="text-<?= $clr[$m]; ?> padding-top-10 h5"><?= $row['company_code']; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $m++; } ?>
                
            </div>
            
            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Supplier Wise Bill Details</h2>
                <div class="tab">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#fabric" role="tab" aria-selected="true">Fabric</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Store" role="tab" aria-selected="false">Store</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light-orange" data-toggle="tab" href="#Production" role="tab" aria-selected="false">Production</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Maintanance" role="tab" aria-selected="false">Maintanance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Others" role="tab" aria-selected="false">Others</a>
                        </li>
                    </ul>




                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="fabric" role="tabpanel">
                            <div class="pd-20">
                                fabric
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="Store" role="tabpanel">
                            <div class="pd-20">
                                Store
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="Production" role="tabpanel">
                            <div class="pd-20">
                                <table class="table hover multiple-select-row data-table-export nowrap">
                                    <thead>
                                        <tr>
                                            <th class="table-plus datatable-nosort">S.No</th>
                                            <th>Entry Number</th>
                                            <th>Entry Date</th>
                                            <th>Bill Type</th>
                                            <th>Bill Number</th>
                                            <th>Bill Date</th>
                                            <th>Supplier</th>
                                            <th>Bill Amount</th>
                                            <th>Bill Image</th>
                                            <th>Rate Approved Image</th>
                                            <th>Remarks</th>
                                            <th>Bill Passing Status</th>
                                            <th>Bill Approval Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $qry = "SELECT a.*, b.supplier_name ";
                                        $qry .= " FROM bill_receipt a ";
                                        $qry .= " LEFT JOIN supplier b ON a.supplier = b.id ";
                                        $qry .= " ORDER BY a.id DESC ";
                                        $query = mysqli_query($mysqli, $qry);
                                        $x = 1;
                                        while ($sql = mysqli_fetch_array($query)) {
                                            
                                            $stat = array('pending' => 'warning', 'rejected' => 'warning', 'approved' => 'success', 'passed' => 'success');
                                            ?>
                                            <tr>
                                                <td><?= $x; ?></td>
                                                <td><?= $sql['entry_number']; ?></td>
                                                <td><?= date('y-m-d', strtotime($sql['entry_date'])); ?></td>
                                                <td><?= $sql['bill_type']; ?></td>
                                                <td><?= $sql['bill_number']; ?></td>
                                                <td><?= date('y-m-d', strtotime($sql['bill_date'])); ?></td>
                                                <td><?= $sql['supplier_name']; ?></td>
                                                <td><?= $bamt[] = $sql['bill_amount']; ?></td>
                                                <td><?php if($sql['bill_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['bill_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                                <td><?php if($sql['approved_image']=="") { print '-'; } else { ?><a href="download.php?f=<?= $sql['approved_image']; ?>" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download</a><?php } ?></td>
                                                <td><?= $sql['comments'] ?  $sql['comments'] : '-'; ?></td>
                                                <td>
                                                    <span class="border border-<?= $stat[$sql['status']]; ?> rounded text-<?= $stat[$sql['status']]; ?>"><?= ucfirst($sql['status']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="border border-<?= $stat[$sql['approval_status']]; ?> rounded text-<?= $stat[$sql['approval_status']]; ?>"><?= ucfirst($sql['approval_status']); ?></span>
                                                </td>
                                            </tr>
                                            <?php $x++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Maintanance" role="tabpanel">
                            <div class="pd-20">
                                Maintanance
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Others" role="tabpanel">
                            <div class="pd-20">
                                Others
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" id="production_tot" value="<?= array_sum($bamt); ?>">
            </div>
            
            
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>
    
    <!-- js -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="vendors/scripts/dashboard.js"></script>
</body>
<script>
    $(document).ready(function () {
        
        var production_tot = $("#production_tot").val();
        
        $(".prod_tot").text(production_tot);

        // $('a.margin-5').data('data-content', 20);
    }
    );

</script>

</html>