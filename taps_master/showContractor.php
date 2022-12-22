
<?php  
	include ('iconn.php');
	include 'header2.php';
	
	print '<h2>All record</h2><hr>';
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

			$l = "select * from contractor_sample order by sample_id;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'No record !</p>';
				exit();
			}		
			
			echo '<br>';
			
			//echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for sample id.." title="Type in a compound" placeholder="Search..">';
			
			echo '<div style="overflow-x:auto;">';
			echo '<table id="compoundTb" class="table" cellspacing="0">
					<tr>
						<th>Sample ID</th>
						<th>Sampling Date</th>
						<th>Site</th>
						<th>Compound</th>
						<th>Compound Group</th>
						<th>Conc Sample</th>
						<th>Sampling Time</th>
						<th>Flow Rate</th>
						<th>Volume</th>
					</tr>';
					
			
			 while ($row =$result_loc->fetch_assoc()){
				 
				 echo "<tr>";
					echo "<td>" . $row["sample_id"]."</td>";
					echo "<td>" . $row["sampling_date"]."</td>";
					echo "<td>" . $row["site_id"]."</td>";
					
					echo "<td>" . $row["compound"]."</td>";
					echo "<td>" . $row["compound_grp"]."</td>";
					echo "<td>" . $row["conc_sample"]."</td>";
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
					echo "<td>" . $row["sampling_time"]."</td>";
					echo "<td>" . $row["flow_rate"]."</td>";
					echo "<td>" . $row["volume"]."</td>";
					/*echo gettype($mInt);
					echo '<br>';
					echo $mS;*/
					echo "</tr>"; 
			 }
			echo '</table></div>';
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