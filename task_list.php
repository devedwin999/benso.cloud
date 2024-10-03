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
                    <?php if(TEAM_TASK_LIST!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <div class="row">

                            <div class="col-md-12">
                                <a class="btn btn-light float-right" onclick="divToPrint('divToPrint')"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                            </div>
                            <div class="col-md-2 d-none">
                                <label for="">Filter Type</label>
                                <select name="" id="FilterOpt" class="custom-select2 multiple-select" style="width: 100%" onchange="FilterOpt()">
                                    <option value="">Select</option>
                                    <?php
                                    $var = array(
                                        'employee' => 'employee',
                                    );
                                    foreach ($var as $ky => $val) {
                                        $typ = ($_GET['type'] == $ky) ? 'selected' : '';
                                        print '<option value="' . $ky . '" ' . $typ . '>' . $val . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3 d-none" id="fromToDiv" >
                                <label for="">Task Date From & To date: <input type="checkbox" <?= ($_GET['del_date']=='true') ? 'checked' : ''; ?> id="del_date"></label>
                                <div class="d-flex">
                                    <input type="date" name="" id="FromDate" class="form-control"
                                        value="<?= $_GET['fdt'] ? $_GET['fdt'] : date('Y-m-01') ?>" style="width:48%">
                                    &nbsp;
                                    <input type="date" name="" id="ToDate" class="form-control"
                                        value="<?= $_GET['tdt'] ? $_GET['tdt'] : date('Y-m-t'); ?>" style="width:48%">
                                </div>
                            </div>

                            <div class="col-md-3" id="employeeDiv" style="display:<?= ($_GET['type'] == 'employee') ? 'block' : 'block'; ?>">
                                <label for="">Employee:</label>
                                <br>
                                <select name="employee" id="employee" class="form-control custom-select2" style="width:100%" multiple>
                                    <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $_GET['employee'], ' WHERE is_active="active"', '')?>                                    
                                </select>
                            </div>
                            

                            <div class="col-md-3">
                                <label for="">&nbsp;</label> <br>
                                <!--<input type="checkbox" name="" id="withImage" class="" <?= ($_GET['ig'] == 'true') ? 'checked' : ''; ?>>-->
                                <input type="button" name="" id="startFilter" onclick="startFilter()"
                                    class="btn btn-outline-secondary" value="Filter">
                                <input value="Clear" type="button"
                                    onclick="window.location.href='task_list.php'"
                                    class="btn btn-outline-secondary">
                            </div>

                        </div>
                    </div>
                </div>

                <script>
                    function startFilter() {
                        var query = window.location.search.substring(1);
                        var vars = query.split("=");
                        var image = vars[1];

                        var type = $("#FilterOpt").val();
                        var employee = $("#employee").val();
                        
                        var ddt = 'fdt=' + $("#FromDate").val() + '&tdt=' + $("#ToDate").val();

                        var del_date = $("#del_date").is(':checked');
                        
                        // if (type == 'employee') {
                            if (employee == "") {
                                message_noload('warning', 'Employee Required!', 1500);
                                $("#employee").focus();
                                return false;
                            }
                            var search = '?type=' + type + '&employee='+ employee +'&' + ddt;
                        // }

                        search += '&del_date=' + del_date;

                        window.location.href = "task_list.php" + search;
                    }

                    function FilterOpt() {
                        var a = ['employee'];

                        a.forEach(showHide);
                    }

                    function showHide(item, index) {
                        var a = $("#FilterOpt").val();
                        if (a == item) {
                            $("#" + item + "Div").show();
                        } else {
                            $("#" + item + "Div").hide();
                        }
                    }
                    
                </script>

                <!-- Export Datatable start -->
                <div class="card-box mb-30" id="divToPrint">
                    <div class="pd-20" style="text-align:center">
                        <h4 class="text-blue h4">Task List</h4>
                    </div>
                    <div class="pb-20" style="overflow-y:auto">
                        <table class="table hover table-striped table-bordered" id="example" border="1" style="border-collapse: collapse" style="width:100%">
                            
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Task Name</th>
                                    <th>Task Message</th>
                                    <th>Assigned A</th>
                                    <th>Assigned B</th>
                                    <th>Task Start</th>
                                    <th>Task End</th>
                                    <th>Task Duration</th>
                                    <th>Allowed Working Time</th>
                                    <th>Actual Working Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                $qry = "SELECT a.* ";
                                $qry .= " FROM team_tasks a ";
    
                                $qry .= " WHERE a.task_complete IS NULL ";
    
                                if (isset($_GET['type'])) {
                                    $typ = $_GET['type'];

                                    $qry .= " AND ";
                                    $df = explode(',', $_GET['employee']);
                                    for($pp=0; $pp<count($df); $pp++) {
                                        if($pp!=0){
                                            $qry .= ' OR ';
                                        }
                                        $qry .= " (FIND_IN_SET(" . $df[$pp] . ", a.assigned_to) > 0 OR FIND_IN_SET(" . $df[$pp] . ", a.assigned_toB) > 0) ";
                                    }
                                }
                                
                                if($_GET['del_date']=='true') {
                                    $qry .= " AND a.delivery_date BETWEEN '" . $_GET['fdt'] . "' AND '" . $_GET['tdt'] . "' ";
                                }
    
                                if ($_SESSION['login_role'] != '1') {
                                    // $qry .= " AND a.id= '" . $_SESSION['loginCompany'] . "'";
                                }
    
                                $qry .= " ORDER BY a.id ASC ";
                                
                                $query = mysqli_query($mysqli, $qry);

                                $x = 1;
                                if(mysqli_num_rows($query)>0) {
                                    while ($sql = mysqli_fetch_array($query)) {

                                        $a_emp = explode(',', $sql['assigned_to']);
                                        $b_emp = explode(',', $sql['assigned_toB']);
                                        $aa = $bb = $aa_tim = array();
                                        for($p=0; $p<count($a_emp); $p++) {
                                            $ero = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(total_time) as total_time FROM team_task_timer WHERE employee_id = '". $a_emp[$p] ."' AND task_id = '". $sql['id'] ."'"));
                                            if($ero['total_time']>0) {
                                                $cl = ($sql['allowed_time']<$ero['total_time']) ? 'red' : '';
                                                $aa_tim[] = '<span style="color:'. $cl .'">'. time_calculator($ero['total_time']). ' <small>('. employee_name($a_emp[$p]) .')</small></span>';
                                            }
                                            $aa[] = employee_name($a_emp[$p]);
                                        }

                                        for($p1=0; $p1<count($b_emp); $p1++) {
                                            $bb[] = employee_name($b_emp[$p1]);
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $x; ?></td>
                                            <td><?= $sql['task_type']; ?></td>
                                            <td><?= $sql['task_msg']; ?></td>
                                            <td><?= implode(',<br>', $aa); ?></td>
                                            <td><?= implode(',<br>', $bb); ?></td>
                                            <td><?= date('d-m-Y H:i A', strtotime($sql['start_date'])) ?></td>
                                            <td><?= date('d-m-Y H:i A', strtotime($sql['end_date'])) ?></td>
                                            <td><?= time_calculator($sql['task_duration']); ?></td>
                                            <td><?= time_calculator($sql['allowed_time']); ?></td>
                                            <td><?= implode('<br>', $aa_tim); ?></td>
                                        <?php $x++; } 
                                } else {
                                    print '<tr><td class="text-center" colspan="11">No result found!</td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); include('modals.php'); ?>
        </div>
    </div>

    <?php include('includes/end_scripts.php'); ?>

</body>

</html>