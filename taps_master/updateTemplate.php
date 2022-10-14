
<?php  
	include ('iconn.php');
	include 'header2.html';

	print'<h2>Update Glab record</h2>';
	
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getCompoundData();
		 //}
		 
		
		 
		 if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			if (!empty($_POST['compound']) and !empty($_POST['who_tef']))	
				{
					
					
						
						$u= "update template set "
						."compound='".$_POST['compound'].
						"',who_tef='".$_POST['who_tef']."'"
						." where compound='".$_POST['compound']."';";
						
						
						if ($dbc->query($u) === TRUE) {
						   // $updated=TRUE;
						
							echo '<script>alert("Update Success")</script>';
							
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
		 
		 
		 
		 
		 

		function getCompoundData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from compounds order by compound ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'compound Configuration Error!</p>';
				exit();
			}		
			
			
			
			print '<form action="updateCompound.php" method="post">';
			
			echo '<br>';
			
			//echo '<table class="table" cellspacing="0" width="100%">
					//<tr>
					//	<th>Code</th>
					//	<th>Location</th>
					//	<th>Action</th>
					//</tr>';
					
					
			print '<tr style="color:#555555;">
				<td style="width:48%;text-align:right">	
					<label for="lid">Compound name</label>	  				
					<td>
					<select style="margin-left:10px" name="compound">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->lid==$t[0]){
							  print '<option value="'.$r_l->compound.'" selected>'.'('.$r_l->compound.') '.$r_l->who_tef.'</option>';}
						  else{
							 print '<option value="'.$r_l->compound.'">'.'('.$r_l->compound.') '.$r_l->who_tef.'</option>';}
					};				
					
			
			print '</td></tr>			
					<tr style="color:#555555;">
						<td style="width:48%;text-align:right">
						  <label for="code">Compound name</label>
						</td>				  				
						<td>  
							<input style="margin-left:10px" type="text" name="who_tef" id="who_tef"></input>
						</td>				  				
					</tr>';			
			
			print	'<tr style="color:#555555;">
							<td style="width:48%;text-align:right">
							</td>				
							<td>
							  <input class=button--general style="margin-left:10px" type="submit" value="Update">
							</td>  
						</tr></table>';
 
			echo '</table>';

		}
		
		
		
		include 'footer.html';

?>
		
		<script>
		
			function editClick(){
				
				
				location.replace("updatelocationform.php")
			}
		
			function delClick(){
				alert("delete click");
			}
		
		
			function showSuccessAlert(){
				window.alert("Success");
			}
		

		
		</script>
		
		
	
