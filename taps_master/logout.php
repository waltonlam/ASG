<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
$_SESSION['vusername'] = "-";
$_SESSION['utp'] = "";
define('TITLE', 'Logout');
//include('templates/header.html');
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
echo '<img src="/taps/css/taps_logo.jpg" class="responsive">';
echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:30px">Logout successfully</p>';
	
echo '<p align="center"><a href="login.php" align="right" >Go to Login Page</a></p>';


include('templates/footer.html'); // Need the footer.
?>

<style>
	input[type=submit] {
		background-color: #87ceeb;
		color: white;
		padding: 12px 20px;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		width:100
	}

	.responsive {
		width: 100%;
		height: auto;
	}
</style>