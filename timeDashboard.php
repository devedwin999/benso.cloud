<?php 
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");



$status = array(
    0 => 'Not Started',
    1 => 'In Progress',
    2 => 'Completed',
);
    
$status_color = array(
    0 => '#ffc107',
    1 => '#17a2b8',
    2 => '#28a745',
);

?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Time Management Dashboard</title>

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
	<link rel="stylesheet" type="text/css" href="src/plugins/jvectormap/jquery-jvectormap-2.0.3.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/fullcalendar/fullcalendar.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() { dataLayer.push(arguments); }
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>

<style>
	.nav-tabs {
		border-bottom: none !important;
	}
	
    .dhide {
        display:none;
    }
    
    .nav-tabs.customtab .nav-item.show .nav-link, .nav-tabs.customtab .nav-link.active {
        border-bottom: 2px solid #0e0e0e;
    }
    
    .nav-tabs.customtab .nav-item.show .nav-link, .nav-tabs.customtab .nav-link:hover {
        border-bottom: 2px solid #0e0e0e;
    }    
</style>


<body>

	<?php include('includes/header.php'); ?>
	<?php include('includes/sidebar.php'); ?>
<style>
	.tab-content {
		border-right: none;
		border-left: none;
		border-bottom: none;
	}

	.table td {
		/* font-size: 14px;
		font-weight: 500;
		padding: 3rem; */
	}

	.al-top {
		vertical-align: top;
		text-align: right;
		padding: 5px;
	}
</style>

    <div class="main-container nw-cont">
		<div class="xs-pd-20-10 pd-ltr-20">
			<div class="page-header">
				<div class="row">
					<div class="col-md-12 text-center col-sm-12">
						<div class="title dnone">
							<h4 class="u">Time Management Dashboard</h4>
						</div>
					</div>
				</div>
				
                <?php if(MOD_TIME_MANAGEMENT!=1) { action_denied(); exit; } ?>
			</div>
			
			<div class="row clearfix progress-box">
				<div class="col-lg-6 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<h5 class="h4 text-blue mb-20">To Do List</h5>
						
						<div class="tab">
							<ul class="nav nav-tabs customtab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#teamTask" role="tab" aria-selected="true">Team Task</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#orderTask" role="tab" aria-selected="false">Order Task</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#todayTask" role="tab" aria-selected="false">Today Task</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="tab" href="#todayComplete" role="tab" aria-selected="false">Today Completed Task</a>
								</li>
							</ul>

							<div class="tab-content">

								<div class="tab-pane fade show active" id="teamTask" role="tabpanel">
									<div class="pd-20" style="overflow-y:auto">
										<table class="table">
											<thead>
												<tr>
													<th>#</th>
													<th style="min-width:150px;">Name</th>
													<th>Status</th>
													<th>End Date</th>
												</tr>
											</thead>
											<tbody>
											<?php
												$qry = "SELECT a.*, b.created_by, b.task_msg, b.end_date, b.task_type ";
												$qry .= " FROM team_tasks_for a ";
												$qry .= " LEFT JOIN team_tasks b ON b.id=a.task_id ";
												$qry .= " WHERE a.employee_id = '".$logUser."' AND b.task_complete IS NULL";
												$qry .= " ORDER BY id DESC";
												
												$m=1;
												$num = mysqli_query($mysqli, $qry);
												
												$m = 1;
												if(mysqli_num_rows($num)>0) {
													while($task = mysqli_fetch_array($num)) {
														$created = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id = '". $task['created_by'] ."'"));
														$sml = ($task['type']=='assigned_toB') ? '<small>(You As Follower)</small>' : '';
												?>
												<tr>
													<td><?= $m; ?></td>
													<td>
														<a href="javascript:;" style="color: #87c9ef !important;" onclick="openTaskSheet(<?= $task['task_id']; ?>)"><?= $task['task_type'] . ' '.$sml; ?></a>
														<p style="color:gray"><?= $task['task_msg']; ?></p>
													</td>
													<td style="color:<?= $status_color[$task['task_status']]; ?>"><?= $status[$task['task_status']]; ?></td>
													<td><?= date('d M, Y - h:i A', strtotime($task['end_date'])); ?></td>
												</tr>
											<?php $m++; } } else { print '<tr><td colspan="4" class="text-center">No Tasks Found!</td></tr>'; } ?>
											</tbody>
										</table>
									</div>
								</div>

								<div class="tab-pane fade" id="orderTask" role="tabpanel">
									<div class="pd-20">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Sl.No</th>
													<th>BO</th>
													<th>Task Date</th>
													<th>Task</th>
													<th>Task Duration</th>
													<th>Task Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$sel = mysqli_query($mysqli, "SELECT * FROM order_tasks WHERE task_for = '". $logUser ."' AND task_status != 2 ORDER BY task_date ASC");
													$p = 1;
													if(mysqli_num_rows($sel) > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															?>
															<tr>
																<td><?= $p++; ?></td>
																<td><?= sales_order_code($result['sales_order_id']); ?></td>
																<td><?= date('d-M, Y', strtotime($result['task_date'])); ?></td>
																<td><a href="javascript:;" style="color: #87c9ef !important;" onclick="open_orderTask(<?= $result['id']; ?>)"><?= $result['activity']; ?></a> <small>(Order Task)</small></td>
																<td><?= time_calculator_new(time_calculator($result['task_timeing']), 1); ?></td>
																<td style="color: <?= $status_color[$result['task_status']] ?>"><?= $status[$result['task_status']]; ?></td>
															</tr>
												<?php } } else { print '<tr><td colspan="6" class="text-center">No tasks found!</td></tr>'; } ?>
											</tbody>
										</table>
									</div>
								</div>

								<div class="tab-pane fade" id="todayTask" role="tabpanel">
									<div class="pd-20">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Sl.No</th>
													<th>BO</th>
													<th>Task Date</th>
													<th>Task</th>
													<th>Task Duration</th>
													<th>Task Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$today_task_count = 0;
													$today_task_time = 0;

													$sel = mysqli_query($mysqli, "SELECT * FROM order_tasks WHERE task_for = '". $logUser ."' AND task_status != 2 AND task_date = '". date('Y-m-d') ."' ORDER BY task_date ASC");
													$or_nuum = mysqli_num_rows($sel);
													if($or_nuum > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															$today_task_count++;
															$today_task_time += $result['task_timeing'];
															?>
															<tr>
																<td><?= $today_task_count; ?></td>
																<td><?= sales_order_code($result['sales_order_id']); ?></td>
																<td><?= date('d-M, Y', strtotime($result['task_date'])); ?></td>
																<td><a href="javascript:;" style="color: #87c9ef !important;" onclick="open_orderTask(<?= $result['id']; ?>)"><?= $result['activity']; ?></a> <small>(Order Task)</small></td>
																<td><?= time_calculator_new(time_calculator($result['task_timeing']), 1); ?></td>
																<td style="color: <?= $status_color[$result['task_status']] ?>"><?= $status[$result['task_status']]; ?></td>
															</tr>
												<?php } } ?>

												<?php
													$qry = "SELECT a.*, b.created_by, b.task_msg, b.end_date, b.task_type, b.allowed_time, b.start_date ";
													$qry .= " FROM team_tasks_for a ";
													$qry .= " LEFT JOIN team_tasks b ON b.id=a.task_id ";
													$qry .= " WHERE a.employee_id = '".$logUser."' AND b.task_complete IS NULL";
													$qry .= " ORDER BY id DESC";
													
													$num = mysqli_query($mysqli, $qry);
													$tem_num = mysqli_num_rows($num);
													if($tem_num > 0) {
														while($task = mysqli_fetch_array($num)) {
															$created = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id = '". $task['created_by'] ."'"));
															$sml = ($task['type']=='assigned_toB') ? '<small>(You As Follower)</small>' : '';

															$today_task_count++;
															$today_task_time += $task['allowed_time'];
													?>
													<tr>
														<td><?= $today_task_count; ?></td>
														<td>Team Task</td>
														<td><?= date('d-M, Y', strtotime($task['start_date'])); ?></td>
														<td>
															<a href="javascript:;" style="color: #87c9ef !important;" onclick="openTaskSheet(<?= $task['task_id']; ?>)"><?= $task['task_type'] . ' '.$sml; ?></a>
															<p style="color:gray"><?= $task['task_msg']; ?></p>
														</td>
														<td><?= time_calculator_new(time_calculator($task['allowed_time']), 1); ?></td>
														<td style="color:<?= $status_color[$task['task_status']]; ?>"><?= $status[$task['task_status']]; ?></td>
													</tr>
												<?php $m++; } } if(($tem_num + $or_nuum) == 0 ) { print '<tr><td colspan="4" class="text-center">No Tasks Found!</td></tr>'; } ?>
											</tbody>
										</table>
									</div>
								</div>

								<div class="tab-pane fade" id="todayComplete" role="tabpanel">
									<div class="pd-20">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Sl.No</th>
													<th>BO</th>
													<th>Task Date</th>
													<th>Task</th>
													<th>Task Duration</th>
													<th>Actual Time</th>
													<th>Task Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$comp_count = 0;
													$task_close_time = 0;
													
													$sel = mysqli_query($mysqli, "SELECT * FROM order_tasks WHERE task_for = '". $logUser ."' AND task_status = 2 ORDER BY task_date ASC");
													if(mysqli_num_rows($sel) > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															$tot_ord_time = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(total_time) as total_time FROM order_task_timer WHERE task_id = '". $result['id'] ."' AND employee_id = '". $logUser ."' "));
															$comp_count++;
															$task_close_time += $tot_ord_time['total_time'];
															?>
															<tr>
																<td><?= $comp_count; ?></td>
																<td><?= sales_order_code($result['sales_order_id']); ?></td>
																<td><?= date('d-M, Y', strtotime($result['task_date'])); ?></td>
																<td><a href="javascript:;" style="color: #87c9ef !important;" onclick="open_orderTask(<?= $result['id']; ?>)"><?= $result['activity']; ?></a></td>
																<td><?= time_calculator_new(time_calculator($result['task_timeing']), 1); ?></td>
																<td><?= time_calculator_new(time_calculator($tot_ord_time['total_time']), 1); ?></td>
																<td style="color: <?= $status_color[$result['task_status']] ?>"><?= $status[$result['task_status']]; ?></td>
															</tr>
												<?php } } ?>

												<?php
													$sel = mysqli_query($mysqli, "SELECT * FROM team_tasks WHERE completed_by = '". $logUser ."' AND task_complete = 'yes' AND completed_date LIKE '%". date('Y-m-d') ."%' ORDER BY id ASC");
													
													if(mysqli_num_rows($sel) > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															$tot_task_time = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(total_time) as total_time FROM team_task_timer WHERE task_id = '". $result['id'] ."' AND employee_id = '". $logUser ."' "));
															$comp_count++;
															$task_close_time += $tot_task_time['total_time'];
															?>
															<tr>
																<td><?= $comp_count; ?></td>
																<td>Team Task</td>
																<td><?= date('d-M, Y', strtotime($result['start_date'])); ?></td>
																<td>
																	<a href="javascript:;" style="color: #87c9ef !important;" onclick="openTaskSheet(<?= $result['id']; ?>)"><?= $result['task_type'] . ' '.$sml; ?></a>
																	<p style="color:gray"><?= $result['task_msg']; ?></p>
																</td>
																<td><?= time_calculator_new(time_calculator($result['allowed_time']), 1); ?></td>
																<td><?= time_calculator_new(time_calculator($tot_task_time['total_time']), 1); ?></td>
																<td style="color:<?= $status_color[2]; ?>"><?= $status[2]; ?></td>
															</tr>
												<?php } } if($comp_count == 0) { print '<tr><td colspan="6" class="text-center">No tasks found!</td></tr>'; } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				

				<div class="col-lg-6 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<div class="progress-box text-center newClass">
						    <div class="calendar-wrap">
        						<!-- <div id='calendar'></div> JS Calender -->
								<?php include('calendar.php'); ?>
        					</div>
						</div>
					</div>
				</div>
				
				
				
			</div>
			
			<div class="row clearfix progress-box">
				<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<div class="progress-box text-center">
							 <input type="text" class="knob dial1" value="0" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgColor="#fff" data-fgColor="#1b00ff" data-angleOffset="180" readonly>
							<h5 class="text-blue padding-top-10 h5">My Performence</h5>
							<span class="d-block">80% Average <i class="fa fa-line-chart text-blue"></i></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<div class="progress-box text-center">
							 <input type="text" class="knob dial2" value="0" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgColor="#fff" data-fgColor="#00e091" data-angleOffset="180" readonly>
							<h5 class="text-light-green padding-top-10 h5">Order Received</h5>
							<span class="d-block">75% Average <i class="fa text-light-green fa-line-chart"></i></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<div class="progress-box text-center">
							 <input type="text" class="knob dial3" value="0" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgColor="#fff" data-fgColor="#f56767" data-angleOffset="180" readonly>
							<h5 class="text-light-orange padding-top-10 h5">Order Speed</h5>
							<span class="d-block">90% Average <i class="fa text-light-orange fa-line-chart"></i></span>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<div class="progress-box text-center">
							 <input type="text" class="knob dial4" value="0" data-width="120" data-height="120" data-linecap="round" data-thickness="0.12" data-bgColor="#fff" data-fgColor="#a683eb" data-angleOffset="180" readonly>
							<h5 class="text-light-purple padding-top-10 h5">Panding Orders</h5>
							<span class="d-block">65% Average <i class="fa text-light-purple fa-line-chart"></i></span>
						</div>
					</div>
				</div>
			</div>

			<?php
				$followup_task = mysqli_fetch_array(mysqli_query($mysqli, "SELECT count(id) as tot_task, sum(task_timeing) as task_timeing FROM order_tasks WHERE resp_b = '". $logUser ."' AND task_status != 2 AND task_date = '". date('Y-m-d') ."'"));
			?>
			
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 pt-10 height-100-p">
						<h2 class="mb-30 h4" style="color: green;font-size: 42px;">Daily Task Visit</h2>
						<div class="browser-visits" style="font-size: 24px;">
							<ul>
								<li class="d-flex flex-wrap align-items-center" style="width: calc(70% - 100px);">
									<div class="browser-name text-success"  style="font-size: 24px;"><i class="icon-copy fa fa-user-secret" aria-hidden="true"></i> Daily Task Visit</div>
									<div class="visit"><span class="badge badge-pill badge-success"><?= $today_task_count; ?></span></div>
									<div class="visit"><span class="badge badge-pill badge-success" style="margin-left: 100%;"><?= time_calculator_new(time_calculator($today_task_time), 2); ?></span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center"  style="width: calc(70% - 100px);">
									<div class="browser-name text-danger"  style="font-size: 24px;"><i class="icon-copy dw dw-analytics-5"></i> Not Reviewed</div>
									<div class="visit"><span class="badge badge-pill badge-danger">0</span></div>
									<div class="visit"><span class="badge badge-pill badge-danger" style="margin-left: 100%;">0 Min</span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center"  style="width: calc(70% - 100px);">
									<div class="browser-name text-warning"  style="font-size: 24px;"><i class="icon-copy dw dw-analytics-211"></i> Followups</div>
									<div class="visit"><span class="badge badge-pill badge-warning"><?= $followup_task['tot_task']; ?></span></div>
									<div class="visit"><span class="badge badge-pill badge-warning" style="margin-left: 100%;"><?= time_calculator_new(time_calculator($followup_task['task_timeing']), 2); ?></span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center"  style="width: calc(70% - 100px);">
									<div class="browser-name text-success"  style="font-size: 24px;"><i class="icon-copy dw dw-analytics-211"></i> Management Tasks</div>
									<div class="visit"><span class="badge badge-pill badge-success">0</span></div>
									<div class="visit"><span class="badge badge-pill badge-success" style="margin-left: 100%;">0 Min</span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center"  style="width: calc(70% - 100px);">
									<div class="browser-name text-secondary"  style="font-size: 24px;"><i class="icon-copy dw dw-tick"></i> Done</div>
									<div class="visit"><span class="badge badge-pill badge-secondary"><?= $comp_count; ?></span></div>
									<div class="visit"><span class="badge badge-pill badge-secondary" style="margin-left: 100%;"><?= time_calculator_new(time_calculator($task_close_time), 2); ?></span></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 pt-10 height-100-p">
					    <div class="tab">
        					<ul class="nav nav-tabs customtab" role="tablist">
        						<li class="nav-item">
        							<a class="nav-link active" data-toggle="tab" href="#daily_task" role="tab" aria-selected="false" style="font-size: 24px;color: green;font-weight: bold;">Daily Task Visit</a>
        						</li>
        						<li class="nav-item">
        							<a class="nav-link sewingInptab" data-toggle="tab" href="#managementTasks" role="tab" aria-selected="false" style="font-size: 24px;color: green;font-weight: bold;">Management Tasks</a>
        						</li>
        						<li class="nav-item">
        							<a class="nav-link cuttingtab" data-toggle="tab" href="#not_reviewed" role="tab" aria-selected="true" style="font-size: 24px;color: red;font-weight: bold;">Not Reviewed</a>
        						</li>
        						<li class="nav-item">
        							<a class="nav-link sewingInptab" data-toggle="tab" href="#followups" role="tab" aria-selected="false" style="font-size: 24px;color: orange;font-weight: bold;">Followups</a>
        						</li>
        						<li class="nav-item">
        							<a class="nav-link sewingOuttab" data-toggle="tab" href="#done_" role="tab" aria-selected="false" style="font-size: 24px;color: gray;font-weight: bold;">Done</a>
        						</li>
        					</ul>
        
        					<div class="tab-content">
        						<div class="tab-pane fade show active" id="daily_task" role="tabpanel">
        							<div class="pd-20">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Sl.No</th>
													<th>BO</th>
													<th>Task Date</th>
													<th>Task</th>
													<th>Task Duration</th>
													<th>Task Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$today_task_count = 0;
													$today_task_time = 0;

													$sel = mysqli_query($mysqli, "SELECT * FROM order_tasks WHERE task_for = '". $logUser ."' AND task_status != 2 AND task_date = '". date('Y-m-d') ."' ORDER BY task_date ASC");
													$or_nuum = mysqli_num_rows($sel);
													if($or_nuum > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															$today_task_count++;
															// $today_task_time += $result['task_timeing'];
															?>
															<tr>
																<td><?= $today_task_count; ?></td>
																<td><?= sales_order_code($result['sales_order_id']); ?></td>
																<td><?= date('d-M, Y', strtotime($result['task_date'])); ?></td>
																<td><a href="javascript:;" style="color: #87c9ef !important;" onclick="open_orderTask(<?= $result['id']; ?>)"><?= $result['activity']; ?></a> <small>(Order Task)</small></td>
																<td><?= time_calculator_new(time_calculator($result['task_timeing']), 1); ?></td>
																<td style="color: <?= $status_color[$result['task_status']] ?>"><?= $status[$result['task_status']]; ?></td>
															</tr>
												<?php } } ?>

												<?php
													$qry = "SELECT a.*, b.created_by, b.task_msg, b.end_date, b.task_type, b.allowed_time, b.start_date ";
													$qry .= " FROM team_tasks_for a ";
													$qry .= " LEFT JOIN team_tasks b ON b.id=a.task_id ";
													$qry .= " WHERE a.employee_id = '".$logUser."' AND b.task_complete IS NULL";
													$qry .= " ORDER BY id DESC";
													
													$num = mysqli_query($mysqli, $qry);
													$tem_num = mysqli_num_rows($num);
													if($tem_num > 0) {
														while($task = mysqli_fetch_array($num)) {
															$created = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id = '". $task['created_by'] ."'"));
															$sml = ($task['type']=='assigned_toB') ? '<small>(You As Follower)</small>' : '';

															$today_task_count++;
															// $today_task_time += $task['allowed_time'];
													?>
													<tr>
														<td><?= $today_task_count; ?></td>
														<td>Team Task</td>
														<td><?= date('d-M, Y', strtotime($task['start_date'])); ?></td>
														<td>
															<a href="javascript:;" style="color: #87c9ef !important;" onclick="openTaskSheet(<?= $task['task_id']; ?>)"><?= $task['task_type'] . ' '.$sml; ?></a>
															<p style="color:gray"><?= $task['task_msg']; ?></p>
														</td>
														<td><?= time_calculator_new(time_calculator($task['allowed_time']), 1); ?></td>
														<td style="color:<?= $status_color[$task['task_status']]; ?>"><?= $status[$task['task_status']]; ?></td>
													</tr>
												<?php $m++; } } if(($tem_num + $or_nuum) == 0 ) { print '<tr><td colspan="4" class="text-center">No Tasks Found!</td></tr>'; } ?>
											</tbody>
										</table>
        							</div>
        						</div>
        						
        						<div class="tab-pane fade" id="managementTasks" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">Management Tasks</div>
        						</div>
        						
        						<div class="tab-pane fade" id="not_reviewed" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">Not Reviewed</div>
        						</div>
        						
        						<div class="tab-pane fade" id="followups" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">
										
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Sl.No</th>
													<th>BO</th>
													<th>Task Date</th>
													<th>Task</th>
													<th>Task Duration</th>
													<th>Task Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$pp = 1;
													$sel = mysqli_query($mysqli, "SELECT * FROM order_tasks WHERE resp_b = '". $logUser ."' AND task_status != 2 AND task_date = '". date('Y-m-d') ."' ORDER BY task_date ASC");
													$or_nuum = mysqli_num_rows($sel);
													if($or_nuum > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															?>
															<tr>
																<td><?= $pp++; ?></td>
																<td><?= sales_order_code($result['sales_order_id']); ?></td>
																<td><?= date('d-M, Y', strtotime($result['task_date'])); ?></td>
																<td><a href="javascript:;" style="color: #87c9ef !important;" onclick="open_orderTask(<?= $result['id']; ?>)"><?= $result['activity']; ?></a> <small>(Order Task)</small></td>
																<td><?= time_calculator_new(time_calculator($result['task_timeing']), 1); ?></td>
																<td style="color: <?= $status_color[$result['task_status']] ?>"><?= $status[$result['task_status']]; ?></td>
															</tr>
												<?php } } else { print '<tr><td colspan="4" class="text-center">No Followup Tasks Found!</td></tr>'; } ?>
											</tbody>
										</table>
									</div>
        						</div>
        						
        						<div class="tab-pane fade" id="done_" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Sl.No</th>
													<th>BO</th>
													<th>Task Date</th>
													<th>Task</th>
													<th>Task Duration</th>
													<th>Actual Time</th>
													<th>Task Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$comp_count = 0;
													$task_close_time = 0;
													
													$sel = mysqli_query($mysqli, "SELECT * FROM order_tasks WHERE task_for = '". $logUser ."' AND task_status = 2 ORDER BY task_date ASC");
													if(mysqli_num_rows($sel) > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															$tot_ord_time = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(total_time) as total_time FROM order_task_timer WHERE task_id = '". $result['id'] ."' AND employee_id = '". $logUser ."' "));
															$comp_count++;
															$task_close_time += $tot_ord_time['total_time'];
															?>
															<tr>
																<td><?= $comp_count; ?></td>
																<td><?= sales_order_code($result['sales_order_id']); ?></td>
																<td><?= date('d-M, Y', strtotime($result['task_date'])); ?></td>
																<td><a href="javascript:;" style="color: #87c9ef !important;" onclick="open_orderTask(<?= $result['id']; ?>)"><?= $result['activity']; ?></a></td>
																<td><?= time_calculator_new(time_calculator($result['task_timeing']), 1); ?></td>
																<td><?= time_calculator_new(time_calculator($tot_ord_time['total_time']), 1); ?></td>
																<td style="color: <?= $status_color[$result['task_status']] ?>"><?= $status[$result['task_status']]; ?></td>
															</tr>
												<?php } } ?>

												<?php
													$sel = mysqli_query($mysqli, "SELECT * FROM team_tasks WHERE completed_by = '". $logUser ."' AND task_complete = 'yes' AND completed_date LIKE '%". date('Y-m-d') ."%' ORDER BY id ASC");
													
													if(mysqli_num_rows($sel) > 0) {
														while($result = mysqli_fetch_assoc($sel)) {
															$tot_task_time = mysqli_fetch_array(mysqli_query($mysqli, "SELECT SUM(total_time) as total_time FROM team_task_timer WHERE task_id = '". $result['id'] ."' AND employee_id = '". $logUser ."' "));
															$comp_count++;
															$task_close_time += $tot_task_time['total_time'];
															?>
															<tr>
																<td><?= $comp_count; ?></td>
																<td>Team Task</td>
																<td><?= date('d-M, Y', strtotime($result['start_date'])); ?></td>
																<td>
																	<a href="javascript:;" style="color: #87c9ef !important;" onclick="openTaskSheet(<?= $result['id']; ?>)"><?= $result['task_type'] . ' '.$sml; ?></a>
																	<p style="color:gray"><?= $result['task_msg']; ?></p>
																</td>
																<td><?= time_calculator_new(time_calculator($result['allowed_time']), 1); ?></td>
																<td><?= time_calculator_new(time_calculator($tot_task_time['total_time']), 1); ?></td>
																<td style="color:<?= $status_color[2]; ?>"><?= $status[2]; ?></td>
															</tr>
												<?php } } if($comp_count == 0) { print '<tr><td colspan="6" class="text-center">No tasks found!</td></tr>'; } ?>
											</tbody>
										</table>
									</div>
        						</div>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
	    </div>
	</div>
	
	<?php include('modals.php'); ?>


	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/jQuery-Knob-master/jquery.knob.min.js"></script>
	<script src="src/plugins/highcharts-6.0.7/code/highcharts.js"></script>
	<script src="src/plugins/highcharts-6.0.7/code/highcharts-more.js"></script>
	<script src="src/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
	<script src="src/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="vendors/scripts/dashboard2.js"></script>
	<script src="src/plugins/fullcalendar/fullcalendar.min.js"></script>
	<script src="vendors/scripts/calendar-setting.js"></script>
	
	<!-- sweetalert -->
	<script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
    <script src="src/plugins/sweetalert2/sweet-alert.init.js"></script>
    
    
<script src="quickaction.js"></script>


</body>



<script>
	$(document).ready(function () {

		$('a.margin-5').data('data-content', 20);
	}
	);
</script>

<script>
	$(".tabclass").click(function () {

		$(".nav-link").removeClass('active');
		$(".tab-pane").removeClass('show active');

		var sd = $(this).attr('data-tgt');
		$("." + sd + "tab").addClass('active');
		$("." + sd + "div").addClass('show active');
	})
</script>



<script>
    $(document).ready(function() {
        searchNoti();
    })
</script>

</html>