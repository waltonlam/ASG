<?php 
session_start();
define('TITLE', 'Monthly Time-Series AQ Sampling');
include('templates/header.html');
include('templates/iconn.php');
include('templates/fn.php');

function getad()
{
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ad = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ad = $forward;
    }
    else
    {
        $ad = $remote;
    }

	return str_replace(".","",$ad);
	//str_replace("world","Peter","Hello world!")
}

$uad = getad();

//echo "</p>".$uad."</p>"; 

//<p>Users who are logged in can take advantage of certain features like this, that, and the other thing.</p>';



// Check if the form has been submitted:

//echo '<style>input[type="text"]{background-color:#6F3;	color:#FFF;}</style>';

$_SESSION['trans_type_curr']="sampletx";
//$_SESSION['o']=rand(10,1000);
$_SESSION['o']=$uad;
$_SESSION['exp']="";



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
				$criteria = '????????????: '.$_POST['kerbside']. ';  ???: '.$_POST['frm_date'].' ???: '.$_POST['to_date'];
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
$h="";
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if (!empty($_POST['EG'])){
		print 'Select EG = '.$_POST['EG'];
		$h = "select * from element where gcde = '".$_POST['EG']."' order by ele_id";
		print '$h = '.$h;
		$result_ele=$dbc->query($h);
		$totnumele = 3;  //Total no. of elements selected
	}

	if ((!empty($_POST['id'])) && (!empty($_POST['loc_id'])) && (!empty($_POST['frm_date'])) && (!empty($_POST['to_date']))  )
	{
		$_SESSION['param'] = $_POST['id'];

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
		$T="Create  table T".$_SESSION['o'];
		$G="Create  table G 
			select T0.loc_id,T0.sample_date,T0.m_time, ";
		$d="";
		$st_L="";
		$st_R="";
		
		// Select * From sample_ele where (loc_id='CDSS' or loc_id='ABC') and ele_id='2-MHEPTA' and sample_date between '2019-01-01' and '2019-01-01';
		

		$loc_id="(";
		for($i = 0; $i < count($_POST['loc_id']); $i++) {			
			if ($i==count($_POST['loc_id'])-1) {
				$loc_id=$loc_id."loc_id='".$_POST['loc_id'][$i]."') and "; 
			}
			else{
				$loc_id=$loc_id."loc_id='".$_POST['loc_id'][$i]."' or "; 
			}
		};		
		print "ln134 - loc_id=".$loc_id."</p>";
		


	//*********Create T**************/	
		$rn1="drop table if exists T".$_SESSION['o'];
		$on_ele_id="(";
		for($i = 0; $i < count($_POST['id']); $i++) {			
			if ($i==count($_POST['id'])-1) {
				$on_ele_id = $on_ele_id."ele_id='".$_POST['id'][$i]."') and " ;
			}
			else{
				$on_ele_id = $on_ele_id."ele_id='".$_POST['id'][$i]."' or "; 
			}

			$d.=$rn1.$i.";".$T.$i." Select loc_id,sample_date,m_time,sample_v as '".$_POST['id'][$i]
				."' From sample_ele where ".$loc_id." ele_id='".$_POST['id'][$i]."' and sample_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."'; ";
			//$d.=$z;
			print "</p>ln192 : d=".$d."</p>";

		};		
	//=========Create T===============/	



	//*****Restrcture Left&Right************

		$tbl_idx=0;
		//$st_L = "select T".$tbl_idx.".loc_id,T".$tbl_idx.".sample_date,T".$tbl_idx.".m_time,";

		$i=0;
		$J="";
		$lvl=$i;
		$rn2="drop table if exists A".$_SESSION['o'].$i.";";

		if (count($_POST['id']) > 1){

			$J="create table A".$_SESSION['o'].$i." select T".$_SESSION['o'].$i.".loc_id,T".$_SESSION['o'].$i."
				.sample_date,T".$_SESSION['o'].$i.".m_time,T".$_SESSION['o'].$i.".".$_POST['id'][$i];
				
			//If ($i++ <= count($_POST['id'])){
				$J .= ",T".$_SESSION['o'].($i+1).".".$_POST['id'][($i+1)];
			//}
			
			$J .= " from T".$_SESSION['o'].$i;

			//If ($i++ <= count($_POST['id'])){
				$J .= " left join T".$_SESSION['o'].($i+1)." on T".$_SESSION['o'].$i.".loc_id = T".$_SESSION['o'].($i+1).".loc_id AND T"
				.$_SESSION['o'].$i.".sample_date = T".$_SESSION['o'].($i+1).".sample_date AND T".$_SESSION['o'].$i.".m_time = T".$_SESSION['o'].($i+1).".m_time ";
			//}

			$J .= "union select T".$_SESSION['o'].($i+1).".loc_id,T".$_SESSION['o'].($i+1)."
					.sample_date,T".$_SESSION['o'].($i+1).".m_time,T".$_SESSION['o'].$i.".".$_POST['id'][$i];

					$J .= ",T".$_SESSION['o'].($i+1).".".$_POST['id'][($i+1)];

			$J .= " from T".$_SESSION['o'].$i;

			$J .= " right join T".$_SESSION['o'].($i+1)." on T".$_SESSION['o'].$i.".loc_id = T".$_SESSION['o'].($i+1).".loc_id AND T"
					.$_SESSION['o'].$i.".sample_date = T".$_SESSION['o'].($i+1).".sample_date AND T".$_SESSION['o'].$i.".m_time = T".$_SESSION['o'].($i+1).".m_time; ";


			print "</p>ln227 J = ".$J."</p>";



			for($i = 1; $i < count($_POST['id'])-1; $i++) {		
				$rn2 .= "drop table if exists A".$_SESSION['o'].$i.";";
				$J .= "create table A".$_SESSION['o'].$i." select A".$_SESSION['o'].($i-1).".loc_id,A".$_SESSION['o'].($i-1)."
					.sample_date,A".$_SESSION['o'].($i-1).".m_time";				
				
				for ($e = 0; $e <= $i; $e++) {
					$J .= ",A".$_SESSION['o'].($i-1).".`".$_POST['id'][$e]."`";
				}

				//If ($i++ <= count($_POST['id'])){
					$J .= ",T".$_SESSION['o'].($i+1).".`".$_POST['id'][($i+1)]."`";
				//}
				
				$J .= " from A".$_SESSION['o'].($i-1);

				//If ($i++ <= count($_POST['id'])){
					$J .= " left join T".$_SESSION['o'].($i+1)." on A".$_SESSION['o'].($i-1).".loc_id = T".$_SESSION['o'].($i+1).".loc_id AND A"
						.$_SESSION['o'].($i-1).".sample_date = T".$_SESSION['o'].($i+1).".sample_date AND A".$_SESSION['o'].($i-1).".m_time = T".$_SESSION['o'].($i+1).".m_time ";
				//}

				$J .= "union select T".$_SESSION['o'].($i+1).".loc_id,T".$_SESSION['o'].($i+1)."
						.sample_date,T".$_SESSION['o'].($i+1).".m_time";

				for ($s = 0; $s <= $i; $s++) {
					$J .= ",A".$_SESSION['o'].($i-1).".`".$_POST['id'][$s]."`";
				}
		
				$J .= ",T".$_SESSION['o'].($i+1).".`".$_POST['id'][($i+1)]."`";


				$J .= " from A".$_SESSION['o'].($i-1);

				$J .= " right join T".$_SESSION['o'].($i+1)." on A".$_SESSION['o'].($i-1).".loc_id = T".$_SESSION['o'].($i+1).".loc_id AND A"
						.$_SESSION['o'].($i-1).".sample_date = T".$_SESSION['o'].($i+1).".sample_date AND A".$_SESSION['o'].($i-1).".m_time = T".$_SESSION['o'].($i+1).".m_time; ";


				print "</p>ln267 J = ".$J."</p>";

				$lvl = $i;
			}
		}else{

			$J = "create table A".$_SESSION['o'].$i.
			" Select loc_id,sample_date,m_time,sample_v as '".$_POST['id'][$i]
				."' From sample_ele where ".$loc_id." ele_id='".$_POST['id'][$i]."' and sample_date between '".$_POST['frm_date']."' and '".$_POST['to_date']."'; ";			
		}


		print "</p>ln273 J = ".$J."</p>";
		$d=$d.$rn2.$J;
		print "</p>ln291 d = ".$d."</p>";

		if ($dbc->multi_query($d)) {
			do {
				/* store first result set */
				if ($result_dtx = $dbc->store_result()) {
					while ($row = $result_dtx->fetch_row()) {
						printf("%s\n", $row[0]);
					}
					$result_dtx->free();
				}
				/* print divider */
				if ($dbc->more_results()) {
					printf("-----------------\n");
				}
			} while ($dbc->next_result());


			$_SESSION['exp']=$lvl;

			//$result_dn=$dbc->query($rdy);


		}
		else
		{
			//handle error
			echo mysqli_error ( $dbc );
		}

	//=============DL Group Left&Right===============




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

		print '</p>ln156 : '.$q.'</p>';
		
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
				
			
			//$result1 = mysqli_query($dbc,"Create table loc2 (".$q.");");



		$h = "select * from tsms.element where "; 	
		$on_ele_id_unt="(";
		for($i = 0; $i < count($_POST['id']); $i++) {		
			if ($i==count($_POST['id'])-1) {
				$on_ele_id_unt = $on_ele_id_unt."ele_id='".$_POST['id'][$i]."' " ;
			}
			else{
				$on_ele_id_unt = $on_ele_id_unt."ele_id='".$_POST['id'][$i]."' or "; 			
			}
		};
		$on_ele_id_unt = $on_ele_id_unt.") ";
		$h = $h.$on_ele_id_unt."order by ele_id;";
		$result_ele_unt=$dbc->query($h);

		print '<br>20200610 : '.$h;
		
	}
}




//[ASG prototype] ------------------------------------------------------
//$h = "select * from element where gcde = ".$_POST['EG']." order by ele_id";

$h = "select * from loc order by name;";
$result_loc=$dbc->query($h);
$h = "select * from eleg order by gname;";
$result_g=$dbc->query($h);
//$result_tx=$dbc->msql_query($q);
/* if ($result_tx->num_rows){						
$criteria = $criteria.'<span> =>Total no. of records found: '.$result_tx->num_rows;
}else{

$criteria = $criteria.'<span class="text--error"> =>No Record has been found!</span>';					
}					
*///---------------------------------------------------------------------



//print '
//<form action="sampletx.php" method="post">
/* print '
	<table style="width:100%;margin-left:0%;">
		<th style="background-color:#008F00;color:yellow;font-weight:bold;font-size:28px">Monthly Time Series AQ Measurement</th>
		<img src="http://reporter.appledaily.com.hk/reporter/images/poster-new.gif">
  <span style="padding-right:1cm"></span>
  </table>
  <div column=2>'; */
  

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
  print '<span style="color:blue;font-weight:bold">????????????: '.$criteria.'</span>'; 
  
  if ($kb_trans==1){
	  print '<hr><button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">?????????(kg)<br>'.$r_tot->tot_glass_bottle_qty.'</br></button>'
			 .'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????(kg)<br>'.$r_tot->tot_ee_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">?????????????????????(kg)<br>'.$r_tot->tot_comp_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">?????????(kg)<br>'.$r_tot->tot_battery_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????????????????(kg)<br>'.$r_tot->tot_light_qty.'</br></button>'
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????(kg)<br>'.$r_tot->tot_paper_qty.'</br></button>'			
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">????????????(kg)<br>'.$r_tot->tot_plastic_qty.'</br></button>'				
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????(kg)<br>'.$r_tot->tot_metal_qty.'</br></button>'				
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">?????????(kg)<br>'.$r_tot->tot_toner_qty.'</br></button>'		
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;150px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????(kg)<br>'.$r_tot->tot_clothes_qty.'</br></button>'		
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????(kg)<br>'.$r_tot->tot_book_qty.'</br></button>'		
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????(kg)<br>'.$r_tot->tot_toy_qty.'</br></button>'			
				.'<button id="b1" style="border-radius:20px;height: 45px;width: 145px;font-size:14.5px;border:none;background-color:#4CAF50;color:white">??????(kg)<br>'.$r_tot->tot_other_qty.'</br></button>'	;	
	}
}
 
print '<hr>  
  <p></p>  <p></p>  <p></p>
';

 */
//--------------------------------------------------------------------


//Selections
print '<form id="ts" action="sampletx.php" method="post">';  
 $criteria = 'Prototyping';  
 print '</select>';
if (!empty($_POST['frm_date'])){
 	print '<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:1px">From</span>
  <input type="date" name="frm_date" style="size:5"'.' value='.$_POST['frm_date'].'>';
}
else{
	print '<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:1px">From</span>
	<input type="date" name="frm_date" style="size:5">' ;
}
if (!empty($_POST['to_date'])){
  print '<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:1px">To</span>
  <input type="date" name="to_date" style="size:5"'.' value='.$_POST['to_date'].'>';
}
else{
	print '<span style="align:right;color:blue;font-weight:bold;font-size:18px;padding-left:15px">To</span>
	<input type="date" name="to_date">';	  
}


	  

 if ($result_loc->num_rows){ 
	//while ($r_sample=$result_tx->fetch_object()){
	echo '<table style="width:100%;margin-left:0%;">';
	$ecols = $result_loc->field_count;
	$loc_no_l=0;
	$idx=0;
	$f='N';
	print '<tr>';


	while ($r_loc = $result_loc->fetch_array(MYSQLI_BOTH)) {
	//print '<td><input type="checkbox" name="'.$r_ele["ele_id"].'" value="'.$r_ele["ele_id"].'">'.$r_ele["name"].'</td>';

		if (!empty($_POST['loc_id'])){
			//print '<p></p>'.'count($_POST[loc_id]) = '.count($_POST['loc_id']).'<p></p>';
			//print '$_POST[loc_id['.$idx.']] = '.$_POST['loc_id'][$idx].'<p></p>';

			for($i = 0; $i < count($_POST['loc_id']); $i++) {			
				if ($r_loc["loc_id"]==$_POST['loc_id'][$i]){
					print '<td><input type="checkbox" name="loc_id[]" value="'.$r_loc["loc_id"].'" checked="checked">'.$r_loc["name"].'</td>';
					$f='Y';
					break 1;
				}
			}
			
			if ($f=='N'){
				print '<td><input type="checkbox" name="loc_id[]" value="'.$r_loc["loc_id"].'">'.$r_loc["name"].'</td>';
			}
			
			++$loc_no_l;
			//++$idx;
			$f='N';    //reset to default
			if ($loc_no_l == 10) {
				print '</tr><tr>'; 
				$loc_no_l=0;
			};

		}
		else{
			print '<td><input type="checkbox" name="loc_id[]" value="'.$r_loc["loc_id"].'">'.$r_loc["name"].'</td>';
			++$loc_no_l;
			++$idx;
			if ($loc_no_l == 10) {
				print '</tr><tr>'; 
				$loc_no_l=0;
			};
		}



	}								 
	print '</tr><tr><td><input style="font-weight:bold" type="checkbox" name="loc_id[]" value="A">~ALL~</td></tr></table><br><hr></hr>';
 }


 if ($result_g->num_rows){ 
	//while ($r_sample=$result_tx->fetch_object()){
	echo '<table style="width:100%;margin-left:0%;">';
	$ecols = $result_g->field_count;
	$g_no_l=0;

	//<select name='myfield' onchange='this.form.submit()'>
	//print '<tr><div class="custom-select" style="width:150px;"><select onchange="this.form.submit()"><option value="0">Select Group:</option>';
	
	print '<tr><select name="EG" onchange="this.form.submit()"><option value="0">Select Group:</option>';

	while ($r_g = $result_g->fetch_array(MYSQLI_BOTH)) {
	//print '<td><input type="checkbox" name="'.$r_ele["ele_id"].'" value="'.$r_ele["ele_id"].'">'.$r_ele["name"].'</td>';
		//print '<td><input type="checkbox" name="loc_id[]" value="'.$r_loc["loc_id"].'">'.$r_loc["name"].'</td>';
		print '<option value="'.$r_g["gcde"].'">'.$r_g["gname"].'</option>';
		//print '<option value="'.$g_no_l.'">'.$r_g["gname"].'</option>';

		++$g_no_l;
	}								 
	//print '</tr></div>
	print '</tr>
	</select><noscript><input type="submit" value="Submit"></noscript>';

	/*
	<!--surround the select box with a "custom-select" DIV element. Remember to set the width:-->
	print '
	 <div class="custom-select" style="width:200px;">
		<select>
			<option value="0">Select Group:</option>
			<option value="1">Audi</option>
			<option value="2">BMW</option>
			<option value="3">Citroen</option>
			<option value="4">Ford</option>
			<option value="5">Honda</option>
			<option value="6">Jaguar</option>
			<option value="7">Land Rover</option>
			<option value="8">Mercedes</option>
			<option value="9">Mini</option>
			<option value="10">Nissan</option>
			<option value="11">Toyota</option>
			<option value="12">Volvo</option>
		</select>
	</div><br>';
	*/
 }






 if (!empty($_POST['EG'])){
	if ($result_ele->num_rows){ 
	//while ($r_sample=$result_tx->fetch_object()){
	echo '<table style="width:100%;margin-left:0%;">';
	$ecols = $result_ele->field_count;
	$ele_no_l=0;
	print '<tr>';
	while ($r_ele = $result_ele->fetch_array(MYSQLI_BOTH)) {
	//print '<td><input type="checkbox" name="'.$r_ele["ele_id"].'" value="'.$r_ele["ele_id"].'">'.$r_ele["name"].'</td>';
	//print '<td><input type="checkbox" name="id[]" value="'.$r_ele["ele_id"].'">'.$r_ele["name"].'</td>';
	print '<td><input type="checkbox" name="id[]" value="'.$r_ele["ele_id"].'">'.$r_ele["name"]
	      .'<input type="hidden" name="id_unt[]" value="'.$r_ele["ele_id"].'('.$r_ele["unt"].')">'.'</td>';

	++$ele_no_l;
		if ($ele_no_l == 5) {
			print '</tr><tr>'; 
			$ele_no_l=0;
		};
	}								 
	print '</tr><tr><td><input style="font-weight:bold" type="checkbox" name="id[]" value="A">~ALL~</td></tr></table><br>';
 }
}
 print '<input type="submit" value="Submit"></form><br>';





//??????header


/* if ((!empty($_POST['id'])) && (!empty($_POST['loc_id']))){
	echo '<table style="width:100%;margin-left:0%;"><tr style="text-align:center;color:#555555;">';
	print '<th style="background-color:#008F00;color:white">Location</th><th style="background-color:#008F00;color:white">Date</th><th style="background-color:#008F00;color:white">Time</th>';
	//foreach ($_POST['id'] as $key => $value) {
	foreach ($_POST['id'] as $key => $value) {

		//echo $value . "<br />";		
		print '<th style="background-color:#008F00;color:white">'.$value.'</th>';
	}

	print '</tr>';
}; */
if ((!empty($_POST['id'])) && (!empty($_POST['loc_id']))){
	echo '<table style="width:100%;margin-left:0%;"><tr style="text-align:center;color:#555555;">';
	print '<th style="background-color:#008F00;color:white">Location</th><th style="background-color:#008F00;color:white">Date</th><th style="background-color:#008F00;color:white">Time</th>';
	//foreach ($_POST['id'] as $key => $value) {
	while ($r_ele_unt = $result_ele_unt->fetch_array(MYSQLI_BOTH)) {

		//echo $value . "<br />";		
		print '<th style="background-color:#008F00;color:white">'.$r_ele_unt["ele_id"].'('.$r_ele_unt["unt"].')</th>';
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
//	$cols = $result_tx->field_count;
	$loc_id_tx = "";
	$date_tx = "";
	$time_tx = "";
	$j=0;
	while ($r_sample = $result_tx->fetch_array(MYSQLI_BOTH)) {
		$cols = $result_tx->field_count;
		//RowB>
		if (($loc_id_tx<>$r_sample[0]) || ($date_tx<>$r_sample[1]) || ($time_tx <> $r_sample[2])){

			if ($loc_id_tx <> ""){
				//NEXE>
				for ($i=$j; $i<count($_POST['id']); $i++ ){   //Fill in remining buckets before start a new line
					//print '<td style="background-color:#00B386;text-align:center;color:white">---</td>';
					//print '<td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[4].'</td>';
					print '<td style="background-color:#00B386;text-align:center;color:white">YYYYYY</td>';

				}	
				//NEXE<
			};

			print '<tr><td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[0].'</td>';
			print '<td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[1].'</td>';
			print '<td style="background-color:#00B386;text-align:center;color:white">'.$r_sample[2].'</td>';
			$j=0;  //Once the new line form, $j should be reset and scanning of buckets should be started over again
		}
		//RowB<


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
				if ($r_sample[3]==$_POST['id'][$j]) { //location selections vs col headers
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
    <h3 style="display:block;margin-left:1%;padding-right:4px;color:#05CDB9;text-align:left;">&nbsp;????????????:</h3>
    <hr style="border:0.2px solid grey;">
    <form  method="post" action="">
        ??????
        <select name="category">
            <option value="">-----</option>
            <option value="SEQ_ID">????????????</option>
            <option value="DATE">??????</option>
        </select>
        ??????
        <input style="width:15%;"type="text" name="crit">????????????????????????
        <button type="submit" class="btn btn-default">????????????</button>
    </form>
    <br>
</td>
</tr>
</table>';
*/


 //[CGS - ASG prototype remark] ------------------------------------------------------
/* 
	if ($comp=="false"){
			print '<p align="center" class="text--error">?????????????????????????????????!</p>';}

	if ($invq=="true"){
				print '<p align="center" class="text--error">?????????????????????</p>';}

 */ //----------------------------------------------------------------------------------


 //$_POST['id']->free();


print '<div class="form-group">
<button onclick="Export()" class="btn btn-primary">Export to CSV File</button>
</div>
<!--  /Content   -->

<script>
function Export()
{
	var conf = confirm("Export to CSV?");
	if(conf == true)
	{
		window.open("templates/exp.php", "_blank");
	}
}
</script>';



print '
 <script>
 var x, i, j, selElmnt, a, b, c;
 /*look for any elements with the class "custom-select":*/
 x = document.getElementsByClassName("custom-select");
 for (i = 0; i < x.length; i++) {
   selElmnt = x[i].getElementsByTagName("select")[0];
   /*for each element, create a new DIV that will act as the selected item:*/
   a = document.createElement("DIV");
   a.setAttribute("class", "select-selected");
   a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
   x[i].appendChild(a);
   /*for each element, create a new DIV that will contain the option list:*/
   b = document.createElement("DIV");
   b.setAttribute("class", "select-items select-hide");
   for (j = 1; j < selElmnt.length; j++) {
	 /*for each option in the original select element,
	 create a new DIV that will act as an option item:*/
	 c = document.createElement("DIV");
	 c.innerHTML = selElmnt.options[j].innerHTML;
	 c.addEventListener("click", function(e) {
		 /*when an item is clicked, update the original select box,
		 and the selected item:*/
		 var y, i, k, s, h;
		 s = this.parentNode.parentNode.getElementsByTagName("select")[0];
		 h = this.parentNode.previousSibling;
		 for (i = 0; i < s.length; i++) {
		   if (s.options[i].innerHTML == this.innerHTML) {
			 s.selectedIndex = i;
			 h.innerHTML = this.innerHTML;
			 y = this.parentNode.getElementsByClassName("same-as-selected");
			 for (k = 0; k < y.length; k++) {
			   y[k].removeAttribute("class");
			 }
			 this.setAttribute("class", "same-as-selected");
			 break;
		   }
		 }
		 h.click();
	 });
	 b.appendChild(c);
   }
   x[i].appendChild(b);
   a.addEventListener("click", function(e) {
	   /*when the select box is clicked, close any other select boxes,
	   and open/close the current select box:*/
	   e.stopPropagation();
	   closeAllSelect(this);
	   this.nextSibling.classList.toggle("select-hide");
	   this.classList.toggle("select-arrow-active");
	 });
 }
 function closeAllSelect(elmnt) {
   /*a function that will close all select boxes in the document,
   except the current select box:*/
   var x, y, i, arrNo = [];
   x = document.getElementsByClassName("select-items");
   y = document.getElementsByClassName("select-selected");
   for (i = 0; i < y.length; i++) {
	 if (elmnt == y[i]) {
	   arrNo.push(i)
	 } else {
	   y[i].classList.remove("select-arrow-active");
	 }
   }
   for (i = 0; i < x.length; i++) {
	 if (arrNo.indexOf(i)) {
	   x[i].classList.add("select-hide");
	 }
   }
 }
 /*if the user clicks anywhere outside the select box,
 then close all select boxes:*/
 document.addEventListener("click", closeAllSelect);

 function onSelectChange(){
	document.getElementById("ts").submit();
   }


 </script>';
 






include('templates/footer.html'); // Need the footer.


?>