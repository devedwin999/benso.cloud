<?php 
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

if(EMPLOYEE_DASH == 1) {  ?>
<script>
    window.location.href='empDash.php';
</script>
<?php exit; } else { ?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Dashboard</title>

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


<style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }
    :root {
      --primary-color: #f6f7fb;
      --white-color: #fff;
      --black-color: #18191a;
      --red-color: #e74c3c;
    }
    
    .newClass {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 60px;
    }
    .newClass .clock {
      display: flex;
      height: 400px;
      width: 400px;
      border-radius: 50%;
      align-items: center;
      justify-content: center;
      background: var(--white-color);
      box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1), 0 25px 45px rgba(0, 0, 0, 0.1);
      position: relative;
    }
    .clock label {
      position: absolute;
      inset: 20px;
      text-align: center;
      transform: rotate(calc(var(--i) * (360deg / 12)));
    }
    .clock label span {
      display: inline-block;
      font-size: 30px;
      font-weight: 600;
      color: var(--black-color);
      transform: rotate(calc(var(--i) * (-360deg / 12)));
    }
    .newClass .indicator {
      position: absolute;
      height: 10px;
      width: 10px;
      display: flex;
      justify-content: center;
    }
    .indicator::before {
      content: "";
      position: absolute;
      height: 100%;
      width: 100%;
      border-radius: 50%;
      z-index: 100;
      background: var(--black-color);
      border: 4px solid var(--red-color);
    }
    .indicator .hand {
      position: absolute;
      height: 130px;
      width: 4px;
      bottom: 0;
      border-radius: 25px;
      transform-origin: bottom;
      background: var(--red-color);
    }
    .hand.minute {
      height: 120px;
      width: 5px;
      background: var(--black-color);
    }
    .hand.hour {
      height: 100px;
      width: 8px;
      background: var(--black-color);
    }
</style>

<body>

	<?php include('includes/header.php'); ?>

	<?php include('includes/sidebar.php'); ?>


    <div class="main-container nw-cont">
		<div class="xs-pd-20-10 pd-ltr-20">
			<div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4 style="font-size: 30px;color: green;">Time Management Dashboard</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.html">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Time Management Dashboard</li>
							</ol>
						</nav>
					</div>
					
					
					<div class="col-md-6 col-sm-12 text-right">
					    <h4 style="font-size: 30px;color: green;"><i class="icon-copy fa fa-tree" aria-hidden="true"></i> Save Paper, Save Tree</h4>
					    
						<!--<div class="dropdown">-->
						<!--	<a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">-->
						<!--		January 2018-->
						<!--	</a>-->
						<!--	<div class="dropdown-menu dropdown-menu-right">-->
						<!--		<a class="dropdown-item" href="#">Export List</a>-->
						<!--		<a class="dropdown-item" href="#">Policies</a>-->
						<!--		<a class="dropdown-item" href="#">View Assets</a>-->
						<!--	</div>-->
						<!--</div>-->
					</div>
				</div>
			</div>
			
			<div class="row clearfix progress-box">
				<div class="col-lg-6 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<div class="progress-box text-center newClass">
						    <div class="clock">
                                <label style="--i: 1"><span>1</span></label>
                                <label style="--i: 2"><span>2</span></label>
                                <label style="--i: 3"><span>3</span></label>
                                <label style="--i: 4"><span>4</span></label>
                                <label style="--i: 5"><span>5</span></label>
                                <label style="--i: 6"><span>6</span></label>
                                <label style="--i: 7"><span>7</span></label>
                                <label style="--i: 8"><span>8</span></label>
                                <label style="--i: 9"><span>9</span></label>
                                <label style="--i: 10"><span>10</span></label>
                                <label style="--i: 11"><span>11</span></label>
                                <label style="--i: 12"><span>12</span></label>
                        
                                <div class="indicator">
                                  <span class="hand hour"></span>
                                  <span class="hand minute"></span>
                                  <span class="hand second"></span>
                                </div>
                            </div>
                            
                            <div class="mode-switch"></div>
						</div>
					</div>
				</div>
				
				
				<div class="col-lg-6 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 height-100-p">
						<div class="progress-box text-center newClass">
						    <div class="calendar-wrap">
        						<div id='calendar'></div>
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
			
			<div class="row">
				<div class="col-lg-4 col-md-6 col-sm-12 mb-30">
					<div class="card-box pd-30 pt-10 height-100-p">
						<h2 class="mb-30 h4" style="color: green;font-size: 42px;">Daily Task Visit</h2>
						<div class="browser-visits" style="font-size: 24px;">
							<ul>
								<li class="d-flex flex-wrap align-items-center">
									<div class="browser-name text-success"  style="font-size: 24px;"><i class="icon-copy fa fa-user-secret" aria-hidden="true"></i> Daily Task Visit</div>
									<div class="visit"><span class="badge badge-pill badge-success">50</span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center">
									<div class="browser-name text-danger"  style="font-size: 24px;"><i class="icon-copy dw dw-analytics-5"></i> Not Reviewed</div>
									<div class="visit"><span class="badge badge-pill badge-danger">40</span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center">
									<div class="browser-name text-warning"  style="font-size: 24px;"><i class="icon-copy dw dw-analytics-211"></i> Followups</div>
									<div class="visit"><span class="badge badge-pill badge-warning">20</span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center">
									<div class="browser-name text-success"  style="font-size: 24px;"><i class="icon-copy dw dw-analytics-211"></i> Management Tasks</div>
									<div class="visit"><span class="badge badge-pill badge-success">20</span></div>
								</li>
								<li class="d-flex flex-wrap align-items-center">
									<div class="browser-name text-secondary"  style="font-size: 24px;"><i class="icon-copy dw dw-tick"></i> Done</div>
									<div class="visit"><span class="badge badge-pill badge-secondary">40</span></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-8 col-md-6 col-sm-12 mb-30">
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
        							    Daily Task Visit
        							</div>
        						</div>
        						
        						<div class="tab-pane fade" id="managementTasks" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">
        							    Management Tasks
        							</div>
        						</div>
        						
        						<div class="tab-pane fade" id="not_reviewed" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">
        							    Not Reviewed
        							</div>
        						</div>
        						
        						<div class="tab-pane fade" id="followups" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">
        							    Followups
        							</div>
        						</div>
        						
        						<div class="tab-pane fade" id="done_" role="tabpanel">
        							<div class="pd-20" style="overflow-y: auto;">
        							    Done
        							</div>
        						</div>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
	    </div>
	</div>


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
    // Get references to DOM elements
    const body = document.querySelector("body"),
      hourHand = document.querySelector(".hour"),
      minuteHand = document.querySelector(".minute"),
      secondHand = document.querySelector(".second"),
      modeSwitch = document.querySelector(".mode-switch");
    
    // check if the mode is already set to "Dark Mode" in localStorage
    if (localStorage.getItem("mode") === "Dark Mode") {
      // add "dark" class to body and set modeSwitch text to "Light Mode"
      body.classList.add("dark");
      modeSwitch.textContent = "Light Mode";
    }
    
    // add a click event listener to modeSwitch
    modeSwitch.addEventListener("click", () => {
      // toggle the "dark" class on the body element
      body.classList.toggle("dark");
      // check if the "dark" class is currently present on the body element
      const isDarkMode = body.classList.contains("dark");
      // set modeSwitch text based on "dark" class presence
      modeSwitch.textContent = isDarkMode ? "Light Mode" : "Dark Mode";
      // set localStorage "mode" item based on "dark" class presence
      localStorage.setItem("mode", isDarkMode ? "Dark Mode" : "Light Mode");
    });
    
    const updateTime = () => {
      // Get current time and calculate degrees for clock hands
      let date = new Date(),
        secToDeg = (date.getSeconds() / 60) * 360,
        minToDeg = (date.getMinutes() / 60) * 360,
        hrToDeg = (date.getHours() / 12) * 360;
    
      // Rotate the clock hands to the appropriate degree based on the current time
      secondHand.style.transform = `rotate(${secToDeg}deg)`;
      minuteHand.style.transform = `rotate(${minToDeg}deg)`;
      hourHand.style.transform = `rotate(${hrToDeg}deg)`;
    };
    
    // call updateTime to set clock hands every second
    setInterval(updateTime, 1000);
    
    //call updateTime function on page load
    updateTime();

</script>

</html>

<?php } ?>