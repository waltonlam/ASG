<?php
date_default_timezone_set('Asia/Hong_Kong');

print '<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>TAPS</title>
			<link rel="stylesheet" type="text/css" media="screen" href="css/style_cms.css" /> 
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			<link href="css/tapsCms.css" type="text/css" rel="stylesheet"/> 

			<style>
					input:focus {
					  background-color: yellow;
					}
					input:read-only {
						background-color: rgb(202, 199, 199);
						border-top-style: none;
						border-right-style: none;
						border-left-style: none;
						border-bottom-style: none;
					}
					
			</style>

		</head>'; 

		error_reporting(0);
		if(!isset($_SESSION)){
			session_start();
		}

		if (empty($_SESSION['vuserid'])) {
			//print 'session user: '.$_SESSION['vuserid'];
			print '<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p><br><br><a href="login.php">Go Login</a>';		
			exit();
		} else{
			print '<h1><a href="exportReport.php"> TAPS </a></h1>';	
			print '<p align="right">Signed in as ';	
			//print $_SESSION['vuserid'].', Last login time: '.date('Y-m-d H:i:s');
			print $_SESSION['vuserid'].', Last login time: '.$_SESSION['lastLoginTime'];
			print '</p>';

			if ($_SESSION['utp']==0){
				print '
				<!--div id="menu">
					<ul id="nav">						
						<li><a href="#" target="_self" >System Maintanance</a>
							<ul>
								<li><a href="#" target="_self" >Maintain Site</a>
									<ul>
										<li><a href="showSite.php" target="_self" >Show All</a>
										<li><a href="updateSite.php" target="_self" >Update Site</a>
										<li><a href="delSite.php" target="_self" >Delete Site</a>
										<li><a href="addSite.php" target="_self" >Add New Site</a>
									</ul>
								</li>
								<li><a href="#" target="_self" >Maintain Compound</a>
									<ul>
										<li><a href="showCompound.php" target="_self" >Show All</a>
										<li><a href="updateCompound.php" target="_self" >Update Compound</a>
										<li><a href="delCompound.php" target="_self" >Delete Compound</a>
										<li><a href="addCompound.php" target="_self" >Add Compound</a>
									</ul>
								</li>
								<li><a href="#" target="_self" >Maintain Category</a>
									<ul>
										<li><a href="showCategory.php">Show All</a>
										<li><a href="updateCategory.php" target="_self" >Update Category</a>
										<li><a href="delCategory.php" target="_self" >Delete Category</a>
										<li><a href="addCategory.php" target="_self" >Add Category</a>
									</ul>
								</li>							
							</ul>
						</li>
						<li><a href="#" target="_self" >Upload CSV Template</a>
							<ul>
								<li><a href="#" target="_self" >Glab Template</a>
									<ul>
										<li><a href="showGlabTemp.php">Show All</a>
										<li><a href="impGlab.php">Import Glab</a>
										<li><a href="updateGlabTemp.php">Update Glab</a> 
										<li><a href="delGlabTemp.php">Delete all record</a>
										<li><a href="delSingleGlabTemp.php">Delete single record</a>
										<li><a href="exportReport.php">Export Report</a>
									</ul>
								</li>
								<li><a href="#" target="_self" >Contractor Template</a>
									<ul>
										<li><a href="showContractor.php">Show All</a>
										<li><a href="impContractor.php">Import Template</a>
										<<li><a href="updateContractorTemp.php">Update Template</a>
										<li><a href="delContractor.php">Delete all record</a>
										<li><a href="delSingleContractor.php">Delete single record</a>
									</ul>
								</li>							
							</ul>		
						</li>
					</ul>
				</div-->

				<div class="navbar">
					<div class="dropdown">
						<button class="dropbtn" onclick="myFunction()">Site
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown">														
							<a href="showSite.php">Show All</a>
							<a href="updateSite.php">Update Site</a>
							<a href="delSite.php">Delete Site</a>
							<a href="addSite.php">Add New Site</a>	
						</div>
					</div> 
				  
					<div class="dropdown">
				  		<button class="dropbtn" onclick="myFunction1()">Compound
							<i class="fa fa-caret-down"></i>
				  		</button>
						<div class="dropdown-content" id="myDropdown1">
							<a href="showCompound.php">Show All</a>
							<a href="updateCompound.php">Update Compound</a>
							<a href="delCompound.php">Delete Compound</a>
							<a href="addCompound.php">Add Compound</a>
						</div>
					</div> 				 
				  
				  	<div class="dropdown">
				  		<button class="dropbtn" onclick="myFunction5()">Category
							<i class="fa fa-caret-down"></i>
						</button>
				 		 <div class="dropdown-content" id="myDropdown5">
							<a href="showCategory.php">Show All</a>
							<a href="updateCategory.php">Update Category</a>
							<a href="delCategory.php">Delete Category</a>
							<a href="addCategory.php">Add Category</a>
				 		 </div>
					</div> 		  

					<div class="dropdown">
						<button class="dropbtn" onclick="myFunction4()">Glab Template
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown4">
							<a href="showGlabTemp.php">Show All</a>
							<a href="impGlab.php">Import Glab</a>
							<!--<a href="updateGlabTemp.php">Update Glab</a> 
							<a href="delGlabTemp.php">Delete all record</a>
							<a href="delSingleGlabTemp.php">Delete single record</a> -->
							<a href="exportReport.php">Export Report</a>
						</div>
				  	</div> 
				  
					<div class="dropdown">
						<button class="dropbtn" onclick="myFunction6()">Contractor Template
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown6">
							<a href="showContractor.php">Show All</a>
							<a href="impContractor.php">Import Template</a>
							<!--<a href="updateContractorTemp.php">Update Template</a>
							<a href="delContractor.php">Delete all record</a>
							<a href="delSingleContractor.php">Delete single record</a>-->
						</div>
					</div> 
				  
					<div class="dropdown">
						<button class="dropbtn" onclick="myFunction7()">Incident Report
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown7">
							<a href="sitePhotoList.php">Show All</a>
							<a href="addSitePhoto.php">Add Incident Report</a>
						</div>
					</div> 

				  	<div class="dropdown">
						<button class="dropbtn" onclick="myFunction8()">Graph Generation
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown8">
							<a href="genGraph.php">Generate Graph of Pollutants</a>
							<a href="genSummaryPlot.php"> Generate Summary Plot</a>
						</div>
				  	</div> 

					<div class="dropdown">
						<button class="dropbtn" onclick="myFunction9()">File Conversion
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown9">
							<a href="getMSWordContent.php">Convert MS Word to Text</a>
							<a href="upphconv.php">PH</a>
							<a href="updixdfconv.php">DF</a>
							<a href="updixdlconv.php">DL-PB</a>
							<a href="uppbconv.php">mPB</a>	
						</div>
				  	</div> 

					<div class="dropdown">
						<button class="dropbtn" onclick="myFunction2()">Account
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown2">
							<a href="showAccount.php">Show All</a>
							<a href="updateAccount.php">Update Account</a>
							<a href="delAccount.php">Delete Account</a>
							<a href="addAccount.php">Add Account</a>
						</div>
					</div> 	

				   <a onclick="logout()">Logout</a>		  
				</div>';		
			}else{
				print '
				<div class="navbar">
					<div class="dropdown">
						<button class="dropbtn" onclick="myFunction4()">Glab Template
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content" id="myDropdown4">
							<a href="exportReport.php">Export Report</a>
						</div>
					</div> 			  
			  	 	<a onclick="logout()">Logout</a>			  
				</div>';
			}
		}

print '<script>
	function goIndexPage() {
	  location.replace("index.php")
	}

	function myFunction() {
	  document.getElementById("myDropdown").classList.toggle("show");
	}

	function myFunction1() {
	  document.getElementById("myDropdown1").classList.toggle("show");
	}

	function myFunction2() {
	  document.getElementById("myDropdown2").classList.toggle("show");
	}

	function myFunction3() {
	  document.getElementById("myDropdown3").classList.toggle("show");
	}
	
	function myFunction4() {
	  document.getElementById("myDropdown4").classList.toggle("show");
	}
	
	function myFunction5() {
	  document.getElementById("myDropdown5").classList.toggle("show");
	}

	function myFunction6() {
	  document.getElementById("myDropdown6").classList.toggle("show");
	}

	function myFunction7() {
		document.getElementById("myDropdown7").classList.toggle("show");
	}

	function myFunction8() {
		document.getElementById("myDropdown8").classList.toggle("show");
	}

	function myFunction9() {
		document.getElementById("myDropdown9").classList.toggle("show");
	}
	
	function myFunction11() {
		document.getElementById("myDropdown11").classList.toggle("show");
	}

	function logout() {
	
	  location.replace("logout.php")
	}

	// Close the dropdown if the user clicks outside of it
	window.onclick = function(e) {
	  if (!e.target.matches(".dropbtn")) {
	  var myDropdown = document.getElementById("myDropdown");
		if (myDropdown.classList.contains("show")) {
		  myDropdown.classList.remove("show");
		}
	  }
	}
</script>';
?>