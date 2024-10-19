<?php //include("includes/perm.php"); ?>
<div class="left-side-bar nw-sidebar">
	<div class="brand-logo">
		<a href="dashboard.php">
			<!-- <img src="vendors/images/deskapp-logo.svg" alt="" class="dark-logo">
		<img src="vendors/images/deskapp-logo-white.svg" alt="" class="light-logo"> -->
			<h5 style="color: #fff;" class="nw-min-head">
				<img src="vendors/images/favicon-32x32.png" style="width:40px">
				<!-- BENSO -->
			</h5>
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
			    
				<li>
					<a href="mod_dash.php" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-house-1" title="Dashboard"></span>
                            <span class="mtext">&nbsp;</span>
						<span class="mtext mtn1"> Dashboard</span>
					</a>
				</li>
				
				<?php if(MOD_MERCHAND==1) { ?>
                    <li>
                        <a href="mod_merch.php" class="dropdown-toggle no-arrow">
                            <i class="icon-copy dw dw-user2 micon" title="Merchandiser"></i>
                                <span class="mtext">&nbsp;</span>
                            <span class="mtext mtn1"> Merchandiser</span>
                        </a>
                    </li>
                <?php } if(MOD_SHEETS==1) { ?>
                    <li>
                        <a href="mod_sheets.php" class="dropdown-toggle no-arrow">
                            <i class="icon-copy fa fa-file-excel-o micon" aria-hidden="true"></i>
                                <span class="mtext">&nbsp;</span>
                            <span class="mtext mtn1"> Sheets</span>
                        </a>
                        </a>
                    </li>
				<?php } if(MOD_APPROVALS==1) { ?>
                    <li>
                        <a href="mod_approvals.php" class="dropdown-toggle no-arrow">
                            <span class="icon-copy ti-layout-grid2 micon"></span>
                                <span class="mtext">&nbsp;</span>
                            <span class="mtext mtn1"> Approvals</span>
                        </a>
                        </a>
                    </li>
			    <?php } if(MOD_PLANNING==1) { ?>
                    <li>
                        <a href="mod_planning.php" class="dropdown-toggle no-arrow">
    						<i class="icon-copy dw dw-map micon" aria-hidden="true"></i>
                                <span class="mtext">&nbsp;</span>
                            <span class="mtext mtn1"> Planning</span>
                        </a>
                        </a>
                    </li>
				<?php } if(MOD_FABRIC==1) { ?>
    				<li>
        				<a href="mod_fabric.php" class="dropdown-toggle no-arrow">
    						<i class="icon-copy dw dw-shopping-bag micon" title="Fabric"></i>
        					<span class="mtext">&nbsp;</span>
        					<span class="mtext mtn1">Fabric</span>
        				</a>
        			</li>
    			    
				<?php } if(DEVELOPING==1) { ?>
    			    <li>
        				<a href="mod_store.php" class="dropdown-toggle no-arrow">
    						<i class="icon-copy dw dw-shopping-cart-1 micon" title="Store"></i>
        					<span class="mtext">&nbsp;</span>
        					<span class="mtext mtn1">Store</span>
        				</a>
        			</li>
    			    
			    <?php } if(MOD_PRODUCTION==1) { ?>
    			    <li>
        				<a href="mod_production.php" class="dropdown-toggle no-arrow">
						    <i class="icon-copy dw dw-factory micon" title="Production"></i>
        					<span class="mtext">&nbsp;</span>
        					<span class="mtext mtn1">Production</span>
        				</a>
        			</li>
        		
        		<?php } if(MOD_QC==1) { ?>
			    
    			    <li class="dropdown">
    					<a href="javascript:;" class="dropdown-toggle nw-noarrow">
    						<i class="icon-copy dw dw-tick micon"></i>
    						<span class="mtext">&nbsp;</span>
    						<span class="mtext mtn1">QC</span>
    					</a>
    					<ul class="submenu">
    					    <?php if(QUALITY_APP==1) { ?>
    							<li><a href="quality-check.php">Production QC</a></li>
    						<?php } ?>
    							<li><a href="#">Store QC</a></li>
    					</ul>
    			    </li>
			    <?php } if(MOD_HR==1) { ?>
			    
    			    <li class="dropdown">
    					<a href="javascript:;" class="dropdown-toggle nw-noarrow">
    					    <i class="icon-copy dw dw-human-resources micon"></i>
    						<span class="mtext">&nbsp;</span>
    						<span class="mtext mtn1">HR</span>
    					</a>
    					<ul class="submenu">
    					    <?php if(UP_EMPLOYEE_TEMP==1) { ?>
    							<li><a href="employee_reg.php">Registered Employees</a></li>
    					    <?php } if(UP_EMPLOYEE==1) { ?>
    							<li><a href="employee.php">Employee</a></li>
				            <?php } if(DEVELOPING==1) { ?>
                                <li><a href="#">Worker</a></li>
                                <li><a href="#">Attendance</a></li>
                                <li><a href="#">Salary</a></li>
                                <li><a href="#">Settlement</a></li>
                                <li><a href="#">Task Manager</a></li>
                            <?php } ?>
    					</ul>
    			    </li>
    			    
			    <?php } if(DEVELOPING==1) { ?>
    			    <li class="dropdown">
    					<a href="javascript:;" class="dropdown-toggle nw-noarrow">
    					    <i class="icon-copy dw dw-startup-1 micon"></i>
    						<span class="mtext">&nbsp;</span>
    						<span class="mtext mtn1">Maintanance</span>
    					</a>
    					<ul class="submenu">
                            <li><a href="#">Purchase</a></li>
                            <li><a href="#">Services</a></li>
                            <li><a href="#">Teansfer</a></li>
                            <li><a href="#">Vehicle</a></li>
    					</ul>
    			    </li>
    			    
    			    <li class="dropdown">
    					<a href="javascript:;" class="dropdown-toggle nw-noarrow">
    					    <i class="icon-copy dw dw-file micon"></i>
    						<span class="mtext">&nbsp;</span>
    						<span class="mtext mtn1">Documents</span>
    					</a>
    					<ul class="submenu">
                            <li><a href="#">Invoice</a></li>
                            <li><a href="#">Document Manager</a></li>
    					</ul>
    			    </li>
    			    
			    <?php } if(MOD_ACCOUNTS==1) { ?>
    			    <li>
        				<a href="mod_accounts.php" class="dropdown-toggle no-arrow">
    						<i class="icon-copy dw dw-money-2 micon" title="Accounts"></i>
        					<span class="mtext">&nbsp;</span>
        					<span class="mtext mtn1">Accounts</span>
        				</a>
        			</li>
				
			    <?php } if(MOD_MASTER==1) { ?>
        		    <li>
        				<a href="mod_masters.php" class="dropdown-toggle no-arrow">
        					<span class="micon dw dw-list3" title="Masters"></span>
        					<span class="mtext">&nbsp;</span>
        					<span class="mtext mtn1">Masters</span>
        				</a>
        			</li>
			    <?php } ?>
			    
		    <?php if(APP_PERMISSIONS==1) { ?>
			    <li>
					<a href="permissions.php" class="dropdown-toggle no-arrow">
						<i class="icon-copy dw dw-door micon"></i>
						<span class="mtext">&nbsp;</span>
						<span class="mtext mtn1">App Permissions</span>
					</a>
				</li>
				
		    <?php } if(APP_LOG==1) { ?>
			    <li>
					<a href="app_log.php" class="dropdown-toggle no-arrow">
					    <!--<i class="icon-copy fa fa-history micon" aria-hidden="true"></i>-->
					    <span class="icon-copy ti-time micon"></span>
						<span class="mtext">&nbsp;</span>
						<span class="mtext mtn1">App Log</span>
					</a>
				</li>
				
		    <?php } if(BUNDLE_TRACK==1) { ?>
			    <li>
					<a href="bundle_track.php" class="dropdown-toggle no-arrow">
					    <!--<i class="icon-copy fa fa-history micon" aria-hidden="true"></i>-->
					    <i class="icon-copy fa fa-search micon" aria-hidden="true"></i>
						<span class="mtext">&nbsp;</span>
						<span class="mtext mtn1">Bundle Tracker</span>
					</a>
				</li>
				
		    <?php } if(MOD_REPORTS==1) { ?>
			    <li>
					<a href="reports.php" class="dropdown-toggle no-arrow">
					    <i class="icon-copy fa fa-bar-chart micon" aria-hidden="true"></i>
						<span class="mtext">&nbsp;</span>
						<span class="mtext mtn1">Reports</span>
					</a>
				</li>
		    <?php } if(MOD_SETTINGS==1) { ?>
			    <li>
					<a href="settings.php" class="dropdown-toggle no-arrow">
						<i class="icon-copy dw dw-settings2 micon"></i>
						<span class="mtext">&nbsp;</span>
						<span class="mtext mtn1">Settings</span>
					</a>
				</li>
		    <?php } if(DEVELOPING==1) { ?>
		    
			    <li>
					<a href="location.php" class="dropdown-toggle no-arrow">
						#
						<span class="mtext">&nbsp;</span>
						<span class="mtext mtn1">Add Location</span>
					</a>
				</li>
			    <li>
					<a href="location_view.php" class="dropdown-toggle no-arrow">
						#
						<span class="mtext">&nbsp;</span>
						<span class="mtext mtn1">View Location</span>
					</a>
				</li>
			<?php } ?>
			</ul>
		</div>
	</div>
</div>
<li>

	<div class="mobile-menu-overlay"></div>