<?php // Script 8.8 - login.php
/* This page lets people log into the site (in theory). */

session_start();
define('TITLE', 'Monthly Time-Series AQ Sampling');
include('templates/header.html');
include('templates/iconn.php');


//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';



// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

$_SESSION['trans_type_curr']="sampletx";


//echo "This is a valid user: ".$_SESSION['vuserid'];
/* 
if (empty($_SESSION['vuserid'])) {
		print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p>';		
		exit();
	} else{
					$q = "select d.district_id did, d.name dname, k.kerbside_id kid, k.name kname
									from user_district ud, district d, kerbside k 
									where k.district_id = ud.district_id and ud.district_id = d.district_id and ud.userid = '".$_SESSION['vuserid']."'";
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


						
} */



//[CGS - ASG prototype remark] ------------------------------------------------------
/* 
$comp="true";
$invq="false";
$kb_trans=0;						
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['kerbside']) and !empty($_POST['frm_date']) and !empty($_POST['to_date'])) 
	{
				$criteria = '街站位置: '.$_POST['kerbside']. ';  由: '.$_POST['frm_date'].' 至: '.$_POST['to_date'];
				if ($_POST['kerbside']=='ALL'){
					$kerbside_id= " and ud.userid='".$_SESSION['vuserid']."'";
				}else{
					$kerbside_id=" and ud.userid='".$_SESSION['vuserid']."' and t.source_id = '".$_POST['kerbside']."' ";
				}
					$q = "select t.trans_date t_trans_date, t.trans_type t_trans_type, t.trans_no t_trans_no, t.source_id t_source_id, k.name kname, t.glass_bottle_qty t_glass_bottle_qty, t.ee_qty t_ee_qty, 
					         t.comp_qty t_comp_qty, t.battery_qty t_battery_qty, t.light_qty t_light_qty, t.paper_qty t_paper_qty, t.plastic_qty t_plastic_qty, t.metal_qty t_metal_qty, t.toner_qty t_toner_qty, 
					         t.clothes_qty t_clothes_qty, t.book_qty t_book_qty, t.toy_qty t_toy_qty, t.other_desc t_other_desc, t.other_qty t_other_qty "
					."from recycle_trans t, kerbside k, user_district ud where ud.district_id=k.district_id and t.trans_type = 'KBS'".$kerbside_id." and t.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' and t.source_id=k.kerbside_id order by t.trans_date desc, t.trans_no desc;";

				
					$result_kbs=$dbc->query($q);
					if ($result_kbs->num_rows){						
						$criteria = $criteria.'<span> =>Total no. of records found: '.$result_kbs->num_rows;
					}else{
						
						$criteria = $criteria.'<span class="text--error"> =>No Record has been found!</span>';					
					}					
					
						$q="select sum(t.glass_bottle_qty) tot_glass_bottle_qty, sum(t.ee_qty) tot_ee_qty, sum(t.comp_qty) tot_comp_qty, sum(t.battery_qty) tot_battery_qty, sum(t.light_qty) tot_light_qty,
						             sum(t.paper_qty) tot_paper_qty,sum(t.plastic_qty) tot_plastic_qty, sum(t.metal_qty) tot_metal_qty, sum(t.toner_qty) tot_toner_qty, sum(t.clothes_qty) tot_clothes_qty,
						             sum(t.book_qty) tot_book_qty, sum(t.toy_qty) tot_toy_qty, sum(t.other_qty) tot_other_qty "
								     ."from recycle_trans t, kerbside k, user_district ud where ud.district_id=k.district_id and t.trans_type = 'KBS'".$kerbside_id." and t.trans_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' and t.source_id=k.kerbside_id order by t.trans_date desc, t.trans_no desc;";
						$result_tot=$dbc->query($q);
						if ($result_tot->num_rows){						
							$r_tot=$result_tot->fetch_object(); 
							$kb_trans=1;		
						}
											
					
		}else{
				$criteria = "";
				$comp="false";				
		}

}  */
//---------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if ((!empty($_POST['id'])) && (!empty($_POST['loc_id'])) && (!empty($_POST['frm_date'])) && (!empty($_POST['to_date']))  )
	{
		$q = "select ";
		echo '<table style="width:100%;margin-left:0%;"><tr style="text-align:center;color:#555555;">';
		$q=$q."loc_id as _loc_id,sample_date as _sample_date,m_time as _m_time,ele_id as _ele_id ";
		$q=$q.",sample_v as _sample_v ";
		$f=" from tsms.sample_ele ";
		$w=" where ";
		$cmb_id_comp = $_POST['id'][0];
		$on_loc_id="";
		$on_sample_date="";
		$on_m_time="";
		$on_ele_id="";


		$loc_id="(";
		for($i = 0; $i < count($_POST['loc_id']); $i++) {			
			if ($i==count($_POST['loc_id'])-1) {
				$loc_id=$loc_id."loc_id='".$_POST['loc_id'][$i]."') and "; 
			}
			else{
				$loc_id=$loc_id."loc_id='".$_POST['loc_id'][$i]."' or "; 
			}
		};		

		$on_ele_id="(";
		for($i = 0; $i < count($_POST['id']); $i++) {			
			if ($i==count($_POST['id'])-1) {
				$on_ele_id = $on_ele_id."ele_id='".$_POST['id'][$i]."') and " ;
			}
			else{
				$on_ele_id = $on_ele_id."ele_id='".$_POST['id'][$i]."' or "; 
			}
		};		


		if ($w==" where "){
			$q=$q.$f.$w;
			//if (count($_POST['id'])>1){
				$q=$q.$on_loc_id.$on_sample_date.$on_m_time.$loc_id.$on_ele_id."sample_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."' ";
			//};
			$w="";
		}
	 //	$q=$q."	group by ".$_POST['id'][0].".sample_date, ".$_POST['id'][0].".m_time order by ".$_POST['id'][0].".sample_date, ".$_POST['id'][0].".m_time,".$_POST['id'][0].".ele_id;";
	 //	$q=$q."	group by loc_id,sample_date,m_time order by sample_date,m_time,ele_id;";
		$q=$q."	order by loc_id,sample_date,m_time,ele_id;";

	 	print $q;
		
		/* 
			$q = "select a.loc_id, a.sample_date, a.m_time, a.ele_id, a.sample_v,  
			2a.ele_id b_ele_id, 2a.sample_v b_sample_v, 
			3a.ele_id c_ele_id, 3a.sample_v c_sample_v 
			from tsms.sample_ele a, tsms.sample_ele as 2a, tsms.sample_ele as 3a
			where (a.loc_id = 2a.loc_id
			and 2a.loc_id = 3a.loc_id)
			and (a.sample_date = 2a.sample_date
			and 2a.sample_date = 3a.sample_date)
			and (a.m_time = 2a.m_time
			and 2a.m_time = 3a.m_time)
			and a.sample_date='2019-05-01'
			and (a.ele_id<>2a.ele_id
			and a.ele_id<>3a.ele_id
			and 2a.ele_id<>a.ele_id
			and 2a.ele_id<>3a.ele_id
			and 3a.ele_id<>a.ele_id
			and 3a.ele_id<>2a.ele_id
			)
			group by a.sample_date, a.m_time
			order by a.sample_date, a.m_time, ele_id;";
			
		*/	$result_tx=$dbc->query($q);
	}
}




//[ASG prototype] ------------------------------------------------------
$h = "select * from element order by ele_id";
$result_ele=$dbc->query($h);
$totnumele = 3;  //Total no. of elements selected
$h = "select * from loc order by name";
$result_loc=$dbc->query($h);

//$result_tx=$dbc->msql_query($q);
/* if ($result_tx->num_rows){						
$criteria = $criteria.'<span> =>Total no. of records found: '.$result_tx->num_rows;
}else{

$criteria = $criteria.'<span class="text--error"> =>No Record has been found!</span>';					
}					
*///---------------------------------------------------------------------



print '
<form action="sampletx.php" method="post">
    <table style="width:100%;margin-left:0%;">
		<th style="background-color:#008F00;color:yellow;font-weight:bold;font-size:28px">Monthly Time Series AQ Measurement</th>
  <span style="padding-right:1cm"></span>
  </table>
  <div column=2>';
  

//[CGS - ASG prototype remark] ------------------------------------------------------
// msql_fetch_object() is similar to msql_fetch_array(), with one difference - an object is returned, instead of an array. Indirectly, that means that you can only access the data by the field names, and not by their offsets (numbers are illegal property names).
// Speed-wise, the function is identical to msql_fetch_array(), and almost as quick as msql_fetch_row() (the difference is insignificant).

 /* while ($r=$result->fetch_object()){
    //printf("%s (%s)\n",$obj->Lastname,$obj->Age);
    print '<option value="'.$r->kid.'">'.'('.$r->kname.') '.$r->kname.'</option>';
  }
  // Free result set
  	mysqli_free_result($result);


  */

//--------------------------------------------------------------------

/* 
print '</select>';
  print'<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:1px">From</span>
  <input type="date" name="frm_date" style="size:5">
  <span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:15px">To</span>
  <input type="date" name="to_date">
</form>
</div>
<p>';
 */

//[CGS - ASG prototype remark] ------------------------------------------------------
/* 
if (!empty($criteria)){ 
  print '<span style="color:blue;font-weight:bold">查詢條件: '.$criteria.'</span>'; 
  
  if ($kb_trans==1){
	  print '<hr><button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">玻璃樽(kg)<br>'.$r_tot->tot_glass_bottle_qty.'</br></button>'
			 .'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">電器(kg)<br>'.$r_tot->tot_ee_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">電腦及相關用品(kg)<br>'.$r_tot->tot_comp_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">充電池(kg)<br>'.$r_tot->tot_battery_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">光管及慳電膽(kg)<br>'.$r_tot->tot_light_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">廢紙(kg)<br>'.$r_tot->tot_paper_qty.'</br></button>'			
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">塑膠廢料(kg)<br>'.$r_tot->tot_plastic_qty.'</br></button>'				
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">金屬(kg)<br>'.$r_tot->tot_metal_qty.'</br></button>'				
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">碳粉盒(kg)<br>'.$r_tot->tot_toner_qty.'</br></button>'		
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;150px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">衣物(kg)<br>'.$r_tot->tot_clothes_qty.'</br></button>'		
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">書本(kg)<br>'.$r_tot->tot_book_qty.'</br></button>'		
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">玩具(kg)<br>'.$r_tot->tot_toy_qty.'</br></button>'			
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">其他(kg)<br>'.$r_tot->tot_other_qty.'</br></button>'	;	
	}
}
 
print '<hr>  
  <p></p>  <p></p>  <p></p>
';

 */
//--------------------------------------------------------------------


//Selections
print '<form action="sampletx.php" method="post">';  
 $criteria = 'Prototyping';  
 print '</select>';
  print'<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:1px">From</span>
  <input type="date" name="frm_date" style="size:5">
  <span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:15px">To</span>
  <input type="date" name="to_date">';

 if ($result_loc->num_rows){ 
	//while ($r_sample=$result_tx->fetch_object()){
	echo '<table style="width:100%;margin-left:0%;">';
	$ecols = $result_loc->field_count;
	$loc_no_l=0;
	print '<tr>';
	while ($r_loc = $result_loc->fetch_array(MYSQLI_BOTH)) {
	//print '<td><input type="checkbox" name="'.$r_ele["ele_id"].'" value="'.$r_ele["ele_id"].'">'.$r_ele["name"].'</td>';
		print '<td><input type="checkbox" name="loc_id[]" value="'.$r_loc["loc_id"].'">'.$r_loc["name"].'</td>';
		++$loc_no_l;
		if ($loc_no_l == 10) {
			print '</tr><tr>'; 
			$loc_no_l=0;
		};
	}								 
	print '</tr><tr><td><input style="font-weight:bold" type="checkbox" name="loc_id[]" value="A">~ALL~</td></tr></table><br><hr></hr>';
 }

 if ($result_ele->num_rows){ 
	//while ($r_sample=$result_tx->fetch_object()){
	echo '<table style="width:100%;margin-left:0%;">';
	$ecols = $result_ele->field_count;
	$ele_no_l=0;
	print '<tr>';
	while ($r_ele = $result_ele->fetch_array(MYSQLI_BOTH)) {
	//print '<td><input type="checkbox" name="'.$r_ele["ele_id"].'" value="'.$r_ele["ele_id"].'">'.$r_ele["name"].'</td>';
	print '<td><input type="checkbox" name="id[]" value="'.$r_ele["ele_id"].'">'.$r_ele["name"]
	      .'<input type="hidden" name="id_unt[]" value="'.$r_ele["ele_id"].'('.$r_ele["unt"].')">'.'</td>';
	++$ele_no_l;
		if ($ele_no_l == 10) {
			print '</tr><tr>'; 
			$ele_no_l=0;
		};
	}								 
	print '</tr><tr><td><input style="font-weight:bold" type="checkbox" name="id[]" value="A">~ALL~</td></tr></table><br>';
 }
 print '<input type="submit" value="Submit"></form><br>';





//欄位header


if ((!empty($_POST['id'])) && (!empty($_POST['loc_id']))){
	echo '<table style="width:100%;margin-left:0%;"><tr style="text-align:center;color:#555555;">';
	print '<th style="background-color:#008F00;color:white">Location</th><th style="background-color:#008F00;color:white">Date</th><th style="background-color:#008F00;color:white">Time</th>';
//	foreach ($_POST['id'] as $key => $value) {
	foreach ($_POST['id_unt'] as $key => $value) {

		//echo $value . "<br />";		
		print '<th style="background-color:#008F00;color:white">'.$value.'</th>';
	}

	print '</tr>';
};


/* 
          //associative and numeric array 
          $row = $result->fetch_array(MYSQLI_BOTH);
          printf ("%s (%s)\n", $row[0], $row["CountryCode"]);

		  //free result set 
		  $result->free();

 */			 
$criteria = 'measures';  
//if ((!empty($_POST['id'])) && (!empty($_POST['loc_id']))){ 
if ((!empty($_POST['id'])) && (!empty($_POST['loc_id'])) && (!empty($_POST['frm_date'])) && (!empty($_POST['to_date'])) ){

/* 						if (!empty($_POST['id']))

	print "%s (%s)\n Total columns : ".$cols;
	while (list($key, $value) = each($arr))
	{
	//  unset($arr[$key + 1]);
	echo $value . PHP_EOL;
	}

*/

//while ($r_sample=$result_tx->fetch_object()){
	$cols = $result_tx->field_count;
	$loc_id_tx = "";
	$date_tx = "";
	$time_tx = "";
	$j=0;

	while ($r_sample = $result_tx->fetch_array(MYSQLI_BOTH)) {

		if (($loc_id_tx<>$r_sample[0]) || ($date_tx<>$r_sample[1]) || ($time_tx <> $r_sample[2])){

			if ($loc_id_tx <> ""){
				for ($i=$j; $i<count($_POST['id']); $i++ ){   //Fill in remining buckets before start a new line
					print '<td style="background-color:#00B386;text-align:center;color:white">---</td>';
				}	
			};
			
			print '<tr><td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[0].'</td>';
			print '<td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[1].'</td>';
			print '<td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[2].'</td>';
			$j=0;  //Once the new line form, $j should be reset and scanning of buckets should be started over again
		}
	//printf("%s (%s)\n",$obj->Lastname,$obj->Age);
	//print '<tr style="text-align:center;color:#555555;"><td style="background-color:#00B386;color:white">'.$r_sample[0]  // $r_sample->loc_id
	
	//.'</td><td style="background-color:#00B386;color:white">'.$r_sample->trans_date.'</td>'
	
	//.'<td style="background-color:#00B386;color:white"><a href="update.php?cluster='.$r_sample[0].'|'.$r_sample[1].'|'.$r_sample[2].'|'.$r_sample[3].'|'.$r_sample[4].'</a></td>'
	//.'<td style="background-color:#00B386;color:white">'.$r_sample[0].'</td><td style="background-color:#00B386;color:white">'.$r_sample[1].'</td><td style="background-color:#00ADB3;color:white">'.$r_sample[2].'</td>'
	//.'<td style="background-color:#00ADB3;color:white">'.$r_sample[3].'</td><td style="background-color:#00ADB3;color:white">'.$r_sample[4].'</td>'

		$loc_id_tx = $r_sample[0];
		$date_tx = $r_sample[1];
		$time_tx = $r_sample[2];

		if ($j<count($_POST['id'])){
			for ($i=0; $i<count($_POST['id']); $i=$j){  //***/This FOR loop is a MUST for ELSE statement working
				//print 'line 383: $ i = '.$i.'  and  $ j = '.$j.'<br>';
				if ($r_sample[3]==$_POST['id'][$j]) { //locatio'n selections vs col headers
					print '<td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[$cols-1].'</td>';
					$j++;
					//$j=0;
					break;  //Once find the bucket, proceed to the next record 
				}
				else{  /****Tracking for those no transactions for "First" few selection elements against first few buckets and this requires 
					   "FOR loop" for keep scanning the next "Right" bucket on the same row
					   Also, filling up the remaining buckets on the same row after fill up the Right bucket*/
					print '<td style="background-color:#00B386;text-align:center;color:white">---</td>';
					$j++;
				}

			}
		}


		//print '</tr>';

		/* 
		.'<td style="background-color:#EB9900;color:white">'.$r_sample->t_other_desc					
		.'</td><td style="background-color:#B5B5B5"><a href="delete.php?cluster='.$r_sample->t_trans_no.'|'.$r_sample->t_trans_date.'|'.$r_sample->t_source_id.'|'.$r_sample->kname.'|'
		.$r_sample->t_glass_bottle_qty.'|'.$r_sample->t_ee_qty.'|'.$r_sample->t_comp_qty.'|'.$r_sample->t_battery_qty.'|'.$r_sample->t_light_qty.'|'.$r_sample->t_paper_qty.'|'.$r_sample->t_plastic_qty
		.'|'.$r_sample->t_metal_qty.'|'.$r_sample->t_toner_qty.'|'.$r_sample->t_clothes_qty.'|'.$r_sample->t_book_qty.'|'.$r_sample->t_toy_qty.'|'.$r_sample->t_other_qty.'|';
		
		//iif(!empty($r_hse->other_desc), $r_hse->other_desc, '@'); 
		if (!empty($r_sample->t_other_desc)){
				print $r_sample->t_other_desc;} 
				else{ 
					print '@';} 			
					
		
		print '"><img src="img/del.png"  width="20" height="20" value='.$r_sample->t_trans_no.'></a></td>'; 

		*/                            
		//--------------------------------------------------------------------------------------                

	}

	if ($j<count($_POST['id'])){
		for ($i=0; $i<count($_POST['id']); $i=$j){
				print '<td style="background-color:#00B386;text-align:center;color:white">---</td>';
				$j++;
		}
	}
    mysqli_free_result($result_tx);
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


 //[CGS - ASG prototype remark] ------------------------------------------------------
/* 
	if ($comp=="false"){
			print '<p align="center" class="text--error">請確保查詢選項均已輸入!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">找不到任何記錄</p>';}

 */ //----------------------------------------------------------------------------------


 //$_POST['id']->free();

include('templates/footer.html'); // Need the footer.


?>