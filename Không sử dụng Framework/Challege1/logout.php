<?php
session_start();
session_destroy();
header("Location: /CHALLEGE1/login.php");
exit;