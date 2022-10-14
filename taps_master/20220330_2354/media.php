
		
<?php
	
	include 'header2.html';
	
		
		getMediaData();
		

		function getMediaData(){
			
			// URL API
			$url = 'http://localhost/api/media/read.php';
			$client = curl_init();
			$options = array(
			CURLOPT_URL				=> $url, // Set URL of API
			CURLOPT_CUSTOMREQUEST 	=> "GET", // Set request method
			CURLOPT_RETURNTRANSFER	=> true, // true, to return the transfer as a string
			);
			curl_setopt_array( $client, $options );

			// Execute and Get the response
			$response = curl_exec($client);
			// Get HTTP Code response
			$httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
			// Close cURL session
			curl_close($client);

			$result=null;
			if($httpCode=="200"){ // if success
				$result=json_decode($response);
				
				echo '<a href="addMedia.php" id="btn-add">Add</a>';
				
				echo '<table class="table" cellspacing="0" width="100%">
					<tr>
						<th>ID.</th>
						<th>Name</th>
						<th>Action</th>
					</tr>';
				
				
				if($result!=null){
				
				
				foreach($result as $rs){
					echo "<tr>";
					echo "<td>".$rs->id."</td>";
					echo "<td>".$rs->name."</td>";
					echo "<td>";
					echo "<a class='btn btn-edit btn-sm' onclick='editClick()'>EDIT</a> ";
					echo "<a class='btn btn-delete btn-sm' onclick='delClick()'>Delete</a> ";
					echo "</td>";
					echo "</tr>";
				}
			}
				
				echo '</table>';
				
				
				
			}else{ // if failed
				$response=json_decode($response);
				echo "<div class='alert alert-danger' style='width:300px;'>Terjadi Kesalahan<br/>".$response->error."</div>";
			}
			
		}
		
		?>
		
		<script>
		
		
			function editClick(){
				alert("edit click");
			}
		
			function delClick(){
				alert("delete click");
			}
		
			document.getElementsByClassName("container")[0].getElementsByTagName('h1')[0].innerHTML = 'Media';
		
		</script>
		
		
	<?php
		include 'footer.html';

	?>	
