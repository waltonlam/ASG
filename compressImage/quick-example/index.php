<?php
$sourceFile = 'image.jpg';
$outputFile = 'compressed-image.jpg';
$outputQuality = 60;
$imageLayer = imagecreatefromjpeg($sourceFile);
imagejpeg($imageLayer, $outputFile, $outputQuality);
?>