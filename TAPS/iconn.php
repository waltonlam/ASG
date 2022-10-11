<?php 

 //CGS - ASG comment -----------------------------------------------------------
 /* if (!$dbc = new mysqli('localhost', 'cgs', 'alcohol', 'cgs'))
	{
		print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
		exit();
	}
 */
 //-----------------------------------------------------------------------------

 //$link = mysqli_connect("127.0.0.1", "my_user", "my_password", "my_db");
	if (!$dbc = new mysqli('127.0.0.1', 'root', 'pass1234', 'taps'))
	{
		print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
		exit();
	}


//mysqli_set_charset($dbc, 'utf8');