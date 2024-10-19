<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");


if (isset($_REQUEST['updateForm'])) {

    $data = array( 
        'task_name' => filter_var($_POST['edit_task_name'], FILTER_SANITIZE_STRING),
        'created_by' => $logUser,
        'created_unit' => $logUnit,
    );

    $qry = Update('mas_task', $data, " WHERE id = '" . $_REQUEST['edit_task_id'] . "'");

    timeline_history('Update', 'mas_task', $_REQUEST['edit_task_id'], 'Task Master Updated.');
    
    $_SESSION['msg'] = "updated";

    header("Location:mas_task.php");

    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BENSO - Team Task</title>
    
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

<style>
    .custom-control-label::before {
        border: #adb5bd solid 0px !important;
    }
    
    .custom-control-label::before {
        content :'👉';
    }
</style>

<body>

    <?php
    include('includes/header.php');
    include('includes/sidebar.php');
    ?>

    <div class="main-container nw-cont">
        <?php
        if ($_SESSION['msg'] == 'saved') { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Team Task Added.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php } else if ($_SESSION['msg'] == 'updated') { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Team Task Updated.
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
                    
                    <?php if(TEAM_TASK_CREATION!=1) { action_denied(); exit; } ?>
                    
                    <div class="pd-20">
                        <h4 class="text-blue h4">Team Tasks
                        
                            <!--<a href="task-add" data-toggle="modal" data-target="#task-add" class="bg-light-blue btn text-blue weight-500" style="float: right;"><i class="ion-plus-round"></i> Add New Task</a>-->
							
							<div class="btn-group mr-2" role="group" aria-label="First group" style="float: right;">
								<a href="<?= $base_url.'task_list.php'; ?>" class="bg-light-blue btn text-blue weight-500" style="float: right;"><i class="fa fa-list"></i> Task List</a>
								<a data-toggle="modal" data-target="#task-add" class="bg-light-blue btn text-blue weight-500" style="float: right;"><i class="ion-plus-round"></i> New Task</a>
							</div>
                        </h4>
                    </div>
                </div>
                
                    
                <div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-30">
						<div class="pd-20 card-box height-100-p">
						    <div class="container pd-0">
						        
						        <?php
						            $ops = mysqli_query($mysqli, "SELECT a.* FROM team_tasks a WHERE a.created_by = '". $logUser ."' AND task_complete IS NULL ORDER BY id DESC");
						            
						            $ops2 = mysqli_query($mysqli, "SELECT a.* FROM team_tasks a WHERE a.created_by = '". $logUser ."' AND task_complete = 'yes' ORDER BY id DESC");
						        ?>
						    
								<div class="task-title row align-items-center">
									<div class="col-md-8 col-sm-12">
										<h5>Todo List (<?= mysqli_num_rows($ops); ?> Left)</h5>
									</div>
									<div class="col-md-4 col-sm-12 text-right">
										
									</div>
								</div>
								<div class="profile-task-list pb-30">
									<ul>
									    <?php if(mysqli_num_rows($ops)>0) {
									    while($row = mysqli_fetch_array($ops)) { ?>
    										<li onclick="openTaskSheet_OnlyView(<?= $row['id']; ?>)">
    											<div class="custom-control custom-checkbox mb-5">
    												<!--<input type="checkbox" class="custom-control-input" id="task-1">-->
    												
    												
    												<label class="custom-control-label" for="task-1"></label>
    											</div>
    											<div class="task-type"><?= $row['task_type']; ?></div>
    											<?= $row['task_msg']; ?>
    											<div class="task-assign">Assigned to <?= implode(' & ', emp_name($row['assigned_to'])); ?> <div class="due-date">due date <span style="text-decoration:underline"><?= date('d M Y', strtotime($row['end_date'])); ?></span></div></div>
    											<div class="task-assign"> <small style="color: #ffb100;">Follower : <?= implode(' & ', emp_name($row['assigned_toB'])); ?></small> </div>
    										</li>
										<?php } } else { ?>
										    <li style="text-align: center;color: gray;">
										        <p>Nothing Found</p>
										    </li>
										<?php } ?>
									</ul>
									
									<!--<audio controls>-->
         <!--                             <source src="horse.ogg" type="audio/ogg">-->
         <!--                             <source src="uploads/audio/horse.mp3" type="audio/mpeg">-->
         <!--                             Your browser does not support the audio element.-->
         <!--                           </audio>-->
								</div>
						    </div>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-30">
						<div class="pd-20 card-box height-100-p">
						    <div class="container pd-0">
						        
								<div class="task-title row align-items-center">
									<div class="col-md-12 col-sm-12">
										<h5>Completed Tasks (<?= mysqli_num_rows($ops2); ?>)</h5>
									</div>
								</div>
								<div class="profile-task-list close-tasks">
									<ul>
										
										<?php if(mysqli_num_rows($ops2)>0) {
									    while($row = mysqli_fetch_array($ops2)) { ?>
    										<li onclick="openTaskSheet_OnlyView(<?= $row['id']; ?>)">
    											<div class="custom-control custom-checkbox mb-5">
    												<!--<input type="checkbox" class="custom-control-input" id="task-1">-->
    												
    												
    												<label class="custom-control-label" for="task-1"></label>
    											</div>
    											<div class="task-type"><?= $row['task_type']; ?></div>
    											<?= $row['task_msg']; ?>
    											<div class="task-assign">Assigned to <?= implode(' & ', emp_name($row['assigned_to'])); ?> <div class="due-date">due date <span style="text-decoration:underline"><?= date('d M Y', strtotime($row['end_date'])); ?></span></div></div>
    											<div class="task-assign"> <small style="color: #ffb100;">Follower : <?= implode(' & ', emp_name($row['assigned_toB'])); ?></small> </div>
    										</li>
										<?php } } else { ?>
										    <li style="text-align: center;color: gray;">
										        <p>Nothing Found</p>
										    </li>
										<?php } ?>
									</ul>
								</div>
								
								<div class="modal fade customscroll" id="task-add" tabindex="-1" role="dialog">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLongTitle">Tasks Add</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Close Modal">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<form method="POST" id="taskForm" autocomplete="off">
    											<div class="modal-body pd-0">
    												<div class="task-list-form">
    													<ul>
    														<li>
																<div class="form-group row">
																	<label class="col-md-4 fieldrequired">Task Type</label>
																	<div class="col-md-8">
																		<select class="custom-select2 form-control" name="type" id="type" style="width:100% !important;" required>
																			<option value="team_task">Team Task</option>
																			<option value="remainder_task">Remainder Task</option>
																		</select>
																	</div>
																</div>

																<div class="form-group row">
																	<label class="col-md-4 fieldrequired">Task</label>
																	<div class="col-md-8">
																	    <input type="text" name="task_type" id="task_type" class="form-control" placeholder="Enter Task">
																		<!--<select class="custom-select2 form-control" name="task_type" id="task_type" style="width:100% !important;" required>-->
																		<!--    <?= select_dropdown('mas_task', array('id', 'task_name'), 'task_name ASC', '', '', ''); ?>-->
																		<!--</select>-->
																	</div>
																</div>
																<div class="form-group row">
																	<label class="col-md-4 fieldrequired">Task Message</label>
																	<div class="col-md-8">
																		<textarea class="form-control" placeholder="Task Message" name="task_msg" id="task_msg" required></textarea>
																	</div>
																</div>
																<div class="form-group row">
																	<label class="col-md-4 fieldrequired">Assigned to (A)</label>
																	<div class="col-md-8">
																		<select class="custom-select2 form-control" name="assigned_to[]" id="assigned_to" style="width:100% !important;" multiple required>
																			<!-- <option value=""  data-image="http://127.0.0.1:8080/benso_cloud/uploads/employeeDet/102/employee_494063.jpg">ertet</option> -->
																		    <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>
																		</select>
																	</div>
																</div>
																<div class="form-group row">
																	<label class="col-md-4 fieldrequired">Assigned to (B)</label>
																	<div class="col-md-8">
																		<select class="custom-select2 form-control" name="assigned_toB[]" id="assigned_toB" style="width:100% !important;" multiple required>
																		    <?= select_dropdown('employee_detail', array('id', 'employee_name'), 'employee_name ASC', '', ' WHERE is_active="active"', ''); ?>
																		</select>
																	</div>
																</div>

																<div class="form-group row d-none remain_r">
																	<label class="col-md-4 fieldrequired">Remainder From</label>
																	<div class="col-md-8">
																	    <input type="date" name="remainder_from" id="remainder_from" class="form-control" value="<?= date('Y-m-d'); ?>">
																	</div>
																</div>

																<div class="form-group row d-none remain_r">
																	<label class="col-md-4 fieldrequired">Remainder Days</label>
																	<div class="col-md-8">
																	    <input type="number" name="remainder_days" id="remainder_days" class="form-control" placeholder="Remainder Days" value="1">
																	</div>
																</div>
																
																<div class="form-group row task_r">
																	<label class="col-md-4 fieldrequired">Start Date & Time</label>
																	<div class="col-md-8 d-flex">
																	    <input class="form-control" name="start_date" id="start_date" type="date" required style="width:50%" value="<?= date('Y-m-d'); ?>" onchange="calculateWorking()">
																	    <input class="form-control" name="start_time" id="start_time" type="time" required style="width:50%" value="<?= date('h:i') ?>" onchange="calculateWorking()">

																		<!-- <input class="form-control datetimepicker" placeholder="Start Date & time" type="text" onchange="calculateWorking()"> -->																    
																	    <!--<input class="form-control datetimepicker" name="start_date" id="start_date" type="text" required>-->
																	</div>
																</div>
																<div class="form-group row task_r">
																	<label class="col-md-4 fieldrequired">End Date & Time</label>
																	<div class="col-md-8 d-flex">
																	    <input class="form-control" name="end_date" id="end_date" type="date" required style="width:50%" onchange="calculateWorking()">
																	    <input class="form-control" name="end_time" id="end_time" type="time" required style="width:50%" value="<? //= date('h:i', strtotime('+3 hours', date('Y-m-d H:i:s'))) ?>" onchange="calculateWorking()">

																		<!-- <input class="form-control datetimepicker" placeholder="End Date & time" type="text" onchange="calculateWorking()"> -->
																	</div>
																</div>
																
																<div class="form-group row task_r">
																	<label class="col-md-4">Task Duration</label>
																	<div class="col-md-8">
																	    <input class="form-control" id="tot_duration" placeholder="" type="text" readonly>
																		<input type="hidden" name="task_duration" id="task_duration">
																	</div>
																</div>
																
																<div class="form-group row">
																	<label class="col-md-4 fieldrequired">Task working time in minutes</label>
																	<div class="col-md-8">
																	    <input class="form-control" name="totTime" id="totTime" placeholder="Task working time in minutes" type="number">
																	</div>
																</div>
    														</li>
    														 
    													</ul>
    												</div>
    												<!--<div class="add-more-task">-->
    												<!--	<a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Task"><i class="ion-plus-circled"></i> Add More Task</a>-->
    												<!--</div>-->
    											</div>
    											<div class="modal-footer">
    												<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
    												<button type="button" class="btn btn-outline-primary" onclick="saveTask()">Add</button>
    											</div>
											</form>
										</div>
									</div>
								</div>
								<!-- add task popup End -->
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
	
    
    <script>
        function saveTask() {

			var type = $("#type").val();
            
            if($("#task_type").val()=="") {
                $("#task_type").focus();
                message_noload('error', 'Task Required!', 1500);
                return false;
            } else if($("#task_msg").val()=="") {
                $("#task_msg").focus();
                message_noload('error', 'Task Message Required!', 1500);
                return false;
            } else if($("#assigned_to").val()=="") {
                $("#assigned_to").focus();
                message_noload('error', 'Assigned (A) To Required!', 1500);
                return false;
            } else if($("#assigned_toB").val()=="") {
                $("#assigned_toB").focus();
                message_noload('error', 'Assigned (B) To Required!', 1500);
                return false;
            } else if($("#start_date").val()=="" && type =='team_task') {
                $("#start_date").focus();
                message_noload('error', 'Start Date Required!', 1500);
                return false;
            } else if($("#end_date").val()=="" && type =='team_task') {
                $("#end_date").focus();
                message_noload('error', 'End Date Required!', 1500);
                return false;
            } else if($("#totTime").val()=="") {
                $("#totTime").focus();
                message_noload('error', 'Task working time in minutes Required!', 1500);
                return false;
            } else {
                var form = $("#taskForm").serialize();
                
                $.ajax({
                    type: 'POST',
                    url: 'ajax_action.php?saveTeamTask',
                    data: form,
                    success :function(msg) {
                        var j = $.parseJSON(msg);
                        
                        if(j.res==0) {
                            message_reload('success', ''+ j.mess +' Task Assigned!', 1500);
                        } else {
                            message_noload('error', 'Something Went Wrong!', 1500);
                        }
                    }
                });
            }
        }
    </script>
    
    <script>
		
		function calculateWorking() {
			var st = $("#start_date").val() + ' ' + $("#start_time").val();
			var en = $("#end_date").val() + ' ' + $("#end_time").val();

			var std = new Date(st);
			var end = new Date(en);

			var milliseconds = end - std;

			var totalSeconds = Math.floor(milliseconds / 1000);

			var totalMinutes = Math.floor(totalSeconds / 60);
			var totalMinutes = Math.floor(milliseconds / (60 * 1000));
			var days = Math.floor(totalMinutes / (24 * 60));
			var hours = Math.floor((totalMinutes % (24 * 60)) / 60);
			var minutes = totalMinutes % 60;

			var stDy = moment(st);
			var enDy = moment(en);
			var dayDifference = enDy.diff(stDy, 'days');

			var remainingMilliseconds = milliseconds % (24 * 60 * 60 * 1000);
			var remainingMinutes = Math.floor(remainingMilliseconds / (60 * 1000));

			$("#task_duration").val(totalSeconds);
			$("#tot_duration").val(dayDifference + ' Days OR ' + hours + ' Hours And ' + minutes + ' Minutes');
		}
    </script>

	<script>
		$(document).ready(function() {
			$("#type").change(function(){

				var typ = $(this).val();

				(typ == 'remainder_task') ? $(".remain_r").removeClass('d-none') : $(".remain_r").addClass('d-none') ;
				(typ == 'team_task') ? $(".task_r").removeClass('d-none') : $(".task_r").addClass('d-none') ;
			});


			$("#remainder_days").keyup(function() {
				var a = $(this).val();

				(a<1) ? $(this).val(1) : $(this).val(a);
			});


			$('#task-add').on('shown.bs.modal', function (e) {
				$("#assigned_to, #assigned_toB").each(function() {
					$(this).select2({
						dropdownParent: $("#task-add"),
					})
				});
			});
			
		});


		// $(document).ready(function() {
		// 	function formatOption(option) {
		// 		if (!option.id) {
		// 			return option.text; // optgroup or placeholder
		// 		}
		// 		const imgSrc = $(option.element).data('image');
		// 		return $('<span><img src="' + imgSrc + '" style="width: 20px; height: 20px; margin-right: 10px;" /> ' + option.text + '</span>');
		// 	}

		// 	function formatSelection(option) {
		// 		const imgSrc = $(option.element).data('image');
		// 		return $('<span><img src="' + imgSrc + '" style="width: 20px; height: 20px; margin-right: 10px;" /> ' + option.text + '</span>');
		// 	}

		// 	$('#assigned_to').select2({
		// 		templateResult: formatOption,
		// 		templateSelection: formatSelection
		// 	});
		// });
	</script>
</html>