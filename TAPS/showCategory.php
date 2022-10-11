
<?php  
	include ('iconn.php');
	include 'header2.php';
	
	print '<h2>All Compound Categories</h2>';
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getCategoryData();
		 //}
		 

		function getCategoryData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from category order by id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Compound Configuration Error!</p>';
				exit();
			}		

			
			echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search..">';
			
			echo '<br>';
			echo '<table id="mediaTb" class="table" cellspacing="0" width="100%">
					<tr>
						<th>ID</th>
						<th>Item</th>
					</tr>';
					
			
			 while ($row =$result_loc->fetch_assoc()){
				 
				 echo "<tr>";
					echo "<td>" . $row["id"]."</td>";
					echo "<td>" . $row["item"]."</td>";
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
		
		
	
