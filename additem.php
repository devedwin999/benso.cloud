<?php
include("includes/connection.php");
include("includes/function.php");

$data = array(

    'category' => filter_var($_POST['category'], FILTER_SANITIZE_STRING),

    'brand' => filter_var($_POST['brand'], FILTER_SANITIZE_STRING),

    'item_code' => filter_var($_POST['item_code'], FILTER_SANITIZE_STRING),

    'hsn_code' => filter_var($_POST['hsn_code'], FILTER_SANITIZE_STRING),

    'item_name' => filter_var($_POST['item_name'], FILTER_SANITIZE_STRING),

    'unit' => filter_var($_POST['unit'], FILTER_SANITIZE_STRING),

    'sales1' => filter_var($_POST['sales1'], FILTER_SANITIZE_STRING),

    'sales2' => filter_var($_POST['sales2'], FILTER_SANITIZE_STRING),

    'sales3' => filter_var($_POST['sales3'], FILTER_SANITIZE_STRING),

    'gst' => filter_var($_POST['gst'], FILTER_SANITIZE_STRING),

    'description' => filter_var($_POST['description'], FILTER_SANITIZE_STRING),

    'created_date' => date('Y-m-d H:i:s')

);

$no = array('can_delete' => 'no');

if (isset($_POST['saveForm']) && isset($_GET['id'])) {
    $qry = Update('itemlist', $data, " WHERE id = '" . $_GET['id'] . "'");

    if ($qry) {
        $qry = Update('category', $no, " WHERE id = '" . $_POST['category'] . "'");
        $qry = Update('brand', $no, " WHERE id = '" . $_POST['brand'] . "'");
    }

    $_SESSION['msg'] = "updated";

    header("Location:item.php");

    exit;
} else if (isset($_POST['saveForm']) && !isset($_GET['id'])) {

    $qry = Insert('itemlist', $data);

    if ($qry) {
        $qry = Update('category', $no, " WHERE id = '" . $_POST['category'] . "'");
        $qry = Update('brand', $no, " WHERE id = '" . $_POST['brand'] . "'");
    }

    $_SESSION['msg'] = "added";

    header("Location:item.php");

    exit;
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Item';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM itemlist WHERE id=" . $id));
} else {
    $comp = 'Add Item';
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO -
        <?= $comp; ?>
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
    .addicon {
        font-size: 17px !important;
        color: #5e5e5e;
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
                        <a class="btn btn-primary" href="item.php" style="float: right;"><i class="fa fa-list"
                                aria-hidden="true"></i> Item List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                <?= $comp; ?>
                            </h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="saveForm" method="post" autocomplete="off">
                        <div class="row">

                            <div class="col-md-4">
                                <label class="col-form-label">Category <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control d-cursor" name="category" id="category" required="">
                                        <?php print select_dropdown('category', array('id', 'category_name'), 'category_name ASC', $sql['category'], '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Brand <span class="text-danger">*</span></label>

                                <i class="icon-copy fa fa-plus-circle addicon" aria-hidden="true" data-toggle="modal"
                                    data-target="#brand-add-modal"></i>

                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="brand" id="brand" required="">
                                        <?php print select_dropdown('brand', array('id', 'brand_name'), 'brand_name ASC', $sql['brand'], '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['item_code'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM itemlist WHERE item_code LIKE '%IT-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'IT-1';
                                } else {
                                    $ex = explode('-', $sqql['item_code']);

                                    $value = $ex[1];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    // $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);

                                    $code = $ex[0] . '-' . $newValue;
                                }
                            }
                            ?>

                            <div class="col-md-4">
                                <label class="col-form-label">Item Code <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="item_code" id="item_code"
                                        placeholder="Item Code" value="<?= $code ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">HSN Code </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="hsn_code" id="hsn_code"
                                        placeholder="HSN Code" value="<?= $sql['hsn_code'] ? $sql['hsn_code'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Item Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="item_name" id="item_name"
                                        placeholder="Item Code"
                                        value="<?= $sql['item_name'] ? $sql['item_name'] : ''; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Unit </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="unit" id="unit" placeholder="Unit"
                                        value="<?= $sql['unit'] ? $sql['unit'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Sales Rate 1 </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="sales1" id="sales1"
                                        placeholder="Sales Rate 1" value="<?= $sql['sales1'] ? $sql['sales1'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Sales Rate 2 </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="sales2" id="sales2"
                                        placeholder="Sales Rate 2" value="<?= $sql['sales2'] ? $sql['sales2'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Sales Rate 3 </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="sales3" id="sales3"
                                        placeholder="Sales Rate 3" value="<?= $sql['sales3'] ? $sql['sales3'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">GST % </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="gst" id="gst" placeholder="GST %"
                                        value="<?= $sql['gst'] ? $sql['gst'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Description </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="description" id="description"
                                        placeholder="Description"
                                        value="<?= $sql['description'] ? $sql['description'] : ''; ?>">
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success button-right" id="saveForm"
                                        name="saveForm" value="Submit">
                                    <a class="btn btn-secondary button-right" href="item.php">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Default Basic Forms End -->

            </div>

            <?php
            include('includes/footer.php');
            include('modals.php');
            ?>

        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $("#state").change(function () {
            var state = $("#state").val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_city=' + state,
                success: function (msg) {
                    $("#city").html(msg);
                }
            })
        })
    </script>

    <script type="text/javascript">
        $(function () {
            $('#saveForm').validate({
                errorClass: "help-block",
                rules: {
                    category: {
                        required: true
                    },
                    brand: {
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