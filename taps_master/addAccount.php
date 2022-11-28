<?php 
	include 'header2.php';
	include 'iconn.php';
	
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
	
	$uid='';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (!empty($_POST['uid']) and !empty($_POST['pwd']) ){
			//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
				
			if(empty($_POST['ronly'])){
				$sql = "INSERT INTO uacc(uid,pwd,fname,lname,ronly) VALUES ('".$_POST['uid']."','".$_POST['pwd']."','".$_POST['fname']."','".$_POST['lname']."',0)";	
			}else{
				$sql = "INSERT INTO uacc(uid,pwd,fname,lname,ronly) VALUES ('".$_POST['uid']."','".$_POST['pwd']."','".$_POST['fname']."','".$_POST['lname']."',1)";
			
			}
			$uid=$_POST['uid'];
			//$sql = "INSERT INTO uacc(uid,pwd,fname,lname,ronly) VALUES ('".$_POST['uid']."','".$_POST['pwd']."','".$_POST['fname']."','".$_POST['lname']."','".$_POST['ronly']."')";
			
			if ($dbc->query($sql) === FALSE) {
				echo "Error: " . $dbc->error;
				exit();
			}else{					
				  //echo "<script> window.onload = function() {showSuccessAlert();}; </script>";
				  print "<script>window.load = function(){prompt_msg();};</script>";	
			}
		}																									
	}
?>

	<h2 style="margin-left:10px">Add Account</h2>
	<hr>
	<body>
		<div>
			<form action="addAccount.php" method="post">
				<table style="margin-left:10px">
					<tr>
						<td>		
							<label>User ID:</label>
						</td>
						<td>
							<input style="margin-left:10px" type="text" name="uid" required>
						</td>
					</tr>
					<tr>
						<td>
							<label>Password:</label>
						</td>
						<td>
							<input style="margin-left:10px" type="text" name="pwd" required>
						</td>
					</tr>
					<tr>
						<td>
							<label>First name:</label>
						</td>
						<td>
							<input style="margin-left:10px" type="text" name="fname">
						</td>
					</tr>
					<tr>
						<td>
							<label>Last name:</label>
						</td>
						<td>
							<input style="margin-left:10px" type="text" name="lname">
						</td>
					</tr>
					<tr>
						<td>
							<label>Read only:</label>
						</td>
						<td>
							<input style="margin-left:10px" type="checkbox" name="ronly">
						</td>
					</tr>
				</table>
				<br>
				<hr>
				<input style="margin-left:10px" class="submit" type="submit" value="Add"  />
			</form>
		</div>
	<?php
		if ($uid<>''){ 
			print '<div class="popup" onclick="prompt_msg()"><span class="popuptext" id="del_code">'.$uid.' has been added successfully</span></div>';
		};
	?>
	</body>
</html>