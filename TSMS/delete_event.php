<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Delete');
include('templates/header.html');
include('templates/iconn.php');




// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];


$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	
	if (!empty($_POST['trans_no']))	
	{
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				
				$u= "delete from edu_event where trans_no=".$_POST['trans_no'];
				
					//date('Y/m/d', $_SESSION['trans_date'])      
					//print $u;

					if ($dbc->query($u) === TRUE) {
					    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">記錄已成功刪除</p>
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
							exit();
					}else{
								//trans_no.'|'.trans_date.'|'.$r_hse->comp_qty.'|'.$r_hse->source_id.'|'.$r_hse->name.'|'.$r_hse->glass_bottle_qty.'|'.$r_hse->ee_qty.'|'
								//.$r_hse->comp_qty.'|'.$r_hse->battery_qty.'|'.$r_hse->light_qty.'|'.$r_hse->paper_qty.'|'.$r_hse->plastic_qty.'|'
								//.$r_hse->metal_qty.'|'.$r_hse->toner_qty.'|'.$r_hse->clothes_qty.'|'.$r_hse->book_qty.'|'.$r_hse->toy_qty.'|'.$r_hse->other_qty.'|'.$r_hse->other_desc.

// $param=$r_edu->e_trans_no.'|'.$r_edu->e_act_no.'|'.$r_edu->e_trans_date.'|'.$r_edu->e_frm_t.'|'.$r_edu->e_to_t
//	    					.'|'.$r_edu->e_act_type.'|'.$r_edu->e_act_name.'|'.$r_edu->e_target_aud_type.'|'.$r_edu->e_org_name.'|'.$r_edu->e_total_party.'|';									
								
								//print $_REQUEST['cluster'].'<p></p>';
							 $token = strtok($_REQUEST['cluster'], "|");

								$i=0; 
								while ($token !== false)
							   {
							   	 $t[$i]=$token;
							//   echo "i=$i-"."$token"."-<br>";
								   $token = strtok("|");
							  	 $i++;
							   }

							  if ($t[14]=="@"){$t[14]="";} 

									$dst="select * from district where district_id='".$t[15]."';";
									$result_dst=$dbc->query($dst);
									if (!$result_dst->num_rows){
											$dst_name="N/A";
									}else{
											$r_dst=$result_dst->fetch_object();
											$dst_name=$r_dst->name;
										}
										
/*									
									$aud="select * from target_aud;";
									$result_aud=$dbc->query($aud);
				
									$dst="select * from district;";
									$result_dst=$dbc->query($dst);	
*/							   							
						//	$u=0;
							//print '<p></p>';
						//	while ($u < sizeof($t)){
						//		print $t[$u].'<br>';
						//		$u++;
					//		}
	
					}
		
				}
				
				
// $param=$r_edu->e_trans_no.'|'.$r_edu->e_act_no.'|'.$r_edu->e_trans_date.'|'.$r_edu->e_frm_t.'|'.$r_edu->e_to_t
//	    					.'|'.$r_edu->e_act_type.'|'.$r_edu->e_act_name.'|'.$r_edu->e_target_aud_type.'|'.$r_edu->e_org_name.'|'.$r_edu->e_total_party.'|';	
	

print '
<form action="delete_event.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">刪除教育活動記錄</p>
      
      <table style="width:100%;margin-left:2%;">
      
      
      <tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="district_id">區域</label>
				</td>				  				
				<td>  
				  <p style="margin-left:10px">('.$t[15].')'.$dst_name.'</p>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="act_no">活動編號</label>
				</td>
				<td>				
				  <input style="margin-left:10px" type="hidden" name="trans_no" id="trans_no" value='.$t[0].'></input>
				  <p style="margin-left:10px">'.$t[1].'</p>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">活動日期</label>
				</td>				  				
				<td>  			
				  <p style="margin-left:10px">'.$t[2].'</p>
				</td>				  				
			</tr>				
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">開始時間</label>
				</td>				  				
				<td>  
				  <p style="margin-left:10px">'.$t[3].'</p>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">完結時間</label>
				</td>				  				
				<td>  			
				  <p style="margin-left:10px">'.$t[4].'</p>
				</td>				  				
			</tr>						  
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="act_type">活動性質</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[5].'</p>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="act_name">活動名稱</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[6].'</p>
				</td>				  				
			</tr>					
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="target_aud_type">對象類別</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[7].'</p>
				</td>				  				
			</tr>	
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="target_aud_type">年齡層</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[17].'</p>
				</td>				  				
			</tr>	
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="org_name">機構名稱</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[8].'</p>
				</td>				  				
			</tr>				
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="total_party">參加人數</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[9].'</p>
				</td>				  				
			</tr>				
			
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="total_party">時數</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[10].'</p>
				</td>				  				
			</tr>						
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="total_party">地點</label>
				</td>				  				
				<td>  						
					<p style="margin-left:10px">'.$t[11].'</p>
				</td>				  				
			</tr>					
				<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="speaker">導師/講者</label>
				</td>				  				
				<td>  		
					<p style="margin-left:10px">'.$t[12].'</p>
				</td>				  				
			</tr>					
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="speaker">Person-Hour</label>
				</td>				  				
				<td>  		
					<p style="margin-left:10px">'.$t[13].'</p>
				</td>				  				
			</tr>							
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="rmk">註明</label>
				</td>				  				
				<td>  			
					<p style="margin-left:10px">'.$t[14].'</p>
				</td>				  				
			</tr>					
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <br></br><input class=button--general style="margin-left:10px" type="submit" value="刪除紀錄">
				</td>  
			</tr></table>';			
			



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


