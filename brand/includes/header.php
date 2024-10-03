<?php
if (!isset($_SESSION['login_brand'])) {
	header('Location:buyerLog.php');
}
?>

<style>
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
    }
</style>
<div class="header">
	<div class="header-left">
		<span class="icon-copy ti-view-list showsidebar" style="margin-left: 15px;font-size: 20px;display:none"></span>
		<span class="icon-copy ti-close hidesidebar" style="margin-left: 15px;font-size: 20px;display:none"></span>

		<div class="menu-icon dw dw-menu ovWindow"></div>

		<?php $smp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT brand_name FROM brand WHERE id='" . $logBrand . "'")); ?>

		<span style="position: absolute;left: 10%;">
		    Buyer Login
		</span>
	</div>
	<!-- <span class="icon-copy ti-close"></span> -->
	<div class="header-right">
	    
		<div class="user-info-dropdown">
			<div class="dropdown">
				<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
					<span class="user-icon">
						<img src="vendors/images/user-icon.png" alt="">
					</span>
					<span class="user-name">
						<?php echo $smp['brand_name']; ?>
					</span>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
					<!-- <a class="dropdown-item" href="javascript:void(0)">
						<i class="dw dw-user1"></i> Profile</a> -->
					<a class="dropdown-item" href="logout.php">
						<i class="dw dw-logout"></i> Log Out</a>
				</div>
			</div>
		</div>
	</div>
</div>