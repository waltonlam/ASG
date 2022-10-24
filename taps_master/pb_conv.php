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
        shell_exec('"C:\\Program Files\\Tesseract-OCR\\tesseract" "C:\\xampp\\htdocs\\taps\\img\\'.$file_name.'" out');       
        $myfile = fopen("out.txt", "r") or die("Unable to open file!");
        fclose($myfile);
        $lines = file('out.txt');
       
        $SampleID_line = preg_grep("/Sample I.D.*/", $lines);  
        $SampleID_line_key = key($SampleID_line);
        $name = $SampleID_line[$SampleID_line_key];
        $sid_lst = substr($name,13);
        $sid = array(substr_count($sid_lst," ")+1);
       // echo ' $sid: '.implode($sid); 
        $spc=0;
        $pg_sample_ID_line = preg_grep("/pg\/sample/", $lines); 
        $pg_sample_ID_line_key = key($pg_sample_ID_line);
        $pg_sample_hd_lst = $pg_sample_ID_line[$pg_sample_ID_line_key];
       // echo ' $pg_sample_hd_lst:    '.$pg_sample_hd_lst; 

        $total_samples=substr_count($sid_lst," ");
       // echo ' $total_samples    '.$total_samples; 

        for ($e = 0; $e<=$total_samples; $e++)  {
            if ($e<$total_samples){
                $spc = strpos($sid_lst," ");
               // echo ' $spc:    '.$spc; 
                $sid[$e]=substr($sid_lst,0,$spc);
              //  echo ' $sid[$e]:    '.$sid[$e]; 
            }else{  
                $sid[$e]=$sid_lst;
              //  echo ' $sid[$e]2:    '.$sid[$e]; 
            }
            
            $sid_lst = substr($sid_lst,$spc+1);
          //  echo ' $sid_lst:    '.$sid_lst;
        };

        $sid_lst = substr($name,13); 
        //echo ' $sid_lst13 :    '.$sid_lst;
        $sid_cnt=substr_count($sid_lst," ");
       // echo ' $sid_cnt:    '.$sid_cnt;

        $sample_line_id=0;
        $sample_line_id=$SampleID_line_key+2;
        $line_cnt=0;
        $trans=array();
        $ln_offset=1;
        for ($e = 0; $e<=$sid_cnt; $e++)  {  
            for ($sample_line_id; $sample_line_id<=35; $sample_line_id++)  {    
                //$sid_val_lst=$lines[$sample_line_id];
                //echo '$lines[$sample_line_id]:: '.$lines[$sample_line_id];
                $sid_val_lst=$lines[$sample_line_id];
               // echo 'sid_val_lst:      '.$sid_val_lst;
                $spc = strpos($sid_val_lst," ") + strpos(substr($sid_val_lst,4)," ")+1;
               // echo 'spc:      '.$spc;
                $comp_name = substr($sid_val_lst,0,$spc); 
                //echo 'compound_name:      '.$comp_name;
                $val_list = substr($sid_val_lst,$spc+1);
                $gp="";
                $cid="";
                $cp_sql="select name, code from compound where code='mPB' and name='".$comp_name."'";

                if (!$result = mysqli_query($dbc, $cp_sql)) {
                    exit(mysqli_error($dbc));
                }else{
                    if ($result -> num_rows){
                        $r_rev = mysqli_fetch_assoc($result);
                        $gp=$r_rev['code'];
                        $cid=$r_rev['name'];	        
                    }else{
                        $cid=$comp_name;
                    }
                }
                $pg_sample_hd_truc = $pg_sample_hd_lst;
                $final_val="";
                for ($f = 0; $f<=$e; $f++)  {    
                    //echo ' 4444444444444444444444444444444 ';
                    $spc = strpos($val_list," ");
                    $pg_sample_hd_truc_spc = strpos($pg_sample_hd_truc," ");
                    if ($f<$e){ 
                        $val_list = substr($val_list,$spc+1);
                        $pg_sample_hd_truc = substr($pg_sample_hd_truc,$pg_sample_hd_truc_spc+1);
                        $spc = strpos($val_list," ");
                        $pg_sample_hd_truc_spc = strpos($pg_sample_hd_truc," ");
                        $val_list = substr($val_list,$spc+1);
                        $pg_sample_hd_truc = substr($pg_sample_hd_truc,$pg_sample_hd_truc_spc+1);
                       // echo ' 5555555555555555555555555555555 ';
                    }

                    if ($f==$e){
                        If (substr($pg_sample_hd_truc,0,$pg_sample_hd_truc_spc) == "pg/sample"){
                            $final_val = substr($val_list,0,$spc);
                        //    echo ' 6666666666666666666666666666666 ';
                        }
                    }

                }
                

                $trans[$line_cnt][0] = substr($sid[$e],0,2);  
                $trans[$line_cnt][1] = "20".substr($sid[$e],6,2)."/".substr($sid[$e],8,2)."/".substr($sid[$e],10,2); 
                $trans[$line_cnt][2] = $sid[$e];  
                $trans[$line_cnt][3] = $gp;  
                $trans[$line_cnt][4] = $cid; 
                $trans[$line_cnt][5] = $final_val; 

                $line_cnt++;
                $ln_offset++;
               // echo ' 77777777777777777777777777777 ';
            }        
            $sample_line_id=$SampleID_line_key+2; 
        };
      //  echo ' 888888888811111111111111111111111 ';
        $hdr="";
        $hdr="SITE,STRTDATE,SAMPID,GROUP,COMPOUND CODE,CONC (ppbv)";
        $hdr = explode(',', $hdr);
        fputcsv($output, $hdr);
            foreach ($trans as $row) {
            //    echo ' 8888888888888888888888888888 ';
                fputcsv($output, (array)$row, ",");
            }

        exit();
        for ($e = 1; $e <= count($lines); $e++) {
          //  echo ' 00000000000000000000000000000000000000 ';
            $J .= substr($name,6)."-";
            
        }
        echo $J;
        echo "</pre>";
    }


}
            
    

?>

