<?php

include("includes/connection.php");
include("includes/function.php");

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

if ($username == "") {
	$_SESSION['msg'] = "1";
	echo "<script>location.href='index.php';</script>";
	exit;

} else if ($password == "") {
	$_SESSION['msg'] = "2";
	header("Location:index.php");
	exit;
} else {

	// $qry = "SELECT * FROM tbl_admin WHERE Binary email_id ='" . $username . "' and Binary password ='" . $password . "' and status='1' and is_active='1'";
	$qry = "SELECT * FROM employee_detail WHERE Binary username ='" . $username . "' and Binary password ='" . $password . "' and company='" . $_REQUEST['role'] . "' and is_active='active'";

	$result = mysqli_query($mysqli, $qry);

	if (mysqli_num_rows($result) > 0) {

		$sqql = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM company WHERE id=" . $_REQUEST['role']));

		$row = mysqli_fetch_assoc($result);

		$_SESSION['login_id'] = $row['id'];
		$_SESSION['login_name'] = $row['username'];
		$_SESSION['login_role'] = $sqql['type'];
		$_SESSION['loginCompany'] = $sqql['id'];
		$_SESSION['login_department'] = $row['department'];
		
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		unset($_SESSION['company']);
		
		timeline_history('Login', 'employee_detail', $_SESSION['login_id'], $row['employee_name'].' Login @'. date('d-M Y H:i:s'));
		
		echo "<script>location.href='dashboard.php';</script>";
		exit;

	} else {
		$_SESSION['msg'] = "4";
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['company'] = $_REQUEST['role'];

		echo "<script>location.href='index.php';</script>";
		exit;

	}

}

?>