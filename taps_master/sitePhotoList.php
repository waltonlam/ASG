<html>
	<head>
		<link href="assets/style.css" rel="stylesheet" type="text/css" />
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
		<?php
			//namespace Phppot;

			//use Phppot\DataSource;
			require_once "connection.php";  
			require_once "iconn.php";
			require_once "header2.php";
			//require_once __DIR__ . '/lib/ImageModel.php';
			//$imageModel = new ImageModel();

			$per_page_record = 10;  // Number of entries to show in a page.   
			// Look for a GET variable page if not found default is 1.        
			if (isset($_GET["page"])) {    
				$page  = $_GET["page"];    
			} else {    
				$page=1;    
			}    

			$start_from = ($page-1) * $per_page_record;   

			//$result = $imageModel->getAllImages($start_from, $per_page_record);
			$query = "SELECT * FROM site_photo LIMIT $start_from, $per_page_record";     
			$rs_result = mysqli_query ($conn, $query);   
		?>

		<div class="container">
			<br> 
			<h2>All Site Photos</h2>
			<div>
				<input type="text" id="myInput" onkeyup="search()" placeholder="Search for id.." title="Type in a name" placeholder="Search..">
				<table id="mediaTb" class="table table-striped table-condensed table-bordered"> 
					<thead>  
						<tr>
							<th width="70%">Photo</th>
							<th width="30%">Action</th>
						</tr>
					</thead> 
					<tbody>   
					<?php     
						while ($row = mysqli_fetch_array($rs_result)) {    
							// Display each field of the records.    
					?>     
						<tr>     
							<td>
								<img src="<?php echo $row["image"]?>" class="img-preview" alt="photo"><?php echo $row["name"]?>
							</td>
							<td>
								<a href="updateSitePhoto.php?id=<?php echo $row['id']; ?>" class="btn-action">Edit</a> 
								<a onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn-action">Delete</a>
								<a href="<?php echo $row['image']; ?>" class="btn-action" target="_blank">Download</a> 
							</td>                                        
						</tr>     
					<?php     
						};    
					?>     
					</tbody>
				</table>
				<!--a href="insert.php" class="btn-link">Add Image</a-->

				<div class="pagination">    
					<?php  
						$query = "SELECT COUNT(*) FROM site_photo";     
						$rs_result = mysqli_query($conn, $query);     
						$row = mysqli_fetch_row($rs_result);     
						$total_records = $row[0];     
						
						echo "</br>";     
						// Number of pages required.   
						$total_pages = ceil($total_records / $per_page_record);     
						$pagLink = "";       
					
						if($page>=2){   
							echo "<a href='sitePhotoList.php?page=".($page-1)."'>  Prev </a>";   
						}       
								
						for ($i=1; $i<=$total_pages; $i++) {   
						if ($i == $page) {   
							$pagLink .= "<a class = 'active' href='sitePhotoList.php?page="  
																.$i."'>".$i." </a>";   
						}               
						else  {   
							$pagLink .= "<a href='sitePhotoList.php?page=".$i."'>   
																".$i." </a>";     
						}   
						};     
						echo $pagLink;   
				
						if($page<$total_pages){   
							echo "<a href='sitePhotoList.php?page=".($page+1)."'>  Next </a>";   
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
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="	crossorigin="anonymous"></script>
		<script type="text/javascript" src="assets/validate.js"></script>

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
				window.location.href = 'sitePhotoList.php?page='+page;   
			}  
		</script>  
	</body>
</html>