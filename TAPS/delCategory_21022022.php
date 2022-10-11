		
<?php  
	include ('iconn.php');
	include 'header2.php';
	
	print'<h2>Delete Category record</h2>';
	print '
	
		<script>
			
				
			
				function delClick(){
					alert("delete click");
				}
			
				function notSuccessAlert(){
					alert("Cannot delete! it is because there are some compounds which include to this category");
				}
			
				function showSuccessAlert(){
					alert("Success");
				}
			
				
			
		</script>
	
	';
	
	
			 getLoginData();
		 
		
		 
		if (!empty($_POST['id'])){
					
					$suid = $_POST['id'];
					
					
					$search = "select * from compound_map where category_id = '{$suid}'";
						
						
					$result=$dbc->query($search);
					if (!$result->num_rows){
						
						$u = "delete from category where id = '{$suid}';";
						if ($dbc->query($u) === TRUE) {
							echo '<script>alert("Delete Success");
							window.location.href = "delCategory.php"; </script>';
						}else{
							echo "Error: " . $dbc->error;
							exit();
						}
						
						//print '<p class="text--error">'.'category Configuration Error!</p>';
						//print "No information";
						exit();
					}else{
						echo "<script> window.onload = function() {notSuccessAlert();}; </script>";
						
						
					}
		

		}else{
				 // print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
						
// echo "<script> window.onload = function() {notSuccessAlert();}; </script>";
		}
		exit();
		 
		 
		 
		 
		 

		function getLoginData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from category order by id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Account Configuration Error!</p>';
				exit();
			}		
			
			
			
			print '<form action="delCategory.php" method="post">';
			
			echo '<br>';
			
		
					
					
			print '<tr style="color:#555555;">
				<td style="width:48%;text-align:right">	
					<label for="lid">Category</label>	  				
					<td>
					<select style="margin-left:10px" name="id">';
					   while ($r_l=$result_loc->fetch_object()){
						  if ($r_l->id==$t[0]){
							  print '<option value="'.$r_l->id.'" selected>'.$r_l->id.'**'.$r_l->item.'</option>';}
						  else{
							 print '<option value="'.$r_l->id.'">'.$r_l->id.'**'.$r_l->item.'</option>';}
					};				
					
			
			
			print	'<tr style="color:#555555;">
							<td text-align:right">
							</td>				
							<td>
							  <input class=button--general style="margin-left:10px" type="submit" value="delete">
							</td>  
						</tr>';
			 
			echo '</table>';

		}
		
		
		
		include 'footer.html';

?>
		

		
		
	
