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
		 
		$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
		$fileNames = array_filter($_FILES['files']['name']); 
		if(!empty($fileNames)){ 
			$incidentId = $imageModel->insertIncidentReport($_POST['site_code'], $_POST['remark']);

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
					$statusMsg = "Files are uploaded successfully.".$errorMsg; 
				}else{ 
					$statusMsg = "There was an error uploading your file."; 
				} 
			}else{ 
				$statusMsg = "Upload failed! ".$errorMsg; 
			} 
		}else{ 
			$statusMsg = 'Please select a file to upload.'; 
		} 
		header('Location: addSitePhoto.php');
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
		</style>
	</head>
	<body>
		<?php
			$l = "select code from site order by code ASC;";
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Site Configuration Error!</p>';
				exit();
			}
		?>
		<div class="form-container">
			<h2 style="margin-left:10px">Add Incident Report</h2>
			<span id="message" style="color:red"><?php echo $statusMsg ?></span>
			<hr>				
			<form action="" method="post" name="frm-add" enctype="multipart/form-data">
				<table style="margin-left:10px">
					<tr>	
						<td style="width: 160px; vertical-align: top;">Site: </td>
						<td>
							<select name=site_code id="site_code">
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
						<td style="width: 160px; vertical-align: top;">Remark: </td>
						<td><textarea id="remark" name="remark" rows="7" cols="100"></textarea></td>
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
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>