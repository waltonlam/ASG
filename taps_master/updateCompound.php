<?php 
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Update Compound');
include ('iconn.php');
include 'header2.php';

if (isset($_SESSION['previous'])) {
	if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
		 //session_destroy();
		 unset($_SESSION['site_id']);
		 unset($_SESSION['dateFrom']);
		 unset($_SESSION['dateTo']);
		 ### or alternatively, you can use this for specific variables:
		 ### unset($_SESSION['varname']);
	}
}

if (isset($_SESSION['prev_incid'])) {
	if (basename($_SERVER['PHP_SELF']) != $_SESSION['prev_incid']) {
		 //session_destroy();
		 unset($_SESSION['site_code_inc']);
		 unset($_SESSION['dateFrom_inc']);
		 unset($_SESSION['dateTo_inc']);
		 ### or alternatively, you can use this for specific variables:
		 ### unset($_SESSION['varname']);
	}
}

//https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_popup
print '
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

/* Popup container - can be anything you want */
.popup {
  position: relative;
  display: inline-block;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* The actual popup */
.popup .popuptext {
  visibility: hidden;
  width: 380px;
  background-color: #555;
  color:yellow;
  text-align: center;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 999;
  /*bottom: 125%;*/
  /*left: 50%;*/
  margin-left: 20%;
}

/* Popup arrow */
.popup .popuptext::before {
/* 
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: 60px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
*/ 

/* https://stackoverflow.com/questions/23761575/how-to-change-position-of-tooltip-arrow
http://jsfiddle.net/qCPQm/
*/
  display: block;
  content:"";
  position: absolute;
  top: 50%;
  margin-top:-6px;
  left: 10%;
  width: 0;
  height: 0;
  border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-left: 6px solid yellow;  /*orientation - (border-right)*/


/* Top arrow 
  https://codepen.io/GreeCoon/pen/OpGjLM

  content: "";
  position: absolute;
  display: block;    
  width: 0px;        
  left: 50%;
  top: 0;
  border: 15px solid transparent;
  border-top: 0;
  border-bottom: 15px solid #5494db;
  transform: translate(-50%, calc(-100% - 5px));	  
*/

}


/* https://codepen.io/imacrab/pen/PXGqGK
Different arrow positioning
*/
.popup.arrow-top:before {
  left: calc(50% - 10px);
  top: -8px;
}
.popup.arrow-right:before {
  top: calc(50% - 10px);
  right: -8px;
}
.popup.arrow-bottom:before {
  left: calc(50% - 10px);
  bottom: -8px;
}
.popup.arrow-left:before {
  top: calc(50% - 10px);
  left: -8px;
}


/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s;
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}
</style>';

print '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
	<script  type="text/javascript">
		/*https://stackoverflow.com/questions/9434/add-multiple-window-onload-events  
		*/
		if (window.addEventListener) // W3C standard
		{
		window.addEventListener("load", prompt_msg, false);  /* NB **not** "onload"*/
		} 
		else if (window.attachEvent) // Microsoft
		{
		window.attachEvent("onload", prompt_msg);
		}

		function editClick(){
			
			
			location.replace("updatelocationform.php")
		}
	
		function delClick(){
			alert("delete click");
		}
	
		function showSuccessAlert(){
			alert("Delete Successfully");
		}
		
		function showConfirmAlert(){
			
			var answer = confirm ("Confirm to delete this record?")
			if (answer){
				
				
			}
		}

		function prompt_msg() {
			var popup = document.getElementById("del_code");
			if(popup){
				popup.classList.toggle("show");
			}
		}			
		
		function Showparam() {
			var e = document.getElementById("cid");
			var str = e.options[e.selectedIndex].innerHTML;
			var info = str.split("**");

			var grp = document.getElementById("gid");
			grp.options[grp.options.selectedIndex].selected = true;

			document.getElementById("cp_name").value = info[1];
			document.getElementById("gid").value = info[2];
			document.getElementById("who_tef").value = info[3];
		}
	</script>';

	print '<script>
	function showprg(str) {
	
	//	alert("str="+str);
		if (str == "") {
			document.getElementById("new_prg").innerHTML = "";
			return;
		} else { 
			if (window.XMLHttpRequest) {
	//        	alert("XMLHttpRequest");
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
	//                    alert("this.readyState == 4, this.responseText="+this.responseText);
					document.getElementById("new_prg").innerHTML = this.responseText;
				}else{
	//            	alert("this.readyState <> 4.....,this.readyState="+this.readyState+"..."+"status="+this.status);
	//            	alert("this.responseText="+this.responseText);
					}
			};
	//        alert("xmlhttp.open");
			xmlhttp.open("GET","fetch.php?q="+str,true);
			xmlhttp.send();
			
		}
	}
	</script>';

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


	/*
	print '<script>function check_num() {
		alert(document.getElementById("other_qty").innerHTML);
				document.getElementById("other_qty").focus();
			return false;

			if (document.getElementById("other_qty").innerHTML<>""){
				if (!is_numeric(document.getElementById("other_qty").innerHTML)){
				alert("Please enter the number");
				document.getElementById("other_qty").focus();
			}
			}
	};
	*/

	// Print some introductory text:

	//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

	// Check if the form has been submitted:

	//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

	//echo "This is a valid user: ".$_SESSION['vuserid'];

	//$updated=FALSE;
	$comp="true";
	$invq="false";
	$comp_id='';				
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (!empty($_POST['gid']) and !empty($_POST['cid'])){
			$comp_id = $_POST['cid'];
			$u= "update compound set "
			."name='".$_POST['cp_name']."',who_tef=".$_POST['who_tef'].",code='".$_POST['gid']."'"
			." where id='".$_POST['cid']."';";
			
			//print "<script>window.onload = function(){prompt_msg();};</script>";
			//$dbc->autocommit(FALSE);
			if ($dbc->query($u) === TRUE) {
				// $updated=TRUE;
				//  echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">Compound has been updated</p>
				//  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="sampletx.php">Home</a></span>';
				
				print "<script>window.load = function(){prompt_msg();};</script>";
				//print "<script>alert('response')</script>";
			}else{
				echo "Error: " . $dbc->error;
				exit();
			};
		}else{
			print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
			$criteria = "";
			$comp="false";				
		}	
	}

	/*
	if (empty($_SESSION['vuserid'])) {
			print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
			exit();
		} else{
						if (empty($_REQUEST['cluster'])) {
								print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		

						}else{

								$token = strtok($_REQUEST['cluster'], "|");
								
									$i=0; 
									while ($token !== false)
								{
									$t[$i]=$token;
								//echo "$token<br>";
									$token = strtok("|");
									$i++;
								}
								if ($t[5]=="@"){$t[5]="";} 

						}		
						
						$q = "select d.district_id did, d.name dname
								from user_district ud, district d
							where ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";

						//print $q;
						$result_ud=$dbc->query($q);
						if (!$result_ud->num_rows){
								print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
								exit();
						}				

						$q = "select *
								from house_type;";
						//print $q;
						$result_hse_t=$dbc->query($q);
						if (!$result_hse_t->num_rows){
								print '<p class="text--error">'.'House Type Configuration Error!</p>';
								exit();
						}				



					}
	*/				
	
	$l = "select id as gid, item 
	from category;";
	//print $q;
	$result_g=$dbc->query($l);
	if (!$result_g->num_rows){
	print '<p class="text--error">'.'Compound Group Configuration Error!</p>';
	exit();
	}		
							
	$l = "select * from compound;";
	//print $q;
	$result_cp=$dbc->query($l);
	if (!$result_cp->num_rows){
	print '<p class="text--error">'.'Compound Configuration Error!</p>';
	exit();
	}		

	print '<body onload="Showparam()"><h2 style="margin-left:10px">Update Compound</h2><hr><div>
	<form action="updateCompound.php" method="post">
		<table style="margin-left:10px">';	
	print '<tr>
		<td><label>Compound: </label><td>
			<select style="width:100%; margin-left:10px;" name="cid" id="cid" onchange="Showparam()">';
				while ($r_cp=$result_cp->fetch_object()){
					/*
					if ($r_e->eid==$t[0]){
						print '<option value="'.$r_e->ele_id.'" selected>'.$r_e->ele_id.'**'.$r_e->name.'**'.$r_e->unt.'**'.$r_e->gcde.'</option>';}
					else{
					print '<option value="'.$r_e->ele_id.'">'.$r_e->ele_id.' > '.$r_e->name.'**'.$r_e->unt.'**'.$r_e->gcde.'</option>';}
					*/
					print '<option value="'.$r_cp->id.'">'.$r_cp->id.'**'.$r_cp->name.'**'.$r_cp->code.'**'.$r_cp->who_tef.'</option>';
				};			  

	print '</select></td><tr>
					<td><label>Compound Group: </label><td>
						<select style="width:100%; margin-left:10px;" name="gid" id="gid">';
						while ($r_l=$result_g->fetch_object()){
							/*
							if ($r_l->gid==$t[0]){
								print '<option value="'.$r_l->gid.'" selected>'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
							else{
								print '<option value="'.$r_l->gid.'">'.'('.$r_l->gid.') '.$r_l->gname.'</option>';}
								*/

							print '<option value="'.$r_l->gid.'">'.'('.$r_l->gid.') '.$r_l->item.'</option>';
						};					

	print '</select></td></tr>			
				<tr>
					<td><label>Compound Name: </label></td>				  				
					<td>  
						<input style="width:100%; margin-left:10px;" type="text" name="cp_name" id="cp_name"></input>
					</td>				  				

				</tr>			
				<!--tr>
					<td><label>WHO TEF: </label></td>				  				
					<td>  
						<input style="width:100%; margin-left:10px;" type="number" step="0.01" name="who_tef" id="who_tef"></input>
					</td>				  				
				</tr-->';			

	print	'</table><hr><input class=button--general style="margin-left:10px" type="submit" value="Update"></form></div>';


	if ($comp_id<>''){ 
		print '<div class="popup" onclick="prompt_msg()"><span class="popuptext" id="del_code">'.$comp_id.' has been updated successfully</span></div>';
	};				
	print '</body>';

	/*
	mysqli_free_result($result_ud);
	mysqli_free_result($result_hse_t);
	*/

	/*
	print '<table style="background-color:white;border-radius:8px;border:none;width:98%;margin-left:1%;">
	<tr>
	<td  style="border:none;text-align:center;">
		<br>
		<h3 style="display:block;margin-left:1%;padding-right:4px;color:#05CDB9;text-align:left;">&nbsp;????????????:</h3>
		<hr style="border:0.2px solid grey;">
		<form  method="post" action="">
			??????
			<select name="category">
				<option value="">-----</option>
				<option value="SEQ_ID">????????????</option>
				<option value="DATE">??????</option>
			</select>
			??????
			<input style="width:15%;"type="text" name="crit">????????????????????????
			<button type="submit" class="btn btn-default">????????????</button>
		</form>
		<br>
	</td>
	</tr>
	</table>';
	*/
	//print'
	// <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="sampletx.php">Home</a></span>';

	if ($comp=="false"){
		print '<p align="center" class="text--error">Please input compound</p>';}

	if ($invq=="true"){
		print '<p align="center" class="text--error">No record found</p>';}

	function check_empty($x){
		//print '$x='.$x;
		if ($x==''){
			print "return 0";
			return 0;		
			}else{
				return $x;
			}
			//	print "nothing";
	}	
	include('templates/footer.html'); 
?>