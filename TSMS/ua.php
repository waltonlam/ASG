<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'UserAcc');
include('templates/header.html');
include('templates/iconn.php');

// Print some introductory text:

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';

// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

//echo "This is a valid user: ".$_SESSION['vuserid'];
$_SESSION['trans_type_curr']="UA";


if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
		
/*		
					$q = "select d.district_id did, d.name dname
									from user_district ud, district d
									where ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";
*/

					$q = "select d.district_id did, d.name dname
									from district d;";
									
					$result_d=$dbc->query($q);
					if (!$result_d->num_rows){
							print '<p class="text--error">'.'District Configuration Problem!</p>';
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
	
	if (!empty($_POST['did'])) 
	{
				$criteria = '區域: '.$_POST['did'];
				//$criteria = 'CGS:   '.$_POST['did']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				if ($_POST['did']=='ALL'){
					$did="";
				}else{
					$did="ud.district_id='".$_POST['did']."' and ";
				}

				
				/*
					$q = "select a.act_type a_act_type, a.act_name a_act_name,e.trans_no e_trans_no,e.district_id e_did,e.act_no e_act_no,e.trans_date e_trans_date,e.frm_t e_frm_t,e.to_t e_to_t,e.act_type e_act_type,e.act_name e_act_name,"
									."e.target_aud_type e_target_aud_type,e.org_name e_org_name,e.total_party e_total_party,e.loc e_loc,e.speaker e_spk, e.rmk e_rmk,"
									."t.target_aud_name t_target_aud_name,d.name dname from activity a, target_aud t, edu_event e, district d, user_district ud "
									."where a.act_type=e.act_type and e.target_aud_type=t.target_aud_type and ud.district_id=e.district_id and d.district_id=ud.district_id "
									.$act_type."and e.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' order by e.trans_date;";
				*/				
				
				

					$q = "select r.region_code r_reg,r.name rname,ud.district_id ud_did,ud.userid ud_uid, d.name dname, u.userid u_uid, u.first_name u_fname, u.last_name u_lname, u.pwd u_pwd, u.email u_email,"
								 ."u.trans_date u_trans_date from region r, district d,user_district ud,user_acc u "
									."where ".$did."d.district_id=ud.district_id and r.region_code=d.region_code and u.userid=ud.userid order by r.region_code,ud.district_id,ud.userid;";

					//print $q;

					$result_ua=$dbc->query($q);
					if ($result_ua->num_rows){						
						$criteria = $criteria.'<span> =>Total no. of records found: '.$result_ua->num_rows;
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
		<th style="background-color:#008F00;color:yellow;font-weight:bold;font-size:28px">帳戶管理</th>
  <span style="padding-right:1cm"></span>
  </table>
<form action="ua.php" method="post">
  <div column=2>
  <span style="padding-right:1cm"></span>
  <span style="align:right;color:blue;font-weight:bold;font-size:18px">區域</span>
  <select name="did" style="font-size:18px">
  <option value="ALL">---ALL---</option>';
  
 	while ($r=$result_d->fetch_object()){
    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
    print '<option value="'.$r->did.'">'.'('.$r->did.') '.$r->dname.'</option>';
  }
  // Free result set
  	mysqli_free_result($result_d);


print '</select><span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:5px"></span>
  <input style="font-size:18px" type="submit">
</form>
</div>
<p>';

if (!empty($criteria)){ 
  print '<span style="color:blue;font-weight:bold">查詢條件: '.$criteria.'</span>'; 
}
 
print '<hr>  
  <p></p>  <p></p>  <p></p>
';

//					$q = "select ud.region_code r_reg,r.name rname,ud.district_id ud_did,ud.userid ud_uid, d.name dname, u.userid u_uid, u.first_name u_fname, u.last_name u_lname, u.pwd u_pwd, u.email u_email "
//								 ."from region r, district d,user_district ud,user_acc u "
    echo '<table style="width:100%;margin-left:0%;">
			<tr style="text-align:center;color:#555555;">
			<th style="background-color:#008A8A;color:white">地區</th>
			<th style="background-color:#008A8A;color:white">負責區域</th>
			<th style="background-color:#008A8A;color:white">用戶登入碼</th>
			<th style="background-color: #008A8A;color:white">姓</th>
			<th style="background-color: #008A8A;color:white">名</th>
			<th style="background-color:#008F00;color:white">删除權限</th>
			</tr>';
			if (!empty($criteria)){ 
					while ($r_ua=$result_ua->fetch_object()){
						    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
					    
						    $param1=$r_ua->u_trans_date.'|'.$r_ua->u_uid.'|'.$r_ua->u_fname.'|'.$r_ua->u_lname.'|'.$r_ua->u_pwd.'|';
 								if (!empty($r_ua->u_email)){
										$param1=$param1.$r_ua->u_email;} 
										else{ 
											$param1=$param1.'@';} 

/*
	    					print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">'.$r_ua->e_trans_no
	    					//.'</td><td style="background-color:#00B386;color:white">'.$r_st->trans_date.'</td>'
	    					.'<td style="background-color:#00B386;color:white">'.$r_ua->e_act_no
	    					.'<td style="background-color:#00B386;color:white"><a href="update.php?cluster='.$r_ua->e_trans_no.'|'.$r_ua->e_act_no.'|'.$r_ua->e_trans_date.'|'.$r_ua->e_frm_dt.'|'.$r_ua->e_to_dt
	    					.'|'.$r_ua->e_act_type.'|'.$r_ua->e_act_name.'|'.$r_ua->e_target_aud_type.'|'.$r_ua->e_org_name.'|'.$r_ua->e_total_party.'|';
	    						    					
								$timestamp1 = strtotime($r_ua->e_frm_dt);
								$timestamp2 = strtotime($r_ua->e_to_dt);
								$hour = abs($timestamp2 - $timestamp1)/(60*60);
								 
	    					print $hour.'|'.$r_ua->e_loc.'|'.$r_ua->e_spk.'|'.(($r_ua->e_to_dt - $r_ua->e_frm_dt)*$r_ua->e_total_party).'|'.$r_ua->e_rmk.'">'.$r_ua->e_trans_date.'</a></td>'
*/


								print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">('.$r_ua->r_reg.')'.$r_ua->rname
	    					.'<td style="background-color:#00B386;color:white">('.$r_ua->ud_did.')'.$r_ua->dname
	    					.'</td><td style="background-color:#00B386;color:white"><a href="update_ua.php?cluster='.$param1.'">'.$r_ua->ud_uid.'</a></td>'
	    					.'</td><td style="background-color:#00B386;color:white">'.$r_ua->u_fname
	    					.'</td><td style="background-color:#00B386;color:white">'.$r_ua->u_lname
								.'</td><td style="background-color:#B5B5B5"><a href="delete_ua.php?cluster='.$param1;							
								print '"><img src="img/del.png"  width="20" height="20" value='.$r_ua->u_uid.'></a></td></tr>';
	  					}
  				mysqli_free_result($result_ua);
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
  <span style="color:blue;font-weight:bold;"><a href="addua.php">新增用戶</a></span>
';



	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}


include('templates/footer.html'); // Need the footer.


?>