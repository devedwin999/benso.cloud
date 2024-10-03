<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['updateForm'])) {

    $yarn = array();
    for($m=0; $m<count($_REQUEST['edit_yarn_name']); $m++) {
        if($_REQUEST['edit_yarn_name'][$m]!="" && $_REQUEST['edit_mixing_percentage'][$m]!="") {
            $yarn[] = $_REQUEST['edit_yarn_name'][$m] .'=='. $_REQUEST['edit_mixing_percentage'][$m];
        }
    }
    
    $data_arr = array(
        'fabric_type' => $_REQUEST['edit_fabric_type'],
        'fabric_name' => $_REQUEST['edit_fabric_name'],
        'gsm' => $_REQUEST['edit_gsm'],
        'dying_color' => $_REQUEST['edit_dying_color'],
        'aop_name' => $_REQUEST['edit_aop_name'],
        'yarn_mixing' => json_encode($yarn),
    );

    $qry = Update('mas_stockitem', $data_arr, ' WHERE id = '. $_REQUEST['mas_stockitem_id']);
    timeline_history('Insert', 'mas_stockitem', $_REQUEST['mas_stockitem_id'], 'Stock Item Updated. Ref: '. $_REQUEST['edit_fabric_name']);

    $_SESSION['msg'] = "updated";

    header("Location:mas_stockgroup_item.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Stock Group Item</title>

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

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Stock Group Item Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Stock Group Item Updated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
        <?php } else if ($_SESSION['msg'] == 'error') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Something Went Wrong!.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
        <?php }
        $_SESSION['msg'] = '';
        ?>

        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="card-box mb-30">
                    
                    <?php if(MAS_STG_ITEM!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <?php if(MAS_STG_ITEM_ADD==1) { ?>
                            <a class="btn btn-primary showmodal" href="javascript:void(0)" style="float: right;">+ Add New</a>
                        <?php } ?>
                        <h4 class="text-blue h4">Manage Stock Group Item
                            <p class="mb-30 text-danger">
                                <i class="icon-copy fa fa-info-circle" aria-hidden="true" title="Info" style="font-size: 15px;"></i> Click on the Status To change
                            </p>
                        </h4>
                    </div>
                    <div class="pb-20">
                        <table class="table hover multiple-select-row data-table-export nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus datatable-nosort">S.No</th>
                                    <th>Fabric Type</th>
                                    <th>Fabric Name</th>
                                    <th>Yarn Mixing</th>
                                    <th>GSM</th>
                                    <th>Dying Color</th>
                                    <th>AOP Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qry = "SELECT * FROM mas_stockitem ORDER BY id DESC";
                                $query = mysqli_query($mysqli, $qry);
                                $x = 1;
                                while ($sql = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $x; ?></td>
                                        <td><?= $sql['fabric_type']; ?></td>
                                        <td><?= fabric_name($sql['fabric_name']); ?></td>
                                        <td><?php $k=1; foreach(json_decode($sql['yarn_mixing']) as $ymp) { $ymp = explode('=', $ymp); print $k.'. '. mas_yarn_name($ymp[0]).' - '. $ymp[2] .' %<br>';  $k++;} ?></td>
                                        <td><?= $sql['gsm']; ?></td>
                                        <td><?= $sql['dying_color'] ? color_name($sql['dying_color']) : '-'; ?></td>
                                        <td><?= $sql['aop_name'] ? $sql['aop_name'] : '-'; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <?php if(MAS_STG_ITEM_EDIT==1) { ?>
                                                        <a class="dropdown-item editmodal" data-id="<?= $sql['id']; ?>" href="javascript:void(0)"><i class="dw dw-edit2"></i> Edit</a>
                                                    <?php } if(MAS_STG_ITEM_DELETE==1) { ?>
                                                        <a class="dropdown-item" onclick="delete_data(<?= $sql['id']; ?>, 'mas_stockitem')"><i class="dw dw-delete-3"></i> Delete</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $x++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Edit Stock Group Item</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="table_name" id="table_name" value="">
                                <div class="modal-body">
                                    <div class="row" id="editmodaldetail">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="updateForm" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                    <input type="hidden" id="temp_id" value="0">
                    <input type="hidden" id="edit_temp_id" value="0">
            </div>
            <?php
            $modals = ['mas_stockgroup_item-add-modal'];
            
            include('includes/footer.php');
            include('modals.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
    <script>
        $(".addMoreyarn").click(function() {
            
            var yn = $("#yarn_name").val();
            var mx = $("#mixing_percentage").val();
            i=0;
            (yn=="") ? message_noload('error', 'Yarn Name Required!', 1500) : i++;
            (mx=="") ? message_noload('error', 'Enter Mixing Percentage!', 1500) : i++;
            
            var yarn = $('#yarn_name option:selected').text();
            
            var temp_id = $("#temp_id").val();
            
            if(i==2) {
                var list = '<tr id="tr'+ temp_id +'"><td>'+ yarn +'</td><td><input type="hidden" name="yarn_name[]" value="'+ yn +'"> <input type="hidden" name="mixing_percentage[]" value="'+ mx +'">'+ mx +' %</td><td><a class="border border-secondary rounded text-secondary" onclick="deleteRow('+ temp_id +')"><i class="fa fa-trash"></i></a></td></tr>';
                
                $("#tbody_tr").before(list);
                $("#yarn_name").val('').trigger('change');
                $("#mixing_percentage").val('');
                
                $("#temp_id").val((parseInt(temp_id)+1));
            }
            
        });
        
        function add_edit() {
            
            var yn = $("#edit_yarn_name").val();
            var mx = $("#edit_mixing_percentage").val();
            i=0;
            (yn=="") ? message_noload('error', 'Yarn Name Required!', 1500) : i++;
            (mx=="") ? message_noload('error', 'Enter Mixing Percentage!', 1500) : i++;
            
            var yarn = $('#edit_yarn_name option:selected').text();
            
            var temp_id = $("#edit_temp_id").val();
            
            if(i==2) {
                var list = '<tr id="tr'+ temp_id +'"><td>'+ yarn +'</td><td><input type="hidden" name="edit_yarn_name[]" value="'+ yn +'"> <input type="hidden" name="edit_mixing_percentage[]" value="'+ mx +'">'+ mx +' %</td><td><a class="border border-secondary rounded text-secondary" onclick="deleteRow('+ temp_id +')"><i class="fa fa-trash"></i></a></td></tr>';
                
                $("#edit_tbody_tr").before(list);
                $("#edit_yarn_name").val('').trigger('change');
                $("#edit_mixing_percentage").val('');
                
                $("#tedit_emp_id").val((parseInt(temp_id)+1));
            }
        }
    </script>
    
    <script>
        function deleteRow(id) {
            $("#tr" + id).remove();
        }
    
        // $(document).ready(function(){
        //     $('.fa-trash').on('click', function(){
        //         alert();
        //         $(this).closest('tr').remove();
        //     });
        // });
    </script>

    <script>
        $('#yarn-add-modal').on('shown.bs.modal', function () {
            $('#yarn_name').focus();
        })
        $('#edit-modal').on('shown.bs.modal', function () {
            $('#edit_yarn_name').focus();
        })


        $(".showmodal").click(function () {
            $("#mas_stockgroup_item-add-modal").modal('show');
        })
    </script>

    <script>
        $(".editmodal").click(function () {

            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: 'ajax_search2.php?get_stock_item_edit=1&id=' + id,
                success: function (msg) {
                    $("#editmodaldetail").html(msg);
                    
                    $("#edit_fabric_type").select2();
                    $("#edit_fabric_name").select2();
                    $("#edit_dying_color").select2();
                    $("#edit_yarn_name").select2();
                }
            })

            $("#edit-modal").modal('show');
        })
    </script>

</html>