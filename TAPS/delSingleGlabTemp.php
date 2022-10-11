		
<?php  
	include ('iconn.php');
	include 'header2.html';
	
	print'<h2>Delete single Glab record</h2>';
	
	print '
	
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
			<script  type="text/javascript">

				function delClick(){
					alert("delete click");
				}
			
			
				function showSuccessAlert(){
					alert("Success");
				}
				
				function showConfirmAlert(){
					
					var answer = confirm ("Confirm to delete this record?")
					if (answer){
						
						
					}
				}
				
		</script>
	
	';
	
	
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getSampleTxData();
		 //}
		
		 
		 if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			$casno1 = $_POST['casno1'];
			if (!empty($_POST['casno1']))	
				{
					
						$casno1 = $_POST['casno1'];
						

						$u= "delete from glab_template where sample_id='{$casno1}' ;";

						
						
						
						if ($dbc->query($u) === TRUE) {
						   // $updated=TRUE;
						
							
							echo '<script>alert("Delete Success");
							window.location.href = "delSingleGlabTemp.php"; </script>';
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
		
		 
		 
		
		 

		function getSampleTxData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from glab_template order by start_date ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Sample Tx Configuration Error!</p>';
				exit();
			}		
			
			
			
			print '<form action="delSingleGlabTemp.php" method="post">';
			
			echo '<br>';
			
			//echo '<table class="table" cellspacing="0" width="100%">
					//<tr>
					//	<th>Code</th>
					//	<th>Location</th>
					//	<th>Action</th>
					//</tr>';
					
					
			print '<tr style="color:#555555;">
				<td style="width:48%;text-align:right">	
					<label for="lid">Sample Code</label>	  				
					<td>
					<select style="margin-left:10px" name="casno1">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->sample_id==$t[0]){
							  print '<option value="'.$r_l->sample_id.'" selected>'.'('.$r_l->sample_id.') '.$r_l->casno1.'</option>';}
						  else{
							 print '<option value="'.$r_l->sample_id.'">'.'('.$r_l->sample_id.') '.$r_l->casno1.'</option>';}
					};				
					
			
			
			print	'<tr style="color:#555555;">
							<td style="width:48%;text-align:right">
							</td>				
							<td>
							  <input name="submit" class="button--general btn submit" style="margin-left:10px" type="submit" value="delete">
							</td>  
						</tr></table>';
						
			
			
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
			 
			 
			 
			echo '</table>';

		}
		
		
		
		include 'footer.html';

?>
		

		
		
	
