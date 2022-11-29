<?php  
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
	print '<html><head>
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
			
			function notSuccessAlert(ent){
				var msg = "Cannot be deleted! Dependencies Exists("+ent+")";
				alert(msg);
			}


			function myFunction() {
				var popup = document.getElementById("del_code");
				popup.classList.toggle("show");
			}			

			
		</script></head>
	';

		$code = '';

		 if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$code = $_POST['code'];

			if (!empty($_POST['code']) ){
					
				$search = "select site from glab_curr where site = '{$_POST['code']}'";
				$result_curr=$dbc->query($search);
				if ($result_curr->num_rows){
					echo "<script> window.onload = function() {notSuccessAlert('CURRENT');}; </script>";
					exit();
				}

/*
				if ($dbc->query($sql) === FALSE) {
					$success = false;
					echo "Error: " . $dbc->error;
					exit();
				}else{			
					$success = true;
				
				}
*/

				$search = "select site from glab_rev_h where site = '{$_POST['code']}'";
				$result_rev_h=$dbc->query($search);
				if ($result_rev_h->num_rows){
					echo "<script> window.onload = function() {notSuccessAlert('HISTORY');}; </script>";
					exit();
				}

				$search = "select distinct site from glab_template where site = '{$_POST['code']}'";						
				$result_template=$dbc->query($search);
				if ($result_template->num_rows){
					echo "<script> window.onload = function() {notSuccessAlert('TEMPLATE');}; </script>";
					exit();
				}
		

				$u = "delete from site where code = '{$_POST['code']}';";
				if ($dbc->query($u) === TRUE) {
					//echo '<script>alert("Delete Successfully");
					//window.location.href = "delSite.php"; </script>';
					//print '<script> window.onload = function() {showSuccessAlert();}; </script>';

					//print '<body onload="showSuccessAlert()">';
					//print 'deleted';

					print "<script>window.onload = function(){myFunction();};</script>";
				}else{
					echo "Error: " . $dbc->error;
				}
				
				//print '<p class="text--error">'.'category Configuration Error!</p>';
				//print "No information";

			}else{
				echo "<script> window.onload = function() {notSuccessAlert();}; </script>";
				
			}

		}
		
//		getLocationData(); 

		 

//		function getLocationData(){
/*			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}
*/
			$l = "select * from site order by code ASC;";
			$result_loc=$dbc->query($l);

			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Location Configuration Error!</p>';

				exit();
			}		
			
			
	print'<h2 style="margin-left:10px">Delete Site</h2><hr>';
			print '<body><div>
			<form action="delSite.php" method="post">';
			
			//echo '<table class="table" cellspacing="0" width="100%">
					//<tr>
					//	<th>Code</th>
					//	<th>Location</th>
					//	<th>Action</th>
					//</tr>';

			print '<table style="margin-left:10px"><tr>
				<td><label>Site: </label></td>
				<td>
					<select style="width:100%; margin-left:10px;" name="code" id="code">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->code==$t[0]){
							  print '<option value="'.$r_l->code.'" selected>'.$r_l->code.'**'.$r_l->location.'</option>';}
						  else{
							 print '<option value="'.$r_l->code.'">'.$r_l->code.'**'.$r_l->location.'</option>';}
					};				

			print	'</td>
					</tr>';

			 //while ($row =$result_loc->fetch_assoc()){
				 
				//echo "<tr>";
				//echo "<td id='code'>" . $row["code"]."</td>";
				//echo "<td id='location'>" . $row["location"]."</td>";
				//echo "<td>";
				
				
				//echo "<input id='btn-edit'  type='submit' value='Update'>";
				
				//echo "<a class='btn btn-edit btn-sm' href='updateLocation.php?code=". $row["code"]."& location=". $row["location"]."'  >EDIT</a> ";
				//echo "<a class='btn btn-delete btn-sm'    href='location.php?del_Item=". $row["code"]."'>Delete</a> ";
				//echo "</td>";
				//echo "</tr>";
				 
				 
			 //}

			echo '</table><hr><input name="submit" class="button--general btn submit" style="margin-left:10px" type="submit" value="delete">
			</form></div>';
			
			if ($code<>''){ 
				print '<div class="popup" onclick="myFunction()"><span class="popuptext" id="del_code">'.$code.' has been deleted successfully</span></div>';
			};			
			
			print '</body></html>';
//		}
		include 'footer.html';
?>
		

		
		
	
