<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
$_SESSION['vusername'] = "-";
$_SESSION['utp'] = "";
define('TITLE', 'Logout');
include('templates/header.html');
include('iconn.php');

// Print some introductory text:
//print '<h2 dir="ltr" style="margin-left: 465px; margin-right: 80px">用戶登入</h2>';
//print '<h2 align="center">Logout</h2>';

	//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

$comp="true";
$invuser="false";
session_destroy();

echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:30px">User logout successfully</p>';
	
echo '<p align="center"><a href="login.php" align="right" >Go Login Page</a></p>';


include('templates/footer.html'); // Need the footer.
?>