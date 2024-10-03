<?php
include("includes/connection.php");
include("includes/function.php");


include("includes/perm.php");

$data = array(

    'supplier_name' => filter_var($_POST['supplier_name'], FILTER_SANITIZE_STRING),

    'supplier_code' => filter_var($_POST['supplier_code'], FILTER_SANITIZE_STRING),

    'address1' => filter_var($_POST['address1'], FILTER_SANITIZE_STRING),

    'address2' => filter_var($_POST['address2'], FILTER_SANITIZE_STRING),

    'area' => filter_var($_POST['area'], FILTER_SANITIZE_STRING),

    'country' => filter_var($_POST['country'], FILTER_SANITIZE_STRING),

    'state' => filter_var($_POST['state'], FILTER_SANITIZE_STRING),

    'city' => filter_var($_POST['city'], FILTER_SANITIZE_STRING),

    'mobile' => filter_var($_POST['mobile_no'], FILTER_SANITIZE_STRING),

    'phone1' => filter_var($_POST['phone_no1'], FILTER_SANITIZE_STRING),

    'phone2' => filter_var($_POST['phone_no2'], FILTER_SANITIZE_STRING),

    'emailid' => filter_var($_POST['emailid'], FILTER_SANITIZE_STRING),

    'gst_no' => filter_var($_POST['gst_no'], FILTER_SANITIZE_STRING),

    'birthdate' => filter_var($_POST['birthdate'], FILTER_SANITIZE_STRING),

    'wedd_date' => filter_var($_POST['other_license'], FILTER_SANITIZE_STRING),

    'created_date' => date('Y-m-d H:i:s')

);

if (isset($_POST['addsupplier']) && isset($_GET['id'])) {
    $qry = Update('supplier', $data, " WHERE id = '" . $_GET['id'] . "'");

    $_SESSION['msg'] = "updated";

    header("Location:supplier.php");

    exit;
} else if (isset($_POST['addsupplier']) && !isset($_GET['id'])) {

    $qry = Insert('supplier', $data);

    $_SESSION['msg'] = "added";

    header("Location:supplier.php");

    exit;
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $comp = 'Edit Supplier';
    $sql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM supplier WHERE id=" . $id));
} else {
    $comp = 'Add Supplier';
    $id = '';
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING -
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

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <!-- Default Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    
                    <?php if(MAS_SUPPLIER_ADD!=1) { action_denied(); exit; }?>
                    
                    <div class="pd-20">
                        <a class="btn btn-primary" href="supplier.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Supplier List</a>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-blue h4">
                                <?= $comp; ?>
                            </h4>
                            <p class="mb-30 text-danger">(*) Fields are Mandatory</p>
                        </div>
                    </div>
                    <form id="add-supplier" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-form-label">Supplier Name <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input class="form-control d-cursor" type="text" name="supplier_name"
                                        id="supplier_name" placeholder="Supplier Name"
                                        value="<?= $sql['supplier_name'] ? $sql['supplier_name'] : ''; ?>">
                                </div>
                            </div>

                            <?php
                            if (isset($_GET['id'])) {
                                $code = $sql['supplier_code'];
                            } else {
                                $qryz = mysqli_query($mysqli, "SELECT * FROM supplier WHERE supplier_code LIKE '%SUP-%' ORDER BY id DESC");
                                $sqql = mysqli_fetch_array($qryz);
                                $numm = mysqli_num_rows($qryz);
                                if ($numm == 0) {
                                    $code = 'SUP-1';
                                } else {
                                    $ex = explode('-', $sqql['supplier_code']);

                                    $value = $ex[1];
                                    $intValue = (int) $value;
                                    $newValue = $intValue + 1;
                                    // $nnum = str_pad($newValue, strlen($value), '0', STR_PAD_LEFT);
                            
                                    $code = $ex[0] . '-' . $newValue;
                                }
                            }
                            ?>

                            <div class="col-md-4">
                                <label class="col-form-label">Supplier Code </label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="supplier_code" id="supplier_code"
                                        placeholder="Supplier Code" value="<?= $code; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Address 1</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address1" id="address1"
                                        placeholder="Address 1"
                                        value="<?= $sql['address1'] ? $sql['address1'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Address 2</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address2" id="address2"
                                        placeholder="Address 2"
                                        value="<?= $sql['address2'] ? $sql['address2'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Area</label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="area" id="area" placeholder="Area"
                                        value="<?= $sql['area'] ? $sql['area'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="">Country</label>
                                <div class="form-group">
                                    <select name="country" id="country" class="custom-select2 form-control">
                                        <?= select_dropdown('master_country', array('auto_number', 'country'), 'country ASC', $sql['country'] ? $sql['country'] : 101, '', ''); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">State</label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="state" id="state">
                                        <option value="">Select State</option>
                                        <?php
                                        if (isset($_GET['id']) && !empty($sql['country'])) {
                                            $where = "country_id='" . $sql['country'] . "'";
                                        } else {
                                            $where = "country_id='101'";
                                        }

                                        $qryd = mysqli_query($mysqli, "SELECT * FROM states WHERE $where ORDER BY state_name ASC ");
                                        while ($stt = mysqli_fetch_array($qryd)) {
                                            if (isset($_GET['id']) && !empty($sql['state'])) {
                                                if ($stt['id'] == $sql['state']) {
                                                    $sell = 'selected';
                                                } else {
                                                    $sell = '';
                                                }
                                            } else {
                                                $sell = '';
                                            }
                                            print '<option value="' . $stt['id'] . '" ' . $sell . '>' . $stt['state_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">City</label>
                                <div class="form-group">
                                    <select class="custom-select2 form-control" name="city" id="city">
                                        <option value="">Select City</option>
                                        <?php
                                        if (isset($_GET['id']) && !empty($sql['state'])) {
                                            $qryd1 = mysqli_query($mysqli, "SELECT * FROM cities WHERE state_id = '" . $sql['state'] . "' ORDER BY cities_name ASC");
                                            // } else {
                                            // 	$qryd1 = mysqli_query($mysqli, "SELECT * FROM cities ORDER BY cities_name ASC limit 1,10");
                                            // }
                                            while ($stt1 = mysqli_fetch_array($qryd1)) {
                                                if ($stt1['id'] == $sql['city']) {
                                                    $citt = 'selected';
                                                } else {
                                                    $citt = '';
                                                }
                                                print '<option value="' . $stt1['id'] . '" ' . $citt . '>' . $stt1['cities_name'] . '</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Mobile No</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="mobile_no" id="mobile_no"
                                        placeholder="Mobile No" value="<?= $sql['mobile'] ? $sql['mobile'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Phone No 1</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="phone_no1" id="phone_no1"
                                        placeholder="Phone No 1" value="<?= $sql['phone1'] ? $sql['phone1'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Phone No 2</label>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="phone_no2" id="phone_no2"
                                        placeholder="Phone No 2" value="<?= $sql['phone2'] ? $sql['phone2'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">Email Id</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="emailid" id="emailid"
                                        placeholder="emailid" value="<?= $sql['emailid'] ? $sql['emailid'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-form-label">GST No</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="gst_no" id="gst_no"
                                        placeholder="GST No" value="<?= $sql['gst_no'] ? $sql['gst_no'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4 d-none">
                                <label class="col-form-label">Birth Date</label>
                                <div class="form-group">
                                    <input class="form-control" type="date" name="birthdate" id="birthdate"
                                        placeholder="Birth Date"
                                        value="<?= $sql['birthdate'] ? $sql['birthdate'] : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-4 d-none">
                                <label class="col-form-label">Wedding Date</label>
                                <div class="form-group">
                                    <input type="date" class="form-control" name="other_license" id="other_license"
                                        placeholder="Wedding Date"
                                        value="<?= $sql['wedd_date'] ? $sql['wedd_date'] : ''; ?>">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success button-right" name="addsupplier"
                                        value="Submit">
                                    <a class="btn btn-secondary button-right" href="supplier.php">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Default Basic Forms End -->

            </div>

            <?php include('includes/footer.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>

    <script>
        $("#country").change(function () {
            var country = $("#country").val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?get_state=1&country=' + country,
                success: function (msg) {
                    $("#state").html(msg);
                }
            })
        })
    </script>

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
            $('#add-supplier').validate({
                errorClass: "help-block",
                rules: {
                    supplier_name: {
                        required: true
                    },
                    supplier_code: {
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


<script>
    $("#supplier_name").change(function() {
        var value = $("#supplier_name").val();
        
        validate_Duplication('supplier', 'supplier_name', value, 'supplier_name');
        // validate_Duplication(table, table_field, value, input_field)
    });
</script>
</body>

</html>