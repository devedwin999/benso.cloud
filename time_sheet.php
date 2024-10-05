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

<style>
    /* .swal2-confirm {
        display: none !important;
    } */
</style>

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
                    <?php } else { 
                        
                        $sql = mysqli_query($mysqli, "SELECT * FROM sod_time_sheet WHERE sales_order_id = ". $ID);
                        $num = mysqli_num_rows($sql);

                        if($num>0) {
                    ?>
                        <div class="pd-20">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl.No</th>
                                        <th>Activity</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Task Days</th>
                                        <th>Task Daily Time</th>
                                        <th>Task Closing Day Time</th>
                                        <th>Responsible A</th>
                                        <th>Responsible B</th>
                                        <!-- <th>Responsible C</th>
                                        <th>Responsible D</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $q=1;
                                        while($fetch = mysqli_fetch_array($sql)) { 
                                            $start = new DateTime($fetch['start_date']);
                                            $end = new DateTime($fetch['end_date']);
                                            $diff = $start->diff($end);
                                            
                                            $daysDiff = $diff->days;
                                    ?>
                                        <tr>
                                            <td><?= $q++; ?></td>
                                            <td><?= $fetch['activity']; ?></td>
                                            <td><?= date('d-M, Y', strtotime($fetch['start_date'])); ?></td>
                                            <td><?= date('d-M, Y', strtotime($fetch['end_date'])); ?></td>
                                            <td><?= $daysDiff; ?> Days</td>
                                            <td><?= time_calculator_new(time_calculator($fetch['daily_time']), 1); ?></td>
                                            <td><?= time_calculator_new(time_calculator($fetch['endday_time']), 1); ?></td>
                                            <td><?= implode(', ', emp_name($fetch['resp_a'])); ?></td>
                                            <td><?= implode(', ', emp_name($fetch['resp_b'])); ?></td>
                                            <!-- <td>-</td>
                                            <td>-</td> -->
                                        </tr>
                                    <?php } 
                                    
                                        $qry = mysqli_query($mysqli, "SELECT * FROM sod_time_sheet WHERE sales_order_id = 334");
                                        while($result = mysqli_fetch_array($qry)) {

                                            $start_date = $result['start_date'];
                                            $end_date = $result['end_date'];
                                            
                                            $start_timestamp = strtotime($start_date);
                                            $end_timestamp = strtotime($end_date);
                                            
                                            $dates = [];
                                            for ($current = $start_timestamp; $current <= $end_timestamp; $current += 86400) {
                                                $dates[] = date('d-m-Y', $current);
                                            }
                                            
                                            foreach ($dates as $date) {
                                                
                                                
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>

                        <form method="POST" id="timesheet-form">
                            <input type="hidden" name="sales_order_id" id="sales_order_id" value="<?= $_GET['id']; ?>">
                            <div class="pd-20">
                                <table class="table table-bordered hover">
                                    <thead>
                                        <tr>
                                            <th>Activity For</th>
                                            <th>Activity</th>
                                            <th>Task calculate from</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Task Days</th>
                                            <th>Daily Work Time</th>
                                            <th>Closing Day Time</th>
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
                                                    $task_type = '<span class="--text-light-orange">General Tasks</span>';
                                                } else if($res['table_name'] == 'mas_approval') {
                                                    $activity = approval_name($res['activity']);
                                                    $task_type = '<span class="--text-blue-50">Buyer Approvals</span>';
                                                }

                                                $start = new DateTime($task_start_date);
                                                $end = new DateTime($task_end_date);
                                                $diff = $start->diff($end);
                                                
                                                $daysDiff = $diff->days;

                                                $xx = $res['id'];
                                        ?>
                                            <tr>
                                                <td><?= $task_type; ?></td>
                                                <td><?= $activity; ?></td>
                                                <td><?= ($res['calculation_type']=='asc') ? 'Order Date' : 'Delivery Date'; ?></td>
                                                <td><input type="date" class="form-control" name="start_date_<?= $xx; ?>" id="start_date_<?= $xx; ?>" value="<?= $task_start_date; ?>"></td>
                                                <td><input type="date" class="form-control" name="end_date_<?= $xx; ?>" id="end_date_<?= $xx; ?>" value="<?= $task_end_date; ?>"></td>
                                                <td><?= $daysDiff+1; ?> Days</td>
                                                <td><?= time_calculator_new(time_calculator($res['daily_time']), 1); ?></td>
                                                <td><?= time_calculator_new(time_calculator($res['endday_time']), 1); ?></td>
                                                <td>
                                                    <select class="form-control custom-select2" name="resp_a_<?= $xx; ?>[]" id="resp_a_<?= $xx; ?>" style="width:100%" multiple required>
                                                        <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $order['merchand_name'], ' WHERE is_active="active"', '`'); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control custom-select2" name="resp_b_<?= $xx; ?>[]" id="resp_b_<?= $xx; ?>" style="width:100%" multiple required>
                                                        <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $order['merchand_name'], ' WHERE is_active="active"', '`'); ?>
                                                    </select>

                                                    <input type="hidden" name="activity_id[]" id="activity_id" value="<?= $xx; ?>">
                                                    <input type="hidden" name="activity[]" id="activity_<?= $xx; ?>" value="<?= $activity; ?>">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="pd-20 text-sm-center">
                                <p class="text-danger">* Once generated, the timesheet cannot be edited. Please check carefully before generating the timesheet.</p>
                                <button type="button" class="btn btn-outline-primary saveBtn"><i class="fa-save fa"></i> Generate Time Sheet</button>
                            </div>
                        </form>
                    <?php } } ?>
                </div>
            </div>
            <?php include ('modals.php'); include ('includes/footer.php'); ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $(document).ready(function() {
            $(".saveBtn").click(function() {

                swal({
                    title: 'Are you sure?',
                    text: "Confirm Generate Time Sheet?",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Generate!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success margin-5',
                    cancelButtonClass: 'btn btn-danger margin-5',
                    buttonsStyling: false
                }).then(function (dd) {
                    if (dd['value'] == true) {

                        $(this).prop('disabled', true);
                        
                        swal_processing();

                        var data = $("#timesheet-form").serialize();
                        
                        setTimeout(function() {
                            $.post('ajax_action.php?createTimeSheet', data, function(msg) {
                                var j = $.parseJSON(msg);
                                
                                if(j.result==0) {
                                    message_reload('success', 'Template Generated!', 1500);
                                } else {
                                    message_error();
                                }
                            });
                        }, 1000);
                    } else {
                        swal( 'Cancelled', '', 'error')
                    }
                })
            });
        });
    </script>

</html>