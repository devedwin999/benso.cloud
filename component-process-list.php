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
    <title>Benso Garmenting - Component Process</title>

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

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="card-box mb-30">
                <?php if(SEW_OUTPUT!=1) { action_denied(); exit; } ?>
                
                <div class="pd-20">
                    <?php if(SEW_OUTPUT_ADD==1) { ?>
                        <a class="d-none btn btn-outline-primary" href="s-output.php" style="float: right;"><i
                                class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                    <?php } ?>
                    
                    <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                        <a class="btn btn-outline-info" href="mod_production.php"><i class="fa fa-home" aria-hidden="true"></i> Production</a>
					</div>
                </div>
                
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="fieldrequired">Process</label>
                            <select name="" id="FilterOpt" class="custom-select2 multiple-select" style="width: 100%" onchange="startFilter()">
                                <option value="all">All Process</option>
                                <?= select_dropdown('process', array('id', 'process_name'), 'process_name ASC', $_GET['ps'], ' WHERE department="2"', '`'); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function startFilter() {
                
                var FilterOpt = $("#FilterOpt").val();
                
                window.location.href = base_url + "component-process-list.php?ps=" + FilterOpt;
            }
            
        </script>
        
        <div class="pd-ltr-20 xs-pd-20-10">
        
            <div class="card-box mb-30">
                <div class="pd-20 text-center">
                    <h4 class="text-blue h4">Component Process Output List</h4>
                </div>
                <div class="pb-20">
                    <table class="table hover multiple-select-row data-table-export nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus datatable-nosort">S.No</th>
                                <th>Date</th>
                                <th>BO</th>
                                <th>Style</th>
                                <th>Combo</th>
                                <th>Part</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Process</th>
                                <th>Output Qty</th>
                                <th>Employee</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 1;
                                
                                $qry = "SELECT * FROM orbidx_component_process";
                                if(isset($_GET['ps']) && ($_GET['ps'] != 'all'))  {
                                    $qry .= " WHERE process = '". $_GET['ps'] ."'";
                                }
                                
                                $res = mysqli_query($mysqli, $qry);
                                while($result = mysqli_fetch_array($res)) {
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= date('M d, Y', strtotime($result['date'])); ?></td>
                                    <td><?= sales_order_code($result['order_id']); ?></td>
                                    <td><?= sales_order_style($result['style_id']); ?></td>
                                    <td><?= color_name($result['combo']); ?></td>
                                    <td><?= part_name($result['part']); ?></td>
                                    <td><?= color_name($result['color']); ?></td>
                                    <td><?= variation_value($result['variation_value']); ?></td>
                                    <td><?= process_name($result['process']); ?></td>
                                    <td><a onclick="show_total_scanned(this)" class="" data-id="<?= $result['id']; ?>" data-from="componenet" style="color:blue; text-decoration: underline;"><?= $result['scanned_count']; ?></a></td>
                                    <td><?= employee_name($result['device_user']); ?></td>
                                    <td><?= company_code($result['logUnit']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
                
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    
    <?php include('includes/end_scripts.php'); ?>

</body>

</html>