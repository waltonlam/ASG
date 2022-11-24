
<!--?php  
	include ('iconn.php');
	include 'header2.php';

	print '<h2>All Compound Categories</h2>';
		//if (isset($_GET['click']) && $_GET['click'] == 'media') {
			
			//getMediaData();
		 //} else if(isset($_GET['click']) && $_GET['click'] == 'location'){
			 getCategoryData();
		 //}
		
		function getCategoryData(){
			
			if (!$dbc = new mysqli('localhost', 'root', '', 'taps'))
			{
				print '<p style="color:red;">Could not connect to the database:<br>'.mysqli_connect_error().'.</p>';
				exit();
			}

			$l = "select * from category order by id ASC;";
			//print $q;
			$result_loc=$dbc->query($l);  

			if (!$result_loc->num_rows){
				print '<p class="text--error">'.'Compound Configuration Error!</p>';
				exit();
			}		

			
			echo '<input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search..">';
			
			echo '<br>';
			echo '<table id="mediaTb" class="table" cellspacing="0" width="100%">
					<tr>
						<th>ID</th>
						<th>Item</th>
					</tr>';
					
			
			 while ($row =$result_loc->fetch_assoc()){
				 
				 echo "<tr>";
					echo "<td>" . $row["id"]."</td>";
					echo "<td>" . $row["item"]."</td>";
					echo "</tr>";
				 
			 }	
			echo '</table>';
		}
		include 'footer.html';
?>
		
<script>
	function search(){
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("myInput");
		filter = input.value.toUpperCase();
		table = document.getElementById("mediaTb");
		tr = table.getElementsByTagName("tr");
		for (i = 0; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[1];
			if (td) {
				txtValue = td.textContent || td.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
				} else {
				tr[i].style.display = "none";
				}
			}       
		}
	}

	function delClick(){
		alert("delete click");
	}
</script-->

<html>   
  	<head>   
		<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->   
		<style>   
			/*table {  
				border-collapse: collapse;  
			} */ 
			.inline{   
				display: inline-block;   
				float: right;   
				margin: 20px 0px;   
			}   
			
			/*input, button{   
				height: 34px;   
			}*/
		
			.pagination {   
				display: inline-block;   
			}   
			.pagination a {   
				font-weight:bold;   
				font-size:10px;   
				color: black;   
				float: left;   
				padding: 8px 16px;   
				text-decoration: none;   
				border:1px solid black;   
			}   
			.pagination a.active {   
					background-color: #E7E3E2;   
			}   
			.pagination a:hover:not(.active) {   
				background-color: #848282;   
			}   
		</style>   
  	</head>   

	<body>   
 	 	<!--center-->  
		<?php  
			// Import the file where we defined the connection to Database.     
			require_once "connection.php";   
			require_once 'iconn.php';
			require_once 'header2.php';

			if (isset($_SESSION['previous'])) {
				if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
					 //session_destroy();
					 unset($_SESSION['site_id']);
					 unset($_SESSION['dateFrom']);
					 unset($_SESSION['dateTo']);
					 ### or alternatively, you can use this for specific variables:
					 ### unset($_SESSION['varname']);
				}
			}
		
			if (isset($_SESSION['prev_incid'])) {
				if (basename($_SERVER['PHP_SELF']) != $_SESSION['prev_incid']) {
					 //session_destroy();
					 unset($_SESSION['site_code_inc']);
					 unset($_SESSION['dateFrom_inc']);
					 unset($_SESSION['dateTo_inc']);
					 ### or alternatively, you can use this for specific variables:
					 ### unset($_SESSION['varname']);
				}
			}
			
			$per_page_record = 10;  // Number of entries to show in a page.   
			// Look for a GET variable page if not found default is 1.        
			if (isset($_GET["page"])) {    
				$page  = $_GET["page"];    
			} else {    
				$page=1;    
			}    
		
			$start_from = ($page-1) * $per_page_record;     

			$query = "SELECT * FROM category LIMIT $start_from, $per_page_record";     
			$rs_result = mysqli_query ($conn, $query);    
		?>    
	
		<div class="container">   
			<h2>All Compound Categories</h2>  
			<hr>
			<br>
			<div>
				<input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search..">
				<table id="mediaTb" class="table table-striped table-condensed table-bordered">   
					<thead>   
						<tr>   
							<th width="10%">ID</th>   
							<th>Item</th>   
						</tr>   
					</thead>   
					<tbody>   
						<?php     
							while ($row = mysqli_fetch_array($rs_result)) {    
								// Display each field of the records.    
						?>     
							<tr>     
								<td><?php echo $row["id"]; ?></td>     
								<td><?php echo $row["item"]; ?></td>                                           
							</tr>     
						<?php     
							};    
						?>     
					</tbody>   
				</table>   
				<br><hr>
				<div class="pagination">    
					<?php  
						$query = "SELECT COUNT(*) FROM category";     
						$rs_result = mysqli_query($conn, $query);     
						$row = mysqli_fetch_row($rs_result);     
						$total_records = $row[0];     
						
						echo "</br>";     
						// Number of pages required.   
						$total_pages = ceil($total_records / $per_page_record);     
						$pagLink = "";       
					
						if($page>=2){   
							echo "<a href='showCategory.php?page=".($page-1)."'>  Prev </a>";   
						}       
								
						for ($i=1; $i<=$total_pages; $i++) {   
						if ($i == $page) {   
							$pagLink .= "<a class = 'active' href='showCategory.php?page="  
																.$i."'>".$i." </a>";   
						}               
						else  {   
							$pagLink .= "<a href='showCategory.php?page=".$i."'>   
																".$i." </a>";     
						}   
						};     
						echo $pagLink;   
				
						if($page<$total_pages){   
							echo "<a href='showCategory.php?page=".($page+1)."'>  Next </a>";   
						}
					?>    
				</div> 
				<div class="inline">   
					<input id="page" type="number" min="1" max="<?php echo $total_pages?>"   
					placeholder="<?php echo $page."/".$total_pages; ?>" required>   
					<button onClick="go2Page();">Go</button>   
				</div>    
			</div>   
		</div>  
		<!--/center-->   
		<script>  
			function search(){
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				table = document.getElementById("mediaTb");
				tr = table.getElementsByTagName("tr");
				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[1];
					if (td) {
						txtValue = td.textContent || td.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						} else {
						tr[i].style.display = "none";
						}
					}       
				}
			}

			function delClick(){
				alert("delete click");
			}

			function go2Page(){   
				var page = document.getElementById("page").value;   
				page = ((page><?php echo $total_pages; ?>)?<?php echo $total_pages; ?>:((page<1)?1:page));   
				window.location.href = 'showCategory.php?page='+page;   
			}   
		</script>  
	</body>   
</html>  

