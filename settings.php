<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if(isset($_POST['img_btn'])) {

    $applogo_image = $_FILES['applogo_image']['name'];
    if (!empty($applogo_image)) {
        if (!is_dir("src/logo/")) {
            mkdir("src/logo/");
        }

        $fille = explode('.', $applogo_image);

        $newName = 'applogo_' . rand(10000, 999999) . '.' . end($fille);

        $uploaddir = 'src/logo/';
        $uploadfile = $uploaddir . $newName;

        move_uploaded_file($_FILES['applogo_image']['tmp_name'], $uploadfile);

        $applogo_image = $uploadfile;
    } else {
        $applogo_image = '';
    }

    
    $data = array(
        'ref' => 'APPLICATION_LOGO',
        'description' => 'Application Logo',
        'value' => $applogo_image,
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qql = mysqli_query($mysqli, "SELECT * FROM settings WHERE ref= 'APPLICATION_LOGO'");
    $uio = mysqli_fetch_array($qql);
    $num = mysqli_num_rows($qql);

    if ($num == 0) {
        $ins = Insert('settings', $data);
        $inId = mysqli_insert_id($ins);
        timeline_history('Insert', 'settings', $inId, $_REQUEST['description'] . ' Setting configuration Inserted.');

    } else {
        $ins = mysqli_query($mysqli, "UPDATE settings SET value = '" . $applogo_image . "' WHERE id = " . $uio['id']);

        $ff = ' Application Logo Updated.';
        timeline_history('Update', 'settings', $uio['id'], $ff);
    }

    $_SESSION['msg'] = "logo_updated";

    header("Location:settings.php");

    exit;

}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO GARMENTING - Settings</title>

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
    
    <!--range slider-->
	<link rel="stylesheet" type="text/css" href="src/plugins/ion-rangeslider/css/ion.rangeSlider.css">

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

    <style>
        /*.accordion span {*/
        /*    color: #e83e8c;*/
            /* font-size: 20px; */
        /*}*/

        .card-body h5 {
            color: #1b00ff;
            text-decoration: underline;
            text-transform: uppercase;
        }
        
        .nav.vtabs.customtab .nav-item.show .nav-link, .nav.vtabs.customtab .nav-link.active {
            border-color: #d1d1d3;
        }
        /*.accordion span {*/
        /*    color: black !important;*/
            /* font-size: 20px; */
        /*}*/
        
        .btn-outline-primary {
            color: #1b00ff !important;
            border-color: #1b00ff;
        }
        
        .btn-outline-primary:hover {
            color: #fff !important;
            background-color: #1b00ff;
            border-color: #1b00ff;
        }
        
        .rounded {
            padding: 0px 5px;
            border-color: #bdbdbd !important;
        }
        
        .modal-lg, .modal-xl {
            max-width: 1500px !important;
        }
        
        .theadInput {
            border-top: none;
            border-right: none;
            border-left: none;
            background-color: #f7f7f7;
            border-radius: inherit;
        }
        
        .theadInput:focus {
                background-color: #f7f7f7;
                border-color: #0a0a0a;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background: #f7f7f7 !important;
        }
        
    </style>


    <div class="main-container nw-cont">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Standard Settings</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Settings</li>
								</ol>
							</nav>
						</div>
						<!--<div class="col-md-6 col-sm-12 text-right">-->
						<!--	<div class="dropdown">-->
						<!--		<a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">-->
						<!--			Menu-->
						<!--		</a>-->
						<!--		<div class="dropdown-menu dropdown-menu-right">-->
						<!--			<a class="dropdown-item" href="introduction.html">Introduction</a>-->
						<!--		</div>-->
						<!--	</div>-->
						<!--</div>-->
					</div>
				</div>
                
                <div class="card-box mb-30">
                    <?php page_spinner(); if(MOD_SETTINGS!=1) { action_denied(); exit; } ?>
                    
                    <div class="pb-20">

                        <div class="accordion" id="accordionExample" style="padding: 25px;">
                            <?php
                            $qry = "SELECT a.*, b.order_code FROM sales_order_detalis a LEFT JOIN sales_order b ON a.sales_order_id=b.id WHERE a.id='" . $id . "' ORDER BY a.id ASC";
                            $query = mysqli_query($mysqli, $qry);
                            $x = 1;
                            $sql = mysqli_fetch_array($query);
                                ?>
                                <div class="card">
                                    <div class="card-header" id="heading<?= $x; ?>">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button"
                                                data-toggle="collapse" data-target="#collapse<?= $x; ?>"
                                                aria-expanded="true" aria-controls="collapse<?= $x; ?>">
                                                <i class="icon-copy dw dw-settings"></i>&nbsp;&nbsp;Settings</button>
                                        </h2>
                                    </div>

                                    <div id="collapse<?= $x; ?>" class="collapse show <?= ($x == 1) ? 'show' : ''; ?>"
                                        aria-labelledby="heading<?= $x; ?>" data-parent="#accordionExample">
                                        <div class="card-body" style="overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="tab">
                        								<div class="row clearfix" style="border: 1px solid gainsboro;">
                        									<div class="col-md-2 col-sm-12">
                        										<ul class="nav flex-column vtabs nav-tabs customtab" role="tablist">
                        											<li class="nav-item">
                        												<a class="nav-link active" data-toggle="tab" href="#config" role="tab" aria-selected="true">Configuration</a>
                        											</li>
                        											<li class="nav-item">
                        												<a class="nav-link" data-toggle="tab" href="#dept_head" role="tab" aria-selected="true">Department Head</a>
                        											</li>
                        											<li class="nav-item">
                        												<a class="nav-link" data-toggle="tab" href="#timeManagement" role="tab" aria-selected="true">Time Sheet Template</a>
                        											</li>
                        											<li class="nav-item">
                        												<a class="nav-link" data-toggle="tab" href="#teamTaskRange" role="tab" aria-selected="true">Team Task Notification</a>
                        											</li>
                        											<li class="nav-item">
                        												<a class="nav-link" data-toggle="tab" href="#financial_year" role="tab" aria-selected="true">Financial Year</a>
                        											</li>
                        											<li class="nav-item">
                        												<a class="nav-link" data-toggle="tab" href="#orbidx_config" role="tab" aria-selected="true">ORBIDX Device Config</a>
                        											</li>
                        											<li class="nav-item">
                        												<a class="nav-link" data-toggle="tab" href="#app_logo" role="tab" aria-selected="true">App Logo</a>
                        											</li>
                        										</ul>
                        									</div>
                        									<div class="col-md-10 col-sm-12">
                        										<div class="tab-content"  style="overflow-y: ;">
                        										    
                        											<div class="tab-pane fade show active" id="config" role="tabpanel">
                        												<div class="pd-20 row">
                        												    <?php if(SET_CONFIG!=1) { action_denied(); } else { ?>
                        												    
                        												    
                        												    <div class="col-sm-12 col-md-12 mb-30">
                                                        						<div class="card card-box">
                                                        							<div class="card-header">Common Config</div>
                                                        							<div class="card-body over-y-auto">
                                                        								<blockquote class="blockquote mb-0">
                                                        								    <table class="table table-bordered table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Ref</th>
                                                                                                        <th>Description</th>
                                                                                                        <th>Value</th>
                                                                                                        <th>Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <!--DELETE_PWD-->
                                                                                                    <?php
                                                                                                        $c_id = 'abc1210';
                                                                                                        $c_ref = 'DELETE_PWD';
                                                                                                        $c_des = 'Delete password';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show"><? //= get_setting_val($c_ref); ?>**********</span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <input type="text" name="" id="<?= $c_id; ?>_valueVal" class="form-control" placeholder="Enter Password" value="<?= get_setting_val($c_ref); ?>">
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                        									<!--<footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>-->
                                                        								</blockquote>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                        												    
                        												    <div class="col-sm-12 col-md-12 mb-30">
                                                        						<div class="card card-box">
                                                        							<div class="card-header">Production Config</div>
                                                        							<div class="card-body over-y-auto">
                                                        								<blockquote class="blockquote mb-0">
                                                        								    <table class="table table-bordered table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Ref</th>
                                                                                                        <th>Description</th>
                                                                                                        <th>Value</th>
                                                                                                        <th>Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <!--SWIBONPNG-->
                                                                                                    <?php
                                                                                                        $c_id = 'abc121';
                                                                                                        $c_ref = 'SWIBONPNG';
                                                                                                        $c_des = 'Sewing Input Based On Planning';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show"><?= get_setting_val($c_ref); ?></span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <select class="custom-select2 form-control" name="" id="<?= $c_id; ?>_valueVal">
                                                                                                                    <option value="NO">NO</option>
                                                                                                                    <option value="YES" <?= (get_setting_val($c_ref)=='YES') ? 'selected' : ''; ?>>YES</option>
                                                                                                                </select>
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    
                                                                                                    <!--SWIBONPNG-->
                                                                                                    <?php
                                                                                                        $c_id = 'abc122';
                                                                                                        $c_ref = 'STBPCSCAN';
                                                                                                        $c_des = 'Stock Based Pcs Scan';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show"><?= get_setting_val($c_ref); ?></span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <select class="custom-select2 form-control" name="" id="<?= $c_id; ?>_valueVal">
                                                                                                                    <option value="NO">NO</option>
                                                                                                                    <option value="YES" <?= (get_setting_val($c_ref)=='YES') ? 'selected' : ''; ?>>YES</option>
                                                                                                                </select>
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                        									<!--<footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>-->
                                                        								</blockquote>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                        												    
                        												    
                        												    <div class="col-sm-12 col-md-12 mb-30">
                                                        						<div class="card card-box over-y-auto">
                                                        							<div class="card-header">Fabric Config</div>
                                                        							<div class="card-body">
                                                        								<blockquote class="blockquote mb-0">
                                                        								    <table class="table table-bordered table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Ref</th>
                                                                                                        <th>Description</th>
                                                                                                        <th>Value</th>
                                                                                                        <th>Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    
                                                                                                    <!--FAB_SOLID-->
                                                                                                    <?php
                                                                                                        $c_id = 'abc123';
                                                                                                        $c_ref = 'FAB_SOLID';
                                                                                                        $c_des = 'Default Fabric <b>Solid</b> Type';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show"><?= select_dropdown_display('process', 'process_name', 'WHERE id IN ('. get_setting_val($c_ref) .')'); ?></span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <select class="custom-select2 form-control" name="" id="<?= $c_id; ?>_valueVal" multiple>
                                                                                                                    <?= select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', get_setting_val($c_ref), 'WHERE process_type="Fabric"', '1'); ?>
                                                                                                                </select>
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    
                                                                                                    <!--FAB_YANDD-->
                                                                                                    <?php
                                                                                                        $c_id = 'abc124';
                                                                                                        $c_ref = 'FAB_YANDD';
                                                                                                        $c_des = 'Default Fabric <b>Y/D</b> Type';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show"><?= select_dropdown_display('process', 'process_name', 'WHERE id IN ('. get_setting_val($c_ref) .')'); ?></span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <select class="custom-select2 form-control" name="" id="<?= $c_id; ?>_valueVal" multiple>
                                                                                                                    <?= select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', get_setting_val($c_ref), 'WHERE process_type="Fabric"', '1'); ?>
                                                                                                                </select>
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    
                                                                                                    <!--FAB_MELANGE-->
                                                                                                    <?php
                                                                                                        $c_id = 'abc125';
                                                                                                        $c_ref = 'FAB_MELANGE';
                                                                                                        $c_des = 'Default Fabric <b>Melange</b> Type';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show"><?= select_dropdown_display('process', 'process_name', 'WHERE id IN ('. get_setting_val($c_ref) .')'); ?></span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <select class="custom-select2 form-control" name="" id="<?= $c_id; ?>_valueVal" multiple>
                                                                                                                    <?= select_dropdown_multiple('process', array('id', 'process_name'), 'process_name ASC', get_setting_val($c_ref), 'WHERE process_type="Fabric"', '1'); ?>
                                                                                                                </select>
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    
                                                                                                    
                                                                                                </tbody>
                                                                                            </table>
                                                        								</blockquote>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                        												    
                        												    
                        												    <div class="col-sm-12 col-md-12 mb-30">
                                                        						<div class="card card-box over-y-auto">
                                                        							<div class="card-header">Accounts Config</div>
                                                        							<div class="card-body">
                                                        								<blockquote class="blockquote mb-0">
                                                        								    <table class="table table-bordered table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Ref</th>
                                                                                                        <th>Description</th>
                                                                                                        <th>Value</th>
                                                                                                        <th>Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    
                                                                                                    <!--FAB_SOLID-->
                                                                                                    <?php
                                                                                                        $c_id = 'abc126';
                                                                                                        $c_ref = 'COST_GEN_CHK';
                                                                                                        $c_des = 'Cost Generation Qty Check';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show">
                                                                                                                <?php
                                                                                                                    $ui = array(
                                                                                                                        'ORD_QTY' => 'Order Qty Vs Bill Qty',
                                                                                                                        // 'QC_QTY' => 'QC Qty Vs Bill Qty',
                                                                                                                        'PRO_QTY' => 'Production Qty Vs Bill Qty',
                                                                                                                    );
                                                                                                                    
                                                                                                                    print $ui[get_setting_val($c_ref)];
                                                                                                                ?>
                                                                                                            </span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <input type="radio" class="rdo" name="rdo" id="tid1" value="ORD_QTY" <?= (get_setting_val($c_ref) == 'ORD_QTY') ? 'checked' : ''; ?>> <label for="tid1">Order Qty Vs Bill Qty</label><br>
                                                                                                                <!--<input type="radio" class="rdo" name="rdo" id="tid2" value="QC_QTY" <?= (get_setting_val($c_ref) == 'QC_QTY') ? 'checked' : ''; ?>> <label for="tid2">QC Qty Vs Bill Qty</label><br>-->
                                                                                                                <input type="radio" class="rdo" name="rdo" id="tid3" value="PRO_QTY" <?= (get_setting_val($c_ref) == 'PRO_QTY') ? 'checked' : ''; ?>> <label for="tid3">Production Qty Vs Bill Qty</label>
                                                                                                                
                                                                                                                
                                                                                                                <input type="hidden" class="rdo_val" id="<?= $c_id; ?>_valueVal" value="<?= get_setting_val($c_ref); ?>">
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    
                                                                                                    <?php
                                                                                                        $c_id = 'abc127';
                                                                                                        $c_ref = 'COST_GEN_CUTTING';
                                                                                                        $c_des = 'Cutting Using';
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show">
                                                                                                                <?php
                                                                                                                    $ui = array(
                                                                                                                        'SCAN_OUT' => 'Scanning Output',
                                                                                                                        'CUTT_OUT' => 'Cutting Output',
                                                                                                                    );
                                                                                                                    
                                                                                                                    print $ui[get_setting_val($c_ref)];
                                                                                                                ?>
                                                                                                            </span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <input type="radio" class="rdo" name="rdo" id="tid4" value="SCAN_OUT" <?= (get_setting_val($c_ref) == 'SCAN_OUT') ? 'checked' : ''; ?>> <label for="tid4">Scanning Output</label><br>
                                                                                                                <input type="radio" class="rdo" name="rdo" id="tid5" value="CUTT_OUT" <?= (get_setting_val($c_ref) == 'CUTT_OUT') ? 'checked' : ''; ?>> <label for="tid5">Cutting Output</label>
                                                                                                                
                                                                                                                
                                                                                                                <input type="hidden" class="rdo_val" id="<?= $c_id; ?>_valueVal" value="<?= get_setting_val($c_ref); ?>">
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                        								</blockquote>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                                                                            <?php } ?>
                        												</div>
                        											</div>
                        											
                        											<div class="tab-pane fade" id="dept_head" role="tabpanel">
                        												<div class="pd-20">
                        												    <?php if(SET_DEPT_HEAD!=1) { action_denied(); } else { ?>
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Department</th>
                                                                                            <th>HOD</th>
                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <!--SWIBONPNG-->
                                                                                        <?php
                                                                                            $uip = mysqli_query($mysqli, "SELECT a.*,b.employee_name FROM department a LEFT JOIN employee_detail b ON a.hod=b.id ORDER BY a.department_name ASC");
                                                                                            while($res = mysqli_fetch_array($uip)) {
                                                                                                
                                                                                            $c_id = 'dpt'.$res['id'];
                                                                                            
                                                                                            $c_des = $res['department_name'];
                                                                                        ?>
                                                                                            <tr class="<?= $c_id; ?>">
                                                                                                <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                <td style="width: 30%;">
                                                                                                    <span class="ashow <?= $c_id; ?>_show"><?= $res['employee_name'] ? $res['employee_name'] : '-'; ?></span>
                                                                                                    
                                                                                                    <span class="ahide <?= $c_id; ?>_hide">
                                                                                                        <select class="custom-select2 form-control" name="" id="<?= $c_id; ?>_valueVal" style="width:100%">
                                                                                                            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $res['hod'], 'WHERE is_active="active"', '') ?>
                                                                                                        </select>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td style="font-size: 20px;width: 10%;">
                                                                                                    <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                    
                                                                                                    <a onclick="savedeptHod('<?= $c_id; ?>', '<?= $res['id']; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                    
                                                                                                    <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            <?php } ?>
                        												</div>
                        											</div>
                        											
                        											<div class="tab-pane fade" id="timeManagement" role="tabpanel">
                        												<div class="pd-20">
                        												    <?php if(SET_TIMETEMP!=1) { action_denied(); } else { ?>
                                                                                <div class="row">
                                                                                    <div class="col-md-12" style="text-align:right;padding: 20px;">
                                                                                        <?php if(SET_TIMETEMP_ADD==1) { ?>
                                                                                            <a class="btn btn-outline-primary" data-toggle="modal" data-target="#tempAdd-addModal"><i class="fa fa-plus"></i> New</a>
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-md-12">
                                                                                        <table class="data-table table hover nowrap dataTable no-footer dtr-inline">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th>Sl.No</th>
                                                                                                    <th>Template Name</th>
                                                                                                    <th>Total Order Day</th>
                                                                                                    <th>Brand</th>
                                                                                                    <th>Action</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php
                                                                                                $x=1;
                                                                                                    $iop = mysqli_query($mysqli, "SELECT * FROM time_management_template ORDER BY id DESC");
                                                                                                    while($result = mysqli_fetch_array($iop)) {
                                                                                                        
                                                                                                        $bnd = mysqli_query($mysqli, "SELECT brand_name FROM brand WHERE id IN (". $result['brand'] .")");
                                                                                                        while($brnd = mysqli_fetch_array($bnd)) {
                                                                                                            $bndd[$x][] = $brnd['brand_name'];
                                                                                                        }
                                                                                                ?>
                                                                                                    <tr>
                                                                                                        <td><?= $x; ?></td>
                                                                                                        <td><?= $result['temp_name']; ?></td>
                                                                                                        <td><?= $result['total_day']; ?></td>
                                                                                                        <td><?= implode('<br>', $bndd[$x]); ?></td>
                                                                                                        <td>
                                                                                                            <!--<a class="border border-secondary rounded text-secondary" onclick="viewTimeTemplate(<?= $result['id']; ?>, 'view')"><i class="fa fa-eye"></i></a>-->
                                                                                                            <?php if(SET_TIMETEMP_EDIT==1) { ?>
                                                                                                                <a class="border border-secondary rounded text-secondary" onclick="editTimeTemplate(<?= $result['id']; ?>, 'edit')"><i class="icon-copy dw dw-edit-1"></i></a>
                                                                                                            <?php } if(SET_TIMETEMP_DELETE==1) { ?>
                                                                                                                <a class="border border-secondary rounded text-secondary" onclick="delete_data(<?= $result['id']; ?>, 'time_management_template')"><i class="fa fa-trash"></i></a>
                                                                                                            <?php } ?>
                                                                                                            
                                                                                                            <div class="modal fade bs-example-modal-lg" id="tempEditModal<?= $result['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                                                        						<div class="modal-dialog modal-lg">
                                                                                        							<div class="modal-content">
                                                                                        								<div class="modal-header">
                                                                                        									<h4 class="modal-title" id="myLargeModalLabel">Edit Time Sheet Template</h4>
                                                                                        									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                        								</div>
                                                                                        								<div class="modal-body">
                                                                                    								        <form method="POST" id="templateFormUpdate<?= $result['id']; ?>">
                                                                                    								            <div class="row">
                                                                                            									    <div class="col-md-3">
                                                                                                                                        <label class="fieldrequired">Template Name</label>
                                                                                                                                        <input type="text" name="temp_name" id="temp_name" placeholder="Template Name" class="form-control" value="<?= $result['temp_name'] ?>" required>
                                                                                                                                        <input type="hidden" name="tempId" id="tempId" value="<?= $result['id'] ?>" required>
                                                                                                                                    </div>
                                                                                            									    <div class="col-md-3">
                                                                                                                                        <label class="fieldrequired">Total Days</label>
                                                                                                                                        <input type="number" name="total_day" id="total_day" placeholder="Total Day" class="form-control" value="<?= $result['total_day'] ?>" required>
                                                                                                                                    </div>
                                                                                            									    <div class="col-md-6">
                                                                                                                                        <label class="fieldrequired">Brand</label><br>
                                                                                                                                        <select class="form-control custom-select2" name="brand[]" id="brand<?= $result['id']; ?>" style="width:100%" required multiple>
                                                                                                                                            <?= select_dropdown_multiple('brand', array('id', 'brand_name'), 'brand_name ASC', $result['brand'], '', '1'); ?>
                                                                                                                                        </select>
                                                                                                                                    </div>
                                                                                            									</div>
                                                                                            									
                                                                                            									<br>
                                                                                            									
                                                                                        								        <div class="over-y-auto">
                                                                                            										<table class="table table-bordered">
                                                                                                                                        <thead style="background-color: #f7f7f7;">
                                                                                                                                            <tr>
                                                                                                                                                <th>Activity</th>
                                                                                                                                                <th class="fieldrequired">Day Calculation</th>
                                                                                                                                                <th class="fieldrequired">Day Start</th>
                                                                                                                                                <th class="fieldrequired">Day End</th>
                                                                                                                                                <th>Daily Work Time</th>
                                                                                                                                                <th>Closing Day Time</th>
                                                                                                                                                <th>Responsible (A)</th>
                                                                                                                                                <th>Responsible (B)</th>
                                                                                                                                                <th>Responsible (C)</th>
                                                                                                                                                <th>Responsible (D)</th>
                                                                                                                                            </tr>
                                                                                                                                        </thead>
                                                                                                                                        <tbody>
                                                                                                            
                                                                                                                                            <tr>
                                                                                                                                                <th colspan="8">Buyer Approvals</th>
                                                                                                                                            </tr>
                                                                                                                                            
                                                                                                                                            <?php
                                                                                                                                            $fbPcs = mysqli_query($mysqli, "SELECT * FROM time_management_template_det WHERE table_name = 'mas_approval' AND temp_id = '". $result['id'] ."'");    
                                                                                                                                            while($rowFab = mysqli_fetch_array($fbPcs)) {
                                                                                                                                                $act = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM ". $rowFab['table_name'] ." WHERE id = '". $rowFab['activity'] ."'"));
                                                                                                                                            $p=$rowFab['id'];
                                                                                                                                            ?>
                                                                                                                                                <tr id="trId<?= $p.'edit'; ?>">
                                                                                                                                                    <td>
                                                                                                                                                        <input type="hidden" value="<?= $rowFab['id']; ?>" name="insId[]" id="">
                                                                                                                                                        <input type="hidden" value="<?= $p.'edit'; ?>" name="nameId[]" id="">
                                                                                                                                                        <?= $act['name']; ?></td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2" name="calculation_type_<?= $p.'edit'; ?>" id="FP_calculation_type<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <option value="asc">Order Date</option>
                                                                                                                                                            <option value="desc" <?= ($rowFab['calculation_type'] == 'desc') ? 'selected' : ''; ?>>Delivery Date</option>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td><input type="number" class="form-control e_start<?= $result['id']; ?>" name="start_day_<?= $p.'edit'; ?>" id="" placeholder="Day Start" value="<?= $rowFab['start_day']; ?>"></td>

                                                                                                                                                    <td><input type="number" class="form-control e_end<?= $result['id']; ?>" name="end_day_<?= $p.'edit'; ?>" id="" placeholder="Day End" value="<?= $rowFab['end_day']; ?>"></td>
                                                                                                                                                    
                                                                                                                                                    <td><input type="number" class="form-control e_daily_time<?= $result['id']; ?>" name="daily_time_<?= $p.'edit'; ?>" id="" placeholder="Daily Work Time" value="<?= ($rowFab['daily_time']/60); ?>"></td>
                                                                                                                                                    
                                                                                                                                                    <td><input type="number" class="form-control e_endday_time<?= $result['id']; ?>" name="endday_time_<?= $p.'edit'; ?>" id="" placeholder="Daily Work Time" value="<?= ($rowFab['endday_time']/60); ?>"></td>

                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rA<?= $result['id']; ?>" name="resp_A_<?= $p.'edit'; ?>[]" id="FP_res_dept<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_A'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rB<?= $result['id']; ?>" name="resp_B_<?= $p.'edit'; ?>[]" id="FP_resp_B<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_B'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rC<?= $result['id']; ?>" name="resp_C_<?= $p.'edit'; ?>[]" id="FP_resp_C<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_C'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rD<?= $result['id']; ?>" name="resp_D_<?= $p.'edit'; ?>[]" id="FP_resp_D<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_D'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <!--<td>-->
                                                                                                                                                    <!--    <a class="border border-secondary rounded text-secondary" onclick="removeTr(<?= $p.'edit'; ?>)"><i class="fa fa-trash"></i></a>-->
                                                                                                                                                    <!--</td>-->
                                                                                                                                                </tr>
                                                                                                                                            <?php } ?>
                                                                                                                                            
                                                                                                                                            <tr>
                                                                                                                                                <th colspan="8">Merchand General</th>
                                                                                                                                            </tr>
                                                                                                                                            
                                                                                                                                            <?php
                                                                                                                                            $arr = array(
                                                                                                                                                'so_approval' => 'SO Approval',
                                                                                                                                                'fab_program' => 'Fabric Program',
                                                                                                                                                'access_program' => 'Accessories Program',
                                                                                                                                                'budget' => 'Budget',
                                                                                                                                                'budget_approval' => 'Budget Approval',
                                                                                                                                            );
                                                                                                                                            
                                                                                                                                            $fbPcs = mysqli_query($mysqli, "SELECT * FROM time_management_template_det WHERE table_name = 'manual' AND temp_id = '". $result['id'] ."'");    
                                                                                                                                            while($rowFab = mysqli_fetch_array($fbPcs)) {
                                                                                                                                                
                                                                                                                                            $p=$rowFab['id'];
                                                                                                                                            ?>
                                                                                                                                                <tr>
                                                                                                                                                    <td>
                                                                                                                                                        <input type="hidden" value="<?= $rowFab['id']; ?>" name="insId[]" id="">
                                                                                                                                                        <input type="hidden" value="<?= $p.'edit'; ?>" name="nameId[]" id="">
                                                                                                                                                        <?= $arr[$rowFab['activity']]; ?></td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2" name="calculation_type_<?= $p.'edit'; ?>" id="calculation_type<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <option value="asc">Order Date</option>
                                                                                                                                                            <option value="desc" <?= ($rowFab['calculation_type'] == 'desc') ? 'selected' : ''; ?>>Delivery Date</option>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td><input type="number" class="form-control e_start<?= $result['id']; ?>" name="start_day_<?= $p.'edit'; ?>" id="" placeholder="Day Start" value="<?= $rowFab['start_day']; ?>"></td>

                                                                                                                                                    <td><input type="number" class="form-control e_end<?= $result['id']; ?>" name="end_day_<?= $p.'edit'; ?>" id="" placeholder="Day End" value="<?= $rowFab['end_day']; ?>"></td>
                                                                                                                                                    
                                                                                                                                                    <td><input type="number" class="form-control e_daily_time<?= $result['id']; ?>" name="daily_time_<?= $p.'edit'; ?>" id="" placeholder="Daily Work Time" value="<?= ($rowFab['daily_time']/60); ?>"></td>
                                                                                                                                                    
                                                                                                                                                    <td><input type="number" class="form-control e_endday_time<?= $result['id']; ?>" name="endday_time_<?= $p.'edit'; ?>" id="" placeholder="Daily Work Time" value="<?= ($rowFab['endday_time']/60); ?>"></td>

                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rA<?= $result['id']; ?>" name="resp_A_<?= $p.'edit'; ?>[]" id="res_dept<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_A'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rB<?= $result['id']; ?>" name="resp_B_<?= $p.'edit'; ?>[]" id="resp_B<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_B'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rC<?= $result['id']; ?>" name="resp_C_<?= $p.'edit'; ?>[]" id="resp_C<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_C'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <td>
                                                                                                                                                        <select class="form-control custom-select2 rD<?= $result['id']; ?>" name="resp_D_<?= $p.'edit'; ?>[]" id="resp_D<?= $p.'edit'; ?>" style="width:100%" required>
                                                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', $rowFab['resp_D'], ' WHERE is_active="active"', ''); ?>
                                                                                                                                                        </select>
                                                                                                                                                    </td>
                                                                                                                                                    <!--<td>-</td>-->
                                                                                                                                                </tr>
                                                                                                                                            <?php } ?>
                                                                                                                                        </tbody>
                                                                                                                                    </table>
                                                                                                                                </div>
                                                                                                                            </form>
                                                                                        								</div>
                                                                                        								<div class="modal-footer">
                                                                                        									<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                                                        									<button type="button" class="btn btn-outline-primary" onclick="UpdateTimeTemplate('<?= $result['id']; ?>', '<?= $p.'edit'; ?>')">Update</button>
                                                                                        								</div>
                                                                                        							</div>
                                                                                        						</div>
                                                                                        					</div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                <?php $x++; } ?>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                            
                                                                            <div class="modal fade bs-example-modal-lg" id="tempAdd-addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                        						<div class="modal-dialog modal-lg">
                                                        							<div class="modal-content">
                                                        								<div class="modal-header">
                                                        									<h4 class="modal-title" id="myLargeModalLabel">New Time Sheet Template</h4>
                                                        									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        								</div>
                                                        								<div class="modal-body">
                                                        								    <form method="POST" id="templateForm">
                                                            									<div class="row">
                                                            									    <div class="col-md-4">
                                                                                                        <label class="fieldrequired">Template Name</label>
                                                                                                        <input type="text" name="temp_name" id="temp_name_add" placeholder="Template Name" class="form-control" required>
                                                                                                    </div>
                                                            									    <div class="col-md-4">
                                                                                                        <label class="fieldrequired">Total Days</label>
                                                                                                        <input type="number" name="total_day" id="total_day_add" placeholder="Total Day" class="form-control" required>
                                                                                                    </div>
                                                            									    <div class="col-md-4">
                                                                                                        <label class="fieldrequired">Brand</label>
                                                                                                        <select class="form-control custom-select2" name="brand[]" id="brand" style="width:100%" required multiple>
                                                                                                            <?= select_dropdown('brand', array('id', 'brand_name'), 'brand_name ASC', '', '', '1'); ?>
                                                                                                        </select>
                                                                                                    </div>
                                                            									</div>
                                                            									
                                                            									<br>
                                                            									
                                                            									<div class="over-y-auto">
                                                            										<table class="table table-bordered">
                                                                                                        <thead style="background-color: #f7f7f7;">
                                                                                                            <tr>
                                                                                                                <th>Activity</th>
                                                                                                                <th class="fieldrequired">Day Calculation</th>
                                                                                                                <th style="width:5%;">
                                                                                                                    <span class="fieldrequired">Day Start</span> <input type="number" class="form-control theadInput" id="mainstartDt" onkeyup="changeAllInp('startDt')">
                                                                                                                </th>
                                                                                                                <th style="width:5%;">
                                                                                                                    <span class="fieldrequired">Day End</span> <input type="number" class="form-control theadInput" id="mainendDt" onkeyup="changeAllInp('endDt')">
                                                                                                                </th>
                                                                                                                <th>
                                                                                                                    <span class="fieldrequired">Daily Work Time</span> <input type="number" class="form-control theadInput" id="maindaily_time" onkeyup="changeAllInp('daily_time')">
                                                                                                                </th>
                                                                                                                <th>
                                                                                                                    <span class="fieldrequired">Closing Day Time</span> <input type="number" class="form-control theadInput" id="mainendday_time" onkeyup="changeAllInp('endday_time')">
                                                                                                                </th>
                                                                                                                <th>Responsible (A)</th>
                                                                                                                <th>Responsible (B)</th>
                                                                                                                <th>Responsible (C)</th>
                                                                                                                <th>Responsible (D)</th>
                                                                                                                <th>Action</th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                            
                                                                                                            <tr>
                                                                                                                <th colspan="8">Buyer Approvals</th>
                                                                                                            </tr>
                                                                                                            
                                                                                                            <?php
                                                                                                            $fbPcs = mysqli_query($mysqli, "SELECT * FROM mas_approval");    
                                                                                                            $p=1;
                                                                                                            while($rowFab = mysqli_fetch_array($fbPcs)) {
                                                                                                            ?>
                                                                                                                <tr id="trId<?= $p; ?>">
                                                                                                                    <td>
                                                                                                                        <input type="hidden" value="mas_approval" name="table_name[]" id="">
                                                                                                                        <input type="hidden" value="<?= $rowFab['id']; ?>" name="activity[]" id="">
                                                                                                                        <input type="hidden" value="<?= $p; ?>" name="nameId[]" id="">
                                                                                                                        <?= $rowFab['name']; ?></td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2" name="calculation_type_<?= $p; ?>" id="FP_calculation_type<?= $rowFab['id'].'APP'; ?>" style="width:100%" required>
                                                                                                                            <option value="asc">Order Date</option>
                                                                                                                            <option value="desc">Delivery Date</option>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td><input type="number" class="form-control startDt" name="start_day_<?= $p; ?>" id="" placeholder="Day Start"></td>
                                                                                                                    <td><input type="number" class="form-control endDt" name="end_day_<?= $p; ?>" id="" placeholder="Day End"></td>
                                                                                                                    <td><input type="number" class="form-control daily_time" name="daily_time_<?= $p; ?>" id="" placeholder="Daily Work Time"></td>
                                                                                                                    <td><input type="number" class="form-control endday_time" name="endday_time_<?= $p; ?>" id="" placeholder="Closing Day Time"></td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respA" name="resp_A_<?= $p; ?>[]" id="FP_res_dept<?= $rowFab['id'].'APP'; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respB" name="resp_B_<?= $p; ?>[]" id="FP_resp_B<?= $rowFab['id'].'APP'; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respC" name="resp_C_<?= $p; ?>[]" id="FP_resp_C<?= $rowFab['id'].'APP'; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respD" name="resp_D_<?= $p; ?>[]" id="FP_resp_D<?= $rowFab['id'].'APP'; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <a class="border border-secondary rounded text-secondary" onclick="removeTr(<?= $p; ?>)"><i class="fa fa-trash"></i></a>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            <?php $p++; } ?>
                                                                                                            
                                                                                                            <tr>
                                                                                                                <th colspan="8">Merchand General</th>
                                                                                                            </tr>
                                                                                                            
                                                                                                            <?php
                                                                                                            $arr = array(
                                                                                                                'so_approval' => 'SO Approval',
                                                                                                                'fab_program' => 'Fabric Program',
                                                                                                                'access_program' => 'Accessories Program',
                                                                                                                'budget' => 'Budget',
                                                                                                                'budget_approval' => 'Budget Approval',
                                                                                                            );
                                                                                                            
                                                                                                            $p=50;
                                                                                                            foreach($arr as $key => $val) {
                                                                                                            ?>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <input type="hidden" value="manual" name="table_name[]" id="">
                                                                                                                        <input type="hidden" value="<?= $key; ?>" name="activity[]" id="">
                                                                                                                        <input type="hidden" value="<?= $p; ?>" name="nameId[]" id="">
                                                                                                                        <?= $val; ?></td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2" name="calculation_type_<?= $p; ?>" id="calculation_type<?= $p; ?>" style="width:100%" required>
                                                                                                                            <option value="asc">Order Date</option>
                                                                                                                            <option value="desc">Delivery Date</option>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td><input type="number" class="form-control startDt" name="start_day_<?= $p; ?>" id="" placeholder="Day Start"></td>
                                                                                                                    <td><input type="number" class="form-control endDt" name="end_day_<?= $p; ?>" id="" placeholder="Day End"></td>
                                                                                                                    <td><input type="number" class="form-control daily_time" name="daily_time_<?= $p; ?>" id="" placeholder="Daily Work Time"></td>
                                                                                                                    <td><input type="number" class="form-control endday_time" name="endday_time_<?= $p; ?>" id="" placeholder="Closing Day Time"></td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respDept" name="resp_A_<?= $p; ?>[]" id="res_dept<?= $p; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respDept" name="resp_B_<?= $p; ?>[]" id="resp_B<?= $p; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respDept" name="resp_C_<?= $p; ?>[]" id="resp_C<?= $p; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <select class="form-control custom-select2 respDept" name="resp_D_<?= $p; ?>[]" id="resp_D<?= $p; ?>" style="width:100%" required>
                                                                                                                            <?= select_dropdown_multiple('employee_detail', array('id', 'employee_name'), 'employee_name ASC', 102, ' WHERE is_active="active"', ''); ?>
                                                                                                                        </select>
                                                                                                                    </td>
                                                                                                                    <td>-</td>
                                                                                                                </tr>
                                                                                                            <?php $p++; } ?>
                                                                                                            
                                                                                                                <!--<tr>-->
                                                                                                                <!--    <th colspan="8">Fabric Process</th>-->
                                                                                                                <!--</tr>-->
                                                                                                                
                                                                                                            <?php
                                                                                                            $fbPcs = mysqli_query($mysqli, "SELECT * FROM process WHERE process_type = 'Fabric'");    
                                                                                                            $p=1;
                                                                                                            while($rowFab = mysqli_fetch_array($fbPcs)) {
                                                                                                            ?>
                                                                                                                <!--<tr>-->
                                                                                                                <!--    <td><input type="hidden" value="<?= $key; ?>" name="activity[]" id=""> <?= $rowFab['process_name']; ?></td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2" name="calculation_type[]" id="FP_calculation_type<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <option value="asc">Order Date</option>-->
                                                                                                                <!--            <option value="desc">Delivery Date</option>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td><input type="number" class="form-control startDt" name="start_day[]" id="" placeholder="Day Start"></td>-->
                                                                                                                <!--    <td><input type="number" class="form-control endDt" name="end_day[]" id="" placeholder="Day End"></td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="res_dept[]" id="FP_res_dept<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_B[]" id="FP_resp_B<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_C[]" id="FP_resp_C<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_D[]" id="FP_resp_D<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--</tr>-->
                                                                                                            <?php $p++; } ?>
                                                                                                            
                                                                                                                <!--<tr>-->
                                                                                                                <!--    <th colspan="8">Accessories Type</th>-->
                                                                                                                <!--</tr>-->
                                                                                                                
                                                                                                            <?php
                                                                                                            $AccPcs = mysqli_query($mysqli, "SELECT * FROM mas_accessories_type ");    
                                                                                                            $p=1;
                                                                                                            while($rowAcc = mysqli_fetch_array($AccPcs)) {
                                                                                                            ?>
                                                                                                                <!--<tr>-->
                                                                                                                <!--    <td><input type="hidden" value="<?= $key; ?>" name="activity[]" id=""> <?= $rowAcc['type_name']; ?></td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2" name="calculation_type[]" id="Acc_calculation_type<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <option value="asc">Order Date</option>-->
                                                                                                                <!--            <option value="desc">Delivery Date</option>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td><input type="number" class="form-control startDt" name="start_day[]" id="" placeholder="Day Start"></td>-->
                                                                                                                <!--    <td><input type="number" class="form-control endDt" name="end_day[]" id="" placeholder="Day End"></td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="res_dept[]" id="Acc_res_dept<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_B[]" id="Acc_resp_B<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_C[]" id="Acc_resp_C<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_D[]" id="Acc_resp_D<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--</tr>-->
                                                                                                            <?php $p++; } ?>
                                                                                                            
                                                                                                                <!--<tr>-->
                                                                                                                <!--    <th colspan="8">Production Process</th>-->
                                                                                                                <!--</tr>-->
                                                                                                                
                                                                                                            <?php
                                                                                                            $fbPcs = mysqli_query($mysqli, "SELECT * FROM process WHERE process_type = 'Production'");    
                                                                                                            $p=1;
                                                                                                            while($rowFab = mysqli_fetch_array($fbPcs)) {
                                                                                                            ?>
                                                                                                                <!--<tr>-->
                                                                                                                <!--    <td><input type="hidden" value="<?= $key; ?>" name="activity[]" id=""> <?= $rowFab['process_name']; ?></td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2" name="calculation_type[]" id="PRO_calculation_type<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <option value="asc">Order Date</option>-->
                                                                                                                <!--            <option value="desc">Delivery Date</option>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td><input type="number" class="form-control startDt" name="start_day[]" id="" placeholder="Day Start"></td>-->
                                                                                                                <!--    <td><input type="number" class="form-control endDt" name="end_day[]" id="" placeholder="Day End"></td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="res_dept[]" id="PRO_res_dept<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $asd['department'], '', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_B[]" id="PRO_resp_B<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_C[]" id="PRO_resp_C<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--    <td>-->
                                                                                                                <!--        <select class="form-control custom-select2 respDept" name="resp_D[]" id="PRO_resp_D<?= $p; ?>" style="width:100%" required>-->
                                                                                                                <!--            <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>-->
                                                                                                                <!--        </select>-->
                                                                                                                <!--    </td>-->
                                                                                                                <!--</tr>-->
                                                                                                            <?php $p++; } ?>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </form>
                                                        								</div>
                                                        								<div class="modal-footer">
                                                        									<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        									<button type="button" class="btn btn-outline-primary" onclick="saveTimeTemplate()">Save</button>
                                                        								</div>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                                                        					
                                                        					<div class="modal fade bs-example-modal-lg" id="tempViewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                        						<div class="modal-dialog modal-lg">
                                                        							<div class="modal-content">
                                                        								<div class="modal-header">
                                                        									<h4 class="modal-title" id="myLargeModalLabel">Time Sheet Template</h4>
                                                        									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        								</div>
                                                        								<div class="modal-body">
                                                    								        <form method="POST" id="">
                                                        								        <div class="over-y-auto">
                                                            										<table class="table table-bordered">
                                                                                                        <thead style="background-color: #f7f7f7;">
                                                                                                            <tr>
                                                                                                                <th>Activity</th>
                                                                                                                <th class="fieldrequired">Day Calculation</th>
                                                                                                                <th class="fieldrequired">Day Start</th>
                                                                                                                <th class="fieldrequired">Day End</th>
                                                                                                                <th>Responsible</th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody id="viewTimeBody">
                                                                                                            <tr><td colspan="5" style="text-align:center;">Loading..  <i class="fa fa-spinner"></i></td></tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </form>
                                                        								</div>
                                                        								<div class="modal-footer">
                                                        									<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        								</div>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                                                        					
                        												</div>
                        											</div>
                        											
                        											<div class="tab-pane fade" id="teamTaskRange" role="tabpanel">
                        												<div class="pd-20">
                        												    <?php if(SET_TEAMTASK!=1) { action_denied(); } else { ?>
                                                                                
                                                                                <div class="pd-20 card-box mb-30">
                                                            						<h4 class="h4 pb-10">Follower (C) Notification Percentage</h4>
                                                            						<div class="row">
                                                            							<div class="col-md-10 mb-30 mb-md-0">
                                                            								<input id="range_01" value="<?= get_setting_val('TEAM_TASK_FOLL_C'); ?>"/>
                                                            								<input type="hidden" id="range_01_temp" value="<?= get_setting_val('TEAM_TASK_FOLL_C'); ?>"/>
                                                            								<input type="hidden" id="range_01_valueVal" value="<?= get_setting_val('TEAM_TASK_FOLL_C'); ?>"/>
                                                            								<input type="hidden" id="range_01_description" value="Team Task Follower C"/>
                                                            								<input type="hidden" id="range_01_reff" value="TEAM_TASK_FOLL_C"/>
                                                            							</div>
                                                            							
                                                            							<div class="col-md-2 mb-30 mb-md-0">
                                                            						        <a class="btn btn-outline-primary range_01Btn d-none" onclick="saveEditvalue('range_01')">Update Percentage</a>
                                                        						        </div>
                                                            						</div>
                                                            					</div>
                                                                                
                                                                                <div class="pd-20 card-box mb-30">
                                                            						<h4 class="h4 pb-10">Follower (D) Notification Percentage</h4>
                                                            						<div class="row">
                                                            							<div class="col-md-10 mb-30 mb-md-0">
                                                            								<input id="range_01_02" value="<?= get_setting_val('TEAM_TASK_FOLL_D'); ?>"/>
                                                            								<input type="hidden" id="range_01_02_temp" value="<?= get_setting_val('TEAM_TASK_FOLL_D'); ?>"/>
                                                            								<input type="hidden" id="range_01_02_valueVal" value="<?= get_setting_val('TEAM_TASK_FOLL_D'); ?>"/>
                                                            								<input type="hidden" id="range_01_02_description" value="Team Task Follower D"/>
                                                            								<input type="hidden" id="range_01_02_reff" value="TEAM_TASK_FOLL_D"/>
                                                            							</div>
                                                            							
                                                            							<div class="col-md-2 mb-30 mb-md-0">
                                                            						        <a class="btn btn-outline-primary range_01_02Btn d-none" onclick="saveEditvalue('range_01_02')">Update Percentage</a>
                                                        						        </div>
                                                            						</div>
                                                            					</div>
                                                            					
                                                                            <?php } ?>
                        												</div>
                        											</div>
                        										    
                        											<div class="tab-pane fade" id="financial_year" role="tabpanel">
                        												<div class="pd-20 row">
                        												    <?php if(SET_FINANCEYEAR!=1) { action_denied(); } else { ?>
                        												    
                        												    
                        												    <div class="col-sm-12 col-md-12 mb-30">
                                                        						<div class="card card-box">
                                                        							<div class="card-header">Current Financial Year</div>
                                                        							<div class="card-body over-y-auto">
                                                        								<blockquote class="blockquote mb-0">
                                                        								    <table class="table table-bordered table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Ref</th>
                                                                                                        <th>Description</th>
                                                                                                        <th>Value</th>
                                                                                                        <th>Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    
                                                                                                    <?php
                                                                                                        $c_id = 'abc127';
                                                                                                        $c_ref = 'FIN_YEAR';
                                                                                                        $c_des = "Based on these settings, the sales order BO Number will follow";
                                                                                                        
                                                                                                        $years = array('2023', '2024', '2025', '2026', '2027', '2028', '2029', '2030');
                                                                                                    ?>
                                                                                                    <tr class="<?= $c_id; ?>">
                                                                                                        <td style="width: 10%;"> <?= $c_ref; ?> <input type="hidden" value="<?= $c_ref; ?>" id="<?= $c_id; ?>_reff"> </td>
                                                                                                        <td style="width: 50%;"> <?= $c_des; ?> <input type="hidden" value="<?= $c_des; ?>" id="<?= $c_id; ?>_description"> </td>
                                                                                                        <td style="width: 30%;">
                                                                                                            <span class="ashow <?= $c_id; ?>_show"><?= get_setting_val($c_ref); ?></span>
                                                                                                            
                                                                                                            <span class="ahide <?= $c_id; ?>_hide">
                                                                                                                <select class="custom-select2 form-control" name="" id="<?= $c_id; ?>_valueVal" style="width:100%">
                                                                                                                    <?php foreach($years as $year) { ?>
                                                                                                                        <option value="<?= $year; ?>" <?= (get_setting_val($c_ref)==$year) ? 'selected' : ''; ?>><?= $year; ?></option>
                                                                                                                    <?php } ?>
                                                                                                                </select>
                                                                                                            </span>
                                                                                                        </td>
                                                                                                        <td style="font-size: 20px;width: 10%;">
                                                                                                            <a onclick="showEditvalue('<?= $c_id; ?>')" class="ashow <?= $c_id; ?>_show"> <i class="icon-copy dw dw-edit-1"></i> </a>
                                                                                                            
                                                                                                            <a onclick="saveEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-android-checkbox-outline"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                                            
                                                                                                            <a onclick="hideEditvalue('<?= $c_id; ?>')" class="ahide <?= $c_id; ?>_hide"> <i class="icon-copy ion-close-round"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                        									<!--<footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>-->
                                                        								</blockquote>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                                                                            <?php } ?>
                        												</div>
                        											</div>
                        										    
                        											<div class="tab-pane fade" id="orbidx_config" role="tabpanel">
                        												<div class="pd-20">
                        												    <?php if(SET_ORBIDX_CONFIG!=1) { action_denied(); } else { ?>
                        												    
                        												    <div class="row">
                                                                                <div class="col-md-12" style="text-align:right;padding: 20px;">
                                                                                    <?php if(ORBIDX_CONFIG_ADD==1) { ?>
                                                                                        <a class="btn btn-outline-primary" data-toggle="modal" data-target="#deviceAdd-addModal"><i class="fa fa-plus"></i> New</a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                                
                                                                                <div class="col-md-12">
                                                                                    <table class="data-table table hover nowrap dataTable no-footer dtr-inline">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>Sl.No</th>
                                                                                                <th>Device Name</th>
                                                                                                <th>Department</th>
                                                                                                <th>Process</th>
                                                                                                <th>Line</th>
                                                                                                <th>Scanning Type</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                            <?php
                                                                                                    
                                                                                                $scan_type = [
                                                                                                    // 'all' => 'Both Bundle & Piece',
                                                                                                    'piece' => 'Piece',
                                                                                                    'bundle' => 'Bundle',
                                                                                                ];
                                                                                            $dd=1;
                                                                                                $qr = mysqli_query($mysqli, "SELECT * FROM orbidx_device ORDER BY id DESC");
                                                                                                while($row = mysqli_fetch_array($qr)) {
                                                                                            ?>
                                                                                                <tr class="td_edDl">
                                                                                                    <td><?= $dd; ?></td>
                                                                                                    <td><?= $row['device']; ?></td>
                                                                                                    <td>
                                                                                                        <span class="dev_name<?= $row['id']; ?>"><?= department_name($row['department']); ?></span>
                                                                                                        
                                                                                                        <span class="d-none dev_select<?= $row['id']; ?>">
                                                                                                            <select class="form-control custom-select2" name="" id="ed_dev_department<?= $row['id']; ?>" style="width:100%">
                                                                                                                <?= select_dropdown('department', array('id', 'department_name'), 'department_name ASC', $row['department'], '', ''); ?>
                                                                                                            </select>
                                                                                                        </span>
                                                                                                        
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <span class="dev_name<?= $row['id']; ?>"><?= process_name($row['process']); ?></span>
                                                                                                        
                                                                                                        <span class="d-none dev_select<?= $row['id']; ?>">
                                                                                                            <select class="form-control custom-select2" name="" id="ed_dev_process<?= $row['id']; ?>" style="width:100%">
                                                                                                                <?= select_dropdown('process', array('id', 'process_name'), 'process_name ASC', $row['process'], ' WHERE process_type = "Production"', ''); ?>
                                                                                                            </select>
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <span class="dev_name<?= $row['id']; ?>"><?= $row['line'] ? line_name($row['line']) : '-'; ?></span>
                                                                                                        
                                                                                                        <span class="d-none dev_select<?= $row['id']; ?>">
                                                                                                            <select class="form-control custom-select2" name="" id="ed_line<?= $row['id']; ?>" style="width:100%">
                                                                                                                <?= select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', $row['line'], '', ''); ?>
                                                                                                            </select>
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td class="d-flex">
                                                                                                        <span class="dev_name<?= $row['id']; ?>"><?= $scan_type[$row['scan_type']] ?></span>
                                                                                                        
                                                                                                        <span class="d-none dev_select<?= $row['id']; ?>">
                                                                                                            <select class="form-control custom-select2" name="" id="ed_scan_type<?= $row['id']; ?>" style="width:100%">
                                                                                                                <?php
                                                                                                                    foreach($scan_type as $key => $value) {
                                                                                                                        $sel = ($row['scan_type'] == $key) ? 'selected' : '';
                                                                                                                        print '<option value="'. $key .'" '. $sel .'>'. $value .'</option>';
                                                                                                                    }
                                                                                                                ?>
                                                                                                            </select>
                                                                                                        </span>
                                                                                                            &nbsp;
                                                                                                            &nbsp;
                                                                                                        <div class="d-flex">
                                                                                                            <?php if(ORBIDX_CONFIG_EDIT==1) { ?>
                                                                                                                <a class="border border-success rounded text-success text-center save_row dev_select<?= $row['id']; ?> d-none" data-id="<?= $row['id']; ?>" title="Save"><i class="icon-copy fa fa-check"></i></i></a>&nbsp;
                                                                                                                
                                                                                                                <a class="border border-danger rounded text-danger text-center cancel_row dev_select<?= $row['id']; ?> d-none" data-id="<?= $row['id']; ?>" title="Cancel"><i class="icon-copy fa fa-close"></i></a>&nbsp;
                                                                                                                
                                                                                                                <a class="border border-info rounded text-info text-center hov_show editRw dev_btn<?= $row['id']; ?>" data-id="<?= $row['id']; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                            <?php } if(ORBIDX_CONFIG_DELETE==1) { ?>
                                                                                                                &nbsp;<a class="border border-danger rounded text-danger text-center hov_show dev_btn<?= $row['id']; ?>" onclick="delete_data(<?= $row['id']; ?>, 'orbidx_device')" title="Delete"><i class="fa fa-trash"></i></a>
                                                                                                            <?php } ?>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            <?php $dd++; } ?>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="modal fade bs-example-modal-lg" id="deviceAdd-addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                        						<div class="modal-dialog" style="max-width:1200px !important;">
                                                        							<div class="modal-content">
                                                        								<div class="modal-header">
                                                        									<h4 class="modal-title" id="myLargeModalLabel">Enter ORBIDX Details</h4>
                                                        									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        								</div>
                                                        								<div class="modal-body">
                                                    								        <form method="POST" id="">
                                                        								        <div class="over-y-auto">
                                                            										<table class="table table-bordered">
                                                                                                        <thead style="background-color: #f7f7f7;">
                                                                                                            <tr>
                                                                                                                <th>Device Name</th>
                                                                                                                <th>Department</th>
                                                                                                                <th>Process</th>
                                                                                                                <th>Scanning Type</th>
                                                                                                                <th>Line</th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td>
                                                                                                                    <input type="text" name="device_name" id="device_name" class="form-control" placeholder="Device Name">
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    <select class="form-control custom-select2" name="dev_department" id="dev_department" style="width:100%">
                                                                                                                        <?= select_dropdown('department', array('id', 'department_name'), 'department_name ASC', '', '', ''); ?>
                                                                                                                    </select>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    <select class="form-control custom-select2" name="dev_process" id="dev_process" style="width:100%">
                                                                                                                        <?= select_dropdown('process', array('id', 'process_name'), 'process_name ASC', '', ' WHERE process_type = "Production"', ''); ?>
                                                                                                                    </select>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    <select class="form-control custom-select2" name="scan_type" id="scan_type" style="width:100%">
                                                                                                                        <?php
                                                                                                                            foreach($scan_type as $key => $value) {
                                                                                                                                print '<option value="'. $key .'">'. $value .'</option>';
                                                                                                                            }
                                                                                                                        ?>
                                                                                                                    </select>
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    <select class="form-control custom-select2" name="line" id="line" style="width:100%">
                                                                                                                        <?= select_dropdown('mas_line', array('id', 'line_name'), 'line_name ASC', '', '', ''); ?>
                                                                                                                    </select>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </form>
                                                        								</div>
                                                        								<div class="modal-footer">
                                                        									<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                                                        									<button type="button" class="btn btn-outline-primary saveDevice">Save</button>
                                                        								</div>
                                                        							</div>
                                                        						</div>
                                                        					</div>
                                                                            <?php } ?>
                        												</div>
                        											</div>
                        										    
                        											<div class="tab-pane fade" id="app_logo" role="tabpanel">
                        												<div class="pd-20">
                        												    <?php if(SET_APP_LOGO==0) { action_denied(); } else { ?>
                                                                                <form method="POST" enctype="multipart/form-data">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3 image_head_tag">
                                                                                            <label class="fieldrequired">App Logo</label>
                                                                                            <div class="form-group">
                                                                                                <input type="file" class="form-control imagefield" name="applogo_image" id="applogo_image" required>
                                                                                            </div>

                                                                                            <small class="imagename">Accept Images Only</small>
                                                                                        </div>

                                                                                        <div class="col-md-3">
                                                                                            <label for="">&nbsp;</label><br>
                                                                                            <button class="btn btn-outline-primary" type="submit" name="img_btn"><i class="fa-save fa"></i> Upload</button>
                                                                                        </div>

                                                                                        <div class="col-md-3">
                                                                                            <img src="<?= get_setting_val('APPLICATION_LOGO'); ?>" alt="" width="200">
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            <?php } ?>
                        												</div>
                        											</div>
                        										</div>
                        									</div>
                        								</div>
                        							</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include('includes/footer.php');
            include('modals.php');
            ?>
        </div>
    </div>
    <!-- js -->
    <?php include('includes/end_scripts.php'); ?>
    
	<script src="src/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
	<script src="vendors/scripts/range-slider-setting.js"></script>
	
	
	<script>
	    $(".cancel_row").click(function() {
	        var temp = $(this).data('id');
	        
	        $(".dev_name" + temp).show();
	        $(".dev_btn" + temp).addClass('hov_show').show();
	        $(".dev_select" + temp).addClass('d-none');
        });
	</script>
	
	<script>
	    $(".editRw").click(function() {
	        var temp = $(this).data('id');
	        
	        $(".dev_name" + temp).hide();
	        $(".dev_btn" + temp).removeClass('hov_show').hide();
	        $(".dev_select" + temp).removeClass('d-none');
        });
	</script>
	
	<script>
	    $(".save_row").click(function() {
	        
	        var temp = $(this).data('id');
	        
	        var department = $("#ed_dev_department" + temp).val();
	        var process = $("#ed_dev_process" + temp).val();
	        var scan_type = $("#ed_scan_type" + temp).val();
	        var line = $("#ed_line" + temp).val();
	        
	        if(department == "") {
	            message_noload('error', 'Department Required!');
	            return false;
	        } else if(process == "") {
	            message_noload('error', 'Process Required!');
	            return false;
	        } else if(line == "") {
	            message_noload('error', 'Line Required!');
	            return false;
	        } else {
	            
	            var data = 'department=' + department + '&process=' + process + '&scan_type=' + scan_type + '&line=' + line + '&id=' + temp;
	            
	            $.ajax({
                    type: 'POST',
                    url: 'ajax_action2.php?save_device_edit=1',
                    data : data,
                    success: function(msg) {
                        var j = $.parseJSON(msg);
                        
                        if(j.error==2) {
                            message_noload('error', 'Device Name Already exists!', 2500);
                        } else if(j.result==0) {
                            message_reload('success', 'Device Updated!', 1500);
                        } else {
                            message_noload('warning', 'something Went wrong!', 1500);
                        }
                    }
                })
	        }
	    });
	</script>
	
	<script>
	    $(".saveDevice").click(function() {
	        
	        var device = $("#device_name").val();
	        var department = $("#dev_department").val();
	        var process = $("#dev_process").val();
	        var scan_type = $("#scan_type").val();
	        var line = $("#line").val();
	        
	        if(device == "") {
	            message_noload('error', 'Device Name Required!');
	            return false;
	        } else if(department == "") {
	            message_noload('error', 'Department Required!');
	            return false;
	        } else if(process == "") {
	            message_noload('error', 'Process Required!');
	            return false;
	        } else if(line == "") {
	            message_noload('error', 'Line Required!');
	            return false;
	        } else {
	            
	            $("#overlay").fadeIn(100);
	            var data = 'device=' + device + '&department=' + department + '&process=' + process + '&scan_type=' + scan_type + '&line=' + line;
	            
	            $.ajax({
                    type: 'POST',
                    url: 'ajax_action2.php?save_device=1',
                    data : data,
                    success: function(msg) {
                        var j = $.parseJSON(msg);
                        
                        if(j.error==2) {
                            $("#overlay").fadeOut(300);
                            message_noload('error', 'Device Name ('+ device +') Already exists!', 2500);
                        } else if(j.result==0) {
                            $("#overlay").fadeOut(300);
                            message_reload('success', 'Device ('+ device +') Added!', 1500);
                        } else {
                            $("#overlay").fadeOut(300); 
                            message_noload('warning', 'something Went wrong!', 1500);
                        }
                    }
                })
	        }
	    });
	</script>
	
	<script>
	    $(".rdo").click(function() {
	        
	         $(".rdo_val").val($(this).val());
	    })
	</script>
	
	<script>
	   // $(document).ready(function() {
	   
	   $("#range_01").change(function() {
	        var a = $(this).val();
	        
	        $("#range_01_valueVal").val(a);
	        
	        var aa = $("#range_01_temp").val();
	        
	        if(a==aa) {
	            $(".range_01Btn").addClass('d-none');
	        } else {
	            $(".range_01Btn").removeClass('d-none');
	        }
	    })
	   
	   $("#range_01_02").change(function() {
	        var a = $(this).val();
	        
	        $("#range_01_02_valueVal").val(a);
	        
	        var aa = $("#range_01_02_temp").val();
	        
	        if(a==aa) {
	            $(".range_01_02Btn").addClass('d-none');
	        } else {
	            $(".range_01_02Btn").removeClass('d-none');
	        }
	    })
	</script>
    
    <script>
        function savedeptHod(tmp, id) {
            var value = $("#"+ tmp +'_valueVal').val();
            
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?UpdateHod=1&id=' + id + '&value=' + value,
                success: function(msg) {
                    var j = $.parseJSON(msg);
                    
                    if(j.res==0) {
                        message_reload('success', 'HOD Updated!', 1500);
                    } else {
                        message_noload('warning', 'something Went wrong!', 1500);
                    }
                }
            })
        }
    </script>
    
    <script>
        function viewTimeTemplate(id, typ) {
            
            if(typ=='edit') {
                $(".upclass").removeClass('d-none');
            } else {
                $(".upclass").addClass('d-none');
            }
            
            $.ajax({
                type : 'POST',
                url : 'ajax_search.php?showTimeTemplate=1&id=' + id + '&typ=' + typ,
                
                success: function(msg) {
                    
                    var json = $.parseJSON(msg);
                    
                    $("#viewTimeBody").html(json.tbody);
                }
            })
            
            $("#tempViewModal").modal('show');
        }
    </script>
    
    <script>
        function editTimeTemplate(id) {
            
            // if(typ=='edit') {
            //     $(".upclass").removeClass('d-none');
            // } else {
            //     $(".upclass").addClass('d-none');
            // }
            
            // $.ajax({
            //     type : 'POST',
            //     url : 'ajax_search.php?showTimeTemplate=1&id=' + id + '&typ=' + typ,
                
            //     success: function(msg) {
                    
            //         var json = $.parseJSON(msg);
                    
            //         $("#viewTimeBody").html(json.tbody);
            //     }
            // })
            
            $("#tempEditModal" + id).modal('show');
        }
    </script>
    
    <script>
        function UpdateTimeTemplate(formId, tmpId) {
            
            var form = $("#templateFormUpdate" + formId).serialize();
            
            var ab = 0;
            
            $(".e_start" + formId).each(function() {
                if($(this).val() == "") {
                    $(this).focus();
                    message_noload('warning', 'Day Start Required!', 1500);
                    ab++;
                }
            });
            
            if(ab == 0) {
                $(".e_end" + formId).each(function() {
                    if($(this).val() == "") {
                        $(this).focus();
                        message_noload('warning', 'Day End Required!', 1500);
                        ab++;
                    }
                });
            }
            
            if(ab==0) {
                $(".e_daily_time" + formId).each(function() {
                    if($(this).val() == "") {
                        $(this).focus();
                        message_noload('warning', 'Daily Working time Required in Minutes!', 1500);
                        ab++;
                    }
                });
            }
            
            if(ab==0) {
                $(".e_endday_time" + formId).each(function() {
                    if($(this).val() == "") {
                        $(this).focus();
                        message_noload('warning', 'Closing day Working time Required in Minutes!', 1500);
                        ab++;
                    }
                });
            }
            
            // if(ab == 0) {
            //     $(".rA" + formId).each(function() {
            //         if($(this).val() == "") {
            //             $(this).focus();
            //             message_noload('warning', 'Responsible (A) Required!', 1500);
            //             ab++;
            //         }
            //     });
            // }
            
            // if(ab == 0) {
            //     $(".rB" + formId).each(function() {
            //         if($(this).val() == "") {
            //             $(this).focus();
            //             message_noload('warning', 'Responsible (B) Required!', 1500);
            //             ab++;
            //         }
            //     });
            // }
            
            // if(ab == 0) {
            //     $(".rC" + formId).each(function() {
            //         if($(this).val() == "") {
            //             $(this).focus();
            //             message_noload('warning', 'Responsible (C) Required!', 1500);
            //             ab++;
            //         }
            //     });
            // }
            
            // if(ab == 0) {
            //     $(".rD" + formId).each(function() {
            //         if($(this).val() == "") {
            //             $(this).focus();
            //             message_noload('warning', 'Responsible (D) Required!', 1500);
            //             ab++;
            //         }
            //     });
            // }
            
            
            if(ab!=0) {
                return false;
            }
            
            
            
            $.ajax({
                type : 'POST',
                url : 'ajax_action.php?updateTimeTemplate',
                data : form,
                
                success: function(msg) {
                    var json = $.parseJSON(msg);
                    
                    if(json.res==0) {
                        message_reload('success', 'Tempate Updated!', 2000);
                    } else {
                        message_noload('warning', 'Something Went Wrong!', 2000);
                    }
                }
            })
                
        }
    </script>
    
    <script>
        function saveTimeTemplate() {
            var temp_name = $("#temp_name_add").val();
            var total_day = $("#total_day_add").val();
            var brand = $("#brand").val();
            
            var form = $("#templateForm").serialize();
            
            if(temp_name=="") {
                $("#temp_name_add").focus();
                message_noload('warning', 'Template Name Required!', 1500);
                return false;
            } else if(total_day=="") {
                $("#total_day_add").focus();
                message_noload('warning', 'Total Days Required!', 1500);
                return false;
            } else if(brand=="") {
                $("#brand").focus();
                message_noload('warning', 'Brand Required!', 1500);
                return false;
            } else {
                var ab = 0;
                
                $(".startDt").each(function() {
                    if($(this).val() == "") {
                        $(this).focus();
                        message_noload('warning', 'Day Start Required!', 1500);
                        ab++;
                    }
                });
                
                if(ab==0) {
                    $(".endDt").each(function() {
                        if($(this).val() == "") {
                            $(this).focus();
                            message_noload('warning', 'Day End Required!', 1500);
                            ab++;
                        }
                    });
                }
                
                if(ab==0) {
                    $(".daily_time").each(function() {
                        if($(this).val() == "") {
                            $(this).focus();
                            message_noload('warning', 'Daily Working time Required in Minutes!', 1500);
                            ab++;
                        }
                    });
                }
                
                if(ab==0) {
                    $(".endday_time").each(function() {
                        if($(this).val() == "") {
                            $(this).focus();
                            message_noload('warning', 'Closing day Working time Required in Minutes!', 1500);
                            ab++;
                        }
                    });
                }
                
                // if(ab==0) {
                //     $(".respA").each(function() {
                //         if($(this).val() == "") {
                //             $(this).focus();
                //             message_noload('warning', 'Responsible (A) Missing!', 1500);
                //             ab++;
                //         }
                //     });
                // }
                
                // if(ab==0) {
                //     $(".respB").each(function() {
                //         if($(this).val() == "") {
                //             $(this).focus();
                //             message_noload('warning', 'Responsible (B) Missing!', 1500);
                //             ab++;
                //         }
                //     });
                // }
                
                // if(ab==0) {
                //     $(".respC").each(function() {
                //         if($(this).val() == "") {
                //             $(this).focus();
                //             message_noload('warning', 'Responsible (C) Missing!', 1500);
                //             ab++;
                //         }
                //     });
                // }
                
                // if(ab==0) {
                //     $(".respD").each(function() {
                //         if($(this).val() == "") {
                //             $(this).focus();
                //             message_noload('warning', 'Responsible (D) Missing!', 1500);
                //             ab++;
                //         }
                //     });
                // }
                
                if(ab!=0) {
                    return false;
                }
                
                $.ajax({
                    type : 'POST',
                    url : 'ajax_action.php?saveTimeTemplate',
                    data : form,
                    
                    success: function(msg) {
                        var json = $.parseJSON(msg);
                        
                        if(json.res==0) {
                            message_reload('success', 'Tempate Saved!', 2000);
                        } else {
                            message_noload('warning', 'Something Went Wrong!', 2000);
                        }
                    }
                })
            }
        }
    </script>
    
    <script>
        function showEditvalue(val) {
            // alert(sa);
            $("."+ val +"_show").hide();
            $("."+ val +"_hide").show();
        }
        
        function hideEditvalue(val) {

            $("."+ val +"_show").show();
            $("."+ val +"_hide").hide();
        }
        
        function saveEditvalue(val) {

            var _reff = $("#"+ val +"_reff").val();
            var _description = $("#"+ val +"_description").val();
            var _valueVal = $("#"+ val +"_valueVal").val();
            
            
            $.ajax({
                type: 'POST',
                url: 'ajax_action.php?UpdateSetting=1&ref=' + _reff +'&description=' + _description + '&value=' + _valueVal,
                success: function(msg) {
                    var json = $.parseJSON(msg);
                    
                    if(json.val==0) {
                        message_reload('success', _description + ' Updated!', 2500);
                    } else {
                        message_noload('error', ' Something Went Wrong');
                        
                    }
                }
            })
        }
        
    </script>
    
    <script>
        $(document).ready(function() {
            $(".ahide").hide();
            $(".ashow").show();
        })
    </script>
    
    <script>
        function removeTr(id) {
            $("#trId" + id).remove();
        }
    </script>
    
    
    <script>
        function changeAllInp(id) {
            
            var val = $("#main" + id).val();
            
            $("."+ id).each(function() {
                
                $(this).val(val);
            })
        }
    </script>


</html>