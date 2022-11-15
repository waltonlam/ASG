<?php
namespace Phppot;
use Phppot\DataSource;
require_once "iconn.php";
require_once "header2.php";
require_once __DIR__ . '/lib/ImageModel.php';
$imageModel = new ImageModel();
if (isset($_POST["submit"])) {
    $result = $imageModel->uploadImage();
    $id = $imageModel->updateImage($result, $_GET["id"], $_POST['site_code'], $_POST['remark']);
	/*if (empty($id)) {
		echo '<script type="text/javascript"> 
				document.getElementById("message").innerHTML += "The Incident Report is updated."; 
			  </script>';
		//print '<span id="message" style="color:red">The Incident Report is updated.</span>';
		//$_SESSION['message'] = "The Incident Report is updated.";
	} else {
		echo "<script> 
       			document.getElementById('message').innerHTML += 'Failed to update Incident Report.'; 
			  </script>";
		//print '<span id="message" style="color:red">Failed to update Incident Report.</span>';
		//$_SESSION['message'] = "Failed to update Incident Report.";
	}*/
}
$result = $imageModel->selectImageById($_GET["id"]);
?>
<html>
	<head>
		<link href="assets/style.css" rel="stylesheet" type="text/css" />
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
		<div>
			<h2>Edit Incident Report</h2>
			<span id="message" style="color:red"></span>
			<hr>
			<form action="?id=<?php echo $result[0]['id']; ?>" method="post" name="frm-edit" enctype="multipart/form-data"	onsubmit="return imageValidation()">
				<table>
					<tr>	
						<td style="width: 160px; vertical-align: top;">Site: </td>
						<td>
							<select name=site_code id="site_code">
								<?php
									while ($r_l=$result_loc->fetch_object()){
										if ($r_l->code==$result[0]["site_code"]){
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
							<div class="preview-container">
								<img src="<?php echo $result[0]["image"]?>" class="img-preview"	alt="photo">
								<div>Name: <?php echo $result[0]["name"]?></div>
							</div>
							<div Class="input-row">
								<input type="file" name="image" id="input-file" class="input-file"	accept=".jpg,.jpeg,.png" value="">
							</div>
						</td>
					</tr>
				</table>				
				<hr>
				<input type="submit" name="submit" value="Submit"> 
				<input type="button" name="cancel" value="Cancel" onClick="document.location.href='incidentReportList.php'"/>						
			</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="assets/validate.js"></script>
	</body>
</html>
