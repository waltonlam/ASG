<?php
session_start();
require('iconn.php');


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=result.csv');
$output = fopen('php://output', 'w');

if(isset($_FILES['file'])){   
    if ($_FILES["file"]["size"] > 0) {
        $file_name = $_FILES['file']['name'];
        $file_tmp =$_FILES['file']['tmp_name'];
        move_uploaded_file($file_tmp,"img/".$file_name);
        shell_exec('"C:\\Program Files\\Tesseract-OCR\\tesseract" "C:\\xampp_20210705\\htdocs\\taps\\img\\'.$file_name.'" out');
        $myfile = fopen("out.txt", "r") or die("Unable to open file!");
        fclose($myfile);
        $lines = file('out.txt');
        $SampleID_line = preg_grep("/Sample I.D.*/", $lines); 
        $SampleID_line_key = key($SampleID_line);
        $name = $SampleID_line[$SampleID_line_key];
        $sid_lst = substr($name,13);
        $sid = array(substr_count($sid_lst," ")+1);
        $spc=0;
        $total_samples=substr_count($sid_lst," ");
        for ($e = 0; $e<=$total_samples; $e++)  {
            if ($e<$total_samples){
                $spc = strpos($sid_lst," ");
                $sid[$e]=substr($sid_lst,0,$spc);
            }else{ 
                $sid[$e]=$sid_lst;
            }
            
            $sid_lst = substr($sid_lst,$spc+1);
        };
        $sid_lst = substr($name,13); 
        $sid_cnt=substr_count($sid_lst," ");
        $sample_line_id=0;
        $sample_line_id=$SampleID_line_key+2;
        $line_cnt=0;
        $trans=array();
        for ($e = 0; $e<=$sid_cnt; $e++)  { 
            for ($sample_line_id; $sample_line_id<=24; $sample_line_id++)  {   
                $sid_val_lst=$lines[$sample_line_id];
                $spc = strpos($sid_val_lst," ");
                $comp_name = substr($sid_val_lst,0,$spc); 
                $val_list = substr($sid_val_lst,$spc+1);  
                $gp="";
                $cid="";
                $cp_sql="select id, code from compound where name='".$comp_name."'";
                if (!$result = mysqli_query($dbc, $cp_sql)) {
                    exit(mysqli_error($dbc));
                }else{
                    if ($result -> num_rows){
                        $r_rev = mysqli_fetch_assoc($result);
                        $gp=$r_rev['code'];
                        $cid=$r_rev['id'];	            
                    }else{
                        $cid=$comp_name;
                    }
                }
                for ($f = 0; $f<=$e; $f++)  {                                               
                    $spc = strpos($val_list," ");                   
                    if ($f<$e){ 
                        $val_list = substr($val_list,$spc+1);
                    }                    
                    if ($f==$e){ 
                        $final_val = substr($val_list,0,$spc);
                    }
                }
                $trans[$line_cnt][0] = substr($sid[$e],0,2);  
                $trans[$line_cnt][1] = "20".substr($sid[$e],6,2)."/".substr($sid[$e],8,2)."/".substr($sid[$e],10,2); 
                $trans[$line_cnt][2] = $sid[$e];  
                $trans[$line_cnt][3] = $gp; 
                $trans[$line_cnt][4] = $cid; 
                $trans[$line_cnt][5] = $final_val;  
                $line_cnt++;
            }        
            $sample_line_id=$SampleID_line_key+2;
        };
        $hdr="";
         $hdr="SITE,STRTDATE,SAMPID,GROUP,COMPOUND CODE,CONC (ppbv)";
        $hdr = explode(',', $hdr);
        fputcsv($output, $hdr);
            foreach ($trans as $row) {
                fputcsv($output, (array)$row, ",");
            }

        exit();
        for ($e = 1; $e <= count($lines); $e++) {
            $J .= substr($name,6)."-";
            
        }
        echo $J;
        echo "</pre>";
    }


}
            
    

?>

