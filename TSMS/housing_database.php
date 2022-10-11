
<!DOCTYPE HTML>
<?php
session_start();
if($_SESSION["AR"]==1){
	header("Location: no_access_right.php");
}
if($_SESSION["AR"] < 1){
	header("Location: no_login.php");
}
?>
<html>
<head>
<title>綠在<?php echo $_SESSION["disname"] ; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- Graph CSS -->
<link href="css/lines.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!----webfonts--->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!---//webfonts--->  
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- report CSS -->
<link href="css/reportcss.css" rel="stylesheet"> 
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<!-- Graph JavaScript -->
<script src="js/d3.v3.js"></script>
<script src="js/rickshaw.js"></script>
<style>
.but1,.but2,.but3{
	text-align:center;
	color:white;
	width:31%;
	padding:10px 20px;
	border-radius:5px;
}

body {
	background-color:#f0edf7;
}
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: relative;
	display:inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #6C648B;
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: white;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {background-color: #FBA100}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3e8e41;
}

td,th{
	width:5%;
	border: 1px solid grey;
	text-align:center;
}
table{
	border-radius:10px;
}
input[type="text"]{
	width:100%;
}
input[type="date"]{
	width:90%;
}
input[type="text"]{
	width:90%;	
}
ul,li{
	list-style-type:none;
}

</style>
</head>
<body>
<div id="wrapper"><!-- webside layout最上面的部分 --->
<nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
  <div class="dropdown" style="float:left;">
    <button style="background-color:#57BC90;"><i class="material-icons" style="padding:15px 15px; height:50px;font-size:22px;line-height:20px;color:#FFF;">reorder</i></button>
     <div class="dropdown-content" style="left:0;">
                          
      <a href="home.php"><i style="font-size:16px;text-align:left;"class="material-icons">home</i>&nbsp;&nbsp;首頁</a>
                      
      <a href="recycledb_link.php"><i class="material-icons" style="font-size:16px;">delete</i>&nbsp;&nbsp;回收物品紀錄<span class="fa arrow"></span></a>
           
      <a href="facility_readonly.php"><i class="material-icons" style="font-size:16px;">lightbulb_outline</i>&nbsp;&nbsp;設施使用量&人次<span class="fa arrow"></span></a>
         
      <a href="activity_userate_link.php"><i class="material-icons" style="font-size:16px;">perm_contact_calendar</i>&nbsp;&nbsp;教育服務&其他活動紀錄<span class="fa arrow"></span></a>
                       
      <a href="participation_readonly.php"><i class="material-icons" style="font-size:16px;">autorenew</i>&nbsp;&nbsp;參與單位總表</a>

		<a href="quarter_report.php"><i class="material-icons" style="font-size:16px;">autorenew</i>&nbsp;&nbsp;季度報表</a>
                      
      <a href="destroy_session.php"><i class="material-icons" style="font-size:16px;">power_settings_new</i>&nbsp;&nbsp;登出<span class="fa arrow"></span></a>
      
    </div>
  </div>
			<ul>
               <li class="navbar-brand" style="margin-top:0.8%;">
               		<b style="float:left;"><?php echo "&nbsp;&nbsp;綠在".$_SESSION["disname"];?></b>
               </li>
               <li class="navbar-brand" style="margin-top:0.8%;">
               		<b style="float:right;font-size:24px;"><?php echo "   ".$_SESSION["myusername"].
					'&nbsp;&nbsp;<img src="https://png.icons8.com/user/ultraviolet/20" title="User" width="24" height="24" >&nbsp;&nbsp;';?></b>
               </li>
            </ul>
</nav><!-- webside最上面的部分完結 --->
<br>
<br>
<br>
    <form><!-- 日期選擇欄位 --->
        <b style="color:#697F89;margin-left:1%;">日期範圍選擇:  </b>
        <select name="year" id="year">
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
        </select>
        <script type="text/javascript">
			document.getElementById("year").value = "<?php if($_GET){echo $_GET["year"]; } ?>";
		</script>
  		<b style="color:#697F89;">年</b>
        <select name="month" id="month">
            <option value="01">01</option>
            <option value="02">02</option>
            <option value="03">03</option>
            <option value="04">04</option>
            <option value="05">05</option>
            <option value="06">06</option>
            <option value="07">07</option>
            <option value="08">08</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select>
        <script type="text/javascript">
			document.getElementById("month").value = "<?php if($_GET){echo $_GET["month"]; } ?>";
		</script>
      	<b style="color:#697F89;">月</b>
        <?php
		if($_SESSION["AR"]==3){
			echo'<b style="color:#697F89;">地區:</b>
			<select name="district" id="district">
	   				 <option value="cewecgs">中西區</option>
        			 <option value="wachcgs">灣仔區</option>
        			 <option value="eastcgs">東區</option>
        			 <option value="soutcgs">南區</option>
        			 <option value="yatmcgs">油尖旺區</option>
        	 		 <option value="shspcgs">深水埗區</option>
        			 <option value="kocicgs">九龍城</option>
       				 <option value="wotscgs">黃大仙</option>
        			 <option value="kwtocgs">觀塘</option>
        			 <option value="kwtscgs">葵青</option>
        			 <option value="tswacgs">荃灣</option>
        			 <option value="tumucgs">屯門</option>
					 <option value="yulocgs">元朗</option>
        			 <option value="nortcgs">北區</option>
        			 <option value="tapocgs">大埔</option>
        			 <option value="shticgs">沙田</option>
        			 <option value="sakucgs">西貢</option>
        			 <option value="islacgs">離島</option>	
			</select>';
			if($_GET){
				$getdistrict = "";
				$getdistrict =  $_GET["district"]; 
					 
					
			echo '<script type="text/javascript">';
			echo 'document.getElementById("district").value ="'.$getdistrict.'";' ;
			echo "</script>";
			}
		}
		?>
        <button type="submit" class="btn btn-default">選擇</button><!--- 日期選擇欄位完結 --->
    </form>
<br>
<table style="background-color:white;border-radius:8px;border:none;width:98%;margin-left:1%;">
<tr>
<td  style="border:none;">
        	<?php
			include("housing_report.php");
			?>
		<form method="post" action="condb_housing.php?month=<?php if($_GET){ echo $_GET["month"].'&year='.$_GET['year'].'&district='.$_GET["district"]; } ?>">
        <td colspan="1">請輸入</td>
        <td style="width:4.5%;"><input type="date" name="date"></td>
        <td colspan="2"><input type= "text" name= "code" placeholder="只需輸入編號:"></td>
        <td><input type= "text" name= "glass"></td>
        <td><input type= "text" name= "electronics"></td>
        <td><input type= "text" name= "computer"></td>
        <td><input type= "text" name= "battery"></td>
        <td><input type= "text" name= "lightbulb"></td>
        <td><input type= "text" name= "paper"></td>
        <td><input type= "text" name= "plastic"></td>
        <td><input type= "text" name= "metal"></td>
        <td><input type= "text" name= "toner"></td>
        <td><input type= "text" name= "clothes"></td>
        <td><input type= "text" name= "books"></td>
        <td><input type= "text" name= "toys"></td>
        <td><input type= "text" name= "other"></td>
        <td><input type= "text" name= "note"></td>
	</tr>
	<tr>
        <td colspan="18" style="border:none; ">
        <?php
            if(isset($_GET['insertsuccess'])==true){
                echo '<h3 style="color:green;"><i class="material-icons">check_circle</i> 新增紀錄成功!!!</h3><br>';
            }
            if(isset($_GET['deletesuccess'])==true){
                echo '<h3 style="color:green;"><i class="material-icons">check_circle</i> 刪除紀錄成功!!!</h3><br>';
            }
            if(isset($_GET['updatesuccess'])==true){
                echo '<h3 style="color:green;"><i class="material-icons">check_circle</i> 修改紀錄成功!!!</h3><br>';
            }
        
            if(isset($_GET['negative'])==true){
                echo '<h3 style="color:red;"><i class="material-icons">warning</i> 新增紀錄失敗!!!不能輸入負數!!!</h3><br>';
            }
            if(isset($_GET['incorrcode'])==true){
                echo '<h3 style="color:red;"><i class="material-icons">warning</i> 新增紀錄失敗!!!你輸入的編號不正確!!!</h3><br>';
            }
            if(isset($_GET['empty'])==true){
                echo '<h3 style="color:red;"><i class="material-icons">warning</i> 新增紀錄失敗!!!請輸入資料!!!</h3><br>';
            }
            if(isset($_GET['seqemp'])==true){
                echo '<h3 style="color:red;"><i class="material-icons">warning</i> 修改紀錄失敗!!!請輸入紀錄序號以修改資料!!!</h3><br>';
            }
            if(isset($_GET['delerr'])==true){
                echo '<h3 style="color:red;"><i class="material-icons">warning</i> 刪除紀錄失敗!!!你要刪除的紀錄不存在!!!</h3><br>';
            }
            if(isset($_GET['empnote'])==true){
                echo '<h3 style="color:red;"><i class="material-icons">warning</i> 新增紀錄失敗!!!如其他欄位不是空值需註明!!!</h3><br>';
            }
        ?>
        <br>
        
        <button style="float:right;" type="submit" class="btn btn-default">新增紀錄</button>
  		 </form>
         
        <a href="participation_readonly.php" target="_blank"><button style="float:left;" class="btn btn-default">查看屋苑/機構編號</button></a>
        
        </td>
	</tr>
	</table>
<br>
</td>
</tr>
</table>
<br>
<br>
<br>
<table style="background-color:white;border-radius:8px;border:none;width:98%;margin-left:1%;">
<tr>
<td  style="border:none;text-align:center;">
    <br>
    <h3 style="display:block;margin-left:1%;padding-right:4px;color:#05CDB9;text-align:left;">&nbsp;刪除紀錄:</h3>
    <hr style="border:1px solid grey;">
    <form  method="post" action="condb_delete_housing.php?month=<?php if($_GET){ echo $_GET["month"].'&year='.$_GET['year'].'&district='.$_GET["district"]; } ?>">
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
</table>
<br>
<br>
<br>
<table style="background-color:white;border-radius:8px;border:none;width:98%;margin-left:1%;">
<tr>
<td  style="border:none;text-align:start;">
    <br>
    <h3 style="display:block;margin-left:1%;padding-right:4px;color:#05CDB9;">&nbsp;修改紀錄:</h3>
    <hr style="border:1px solid grey;">
	<table border="0" style="width:98%;margin-left:1%;">
    <tr style="color:#555555;">
        <td class="nonnumber">紀錄序號</td>
        <td class="nonnumber">日期</td>
        <td class="nonnumber">編號</td>
        <td class="pr">玻璃樽</td>
        <td  class="pr">電器</td>
        <td  class="pr">電腦及相關用品</td>
        <td  class="pr">充電池</td>
        <td  class="pr">光管及慳電膽</td>
        <td  class="threecolourbin">廢紙</td>
        <td class="threecolourbin">塑膠廢料</td>
    </tr>
	<tr>
        <form method="post" action="condb_update_housing.php?month=<?php if($_GET){ echo $_GET["month"].'&year='.$_GET['year'].'&district='.$_GET["district"];} ?>">
        <td><input type="number" name="SEQ_ID2" placeholder="需輸入此欄以修改資料"></td>
        <td ><input type="date" name="date2"></td>
        <td><input type="text" name="code2"></td>
        <td><input type= "text" name= "glass2"></td>
        <td><input type= "text" name= "electronics2"></td>
        <td><input type= "text" name= "computer2"></td>
        <td><input type= "text" name= "battery2"></td>
        <td><input type= "text" name= "lightbulb2"></td>
        <td><input type= "text" name= "paper2"></td>
        <td><input type= "text" name= "plastic2"></td>
	</tr>
	<tr>
		<td colspan="10" style="border:none;">
		</td>
		</tr>
	<tr style="color:#555555;">
        <td class="threecolourbin">金屬</td>
        <td class="others">碳粉盒</td>
        <td class="others">衣物</td>
        <td class="others">書本</td>
        <td class="others">玩具</td>
        <td class="others">其他</td>
        <td class="others" colspan="2">註明</td>
	</tr>
	<tr>
        <td><input type= "text" name= "metal2"></td>
        <td><input type= "text" name= "toner2"></td>
        <td><input type= "text" name= "clothes2"></td>
        <td><input type= "text" name= "books2"></td>
        <td><input type= "text" name= "toys2"></td>
        <td><input type= "text" name= "other2"></td>
        <td colspan="2"><input type= "text" name= "note2"></td>
        <td colspan="2" style="border:none;">
        <button style="float:right;"type="submit" class="btn btn-default">修改資料</button>
        </td>
        </form>	
	</tr>
	</table>
<br>
</td>
</tr>
</table>
<br>
<br>
<br>
</body>
</html>