
<?php  
	include ('iconn.php');
	include 'header2.php';

	if (isset($_SESSION['previous'])) {
		if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
			 //session_destroy();
			 unset($_SESSION['site_id']);
			 unset($_SESSION['dateFrom']);
			 unset($_SESSION['dateTo']);
			 ### or alternatively, you can use this for specific variables:
			 ### unset($_SESSION['varname']);
		}
	}

	if (isset($_SESSION['prev_incid'])) {
		if (basename($_SERVER['PHP_SELF']) != $_SESSION['prev_incid']) {
			 //session_destroy();
			 unset($_SESSION['site_code_inc']);
			 unset($_SESSION['dateFrom_inc']);
			 unset($_SESSION['dateTo_inc']);
			 ### or alternatively, you can use this for specific variables:
			 ### unset($_SESSION['varname']);
		}
	}
	
	print '<h2 style="margin-left:10px">All User Accounts</h2><hr>';
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getLoginData();
		 //}


		function getLoginData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from uacc order by uid ASC;";
			//print $q;
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Login Configuration Error!</p>';
				exit();
			}		
			
			//echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for uid.." title="Type in a compound" placeholder="Search..">';
			
			//echo '<br>';
			echo '<table id="compoundTb" class="table" cellspacing="0" width="100%">
					<tr>
						<th>User Id</th>
						<th>Password</th>
						<th>First name</th>
						<th>Last name</th>
						<th>Read only</th>
					</tr>';
			
			 while ($row =$result_loc->fetch_assoc()){
				 
				 echo "<tr>";
					echo "<td>" . $row["uid"]."</td>";
					echo "<td>" . $row["pwd"]."</td>";

					echo "<td>" . $row["fname"]."</td>";
					echo "<td>" . $row["lname"]."</td>";


					if ($row["ronly"] == 1) {
						echo "<td>Yes</td>";
					}else{
						echo "<td>No</td>";
					}
					//echo "<td>" . $row["ronly"]."</td>";
					echo "</tr>";
			 }
			echo '</table><hr>';
		}
		include 'footer.html';
?>
		
<script>	
	/*function search(){
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
	}*/

	function delClick(){
		alert("delete click");
	}
</script>