
<?php  
	include ('iconn.php');
	include 'header2.php';
	

	print '<h2>All record</h2>';
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getConstractorData();
		 //}
		 
		
		 
		
		 
		 
		 
		 

		function getConstractorData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from contractor_template order by sample_id DESC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'No record !</p>';
				exit();
			}		
			
			echo '<br>';
			
			echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for sample id.." title="Type in a compound" placeholder="Search..">';
			
			echo '<br>';
			echo '<table id="compoundTb" class="table" cellspacing="0" width="100%">
					<tr>
						<th>Sample ID</th>
						<th>Site</th>
						<th>Compound group</th>
						
						<th>Flow rate</th>
						<th>Duration</th>
						<th>Remarks</th>
						<th>Sample Date</th>
						<th>Who_Tef</th>
					</tr>';
					
			
			 while ($row =$result_loc->fetch_assoc()){
				 
				 echo "<tr>";
					echo "<td>" . $row["sample_id"]."</td>";
					echo "<td>" . $row["site"]."</td>";
					echo "<td>" . $row["compound_group"]."</td>";
					
					echo "<td>" . $row["flow_rate"]."</td>";
					echo "<td>" . $row["duration"]."</td>";
					echo "<td>" . $row["remarks"]."</td>";
					/*echo "<td>" . $row["year"]."</td>";
					
					$m = "0000-00-00";
					if ($row["month"] === '0000-00-00'){
						$m = "00";
						echo "<td>" . $m."</td>";
					}else{
						$date = new DateTime($row["month"]);
						$m = date("m",strtotime($date->format('Y-m-d')));
						$date = new DateTime($row["month"]);
						$m = date("m",strtotime($date->format('Y-m-d')));
						$mInt = (int)$m;
						$mS = (string)$mInt;
						echo "<td>" . $mS."</td>";
					}*/
					echo "<td>" . $row["sample_date"]."</td>";
					echo "<td>" . $row["who_tef"]."</td>";
					/*echo gettype($mInt);
					echo '<br>';
					echo $mS;*/
					
					
					
					echo "</tr>";
				 
			 }
			 
			 
			 
			echo '</table>';

		}
		
		
		
		include 'footer.html';

?>
		
		<script>
		
			function search(){
				
				
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				table = document.getElementById("compoundTb");
				tr = table.getElementsByTagName("tr");
				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[0];
					if (td) {
					  txtValue = td.textContent || td.innerText;
					  if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					  } else {
						tr[i].style.display = "none";
					  }
					}       
				  }
			}
		
			function delClick(){
				alert("delete click");
			}
		
		
		</script>
		
		
	
