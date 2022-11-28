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
	//if (isset($_GET['click']) && $_GET['click'] == 'media') {
		
		//getMediaData();
		//} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
	//	 getMediaData();
		//}

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

	$suid="";	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (!empty($_POST['uid'])) {
			$suid=$_POST['uid'];
			if(isset($_POST['ronly'])){
				$u= "update uacc set ".
				"pwd='".$_POST['pwd']."'".
				",fname='".$_POST['fname']."'".
				",lname='".$_POST['lname']."'".
				",ronly=1"
				." where uid='".$_POST['uid']."';";	
			}else{
				
				$u= "update uacc set ".
				"pwd='".$_POST['pwd']."'".
				",fname='".$_POST['fname']."'".
				",lname='".$_POST['lname']."'".
				",ronly=0"
				." where uid='".$_POST['uid']."';";
			}

			if ($dbc->query($u) === TRUE) {
				// $updated=TRUE;
				//	echo '<script>alert("Update Success");
				//	window.location.href = "updateAccount.php"; </script>';
				print "<script>window.load = function(){prompt_msg();};</script>";
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

//		function getMediaData(){
	global $globalAccountArray ;
	/*
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
	
	print '<h2 style="margin-left:10px">Update Account</h2><hr>';
	print '<div>';
	print '<form action="updateAccount.php" method="post">';
	print '<table style="margin-left:10px">';

	//echo '<table class="table" cellspacing="0" width="100%">
			//<tr>
			//	<th>Code</th>
			//	<th>Location</th>
			//	<th>Action</th>
			//</tr>';
	$globalAccountArray = array();
			
		print  '<tr>
					<td>User Id: </td>
					<td>
						<select style="margin-left:10px" name="uid" id="accountSelector" onchange="updateUi(value)">';
						while ($r_l=$result_loc->fetch_object()){
								print '<option value="'.$r_l->uid.'">'.$r_l->uid.'**'.$r_l->fname.' '.$r_l->lname.'</option>';
								$accountObj = new stdClass();
								$accountObj -> uid = $r_l->uid;
								$accountObj -> pwd = $r_l->pwd;
								$accountObj -> fname = $r_l->fname;
								$accountObj -> lname = $r_l->lname;
								$accountObj -> ronly = $r_l->ronly;
								array_push($globalAccountArray, $accountObj);
						};				
		print   	    '</select>';
		print       '</td>
				</tr>			
				<tr>
				<td>Password: </td>				  				
				<td>  
					<input style="margin-left:10px" type="text" name="pwd" id="pwd"></input>
				</td>				  				
			</tr>';			
	
	print	'<tr>
				<td>
				</td>	
				<tr>
					<td>
						First name: 
					</td>	
					<td>
						<input style="margin-left:10px" type="text" name="fname" id="fname"></input>
					</td>
				<tr>		
					<td>
						Last name: 
					</td>	
					<td>
						<input style="margin-left:10px" type="text" name="lname" id="lname"></input>
					</td>
				</tr>
				<tr>
					<td>
						Read only: 
					</td>
					<td>
						<input style="margin-left:10px"  type="checkbox" name="ronly" value="" id="ronly">
					</td>	
				</tr>	
			</table><hr><input style="margin-left:10px" class=button--general type="submit" value="Update">';

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
	
	print '</table></div>';

	echo "<script> window.onload = function() {selectFirstItem();}; </script>";
	if ($suid<>''){ 
		print '<div class="popup" onclick="prompt_msg()"><span class="popuptext" id="del_code">'.$suid.' has been updated successfully</span></div>';
	};

//		}
		include 'footer.html';

?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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

			function selectFirstItem(){
				// document.getElementById("compoundSelector").selectedIndex = 0;
				updateUi(document.getElementById("accountSelector").value);
			}

			function updateUi(str){
				var jArray = <?php echo json_encode($globalAccountArray); ?>;
				var json_string = JSON.stringify(jArray);

				// alert(json_string);
				$.ajax({
				    url:"updateAccountProcess.php",
				    method:"POST", //First change type to method here
				    data:{
				        "uid": str, // Second add quotes on the value.
				        "allAccount":json_string
				    },
				    success:function(response) {
			        	// alert(response);
						var obj = JSON.parse(response);
						//console.log(obj["uid"] + "  |  "+obj["pwd"]+ "  |  "+obj["fname"]+ "  |  "+obj["lname"] + "  |  "+obj["ronly"]);

						if (obj["uid"] == str) {
							document.getElementById("fname").value = obj["fname"];
							document.getElementById("lname").value = obj["lname"];
							if (obj["ronly"] == 1) {
								document.getElementById("ronly").checked = true;
							}else{
								document.getElementById("ronly").checked = false;
							}
							
						}
				        	
				    },
				    error:function(){
				    	alert("error");
				    }
				});
		}
		</script>