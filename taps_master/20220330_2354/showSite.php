
<?php  
	include ('iconn.php');
	include 'header2.php';
	print '<h2>All Sites</h2>';
		getLocationData();


		function getLocationData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from site order by code ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Site Configuration Error!</p>';
				exit();
			}		
			//echo '<br>';
			
			echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for code ..." title="Type in a name" placeholder="Search..">';
			
			echo '<br>';
			echo '<table id="locTb"  class="table" cellspacing="0" width="100%">
					<tr>
						<th>Code</th>
						<th>Site</th>
					</tr>';
					
			
			 while ($row =$result_loc->fetch_assoc()){
				 
				 echo "<tr>";
					echo "<td class='row-data'>" . $row["code"]."</td>";
					echo "<td class='row-data'>" . $row["location"]."</td>";
					
					echo "</tr>";
				 
			 }
			 
			 
			 
			echo '</table>';

		}
		
		
		
		include 'footer.html';

?>
		
		<script  type="text/javascript">
		function show(){
			
			var data = 
				document.getElementById(rowId).querySelectorAll(".row-data");
			
			var name = data[0].innerHTML;
            var age = data[1].innerHTML;
			
			alert("Code: " + name + "\nLocation: " + age);
			
			/*if(confirm('Are you sure to remove this record ?'))
			{
				$.ajax({
				   url: '/delete.php',
				   type: 'POST',
				   data: {id: id},
				   error: function() {
					  alert('Something is wrong');
				   },
				   success: function(data) {
						$("#"+id).remove();
						alert("Record removed successfully");  
				   }
				});
			}*/
		}
		
			function search(){
				
				
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				table = document.getElementById("locTb");
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
		
		
	
