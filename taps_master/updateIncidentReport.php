<?php
	namespace Phppot;
	use Phppot\DataSource;
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/ImageModel.php';
	$imageModel = new ImageModel();

	if (isset($_POST["submit"])) {
		//$result = $imageModel->uploadImage();
		$id = $imageModel->updateIncidentReport($_GET["id"], $_POST['site_code'], $_POST['remark']);
		if(empty($id)){
			$msg = "Incident report is updated successfully.";
		}else{
			$msg = "Incident report is failed to update.";
		}

		$create_by = $_SESSION['vuserid'];

		// File upload configuration 
		$targetDir = "/opt/lampp/htdocs/taps/uploads/"; 
		$allowTypes = array('jpg','png','jpeg','gif'); 
		 
		$statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
		$fileNames = array_filter($_FILES['files']['name']); 
		if(!empty($fileNames)){ 
			//$incidentId = $imageModel->insertIncidentReport($_POST['site_code'], $_POST['remark']);

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
						$insertValuesSQL .= "('".$fileName."', '/taps/uploads/".$fileName."', '".$_POST['site_code']."', '".$_GET["id"]."', NOW(), NOW(), '".$create_by."', '".$create_by."'),"; 
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

	if(isset($_POST['delete'])){
		$all_id = $_POST['site_photo_delete_id'];
		$extract_id = implode(',' , $all_id);

		//Delete files in SFTP
		$tobeDeleteItemList = $imageModel->getTobeDeleteItemsById($extract_id);
		for($x = 0; $x < count($tobeDeleteItemList); $x++) {
			if(isset($tobeDeleteItemList[$x])) {
				$path= $tobeDeleteItemList[$x]["path"];
				unlink("/opt/lampp/htdocs".$path);
			} else {
				echo "No Delete Items Found";
			}
		}	

		//Delete files in db
		$query = "DELETE FROM site_photo WHERE id IN($extract_id) ";
		$deleteQeury = $dbc->query($query);
		if($deleteQeury){
			$msg = "Site photos deleted successfully.";
		}else{
			$msg = "Site photos not deleted.";
		}
	}

	$result = $imageModel->getIncidentReportById($_GET["id"]);
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
		<?php
			$l = "select code from site order by code ASC;";
			$result_loc=$dbc->query($l);
			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Site Configuration Error.</p>';
				exit();
			}
		?>
		<div>
			<h2 style="margin-left:10px">View Incident Report</h2>
			<span id="message" style="color:red"><?php echo $msg ?></span>
			<span id="message" style="color:red"><?php echo $statusMsg ?></span>
			<hr>
			<form action="?id=<?php echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data">
				<div style="overflow-x: auto;">
					<table style="margin-left:10px">
						<tr>	
							<td style="width: 160px; vertical-align: top;">Site: </td>
							<td>
								<select name=site_code id="site_code">
									<?php
										while ($r_l=$result_loc->fetch_object()){
											if ($r_l->code==$result[0]["site_id"]){
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
							<td style="width: 160px; vertical-align: top;">Remark: </td>
							<td><textarea id="remark" name="remark" rows="7" cols="100"><?php echo $result[0]["remark"]?></textarea></td>
						</tr>
						<tr>
							<td style="width: 160px; vertical-align: top;">&nbsp;</td>
						</tr>
						<tr>
							<td style="width: 160px; vertical-align: top;">Site Photo: </td>
							<td>
								<div>
								<?php
									$sitePhotoResultList = $imageModel->getSitePhotoById($_GET["id"]);	
									if(!empty($sitePhotoResultList)) {
										for($x = 0; $x < count($sitePhotoResultList); $x++) {
											if(isset($sitePhotoResultList[$x])) {
												$filename = $sitePhotoResultList[$x]["file_name"];
												$sitePhotoId = $sitePhotoResultList[$x]["id"];
												$path= $sitePhotoResultList[$x]["path"];
								?>			
												<input style="vertical-align: top;" type="checkbox" name="site_photo_delete_id[]" value="<?= $sitePhotoId ?>">
												<a href="<?php echo $path ?>" class="btn-action" target="_blank">
													<img src="<?php echo $path?>" class="img-preview" alt="photo"> 
												</a>
								<?php		
											} else {
												echo "No Site Photo Found";
											}
										}	
									}else{
								?>		
									No Site Photo Found. Please upload...
								<?php
									}
								?>
								</div>
							</td>
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
				</div>			
				<hr>
				<input type="submit" style="margin-left:10px" name="submit" value="Save"> 
				<input type="submit" style="margin-left:10px" name="delete" value="Delete" <?php if (empty($sitePhotoResultList)) { ?> style="display: none" <?php } ?> > 
				<input type="button" style="margin-left:10px" name="cancel" value="Cancel" onClick="document.location.href='incidentReportList.php'"/>						
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>