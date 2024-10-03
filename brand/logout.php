<?php
session_start();
unset($_SESSION["login_brand"]);
echo "<script language=javascript>location.href='buyerLog.php';</script>";
?>