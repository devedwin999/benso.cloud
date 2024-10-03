<?php
include("includes/connection.php");
include("includes/function.php");
// require_once("phpqrcode/qrlib.php");

include("includes/perm.php");

$data = array();
$ID = $_GET['id'];
$LAY = $_GET['lay'];

if (isset($_POST['saveBarcode1'])) {
    
    for ($k = 0; $k < count($_REQUEST['bundle_id']); $k++) {

        $item = str_replace(' ', '', $_REQUEST['barcode_det'][$k]);
        $file = "uploads/qrcode/" . $item . "/" . $item . ".png";
        $ecc = 'H';
        $pixel_size = 20;
        $frame_size = 1;
        
        $sel = mysqli_fetch_array(mysqli_query($mysqli, "SELECT pcs_per_bundle FROM bundle_details WHERE id='". $_REQUEST['bundle_id'][$k] ."' "));
        
        $range = range(1, $sel['pcs_per_bundle']);
        
        $mk = mysqli_query($mysqli, "UPDATE bundle_details SET boundle_qr='" . $item . "', boundle_qrImage='" . $item . ".png', tot_checking = '". implode(',', $range) ."', tot_ironing = '". implode(',', $range) ."', tot_packing = '". implode(',', $range) ."' WHERE id=" . $_REQUEST['bundle_id'][$k]);
    }


    $_SESSION['msg'] = "bundle_generated";

    header("Location:generate-barcode.php?id=" . $ID . "&lay=" . $LAY);

    exit;
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

        .col-md-4 {
            padding: 15px !important;
        }
    </style>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Generate Barcode</title>

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

    <style>
        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>
</head>

<body>

    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>

    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>

    <div class="cell">
        <div class="card">
            <span class="dots-loader">Loading&#8230;</span>
        </div>
    </div>

    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'Piece_generated') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Pieces Barcode Generated.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'bundle_generated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Bundle Barcode Generated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php }
        $_SESSION['msg'] = '';
        
        $resultt = mysqli_fetch_array(mysqli_query($mysqli, "SELECT entry_number FROM cutting_barcode WHERE id=" . $ID));
        ?>
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="pd-20 card-box mb-30">
                    <?php page_spinner(); if(CUTTING_QR_GENERATE!=1) { action_denied(); exit; } ?>
                    <div class="pd-20">
                        <h4 class="text-blue h4">Generate Barcode for <span style="color:red;text-decoration:underline "><?= $resultt['entry_number'] ?></span>
                            <a class="btn btn-outline-primary" href="view-barcode.php" style="float: right;"><i class="fa fa-list" aria-hidden="true"></i> Barcode List</a>
                        </h4>
                    </div>

                    <form id="saveBarcodeForm" method="POST" enctype="multipart/form-data">
                        <div style="overflow-y: auto;width:100%">
                            
                            <script>
                                function print_selected() {
                                    
                                    var val = [];
                                    var id = 0;
                                    $(".int_cbox").each(function() {
                                        if($(this).is(':checked')) {
                                            id++;
                                            val.push($(this).val());
                                        }
                                    });
                                    
                                    if(id==0) {
                                        message_noload('error', 'Nothing Selected!');
                                        return false;
                                    }
                                    
                                    var url ="piece_barcode.php?dta=" + btoa(val);
                                    window.open(url, '_blank');
                                    
                                }
                                
                                
                                function openPrint() {
                                    
                                    var url = 'qr_print.php?id=' + $("#refId").val() + '&lay=' + $("#layId").val() + '&comp=' + $("#component").val();
                                    window.open(url, '_blank');
                                }
                            </script>
                            
                            <table class="table table-striped table-bordered" style="width:100% !important">
                                <thead>
                                    <tr style="background-color: ;">
                                        <th><label>BO No</label></th>
                                        <th><label>Style No</label></th>
                                        <th><label>Combo</label></th>
                                        <th><label>Part</label></th>
                                        <th><label>Color</label></th>
                                        <th><label>Bundle No</label></th>
                                        <th><label>Bundle Qty</label></th>
                                        <th>
                                            <label>Bundle QR<br>
                                                <select class="form-control custom-select2" id="component">
                                                    <?= select_dropdown('mas_component', array('id', 'component_name'), 'component_name ASC', '', '', ''); ?>
                                                </select>
                                                <input type="hidden" id="layId" value="<?= $LAY; ?>">
                                                <input type="hidden" id="refId" value="<?= $ID; ?>">
                                                <a onclick="openPrint()" target="_blank"><i class="icon-copy fa fa-print" aria-hidden="true" style="font-size: 20px;" title="Print All"></i></a>
                                            </label>
                                        </th>
                                        <th><label>Size</label></th>
                                        <th>
                                            <label>Piece QR</label><br>
                                            <input type="checkbox" class="c_all" id="c_all" value="c_all"><label for="c_all">(All)</label>&nbsp;&nbsp;
                                            <a onclick="print_selected()" target="_blank"><i class="icon-copy fa fa-print" aria-hidden="true" style="font-size: 20px;" title="Print All"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                    
                                    $qry = "SELECT a.* ";
                                    $qry .= " FROM bundle_details a ";
                                    $qry .= " WHERE a.cutting_barcode_id = '" . $ID . "' AND a.lay_length='" . $LAY . "'";

                                    $sql = mysqli_query($mysqli, $qry);
                                    while ($row = mysqli_fetch_array($sql)) {
                                        ?>
                                        <tr>
                                            <td><?= sales_order_code($row['order_id']); ?></td>
                                            <td><?= sales_order_style($row['style_id']); ?>
                                                <input type="hidden" name="bundle_id[]" id="bundle_id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="barcode_det[]" id="barcode_det" value="<?= str_replace('-', '', sales_order_code($row['order_id'])) . sales_order_style($row['style_id']) . $row['combo'] . $row['color'] . $row['part'] . $row['bundle_number']; ?>">
                                            </td>
                                            <td><?= color_name($row['combo']); ?></td>
                                            <td><?= part_name($row['part']); ?></td>
                                            <td><?= color_name($row['color']); ?></td>
                                            <td><?= $row['bundle_number']; ?></td>
                                            <td><?= $row['pcs_per_bundle'] ?></td>
                                            <td>
                                                <input type="hidden" value="<?= $row['boundle_qr']; ?>" name="" id="dsff<?= $row['id']; ?>"> 

                                                    <?php
                                                    if($row['boundle_qr']=="")
                                                    {
                                                        print 'Not Generated';
                                                    } else { print $row['boundle_qr']; } ?> 
                                            </td>
                                            <td><?= variation_value($row['variation_value']); ?></td>
                                            <td>
                                                <input type="checkbox" class="int_cbox" id="" value="<?= $row['id']; ?>">&nbsp;&nbsp;
                                                <!--<a href="qrpiece_print.php?bid=<?= $row['id']; ?>" target="_blank"><i class="fa fa-print"></i></a>-->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align:center;margin-top: 70px;">
                            <a href="view-barcode.php" type="button" class="btn btn-outline-secondary" data-dismiss="modal">Go Back</a>&nbsp;&nbsp;&nbsp;
                            <input type="submit" name="saveBarcode1" id="saveBarcode" class="btn btn-outline-primary saveBtn" value="Generate QR">&nbsp;&nbsp;&nbsp;
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="image-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-top">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">QR View</h4>
                            <i class="icon-copy fa fa-print" aria-hidden="true"
                                style="position: absolute;font-size: x-large;right: 75px;" onclick="PrintDiv()"></i>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form method="post" id="var_modalform" enctype="multipart/form-data">

                            <div class="modal-body" id="divToPrint">

                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Sl.No : <span id="td_sonum"></span></td>
                                            <td></td>
                                            <td>Style No : <span id="td_style_no"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Color : <span id="td_color_name"></span></td>
                                            <td></td>
                                            <td>Size : <span id="td_type"></span></td>
                                        </tr>
                                        <tr>
                                            <td>Bundle No : <span id="td_no_of_bundle"></span></td>
                                            <td></td>
                                            <td>QR : <span id="td_qrnum"></span></td>
                                        </tr>
                                    </tbody>
                                </table>


                                <div style="text-align: center;" id="img_space"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade bs-example-modal-lg" id="pice_modal" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Pieces QR List

                                <!-- <i class="icon-copy fa fa-print" aria-hidden="true"
                                    style="position: absolute;font-size: x-large;right: 75px;"
                                    onclick="PrintPieces()"></i> -->
                                <a class="printpiececlass" target="_blank"><i class="icon-copy fa fa-print"
                                        aria-hidden="true"
                                        style="position: absolute;font-size: x-large;right: 75px;top:20px"></i></a>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body" id="piecesToPrint">
                            <div class="row" id="pieces_body"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>

        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        $(".saveBtn").click(function() {
            $("#overlay").fadeIn(100);
        });
    </script>
    
    <script>
        $("#c_all").click(function() {
            
            var ch = $(this).is(':checked');
            
            if(ch==true) {
                $(".int_cbox").prop('checked', true);
            } else {
                $(".int_cbox").prop('checked', false);
            }
        });
    </script>

    <script>
        $('#image-modal').on('hidden.bs.modal', function () {
            $("#td_sonum").text('');
            $("#td_style_no").text('');
            $("#td_color_name").text('');
            $("#td_type").text('');
            $("#td_no_of_bundle").text('');
            $("#td_qrnum").text('');
            $("#img_space").html('<span>No Connection. Reload Page.</span>'); 
        });
        $('#pice_modal').on('hidden.bs.modal', function () {
            $("#pieces_body").html('<span>No Connection. Reload Page.</span>');
        });
    </script>

    <script>
        function viewpicebarcode(id) {

            $(".printpiececlass").attr("href", "qrpiece_print.php?bid=" + id);

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?pices_listt=1&id=' + id,

                success: function (msg) {
                    var json = $.parseJSON(msg);
                    $("#pieces_body").html(json.body);
                }
            });

            $("#pice_modal").modal('show');
        }
    </script>

    <script type="text/javascript">
        function PrintDiv() {
            var divToPrint = document.getElementById('divToPrint');
            var popupWin = window.open('', '_blank', '');
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();
        }
    </script>

    <script type="text/javascript">
        function PrintPieces() {
            var divToPrint = document.getElementById('piecesToPrint');
            var popupWin = window.open('', '_blank', '');
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();
        }
    </script>

    <script>
        function showimage(id, type) {
            var val = $("#dsff" + id).val();

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?qrPrintDet=1&id=' + id,

                success: function (msg) {
                    var json = $.parseJSON(msg);

                    $("#td_sonum").text(json.so_num);
                    $("#td_style_no").text(json.style_no);
                    $("#td_color_name").text(json.color_name);
                    $("#td_type").text(json.type);
                    $("#td_no_of_bundle").text(json.no_of_bundle);
                    $("#td_qrnum").text(json.boundle_qr);
                }
            })

            $.ajax({
                type: 'POST',
                url: 'ajax_search.php?showimage_qr=1&img=' + val,

                success: function (msg) {
                    $("#img_space").html(msg);
                }
            })

            $("#image-modal").modal('show');
        };
    </script>
</body>

</body>

</html>