<?php
include("includes/connection.php");
include("includes/function.php");

?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>BENSO GARMENTING - Buyer Login</title>

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

<body class="login-page"  style="background-image:url('src/bg-1.jpg');background-repeat: no-repeat;background-repeat: no-repeat;background-size: 100% 100%;">
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
				<!--<div class="col-md-6 col-lg-7">-->
				<!--	<img src="vendors/images/login-page-img.png" alt="">-->
				<!--</div>-->
				<div class="col-md-12 col-lg-12">
					<div class="login-box bg-white box-shadow border-radius-10">
						
						<?php if($_SESSION['bmsg']!="") { ?>
    						<div class="login-title">
    						    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong></strong> <?= $_SESSION['bmsg']; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
    						</div>
						<?php } $_SESSION['bmsg'] = ""; ?>
						
						
						<div class="login-title">
							<h2 class="text-center text-primary">Buyer Login</h2>
						</div>
						<form action="login_db.php" id="login-form" method="post" autocomplete="off">
						    
							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg" name="username" id="username"
									placeholder="Username">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" name="password"
									id="password" placeholder="**********">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
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
									<a class="btn btn-outline-primary btn-lg btn-block" href="#">Register To Create Account</a>
								</div>
								
								<div class="font-16 weight-600 pt-10 pb-10 col-sm-12 text-center" data-color="#707373">&nbsp;</div>
								
								<div class="col-sm-12 input-group mb-0">
									<a class="btn-lg btn-block" href="../index.php" style="text-align: center;text-decoration: underline;">Go Back</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	
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

	<script type="text/javascript">

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
	
	<script>
	    $(document).ready(function() {
	        setTimeout(function() {
	            $(".alert-dismissible").fadeOut('slow');
	        }, 2000)
	    })
	</script>


</body>

</html>