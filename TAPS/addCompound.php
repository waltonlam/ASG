<?php 
	
	include 'header2.php';
	include 'iconn.php';

//https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_popup
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
			popup.classList.toggle("show");
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

		

	print '
	
		<script>
			
				function editClick(){
					
					
					location.replace("updatelocationform.php")
				}
			
				function delClick(){
					alert("delete click");
				}
			
			
				function showSuccessAlert(){
					alert("Success");
				}
			
				
			
		</script>
	
	';
	
	
	
	$cid="";
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$success = false;
		
		if (!empty($_POST['id']) and !empty($_POST['name']) and !empty($_POST['gid']) ){
			//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))
				$cid=$_POST['id'];
						
			if ($_POST['who_tef'] == ''){
				$sql = "INSERT INTO `compound`(`id`, `name`, `who_tef`, `code`) VALUES ('".$_POST['id']."','".$_POST['name']."', NULL ,'".$_POST['gid']."')";
			}else{				
				$sql = "INSERT INTO `compound`(`id`, `name`, `who_tef`, `code`) VALUES ('".$_POST['id']."','".$_POST['name']."','".$_POST['who_tef']."','".$_POST['gid']."')";
			}
			/*if (!empty($_POST['who_tef'])){
				
				$sql = "INSERT INTO compound(id,name,who_tef) VALUES ('".$_POST['id']."','".$_POST['name']."','".$_POST['who_tef']."')";
			
			}else{
				
				$sql = "INSERT INTO compound(id,name) VALUES ('".$_POST['id']."','".$_POST['name'].")";
			}*/
			
			
			echo '<br>';
			if ($dbc->query($sql) === FALSE) {
				$success = false;
				echo "Error: " . $dbc->error;
				exit();
			}else{			

				print "<script>window.load = function(){prompt_msg();};</script>";	
			}
			
/*																															
			$sqlc= "INSERT INTO compound_map(category_id,compound_id) VALUES ('".$_POST['cid']."','".$_POST['id']."')";
			
			if ($dbc->query($sqlc)===FALSE) {
				$success = false;
				echo "Error: " . $dbc->error;
				exit();
			}else{	
				$success = true;
				 
			}
			
			if ($success === TRUE){
				 echo "<script> window.onload = function() {showSuccessAlert();}; </script>";
			}else{
				echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">New media added Not successfully</p>';
			} */

		}
			
	}
	

	
	/*
	function getMediaData(){
	
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}*/
			
			$l = "select * from category order by id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Category Configuration Error!</p>';
				exit();
			}		
			
			
			print '<h2>Add New Compound record</h2>';
			print '<div id="main-content"><br>';
			print '<form action="addCompound.php" method="post"><table>';
			
			
			//echo '<table class="table" cellspacing="0" width="100%">
					//<tr>
					//	<th>Code</th>
					//	<th>Location</th>
					//	<th>Action</th>
					//</tr>';
					

			print '<tr>
				<td >	
					<label for="lid">Category Code</label>	  				
					</td><td>
					<select  style="margin-left:10px" name="gid">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->id==$t[0]){
							  print '<option value="'.$r_l->id.'" selected>'.'('.$r_l->id.') '.$r_l->item.'</option>';}
						  else{
							 print '<option value="'.$r_l->id.'">'.'('.$r_l->id.') '.$r_l->item.'</option>';}
					};
					
			print '</select></td>';
			print '</tr>';
			
			print '<tr>';
			print '<td>';
			print '<label>ID</label></td><td>
            <input  style="margin-left:10px" type="text" name="id" required/>';
			
			print '</td>';
			print '</tr>';

			print '<tr>';
			print '<td>';
       
            print '<label>Name</label></td><td>
            <input  style="margin-left:10px" type="text" name="name" required/>';
			
			print '</td>';
			
			print '</tr>

			<tr>
			<td>
			  <label for="who_tef">WHO TEF</label>
			</td>				  				
			<td>  
				<input style="margin-left:10px" type="number" step="0.01" name="who_tef" id="who_tef"></input>
			</td></tr>';		
			
			print	'<tr>
							<td>
							</td>				
							<td>
							  <input style="margin-left:10px" class="submit" type="submit" value="Add"  />
							</td>  
						</tr>';
						
			
			print '</table>';
			
			print '</form>';
			
			print '<br></div>';if ($cid<>''){print '<div class="popup" onclick="prompt_msg()"><span class="popuptext" id="del_code">'.$cid.' has been updated successfully</span></div>';
			};


?>

 
</body>
</html>
