<?php
// echo "<pre>", print_r($_POST, 1); die;
date_default_timezone_set('Asia/Kolkata');

    error_reporting(0);
 	ob_start();
    session_start();
 
 	header("Content-Type: text/html;charset=UTF-8");
	
	
// 	if($_SESSION['login_id']==102) {
	    
//     	//development db only edwin login 
//      	 DEFINE ('DB_USER', 'benso_developer');
// 		 DEFINE ('DB_PASSWORD', '3VbqGf!65.Ce');
// 		 DEFINE ('DB_HOST', 'localhost'); 
// 		 DEFINE ('DB_NAME', 'benso_develop');
// 	} else {
	    
	    //local live 
     	 DEFINE ('DB_USER', 'benso_app');
    	 DEFINE ('DB_PASSWORD', 'benso@!123benso');
    	 DEFINE ('DB_HOST', 'localhost'); 
    	 DEFINE ('DB_NAME', 'benso_app');
// 	}
		 

	
	$mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

	if ($mysqli->connect_errno) 
	{
    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	mysqli_query($mysqli,"SET NAMES 'utf8'");	 

	
    $logBrand = $_SESSION['login_brand'];
?> 
	 
 