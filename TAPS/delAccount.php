
<?php  
	include ('iconn.php');
	include 'header2.php';
	
	print'<h2>Delete Account</h2>';
	print '
	<style>
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
	

	$suid='';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{		
		if (!empty($_POST['uid']))	
		{
			$suid = $_POST['uid'];
			$u= "delete from uacc where uid='{$suid}';";
					
			if ($dbc->query($u) === TRUE) {
				// $updated=TRUE;
				
//				echo '<script>alert("Delete Success");
//				window.location.href = "delAccount.php"; </script>';

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
		function getLoginData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}
*/
	$l = "select * from uacc order by uid ASC;";
	//print $q;
	$result_loc=$dbc->query($l);
	if (!$result_loc->num_rows){
		print '<p class="text--error">'.'Account Configuration Error!</p>';
		exit();
	}		
	
	
	
	print '<body><div id="main-content"><form action="delAccount.php" method="POST">';
	
	echo '<br><table><br>';
			
			//echo '<table class="table" cellspacing="0" width="100%">
					//<tr>
					//	<th>Code</th>
					//	<th>Location</th>
					//	<th>Action</th>
					//</tr>';
					
					
			print '<tr style="color:#555555;">
				<td>	
					<label for="lid">Account</label></td>	  				
					<td>
					<select  style="margin-left:10px" name="uid" id="uidSelector">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->uid==$t[0]){
							  print '<option value="'.$r_l->uid.'" selected>'.$r_l->uid.'**'.$r_l->fname.' '.$r_l->lname.'</option>';}
						  else{
							 print '<option value="'.$r_l->uid.'">'.$r_l->uid.'**'.$r_l->fname.' '.$r_l->lname.'</option>';}
					};				
			
			print	'
							<td>
							  <input class=button--general style="margin-left:10px" type="submit" value="Delete" >
							</td>  </tr><tr><td><br><br></td></tr>
						</table></form></div>						';

				if ($suid<>''){ 
					print '<div class="popup" onclick="myFunction()"><span class="popuptext" id="del_code">'.$suid.' has been deleted successfully</span></div>';
				};	
	
				print '</body>';

//		}
		
		
		
		include 'footer.html';

?>
		

		
		
	
