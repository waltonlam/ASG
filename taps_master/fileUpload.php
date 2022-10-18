<?php
require_once "iconn.php";
require_once "header2.php";
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

session_start();
$message = ''; 

if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload the File'){
  if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK){
    // uploaded file details
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = $_FILES['uploadedFile']['name'];
    $fileSize = $_FILES['uploadedFile']['size'];
    $fileType = $_FILES['uploadedFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    // removing extra spaces
    //$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    // file extensions allowed
    $allowedfileExtensions = array('zip', 'txt', 'xls', 'docx');

    if (in_array($fileExtension, $allowedfileExtensions)){
      // directory where file will be moved
      $uploadFileDir = 'uploadDocx/';
      //$dest_path = $uploadFileDir . $newFileName;
      $dest_path = $uploadFileDir . $fileName;
      if(move_uploaded_file($fileTmpPath, $dest_path)) {
        $message = 'File uploaded successfully.';
      } else {
        $message = 'An error occurred while uploading the file to the destination directory. Ensure that the web server has access to write in the path directory.';
      }
    }else{
      $message = 'Upload failed as the file type is not acceptable. The allowed file types are:' . implode(',', $allowedfileExtensions);
    }
  }
  else
  {
    $message = 'Error occurred while uploading the file.<br>';
    $message .= 'Error:' . $_FILES['uploadedFile']['error'];
  }
}

$_SESSION['message'] = $message;
header("Location: getMSWordContent.php");

function readDocx($filename){
  $zip = new ZipArchive();
  if ($zip->open($filename)) {
      $content = $zip->getFromName("word/document.xml");
      $zip->close();
      $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
      $content = str_replace('</w:r></w:p>', "\r\n", $content);

      return strip_tags($content, '<w:t>');
      //return $content;
  }
  return false;
}

$doc_contents = readDocx("PFOS_TAP_Air_21A123.docx");
echo $doc_contents;
$matched =  preg_match_all("/(__([^\s]+)__)/sU",$doc_contents,$matches);
//echo "<pre>".print_r(array_values(array_unique($matches[1])),true)."</pre>";

// Saving the document as HTML file...
$phpWord = new \PhpOffice\PhpWord\PhpWord();
// Adding an empty Section to the document...
$section = $phpWord->addSection();
// Adding Text element to the Section having font styled by default...
$section->addText($doc_contents);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
$objWriter->save('outputHtml/PFOS_TAP_Air_21A123.html');


?>