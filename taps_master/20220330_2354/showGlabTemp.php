
<?php  
	include ('iconn.php');
	include 'header2.php';
	print '<h2>All record</h2>';
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getTemplateData();
		 //}
		 
		
		 
		
		 
		 
		 
		 

		function getTemplateData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from glab_template order by sample_id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'No record !</p>';
				exit();
			}		
			echo '<br>';
			
			echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for code ..." title="Type in a type" placeholder="Search..">';
			
			echo '<br>';
			echo '<table id="tempTb"  class="table" cellspacing="0" width="100%">
					<tr>
						<th>Sample Id</th>
						<th>Site</th>
						<th>Start date</th>
						<th>Casno1</th>
						<th>Compound Group</th>
						<th>Compound Code</th>
						<th>Raw Result</th>
						<th>Epd conc</th>
						<th>Glab Result</th>
					</tr>';
					
			
			 while ($row =$result_loc->fetch_assoc()){
				 
				 echo "<tr>";
					echo "<td>" . $row["sample_id"]."</td>";
					echo "<td>" . $row["site"]."</td>";
					echo "<td>" . $row["start_date"]."</td>";
					echo "<td>" . $row["casno1"]."</td>";
					echo "<td>" . $row["compound_group"]."</td>";
					echo "<td>" . $row["compound_code"]."</td>";
					echo "<td>" . round($row["raw_result"], 2) ."</td>";
					echo "<td>" . $row["conc_string"]."</td>";
					echo "<td>" . round($row["glab_result"],2)."</td>";
				
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
				table = document.getElementById("tempTb");
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
		
		
	
