<?php
if (!isset($_SESSION['login_id'])) {
	header('Location:index.php');
}

date_default_timezone_set('Asia/Kolkata');
date_default_timezone_set("Asia/Kolkata");

$modals = array();
?>

<style>

    .custom_a {
        text-decoration: underline !important;
        color: blue !important;
    }
    
    .min-height-70vh {
        min-height: 70vh;
    }
    
    .filt_badge {
        background: #ffa4ba;
        padding: 2px;
        color: black;
        position: fixed;
    }

    #overlay{
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        display: none;
        /*background: rgb(255 255 255 / 0%);*/
        background: rgb(0 0 0 / 28%);
        /*margin-left: -120px;*/
        z-index:100000;
    }
    .cv-spinner {
        /*height: 100%;*/
        /*display: flex;*/
        /*justify-content: center;*/
        /*align-items: center;  */
        
        height: 50%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
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
    
    
.is-hide{
  display:none;
}

    .hov_show {
        display: none;
    }
    
    .td_edDl:hover .hov_show {
        display: block;
    }

    .mw-200 {
        max-width:200px !important;
    }

    .ta-right {
        text-align:right;
    }
    
    .ta-left {
        text-align:left;
    }
    
    .over-y-auto {
        overflow-y:auto;
    }

    .pe-none {
        pointer-events: none !important;
    }
	.nw-cont {
		padding: 80px 20px 0 90px !important;
	}

	.nw-head {
		width: calc(100% - 68px) !important;
	}

	.nw-sidebar {
		width: 68px !important;
	}

	.nw-brand-logo {
		width: 0px !important;
	}

	.dropdown-toggle:hover .dw dw-house-1 {
		display: block !important;
	}

	.left-side-bar .mCS-dark-2.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
		background: #393939 !important;
	}

	.rounded {
		padding: 5px;
	}

	input {
		min-width: 100px !important;
	}

	input[type="checkbox"] {
		min-width: 20px !important;
	}

	.custom-select2 {
		min-width: 100px !important;
	}

	.fieldrequired::after {
		color: #dc3545 !important;
		content: ' *';
	}
	
	.tcl1 {
	    position: fixed;
        margin-top: 0%;
        margin-left: 40%;
        z-index: 1000;
	}
	
	.go_back {
	    display:none;
	}
	
	.tcl2 {
        color:blue;
        width: 100%;
        margin-left: 40%;
        display:flex
    }
	
    .tcl3 {
        padding: 0px 50px 0px 0px;
        /*text-shadow: 0px 0px 20px;*/
    }
    
    .user-notification .dropdown-toggle .badge {
        position: absolute;
        right: 3px;
        top: 4px;
        background: #fba6bb;
        width: 15px;
        height: 15px;
        display: block;
        font-size: 12px;
        padding: 2px;
        color: black;
    }
    
    .blockAll {
        cursor: not-allowed;
        pointer-events: none;
    }
    
    input[type="radio"] {
        min-width: 10px !important;
    }
    
    .req {
        /*color:#dc3545 !important;*/
    }
    
    .req::after {
		color: #dc3545 !important;
		content: '* This field is required';
	}
	
	
	@media (max-width: 479px) {
        .nav.vtabs {
            border-bottom: 0;
            border-right: none !important;
            height: 100%;
        }
        
        .nav.vtabs.customtab .nav-link.active {
            border-right: 1px solid #1b00ff40 !important;
            border-top: 1px solid #1b00ff40 !important;
            border-left: 1px solid #1b00ff40 !important;
            border-bottom: 1px solid #1b00ff40 !important;
        }
        
        /*.tcl1 {*/
        /*    position:absolute;*/
        /*    width:100%;*/
        /*    margin-top: 17%;*/
        /*    margin-left: 0% !important;*/
        /*    z-index: 0;*/
        /*}*/
        
        .tcl1 {
            position: absolute;
            width: 100%;
            margin-top: 17%;
            margin-left: 0% !important;
            z-index: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .tcl2 {
            color: blue;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            justify-content: center;
            text-align: center;
        }
        
        .tcl11 {
            position: absolute;
            width: 90%;
            margin-top: 20%;
            margin-left: 3% !important;
            z-index: 0;
        }
        
        .tcl21 {
            color: blue;
            margin-left: auto;
            margin-right: auto;
            display: flex;
        }

            
    	.go_back {
    	    display:block;
    	}
        
        /*.tcl2 {*/
        /*    color:blue;*/
        /*    margin-left: 0%;*/
        /*    display:flex*/
        /*}*/
        
        .tcl3 {
            padding: 0px 50px 0px 0px;
            /*text-shadow: 0px 0px 20px;*/
        }
    }
    
    
    #div1 {
      /*background-color: red;*/
      transform: translateY(33%);
    }
    
    #time {
      /*font-family: 'Nova Mono', monospace;*/
      /*font-size: 20px;*/
      /*text-align: center;*/
      /*text-shadow: 0px 0px 20px;*/
    }
    
    /*#date {*/
    /*  font-family: 'Eczar', serif;*/
    /*  font-size: 10vmin;*/
    /*  text-align: center;*/
    /*  text-shadow: 0px 0px 20px blue;*/
    /*}*/
    
    
    
    .btn-outline-danger {
        color: #dc3545 !important;
        border-color: #dc3545;
    }
    
    .btn-outline-danger:hover {
        color: #fff !important;
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-outline-primary {
        color: #1b00ff !important;
        border-color: #1b00ff;
    }
    
    .btn-outline-primary:hover {
        color: #fff !important;
        background-color: #1b00ff;
        border-color: #1b00ff;
    }
    
    .btn-outline-success:hover {
        color: #fff !important;
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .btn-outline-success {
        color: #28a745 !important;
        border-color: #28a745;
    }
    
    .btn-outline-warning {
        color: #ffc107 !important;
        border-color: #ffc107;
    }
    
    .btn-outline-warning:hover {
        color: #212529 !important;
        background-color: #ffc107;
        border-color: #ffc107;
    }
    
    .btn-danger {
        color: #fff !important;
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-success {
        color: #fff !important;
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .btn-outline-secondary:hover {
        color: #fff !important;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .f-12 {
        font-size: 12px !important;
    }
    .f-12 td {
        font-size: 12px !important;
    }
    
    .f-left {
        float:left !important;
    }
    
    .f-right {
        float:right !important;
    }
    
    .u {
        text-decoration:underline;
    }
    
    .w-100 {
        width:100% !important;
    }
    
    .btn-info {
        color: #fff !important;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .btn-outline-info:hover {
        color: #fff !important;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    
    a.text-info:hover {
        color: #ffff !important;
    }
    
    
    @media (max-width: 767px) {
        .dnone {
            display:none;
        }
        
        .title h4 {
            font-size:20px;
        }
    }
    
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0; /* Optional */
    }
    
    /* For Firefox */
    input[type="number"]::-moz-inner-spin-button,
    input[type="number"]::-moz-outer-spin-button {
      -moz-appearance: none;
      margin: 0; /* Optional */
    }

    .tab-content {
        border-right: 1px solid #dee2e6;
        border-left: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }
</style>

<style>
    .progress {
        height: 3px;
        width: 100%;
        /* background-color: #f5f5f5; */
        border-radius: 4px;
        overflow: hidden;
        position: relative;
        /* top: 100px; */
    }

    .progress-bar {
        height: 100%;
        background-color: #007bff;
    }

    .progress-bar-striped {
        background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem;
    }
</style>

<!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
<!--<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>-->
<?= page_spinner(); ?>
<div class="header nw-head">
	<div class="header-left">
		<span class="icon-copy ti-view-list showsidebar" style="margin-left: 15px;font-size: 20px;display:none"></span>
		<span class="icon-copy ti-close hidesidebar" style="margin-left: 15px;font-size: 20px;display:none"></span>
		<div class="menu-icon dw dw-menu ovWindow"></div>
		
		<?php $smp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id='" . $logUser . "'")); ?>
		
		<span style="margin-left: 10px;min-width: 100px;"><?= company_code($_SESSION['loginCompany']); ?></span>
        
	</div>
	
	<div class="header-right">
	    
		<div class="user-notification">
			<div class="dropdown">
				<a class="dropdown-toggle no-arrow check_inout_" href="#" role="button" data-toggle="dropdown">
				    <i class="icon-copy dw dw-wall-clock1"></i>
				</a>
			</div>
		</div>
	    
		<!--<div class="user-notification">-->
		<!--	<div class="dropdown">-->
		<!--		<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">-->
		<!--		    <i class="icon-copy dw dw-email1"></i>-->
		<!--		</a>-->
		<!--	</div>-->
		<!--</div>-->

		<div class="user-notification">
			<div class="dropdown">
				<a class="dropdown-toggle no-arrow" onclick="LoadNotification()" href="#" role="button" data-toggle="dropdown">
					<i class="icon-copy dw dw-notification"></i>
					<span class="notification-active notiCount"></span>
				</a>
				 <div class="dropdown-menu dropdown-menu-right">
					<div class="notification-list mx-h-350 customscroll">
						<ul id="notiContentArea">
							<li>
								<a href="#">
								    <div class="spinner-grow" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
									Loading..
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="user-info-dropdown">
			<div class="dropdown">
				<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
					<span class="user-icon">
						<img src="<?= $smp['employee_photo']; ?>" alt=""  style="width:50px;height:50px">
					</span>
					<span class="user-name">
						<?php echo $smp['employee_name']; ?>
					</span>
					<input type="hidden" value="<?= $logUser; ?>" id="logUser">
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
					 <a class="dropdown-item editEmployee" data-id="<?= $logUser; ?>" href="javascript:void(0)"><i class="dw dw-user1"></i>Edit Profile</a>
					 <a class="dropdown-item" href="teamTask.php"><i class="icon-copy fa fa-tasks" aria-hidden="true"></i> Team Task</a>
					<a class="dropdown-item" href="logout.php"><i class="dw dw-logout"></i> Log Out</a>
				</div>
			</div>
		</div>
		
	</div>

    <div class="progress" role="progressbar" id="progressBar">
        <div class="progress-bar progress-bar-striped progress-bar-animated" id="innerBar"></div>
    </div>
</div>
        
<!--<div class="tcl11 go_back">--><!--<div class="tcl21">--><!--<a class="border border-secondary rounded text-secondary" onclick="history_back()"><i class="icon-copy fa fa-arrow-left text-dark" aria-hidden="true"></i></a>--><!--</div>--><!--</div>-->

<div class="tcl1">
    <div id="div1" class="tcl2">
        <p id="time"></p>
        <p class="tcl3">&nbsp;&nbsp;<?= date('d, M-y'); ?></p>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var progressBar = document.getElementById("progressBar");
        var innerBar = document.getElementById("innerBar");

        innerBar.style.width = "0";

        var width = 0;
        var interval = setInterval(function() {
            width++;
            innerBar.style.width = width + "%";
            if (width >= 100) {
            clearInterval(interval);
            }
        }, 5);
        // alert(width);

        setTimeout(function(){
            $("#progressBar").addClass('d-none');
        }, 1100);
    });
</script>
    
<script>

    function history_back() {
        alert();
    }
        
    function LoadNotification() {
            
        searchNoti();
            
        var logUser = $("#logUser").val();
            
        $.ajax({
            type: 'POST',
            url: 'searchNotification.php?search_type=LoadNotification&user=' + logUser,
                
            success :function(msg) {
                var j = $.parseJSON(msg);
                
                $("#notiContentArea").html(j.list);
            }
        });
    }
</script>

<script>

    function searchNoti() {

        var logUser = $("#logUser").val();
        
        $.ajax({
            type: 'POST',
            url: 'searchNotification.php?search_type=teamTaskCount&user=' + logUser,

            success :function(msg) {
                var j = $.parseJSON(msg);
                
                if(j.num>0) {
                    $(".notiCount").addClass('badge');
                    $(".notiCount").text(j.num);
                }
            }
        });
    }
</script>


<script>
    
    window.setInterval(ut, 1000);

    function ut() {
        var d = new Date();
        
        document.getElementById("time").innerHTML = d.toLocaleTimeString();
        
    }
</script>

<script>
    // window.setInterval(updateTime, 1000);

    // function updateTime() {
    //     var currentTime = new Date();
        
    //     var newTime = new Date(currentTime.getTime() - (20 * 60 * 1000));

    //     document.getElementById("time").innerHTML = newTime.toLocaleTimeString();
    // }
</script>
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	