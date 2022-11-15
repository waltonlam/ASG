<?php
	namespace Phppot;
	use Phppot\DataSource;
	require_once "iconn.php";
	require_once "header2.php";
	require_once __DIR__ . '/lib/ImageModel.php';
	$imageModel = new ImageModel();

	if (isset($_POST['send'])) {
		if (file_exists('../uploads/' . $_FILES['image']['name'])) {
			$fileName = $_FILES['image']['name'];
			$_SESSION['message'] = $fileName . " file already exists.";
		} else {
			$result = $imageModel->uploadImage();
			//echo '<script>console.log("'.$_POST['site_code'].'"); </script>';
			$id = $imageModel->insertImage($result, $_POST['site_code'], $_POST['remark']);
			if (! empty($id)) {
				$_SESSION['message'] = "Image added to the server and database.";
			} else {
				$_SESSION['message'] = "Image upload incomplete.";
			}
		}
		header('Location: addSitePhoto.php');
	}
?>

<html>
	<head>
		<style>
			input[type=button], input[type=submit], input[type=reset] {
				background-color: #4D9BF3;
				border: none;
				color: white;
				padding: 16px 32px;
				text-decoration: none;
				margin: 4px 2px;
				cursor: pointer;
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
			<h2>Add Incident Report</h2>
			<span id="message" style="color:red"></span>
			<hr>				
			<form action="" method="post" name="frm-add" enctype="multipart/form-data" onsubmit="return imageValidation()">
				<br>
				<table>
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
							<div Class="input-row">
								<input type="file" name="image" id="input-file" class="input-file" accept=".jpg,.jpeg,.png">
							</div>
						</td>
					</tr>
				</table>
				<br>				
				<hr>
				<input type="submit" name="send" value="Submit"> 
				<input type="button" name="cancel" value="Cancel" onClick="document.location.href='incidentReportList.php'"/>		
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>