<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Housing');
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


}
</script>';



print '<script>
function EnableTextBox_tmp() {
alert("Hello");
alert(document.getElementById("act_type").value); 
if (document.getElementById("act_type").value == "E") {
		document.getElementById("act_name").disabled = true;
		document.getElementById("org_name").disabled = true;
}
else {
		document.getElementById("act_name").disabled = false;
		document.getElementById("org_name").disabled = false;
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
	document.getElementById("act_no").readOnly = false;	
	document.getElementById("act_name").readOnly = false;
	document.getElementById("org_name").readOnly = false ;
	document.getElementById("speaker").readOnly = false;
	document.getElementById("loc").readOnly = false;
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



// Print some introductory text:

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
		

$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['trans_date']) and !empty($_POST['act_name']) and !empty($_POST['loc']) and !empty($_POST['speaker']) and !empty($_POST['org_name']) and !empty($_POST['act_no'])) 	
	
	{
		 		//print 'Duration = '.$_POST['to_t']-$_POST['frm_t'];
		 		
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
				
				$q = "insert into edu_event (trans_date,district_id,act_no,frm_t,to_t,act_type,act_name,target_aud_type,org_name,total_party,loc,speaker,rmk,age_cate) 
				values ('".date('Y/m/d', strtotime($_POST['trans_date']))."','".$_POST['district_id']
						."','".$_POST['act_no']."','".$_POST['frm_t']."','".$_POST['to_t']
						."','".$h[0]."','".$_POST['act_name']."','".$_POST['target_aud_type']
						."','".$_POST['org_name']."',".$_POST['total_party'].",'".$_POST['loc']
						."','".$_POST['speaker']."','".$_POST['rmk']."','".$_POST['age_cate']."');";


					if ($dbc->query($q) === TRUE) {
					    echo "New record created successfully";
					} else {
					    echo "Error: " . $q . "<br>" . $dbc->error;
					};

		}else{
				$criteria = "";
				$comp="false";				
		}


} 
	


print '
<form onsubmit="submitForm()" action="addevent.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">新增教育活動/到訪記錄</p>
      
      <table style="width:92%;margin-left:2%;">
	
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="act_no">活動編號</label>
				</td>
				<td>				
				 	<input style="margin-left:10px" type="text" name="act_no" id="act_no"></input>
				</td>
			</tr>
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="district_id">區域</label>
				</td>				  
				<td>				  				  
				  <select style="margin-left:10px" name="district_id">';
				 	while ($r_ud=$result_ud->fetch_object()){
				    print '<option value="'.$r_ud->did.'">'.'('.$r_ud->did.') '.$r_ud->dname.'</option>';
				  }			
			
print	'</td><tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">活動/到訪日期</label>
				</td>				  
				
				<td>  
				  <input style="margin-left:10px" type="date" name="trans_date" id="trans_date"></input>
				</td>				  				
			</tr>';




print '<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="frm_t">開始時間</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="time" name="frm_t" id="frm_t"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="to_t">完結時間</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="time" name="to_t" id="to_t"></input>
				</td>
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="act_type">活動性質</label>
				</td>
				<td>
				  <select style="margin-left:10px" name="act_type" id="act_type">';
				 	while ($r_act=$result_act->fetch_object()){
				    print '<option value="'.$r_act->act_type.'|'.$r_act->act_name.'|'.$r_act->hr_req.'|'.$r_act->qty_req.'">'.'('.$r_act->act_type.') '.$r_act->act_name.'</option>';
				  }			
	  
				  
print '</select></td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="act_name">活動名稱</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="text" name="act_name" id="act_name"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="target_aud">對象類別</label>
				</td>
				<td>
				  <select style="margin-left:10px" name="target_aud_type">';
				 	while ($r_aud=$result_aud->fetch_object()){
				    print '<option value="'.$r_aud->target_aud_type.'">'.'('.$r_aud->target_aud_type.') '.$r_aud->target_aud_name.'</option>';
				  }					 	
print	'</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="target_aud">年齡層</label>
				</td>
				<td>
				  <select style="margin-left:10px" name="age_cate">';
				 	while ($r_ag=$result_ag->fetch_object()){
				    print '<option value="'.$r_ag->age_cate.'">'.'('.$r_ag->age_cate.') '.$r_ag->descp.'</option>';
				  }			
print	'</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="org_name">機構名稱</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="text" name="org_name" id="org_name"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="total_party">參加人數</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="1"  min="1" max="2000" name="total_party" id="total_party"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="loc">地點</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="text" name="loc" id="loc"></input>
				</td>				 	
			</tr>


			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="speaker">導師/講者</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="text" name="speaker" id="speaker"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="rmk">註明</label>
				</td>
				<td>				
				 	<input style="margin-left:10px" type="text" name="rmk" id="rmk"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="新增">
				</td>  
			</tr></table>';


  	mysqli_free_result($result_ud);
  	mysqli_free_result($result_ag);
  	mysqli_free_result($result_act);
  	mysqli_free_result($result_aud);
  	mysqli_free_result($result_dst);


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
			print '<p align="center" class="text--error">請確保選項均已輸入!</p>';}

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

