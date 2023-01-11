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

	$selectCompoundGroup = "select * from category order by id ASC;";
	$compoundGrpResult = $dbc->query($selectCompoundGroup);

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
		<script>
			function getCompound() {
				var str='';
				var val=document.getElementById('compoundGrpList');
				for (i=0;i< val.length;i++) { 
					if(val[i].selected){
						str += "'" + val[i].value + "'" + ','; 
					}
				}    
				var str=str.slice(0,str.length -1);
				$.ajax({          
					type: "GET",
					url: "getCompoundName.php",
					data:'compoundGrp_id='+str,
					success: function(data){
						$("#compoundList").html(data);
					}
				});
			}
		</script>	
	</head>
	<body>
		<div>
			<h2>Export Glab Sample</h2>
			<hr>
		</div>
		<div>
			<form action="exportGlabXls.php" method="post" id="export-form">
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div> 
				<table style="margin-left:10px">
					<tr>
						<td style="width: 160px; vertical-align: top;">Site:</td>
						<td>
							<select style="width: 160px; vertical-align: top;" name=site_code id="site_code">
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
						<td style="width: 160px; vertical-align: top;">&nbsp;</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">Compound Group:</td>
						<td>
							<select name="compoundGrp[]" id="compoundGrpList" onChange="getCompound()" multiple size=10>
								<option value="">Select Compound Group</option>
								<?php foreach ($compoundGrpResult as $compoundGrp) { ?>
									<option value="<?php echo $compoundGrp["id"]; ?>"><?php echo $compoundGrp["item"]; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">&nbsp;</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">Compound: </td>
						<td>
							<select name="compound[]" id="compoundList" multiple size=10>
								<option value="">Select Compound</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">&nbsp;</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">
							Date from: 
						</td>
						<td>							
							<input style="width: 160px; vertical-align: top;" type="date" name="dateFrom"/>							 
							to 					
							<input style="width: 160px; vertical-align: top;" type="date" name="dateTo"/>
						</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">&nbsp;</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">&nbsp;</td>
						<td>    
							<input style="width: 160px; vertical-align: top;" type="submit" name="export" value="Export"/>
						</td>
					</tr>
				</table>
			</form>
		</div>  
	</body>   
</html>