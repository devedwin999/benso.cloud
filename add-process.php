<?php
include("includes/connection.php");
include("includes/function.php");

$data = array();

if (isset($_POST['savebudget'])) {

    for ($y = 0; $y < count($_REQUEST['processbox_id']); $y++) {
        if ($_REQUEST['processbox_val'][$y] == "checked") {
            $pdata = array(
                'is_approved' => $_REQUEST['truefalse'],
            );

            $qry = Update('budget_process', $pdata, " WHERE id = '" . $_REQUEST['processbox_id'][$y] . "'");
        }
    }

    for ($y1 = 0; $y1 < count($_REQUEST['subprocessbox_id']); $y1++) {
        if ($_REQUEST['subprocessbox_val'][$y1] == "checked") {
            $pdata = array(
                'is_approved' => $_REQUEST['truefalse'],
            );
            $qry = Update('budget_subprocess', $pdata, " WHERE id = '" . $_REQUEST['subprocessbox_id'][$y1] . "'");
        }
    }

    $_SESSION['msg'] = "saved";

    header("Location:cmt_budget.php");

    exit;
} else if (isset($_POST['addcustomer']) && !isset($_GET['id'])) {

    $qry = Insert('customer', $data);

    $_SESSION['msg'] = "added";

    header("Location:customer.php");

    exit;
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
        .table td,
        .table th {
            border-top: 0px solid #dee2e6 !important;
        }

        .prevent-select {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
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
                    <div class="pd-20">
                        <h4 class="text-blue h4">Add Process for sales order of <span style="color:red">
                                <?= $sql['order_code']; ?>
                            </span>
                            <a class="btn btn-primary" href="cmt_budget.php" style="float: right;"><i class="fa fa-list"
                                    aria-hidden="true"></i> Budget List</a>
                        </h4>
                    </div>

                    <form id="budForm" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="padding: 20px;">Process</h5>
                                <table class="table">
                                    <thead style="background-color: #d7d7d7;">
                                        <tr>
                                            <td>Process</td>
                                            <td>Rate</td>
                                            <td>Revised Rate</td>
                                            <td>Rework Rate</td>
                                            <td class="prevent-select">Budget Status</td>
                                            <td>Process</td>
                                        </tr>
                                    </thead>
                                    <tbody id="processBody">
                                        <?php


                                        $opid = mysqli_query($mysqli, "SELECT a.*, b.process_name FROM budget_process a LEFT JOIN process b ON a.process=b.id WHERE a.so_id='" . $_GET['id'] . "'");
                                        if (mysqli_num_rows($opid) > 0) {
                                            while ($row = mysqli_fetch_array($opid)) {

                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="budget_process[]" id=""
                                                            value="<?= $row['id']; ?>">
                                                        <input type="hidden" name="process_id[]" id=""
                                                            value="' . $row['id'] . '">
                                                        <?= $row['process_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['rate']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['revised_rate']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['rework_rate']; ?>
                                                    </td>
                                                    <td>

                                                        <?php
                                                        if ($row['is_approved'] == 'true') {
                                                            $ppo = '<span class="text-success">Approved</span>';
                                                        } else if ($row['is_approved'] == 'false') {
                                                            $ppo = '<span class="text-danger">Rejected</span>';
                                                        } else {
                                                            $ppo = '<span class="text-info">Waiting</span>';
                                                            ?>
                                                                <input type="checkbox" value="<?= $row['id']; ?>"
                                                                    id="processbox<?= $row['id']; ?>" name="processbox[]"
                                                                    class="processbox" onclick="processbox(<?= $row['id']; ?>)">
                                                                <input type="hidden" class="processbox_val" name="processbox_val[]"
                                                                    id="processbox_val<?= $row['id']; ?>"
                                                                    value="<?= $row['is_approved'] ? $row['is_approved'] : ''; ?>">
                                                                <input type="hidden" class="" name="processbox_id[]"
                                                                    value="<?= $row['id']; ?>">

                                                        <?php }
                                                        print $ppo; ?>
                                                    </td>
                                                    <td>
                                                        <a
                                                            onclick="addProcess(<?= $row['id']; ?>, 'budget_process', <?= $row['so_id']; ?>, <?= $row['process']; ?>)">
                                                            <i class="icon-copy fa fa-user-plus" aria-hidden="true"></i>
                                                            Add
                                                        </a>
                                                    </td>

                                                </tr>
                                            <?php }
                                        } else {
                                            print '<tr><td colspan="5" align="center">Budget Not Created</td></tr>';
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 style="padding: 20px;">Sub Process</h5>
                                <table class="table">
                                    <thead style="background-color: #d7d7d7;">
                                        <tr class="prevent-select">
                                            <td>Process</td>
                                            <td>Operation</td>
                                            <td>Price</td>
                                            <td>Budget Status</td>
                                            <td>Process</td>
                                        </tr>
                                    </thead>
                                    <tbody id="subprocessBody">
                                        <?php
                                        $olp = mysqli_query($mysqli, "SELECT a.*, b.sub_process_name, c.process_name FROM budget_subprocess a LEFT JOIN sub_process b ON a.subprocess = b.id LEFT JOIN process c ON a.process = c.id WHERE a.so_id='" . $_GET['id'] . "'");
                                        if (mysqli_num_rows($olp) > 0) {
                                            while ($subb_p = mysqli_fetch_array($olp)) {
                                                ?>

                                                <tr>
                                                    <td>
                                                        <?= $subb_p['process_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $subb_p['sub_process_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $subb_p['price']; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($subb_p['is_approved'] == 'true') {
                                                            $opp = '<span class="text-success">Approved</span>';
                                                        } else if ($subb_p['is_approved'] == 'false') {
                                                            $opp = '<span class="text-danger">Rejected</span>';
                                                        } else {
                                                            $opp = '<span class="text-info">Waiting</span>';

                                                            ?>
                                                                <input type="checkbox" value="<?= $subb_p['id']; ?>"
                                                                    name="subprocessbox[]" class="subprocessbox"
                                                                    id="subprocessbox<?= $subb_p['id']; ?>"
                                                                    onclick="subprocessbox(<?= $subb_p['id']; ?>)">

                                                                <input type="hidden" class="subprocessbox_val"
                                                                    name="subprocessbox_val[]"
                                                                    id="subprocessbox_val<?= $subb_p['id']; ?>"
                                                                    value="<?= $subb_p['is_approved'] ? $subb_p['is_approved'] : ''; ?>">

                                                                <input type="hidden" class="" name="subprocessbox_id[]"
                                                                    value="<?= $subb_p['id']; ?>">

                                                        <?php }
                                                        print $opp; ?>
                                                    </td>
                                                    <td>
                                                        <a
                                                            onclick="addProcess(<?= $subb_p['id']; ?>, 'budget_subprocess', <?= $subb_p['so_id']; ?>, <?= $subb_p['subprocess']; ?>)">
                                                            <i class="icon-copy fa fa-user-plus" aria-hidden="true"></i>
                                                            Add
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else {
                                            print '<tr><td colspan="4" align="center">Budget Not Created</td></tr>';
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="addProcess-modal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Assign Process</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" id="saveprocessForm">
                            <div class="modal-body">
                                <div class="row" id="proess_body"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="saveprocess" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <?php include('includes/footer.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $("#saveprocess").click(function () {

            var form = $("#saveprocessForm").serialize();
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?saveprocess=1',
                data: form,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    if (json.result == 'success') {
                        swal({
                            type: 'success',
                            title: 'Process Saved',
                            showConfirmButton: true,
                            timer: 1500
                        }).then(
                            function () {
                                location.reload();
                            })
                    } else {
                        swal(
                            'Something went wrong',
                            '',
                            'error'
                        )
                    }
                }
            })
        })
    </script>


    <script>
        function addProcess(id, table, so_id, process) {
            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?addProcess=1&id=' + id + '&table=' + table + '&so_id=' + so_id + '&process=' + process,
                success: function (msg) {
                    $("#proess_body").html(msg);
                }
            })

            $("#addProcess-modal").modal('show');
        }
    </script>


    <script>
        function subprocessbox(id) {
            var a = $("#subprocessbox" + id).is(':checked');

            if (a == true) {
                $("#subprocessbox_val" + id).val('checked');
            } else {
                $("#subprocessbox_val" + id).val('');
            }
        }

        function processbox(id) {
            var a = $("#processbox" + id).is(':checked');

            if (a == true) {
                $("#processbox_val" + id).val('checked');
            } else {
                $("#processbox_val" + id).val('');
            }
        }
    </script>

    <script>
        $(".check-process").click(function () {
            if (this.checked == true) {
                $(".processbox_val").val('checked');
                $(".processbox").prop("checked", true);
            } else {
                $(".processbox_val").val('');
                $(".processbox").prop("checked", false);
            }
        });

        $(".check-subprocess").click(function () {
            if (this.checked == true) {
                $(".subprocessbox_val").val('checked');
                $(".subprocessbox").prop("checked", true);
            } else {
                $(".subprocessbox_val").val('');
                $(".subprocessbox").prop("checked", false);
            }
        });
    </script>

    <script>
        function delete_data(id, table) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success margin-5',
                cancelButtonClass: 'btn btn-danger margin-5',
                buttonsStyling: false
            }).then(function (dd) {
                if (dd['value'] == true) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_action.php?delete_data=' + id + '&table=' + table,
                        success: function (msg) {
                            if (msg == 0) {
                                swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    showConfirmButton: true,
                                    timer: 1500
                                }).then(
                                    function () {
                                        location.reload();
                                    })
                            } else {
                                swal(
                                    'Something went wrong',
                                    '',
                                    'error'
                                )
                            }
                        }
                    })
                } else {
                    swal(
                        'Cancelled',
                        '',
                        'error'
                    )
                }
            })
        };
    </script>

</body>

</html>