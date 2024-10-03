<?php
session_start();
unset($_SESSION["login_id"]);
unset($_SESSION["login_name"]);
unset($_SESSION["login_role"]);
unset($_SESSION["loginCompany"]);
echo "<script language=javascript>location.href='index.php';</script>";
?>