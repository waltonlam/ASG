<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Education Event');
include('templates/header.html');
include('templates/iconn.php');

// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];
$_SESSION['trans_type_curr']="EVN";


if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
		
		
					$q = "select d.district_id did, d.name dname
									from user_district ud, district d
									where ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";
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


$comp="true";
$invq="false";
				
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	
//	empty($_POST['act_no']) and !empty($_POST['frm_dt']) and !empty($_POST['to_dt']) and !empty($_POST['act_type']) and !empty($_POST['act_name']) and !empty($_POST['target_aud_type'])
//			and !empty($_POST['org_name']) and !empty($_POST['total_party']) and !empty($_POST['loc']
	
	if (!empty($_POST['frm_date']) and !empty($_POST['to_date'])) 
	{
				$criteria = '區域: '.$_POST['did']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				if ($_POST['did']=='ALL'){
//					$act_type= " and ud.userid='".$_SESSION['vuserid']."' ";
					$act_type= "ud.userid='".$_SESSION['vuserid']."' ";
				}else{
//					$act_type=" and ud.userid='".$_SESSION['vuserid']."' and e.district_id = '".$_POST['did']."' ";
					$act_type="ud.userid='".$_SESSION['vuserid']."' and e.district_id = '".$_POST['did']."' ";

				}
				
				/*
					$q = "select a.act_type a_act_type, a.act_name a_act_name,e.trans_no e_trans_no,e.district_id e_did,e.act_no e_act_no,e.trans_date e_trans_date,e.frm_t e_frm_t,e.to_t e_to_t,e.act_type e_act_type,e.act_name e_act_name,"
									."e.target_aud_type e_target_aud_type,e.org_name e_org_name,e.total_party e_total_party,e.loc e_loc,e.speaker e_spk, e.rmk e_rmk,"
									."t.target_aud_name t_target_aud_name,d.name dname from activity a, target_aud t, edu_event e, district d, user_district ud "
									."where a.act_type=e.act_type and e.target_aud_type=t.target_aud_type and ud.district_id=e.district_id and d.district_id=ud.district_id "
									.$act_type."and e.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' order by e.trans_date;";
				*/				
					$q = "select a.act_type a_act_type, a.act_name a_act_name,ag.age_cate e_age_cate,ag.descp e_age_descp,e.trans_no e_trans_no,e.district_id e_did,e.act_no e_act_no,e.trans_date e_trans_date,e.frm_t e_frm_t,e.to_t e_to_t,e.act_type e_act_type,e.act_name e_act_name,"
									."e.target_aud_type e_target_aud_type,e.org_name e_org_name,e.total_party e_total_party,e.loc e_loc,e.speaker e_spk, e.rmk e_rmk,"
									."t.target_aud_name t_target_aud_name,d.name dname from edu_event e left join activity a  on e.act_type=a.act_type left join target_aud t  on e.target_aud_type=t.target_aud_type "
									."left join district d on d.district_id=e.district_id  left join user_district ud  on d.district_id=ud.district_id left join age_grp ag on ag.age_cate=e.age_cate "
									."where ".$act_type."and e.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' order by e.trans_date desc,e.trans_no desc ;";

				
					$result_edu=$dbc->query($q);
					if ($result_edu->num_rows){						
						$criteria = $criteria.'<span> =>Total no. of records found: '.$result_edu->num_rows;

						$q="select sum(round(time_to_sec(TIMEDIFF(to_t,frm_t))/3600, 2)*e.total_party) nl_act_tot from edu_event e, activity a, district d, user_district ud 
						     where d.district_id=e.district_id and d.district_id=ud.district_id and e.act_type=a.act_type and ".$act_type
						     ."AND a.per_hr_cnt = 'Y' and e.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."';";

						$result_tot=$dbc->query($q);
						if (!$result_tot->num_rows){						
							print '<span class="text--error"> =>Activity Type Configuration Error!</span>';
						}						

						$q="select sum(round(time_to_sec(TIMEDIFF(to_t,frm_t))/3600, 2)*e.total_party) s_act_tot from edu_event e, activity a, district d, user_district ud 
						     where d.district_id=e.district_id and d.district_id=ud.district_id and e.act_type=a.act_type and ".$act_type
						     ."AND a.per_hr_cnt = 'N' and e.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."';";

						$result_stot=$dbc->query($q);
						if (!$result_stot->num_rows){						
							print '<span class="text--error"> =>Activity Type Configuration Error!</span>';
						}						


					}else{
						
						$criteria = $criteria.'<span class="text--error"> =>No Record has been found!</span>';
					
					}					
		}else{
				$criteria = "";
				$comp="false";				
		}

} 
	



print '
    <table style="width:100%;margin-left:0%;">
		<th style="background-color:#008F00;color:yellow;font-weight:bold;font-size:28px">教育活動/到訪記錄</th>
  <span style="padding-right:1cm"></span>
  </table>
<form action="edu_event.php" method="post">
  <div column=2>
  <span style="padding-right:1cm"></span>
  <span style="align:right;color:blue;font-weight:bold;font-size:18px">區域</span>
  <select name="did" style="font-size:18px">
  <option value="ALL">---ALL---</option>';
  
 	while ($r=$result->fetch_object()){
    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
    print '<option value="'.$r->did.'">'.'('.$r->did.') '.$r->dname.'</option>';
  }
  // Free result set
  	mysqli_free_result($result);


/*	
		  <option value="volvo">Volvo</option>
		  <option value="saab">Saab</option>
		  <option value="opel">Opel</option>
		  <option value="audi">Audi</option>
*/

print '</select>';
  print'<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:30px">由</span>
  <input type="date" name="frm_date" style="size:5">
  <span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:15px">至</span>
  <input type="date" name="to_date">
  <input type="submit">
</form>
</div>
<p>';


if (!empty($criteria)){ 
  print '<span style="color:blue;font-weight:bold">查詢條件: '.$criteria.'</span>'; 
	if ($result_edu->num_rows){	
			$r_tot=$result_tot->fetch_object();
			print '<hr><button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">常規教育活動<br>'.$r_tot->nl_act_tot.'(hrs)</button>';
			$r_stot=$result_stot->fetch_object();
			print '<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">特色教育活動<br>'.$r_stot->s_act_tot.'(hrs)</br></button>';

		}
//		 .'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">特色教育活動<br>'.'</br></button>';

}
 
print '<hr>  
  <p></p>  <p></p>  <p></p>
';

//欄位header
    echo '<table style="width:100%;margin-left:0%;">
			<tr style="text-align:center;color:#555555;">
			<th style="background-color:#008F00;color:white">紀錄序號</th>
			<th style="background-color:#008F00;color:white">區域</th>
			<th style="background-color:#008F00;color:white">活動編號</th>
			<th style="background-color:#008F00;color:white">日期</th>
			<th style="background-color:#008F00;color:white">開始時間</th>
			<th style="background-color: #008A8A;color:white">完結時間</th>
			<th style="background-color: #008A8A;color:white">活動性質</th>
			<th style="background-color: #008A8A;color:white">活動名稱</th>
			<th style="background-color: #008A8A;color:white">對象類別</th>
			<th style="background-color: #008A8A;color:white">年齡層</th>			
			<th style="background-color: #008A8A;color:white">機構名稱</th>
			<th style="background-color: #FF5959;color:white">參加人數</th>
			<th style="background-color: #FF5959;color:white">時數</th>
			<th style="background-color: #FF5959;color:white">地點</th>
			<th style="background-color: #B87800;color:white">導師/講者</th>
			<th style="background-color: #B87800;color:white">Person-Hour</th>
			<th style="background-color: #B87800;color:white">註明</th>
			<th style="background-color: #B87800;color:white">刪除</th>
			</tr>';
			if (!empty($criteria)){ 
					while ($r_edu=$result_edu->fetch_object()){
						    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
						    
						    $param1=$r_edu->e_trans_no.'|'.$r_edu->e_act_no.'|'.$r_edu->e_trans_date.'|'.$r_edu->e_frm_t.'|'.$r_edu->e_to_t
	    					.'|'.$r_edu->e_act_type.'|'.$r_edu->e_act_name.'|'.$r_edu->e_target_aud_type.'|'.$r_edu->e_org_name.'|'.$r_edu->e_total_party.'|';
	    						    					
								$timestamp1 = strtotime($r_edu->e_frm_t);
								$timestamp2 = strtotime($r_edu->e_to_t);
								$hour = round(abs($timestamp2 - $timestamp1)/(60*60), 2);
								 
	    					//$param1=$param.$hour.'|'.$r_edu->e_loc.'|'.$r_edu->e_spk.'|'.($hour*$r_edu->e_total_party).'|'.$r_edu->e_rmk;
	    					$param1=$param1.$r_edu->e_loc.'|'.$r_edu->e_spk.'|';
								if (!empty($r_edu->e_rmk)){
										$param1=$param1.$r_edu->e_rmk.'|'.$r_edu->e_did.'|'.$r_edu->e_age_cate.'|'.$r_edu->e_age_descp;} 
										else{ 
											$param1=$param1.'@'.'|'.$r_edu->e_did.'|'.$r_edu->e_age_cate.'|'.$r_edu->e_age_descp;} 



								$param2=$r_edu->e_trans_no.'|'.$r_edu->e_act_no.'|'.$r_edu->e_trans_date.'|'.$r_edu->e_frm_t.'|'.$r_edu->e_to_t
	    					.'|'.$r_edu->a_act_name.'|'.$r_edu->e_act_name.'|'.$r_edu->t_target_aud_name.'|'.$r_edu->e_org_name.'|'.$r_edu->e_total_party.'|';
								$param2=$param2.$hour.'|'.$r_edu->e_loc.'|'.$r_edu->e_spk.'|'.($hour*$r_edu->e_total_party);
/*
	    					print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">'.$r_edu->e_trans_no
	    					//.'</td><td style="background-color:#00B386;color:white">'.$r_st->trans_date.'</td>'
	    					.'<td style="background-color:#00B386;color:white">'.$r_edu->e_act_no
	    					.'<td style="background-color:#00B386;color:white"><a href="update.php?cluster='.$r_edu->e_trans_no.'|'.$r_edu->e_act_no.'|'.$r_edu->e_trans_date.'|'.$r_edu->e_frm_dt.'|'.$r_edu->e_to_dt
	    					.'|'.$r_edu->e_act_type.'|'.$r_edu->e_act_name.'|'.$r_edu->e_target_aud_type.'|'.$r_edu->e_org_name.'|'.$r_edu->e_total_party.'|';
	    						    					
								$timestamp1 = strtotime($r_edu->e_frm_dt);
								$timestamp2 = strtotime($r_edu->e_to_dt);
								$hour = abs($timestamp2 - $timestamp1)/(60*60);
								 
	    					print $hour.'|'.$r_edu->e_loc.'|'.$r_edu->e_spk.'|'.(($r_edu->e_to_dt - $r_edu->e_frm_dt)*$r_edu->e_total_party).'|'.$r_edu->e_rmk.'">'.$r_edu->e_trans_date.'</a></td>'
*/


								print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">'.$r_edu->e_trans_no
	    					//.'</td><td style="background-color:#00B386;color:white">'.$r_st->trans_date.'</td>'
	    					.'<td style="background-color:#00B386;color:white">'.$r_edu->e_did
	    					.'<td style="background-color:#00B386;color:white">'.$r_edu->e_act_no
	    					.'<td style="background-color:#00B386;color:white"><a href="update_event.php?cluster='.$param1.'">'.$r_edu->e_trans_date.'</a></td>'
	    					
	    					.'</td><td style="background-color:#00B386;color:white">'.$r_edu->e_frm_t.'</td><td style="background-color:#00ADB3;color:white">'.$r_edu->e_to_t.'</td>'
	    					.'<td style="background-color:#00ADB3;color:white">'.$r_edu->a_act_name.'</td><td style="background-color:#00ADB3;color:white">'.$r_edu->e_act_name.'</td><td style="background-color:#00ADB3;color:white">'.$r_edu->t_target_aud_name.'</td><td style="background-color:#00ADB3;color:white">'.$r_edu->e_age_descp.'</td>'
	    					.'<td style="background-color:#00ADB3;color:white">'.$r_edu->e_org_name.'</td><td style="background-color:#EB6E80;color:white">'.$r_edu->e_total_party.'</td><td style="background-color:#EB6E80;color:white">';
	    					
 					 			print $hour.'</td>';			    					
/*
	    					if ($r_edu->e_act_type=="D") {
	    						print 'NA'.'</td>';
	    					 }else{ 
	    					 			print $hour.'</td>';}			    					
*/	    					
	    					print '<td style="background-color:#EB6E80;color:white">'.$r_edu->e_loc.'</td><td style="background-color:#EB9900;color:white">'.$r_edu->e_spk.'</td><td style="background-color:#EB9900;color:white">';

 					 			print $hour*$r_edu->e_total_party.'</td>';
/*
	    					if ($r_edu->e_act_type=="D") {
	    							print 'NA'.'</td>';
	    					 }else{ 
	    					 			print $hour*$r_edu->e_total_party.'</td>';}
*/	    					
	    					print '<td style="background-color:#EB9900;color:white">'.$r_edu->e_rmk
	    					
								.'</td><td style="background-color:#B5B5B5"><a href="delete_event.php?cluster='.$param2.'|';
						
/* 					  			
								//iif(!empty($r_hse->other_desc), $r_hse->other_desc, '@'); 
								if (!empty($r_edu->e_rmk)){
										print $r_edu->e_rmk.'|'.$r_edu->e_did;} 
										else{ 
											print '@'.'|'.$r_edu->e_did;} 
*/											
											
								if (!empty($r_edu->e_rmk)){
											print $r_edu->e_rmk.'|'.$r_edu->e_did.'|'.$r_edu->e_age_cate.'|'.$r_edu->e_age_descp;} 
										else{ 
											print '@'.'|'.$r_edu->e_did.'|'.$r_edu->e_age_cate.'|'.$r_edu->e_age_descp;} 

								
								
								print '"><img src="img/del.png"  width="20" height="20" value='.$r_edu->e_trans_no.'></a></td></tr>';
	  					}
  				mysqli_free_result($result_edu);
  			}
			echo '</table>';

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
  <br></br><span style="color:blue;font-weight:bold;padding-left:35px;padding-right:15px"><a href="home.php">Home</a></span>
  <span style="color:blue;font-weight:bold;"><a href="addevent.php">新增教育活動/到訪記錄</a></span>
';



	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}


include('templates/footer.html'); // Need the footer.


?>