<?php
include("includes/connection.php");
include("includes/function.php");

if (isset($_POST['SaveBtn'])) {

    $data = array(
        'entry_num' => filter_var($_POST['entry_num'], FILTER_SANITIZE_STRING),
        'piece_id' => implode(',', $_POST['piece_id']),
        'created_date' => date('Y-m-d H:i:s')
    );

    for ($m = 0; $m < count($_POST['piece_id']); $m++) {
        $upd = mysqli_query($mysqli, "UPDATE bundle_piece_details SET sewing_output='yes' WHERE id='" . $_POST['piece_id'][$m] . "'");
    }

    if (isset($_POST['SaveBtn']) && isset($_GET['id'])) {
        $qry = Update('sewing_output', $data, " WHERE id = '" . $_GET['id'] . "'");

        $_SESSION['msg'] = "updated";

        header("Location:view-sewingOutput.php");

        exit;
    } else if (isset($_POST['SaveBtn']) && !isset($_GET['id'])) {

        $qry = Insert('sewing_output', $data);

        $_SESSION['msg'] = "added";

        header("Location:view-sewingOutput.php");

        exit;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Processing';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sewing_Output WHERE id=" . $id));
} else {
    $comp = 'Add Processing';
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Sewing Outward List</title>

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
                        <a class="btn btn-outline-primary" href="View-sewingOutput.php" style="float: right;"><i
                                class="fa fa-list" aria-hidden="true"></i> Sewing Output List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                Sewing Output
                            </h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-process" method="post" autocomplete="off">
                        <div class="row">

                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['entry_num'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM sewing_output WHERE entry_num LIKE '%SOW-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'SOW-1';
                                } else {
                                    $ex = explode('-', $sqql['entry_num']);

                                    $value = $ex[1];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    // $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
                            
                                    $code = $ex[0] . '-' . $newValue;
                                }
                            }
                            ?>
                            <div class="col-md-3">
                                <label class="col-form-label">Entry Number <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" readonly name="entry_num" class="form-control"
                                        value="<?= $code; ?>">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <label class="col-form-label">Piece QR Code <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="pieceQrNum" id="pieceQrNum" class="custom-select2 form-control"
                                        style="width:100%">
                                        <?= select_dropdown('bundle_piece_details', array('id', 'piece_qr'), 'piece_qr ASC', '', 'WHERE in_proseccing="yes" AND is_inwarded="yes" AND sewing_input="yes"', ''); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding:34px">
                                <button type="button" class="btn btn-outline-primary addrow"> Add</button>
                            </div>


                            <div class="col-md-12" style="overflow-y: scroll;">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>QR Code</th>
                                            <th>Style No</th>
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th>Boundle No</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if (isset($_GET['id'])) {
                                            $ftch = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM sewing_Output WHERE id='" . $_GET["id"] . "'"));

                                            foreach (explode(',', $ftch['piece_id']) as $value) {

                                                $qry = "SELECT a.*, d.style_no, e.color_name, f.type, b.bundle_number ";
                                                $qry .= "FROM bundle_piece_details a ";
                                                $qry .= "LEFT JOIN bundle_details b ON a.bundle_detail_id=b.id ";
                                                $qry .= "LEFT JOIN cutting_barcode c ON b.cutting_barcode_id=c.id ";
                                                $qry .= "LEFT JOIN sales_order_detalis d ON c.style=d.id ";
                                                $qry .= "LEFT JOIN color e ON c.color=d.id ";
                                                $qry .= "LEFT JOIN variation_value f ON b.variation_value=f.id ";
                                                $qry .= "WHERE a.id='" . $value . "' ";

                                                $res = mysqli_query($mysqli, $qry);

                                                $sql = mysqli_fetch_array($res);

                                                ?>
                                                <tr>
                                                    <td><input type="hidden" id="" name="piece_id[]" value="<?= $sql['id']; ?>">
                                                        <?= $sql['piece_qr'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $sql['style_no'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $sql['color_name'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $sql['type'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $sql['bundle_number'] ?>
                                                    </td>
                                                    <td>
                                                        <?= '-' ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </tbody>
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
        $(".addrow").click(function () {
            var a = $("#pieceQrNum").val();
            if (a == "") {
                message_noload('warning', 'Select Piece QR Code!', 2000);
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_search.php?pieceQrNum=1&id=' + a,
                    success: function (msg) {

                        var json = $.parseJSON(msg);
                        // alert(json);
                        // console.log(json);

                        // if (json.num == 0) {
                        //     message_noload('warning', 'Bundle Not Found!', 1000);
                        // } else {

                        $("#tableBody").append(json.table);
                        $("#boundle_id").val('');
                        $("#qr_number").val('');
                        $("#pieceQrNum").val('').trigger('change')

                        swal(
                            {
                                position: 'top-end',
                                type: 'success',
                                title: 'Piece Added',
                                showConfirmButton: false,
                                timer: 1000
                            }
                        )
                        // }
                    }
                })
            }
        })

    </script>

    <script>
        function removeRow(id) {
            $("#tbTr" + id).remove();
        }
    </script>

    <!-- <script>
        $("#qr_number").autocomplete({
            source: "ajax_search.php?getPiecedet=1&table=bundle_details&searchField=boundle_qr",
            select: function (event, ui) {
                event.preventDefault();

                $("#qr_number").val(ui.item.value);
                $("#boundle_id").val(ui.item.id);
            }
        });
    </script> -->

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