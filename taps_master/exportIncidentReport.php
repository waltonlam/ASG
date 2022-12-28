<?php
	namespace Phppot;
	use Phppot\DataSource;
	require_once "connection.php";  
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/ImageModel.php';
	$imageModel = new ImageModel();
	$incidentResult = $imageModel->getIncidentReport();
	//print_r($incidentResult);

	$l = "select code from site order by code ASC;";
	$result_loc=$dbc->query($l);
	if (!$result_loc->num_rows){
		print '<p class="text--error">'.'Site Configuration Error!</p>';
		exit();
	}

	session_start();
 	$type = $_SESSION['type'];
	$message = $_SESSION['message'];
	
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
	</head>
	<body>
		<div>
			<h2>Export Incident Report</h2>
			<hr>
		</div>
		<div>
			<form action="exportToExc.php" method="post" id="export-form">
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div> 
				<table style="margin-left:10px">
					<td style="width:25%">
						<label>Site:</label>
					</td>
					<td>
						<select style="width:100%; margin-left:10px;" name=site_code id="site_code">
						<option value="">Please Select</option>
							<?php
								while ($r_l=$result_loc->fetch_object()){
									if ($r_l->code==$t[0]){
										print '<option value="'.$r_l->code.'" selected>'.$r_l->code.$r_l->location.'</option>';
									}else{
										print '<option value="'.$r_l->code.'">'.$r_l->code.$r_l->location.'</option>';
									}
								};
							?>
						</select>
					</td>
					</tr>
					<tr>
						<td>
							<label>Date from: </label>
						</td>
						<td>							
							<input style="margin-left:10px;" type="date" name="dateFrom"/>							 
							<label> to </label>							
							<input style="margin-left:10px;" type="date" name="dateTo"/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>    
							<input style="margin-left:10px;" type="submit" name="export" value="Export"/>
						</td>
					</tr>
				</table>
				
			</form>
		</div>  
	</body>   
</html>