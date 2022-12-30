<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
$_SESSION['vusername'] = "-";
$_SESSION['utp'] = "";
define('TITLE', 'Login');
//include('templates/header.html');
include('iconn.php');
date_default_timezone_set('Asia/Hong_Kong');

// Print some introductory text:
//print '<h2 dir="ltr" style="margin-left: 465px; margin-right: 80px">用戶登入</h2>';
print '<img src="/taps/css/taps_logo.jpg" class="responsive"><div>
		<h2 style="margin-right:10px">Toxic Air Pollution System</h2><hr>';

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

$comp="true";
$invuser="false";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Handle the form:
	if ( (!empty($_POST['uid'])) )  {
			$q = "select * from uacc where uid = '".$_POST['uid']."' and pwd='".$_POST['pwd']."'";
			$result=$dbc->query($q);
			if ($result->num_rows)
			{
				$_SESSION['vuserid'] = $_POST['uid'];
				$r=$result->fetch_object();
				$_SESSION['vusername'] = $r->fname." ".$r->lname;
				$_SESSION['utp']=$r->ronly;
				$_SESSION['lastLoginTime'] = date('Y-m-d H:i:s');
				//print $_SESSION['utp'];
				//$_SESSION['dbc'] = $dbc;
				//echo "This is a valid user: ".$_SESSION['vusername'];
				header("location:exportGlabReport.php");
				//exit();
			}	else{
				$invuser="true";
			}				

			//$q = "select * from user_acc where userid = ? and pwd= ?";
			//$stmt = $db->prepare($q);
			//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
			//$stmt->execute();
			//mysqli_query($dbc, $q);			
	} else { 
		$comp="false";
		//print '<p class="text--error">Please make sure you enter both an email address and a password!<br>Go back and try again.</p>';

	}
} 

//else { // Display the form.
	print '
	<form action="login.php" method="post">
	<table style="margin-right:10px">
		<tr>
			<td>User ID:</td>
			<td><input type="text" name="uid" size="15" dir="ltr" required></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="pwd" size="15" dir="ltr"></td>
		</tr>
	<table>
	<hr>
	<input style="margin-right:10px" type="submit" name="submit" value="Login" class="button--general">
	</form>';
	
	if ($comp=="false"){
		print '<p align="center" class="text--error">Please ensure the username or password is correct!</p>';
	}

	if ($invuser=="true"){
		print '<p align="center" class="text--error">Please ensure the username or password is correct!</p>';
	}

//}

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