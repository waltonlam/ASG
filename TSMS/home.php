<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Home');
include('templates/header.html');
include('templates/iconn.php');

// Print some introductory text:
//print '<h2 dir="ltr" style="margin-left: 465px; margin-right: 80px">用戶登入</h2>';
print '<div align=center style="background-color:#E8E8E8"><br><h2 align="center" class="logo" style="background-color:#E8E8E8">功能選項<br><br></h2>';

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];


if (empty($_SESSION['vuserid'])) {
		print '<p align="center" class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();

	} 
/*	
	else{
	
			$dbc = $_SESSION['dbc'];
			$q = "select * from user_acc where userid = '".$_SESSION['vuserid']."'";
			print '$q = '.$q;
			
			$result=$dbc->query($q);
			$r=$result->fetch_object();
			print "Email address is : ".$r->email;
		}
*/

    					
				print '<table style="align:center"><tr>
				<td><img id="hse" src="img/housing.png"  width="200" height="200" style="cursor: pointer"></td>
				<td><img id="kbs" src="img/kerbside.png"  width="200" height="200" style="cursor: pointer"></td>
				<td><img id="cgs" src="img/station.png"  width="200" height="200" style="cursor: pointer"></td>
				<td><img id="ol" src="img/outlet.png"  width="200" height="200" style="cursor: pointer"></td>
				<td><img id="edu" src="img/edu.png"  width="200" height="200" style="cursor: pointer"></td>
				<td><img id="fc" src="img/fc.png"  width="200" height="200" style="cursor: pointer"></td>
				<td><img id="ua" src="img/ua.png"  width="200" height="200" style="cursor: pointer"></td>
				<tr><td><img id="hse_m" src="img/hse_main.png"  width="200" height="150" style="align:center; cursor: pointer"></td>
				<td><img id="kb_m" src="img/kb_main.png"  width="200" height="150" style="cursor: pointer"></td>
				<td><img id="cgs_m" src="img/cgs_main.png"  width="200" height="150" style="cursor: pointer"></td>
				</tr>
				</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
  </div><br><br><br><p></p><p></p><p></p>';

/*
        <button id="hse" style="border-radius:25px;height: 80px;width: 150px;font-size:20px;border:3px #00CCCC solid;background-color:white;color:blue;cursor: pointer">屋苑/機構</button>
        <button id="kbs" style="border-radius:25px;height: 80px;width: 150px;font-size:20px;border:3px #00CCCC solid;background-color:white;color:blue;cursor: pointer">街站</button>
        <button id="cgs" style="border-radius:25px;height: 80px;width: 150px;font-size:20px;border:3px #00CCCC solid;background-color:white;color:blue;cursor: pointer">CGS</button>
        <button id="ol" style="border-radius:25px;height: 80px;width: 150px;font-size:20px;border:3px #00CCCC solid;background-color:white;color:blue;cursor: pointer">下游</button>
        <button id="edu" style="border-radius:25px;height: 80px;width: 150px;font-size:20px;border:3px #00CCCC solid;background-color:white;color:blue;cursor: pointer">教育活動</button>
        <button id="ua" style="border-radius:25px;height: 80px;width: 150px;font-size:20px;border:3px #00CCCC solid;background-color:white;color:red;cursor: pointer">帳戶管理</button>      
*/

print '<script type="text/javascript">
    document.getElementById("hse").onclick = function () {
        location.href = "housing.php";
    };

    document.getElementById("kbs").onclick = function () {
        location.href = "kerbside.php";
    };

    document.getElementById("cgs").onclick = function () {
        location.href = "station.php";
    };

    document.getElementById("ol").onclick = function () {
        location.href = "out_trans.php"
    };

    document.getElementById("edu").onclick = function () {
        location.href = "edu_event.php"
    };
    
    document.getElementById("fc").onclick = function () {
        location.href = "fc_trans.php"
    };

    document.getElementById("ua").onclick = function () {
        location.href = "ua.php"
    };    

    document.getElementById("hse_m").onclick = function () {
        location.href = "house_m.php"
    };    

    document.getElementById("kb_m").onclick = function () {
        location.href = "kerbside_m.php"
    };    

    document.getElementById("cgs_m").onclick = function () {
        location.href = "station_m.php"
    };    

</script>';



include('templates/footer.html'); // Need the footer.


?>