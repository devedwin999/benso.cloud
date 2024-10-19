<?php
include("includes/connection.php");
include("includes/function.php");


if (isset($_REQUEST['SaveBtn'])) {


    for ($i = 0; $i < count($_REQUEST['bundle_id']); $i++) {
        $dta = array(
            'process_id' => $_REQUEST['processing_list'],
            'bundle_id' => $_REQUEST['bundle_id'][$i],
            'good_pcs' => $_REQUEST['good_pcs'][$i],
            'rework_pcs' => $_REQUEST['rework_pcs'][$i],
            'rejection_pcs' => $_REQUEST['rejection_pcs'][$i],
            'rework_stage' => $_REQUEST['rework_stage'][$i],
            'created_date' => date('Y-m-d H:i:s')
        );

        if (empty($_REQUEST['saved_id'][$i])) {
            $qry = Insert('checking_list', $dta);
            $_SESSION['msg'] = "added";
        } else {
            Update('checking_list', $dta, " WHERE id = '" . $_REQUEST['saved_id'][$i] . "'");
            $_SESSION['msg'] = "updated";
        }
    }

    $dta2 = array(
        'is_checked' => 1,
    );

    Update('processing_list', $dta2, " WHERE id = '" . $_REQUEST['processing_list'] . "'");



    header("Location:checking-list.php");

    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit ';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $id));
} else {
    $comp = 'Add ';
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Inwarded List
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
    .ui-menu-item-wrapper {
        background-color: #eae8e8 !important;
        padding: 10px;
        width: 20% !important;
        border-bottom: 1px solid #c6c5c5;
    }
</style>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="checking-list.php" style="float: right;"><i
                                class="fa fa-list" aria-hidden="true"></i> Checking List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                Checking
                            </h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">
                        <div class="row">

                            <div class="col-md-4"></div>
                            <div class="col-md-4" style="padding: 40px;">
                                <label for="">Inwarded List</label>
                                <div class="input-group mb-3">

                                    <select name="processing_list" id="processing_list"
                                        class="custom-select2 form-control">
                                        <?php
                                        if (isset($_GET['id'])) {
                                            print select_dropdown('processing_list', array('id', 'processing_code'), 'processing_code ASC', $sql['id'], 'WHERE id=' . $sql['id'], '');
                                        } else {
                                            print select_dropdown('processing_list', array('id', 'processing_code'), 'processing_code ASC', '', 'WHERE is_inwarded = 1 AND is_checked IS NULL', '');
                                        }
                                        ?>
                                    </select>
                                    <div class="input-group-append">
                                        <!-- <button class="btn btn-outline-secondary" type="button">Scan</button> -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="overflow-y: scroll;width:100%">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Boundle QR</th>
                                            <th>Boundle No</th>
                                            <th>Boundle Qty</th>
                                            <th>Good Pcs</th>
                                            <th>Rework Pcs</th>
                                            <th>Rejection Pcs</th>
                                            <th>Rework Stage</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                            </div>


                        </div>
                        <hr>
                        <div class=" row">
                            <div class="col-md-12">
                                <div class="form-group" style="text-align: center;">
                                    <a class="btn btn-secondary" href="view-processing.php">Cancel</a>
                                    <input type="submit" class="btn btn-success" name="SaveBtn" value="Submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>

    <script>
        function showPices(id) {
            if ($("#show_pieces" + id).val() == "") {
                $("#trcBoxx" + id).show();
                $("#show_pieces" + id).val('1');
                $("#iconn" + id).removeClass('ion-chevron-right').addClass('ion-chevron-down');
            } else {
                $("#trcBoxx" + id).hide();
                $("#show_pieces" + id).val('');
                $("#iconn" + id).removeClass('ion-chevron-down').addClass('ion-chevron-right');
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            var id = $("#processing_list").val();
            processing_list(id);
        })

        $("#processing_list").change(function () {
            var id = $(this).val();
            processing_list(id);
        })

        function processing_list(id) {

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getChecking=1&id=' + id,

                success: function (msg) {
                    $("#tableBody").html(msg);
                }
            })

        }
    </script>

    <script>
        function ncbox(id) {
            var a = $("#ncbox" + id).is(":checked");
            if (a == true) {
                $(".ncbox" + id).prop('checked', true);
            } else {
                $(".ncbox" + id).prop('checked', false);
            }
        }
    </script>

    <script type="text/javascript">
        $(function () {
            $('#add-process').validate({
                errorClass: "help-block",
                rules: {
                    p_type: {
                        required: true
                    },
                    process_id: {
                        required: true
                    },
                    supplier_id: {
                        required: true
                    }
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