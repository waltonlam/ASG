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
	</style>

	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			if (!empty($_POST['compound_grp'])){				
				$u= "update qc_criteria set "
				."compound_grp='".$_POST['compound_grp']."'";
				if(!empty ($_POST['comp_grp_name'])){
					$u .= ",comp_grp_name='".$_POST['comp_grp_name']."'";
				}
				if(!empty ($_POST['ptg_diff_colocate'])){
					$u .= ",ptg_diff_colocate='".$_POST['ptg_diff_colocate']."'";
				}		
				
				if(!empty ($_POST['ptg_pollutant'])){
					$u .= ",ptg_pollutant='".$_POST['ptg_pollutant']."'";
				}
	
				if(!empty ($_POST['percentile'])){
					$u .= ",percentile='".$_POST['percentile']."'";
				}
	
				if(!empty ($_POST['year_avg'])){
					$u .= ",year_avg='".$_POST['year_avg']."'";
				}
	
				$u .=" where compound_grp='".$_POST['compound_grp']."';";
				
				$updateQuery = $dbc->query($u);
				if ($updateQuery) {
					$msg = "QC Criteria is updated. ";
				}else{
					$msg = "QC Criteria cannot be updated. ";
				}
			}else{
				$msg = "There is no information for updating";	
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
		<span id="message" style="margin-left:10px; color:red;"><?php echo $msg ?></span>
		<hr>
		<div>
			<form class="post-form" action="updateQCriteria.php" method="post">     
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