<?php
if (!empty($_POST["submit"])) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        $conn = mysqli_connect('localhost', 'root', '', 'blog_eg');
        $imgData = file_get_contents($_FILES['userImage']['tmp_name']);
        $imageProperties = getimageSize($_FILES['userImage']['tmp_name']);
        $null = NULL;
        $sql = "INSERT INTO tbl_image_data(image_type ,image_data)
	VALUES(?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sb", $imageProperties['mime'], $null);
        
        $stmt->send_long_data(1, $imgData);
        
        $stmt->execute();
        $currentId = $stmt->insert_id;
        
    }
}
?>

<html>
<head>
<link href="assets/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="form-container">
		<h1>Upload Image Blob</h1>

		<form action="" method="post"
			name="frm-edit" enctype="multipart/form-data" >
			<?php if(!empty($currentId)) { ?>
			<div class="preview-container">
				<img src="image-view.php?image_id=<?php echo $currentId; ?>" class="img-preview"
					alt="photo">
			</div>
			<?php } ?>
			<div Class="input-row">
				<input type="file" name="userImage" id="input-file" class="input-file"
					accept=".jpg,.jpeg,.png" value="" required>
			</div>
			<input type="submit" name="submit" class="btn-link" value="Save">
			<span id="message"></span>
	
	</div>
	</form>
</body>
</html>