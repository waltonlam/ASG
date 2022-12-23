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

	  	#response {
			padding: 10px;
			margin-bottom: 10px;
			border-radius: 5px;
			display: none;
		}

		.success {
			background: #c7efd9;
			border: #bbe2cd 1px solid;
		}

		.error {
			background: #fbcfcf;
			border: #f3c6c7 1px solid;
		}

		div#response.display-block {
			display: block;
		}
	</style>

	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		  if (!empty($_POST['compound_grp']) and !empty($_POST['ptg_diff_colocate']) or !empty($_POST['ptg_pollutant']) or !empty($_POST['percentile']) or !empty($_POST['year_avg'])){
				$selectQuery = "SELECT * FROM qc_criteria WHERE compound_grp = '".$_POST['compound_grp']."';";
				$checkDuplicateCompound = $dbc->query($selectQuery);

				if($checkDuplicateCompound->num_rows === 0){
					if(strlen($_POST['compound_grp']) > 5){
						$type = "error";
						$message = "Compound Group should not exceed 5 characters.";
					}else if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['compound_grp'])){
						$type = "error";
						$message = "Compound Group should not contains special characters.";
					}else if(strlen($_POST['comp_grp_name']) > 255){
						$type = "error";
						$message = "Compound Group Name  should not exceed 255 characters.";
					}else if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['comp_grp_name'])){
						$type = "error";
						$message = "Compound Group Name should not contains special characters.";
					}else if(strlen($_POST['ptg_diff_colocate']) > 3){
						$type = "error";
						$message = "Percentage difference of co-locate should not exceed 3 digits.";
					}else if(strlen($_POST['ptg_pollutant']) > 2){
						$type = "error";
						$message = "Percentage Pollutant should not exceed 2 digits.";
					}else if(strlen($_POST['percentile']) > 3){
						$type = "error";
						$message = "Percentile should not exceed 3 digits.";
					}else if(strlen($_POST['year_avg']) > 2){
						$type = "error";
						$message = "Year average should not exceed 2 digits.";
					}else{
						$sql = "INSERT INTO qc_criteria(compound_grp,comp_grp_name,ptg_diff_colocate,ptg_pollutant,percentile,year_avg) VALUES ('".$_POST['compound_grp']."','".$_POST['comp_grp_name']."','".$_POST['ptg_diff_colocate']."','".$_POST['ptg_pollutant']."','".$_POST['percentile']."','".$_POST['year_avg']."')";
						if ($dbc->query($sql) === FALSE) {
							echo "Error: " . $dbc->error;
							exit();
						}else{	
							$type = "success";				
							$message = "New Compound group is created.";
						}
					}
				}else{
					$type = "error";
					$message = "Compound group is existed.";
				}
			}
		}
	?>

	<body>
		<h2 style="margin-left:10px">Add QC Criteria</h2>
		<hr>
		<div>
			<form class="post-form" action="addQCriteria.php" method="post">   
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div>  
				<table style="margin-left:10px">
					<tr>
						<td><label>Compound Group<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="compound_grp" value="<?=$_POST['compound_grp']?>" required/>
						</td>
					</tr>
					<tr>
						<td><label>Compound Group Name<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="comp_grp_name" value="<?=$_POST['comp_grp_name']?>" required/>
						</td>
					</tr>
					<tr>
						<td><label>Percentage Difference of co-locate Sample<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="ptg_diff_colocate" value="<?=$_POST['ptg_diff_colocate']?>" required/>
						</td>  
					</tr>
					<tr>
						<td><label>Over Limit Rate<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="ptg_pollutant" value="<?=$_POST['ptg_pollutant']?>" required/>
						</td> 
					</tr>
					<tr>
						<td><label>Percentile<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="percentile" value="<?=$_POST['percentile']?>" required/>
						</td>
					</tr>
					<tr>
						<td><label>Year of Average<span style="color:red">*</span>: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="year_avg" value="<?=$_POST['year_avg']?>" required/>
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