<?php  
	include ('iconn.php');
	include 'header2.php';
	
	//if (isset($_GET['click']) && $_GET['click'] == 'media') {
	//getMediaData();
	//} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
		getFactorData();
	//}
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (!empty($_POST['compound'])){				
			$u= "update factor set "
			."compound='".$_POST['compound']."'";
			if(!empty ($_POST['who_tef_1998'])){
				$u .= ",who_tef_1998='".$_POST['who_tef_1998']."'";
			}		
			
			if(!empty ($_POST['who_tef_2005'])){
				$u .= ",who_tef_2005='".$_POST['who_tef_2005']."'";
			}

			if(!empty ($_POST['i_tef'])){
				$u .= ",i_tef='".$_POST['i_tef']."'";
			}

			if(!empty ($_POST['who_tef'])){
				$u .= ",who_tef='".$_POST['who_tef']."'";
			}

			$u .=" where compound='".$_POST['compound']."';";
			
			if ($dbc->query($u) === TRUE) {
				// $updated=TRUE;
				echo '<script>alert("Update Success")</script>';
				header("Refresh:0");
			}else{
				echo "Error: " . $dbc->error;
				exit();
			};
			echo "<meta http-equiv='refresh' content='0'>";
		}else{
			print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
			$criteria = "";
			$comp="false";				
		}
		exit();				
	} 

	function getFactorData(){
		if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
		{
			print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
			exit();
		}

		$l = "select * from factor order by compound ASC;";
		//print $q;
		$result_loc=$dbc->query($l);
		if (!$result_loc->num_rows){
			print '<p class="text--error">'.'Factor Configuration Error!</p>';
			exit();
		}		

		print '<h2 style="margin-left:10px">Update Factor</h2><hr>';
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
		</style>';
		
		print '<body onload="Showparam()"><form action="updateFactor.php" method="post">';
		
		echo '<table style="margin-left:10px">';
		
		//echo '<table class="table" cellspacing="0" width="100%">
				//<tr>
				//	<th>Code</th>
				//	<th>Location</th>
				//	<th>Action</th>
				//</tr>';
					
		print '<tr>
				<td>	
					<label>Factor Code: </label> 				
				<td>
				<select style="width:100%; margin-left:10px;" name="compound" id="compound" onchange="Showparam()">';
					while ($r_l=$result_loc->fetch_object()){
						if ($r_l->lid==$t[0]){
							print '<option value="'.$r_l->compound.'" selected>'.'('.$r_l->compound.') '.'**'.$r_l->who_tef_1998.'**'.$r_l->who_tef_2005.'**'.$r_l->i_tef.'**'.$r_l->who_tef.'</option>';}
						else{
							print '<option value="'.$r_l->compound.'">'.'('.$r_l->compound.') '.'**'.$r_l->who_tef_1998.'**'.$r_l->who_tef_2005.'**'.$r_l->i_tef.'**'.$r_l->who_tef.'</option>';}
				};				
				
		print '</td></tr>			
				<tr>
					<td>
						<label>WHO-TEF-1998: </label> 
					</td>				  				
					<td>  
						<input style="width:100%; margin-left:10px;" type="text" name="who_tef_1998" id="who_tef_1998"></input>
					</td>				  				
				</tr>
				<tr>
					<td>
						<label>WHO-TEF-2005: </label> 
					</td>				  				
					<td>  
						<input style="width:100%; margin-left:10px;" type="text" name="who_tef_2005" id="who_tef_2005"></input>
					</td>				  				
				</tr>
				<tr>
					<td>
						<label>I-TEF: </label> 
					</td>				  				
					<td>  
						<input style="width:100%; margin-left:10px;" type="text" name="i_tef" id="i_tef"></input>
					</td>				  				
				</tr>
				<tr>
					<td>
						<label>WHO-TEF: </label> 
					</td>				  				
					<td>  
						<input style="width:100%; margin-left:10px;" type="text" name="who_tef" id="who_tef"></input>
					</td>				  				
				</tr>';			
		
		print '</table><hr><input style="margin-left:10px" type="submit" value="Update">';
		
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

		echo '</table></form><body>';
	}
	include 'footer.html';
?>
		
<script>
	function Showparam() {
		var e = document.getElementById("compound");
		var str = e.options[e.selectedIndex].innerHTML;
		var info = str.split("**");

		var grp = document.getElementById("compound");
		grp.options[grp.options.selectedIndex].selected = true;

		document.getElementById("who_tef_1998").value = info[1];
		document.getElementById("who_tef_2005").value = info[2];
		document.getElementById("i_tef").value = info[3]
		document.getElementById("who_tef").value = info[4];
	}

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