<?php
//https://phpword.readthedocs.io/en/latest/writersreaders.html
//https://stackoverflow.com/questions/10646445/read-word-document-in-php
require_once "iconn.php";
require_once "header2.php";
/*require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

function readDocx($filename){
    $zip = new ZipArchive();
    if ($zip->open($filename)) {
        $content = $zip->getFromName("word/document.xml");
        $zip->close();
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);

        return strip_tags($content);
    }
    return false;
}*/

/*function read_docx($filename){
 
    $striped_content = '';
    $content = '';
 
    if(!$filename || !file_exists($filename)) return false;
 
    $zip = zip_open($filename);
    if (!$zip || is_numeric($zip)) return false;
 
    while ($zip_entry = zip_read($zip)) {
 
        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
 
        if (zip_entry_name($zip_entry) != "word/document.xml") continue;
 
        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
 
        zip_entry_close($zip_entry);
    }
    zip_close($zip);      
    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    //$striped_content = strip_tags($content);
 
    return $content;
}*/

/*$doc_contents = readDocx("PFOS_TAP_Air_21A123.docx");
echo $doc_contents;
$matched =  preg_match_all("/(__([^\s]+)__)/sU",$doc_contents,$matches);
echo "<pre>".print_r(array_values(array_unique($matches[1])),true)."</pre>";

// Saving the document as HTML file...
$phpWord = new \PhpOffice\PhpWord\PhpWord();
// Adding an empty Section to the document...
$section = $phpWord->addSection();
// Adding Text element to the Section having font styled by default...
$section->addText($doc_contents);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
$objWriter->save('outputHtml/PFOS_TAP_Air_21A123.html');
*/
?>

<!--html>
<head>
<link href="assets/style.css" rel="stylesheet" type="text/css" />
	<style>
	.button {
	background-color: #4D9BF3;
	border: none;
	color: white;
	padding: 15px 32px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	margin: 4px 2px;
	cursor: pointer;
	}
	</style>
</head>
<body>

	<div class="form-container">
		<br>
		<h2>Upload Docx file</h2>
		<form action="" method="post" name="frm-add"
			enctype="multipart/form-data" onsubmit="return readDocx()">
			<div Class="input-row">
				<input type="file" name="image" id="input-file" class="input-file"
					accept=".docx">
			</div>
			<input type="submit" name="send" value="Submit" class="button"> 
			<input type="button" name="cancel" value="Cancel" class="button" onClick="document.location.href='exportReport.php'"/>
			<br>
			<span id="message"></span>	
	</div>
	</form>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
		crossorigin="anonymous"></script>
	<script src="assets/validate.js"></script>
</body>
</html-->

<?php
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
  <title>PHP File Upload</title>
</head>
<body>
  <?php
    if (isset($_SESSION['message']) && $_SESSION['message']){
      printf('<b>%s</b>', $_SESSION['message']);
      unset($_SESSION['message']);

    }
  ?>
  <h2>Upload Docx file</h2>
  <form method="POST" action="fileUpload.php" enctype="multipart/form-data">
    <div>
      <span>Upload a File:</span>
      <input type="file" name="uploadedFile" />
    </div>
    <input type="submit" name="uploadBtn" value="Upload the File" />
  </form>
</body>
</html>