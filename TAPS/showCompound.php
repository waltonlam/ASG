
<?php  
	include ('iconn.php');
	include 'header2.php';
	print '<h2>All Compounds</h2>';
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){

		 //}
		 


/*STP			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}
*/

	echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search..">';
						
	echo '<br>';
	echo '<table id="mediaTb" class="table" cellspacing="0" width="100%">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Code</th>
				<th>Who_Tef</th>


			</tr>';


	$l = "SELECT id,name, who_tef, code FROM compound order by id;";

	if (!$result_cp = mysqli_query($dbc, $l)) {
		exit(mysqli_error($dbc));
	}else{

		if ($result_cp->num_rows){ 
			while ($row = mysqli_fetch_assoc($result_cp)) {
				echo "<tr>";
				echo "<td>" . $row["id"]."</td>";
				echo "<td>" . $row["name"]."</td>";
				echo "<td>" . $row["code"]."</td>";
				echo "<td>" . $row["who_tef"]."</td>";

				echo "</tr>";
					
			}

		}else{
			print '<p class="text--error">'.'Compound Configuration Error!</p>';
			exit();
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
				table = document.getElementById("mediaTb");
				tr = table.getElementsByTagName("tr");
				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[1];
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
		
		
	
