<?php 
ob_start();

function query_to_csv($db_conn, $query, $filename, $attachment = false, $headers = true) {
       
   if($attachment) {
       // send response headers to the browser
       header( 'Content-Type: text/csv' );
       header( 'Content-Disposition: attachment;filename='.$filename);
       $fp = fopen('php://output', 'w');
   } else {
       $fp = fopen($filename, 'w');
   }
  
   $result = mysql_query($query, $db_conn) or die( mysql_error( $db_conn ) );
  
   if($headers) {
       // output header row (if at least one row exists)
       $row = mysql_fetch_assoc($result);
       if($row) {
           fputcsv($fp, array_keys($row));
           // reset pointer back to beginning
           mysql_data_seek($result, 0);
       }
   }
  
   while($row = mysql_fetch_assoc($result)) {
       fputcsv($fp, $row);
   }
  
   fclose($fp);

   ob_end_flush();
}



function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}


