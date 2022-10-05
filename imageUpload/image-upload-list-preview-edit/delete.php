<?php
namespace Phppot;

use Phppot\DataSource;
require_once __DIR__ . '/lib/ImageModel.php';
$imageModel = new ImageModel();
$id=$_REQUEST['id'];
$result = $imageModel->deleteImageById($id);
header("Location: index.php");
?>
