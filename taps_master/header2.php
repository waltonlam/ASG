<?php
	date_default_timezone_set('Asia/Hong_Kong');
?>


<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>TAPS</title>
      <link href="css/bootstrap.css" rel="stylesheet">
      <link href="css/jquery.smartmenus.bootstrap.css" rel="stylesheet">
      <link id="switcher" href="css/theme-color/default-theme.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
   </head>

   <?php
		error_reporting(0);
		if(!isset($_SESSION)){
			session_start();
		}

		if (empty($_SESSION['vuserid'])) {
	?>
			<p class="text--error">Please make sure you login to the system before using any functions<br>Go back and try again.</p><br><br><a href="login.php">Go Login</a>	
	<?php		
		exit();
		} else{
	?>
		<h1 style="margin-left:10px"><a href="exportReport.php"> TAPS </a></h1>
		<p style="margin-right:10px" align="right">Signed in as <?php echo $_SESSION['vuserid'].', Last login time: '.$_SESSION['lastLoginTime'] ?> </p>

	<?php		
		} 
	?>	
   <body>
		<?php
			if ($_SESSION['utp']==0){
		?>
		<section id="menu">
			<div class="container">
				<div class="menu-area">
					<!-- Navbar -->
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							</button>          
						</div>
						<div class="navbar-collapse collapse">
							<!-- Left nav -->
							<ul class="nav navbar-nav">
								<li>
								<a href="#">System Maintanance <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Maintain Site <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showSite.php">Show All</a></li>
											<li><a href="updateSite.php">Update Site</a></li>
											<li><a href="delSite.php">Delete Site</a></li>
											<li><a href="addSite.php">Add Site</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Maintain Compound <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showCompound.php">Show All</a></li>
											<li><a href="updateCompound.php">Update Compound</a></li>
											<li><a href="delCompound.php">Delete Compound</a></li>
											<li><a href="addCompound.php">Add Compound</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Maintain Category <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showCategory.php">Show All</a></li>
											<li><a href="updateCategory.php">Update Category</a></li>
											<li><a href="delCategory.php">Delete Category</a></li>
											<li><a href="addCategory.php">Add Category</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Maintain Factor <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showFactor.php">Show All</a></li>
											<li><a href="updateFactor.php">Update Factor</a></li>
											<li><a href="delFactor.php">Delete Factor</a></li>
											<li><a href="addFactor.php">Add Factor</a></li>
										</ul>
									</li>
									<li>
										<a href="showQCriteria.php">Maintain QC Criteria</a>
									</li>
									<li>
										<a href="#">Maintain User Account <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showAccount.php">Show All</a></li>
											<li><a href="updateAccount.php">Update User Account</a></li>
											<li><a href="delAccount.php">Delete User Account</a></li>
											<li><a href="addAccount.php">Add User Account</a></li>
										</ul>
									</li>  
								</ul>
								</li>
								<li>
								<a href="#">CSV Template <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Glab Template <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showGlabSample.php">Show All</a></li>
											<li><a href="dlGlabTemplate.php">Download Glab Template</a></li>
											<li><a href="impGlabCsv.php">Import VOC Csv</a></li>
											<li><a href="impPahCsv.php">Import PAH Csv</a></li>
											<li><a href="exportReport.php">Export Report</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Contractor Template <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showContractor.php">Show All</a></li>
											<!--li><a href="impContractor.php">Import Template</a></li>
											<li><a href="updateContractorTemp.php">Update Template</a></li>
											<li><a href="delContractor.php">Delete all record</a></li>
											<li><a href="delSingleContractor.php">Delete single record</a></li-->
										</ul>
									</li>
									</ul>
								</li>
								<li>
								<a href="#">Incident Report <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="incidentReportList.php">Show All</a></li>
									<li><a href="addIncidentReport.php">Add Incident Report</a></li>
									<li><a href="exportToExc.php">Export Incident Report</a></li>
								</ul>
								</li>
								<li>
								<a href="#">Statistical Graph<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="genSummaryPlot.php">Summary Plot</a></li>
									<li><a href="genSummaryPlot.php">Scatter Plot</a></li>
								</ul>
								</li>
								<li><a href="logout.php">Logout</a></li>
								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
			}else{
		?>
			<section id="menu">
			<div class="container">
				<div class="menu-area">
					<!-- Navbar -->
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							</button>          
						</div>
						<div class="navbar-collapse collapse">
							<!-- Left nav -->
							<ul class="nav navbar-nav">
								<li>
								<a href="#">System Maintanance <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Maintain Site <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showSite.php">Show All</a></li>
											<!--li><a href="updateSite.php">Update Site</a></li>
											<li><a href="delSite.php">Delete Site</a></li>
											<li><a href="addSite.php">Add Site</a></li-->
										</ul>
									</li>
									<li>
										<a href="#">Maintain Compound <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showCompound.php">Show All</a></li>
											<!--li><a href="updateCompound.php">Update Compound</a></li>
											<li><a href="delCompound.php">Delete Compound</a></li>
											<li><a href="addCompound.php">Add Compound</a></li-->
										</ul>
									</li>
									<li>
										<a href="#">Maintain Category <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showCategory.php">Show All</a></li>
											<!--li><a href="updateCategory.php">Update Category</a></li>
											<li><a href="delCategory.php">Delete Category</a></li>
											<li><a href="addCategory.php">Add Category</a></li-->
										</ul>
									</li>
									<li>
										<a href="#">Maintain Factor <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showFactor.php">Show All</a></li>
											<!--li><a href="updateFactor.php">Update Factor</a></li>
											<li><a href="delFactor.php">Delete Factor</a></li>
											<li><a href="addFactor.php">Add Factor</a></li-->
										</ul>
									</li>
									<li>
										<a href="#">Maintain User Account <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showAccount.php">Show All</a></li>
											<!--li><a href="updateAccount.php">Update User Account</a></li>
											<li><a href="delAccount.php">Delete User Account</a></li>
											<li><a href="addAccount.php">Add User Account</a></li-->
										</ul>
									</li>  
								</ul>
								</li>
								<li>
								<a href="#">CSV Template <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">Glab Template <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showGlabSample.php">Show All</a></li>
											<!--li><a href="dlGlabTemplate.php">Download Glab Template</a></li>
											<li><a href="impGlabCsv.php">Import VOC Csv</a></li>
											<li><a href="impPahCsv.php">Import PAH Csv</a></li>
											<li><a href="exportReport.php">Export Report</a></li-->
										</ul>
									</li>
									<li>
										<a href="#">Contractor Template <span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><a href="showContractor.php">Show All</a></li>
											<!--li><a href="impContractor.php">Import Template</a></li>
											<li><a href="updateContractorTemp.php">Update Template</a></li>
											<li><a href="delContractor.php">Delete all record</a></li>
											<li><a href="delSingleContractor.php">Delete single record</a></li-->
										</ul>
									</li>
									</ul>
								</li>
								<li>
								<a href="#">Incident Report <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="incidentReportList.php">Show All</a></li>
									<!--li><a href="addIncidentReport.php">Add Incident Report</a></li-->
								</ul>
								</li>
								<!--li>
								<a href="#">Graph Generation <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="genSummaryPlot.php">Generate Summary Plot</a></li>
								</ul>
								</li-->
								<li><a href="logout.php">Logout</a></li>
								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
			}
		?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>  
		<script type="text/javascript" src="js/jquery.smartmenus.js"></script>
		<script type="text/javascript" src="js/jquery.smartmenus.bootstrap.js"></script>  
	  	<script>
			function logout() {
		
				location.replace("logout.php")
			}


		</script>
	</body>
</html>