<?php
	namespace Phppot;
	use Phppot\DataSource;
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/ImageModel.php';
	$imageModel = new ImageModel();

	/*if (isset($_POST["submit"])) {
		$result = $imageModel->uploadImage();
		$id = $imageModel->updateImage($result, $_GET["id"], $_POST['site_code'], $_POST['remark']);
	}*/

	if(isset($_POST['delete'])){
		$all_id = $_POST['site_photo_delete_id'];
		$extract_id = implode(',' , $all_id);
		// echo $extract_id;

		$query = "DELETE FROM site_photo WHERE id IN($extract_id) ";
		//$query_run = mysqli_query($con, $query);
		$deleteQeury = $dbc->query($query);
		if($deleteQeury){
			$msg = "Site Photos Deleted Successfully";
		}else{
			$msg = "Site Photos Not Deleted";
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
				print '<p class="text--error">'.'Site Configuration Error!</p>';
				exit();
			}
		?>
		<div>
			<h2>View Incident Report</h2>
			<span id="message" style="color:red"><?php echo $msg ?></span>
			<hr>
			<form action="?id=<?php echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data"	onsubmit="return imageValidation()">
				<table>
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
						<td style="width: 160px; vertical-align: top;">Remark: </td>
						<td><textarea id="remark" name="remark" rows="7" cols="100"><?php echo $result[0]["remark"]?></textarea></td>
					</tr>
					<tr>
						<td style="width: 160px; vertical-align: top;">Site Photo: </td>
						<td>
							<div >
							<?php
								$sitePhotoResultList = $imageModel->getSitePhotoById($_GET["id"]);	
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
							?>
							</div>
							
							<!--div Class="input-row">
								<input type="file" name="image" id="input-file" class="input-file" accept=".jpg,.jpeg,.png">
							</div-->
						</td>
					</tr>
				</table>				
				<hr>
				<!--input type="submit" name="submit" value="Submit"--> 
				<input type="submit" name="delete" value="Delete"> 
				<input type="button" name="cancel" value="Cancel" onClick="document.location.href='incidentReportList.php'"/>						
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>