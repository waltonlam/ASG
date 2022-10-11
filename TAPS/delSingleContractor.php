
<?php  
	include ('iconn.php');
	include 'header2.html';
	
	print'<h2>Delete Single contractor record</h2>';
	
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
			 getContractorData();
		 //}
		
		 
		 if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			$sample_id = $_POST['sample_id'];
			if (!empty($_POST['sample_id']))	
				{
					
						$sample_id = $_POST['sample_id'];
						
						$u= "delete from contractor_template where sample_id='{$sample_id}';";
						
						
						
						if ($dbc->query($u) === TRUE) {
						   // $updated=TRUE;
						
							
							 echo '<script>alert("Delete Success");
							window.location.href = "delSingleContractor.php"; </script>';
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
		
		 
		 
		
		 

		function getContractorData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from contractor_template order by sample_id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'No Record !</p>';
				exit();
			}		
			
			
			
			print '<form action="delSingleContractor.php" method="post">';
			
			echo '<br>';							
					
			print '<tr style="color:#555555;">
				<td style="width:48%;text-align:right">	
					<label for="lid">Contractor Code</label>	  				
					<td>
					<select style="margin-left:10px" name="sample_id">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->sample_id==$t[0]){
							  print '<option value="'.$r_l->sample_id.'" selected>'.'('.$r_l->sample_id.') '.$r_l->compound_group.'</option>';}
						  else{
							 print '<option value="'.$r_l->sample_id.'">'.'('.$r_l->sample_id.') '.$r_l->compound_group.'</option>';}
					};				
					
			
			
			print	'<tr style="color:#555555;">
							<td style="width:48%;text-align:right">
							</td>				
							<td>
							  <input name="submit" class="button--general btn submit" style="margin-left:10px" type="submit" value="delete">
							</td>  
						</tr></table>';
						
			
			

			 
			 
			 
			echo '</table>';

		}
		
		
		
		include 'footer.html';

?>
		

		
		
	
