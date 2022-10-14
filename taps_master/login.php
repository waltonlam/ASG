<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
$_SESSION['vusername'] = "-";
$_SESSION['utp'] = "";
define('TITLE', 'Login');
include('templates/header.html');
include('iconn.php');

// Print some introductory text:
//print '<h2 dir="ltr" style="margin-left: 465px; margin-right: 80px">用戶登入</h2>';
print '<h2 align="center">Login</h2>';

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
				//print $_SESSION['utp'];
				//$_SESSION['dbc'] = $dbc;
				//echo "This is a valid user: ".$_SESSION['vusername'];
				header("location:exportReport.php");
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

	print '<form action="login.php" method="post">
	<p align="center" style="margin-right:18px">
	<font size="5">Username:<input type="text" name="uid" size="20" dir="ltr" style="margin-left: 8px;" required>
	</br>
	<p align="center" style="margin-right:18px">
	<font size="5">Password:<input type="password" name="pwd" size="20" dir="ltr" style="margin-left: 8px;" >
	</p>
	<p align="center">
	<input type="submit" name="submit" value="Submit" class="button--general">
	</p>
	</form>';
	
	if ($comp=="false"){
				print '<p align="center" class="text--error">Please ensure the username or password is correct!</p>';}

	if ($invuser=="true"){
				print '<p align="center" class="text--error">Please ensure the username or password is correct!</p>';}


//}

include('templates/footer.html'); // Need the footer.
?>