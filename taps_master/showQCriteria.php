<?php  
	namespace Phppot;
	use Phppot\DataSource;
	require_once "connection.php";  
	require_once "iconn.php";
	require_once "header2.php";

	if(isset($_POST['delete'])){
		if(!empty($_POST['qc_delete_id'])){
			$all_id = $_POST['qc_delete_id'];
			$extract_id = implode(',' , $all_id);

			//Delete QC Criteria in DB
			$query = "DELETE FROM qc_criteria WHERE id IN($extract_id) ";
			$deleteQeury = $dbc->query($query);
			if($deleteQeury){
				$msg = "QC Criteria deleted successfully.";
			}else{
				$msg = "QC Criteria not deleted.";
			}
		}else{
			$msg = "Please select at least one compound group to delete.";
		}
	}
?>

<html>
	<head>
		<link href="assets/style.css" rel="stylesheet" type="text/css" />
		<style>
			input[type=button], input[type=submit], input[type=reset] {
				background-color: #87ceeb;
				color: white;
				padding: 12px 20px;
				border: none;
				border-radius: 4px;
				cursor: pointer;
				width:100
			}
		</style>
	</head>
	<body>
		<div>
			<h2 style="margin-left:10px">All QC Criteria</h2>
			<span id="message" style="margin-left:10px; color:red;"><?php echo $msg ?></span>
			<hr>				
			<form method="post" name="frm-list" >
				<div style="overflow-x: auto;">
					<table id="mediaTb" class="table table-striped table-condensed table-bordered"> 
						<thead> 
							<tr>
								<th></th>
								<th>Compound Group</th>
								<th>Percentage Difference of co-locate Sample</th>
								<th>Percentage of Pollutant</th>
								<th>Percentile</th>
								<th>Year of Average</th>
							</tr>
						</thead> 
						<tbody> 
							<?php 
								$query = "select * from qc_criteria order by id ASC;";
								$rs_result = mysqli_query ($conn, $query);
								
								while ($row = mysqli_fetch_array($rs_result)){ 
							?>		
								<tr>
									<td><input style="vertical-align: top;" type="checkbox" name="qc_delete_id[]" value="<?php echo $row["id"] ?>"></td>
									<td><?php echo $row["compound_grp"] ?></td>
									<td><?php echo $row["ptg_diff_colocate"] ?></td>
									<td><?php echo $row["ptg_pollutant"] ?></td>
									<td><?php echo $row["percentile"] ?></td>
									<td><?php echo $row["year_avg"] ?></td>
								</tr>		
							<?php } ?>	
						</tbody> 	
					</table>	
				</div>			
				<hr>
				<input type="submit" style="margin-left:10px" name="delete" value="Delete">
				<input type="button" style="margin-left:10px" name="add" value="Add" onClick="document.location.href='addQCriteria.php'">
				<input type="button" style="margin-left:10px" name="update" value="Update" onClick="document.location.href='updateQCriteria.php'">
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>