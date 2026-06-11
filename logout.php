<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();

header("Location: users/index.php"); // Tetap seperti ini jika di luar
exit;