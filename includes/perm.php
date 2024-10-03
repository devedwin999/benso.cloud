<?php
include("includes/connection.php");

// print "SELECT b.permission_name,b.value FROM employee_detail a LEFT JOIN user_permissions b ON a.user_group=b.user_group WHERE a.id='" . $_SESSION['login_id'] . "'";

$sqlqq_POIUUBBMMJK = mysqli_query($mysqli, "SELECT b.permission_name,b.value FROM employee_detail a LEFT JOIN user_permissions b ON a.user_group=b.user_group WHERE a.id='" . $_SESSION['login_id'] . "'");
while ($UIOPLOIJKNHY = mysqli_fetch_array($sqlqq_POIUUBBMMJK)) {

    define($UIOPLOIJKNHY['permission_name'], $UIOPLOIJKNHY['value']);
}

?>