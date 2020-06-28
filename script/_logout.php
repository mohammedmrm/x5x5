<?php
session_start();
session_destroy();
setcookie('username','', time() + (86400 * 30), "/");
setcookie('password','', time() + (86400 * 30), "/");
header("location: ../login.php");
?>