<?php 
	include 'header2.php';
	include 'iconn.php';
?>
<html>
	<style>
	  input[type=button], input[type=submit] {
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
				$selectQuery = "SELECT * FROM qc_criteria WHERE compound_grp = '".$_POST['compound_grp']."';";
				$checkDuplicateCompound = $dbc->query($selectQuery);

				if($checkDuplicateCompound->num_rows === 0){
					$sql = "INSERT INTO qc_criteria(compound_grp,ptg_diff_colocate,ptg_pollutant,percentile,year_avg) VALUES ('".$_POST['compound_grp']."','".$_POST['ptg_diff_colocate']."','".$_POST['ptg_pollutant']."','".$_POST['percentile']."','".$_POST['year_avg']."')";
				
					if ($dbc->query($sql) === FALSE) {
						echo "Error: " . $dbc->error;
						exit();
					}else{					
						$msg = "New Factor is created.";
					}
				}else{
					$msg = "Compound group is existed.";
				}
			}
		}
	?>

	<body>
		<h2 style="margin-left:10px">Add QC Criteria</h2>
		<span id="message" style="margin-left:10px; color:red;"><?php echo $msg ?></span>
		<hr>
		<div>
			<form class="post-form" action="addQCriteria.php" method="post">     
				<table style="margin-left:10px">
					<tr>
						<td><label>Compound Group<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="compound_grp" required/>
						</td>
					</tr>
					<tr>
						<td><label>Percentage Difference of co-locate Sample<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="ptg_diff_colocate" required/>
						</td>  
					</tr>
					<tr>
						<td><label>Percentage of Pollutant<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="ptg_pollutant" required/>
						</td> 
					</tr>
					<tr>
						<td><label>Percentile<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="percentile" required/>
						</td>
					</tr>
					<tr>
						<td><label>Year of Average<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="year_avg" required/>
						</td>
					</tr>
				</table>
				<hr>
				<input style="margin-left:10px" class="submit" type="submit" value="Add"  />
				<input type="button" style="margin-left:10px" name="cancel" value="Cancel" onClick="document.location.href='showQCriteria.php'">
			</form>
		</div>
	</body>
</html>