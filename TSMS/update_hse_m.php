<?php 
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Station');
include('templates/header.html');
include('templates/iconn.php');


print '<script type="text/javascript">
    document.getElementById("cancel").onclick = function () {
        location.href = "home.php";
    }';


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

print '<script>
function showprg(str) {

//	alert("str="+str);
    if (str == "") {
        document.getElementById("new_prg").innerHTML = "";
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
                document.getElementById("new_prg").innerHTML = this.responseText;
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


// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];

//$updated=FALSE;
$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['did']) and !empty($_POST['hname']) and !empty($_POST['hse_type_id']) and !empty($_POST['h_addr']))	
		{
				$u= "update housing set "
				."district_id='".$_POST['did']."',name='".$_POST['hname']."',house_type_id='".$_POST['hse_type_id']."',address='".$_POST['h_addr']."'"
				." where house_id='".$_POST['hid']."';";
				
				//print $u;
				//$dbc->autocommit(FALSE);

					if ($dbc->query($u) === TRUE) {
					   // $updated=TRUE;
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">記錄已成功更新</p>
					    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="house_m.php">Back</a></span>';

							}else{
								    echo "Error: " . $dbc->error;
								    exit();
												};

		}else{
				print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		
				$criteria = "";
				$comp="false";				

				}
		exit();
		
} 



if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
					if (empty($_REQUEST['cluster'])) {
							print '<p class="text--error">There is no information for updating<br>Go back and try again.</p>';		

					}else{

							 $token = strtok($_REQUEST['cluster'], "|");
							 
								$i=0; 
								while ($token !== false)
							   {
							   	 $t[$i]=$token;
							   //echo "$token<br>";
								   $token = strtok("|");
							  	 $i++;
							   }
							if ($t[5]=="@"){$t[5]="";} 

					}		
					
					$q = "select d.district_id did, d.name dname
							from user_district ud, district d
						where ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";

					//print $q;
					$result_ud=$dbc->query($q);
					if (!$result_ud->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				

					$q = "select *
							from house_type;";
					//print $q;
					$result_hse_t=$dbc->query($q);
					if (!$result_hse_t->num_rows){
							print '<p class="text--error">'.'House Type Configuration Error!</p>';
							exit();
					}				



				}
				
	
	
/*
						    $param1=$r_ua->u_uid.'|'.$r_ua->u_fname.'|'.$r_ua->u_lname.'|'.$r_ua->u_pwd.'|';
 								if (!empty($r_ua->u_email)){
										$param1=$param1.$r_ua->u_email;} 
										else{ 
											$param1=$param1.'@';} 
*/							
print '
<form action="update_hse_m.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">更新屋苑/機構資料</p>
      
      <table style="width:92%;margin-left:2%;">
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="did">區域</label>
				</td>		
				<td>
				  <select style="margin-left:10px" name="did">';
				 	while ($r_ud=$result_ud->fetch_object()){
						if ($r_ud->did==$t[0]){
							print '<option value="'.$r_ud->did.'" selected>'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';}
						else{
				   		print '<option value="'.$r_ud->did.'">'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';}
				  };			

print '</td></tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">	
					<label for="hid">屋苑/機構(編號)</label>	  				
				<td>  
					<input style="margin-left:10px" type="text" name="hid" id="hid" readonly value="'.$t[1].'"></input>
</td></tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="hname">屋苑/機構(名稱)</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="text" name="hname" id="hname" required value="'.$t[2].'"></input>
				</td>				  				
			</tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="hse_type_id">屋苑類別</label>
				</td>
				<td>
				  <select style="margin-left:10px" name="hse_type_id">';
				 	while ($r_hse_t=$result_hse_t->fetch_object()){
						if ($r_hse_t->house_type_id==$t[3]){
							print '<option value="'.$r_hse_t->house_type_id.'" selected>'.'('.$r_hse_t->house_type_id.') '.$r_hse_t->name.'</option>';}
						else{
				   		print '<option value="'.$r_hse_t->house_type_id.'">'.'('.$r_hse_t->house_type_id.') '.$r_hse_t->name.'</option>';}
				  };			
			
			
print	'<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="h_addr">地址</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="text" name="h_addr" id="h_addr" required value="'.$t[5].'"></input>
		</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="更新">
				</td>  
			</tr></table>';
  	mysqli_free_result($result_ud);
		mysqli_free_result($result_hse_t);





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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="house_m.php">Back</a></span>
';



	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}


function check_empty($x){
	//print '$x='.$x;
	if ($x==''){
		print "return 0";
		return 0;		
		}else{
			return $x;
		}
		//	print "nothing";
}	




include('templates/footer.html'); 






?>

<script language="javascript">
function ShowMeDate() {
　var Today=new Date();
　alert("今天日期是 " + Today.getFullYear()+ " 年 " + (Today.getMonth()+1) + " 月 " + Today.getDate() + " 日");
}


<script>

