<?php  
	namespace Phppot;
	use Phppot\DataSource;
	require_once "connection.php";  
	require_once "iconn.php";
	require_once "header2.php";
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
		$errorFlag = false;
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if (!empty($_POST['compound_grp'])){				
				$u= "update qc_criteria set "
				."compound_grp='".$_POST['compound_grp']."'";
				if(!empty ($_POST['comp_grp_name'])){
					if(strlen($_POST['comp_grp_name']) > 255){
						$errorFlag = true;
						$type = "error";
						$message = "Compound Group Name should not exceed 255 characters.";
					}else if(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['comp_grp_name'])){
						$errorFlag = true;
						$type = "error";
						$message = "Compound Group Name should not contains special characters.";
					}else{
						$u .= ",comp_grp_name='".$_POST['comp_grp_name']."'";
					}
				}
				if(!empty ($_POST['ptg_diff_colocate'])){
					if(strlen($_POST['ptg_diff_colocate']) > 3){
						$errorFlag = true;
						$type = "error";
						$message = "Percentage difference of co-locate should not exceed 3 digits.";
					}else{
						$u .= ",ptg_diff_colocate='".$_POST['ptg_diff_colocate']."'";
					}
				}		
				
				if(!empty ($_POST['ptg_pollutant'])){
					if(strlen($_POST['ptg_pollutant']) > 2){
						$errorFlag = true;
						$type = "error";
						$message = "Percentage Pollutant should not exceed 2 digits.";
					}else{
						$u .= ",ptg_pollutant='".$_POST['ptg_pollutant']."'";
					}
				}
	
				if(!empty ($_POST['percentile'])){
					if(strlen($_POST['percentile']) > 3){
						$errorFlag = true;
						$type = "error";
						$message = "Percentile should not exceed 3 digits.";
					}else{
						$u .= ",percentile='".$_POST['percentile']."'";
					}
				}
	
				if(!empty ($_POST['year_avg'])){
					if(strlen($_POST['year_avg']) > 2){
						$errorFlag = true;
						$type = "error";
						$message = "Year average should not exceed 2 digits.";
					}else{
						$u .= ",year_avg='".$_POST['year_avg']."'";
					}
				}
	
				$u .=" where compound_grp='".$_POST['compound_grp']."';";
				
				if(!$errorFlag){
					$updateQuery = $dbc->query($u);
					
					if ($updateQuery) {
						$type = "success";
						$message = "QC Criteria is updated. ";
					}else{
						$type = "error";
						$message = "QC Criteria cannot be updated. ";
					}
				}
			}else{
				$type = "error";
				$message = "There is no information for updating";	
			}			
		} 
	?>

	<body onload="Showparam()">
		<?php 
			$selectQuery = "select * from qc_criteria order by id ASC;";
			$qcResult=$dbc->query($selectQuery);
			if (!$qcResult->num_rows){
				print '<p class="text--error">'.'QC Criteria Configuration Error!</p>';
				exit();
			}
		?>
		<h2 style="margin-left:10px">Update QC Criteria</h2>
		<hr>
		<div>
			<form class="post-form" action="updateQCriteria.php" method="post">    
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div>  
				<table style="margin-left:10px">
					<tr>
						<td>	
							<label>Compound Group: </label> 				
						<td>
						<select style="width:100%; margin-left:10px;" name="compound_grp" id="compound_grp" onchange="Showparam()">';
							<?php
								while ($r_l=$qcResult->fetch_object()){
									if ($r_l->compound_grp==$result[0]["compound_grp"]){
										print '<option value="'.$r_l->compound_grp.'" selected>'.$r_l->compound_grp.'**'.$r_l->comp_grp_name.'**'.$r_l->ptg_diff_colocate.'**'.$r_l->ptg_pollutant.'**'.$r_l->percentile.'**'.$r_l->year_avg.'</option>';
									}else{
										print '<option value="'.$r_l->compound_grp.'" >'.$r_l->compound_grp.'**'.$r_l->comp_grp_name.'**'.$r_l->ptg_diff_colocate.'**'.$r_l->ptg_pollutant.'**'.$r_l->percentile.'**'.$r_l->year_avg.'</option>';
									}
								};
							?>									
						</td>
					</tr>
					<tr>
						<td><label>Compound Group Name: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="text" name="comp_grp_name" id="comp_grp_name"/>
						</td>  
					</tr>
					<tr>
						<td><label>Percentage Difference of co-locate Sample: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="ptg_diff_colocate" id="ptg_diff_colocate"/>
						</td>  
					</tr>
					<tr>
						<td><label>Over Limit Rate: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="ptg_pollutant" id="ptg_pollutant"/>
						</td> 
					</tr>
					<tr>
						<td><label>Percentile: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="percentile" id="percentile"/>
						</td>
					</tr>
					<tr>
						<td><label>Year of Average: </label></td>
						<td>
							<input style="width:100%; margin-left:10px;" type="number" name="year_avg" id="year_avg"/>
						</td>
					</tr>
				</table>
				<hr>
				<input style="margin-left:10px" class="submit" type="submit" value="Update"  />
				<input type="button" style="margin-left:10px" name="cancel" value="Cancel" onClick="document.location.href='showQCriteria.php'">
			</form>
		</div>
	</body>
	<script>
		function Showparam() {
			var e = document.getElementById("compound_grp");
			var str = e.options[e.selectedIndex].innerHTML;
			var info = str.split("**");
			var grp = document.getElementById("compound_grp");
			grp.options[grp.options.selectedIndex].selected = true;

			document.getElementById("comp_grp_name").value = info[1];
			document.getElementById("ptg_diff_colocate").value = info[2];
			document.getElementById("ptg_pollutant").value = info[3];
			document.getElementById("percentile").value = info[4]
			document.getElementById("year_avg").value = info[5];
		}
	</script>
</html>