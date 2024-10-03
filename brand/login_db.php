<?php

include("includes/connection.php");

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

if ($username == "") {
	$_SESSION['msg'] = "1";
	$_SESSION['bmsg'] = "Enter User Name";
	echo "<script>location.href='buyerLog.php';</script>";
	exit;

} else if ($password == "") {
	$_SESSION['msg'] = "2";
	$_SESSION['bmsg'] = "Enter Password";
	header("Location:buyerLog.php");
	exit;
} else {

	// $qry = "SELECT * FROM tbl_admin WHERE Binary email_id ='" . $username . "' and Binary password ='" . $password . "' and status='1' and is_active='1'";
	$qry = "SELECT * FROM brand WHERE Binary username ='" . $username . "' and Binary password ='" . $password . "'";

	$result = mysqli_query($mysqli, $qry);

	if (mysqli_num_rows($result) > 0) {

		$row = mysqli_fetch_assoc($result);

		$_SESSION['login_brand'] = $row['id'];

		echo "<script>location.href='dashboard.php';</script>";
		exit;

	} else {
		$_SESSION['msg'] = "4";
    	$_SESSION['bmsg'] = "Incorrect User Name or Password";
		echo "<script>location.href='buyerLog.php';</script>";
		exit;

	}

}

?>