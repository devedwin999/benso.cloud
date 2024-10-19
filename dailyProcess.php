<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['SaveBtn'])) {

    for ($i = 0; $i < count($_REQUEST['dailyId']); $i++) {
        $dta = array(
            'outward_id' => $_GET['id'],
            'bundle_id' => $_REQUEST['bundle_id'][$i],
            'employee' => $_REQUEST['employee'][$i],
            'completed_qty' => $_REQUEST['completed_qty'][$i],
            'date' => date('Y-m-d'),
            'created_date' => date('Y-m-d H:i:s')
        );
        // print_r($dta);
        if (empty($_REQUEST['dailyId'][$i])) {
            $qry = Insert('inhouse_process', $dta);
            $_SESSION['msg'] = "added";
        } else {
            Update('inhouse_process', $dta, " WHERE id = '" . $_REQUEST['dailyId'][$i] . "'");
            $_SESSION['msg'] = "updated";
        }
    }
    

    header("Location:in_house.php");

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
    <title>BENSO - Inward List
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
                        <a class="btn btn-outline-primary" href="in_house.php" style="float: right;"><i
                                class="fa fa-list" aria-hidden="true"></i> In-house List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                In-house Daily Processing
                            </h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">

                        <input type="hidden" name="processing_list" id="processing_list" value="<?= $sql['id']; ?>">

                        <div style="overflow-y: scroll;width:100%">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Boundle QR</th>
                                        <th>Boundle No</th>
                                        <th>Boundle Qty</th>
                                        <th>Size</th>
                                        <th>Already Completed</th>
                                        <th>Employee</th>
                                        <th style="width: 250px;">Today Completed</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <?php

                                    $sql1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM processing_list WHERE id=" . $_REQUEST['id']));
                                    if (!empty($sql1['boundle_id'])) {
                                        foreach (explode(',', $sql1['boundle_id']) as $key => $value) {
                                            $qry = "SELECT a.*, b.order_code, b.ref_id, c.style_no, d.color_name, e.type ";
                                            $qry .= "FROM bundle_details a ";
                                            $qry .= "LEFT JOIN cutting_barcode b ON a.cutting_barcode_id=b.id ";
                                            $qry .= "LEFT JOIN sales_order_detalis c ON b.style=c.id ";
                                            $qry .= "LEFT JOIN color d ON b.color=d.id ";
                                            $qry .= "LEFT JOIN variation_value e ON a.variation_value=e.id ";
                                            $qry .= "WHERE a.id='" . $value . "' ";

                                            $sql = mysqli_fetch_array(mysqli_query($mysqli, $qry));

                                            $olp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM inhouse_process WHERE outward_id='" . $_REQUEST['id'] . "' AND bundle_id='" . $value . "' AND date='" . date('Y-m-d') . "'"));

                                            $valid = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(completed_qty) as t_qty FROM inhouse_process WHERE outward_id='" . $_REQUEST['id'] . "' AND bundle_id='" . $value . "' AND date!='".date('Y-m-d')."'"));

                                            $x = $key + 1;
                                            print '<tr>';
                                            print '<td style="color: #007cff;"> 
                                                    <input type="checkbox" class="d-none" checked> 
                                                    
                                                    <a class="chkk" onclick="showPices(' . $value . ')"><i class="icon-copy ion-chevron-right" id="iconn' . $value . '" ></i> ' . $sql['boundle_qr'] . '</a> 
                                                    <input type="hidden" id="show_pieces' . $value . '">
                                                    <input type="hidden" value="' . $olp['id'] . '" name="dailyId[]">
                                                    <input type="hidden" value="' . $value . '" name="bundle_id[]">
                                                    <input type="hidden" value="' . $sql['pcs_per_bundle'] . '" id="max_qty' . $sql['id'] . '">
                                                    <input type="hidden" value="' . $valid['t_qty'] . '" id="already_d' . $sql['id'] . '">
                                                    </td>';
                                            print '<td>' . $sql['bundle_number'] . '</td>';
                                            print '<td>' . $sql['pcs_per_bundle'] . '</td>';
                                            print '<td>' . $sql['type'] . '</td>';
                                            print '<td style="text-align:center">' . $valid['t_qty'] . '</td>';
                                            print '<td><select class="form-control custom-select2" style="width:100%" name="employee[]" id="employee' . $value . '">';
                                            print select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $olp['employee'], ' WHERE type="employee"', '');
                                            print '</td>';
                                            print '<td><input type="text" class="form-control" name="completed_qty[]" onkeyup="completed_qtycheck(' . $sql['id'] . ')" id="completed_qty' . $sql['id'] . '" value="' . $olp['completed_qty'] . '"></td>';
                                            print '<td><i class="icon-copy fa fa-eye" aria-hidden="true" onclick="dailyCompletye(' . $sql['id'] . ')"></i></td>';
                                            print '</tr>';

                                            print '<tr id="trcBoxx' . $value . '" style="display:none;background-color: #f4f4f4;"> <td colspan="8"> <div class="row">';

                                            $nsel = mysqli_query($mysqli, "SELECT * FROM inwarded_bundle WHERE bundle_id=" . $value);
                                            // $nmm = 
                                            $numm = mysqli_num_rows($nsel);
                                            $fthh = mysqli_fetch_array($nsel);

                                            $inarr = explode(',', $fthh['pieces_qr']);

                                            print '<input type="hidden" value="' . $fthh['id'] . '" name="saved_id[]">';
                                            print '<div class="col-md-12" style="color:red;"><input type="checkbox" id="ncbox' . $sql['bundle_number'] . '" onchange="ncbox(' . $sql['bundle_number'] . ')"> <label for="ncbox' . $sql['bundle_number'] . '"> Check All</label> </br></div>';

                                            for ($i = 1; $i <= $sql['pcs_per_bundle']; $i++) {
                                                if ($numm > 0) {
                                                    if (in_array($sql['boundle_qr'] . $i, $inarr)) {
                                                        $ch = 'checked';
                                                    } else if ($_REQUEST['readonly'] == true) {
                                                        $ch = 'disabled';
                                                    } else {
                                                        $ch = '';
                                                    }
                                                } else {
                                                    $ch = 'checked';
                                                }
                                                print '<div class="col-md-2"><input type="checkbox" class="ncbox' . $sql['bundle_number'] . '" name="' . $sql['boundle_qr'] . '[]" value="' . $sql['boundle_qr'] . $i . '" ' . $ch . ' > <label class="">' . $sql['boundle_qr'] . $i . '</label> </div>';
                                            }

                                            print '</div> </td> </tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class=" row">
                            <div class="col-md-12">
                                <div class="form-group" style="text-align: center;">
                                    <a class="btn btn-secondary" href="in_house.php">Cancel</a>
                                    <input type="submit" class="btn btn-success" name="SaveBtn" value="Submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal fade" id="Modalcomplete" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Daily Processing</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Employee Name</th>
                                            <th>Qty Completed</th>
                                        </tr>
                                    </thead>
                                    <tbody id="compBody"></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
    <?php include('includes/end_scripts.php'); ?>

    <script>
        function completed_qtycheck(id) {
            var a = $("#max_qty" + id).val();
            var b = $("#completed_qty" + id).val();
            var c = $("#already_d" + id).val();

            if (parseInt(a) < (parseInt(b) + parseInt(c))) {
                message_noload('warning', 'Qty Exceed Required!', 1000);
                $("#completed_qty" + id).val(0);
            }
        }
    </script>

    <script>
        function dailyCompletye(id) {

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?dailyCompletye=0&id=' + id,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    $("#compBody").html(json.tbody);
                }
            });

            $("#Modalcomplete").modal('show');
        }
    </script>

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