<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

$data = array();

if (isset($_POST['savebudget'])) {

    for ($k = 0; $k < count($_REQUEST['process_id']); $k++) {
        $data = array(
            'so_id' => $_GET['id'],
            'process' => $_REQUEST['process_id'][$k],
            'rate' => $_REQUEST['prate'][$k],
            'revised_rate' => $_REQUEST['revised_rate'][$k],
            'rework_rate' => $_REQUEST['rework_rate'][$k],
            'created_date' => date('Y-m-d H:i:s'),
        );

        if (!empty($_REQUEST['budget_process'][$k])) {
            $qry = Update('budget_process', $data, " WHERE id = '" . $_REQUEST['budget_process'][$k] . "'");
        } else {
            $qry = Insert('budget_process', $data);
        }
    }

    for ($l = 0; $l < count($_REQUEST['sub_id']); $l++) {
        $subdata = array(
            'so_id' => $_GET['id'],
            'process' => $_REQUEST['pro_id'][$l],
            'subprocess' => $_REQUEST['sub_id'][$l],
            'price' => $_REQUEST['sub_price'][$l],
            'created_date' => date('Y-m-d H:i:s'),
        );

        if (empty($_REQUEST['budget_subprocess_id'][$l])) {
            $qry = Insert('budget_subprocess', $subdata);
        } else {
            $qry = Update('budget_subprocess', $subdata, " WHERE id = '" . $_REQUEST['budget_subprocess_id'][$l] . "'");
        }
    }
    
    for ($k = 0; $k < count($_REQUEST['newProcess']); $k++) {
        
        $ndt = array(
            'category' => $_REQUEST['category_id'],
            'process_name' => $_REQUEST['newProcess'][$k],
        );
        
        $qry = Insert('process', $ndt);
        
        $inid = mysqli_insert_id($mysqli);
            
        $data = array(
            'so_id' => $_GET['id'],
            'process' => $inid,
            'category' => $_REQUEST['category_id'],
            'rate' => $_REQUEST['rate1'][$k],
            'revised_rate' => $_REQUEST['rate2'][$k],
            'rework_rate' => $_REQUEST['rate3'][$k],
            'created_date' => date('Y-m-d H:i:s'),
        );

        $qry = Insert('budget_process', $data);
    }

    $_SESSION['msg'] = "saved";

    header("Location:cmt_budget.php");

}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sales_order WHERE id=" . $id));
} else {
    $id = '';
}
?>
<!DOCTYPE html>
<html>

<head>

    <style>
        .table thead th {
            width: 1%;
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

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="pd-20 card-box mb-30">
                    
                    <?php if(BUDGET_EDIT!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">Budget for sales order of <span style="color:red">
                                <?= $sql['order_code']; ?>
                            </span>
                            <a class="btn btn-outline-primary" href="cmt_budget.php" style="float: right;"><i
                                    class="fa fa-list" aria-hidden="true"></i> Budget List</a>
                        </h4>
                    </div>

                    <div class="row">
                        <div class="col-md-4" style="margin-left:1% !important;color:red;padding: 25px;">
                            <label>Category :</label>
                            <?php
                            $ioo = mysqli_fetch_array(mysqli_query($mysqli, "SELECT b.id as catid, b.category_name FROM budget_process a LEFT JOIN category b ON a.category=b.id WHERE a.so_id='" . $_GET['id'] . "'"));
                            ?>
                            <select name="category" id="category" class="custom-select2 form-control" disabled>
                                <?= select_dropdown('category', array('id', 'category_name'), 'category_name ASC', $ioo['catid'], '', '') ?>
                            </select>
                        </div>
                    </div>

                    <form id="budForm" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 style="padding: 20px;">Process</h5>
                                <table class="table table-striped table-responsive">
                                    <thead style="background-color: #d7d7d7;">
                                        <tr>
                                            <th style="width: ;">Process</th>
                                            <th>Rate</th>
                                            <th>Revised Rate</th>
                                            <th>Rework Rate</th>
                                            <th><i class="icon-copy fa fa-plus-circle" aria-hidden="true" onclick="addmoreQrydetail()"></i> <input type="hidden" id="addmoreQrydetail" value="101"> </th>
                                        </tr>
                                    </thead>
                                    <tbody id="processBody">
                                        <?php
                                        $usql = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM budget_process a LEFT JOIN process b ON a.process=b.id WHERE a.so_id='" . $_GET['id'] . "'");
                                        while ($row = mysqli_fetch_array($usql)) { ?>

                                            <tr>
                                                <td>
                                                    <input type="hidden" name="budget_process[]" id=""
                                                        value="<?= $row['id']; ?>">
                                                    <input type="hidden" name="process_id[]" id=""
                                                        value="<?= $row['process']; ?>">
                                                    <?= $row['process_name']; ?>

                                                    <i class="icon-copy fa fa-eye" data-toggle="modal"
                                                        data-target="#showprocessModal<?= $row['id']; ?>" data-type="edit"
                                                        data-id="<?= $row['process']; ?>" aria-hidden="true"
                                                        style="float: right;font-size: 20px;" title="Sub Process List"></i>

                                                    <div class="modal fade" id="showprocessModal<?= $row['id']; ?>"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Sub
                                                                        Process List</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Process</th>
                                                                                    <th>Operation</th>
                                                                                    <th>Price</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $myqs = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM sub_process a LEFT JOIN process b ON a.process_id=b.id WHERE a.process_id=" . $row['process']);
                                                                                if (mysqli_num_rows($myqs) > 0) {
                                                                                    while ($fth = mysqli_fetch_array($myqs)) {
                                                                                        $sl = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM budget_subprocess WHERE so_id='" . $_GET['id'] . "' AND subprocess='" . $fth['id'] . "' "));

                                                                                        $pic = $sl['price'] ? $sl['price'] : $fth['price'];

                                                                                        print '<tr> <td>' . $fth['process_name'] . '</td> <td>' . $fth['sub_process_name'] . '</td> <td>
                                                                                            <input type="number" name="sub_price[]" class="form-control" value="' . $pic . '">
                                                                                            
                                                                                            <input type="hidden" name="sub_id[]" class="form-control" value="' . $fth['id'] . '">
                                                                                            <input type="hidden" name="pro_id[]" class="form-control" value="' . $fth['process_id'] . '">
                                                                                            
                                                                                            <input type="hidden" name="budget_subprocess_id[]" class="form-control" value="' . $sl['id'] . '">
                                                                                            </td> </tr>';
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="text" name="prate[]" id="" class="form-control"
                                                        value="<?= $row['rate']; ?>"></td>
                                                <td><input type="text" name="revised_rate[]" id="" class="form-control"
                                                        value="<?= $row['revised_rate']; ?>"></td>
                                                <td><input type="text" name="rework_rate[]" id="" class="form-control"
                                                        value="<?= $row['rework_rate']; ?>"></td>
                                                <td>-</td>
                                            </tr>
                                        <?php }
                                        ?>

                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div style="text-align:center;">
                            <input type="submit" name="savebudget" class="btn btn-primary" value="Update">
                        </div>
                    </form>
                </div>
            </div>


            <?php include('includes/footer.php');
            include('modals.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
    
        function addmoreQrydetail()
        {
            var row = $("#addmoreQrydetail").val();
            var a = '<tr id="tr'+row+'"> <td> <input type="text" name="newProcess[]" class="form-control"> </td> <td><input type="text" name="rate1[]" class="form-control"></td> <td><input type="text" name="rate2[]" class="form-control"></td> <td><input type="text" name="rate3[]" class="form-control"></td><td><i class="icon-copy fa fa-trash" aria-hidden="true" onclick="removeRow('+ row +')"></i> </td> </tr>';
         
         
         $("#processBody").append(a);
         $("#addmoreQrydetail").val((parseInt(row)+1));
        }
    
    
        function removeRow(id)
        {
            $("#tr"+id).remove();
        }
    </script>


    <script type="text/javascript">
        $(function () {
            $('#soForm').validate({
                errorClass: "help-block",
                rules: {
                    department: {
                        required: true
                    },
                },
                errorPlacement: function (label, element) {
                    label.addClass('mt-2 text-danger');
                    label.insertAfter(element);
                },
                highlight: function (element, errorClass) {
                    $(element).parent().addClass('has-danger')
                    $(element).addClass('form-control-danger')
                }
            });
        });
    </script>


</body>

</html>