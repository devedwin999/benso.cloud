<?php
include("includes/connection.php");
include("includes/function.php");

if(isset($_SESSION['login_id']))
{
    header('Location:dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Login</title>

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
		function gtag() { dataLayer.push(arguments); }
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	
	
	<style>
	    .select-role {
            margin-bottom: 25px;
            padding: 0px 20px;
        }
	</style>
</head>

<style>
	@media (max-width: 767px) {

		.app_download {
			text-align: right;
			position: absolute;
			top: 10px;
			right: 20px;
		}
	}
	
	.app_download {
		text-align: right;
		position: absolute;
		top: 10px;
		right: 15%;
	}
</style>

<body class="login-page">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="index.php">
					<h4>BENSO GARMENTING</h4>
				</a>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<div class="app_download downloadBtn">
								<img src="<?= $base_url; ?>/src/logo/app-gif.gif" alt="" width="100">
							</div>
							<h2 class="text-center text-primary">Sign In</h2>
							
						</div>
						<form action="login_db.php" id="login-form" method="post" autocomplete="off">
						    
						    <div class="row">
							    <div class="select-role col-md-12">
    								<div class="btn-group btn-group-toggle" data-toggle="buttons">
    									<label class="btn active logBuyer">
    										<input type="radio" name="options" id="admin">
    										<div class="icon">
    										    <!--<img src="vendors/images/briefcase.svg" class="svg" alt="">-->
    										    <i class="icon-copy ion-earth" style="font-size: 30px;color:green"></i>
										    </div>
    										<span>I'm</span>
    										<span style="color:green;font-weight: bold;font-size: 16px;">Buyer</span>
    									</label>
    									<label class="btn logSupplier">
    										<input type="radio" name="options" id="user">
    										<div class="icon"><img src="vendors/images/person.svg" class="svg" alt=""></div>
    										<span>I'm</span>
    										Supplier
    									</label>
    								</div>
    							</div>
							</div>
							
							<script>
							    $(".logBuyer").click(function() {
							        window.location.href="brand/buyerLog.php";
							    })
							</script>
							
							<script>
							    $(".logSupplier").click(function() {
							        window.location.href="supplierLog.php";
							    })
							</script>
							    <?php if($_SESSION['msg']==4) { ?>
							        <p class="text-danger text-center invalid_login">* Invalid Login. Check login Details!</p>
							    <?php } ?>
							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg" name="username" id="username" placeholder="Username" value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="********" value="<?= isset($_SESSION['password']) ? $_SESSION['password'] : ''; ?>">
								<div class="input-group-append custom">
									<span class="input-group-text">
									    <i class="icon-copy pss_v fa fa-eye-slash" aria-hidden="true"></i>
									    <!--<i class="dw dw-padlock1"></i>-->
								    </span>
								</div>
							</div>
							<div class="input-group custom">
								<select name="role" id="role" class="custom-select2 form-control form-control-lg">
									<?= select_dropdown('company', array('id', 'company_name'), 'company_name ASC', isset($_SESSION['company']) ? $_SESSION['company'] : '', '', ''); ?>
								</select>
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user-2"></i></span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
									</div>
								</div>
								
								<div class="font-16 weight-600 pt-10 pb-10 col-sm-12 text-center" data-color="#707373">OR</div>
								
								<div class="col-sm-12 input-group mb-0">
									<a class="btn btn-outline-primary btn-lg btn-block" href="register.php">Register To Create Account</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>
	
	
	<?php
	$_SESSION['msg'] = '';
	$_SESSION['username'] = '';
	$_SESSION['password'] = '';
	$_SESSION['company'] = '';
	?>

	
	<script>
		$(".custom-select2").select2({
			placeholder: "Select a state",
			allowClear: true
		});
	</script>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>

	<script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
	<script src="src/plugins/sweetalert2/sweet-alert.init.js"></script>


	<script>

		$(document).ready(function(){
			$(".downloadBtn").click(function() {
				swal({
					title: 'Are you sure?',
					text: "Confirm Download?",
					type: 'info',
					showCancelButton: true,
					confirmButtonText: 'Yes, Download!',
					cancelButtonText: 'No, cancel!',
					confirmButtonClass: 'btn btn-success margin-5',
					cancelButtonClass: 'btn btn-danger margin-5',
					buttonsStyling: false
				}).then(function (dd) {
					if (dd['value'] == true) {

						var form = document.createElement('form');
						form.method = 'GET';
						form.action = 'download_apk.php';
						document.body.appendChild(form);
						form.submit();
						swal( 'App Successfully downloaded.', '', 'success')
					} else {
						swal( 'Cancelled', '', 'info')
					}
				})
			});
		});
		
        // document.getElementById('downloadBtn').addEventListener('click', function() {
        //     // Create a new form element
        //     var form = document.createElement('form');
        //     form.method = 'GET';
        //     form.action = 'download.php';

        //     document.body.appendChild(form);

        //     // Submit the form
        //     form.submit();
        // });
    </script>
	
	<script>
	    $(document).ready(function() {
	        setTimeout(function() {
	            $(".invalid_login").fadeOut();
	        }, 5000);
	    })
	</script>

	<script type="text/javascript">
	
    	$(".pss_v").click(function() {
    	    
    	    var a = $(this).hasClass('fa-eye-slash');
    	    
    	    if(a==true) {
    	        $(this).removeClass('fa-eye-slash').addClass('fa-eye');
    	        $("#password").prop('type', 'text').prop('placeholder', 'Password');
    	    } else {
    	        $(this).removeClass('fa-eye').addClass('fa-eye-slash');
    	        $("#password").prop('type', 'password').prop('placeholder', '********');
    	    }
    	});

		$(document).ready(function () {
			// Set the default cursor focus to a specific field, e.g., field2
			$('#username').focus();
		});

		$(function () {
			$('#login-form').validate({
				errorClass: "help-block",
				rules: {
					username: {
						required: true
					},
					password: {
						required: true
					},
					role: {
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