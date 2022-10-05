<?php
    $conn = mysqli_connect('localhost', 'root', '', 'blog_eg');
    if(isset($_GET['image_id'])) {
        $sql = "SELECT image_type,image_data FROM tbl_image_data WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_GET['image_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array();
		
		header("Content-type: " . $row["image_type"]);
        echo $row["image_data"];
	}
	mysqli_close($conn);
?>