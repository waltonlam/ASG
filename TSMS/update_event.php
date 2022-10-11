<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'EDUEvent');
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
function EnableTextBox() {
if (document.getElementById("act_type").value == "E") {
	document.getElementById("act_no").value="NA";
	document.getElementById("act_name").value="NA";
	document.getElementById("act_name").value="NA";
	document.getElementById("org_name").value="NA";
	document.getElementById("speaker").value="NA";
	document.getElementById("loc").value="CGS";
	document.getElementById("act_no").readOnly = true;
	document.getElementById("act_name").readOnly= true;
	document.getElementById("org_name").readOnly = true;
	document.getElementById("speaker").readOnly = true;
	document.getElementById("loc").readOnly = true;

	}else{
	document.getElementById("act_no").value="";
	document.getElementById("act_name").value="";
	document.getElementById("org_name").value="";
	document.getElementById("speaker").value="";
	document.getElementById("loc").value="";
	document.getElementById("act_no").disabled = false;	
	document.getElementById("act_name").disabled = false;
	document.getElementById("org_name").disabled = false ;
	document.getElementById("speaker").disabled = false;
	document.getElementById("loc").disabled = false;
		}
}
</script>';

// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];


$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['trans_date']) and !empty($_POST['act_name']) and !empty($_POST['loc']) and !empty($_POST['speaker']) and !empty($_POST['org_name']) and !empty($_POST['act_no'])) 		
	{
				
		 		$token = strtok($_POST['act_type'], "|");							 
				$i=0; 
				while ($token !== false)
			   {
			   	 $h[$i]=$token;
				   $token = strtok("|");
			  	 $i++;
			   }
				$timestamp1 = strtotime($_POST['frm_t']);
				$timestamp2 = strtotime($_POST['to_t']);
				$hour = round(abs($timestamp2 - $timestamp1)/(60*60), 2);
				if ($hour<$h[2]){
					echo '<p align="center" class="text--error">活動性質: "'.$h[1].'" 所需要時數最少為 '.$h[2].' 小時. 請更正</p>'; 
					exit();
				}
				
				if ($_POST['total_party']<$h[3]){
					echo '<p align="center" class="text--error">活動性質: "'.$h[1].'" 所需要參加人數最少為 '.$h[3].' 人. 請更正</p>'; 
					exit();
				}				
			
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				
				$u= "update edu_event set "
				."trans_date='".$_POST['trans_date']."',district_id='".$_POST['district_id']."',act_no='".$_POST['act_no']."',frm_t='".$_POST['frm_t']."',to_t='".$_POST['to_t']."'"
				.",act_type='".$h[0]."',act_name='".$_POST['act_name']."',target_aud_type='".$_POST['target_aud_type']."',org_name='".$_POST['org_name']."',total_party='".$_POST['total_party']."'"
				.",loc='".$_POST['loc']."',speaker='".$_POST['speaker']."',rmk='".$_POST['rmk']."',age_cate='".$_POST['age_cate']."'"
				." where trans_no=".$_POST['trans_no'];
				
				/*
				."trans_date='".$_POST['trans_date']."',source_id='".$_POST['source_id']."',glass_bottle_qty=".check_empty($_POST['glass_bottle_qty']).",ee_qty=".check_empty($_POST['ee_qty']).",comp_qty=".check_empty($_POST['comp_qty'])
				.",battery_qty=".check_empty($_POST['battery_qty']).",light_qty=".check_empty($_POST['light_qty']).",paper_qty=".check_empty($_POST['paper_qty']).",plastic_qty=".check_empty($_POST['plastic_qty'])
				.",metal_qty=".check_empty($_POST['metal_qty']).",toner_qty=".check_empty($_POST['toner_qty']).",clothes_qty=".check_empty($_POST['clothes_qty']).",book_qty=".check_empty($_POST['book_qty']).",toy_qty=".check_empty($_POST['toy_qty'])
				.",other_desc='".$_POST['other_desc']."',other_qty=".check_empty($_POST['other_qty'])." where trans_no=".$_POST['trans_no'];	
				*/
				
					//date('Y/m/d', $_SESSION['trans_date'])      
					//print $u;

					if ($dbc->query($u) === TRUE) {
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">記錄已成功更新</p>
					    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="edu_event.php">Back</a></span>';
					} else {
					    echo "Error: " . $u . "<br>" . $dbc->error;
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
								//trans_no.'|'.trans_date.'|'.$r_hse->comp_qty.'|'.$r_hse->source_id.'|'.$r_hse->name.'|'.$r_hse->glass_bottle_qty.'|'.$r_hse->ee_qty.'|'
								//.$r_hse->comp_qty.'|'.$r_hse->battery_qty.'|'.$r_hse->light_qty.'|'.$r_hse->paper_qty.'|'.$r_hse->plastic_qty.'|'
								//.$r_hse->metal_qty.'|'.$r_hse->toner_qty.'|'.$r_hse->clothes_qty.'|'.$r_hse->book_qty.'|'.$r_hse->toy_qty.'|'.$r_hse->other_qty.'|'.$r_hse->other_desc.
								
								//print $_REQUEST['cluster'].'<p></p>';
							 $token = strtok($_REQUEST['cluster'], "|");
								$i=0; 
								while ($token !== false)
							   {
							   	 $t[$i]=$token;
							   //echo "$token<br>";
								   $token = strtok("|");
							  	 $i++;
							   }
							if ($t[12]=="@"){$t[12]="";} 
							   
							   //exit();
							   							
							 //  print "-".$t[17]."-";
							   														   							
						//	$u=0;
							//print '<p></p>';
						//	while ($u < sizeof($t)){
						//		print $t[$u].'<br>';
						//		$u++;
					//		}
	
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

					$act="select * from activity;";
					$result_act=$dbc->query($act);
					
					$aud="select * from target_aud;";
					$result_aud=$dbc->query($aud);

					$dst="select * from district;";
					$result_dst=$dbc->query($dst);					

					$ag="select * from age_grp;";
					$result_ag=$dbc->query($ag);					

						//$q = "select * from user_acc where userid = ? and pwd= ?";
						//$stmt = $db->prepare($q);
						//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
						//$stmt->execute();
						//mysqli_query($dbc, $q);			

				}
				
				
	

//3|RE066|2017-07-04|10:15:00|11:45:00|A|咖啡渣艾草蚊香工作坊||光明學校|31|1.5|光明學校|曾海燕姑娘|46.5|
print '
<form action="update_event.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">更新教育活動記錄</p>
      
      <table style="width:92%;margin-left:2%;">
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="district_id">區域</label>
				</td>				  				
				<td>  
				  <select style="margin-left:10px" name="district_id" value="'.$t[13].'">';
				 	while ($r_ud=$result_ud->fetch_object()){
				 		if ($r_ud->did==$t[13]){
				 			print '<option value="'.$r_ud->did.'" selected>'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';
				 		}else{
				 			print '<option value="'.$r_ud->did.'">'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';
				 		}
				  }	
print	'</td></tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="act_no">活動編號</label>
				</td>
				<td>				
				  <input style="margin-left:10px" type="hidden" name="trans_no" id="trans_no" value='.$t[0].'></input>
				  <input style="margin-left:10px" type="text" name="act_no" id="act_no" value='.$t[1].'></input>
				</td>
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">活動日期</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="date" name="trans_date" id="trans_date" value='.$t[2].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">開始時間</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="time" name="frm_t" id="frm_t" value='.$t[3].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">完結時間</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="time" name="to_t" id="to_t" value='.$t[4].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="act_type">活動性質</label>
				</td>				  				
				<td>  	
				  <select style="margin-left:10px" name="act_type" id="act_type" value="'.$t[5].'">';
				 	while ($r_act=$result_act->fetch_object()){
				 		if ($r_act->act_type==$t[5]){
				    	print '<option value="'.$r_act->act_type.'|'.$r_act->act_name.'|'.$r_act->hr_req.'|'.$r_act->qty_req.'" selected>'.'('.$r_act->act_type.') '.$r_act->act_name.'</option>';
				    }else{				    	
				    	print '<option value="'.$r_act->act_type.'|'.$r_act->act_name.'|'.$r_act->hr_req.'|'.$r_act->qty_req.'">'.'('.$r_act->act_type.') '.$r_act->act_name.'</option>';
				    }
				  }	
print	'</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="act_name">活動名稱</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="text" name="act_name" id="act_name" value='.$t[6].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="target_aud_type">對象類別</label>
				</td>				  				
				<td>  
				  <select style="margin-left:10px" name="target_aud_type" value="'.$t[7].'">';
				 	while ($r_aud=$result_aud->fetch_object()){
				 		if ($r_aud->target_aud_type==$t[7]){
				    	print '<option value="'.$r_aud->target_aud_type.'" selected>'.'('.$r_aud->target_aud_type.') '.$r_aud->target_aud_name.'</option>';
				    }else{
				    	print '<option value="'.$r_aud->target_aud_type.'">'.'('.$r_aud->target_aud_type.') '.$r_aud->target_aud_name.'</option>';
				    }				    
				  }	
			print	'</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="age_grp">年齡層</label>
				</td>				  				
				<td>  
				  <select style="margin-left:10px" name="age_cate" value="'.$t[14].'">';
				 	while ($r_ag=$result_ag->fetch_object()){
				 		if ($r_ag->age_cate==$t[14]){
				    	print '<option value="'.$r_ag->age_cate.'" selected>'.'('.$r_ag->age_cate.') '.$r_ag->descp.'</option>';
				    }else{
				    	print '<option value="'.$r_ag->age_cate.'">'.'('.$r_ag->age_cate.') '.$r_ag->descp.'</option>';
				    }				    
				  }	
	  				  
print	'</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="org_name">機構名稱</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="text" name="org_name" id="org_name" value="'.$t[8].'"></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="total_party">參加人數</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px" type="number" step="1"  min="1" max="2000" name="total_party" id="total_party" value='.$t[9].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="total_party">地點</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px" type="text" name="loc" id="loc" value='.$t[10].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="speaker">導師/講者</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px" type="text" name="speaker" id="speaker" value="'.$t[11].'"></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="rmk">註明</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px" type="text" name="rmk" id="rmk" value='.$t[12].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="更新">
				</td>  
			</tr></table>';


  // Free result set
  	mysqli_free_result($result_act);
  	mysqli_free_result($result_aud);
  	mysqli_free_result($result_ud);
  //	mysqli_free_result($result_dst);





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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="edu_event.php">Back</a></span>
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




include('templates/footer.html'); // Need the footer.






?>

<script language="javascript">
function ShowMeDate() {
　var Today=new Date();
　alert("今天日期是 " + Today.getFullYear()+ " 年 " + (Today.getMonth()+1) + " 月 " + Today.getDate() + " 日");
}


<script>

