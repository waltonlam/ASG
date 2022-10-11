<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Housing');
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



// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];


$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['trans_date']))	
	{
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				
				$u= "update recycle_trans set "
				."trans_date='".$_POST['trans_date']."',source_id='".$_POST['source_id']."',glass_bottle_qty=".check_empty($_POST['glass_bottle_qty']).",ee_qty=".check_empty($_POST['ee_qty']).",comp_qty=".check_empty($_POST['comp_qty'])
				.",battery_qty=".check_empty($_POST['battery_qty']).",light_qty=".check_empty($_POST['light_qty']).",paper_qty=".check_empty($_POST['paper_qty']).",plastic_qty=".check_empty($_POST['plastic_qty'])
				.",metal_qty=".check_empty($_POST['metal_qty']).",toner_qty=".check_empty($_POST['toner_qty']).",clothes_qty=".check_empty($_POST['clothes_qty']).",book_qty=".check_empty($_POST['book_qty']).",toy_qty=".check_empty($_POST['toy_qty'])
				.",other_desc='".$_POST['other_desc']."',other_qty=".check_empty($_POST['other_qty'])." where trans_no=".$_POST['trans_no'];	
				
					if ($dbc->query($u) === TRUE) {
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">記錄已成功更新</p>
					    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>';
					    	if ($_SESSION['trans_type_curr']=="HSE"){
								 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="housing.php">Back</a></span>';};								 
								if ($_SESSION['trans_type_curr']=="KBS"){
								 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="kerbside.php">Back</a></span>';};
								if ($_SESSION['trans_type_curr']=="CGS"){
								 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="station.php">Back</a></span>';};

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
							exit();
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
							   							
							 //  print "-".$t[17]."-";
							   														   							
						//	$u=0;
							//print '<p></p>';
						//	while ($u < sizeof($t)){
						//		print $t[$u].'<br>';
						//		$u++;
					//		}
	
					}
		

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
				
				
	


print '
<form action="update.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">更新回收記錄</p>
      
      <table style="width:92%;margin-left:2%;">
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">回收日期</label>
				</td>				  
				
				<td>  
				  <input style="margin-left:10px" type="hidden" name="trans_no" id="trans_no" value='.$t[0].'></input>
				  <input style="margin-left:10px" type="date" name="trans_date" id="trans_date" value='.$t[1].'></input>
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
				    if ($_SESSION['trans_type_curr']=='HSE'){
				    	if ($r->hid==$t[2]){
				    		print '<option value="'.$r->hid.'" selected>'.'('.$r->htname.') '.$r->hname.'</option>';				    		
				    	}else{
				    		print '<option value="'.$r->hid.'">'.'('.$r->htname.') '.$r->hname.'</option>';
				    	}
				    }	
 				    if ($_SESSION['trans_type_curr']=='KBS'){
 				    	if ($r->kid==$t[2]){
				    		print '<option value="'.$r->kid.'" selected>'.'('.$r->kname.') '.$r->kname.'</option>';
				    	}else{
				    		print '<option value="'.$r->kid.'">'.'('.$r->kname.') '.$r->kname.'</option>';				    		
				    	}
				    }	
 				    if ($_SESSION['trans_type_curr']=='CGS'){
 				    	if ($r->sid==$t[2]){
				    		print '<option value="'.$r->sid.'" selected>'.'('.$r->sid.') '.$r->sname.'</option>';
				    	}else{
				    		print '<option value="'.$r->sid.'">'.'('.$r->sid.') '.$r->sname.'</option>';				    		
				    	}
				    }	 				    
				  };



print '<td></tr><tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="glass_bottle_qty">玻璃樽(kg)</label>
				</td>
				<td>				
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="glass_bottle_qty" id="glass_bottle_qty" value='.$t[4].'></input>
				</td>
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="ee_qty">電器(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="ee_qty" id="ee_qty" value='.$t[5].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="comp_qty">電腦及相關用品(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="comp_qty" id="comp_qty" value='.$t[6].'></input>
				</td>
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="battery_qty">充電池(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="battery_qty" id="battery_qty" value='.$t[7].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="light_qty">光管及慳電膽(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="light_qty" id="light_qty" value='.$t[8].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="paper_qty">廢紙(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="paper_qty" id="paper_qty" value='.$t[9].'></input>
				</td>				 	
			</tr>

			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="plastic_qty">塑膠廢料(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="plastic_qty" id="plastic_qty" value='.$t[10].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="metal_qty">金屬(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="metal_qty" id="metal_qty" value='.$t[11].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="toner_qty">碳粉盒(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="toner_qty" id="toner_qty" value='.$t[12].'></input>
				</td>				 	
			</tr>


			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="clothes_qty">衣物(kg)</label>
				</td>
				<td>
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="clothes_qty" id="clothes_qty" value='.$t[13].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="book_qty">書本(kg)</label>
				</td>
				<td>					
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="book_qty" id="book_qty" value='.$t[14].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">				
					<label for="toy_qty">玩具(kg)</label>
				</td>
				<td>					
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="toy_qty" id="toy_qty" value='.$t[15].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="other_qty">其他(kg)</label>
				</td>
				<td>					
				 	<input style="margin-left:10px" type="number" step="0.01"  min="0" max="10000000" name="other_qty" id="other_qty" value='.$t[16].'></input>
				</td>				 	
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="other_desc">註明</label>
				</td>
				<td>				
				 	<input style="margin-left:10px" type="text" name="other_desc" id="other_desc" value="'.$t[17].'"></input>
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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>
';
	if ($_SESSION['trans_type_curr']=="HSE"){
	 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="housing.php">Back</a></span>';};								 
	if ($_SESSION['trans_type_curr']=="KBS"){
	 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="kerbside.php">Back</a></span>';};
	if ($_SESSION['trans_type_curr']=="CGS"){
	 print '<span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="station.php">Back</a></span>';};


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

