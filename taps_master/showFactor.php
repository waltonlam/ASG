
<?php  
	include ('iconn.php');
	include 'header2.php';

	//if (isset($_GET['click']) && $_GET['click'] == 'media') {
	//getMediaData();
	//} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
		getFactorData();
	//}
		
	function getFactorData(){
		if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
		{
			print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
			exit();
		}

		$l = "select * from factor order by compound ASC;";
		//print $q;
		$result_loc=$dbc->query($l);
		if (!$result_loc->num_rows){
			print '<p class="text--error">'.'Compound Configuration Error!</p>';
			exit();
		}		
		
		echo '<br>';		
		echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for compound.." title="Type in a compound" placeholder="Search..">';
		echo '<br>';
		echo '<table id="compoundTb" class="table" cellspacing="0" width="100%">
				<tr>
					<th>Compound</th>
					<th>WHO-TEF-1998</th>
					<th>WHO-TEF-2005</th>
					<th>I-TEF</th>
				</tr>';		
		while ($row =$result_loc->fetch_assoc()){				
			echo "<tr>";
			echo "<td>" . $row["compound"]."</td>";
			echo "<td>" . $row["who_tef_1998"]."</td>";
			echo "<td>" . $row["who_tef_2005"]."</td>";
			echo "<td>" . $row["i_tef"]."</td>";
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