<?php
date_default_timezone_set('Asia/Kolkata');

error_reporting(0);
ob_start();
session_start();

header("Content-Type: text/html;charset=UTF-8");

DEFINE ('DB_USER', 'u439020742_benso_cloud');
DEFINE ('DB_PASSWORD', 'BENSO_cloud_2024');
DEFINE ('DB_HOST', 'localhost'); 
DEFINE ('DB_NAME', 'u439020742_benso_cloud');

$mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

if ($mysqli->connect_errno) 
{
echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

mysqli_query($mysqli,"SET NAMES 'utf8'");	 


$logUser = $_SESSION['login_id'];
$logUnit = $_SESSION['loginCompany'];
$logdept = $_SESSION['login_department'];

$base_url = 'https://benso.cloud/';
