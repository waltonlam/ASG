<?php 

ini_set('max_execution_time', 0);
set_time_limit(1800);
ini_set('memory_limit', '-1');
ini_set('display_errors',1);

session_start();
include('header2.php');
include('iconn.php');
include('fn.php');

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
        for ($e = 0; $e<=$sid_cnt; $e++)  {
            for ($sample_line_id; $sample_line_id<=22; $sample_line_id++)  {
                $sid_val_lst=$lines[$sample_line_id];
                $spc = strpos($sid_val_lst," ");
                $comp_name = substr($sid_val_lst,0,$spc);
                $val_list = substr($sid_val_lst,$spc+1); 
                for ($f = 0; $f<=$e; $f++)  {   
                    $spc = strpos($val_list," ");                   
                    if ($f<$e){ 
                        $val_list = substr($val_list,$spc+1);
                    }                    
                    if ($f==$e){ 
                        $final_val = substr($val_list,0,$spc);
                    }
                }

                $sid[$e].=";".$comp_name.";".$final_val;  
            }        
            $sample_line_id=$SampleID_line_key+2;
        };
        $output = fopen('php://output', 'w');
        $hdr="";
        $hdr="Site,Start Date,Sample ID,Group,Compound,Conc.(ppbv)/SAMPLE,Flow(LPM),Time(min),Volume(m3),Conc/M3,WHO TEF Ratio,WHO TEQ Conc/m3,Casno1";
        $hdr = explode(',', $hdr);
        fputcsv($output, $hdr);
        exit();
  
        for ($e = 1; $e <= count($lines); $e++) {
            $J .= substr($name,6)."-";
            
        }
 
    }


}
		

?>	


<!DOCTYPE html>
<html>

<head>
<script src="jquery-3.2.1.min.js"></script>

<style>

.outer-scontainer {
    background: #F0F0F0;
    border: #e0dfdf 1px solid;
    padding: 20px;
    border-radius: 2px;
}

.input-row {
    margin-top: 0px;
    margin-bottom: 20px;
}



.outer-scontainer table {
    border-collapse: collapse;
    width: 100%;
}

.outer-scontainer th {
    border: 1px solid #dddddd;
    padding: 8px;
    text-align: left;
}

.outer-scontainer td {
    border: 1px solid #dddddd;
    padding: 8px;
    text-align: left;
}

#response {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    display: none;
}




.success {
    background: #c7efd9;
    border: #bbe2cd 1px solid;
}

.error {
    background: #fbcfcf;
    border: #f3c6c7 1px solid;
}

div#response.display-block {
    display: block;
}

#submit {
  background-color: #87ceeb;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  width:100%;
}

</style>
<script type="text/javascript">
$(document).ready(function() {
    $("#frmJPGImport").on("submit", function () {

	    $("#response").attr("class", "");
        $("#response").html("");
        var fileType = ".jpg";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
        	    $("#response").addClass("error");
        	    $("#response").addClass("display-block");
            $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
        }
        return true;
    });
});
</script>
</head>

<body>
        
    <h2>Convert to List</h2>

    <div id="response"
        class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <?php if(!empty($message)) { echo $message; } ?>
        </div>
    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="pb_conv.php" method="post"
                name="frmJPGImport" id="frmJPGImport"
                enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose <b>mPB Group</b> JPEG File (.jpg)</label>
                    <br />

                </div>
				
				
				 <div class="input-row">
                   <input type="file" name="file"
                        id="file" accept=".jpg">
                   
                    <br />

                </div>

                    <input type="submit" value="Convert" name="conv">      

				<div>
				
				
				</div>
				

            </form>

        </div>


    </div>
</body>

</html>

