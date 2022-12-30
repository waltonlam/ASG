<?php
	namespace Phppot;
	use Phppot\DataSource;
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/ImageModel.php';
	$imageModel = new ImageModel();

	if (isset($_POST["submit"])) {
		//$result = $imageModel->uploadImage();
		$message = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ""; 
		$compoundGroup = "";
		$compound = "";
		if (!empty($_POST['compoundGrp'])) {
			foreach ($_POST['compoundGrp'] as $selectedCompoundGrp) {
				$compoundGroup = $selectedCompoundGrp;
			}
		}

		if (!empty($_POST['compound'])) {
			foreach ($_POST['compound'] as $selected) {
				$compound .= $selected.',';
			}
		}

		$id = $imageModel->updateIncidentReport($_GET["id"], $_POST['site_code'], $_POST['remark'], $compoundGroup, $compound);
		if(empty($id)){
			$type = "success";
			$message = "Incident report is updated successfully.";
		}else{
			$type = "error";
			$message = "Incident report is failed to update.";
		}

		$create_by = $_SESSION['vuserid'];

		// File upload configuration 
		$targetDir = "/opt/lampp/htdocs/taps/uploads/"; 
		$allowTypes = array('jpg','png','jpeg','gif'); 
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
					$type = "error";
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
	}

	if(isset($_POST['delete'])){
		$all_id = $_POST['site_photo_delete_id'];
		if(empty($all_id)){
			$type = "error";
			$message = "Please select at least one site photo to delete.";
		}else{
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
			$deleteQuery = $dbc->query($query);
			if($deleteQuery){
				$type = "success";
				$message = "Site photos deleted successfully.";
			}else{
				$type = "error";
				$message = "Site photos not deleted.";
			}
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
				print '<p class="text--error">'.'Site Configuration Error.</p>';
				exit();
			}

			$selectCompoundGroup = "select * from category order by id ASC;";
			$compoundGrpResult = $dbc->query($selectCompoundGroup);

			$compoundStr = substr($result[0]["compound"], 0, -1);
			$compoundStr_arr = explode (",", $compoundStr); 
			//print_r($compoundStr_arr);

			$selectCompound = "select * from compound where code = '".$result[0]["compound_grp"]."';";
			$compoundResult = $dbc->query($selectCompound);
			//print_r($compoundResult);
		?>
		<div>
			<h2 style="margin-left:10px">View Incident Report</h2>
			<hr>
			<form action="?id=<?php echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data">
				<div id="response"
					class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
					<?php if(!empty($message)) { echo $message; } ?>
				</div>  
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
							<td style="width: 160px; vertical-align: top;">Compound Group:</td>
							<td>
								<select name="compoundGrp[]" id="compoundGrpList" onChange="getCompound()" size=10>
									<option value="">Select Compound Group</option>
									<?php foreach ($compoundGrpResult as $compoundGrp) { ?>
										<option value="<?php echo $compoundGrp["id"]; ?>" <?php echo ($compoundGrp["id"] == $result[0]["compound_grp"]) ? 'selected="selected"' : "" ?>><?php echo $compoundGrp["item"]; ?></option>
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
									<?php foreach ($compoundResult as $compound) { ?>
										<option value="<?php echo $compound["id"]; ?>" <?php echo (in_array($compound["id"], $compoundStr_arr)) ? 'selected="selected"' : "" ?>><?php echo $compound["name"]; ?></option>
									<?php } ?>
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
									No Site Photo Found. Please upload.
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
	</body>
</html>