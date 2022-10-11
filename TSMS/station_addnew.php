<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'NewStation');
include('templates/header.html');
include('templates/iconn.php');



print '<script>function check_num() {
    alert(document.getElementById("other_qty").innerHTML);
		    document.getElementById("other_qty").focus();
		return false;

		if (document.getElementById("other_qty").innerHTML<>""){
			if (!is_numeric(document.getElementById("other_qty").innerHTML)){
		    alert("請輸入數字");
		    document.getElementById("other_qty").focus();
		  }
		}
}
</script>';


print '<script>
function submitForm(){

        return false;


    if(validationfails){
        return false;
    }
    else  {
        document.form.submit();
        return true;
    }
}
</script>';


print '<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
function fetch_select(val)
{

  document.getElementById("new_select").innerHTML="XXXX"; 
	alert("hello");
 $.ajax({
 method: "post",
 url: "fetch_data.php",
 data: {
  get_option:val
 },
 success: function (response) {
  document.getElementById("new_select").innerHTML=response; 
 }
 });
}

</script>';



print '<script>
function showprg(str) {

//	alert("str="+str);
    if (str == "") {
        document.getElementById("new_outlet_prg").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
//        	alert("XMLHttpRequest");
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
//                    alert("this.readyState == 4, this.responseText="+this.responseText);
                document.getElementById("new_outlet_prg").innerHTML = this.responseText;
            }else{
//            	alert("this.readyState <> 4.....,this.readyState="+this.readyState+"..."+"status="+this.status);
//            	alert("this.responseText="+this.responseText);
            	}
        };
//        alert("xmlhttp.open");
        xmlhttp.open("GET","fetch.php?q="+str,true);
        xmlhttp.send();
        
    }
}
</script>';


//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];

if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
		  		$q = "select d.district_id did, d.name dname 
								from user_district ud, district d
								where ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";

					//print $q;
					$result_ud=$dbc->query($q);
					if (!$result_ud->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				
					/*
					$r="select * from house_type;";
					$result_hse=$dbc->query($r);
					if (!$result_hse->num_rows){
							print '<p class="text--error">'.'House Type Configuration Error!</p>';
							exit();
					}				
					*/

}


$created=FALSE;
$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['did']) and !empty($_POST['sid']) and !empty($_POST['sname']) and !empty($_POST['s_addr']))	
		//if (isset($_POST['userid']) and isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['pwd']))	
	{
/*		
		print '<p>userid='.$_POST['userid'].'</p>';
				print '<p>fname'.$_POST['fname'].'</p>';
						print '<p>lname'.$_POST['lname'].'</p>';
*/
		//and isset($_POST['did']) 
			$q = "insert into station (district_id,station_id,name,address) 
						values ('".$_POST['did']."','".$_POST['sid']."','".$_POST['sname']."','".$_POST['s_addr']."');";

				//date('Y/m/d', $_SESSION['trans_date'])    					
				//$mysqli->autocommit(FALSE);				
				//$dbc->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);  
					if ($dbc->query($q) === FALSE) {
					    echo "Error: " . $dbc->error;
					    exit();
						}else{					
					    		$created=TRUE;
					    	}
																															
				} else {
						//print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
						$criteria = "";
						$comp="false";								
	
				    //echo "Error-> " . $q . "<br>" . $dbc->error;
//				    exit();
					}
}					
				//$dbc->commit();
//					$dbc->autocommit(TRUE);

 
	
//trans_date,userid,first_name,last_name,pwd,email,role) 

print '
<form onsubmit="submitForm()" action="station_addnew.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">新增CGS</p>
      
      <table style="width:92%;margin-left:2%;">		     	
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="did">區域</label>
				</td>				  
				<td>					  
				  <select style="margin-left:10px" name="did">';
				 	while ($r_ud=$result_ud->fetch_object()){
				    print '<option value="'.$r_ud->did.'">'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';
				  };			
print '</td></tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="sid">CGS(編號)</label>
				</td>
				<td>
				  <input style="margin-left:10px" type="text" name="sid" id="sid"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="sname">CGS(名稱)</label>
				</td>
				<td>
				  <input style="margin-left:10px" type="text" name="sname" id="sname"></input>
				</td>				 	
			</tr>													
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="s_addr">地址</label>
				</td>   
				<td>  
					<textarea rows="4" cols="50" style="margin-left:10px" name="s_addr" id="s_addr"></textarea> 
				</td>				 	
			</tr>		
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="新增">
				</td>  
			</tr></table></form>';


  	mysqli_free_result($result_ud);
  	//mysqli_free_result($result_hse);





/*
print '<table style="background-color:white;border-radius:8px;border:none;width:98%;margin-left:1%;">
<tr>
<td  style="border:none;text-align:center;">
    <br>
    <h3 style="display:block;margin-left:1%;padding-right:4px;color:#05CDB9;text-align:left;">&nbsp;刪除紀錄:</h3>
    <hr style="border:0.2px solid grey;">
    <form  method="post" action="">
        刪除
        <select name="category">
            <option value="">-----</option>
            <option value="SEQ_ID">紀錄序號</option>
            <option value="DATE">日期</option>
        </select>
        等於
        <input style="width:15%;"type="text" name="crit">的所有有關紀錄。
        <button type="submit" class="btn btn-default">確定刪除</button>
    </form>
    <br>
</td>
</tr>
</table>';
*/
print'
  <span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="station_m.php">Back</a></span>
';


	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保所有資料均已輸入!</p>';
			}else{
						if ($created===TRUE){					
				echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">新增CGS成功</p>';}
					}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}






function check_empty($x){
	//print '$x='.$x;
	if ($x==''){
		//print "return 0";
		return 0;		
		}else{
			return $x;
		}
		//	print "nothing";
}	




include('templates/footer.html'); // Need the footer.






?>

<script language="javascript">
function ShowMeDate() {
　var Today=new Date();
　alert("今天日期是 " + Today.getFullYear()+ " 年 " + (Today.getMonth()+1) + " 月 " + Today.getDate() + " 日");
}


<script>

