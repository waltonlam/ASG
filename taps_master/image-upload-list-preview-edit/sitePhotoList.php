<?php
namespace Phppot;

use Phppot\DataSource;
require_once __DIR__ . '/lib/ImageModel.php';
$imageModel = new ImageModel();
?>
<html>
<head>
	<title>Display all records from Database</title>
	<link href="assets/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="image-datatable-container">
		<table class="image-datatable" width="100%">
			<tr>
				<th width="70%">Image</th>
				<th width="30%">Action</th>
			</tr>
        <?php
        $result = $imageModel->getAllImages();
        ?>

        <tr>
        <?php
        if (! empty($result)) {
            foreach ($result as $row) {
		?>
				<td>
					<img src="<?php echo $row["image"]?>" class="profile-photo" alt="photo"><?php echo $row["name"]?>
				</td>
				<td>
					<a href="update.php?id=<?php echo $row['id']; ?>" class="btn-action">Edit</a> 
					<a onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn-action">Delete</a>
					<a href="<?php echo $row['image']; ?>" class="btn-action" target="_blank">Download</a> 
				</td>
			</tr>
		<?php
            }
        }
        ?>
		</table>
		<a href="insert.php" class="btn-link">Add Image</a>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
		crossorigin="anonymous">
	</script>
	<script type="text/javascript" src="assets/validate.js"></script>
</body>

</html>