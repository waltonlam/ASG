# TAPS

Toxic Air Pollutant project for Section 2


To simply deploy and setup the TAPS, follow the followings:

1. checkout the project from the gitLab

	1.1 Open the link (https://118.140.197.41/) at the browser ,then login

	1.2 Click the project -> asg2/TAPS

	1.3 Use the sourcetree or any source control tools to checkout the project

	1.4 Copy the below link to your checkout tool 
	 	ssh://git@192.168.100.152:22022/asg2/taps.git
	 	
	1.5 Select the branch -> demo


2.  Copy entire	directory "./" to under	htdocs (C:/xampp/htdocs/[your directory]) ,	e.g., C:/xampp/htdocs/taps-demo


3.  Setup the database for the TAPS system. 

	A sample sql file in place at the project (tap.sql) and ready to be imported:

	3.0  Open browser and put URL http://[domain]/phpmyadmin/index.php
	     e.g. https://localhost/phpmyadmin/index.php 

	3.1  On	the left column, click "New".

	3.2  Input "taps" to the "Database name" field , then click "create".

	3.3  On	the left column, click "taps".

	3.4  On the top menu, click "import" -> "Choose File",
	 	 select the "taps.sql" form the project folder(C:\xampp\htdocs\ [your directory])

	3.5  Click "Go".


4. Open new tab from the browser, enter"http://[your domain]/tap-demo/login.php". 
   e.g. http://localhost/tap-demo/login.php