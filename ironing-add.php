<?php
include ("includes/connection.php");
include ("includes/function.php");

include ("includes/perm.php");

$urlTyp = $_GET['typ'];

if (isset($_POST['SaveBtn'])) {

    $value = array(
        'order_id' => $_POST['order_id'],
        'style_id' => $_POST['style_id'],
        'sod_part' => $_POST['sod_part'],
        'entry_number' => $_POST['entry_number'],
        'entry_date' => $_POST['entry_date'],
        'assigned_emp' => $_POST['employee'],
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    if ($_POST['iid'] == "") {
        $ins = Insert('ironing', $value);
        $inid = mysqli_insert_id($mysqli);
    } else {

        $value2 = array(
            'entry_number' => $_POST['entry_number'],
            'entry_date' => $_POST['entry_date'],
            'assigned_emp' => $_POST['employee'],
        );

        $ins = Update('ironing', $value2, 'WHERE id = ' . $_POST['iid']);
        $inid = $_POST['iid'];
    }

    for ($p = 0; $p < count($_POST['sod_size']); $p++) {

        $irn = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sod_size WHERE id = " . $_POST['sod_size'][$p]));

        $val = array(
            'ironing_id' => $inid,
            'order_id' => $irn['order_id'],
            'style_id' => $irn['style_id'],
            'sod_combo' => $irn['sod_combo'],
            'sod_part' => $irn['sod_part'],
            'sod_size' => $irn['id'],
            'combo_id' => $irn['combo_id'],
            'part_id' => $irn['part_id'],
            'color_id' => $irn['color_id'],
            'variation_value' => $irn['variation_value'],
            'order_qty' => $irn['excess_qty'],
            'ironing_qty' => $_POST['ironing_qty'][$p],
        );

        if ($_POST['ironing_det_id'][$p] == '') {
            $ins = Insert('ironing_detail', $val);
        } else {

            $val1 = array(
                'ironing_qty' => $_POST['ironing_qty'][$p],
            );

            $ins = Update('ironing_detail', $val1, 'WHERE id = ' . $_POST['ironing_det_id'][$p]);
        }
    }


    $_SESSION['msg'] = "added";

    header("Location:ironing.php");

    exit;
}

if (isset($_GET['id'])) {
    $ID = $_GET['id'];
    $comp = 'Edit Ironing';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM ironing WHERE id=" . $ID));
} else {
    $comp = 'Add Ironing';
    $ID = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Ironing
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

    .rounded {
        padding: 1px !important;
    }

    .tmp-hide {
        display: none;
        border-top: none;
        border-right: none;
        border-left: none;
        width: 50%;
    }

    /*#reader__status_span {*/
    /*    font-size:25px !important;*/
    /*}*/
</style>

<body>

    <?php include ('includes/header.php'); ?>

    <?php include ('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="pd-20 card-box mb-30">
                    <?php if (IRONING_ADD != 1) {
                        action_denied();
                        exit;
                    } ?>
                    <div class="pd-20">
                        <a class="btn btn-outline-primary" href="ironing.php" style="float: right;"><i
                                class="fa fa-list" aria-hidden="true"></i> Ironing List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                Ironing Entry
                            </h4>
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">
                        <input type="hidden" name="iid" id="iid" value="<?= $ID; ?>">

                        <div class="row">
                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['entry_number'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM ironing WHERE entry_number LIKE '%IR-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'IR-1';
                                } else {
                                    $ex = explode('-', $sqql['entry_number']);
                                    $value = $ex[1];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
                                    $code = $ex[0] . '-' . $nnum;
                                }
                            }
                            ?>
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" readonly name="entry_number" class="form-control"
                                        value="<?= $code; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Entry Date <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="date" name="entry_date" class="form-control" required
                                        value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>

                            <div class="col-md-2  <?= isset($_GET['id']) ? 'pe-none' : ''; ?>">
                                <label for="">BO <span class="text-danger">*</span></label>
                                <div class="form-group">

                                    <select class="custom-select2 form-control" name="order_id" id="order_id" required>
                                        <?= select_dropdown('sales_order', array('id', 'order_code'), 'id DESC', $sql['order_id'], '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2  <?= isset($_GET['id']) ? 'pe-none' : ''; ?>">
                                <label class="fieldrequired">Style :</label>
                                <select class="custom-select2 form-control" name="style_id" id="style_id" required
                                    style="width:100%;">
                                    <?= isset($_GET['id']) ? select_dropdown('sales_order_detalis', array('id', 'style_no'), 'id ASC', $sql['style_id'], ' WHERE sales_order_id = ' . $sql['order_id'], '') : ''; ?>
                                </select>
                            </div>

                            <div class="col-md-2  <?= isset($_GET['id']) ? 'pe-none' : ''; ?>">
                                <label class="fieldrequired">Combo || Part || Color</label>
                                <select class="custom-select2 form-control" name="sod_part" id="sod_part" required
                                    style="width:100%;">
                                    <?php
                                    if (isset($_GET['id'])) {
                                        $kb = mysqli_query($mysqli, "SELECT * FROM sod_part WHERE sales_order_detail_id = '" . $sql['order_id'] . "'");
                                        $data['option'][] = '<option value="">Select</option>';
                                        while ($opt = mysqli_fetch_array($kb)) {

                                            print '<option value="' . $opt['id'] . '" >' . color_name($opt['combo_id']) . ' | ' . part_name($opt['part_id']) . ' | ' . color_name($opt['color_id']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="col-form-label">Employee <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="employee" id="employee" class="custom-select2 form-control" required
                                        style="width:100%">
                                        <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', ($sql['assigned_emp'] ? $sql['assigned_emp'] : $logUser), 'WHERE is_active="active"', ''); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Size</th>
                                            <th>Order Qty</th>
                                            <th>Already Ironed Qty</th>
                                            <th>Ironing Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody id="main_tbody">
                                        <?php
                                        if (isset($_GET['id'])) {
                                            $da = mysqli_query($mysqli, "SELECT * FROM ironing_detail WHERE ironing_id = " . $ID);
                                            if (mysqli_num_rows($da) > 0) {
                                                $sl = 1;
                                                while ($result = mysqli_fetch_array($da)) {
                                                    $sum = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(ironing_qty) as ironing_qty FROM ironing_detail WHERE sod_size = '" . $result['sod_size'] . "' AND variation_value = '" . $result['variation_value'] . "'"));
                                                    print '<tr>
                                                            <td>' . $sl . '</td>
                                                            <td>' . variation_value($result['variation_value']) . '</td>
                                                            <td>' . $result['order_qty'] . '</td>
                                                            <td>' . ($allowed = $sum['ironing_qty'] ? $sum['ironing_qty'] : '0') . '</td>';

                                                    if ($_GET['type'] == 'view') {

                                                        print '<td>' . $result['ironing_qty'] . '</td>';
                                                    } else {
                                                        print '<td>
                                                                    <input type="hidden" name="ironing_det_id[]" value="' . $result['id'] . '">
                                                                    <input type="hidden" name="sod_size[]" value="' . $result['variation_value'] . '">
                                                                    <input type="text" name="ironing_qty[]" style="max-width: 250px;" class="form-control" placeholder="Ironing Qty" value="' . $result['ironing_qty'] . '"></td>';
                                                    }
                                                    print '</tr>';
                                                    $sl++;
                                                }
                                            } else {
                                                print '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
                                            }
                                        } else {
                                            print '<tr><td colspan="5" class="text-center">No Data Found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot class="tfoot <?= isset($_GET['id']) ? '' : 'd-none'; ?>">
                                        <?php if ($_GET['type'] != 'view') { ?>
                                            <tr>
                                                <td class="text-center" colspan="5">
                                                    <button type="submit" name="SaveBtn" class="btn btn-outline-primary"><i
                                                            class="fa-save fa"></i>
                                                        <?= isset($_GET['id']) ? 'Update' : 'Save'; ?></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tfoot>
                                </table>
                                <hr>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php include ('includes/footer.php');
            include ('modals.php'); ?>
        </div>
    </div>
    <?php include ('includes/end_scripts.php'); ?>

    <script>
        // $(document).ready(function() {
        // $(".qty_validation").keyup(function(){
        //     alert();
        // });

        function qty_validation(element) {

            var value = $(element).val();
            var allowed = $(element).data('allowed');

            if (allowed < value) {
                $(element).val(allowed).select();
                message_noload('info', 'Qty Exeeded!', 2500);
            }
        }
        // });
    </script>

    <script>
        $("#style_id").change(function () {
            var id = $(this).val();
            $("#overlay").fadeIn(100);

            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?get_combo_details_for_style=1&id=' + id,
                success: function (msg) {
                    var json = $.parseJSON(msg);
                    $("#sod_part").html(json.option);
                    $("#main_tbody").html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                    $(".tfoot").addClass('d-none');
                    $("#overlay").fadeOut(500);
                }
            });
        });
    </script>

    <script>
        $("#order_id").change(function () {
            var a = $(this).val();
            $("#overlay").fadeIn(100);
            if (a == "") {
                $("#style_id").html('').trigger('change');
                $("#sod_part").html('').trigger('change');
                $("#multibundle").html('').trigger('change');
                $("#overlay").fadeOut(500);
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?getStyleNoforProcess=1&id=' + a,
                success: function (msg) {
                    $("#style_id").html(msg);
                    $("#main_tbody").html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                    $(".tfoot").addClass('d-none');
                    $("#overlay").fadeOut(500);
                }
            });
        })
    </script>

    <script>
        $("#sod_part").change(function () {
            var id = $(this).val();
            $("#overlay").fadeIn(100);

            var data = {
                id: id,
            };
            if (id != "") {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search3.php?type=sizeWizeListfor_ironing',
                    data: data,
                    success: function (msg) {
                        var json = $.parseJSON(msg);
                        $("#main_tbody").html(json.tbody);
                        $(".tfoot").removeClass('d-none');
                        $("#overlay").fadeOut(500);
                    }
                });
            } else {

                $("#main_tbody").html('<tr><td colspan="5" class="text-center">No data found</td></tr>');
                $(".tfoot").addClass('d-none');
                $("#overlay").fadeOut(500);
            }
        });
    </script>

</body>

</html>