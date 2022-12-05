<?php 
	include 'header2.php';
	include 'iconn.php';
?>
<html>
	<style>
	  input[type=submit] {
		background-color: #87ceeb;
		color: white;
		padding: 12px 20px;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		width:100
	  }
	</style>

	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		  if (!empty($_POST['compound_grp']) and !empty($_POST['ptg_diff_colocate']) or !empty($_POST['ptg_pollutant']) or !empty($_POST['percentile']) or !empty($_POST['year_avg'])){
				//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
				$sql = "INSERT INTO qc_criteria(compound_grp,ptg_diff_colocate,ptg_pollutant,percentile,year_avg) VALUES ('".$_POST['compound_grp']."','".$_POST['ptg_diff_colocate']."','".$_POST['ptg_pollutant']."','".$_POST['percentile']."','".$_POST['year_avg']."')";
				
				if ($dbc->query($sql) === FALSE) {
					echo "Error: " . $dbc->error;
					exit();
				}else{					
					echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">New Factor added successfully</p>';
				}
			}
		}
	?>
	<h2 style="margin-left:10px">Add QC Criteria</h2>
	<hr>
	<body>
		<div>
			<form class="post-form" action="addQCriteria.php" method="post">     
				<table style="margin-left:10px">
					<tr>
						<td><label>Compound Group: <span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="compound_grp" required/>
						</td>
					</tr>
					<tr>
						<td><label>Percentage Difference of co-locate Sample: <span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="ptg_diff_colocate" required/>
						</td>  
					</tr>
					<tr>
						<td><label>Percentage of Pollutant: <span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="ptg_pollutant" required/>
						</td> 
					</tr>
					<tr>
						<td><label>Percentile: <span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="percentile" required/>
						</td>
					</tr>
					<tr>
						<td><label>Year of Average: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="year_avg"/>
						</td>
					</tr>
				</table>
				<hr>
				<input style="margin-left:10px" class="submit" type="submit" value="Add"  />
			</form>
		</div>
	</body>
</html>