﻿<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Facility');
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
	if (!empty($_POST['trans_date']))	
	{
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				
				$u= "update fc_trans set "
				."trans_date='".$_POST['trans_date']."',station_id='".$_POST['sid']."',qty=".$_POST['qty']
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
					    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>';
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
								
								print $_REQUEST['cluster'].'<p></p>';
							 $token = strtok($_REQUEST['cluster'], "|");
							 
								$i=0; 
								while ($token !== false)
							   {
							   	 $t[$i]=$token;
							   //echo "$token<br>";
								   $token = strtok("|");
							  	 $i++;
							   }
							   
							   //exit();
							   							
							 //  print "-".$t[17]."-";
							   														   							
						//	$u=0;
							//print '<p></p>';
						//	while ($u < sizeof($t)){
						//		print $t[$u].'<br>';
						//		$u++;
					//		}
	
					}
		
					$q = "select d.district_id did, d.name dname, s.station_id sid, s.name sname 
								from user_district ud, district d, station s 
								where ud.district_id = d.district_id and s.district_id=ud.district_id and ud.userid = '".$_SESSION['vuserid']."'";						

					//print $q;
					$result_ud=$dbc->query($q);
					if (!$result_ud->num_rows){
							print '<p class="text--error">'.$_SESSION['vusername']." ".'is not responsible for any district!</p>';
							exit();
					}				


						//$q = "select * from user_acc where userid = ? and pwd= ?";
						//$stmt = $db->prepare($q);
						//$stmt->bind_param('ss',$_POST['userid'], $_POST['pwd']);
						//$stmt->execute();
						//mysqli_query($dbc, $q);			

				}
				
				
	
//$param1=$r_fc->t_trans_no.'|'.$r_fc->t_trans_date.'|'.$r_fc->sid.'|'.$r_fc->sname.'|'.$r_fc->t_qty;

//3|RE066|2017-07-04|10:15:00|11:45:00|A|咖啡渣艾草蚊香工作坊||光明學校|31|1.5|光明學校|曾海燕姑娘|46.5|
print '
<form action="update_fc.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">更新設施使用率記錄</p>
      
      <table style="width:92%;margin-left:2%;">
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="district_id">CGS</label>
				</td>				  				
				<td>  
				  <select style="margin-left:10px" name="sid" value="'.$t[2].'">';
				 	while ($r_ud=$result_ud->fetch_object()){
				 		if ($r_ud->sid==$t[2]){
				 			print '<option value="'.$r_ud->sid.'" selected>'.'('.$r_ud->sid.') '.$r_ud->sname.'</option>';
				 		}else{
				 			print '<option value="'.$r_ud->sid.'">'.'('.$r_ud->sid.') '.$r_ud->sname.'</option>';
				 		}
				  }	
print	'</td></tr>			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">設施使用日期</label>
				</td>				  				
				<td>  
				  <input style="margin-left:10px" type="date" name="trans_date" id="trans_date" value='.$t[1].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="total_party">人數</label>
				</td>				  				
				<td>  
				 	<input style="margin-left:10px" type="number" step="1"  min="1" max="2000" name="qty" id="qty" required value='.$t[4].'></input>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <input style="margin-left:10px" type="hidden" name="trans_no" id="trans_no" value='.$t[0].'></input>				
				</td>				
				<td>
				  <input class=button--general style="margin-left:10px" type="submit" value="更新">
				</td>  
			</tr></table>';


  // Free result set
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
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>
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

