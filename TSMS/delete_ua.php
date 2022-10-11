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
				//$criteria = '屋苑/機構: '.$_POST['house']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];		
		$dbc->autocommit(FALSE);	
		 $u="delete from user_acc where userid='".$_POST['userid']."';";
				if ($dbc->query($u) === TRUE) {
						 $u="delete from user_district where userid='".$_POST['userid']."';";													
							if ($dbc->query($u) === TRUE) {							 							 
							    $dbc->autocommit(TRUE);
							    echo '<p style="color:blue;font-weight:bold;text-align:center;font-size:1cm">記錄已成功刪除</p>
							    <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="ua.php">Back</a></span>';
								}else{
									    echo "Error: " . $u . "<br>" . $dbc->error;	
									    exit();											
											}

				} else {
				    		echo "Error: " . $u . "<br>" . $dbc->error;
								};

		exit();

} 

if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
					
					if (empty($_REQUEST['cluster'])) {
							print '<p class="text--error">There is no information for Deletion<br>Go back and try again.</p>';		
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
							//   echo "i=$i-"."$token"."-<br>";
								   $token = strtok("|");
							  	 $i++;
							   }

							if ($t[4]=="@"){$t[4]="";} 

							$q = "select ud.userid uid,d.district_id did, d.name dname
											from district d left join (select ud.userid, ud.district_id
																									from user_district ud where ud.userid='".$t[1]."') ud on d.district_id=ud.district_id order by region_code,d.district_id;";
											
							$result_rd=$dbc->query($q);
							if (!$result_rd->num_rows){
									print '<p class="text--error">'.'District Configuration Problem!</p>';
									exit();
							}				

						//	$u=0;
							//print '<p></p>';
						//	while ($u < sizeof($t)){
						//		print $t[$u].'<br>';
						//		$u++;
					//		}
	
					}
		
				}
				
				
print '
<form action="delete_ua.php" method="post">
			<p style="text-align:center;font-size:28px;color:blue;font-weight:bold;">刪除用戶編碼記錄</p>
      
      <table style="width:100%;margin-left:2%;">
      
      
      <tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="userid">用戶編碼</label>
				</td>				  				
				<td><input style="margin-left:10px" type="hidden" name="userid" id="userid" value="'.$t[1].'"></input>  
				  <p style="margin-left:10px">'.$t[1].'</p>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="act_no">姓氏</label>
				</td>
				<td>				
				  <p style="margin-left:10px">'.$t[2].'</p>
				</td>				  				
			</tr>
			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				  <label for="trans_date">名字</label>
				</td>				  				
				<td>  			
				  <p style="margin-left:10px">'.$t[3].'</p>
				</td>				  				
			</tr>				

<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>

			<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
					<label for="did[]">負責地區</label>
				</td>
				<td>';
					$i=1;
			   	while ($r=$result_rd->fetch_object()){
			   		if (!empty($r->uid)){
						    print '<input style="margin-left:20px" type="checkbox" name="did[]" id="did[]" checked value="'.$r->did.'">('.$r->did.') '.$r->dname.'</option>';
						  	if ($i==6){print '<br><br>'; $i=0;}
						    $i++;
						 }else{
						   	print '<input style="margin-left:20px" type="checkbox" name="did[]" id="did[]" value="'.$r->did.'">('.$r->did.') '.$r->dname.'</option>';
						  	if ($i==6){print '<br><br>'; $i=0;}
						    $i++;
						  }
  				}			
			
print '<tr style="color:#555555;">
				<td style="width:48%;text-align:right">
				</td>				
				<td>
				  <br></br><input class=button--general style="margin-left:10px" type="submit" value="刪除紀錄">
				</td>  
			</tr></table>';			
			

print'
  <br></br><p></p><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span><span style="color:blue;font-weight:bold;padding-left:15px;padding-right:15px"><a href="ua.php">Back</a></span>
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


