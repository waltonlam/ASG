<?php  
	include ('iconn.php');
	include 'header2.php';
	
	print'<h2 style="margin-left:10px">Delete Factor</h2><hr>';
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
		</style>
		
		<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
		<script  type="text/javascript">		
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
	
	//if (isset($_GET['click']) && $_GET['click'] == 'media') {
	//getMediaData();
	//} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
	getFactorData();
	//}	 
		
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (!empty($_POST['compound']))	{
			$scompound = $_POST['compound'];										
			$u= "delete from factor where compound='{$scompound}';";						
			if ($dbc->query($u) === TRUE) {
				// $updated=TRUE;							
				echo '<script>alert("Delete Success");
				window.location.href = "delFactor.php"; </script>';
			}else{
				echo "Error: " . $dbc->error;
				exit();
			};	
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
		
		print '<form action="delFactor.php" method="post">';				
		print '<table style="margin-left:10px">';
		print '<tr>
				<td>	
					<label>Factor: </label>  				
				</td>
				<td>
				<select style="width:100%; margin-left:10px;" name="compound">';
					while ($r_l=$result_loc->fetch_object()){
						if ($r_l->lid==$t[0]){
							print '<option value="'.$r_l->compound.'" selected>'.'('.$r_l->compound.') </option>';}
						else{
							print '<option value="'.$r_l->compound.'">'.'('.$r_l->compound.') </option>';}
					};				
		print '</select></td></tr></table><hr><input style="margin-left:10px" type="submit" value="delete">';

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
		print '</form>';
	}
	include 'footer.html';
?>