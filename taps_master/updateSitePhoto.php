<?php
namespace Phppot;

use Phppot\DataSource;
require_once __DIR__ . '/lib/ImageModel.php';
$imageModel = new ImageModel();
if (isset($_POST["submit"])) {
    $result = $imageModel->uploadImage();
    $id = $imageModel->updateImage($result, $_GET["id"]);
}
$result = $imageModel->selectImageById($_GET["id"]);

?>
<html>
<head>
<link href="assets/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="form-container">
		<h1>View/Edit Site Photo</h1>

		<form action="?id=<?php echo $result[0]['id']; ?>" method="post"
			name="frm-edit" enctype="multipart/form-data"
			onsubmit="return imageValidation()">
			<div class="preview-container">
				<img src="<?php echo $result[0]["image"]?>" class="img-preview"
					alt="photo">
				<div>Name: <?php echo $result[0]["name"]?></div>
			</div>
			<div Class="input-row">
				<input type="file" name="image" id="input-file" class="input-file"
					accept=".jpg,.jpeg,.png" value="">
			</div>
			<button type="submit" name="submit" class="btn-link">Save</button>
			<input type="button" name="cancel" value="Cancel" class="btn-link" onClick="document.location.href='sitePhotoList.php'"/>
			<br>
			<span id="message"></span>
	
	</div>
	</form>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
		crossorigin="anonymous"></script>
	<script src="assets/validate.js"></script>
</body>
</html>
