<?php
	namespace Phppot;
	use Phppot\DataSource;
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/ImageModel.php';
	$imageModel = new ImageModel();

	if(isset($_POST['submit'])){ 
		$create_by = $_SESSION['vuserid'];

		// File upload configuration 
		$targetDir = "/opt/lampp/htdocs/taps/uploads/"; 
		$allowTypes = array('jpg','png','jpeg','gif'); 
		 
		$message = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
		$fileNames = array_filter($_FILES['files']['name']); 

		$compoundGroup = "";
		$compound = "";
		$remark = "";

		if (empty($_POST['site_code'])) {
			$type = "error";
			$message = 'Please select a site.'; 
		}else{
			if (!empty($_POST['compoundGrp'])) {
				foreach ($_POST['compoundGrp'] as $selectedCompoundGrp) {
					$compoundGroup = $selectedCompoundGrp;
				}
			}else{
				$compoundGroup = "";
			}

			if (!empty($_POST['compound'])) {
				foreach ($_POST['compound'] as $selected) {
					$compound .= $selected.',';
				}
			}else{
				$compound = "";
			}

			if (empty($_POST['remark'])) {
				$remark = "";
			}else{
				$remark = $_POST['remark'];
			}

			$incidentId = $imageModel->insertIncidentReport($_POST['site_code'], $remark, $compoundGroup, $compound);
			$type = "success";
			$message = 'Incident report is created.'; 
		}

		if(!empty($fileNames)){ 
			foreach($_FILES['files']['name'] as $key=>$val){ 
				// File upload path 
				$fileName = basename($_FILES['files']['name'][$key]); 
				$targetFilePath = $targetDir . $fileName; 
				
				// Check whether file type is valid 
				$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
				if(in_array($fileType, $allowTypes)){ 
					// Upload file to server 
					if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
						// Image db insert sql 
						$insertValuesSQL .= "('".$fileName."', '/taps/uploads/".$fileName."', '".$_POST['site_code']."', '".$incidentId."', NOW(), NOW(), '".$create_by."', '".$create_by."'),"; 
					}else{ 
						$errorUpload .= $_FILES['files']['name'][$key].' | '; 
					} 
				}else{ 
					$errorUploadType .= $_FILES['files']['name'][$key].' | '; 
				} 
			} 
			// Error message 
			$errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
			$errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
			$errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
			
			if(!empty($insertValuesSQL)){ 
				$insertValuesSQL = trim($insertValuesSQL, ','); 
				// Insert image file name into database 
				$insert = $dbc->query("INSERT INTO site_photo (file_name, path, site_code, incident_id, create_date, last_upd_date, create_by, last_upd_by) VALUES $insertValuesSQL"); 
				if($insert){ 
					$type = "success";
					$message = "Files are uploaded successfully.".$errorMsg; 
				}else{ 
					$type = "error";
					$message = "There was an error uploading your file."; 
				} 
			}else{ 
				$type = "error";
				$message = "Upload failed! ".$errorMsg; 
			} 
		}
		/*else{ 
			$type = "error";
			$message = 'Please select a file to upload.'; 
		} */
	} 
?>

<html>
	<head>
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

		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
		<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
		<script>
			function getCompound() {
				var str='';
				var val=document.getElementById('compoundGrpList');
				for (i=0;i< val.length;i++) { 
					if(val[i].selected){
						str += val[i].value + ','; 
					}
				}         
				var str=str.slice(0,str.length -1);
				$.ajax({          
					type: "GET",
					url: "getCompound.php",
					data:'compoundGrp_id='+str,
					success: function(data){
						$("#compoundList").html(data);
					}
				});
			}
		</script>
	</head>
	<body>
		<?php
			$l = "select code from site order by code ASC;";
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Site Configuration Error!</p>';
				exit();
			}

			$selectCompoundGroup = "select * from category order by id ASC;";
			$compoundGrpResult = $dbc->query($selectCompoundGroup);
		?>
		<div class="form-container">
			<h2 style="margin-left:10px">Add Incident Report</h2>
			<hr>				
			<form action="" method="post" name="frm-add" enctype="multipart/form-data">
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div>  	
				<table style="margin-left:10px">
					<tr>	
						<td style="width: 160px; vertical-align: top;">Site<span style="color:red">*</span>:</td>
						<td>
							<select name=site_code id="site_code">
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
							<select name="compoundGrp[]" id="compoundGrpList" onChange="getCompound()" size=10>
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
						<td style="width: 160px; vertical-align: top;">Remark: </td>
						<td><textarea id="remark" name="remark" rows="7" cols="100"></textarea></td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">&nbsp;</td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">Upload Site Photo: </td>
						<td>
							<div>
								<input type="file" name="files[]" multiple>
							</div>
						</td>
					</tr>
				</table>				
				<hr>
				<input type="submit" style="margin-left:10px" name="submit" value="Submit"> 
				<input type="button" style="margin-left:10px" name="cancel" value="Cancel" onClick="document.location.href='incidentReportList.php'"/>		
			</form>
		</div>
	</body>
</html>