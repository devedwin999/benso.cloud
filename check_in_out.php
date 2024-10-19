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
    <title>BENSO - Check In / Out List</title>

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
        <?php
        if ($_SESSION['msg'] == 'updated') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Updated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'added') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Saved.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php }
        $_SESSION['msg'] = '';
        ?>
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Export Datatable start -->
                <div class="card-box mb-30">
                    <?php //if(FAB_PO!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">				       
                        <h4 class="text-blue h4">Check In / Out Reports</h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Employee Name</th>
                                    <th>Employee ID</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>                                                                                               
                                                                                                 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT a.*, b.employee_name,employee_code,mobile FROM attendance a LEFT JOIN employee_detail b ON a.employee_id=b.id WHERE date='". date('Y-m-d') ."' group by a.employee_id ORDER BY a.id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    $inTime = mysqli_fetch_array(mysqli_query($mysqli,"SELECT in_time FROM attendance WHERE employee_id = '". $sql['employee_id'] ."' AND date='". $sql['date'] ."' ORDER BY id ASC"));
                                    $outTime = mysqli_fetch_array(mysqli_query($mysqli,"SELECT out_time FROM attendance WHERE employee_id = '". $sql['employee_id'] ."' AND date='". $sql['date'] ."' ORDER BY id DESC"));
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><a class="viewAttendance" data-emp="<?= $sql['employee_id']; ?>" data-date="<?= $sql['date'] ?>" style="color: blue;text-decoration: underline;"><?= $sql['employee_name']; ?></a></td>
                                        <td><?= $sql['employee_code']; ?></td>
                                        <td><?= $sql['date']; ?></td>                                       
                                        <td><?= ($sql['in_time'] != NULL) ? date('h:i:s A', ($inTime['in_time'])) : '-';  ?></td>                                                                        
                                        <td><?= ($sql['out_time'] != NULL) ? date('h:i:s A', ($outTime['out_time'])) : '-';  ?></td>                                      
                                       
                                        </td>                                                                       
                                    </tr>
                                    <?php $x++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Export Datatable End -->
            </div>
            <?php include('includes/footer.php'); ?>
            <?php include('modals.php'); ?>
        </div>
    </div>
    
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $(".viewAttendance").click(function() {
            var employee = $(this).data('emp');
            var date = $(this).data('date');

            var data = "employee=" + employee + "&date=" + date;

            $.ajax({
                type : 'POST',
                url : 'ajax_search2.php?showAttendance=1',
                data : data,
                success:function(msg) {
                    var json = $.parseJSON(msg);

                    $("#att_list_body").html(json.tbody);

                    $("#attendance-detail-list-modal").modal('show');
                }
            })
        });
    </script>

</body>

</html>