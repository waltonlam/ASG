<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'New Location');
include('templates/header.html');
include('templates/iconn.php');


if (empty($_SESSION['vuserid'])) {
	print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
	exit();
} else{
	if ($_SESSION['utp']=='R'){
		print '<p class="text--error">Access Deny</p>';		
		exit();
	}

		/*
		$q = "select d.district_id did, d.name dname, s.station_id sid, s.name sname
				from user_district ud, district d, station s 
				where s.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";
		$result=$dbc->query($q);
		if (!$result->num_rows){
				print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
				exit();
		}				
			//$q = "select * from user_acc where userid = ? and pwd= ?";
			//$stmt = $db->prepare($q);
			//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
			//$stmt->execute();
			//mysqli_query($dbc, $q);			
		*/			
}


$created=FALSE;
$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['lid']) and !empty($_POST['lname']) )	
		//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
	{
			$q = "insert into loc (loc_id,name) 
						values ('".$_POST['lid']."','".$_POST['lname']."');";

				//date('Y/m/d', $_SESSION['trans_date'])    					
				//$mysqli->autocommit(FALSE);				
				//$dbc->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);  
					if ($dbc->query($q) === FALSE) {
					    echo "Error: " . $dbc->error;
					    exit();
						}else{					
					    		$created=TRUE;
					    	}
																															
				} else {
						//print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
						$criteria = "";
						$comp="false";								
	
				    //echo "Error-> " . $q . "<br>" . $dbc->error;
//				    exit();
					}
}					
				//$dbc->commit();
//					$dbc->autocommit(TRUE);

print '
<hr>
	<div class="topnav">
    <a href="imp.php">Data Template Input</a>
		<a href="loc_new.php">Add Loc</a>
		<a href="update_loc_m.php">Edit Loc</a>
		<a href="del_loc.php">Delete Loc</a>
		<a href="egp_new.php">Add Param Group</a>
		<a href="update_gp_m.php">Edit Param Group</a>
		<a href="del_gp.php">Delete Param Group</a>
		<a href="ele_new.php">Add Param</a>
		<a href="update_ele_m.php">Edit Param</a>
		<a href="del_ele.php">Delete Param</a>
		<a href="ua_new.php">Add UAC</a>
		<a href="update_ua_m.php">Edit UAC</a>
        <a href="del_ua.php">Delete UAC</a>
        <a href="logout.php">Logout</a>;
	</div><hr>'; 
	
print '

<form onsubmit="submitForm()" action="loc_new.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">New Location</p>
      
      <table style="width:92%;margin-left:2%;">		     	
			<tr style="color:#555555;">';
				  /*
				 	while ($r_ud=$result_ud->fetch_object()){
				    print '<option value="'.$r_ud->did.'">'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';
				  };
				  */			
print '</td></tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="sid">Location Code</label>
				</td>
				<td>
				  <input style="margin-left:10px" type="text" name="lid" id="lid"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="sname">Location Name</label>
				</td>
				<td>
				  <input style="margin-left:10px" type="text" name="lname" id="lname"></input>
				</td>				 	
			</tr>													
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="Add New">
				</td>  
			</tr></table></form>';



print'
  <span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="sampletx.php">Home</a></span></body>

  </html>';


	if ($comp=="false"){
			print '<p align="center" class="text--error">Please enter the missing entry!</p>';
			}else{
						if ($created===TRUE){					
				echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">New Location added successfully</p>';}
					}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}





include('templates/footer.html'); // Need the footer.




?>

