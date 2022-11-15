
<?php  
	include ('iconn.php');
	include 'header2.php';
	
	print'<h2>Delete compound record</h2>';
	
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
	
	
	
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getMediaData();
		 //}
		 
		
		 
		 if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{

			if (!empty($_POST['id']))	
				{
					$scode = $_POST['id'];
					
					
						
						
						$u= "delete from compound where id='{$scode}';";
						
						
						$cu= "delete from compound_map where compound_id='{$scode}';";
						
						if ($dbc->query($u) === TRUE and $dbc->query($cu) === TRUE ) {
						   // $updated=TRUE;
													
							echo '<script>alert("Delete Success");
							window.location.href = "delCompound.php"; </script>';
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
		 
		 
		 
		 
		 

		function getMediaData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from compound order by id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Location Configuration Error!</p>';
				exit();
			}		
			
			
			
			print '<form action="delCompound.php" method="post">';
			
			echo '<br>';
			
			//echo '<table class="table" cellspacing="0" width="100%">
					//<tr>
					//	<th>Code</th>
					//	<th>Location</th>
					//	<th>Action</th>
					//</tr>';
					
					
			print '<tr style="color:#555555;">
				<td >	
					<label for="lid">Compound Name</label>	  				
					<td>
					<select style="margin-left:10px" name="id">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->id==$t[0]){
							  print '<option value="'.$r_l->id.'" selected>'.$r_l->id.'**'.$r_l->name.'</option>';}
						  else{
							 print '<option value="'.$r_l->id.'">'.$r_l->id.'**'.$r_l->name.'</option>';}
					};				
					
			
			
			print	'<tr style="color:#555555;">
							<td style="width:48%;text-align:right">
							</td>				
							<td>
							  <input class=button--general style="margin-left:10px" type="submit" value="delete">
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
		

		
		
	
