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
				$type = "success";
				$message = "QC Criteria deleted successfully.";
			}else{
				$type = "error";
				$message = "QC Criteria not deleted.";
			}
		}else{
			$type = "error";
			$message = "Please select at least one compound group to delete.";
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
		<script>
			function toggle(source) {
				checkboxes = document.getElementsByName('qc_delete_id[]');
				for(var i=0, n=checkboxes.length;i<n;i++) {
					checkboxes[i].checked = source.checked;
				}
			}
		</script>
	</head>
	<body>
		<div>
			<h2 style="margin-left:10px">All QC Criteria</h2>
			<hr>				
			<form method="post" name="frm-list" >
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div>  
				<div style="overflow-x: auto;">
					<table id="mediaTb" class="table table-striped table-condensed table-bordered"> 
						<thead> 
							<tr>
								<?php if($_SESSION['utp']==0){ ?>
									<th><input type="checkbox" onClick="toggle(this)" />All<br/></th>
								<?php } ?>
								<th>Compound Group</th>
								<th>Compound Group Name</th>
								<th>Percentage Difference of co-locate Sample</th>
								<th>Over Limit Rate</th>
								<th>Percentile</th>
								<th>Year of Average</th>
							</tr>
						</thead> 
						<tbody> 
							<?php 
								$query = "select * from qc_criteria order by compound_grp ASC;";
								$rs_result = mysqli_query ($conn, $query);
								
								while ($row = mysqli_fetch_array($rs_result)){ 
							?>		
								<tr>
									<?php if($_SESSION['utp']==0){ ?>
										<td><input style="vertical-align: top;" type="checkbox" name="qc_delete_id[]" value="<?php echo $row["id"] ?>"></td>
									<?php } ?>
									<td><?php echo $row["compound_grp"] ?></td>
									<td><?php echo $row["comp_grp_name"] ?></td>
									<td><?php echo $row["ptg_diff_colocate"]."%" ?></td>
									<td><?php echo $row["ptg_pollutant"] ?></td>
									<td><?php echo $row["percentile"] ?></td>
									<td><?php echo $row["year_avg"] ?></td>
								</tr>		
							<?php } ?>	
						</tbody> 	
					</table>	
				</div>			
				<hr>
				<?php if($_SESSION['utp']==0){ ?>
					<input type="submit" style="margin-left:10px" name="delete" value="Delete">
					<input type="button" style="margin-left:10px" name="add" value="Add" onClick="document.location.href='addQCriteria.php'">
					<input type="button" style="margin-left:10px" name="update" value="Update" onClick="document.location.href='updateQCriteria.php'">
				<?php } ?>
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>