﻿<?php // Script 8.8 - login.php
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

					if ($_SESSION['trans_type_curr']=="HSE"){
							$q = "select d.district_id did, d.name dname, h.house_id hid, h.name hname, ht.name htname 
									from user_district ud, district d, housing h, house_type ht 
								where ht.house_type_id = h.house_type_id and h.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";}
					if ($_SESSION['trans_type_curr']=="KBS"){
							$q = "select d.district_id did, d.name dname, k.kerbside_id kid, k.name kname
									from user_district ud, district d, kerbside k 
								where k.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";}
					if ($_SESSION['trans_type_curr']=="CGS"){
							$q = "select d.district_id did, d.name dname, s.station_id sid, s.name sname
									from user_district ud, district d, station s 
								where s.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";}

					//print $q;
					$result=$dbc->query($q);
					if (!$result->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				

						//$q = "select * from user_acc where userid = ? and pwd= ?";
						//$stmt = $db->prepare($q);
						//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
						//$stmt->execute();
						//mysqli_query($dbc, $q);			

				}
		
$created=FALSE;
$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['trans_date']))	
	{
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				
				$q = "insert into recycle_trans (trans_date,trans_type,source_id,glass_bottle_qty,ee_qty,comp_qty,battery_qty,light_qty,paper_qty,plastic_qty,metal_qty,toner_qty,clothes_qty,book_qty,toy_qty,other_desc,other_qty) 
				values ('".date('Y/m/d', strtotime($_POST['trans_date']))."','".$_SESSION['trans_type_curr']."','".$_POST['source_id']
						."',".check_empty($_POST['glass_bottle_qty']).",".check_empty($_POST['ee_qty']).",".check_empty($_POST['comp_qty'])
						.",".check_empty($_POST['battery_qty']).",".check_empty($_POST['light_qty']).",".check_empty($_POST['paper_qty'])
						.",".check_empty($_POST['plastic_qty']).",".check_empty($_POST['metal_qty']).",".check_empty($_POST['toner_qty'])
						.",".check_empty($_POST['clothes_qty']).",".check_empty($_POST['book_qty']).",".check_empty($_POST['toy_qty'])
						.",'".$_POST['other_desc']."',".check_empty($_POST['other_qty']).")";
					
					//date('Y/m/d', $_SESSION['trans_date'])      


					if ($dbc->query($q) === FALSE) {
					    echo "Error: " . $dbc->error;
					    exit();
						}else{					
					    		$created=TRUE;
					    	}

		}else{
				$criteria = "";
				$comp="false";				
		}


} 
	


print '
<form onsubmit="submitForm()" action="addnew.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">新增回收記錄</p>
      
      <table style="width:92%;margin-left:2%;">
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">回收日期</label>
				</td>				  
				
				<td>  
				  <input style="margin-left:10px" type="date" name="trans_date" id="trans_date"></input>
				</td>				  				
			</tr>
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="source_id">';
				  if ($_SESSION['trans_type_curr']=='HSE'){print '屋苑/機構';}
				  if ($_SESSION['trans_type_curr']=='KBS'){print '街站';}
				  if ($_SESSION['trans_type_curr']=='CGS'){print 'CGS';}
print '(編號)</label>
				</td>				  
				<td>				  				  
				  <select style="margin-left:10px" name="source_id">';
				 	while ($r=$result->fetch_object()){
				    if ($_SESSION['trans_type_curr']=='HSE'){print '<option value="'.$r->hid.'">'.'('.$r->htname.') '.$r->hname.'</option>';}
 				    if ($_SESSION['trans_type_curr']=='KBS'){print '<option value="'.$r->kid.'">'.$r->kname.'</option>';}
 				    if ($_SESSION['trans_type_curr']=='CGS'){print '<option value="'.$r->sid.'">'.$r->sname.'</option>';}
				  };


print '<td></tr><tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="glass_bottle_qty">玻璃樽(kg)</label>
				</td>
				<td>				
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="glass_bottle_qty" id="glass_bottle_qty"></input>
				</td>
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="ee_qty">電器(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="ee_qty" id="ee_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="comp_qty">電腦及相關用品(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="comp_qty" id="comp_qty"></input>
				</td>
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="battery_qty">充電池(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="battery_qty" id="battery_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="light_qty">光管及慳電膽(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="light_qty" id="light_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="paper_qty">廢紙(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="paper_qty" id="paper_qty"></input>
				</td>				 	
			</tr>

			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="plastic_qty">塑膠廢料(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="plastic_qty" id="plastic_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="metal_qty">金屬(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="metal_qty" id="metal_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="toner_qty">碳粉盒(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="toner_qty" id="toner_qty"></input>
				</td>				 	
			</tr>


			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="clothes_qty">衣物(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="clothes_qty" id="clothes_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="book_qty">書本(kg)</label>
				</td>
				<td>					
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="book_qty" id="book_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">				
					<label for="toy_qty">玩具(kg)</label>
				</td>
				<td>					
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="toy_qty" id="toy_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="other_qty">其他(kg)</label>
				</td>
				<td>					
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="other_qty" id="other_qty"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="other_desc">註明</label>
				</td>
				<td>				
				 	<input style="margin-left:10px" type="text" name="other_desc" id="other_desc"></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="新增">
				</td>  
			</tr></table>';


  // Free result set
  	mysqli_free_result($result);





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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>';
  
	if ($_SESSION['trans_type_curr']=="HSE"){
	 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="housing.php">Back</a></span>';};
	 
	if ($_SESSION['trans_type_curr']=="KBS"){
	 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="kerbside.php">Back</a></span>';};
	if ($_SESSION['trans_type_curr']=="CGS"){
	 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="station.php">Back</a></span>';};





	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';
			}else{
						if ($created===TRUE){					
				echo '<p align="center" style="color:blue;font-weight:bold;font-size:28px">新增回收記錄成功</p>';}
					}



	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}






function check_empty($x){
	//print '$x='.$x;
	if ($x==''){
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

