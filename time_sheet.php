<?php
include ("includes/connection.php");
include ("includes/function.php");

include ("includes/perm.php");


$ID = $_GET['id'];
$order = mysqli_fetch_array(mysqli_query($mysqli, "SELECT a.*, b.merchand_name FROM sales_order a LEFT JOIN merchand_detail b ON a.merchandiser=b.id WHERE a.id = ". $ID));

$manual = array(
    'so_approval' => 'Sales Order',
    'fab_program' => 'Fabric Program',
    'access_program' => 'Accessories Program',
    'budget' => 'Buddget',
    'budget_approval' => 'Buddget Approval',
);
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Time Sheet</title>

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
    include ('includes/header.php');
    include ('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="card-box mb-30">

                    <?php if (TIME_SHEET_CREATION != 1) { action_denied(); exit; } ?>

                    <div class="pd-20">                        
                        <div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
                            <a href="sales_order.php?id=<?= $_GET['id']; ?>" class="btn btn-outline-info timesheerBtn">BO: <?= sales_order_code($_GET['id']); ?></a>
                            <a class="btn btn-outline-primary" href="sales_order_list.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Order List</a>
                        </div>

                        <h4 class="text-blue h4">Time Sheet for the order <span class="u" style="color:red;"><?= sales_order_code($_GET['id']); ?></span></h4>
                    </div>

                    <?php if($order['template_id']==0) { ?>
                        <div class="pd-20" style="text-align: center;">
                            <span style="color:red;font-size: 50px;"><i class="fa fa-exclamation-circle"></i></span></br>                            
                            <h4 style="color: #0058ff;">Template Not Found!</h4> <br>
                            <p>Select a valid <b>Time Sheet Template</b> in the sales order.</p>
                            <a href="sales_order.php?id=<?= $_GET['id']; ?>" style="text-decoration: underline;color: #a686ff;">BO: <?= sales_order_code($_GET['id']); ?></a>
                        </div>
                    <?php } else { ?>

                        <div class="pd-20">
                            <table class="table table-bordered hover">
                                <thead>
                                    <tr>
                                        <th>Activity For</th>
                                        <th>Activity</th>
                                        <th>Task calculate from</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Task Duration</th>
                                        <th>Responsible (A)</th>
                                        <th>Responsible (B)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $bklm = mysqli_query($mysqli, "SELECT * FROM time_management_template_det WHERE temp_id = '" . $order['template_id'] . "'");

                                        while ($res = mysqli_fetch_assoc($bklm)) {
                                    
                                            $ord_date = $order['order_date'];
                                            $del_date = $order['delivery_date'];
                                            
                                            if ($res['calculation_type'] == 'asc') {
                                                $task_start_date = date('Y-m-d', strtotime($ord_date . ' +' . $res['start_day'] . ' days'));
                                                $task_end_date = date('Y-m-d', strtotime($ord_date . ' +' . $res['end_day'] . ' days'));
                                            } else if ($res['calculation_type'] == 'desc') {
                                                $task_start_date = date('Y-m-d', strtotime($del_date . ' -' . $res['start_day'] . ' days'));
                                                $task_end_date = date('Y-m-d', strtotime($del_date . ' -' . $res['end_day'] . ' days'));
                                            }

                                            if($res['table_name'] == 'manual') {
                                                $activity = $manual[$res['activity']];
                                                $task_type = 'General Tasks';
                                            } else if($res['table_name'] == 'mas_approval') {
                                                $activity = approval_name($res['activity']);
                                                $task_type = 'Buyer Approvals';
                                            }

                                            $start = new DateTime($task_start_date);
                                            $end = new DateTime($task_end_date);
                                            $diff = $start->diff($end);
                                            
                                            $daysDiff = $diff->days;
                                    ?>
                                        <tr>
                                            <td><?= $task_type; ?></td>
                                            <td><?= $activity; ?></td>
                                            <td><?= ($res['calculation_type']=='asc') ? 'Order Date' : 'Delivery Date'; ?></td>
                                            <td><input type="date" class="form-control" name="" id="" value="<?= $task_start_date; ?>"></td>
                                            <td><input type="date" class="form-control" name="" id="" value="<?= $task_end_date; ?>"></td>
                                            <td><?= $daysDiff+1; ?> Days</td>
                                            <td>
                                                <select class="form-control custom-select2" name="" id="" style="width:100%" multiple required>
                                                    <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $order['merchand_name'], ' WHERE is_active="active"', ''); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control custom-select2" name="" id="" style="width:100%" multiple required>
                                                    <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $order['merchand_name'], ' WHERE is_active="active"', ''); ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
                include ('modals.php');
                include ('includes/footer.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include ('includes/end_scripts.php'); ?>

</html>