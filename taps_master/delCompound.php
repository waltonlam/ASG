
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
	
	print'<h2>Delete Compound</h2><hr>';
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
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script  type="text/javascript">
			
				
			
				function delClick(){
					alert("delete click");
				}
			
				function notSuccessAlert(){
					alert("Cannot delete! it is because there are some compounds which include to this category");
				}
			
				function showSuccessAlert(){
					alert("Success");
				}
			
				function myFunction() {
					var popup = document.getElementById("del_code");
					popup.classList.toggle("show");
				}	
			
		</script>
	
	';

		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){

		 //}
		
	$scode='';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{

		if (!empty($_POST['id']))	
		{
			$scode = $_POST['id'];
				
			$u= "delete from compound where id='{$scode}';";
			
			//$cu= "delete from compound_map where compound_id='{$scode}';";
			
			//if ($dbc->query($u) === TRUE and $dbc->query($cu) === TRUE ) {
				// $updated=TRUE;
			if ($dbc->query($u) === TRUE) {
										
				//echo '<script>alert("Delete Success");
				//window.location.href = "delCompound.php"; </script>';
				print "<script>window.onload = function(){myFunction();};</script>";				
			}else{
				echo "Error: " . $dbc->error;
				exit();
			};
					
		}else{
			print '<p class="text--error">There is no information for deletion<br>Go back and try again.</p>';		
			$criteria = "";
			$comp="false";				

		}			
	} 
 
/*
		function getMediaData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}
*/
			$l = "select * from compound order by id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Location Configuration Error!</p>';
				exit();
			}		

			print '<body><div><form action="delCompound.php" method="post">';
			
			echo '<br><table>';
			
			//echo '<table class="table" cellspacing="0" width="100%">
					//<tr>
					//	<th>Code</th>
					//	<th>Location</th>
					//	<th>Action</th>
					//</tr>';
					
					
			print '<tr style="color:#555555;">
				<td>Compound Name: </td>	  				
					<td>
					<select style="margin-left:10px" name="id">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->id==$t[0]){
							  print '<option value="'.$r_l->id.'" selected>'.$r_l->id.'**'.$r_l->name.'</option>';}
						  else{
							 print '<option value="'.$r_l->id.'">'.$r_l->id.'**'.$r_l->name.'</option>';}
					};				
			
			print	'</td> 
					</tr></table><br><hr><input class=button--general style="margin-left:10px" type="submit" value="Delete">';
						
			if ($scode<>''){ 
				print '<div class="popup" onclick="myFunction()"><span class="popuptext" id="del_code">'.$scode.' has been deleted successfully</span></div>';
			};				
			
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
			echo '</table></form></div><body>';
//		}
		include 'footer.html';
?>
		

		
		
	
