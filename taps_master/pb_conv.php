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
        //echo ' $sid_cnt:    '.$sid_cnt;

        $sample_line_id=0;
        $sample_line_id=$SampleID_line_key+2;
        $line_cnt=0;
        $trans=array();
        $ln_offset=1;
        //sid_cnt == column count == 2 [0,1,2]
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
                $val_list = str_replace('< ', '<', $val_list);

                //echo '$val_list:      '.$val_list;
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
                //echo ' $pg_sample_hd_truc'.$pg_sample_hd_truc;
                $final_val="";
                for ($f = 0; $f<=$e; $f++)  {    
                  //  echo ' $f: '.$f.' $e: '.$e;
                    //spc == space position 
                    $spc = strpos($val_list," ");
                    //echo ' $spc: '.$spc;

                    $pg_sample_hd_truc_spc = strpos($pg_sample_hd_truc," ");
                  //  echo ' $pg_sample_hd_truc_spc: '.$pg_sample_hd_truc_spc;
                    if ($f<$e){ 
                        $val_list = substr($val_list,$spc+1);
                        //echo '[line 100] $val_list: '.$val_list;
                        $pg_sample_hd_truc = substr($pg_sample_hd_truc,$pg_sample_hd_truc_spc+1);
                        //echo '[line 102] $pg_sample_hd_truc: '.$pg_sample_hd_truc;
                        $spc = strpos($val_list," ");
                        //echo ' [line 104] $spc: '.$spc;
                        $pg_sample_hd_truc_spc = strpos($pg_sample_hd_truc," ");
                        //echo ' [line 106] $pg_sample_hd_truc_spc: '.$pg_sample_hd_truc_spc;
                        $val_list = substr($val_list,$spc+1);
                       // echo ' [line 108] $val_list: '.$val_list;
                        $pg_sample_hd_truc = substr($pg_sample_hd_truc,$pg_sample_hd_truc_spc+1);
                       // echo ' [line 110] $pg_sample_hd_truc: '.$pg_sample_hd_truc;
                    }

                    if ($f==$e){
                        If (substr($pg_sample_hd_truc,0,$pg_sample_hd_truc_spc) == "pg/sample"){
                            $final_val = substr($val_list,0,$spc);
                            //echo ' [line 116] $val_list: '.$val_list;
                           // echo ' [line 117] $spc: '.$spc;
                           // echo ' [line 118] $final_val: '.$final_val;
                        }
                    }

                }
                //$str = "Apple is healthy.";
                /*$delimiter = ' ';
                $vals = explode($delimiter, $val_list);
                foreach ($vals as $val) {
                    echo $val;
                    echo "/n";
                }*/
                /*echo $vals[0];
                echo $vals[1];
                echo $vals[2];
                echo $vals[3];
                echo $vals[4];
                echo $vals[5];*/

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
            //echo ' $row: '.implode($row);
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

