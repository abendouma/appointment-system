<?php
/***************
  appointmentHandler.php
  Author: Alexander Miner
  Project: RDI Project 2
  Group: E Man Maet
    Eric Kisner
	Charles Porter
	Alexander Miner
****************/
	ini_set('allow_url_fopen', 'on');
	
	$basePath = "http://simon.ist.rit.edu:8080/AppointmentSystem/resources/appointmentservice/";
	$errorMessage;
	$pageState;			//holds state of page to display
	/*	Possible page states:
	*	Log Out - display login dialogue
	*	Log In - to determine valid login
	*	My Appointments - display user's appointments (also used to delete)
	*	New Appointment - display add appointment dialogue (also used to update)
	*	Day View - view doctor's schedule for a given day
	* 	Week View - view doctor's schedule for a given week
	*/
	$controlState;		//holds state of control to display form controls
	/*	Possible control states:
	*	login - display login dialogue
	*	Logged in- display main controls
	*		-consists of logout, add appt, view my appts, view day, view week (disable current view)
	*/
	$myName;			//holds name of logged in user
	$myKey;				//ID of logged in user
	$doctorName;		//name of user's doctor
	
	function init(){
		global $pageState;
		global $controlState;
		global $doctorName;
		global $errorMessage;
		
		//remove this line after implementation
		$doctorName = "Doctor Dude";
		$errorMessage = "";
		//$errorMessage = file_get_contents("http://simon.ist.rit.edu:8080/AppointmentSystem/resources/appointmentservice/PatientAppointments?patientId=1");
		
		if(!(isset($_POST['pageState']))){		//if page is receiving a POST submission
			$pageState = "Log Out";
			$controlState = "login";
		}
	}
	
	//updates the page
	function updatePage(){
		global $basePath;
		global $pageState;
		global $controlState;
		global $myName;
		global $errorMessage;
		global $myKey;
		
		if(isset($_POST['pageState'])){		//if page is receiving a POST submission
			if(($_POST['pageState']) == "Log In"){	//if attempting to log in
				//set path to call Login method with username and password
				$url = $basePath . "Login?username=" . ($_POST['login_name']) . "&password=" . ($_POST['login_pass']);
				$res = file_get_contents($url);
				/*$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				$res = curl_exec($ch);
				curl_close($ch);*/
				
				if($res != null){
				//if($res != false){
					//if the credentials are accepted
					$pageState = "My Appointments";
					$controlState = "Logged in";	
					$myName = "Test Name";
					$myKey = $res;
				}
				else{
					$errorMessage = "Incorrect login." . " Res = " . $res . " query: " . $url;
					$pageState = "Log Out";
					$controlState = "login";
				}
			}
			else if(($_POST['pageState']) == "My Appointments"){
				$pageState = "My Appointments";
				$controlState = "Logged in";	
				$myName = "Test Name";
				
				$url = $basePath . "PatientAppointments?patientId=" . $myKey;
				$res = file_get_contents($url);
				if($res != null){
					//build display for my appointments
					//include button to delete or change an appointment
				}
				else{
					$errorMessage = "No appointments found.";
				}
			}
			else if(($_POST['pageState']) == "New Appointment"){
				$pageState = "New Appointment";
				$controlState = "Logged in";	
				$myName = "Test Name";
				
				//add form controls to add an appointment
			}
			else if(($_POST['pageState']) == "Day View"){
				$pageState = "Day View";
				$controlState = "Logged in";	
				$myName = "Test Name";
				
				//$url = $basePath . "DayAppointments?timeframe=" . $;
				//$res = file_get_contents($url);
				if($res != null){
					//build display for this day
				}
				else{
					$errorMessage = "No appointments found.";
				}
			}
			else if(($_POST['pageState']) == "Week View"){
				$pageState = "Week View";
				$controlState = "Logged in";	
				$myName = "Test Name";
				
				//$url = $basePath . "TimespanAppointments?startTime=" . $ . "&endTime=" . $;
				//$res = file_get_contents($url);
				if($res != null){
					//build display for this week
				}
				else{
					$errorMessage = "No appointments found.";
			}
		}
	}
	
	//display proper buttons
	function buildControl(){
		global $controlState;
		
		if($controlState == "login"){
			buildLogin();
		}
		else if($controlState == "Logged in"){
			buildMain();
		}
	}
	
	//build the login display
	function buildLogin(){
		$dispBuild = "<table id = 'loginTable' border = 0 cellpadding = 0 cellspacing = 0>";
		$dispBuild = $dispBuild . "<tr><td>Name</td><td><input type = 'text' name = 'login_name' size = '15' maxlength = '30' value = ''></td></tr>";
		$dispBuild = $dispBuild . "<tr><td>Password</td><td><input type = 'password' name = 'login_pass' size = '15' maxlength = '30' value = ''></td></tr>";
		$dispBuild = $dispBuild . "</table>";
		$dispBuild = $dispBuild . "<input type = 'Submit' name = 'pageState' value = 'Log In' />";
		echo($dispBuild);
	}
	
	//builds main control
	function buildMain(){
		global $pageState;
		
		echo "<div id = 'mainControl'>";
		
			if($pageState != "My Appointments"){
				myAppt();
			}
			if($pageState != "New Appointment"){
				addAppt();
			}
			if($pageState != "Day View"){
				addDay();
			}
			if($pageState != "Week View"){
				addWeek();
			}
			addLogout();
			
		echo "</div>";
	}
	
	//adds the log out button
	function addLogout(){
		echo "<input type = 'Submit' name = 'logout_submit' value = 'Log Out' />";
	}
	
	//add the my appointments button
	function myAppt(){
		echo "<input type = 'Submit' name = 'pageState' value = 'My Appointments' />";
	}
	
	//add the add appointment button
	function addAppt(){
		echo "<input type = 'Submit' name = 'pageState' value = 'New Appointment' />";
	}
	
	//add the view day button
	function addDay(){
		echo "<input type = 'Submit' name = 'pageState' value = 'Day View' />";
	}
	
	//add the view week button
	function addWeek(){
		echo "<input type = 'Submit' name = 'pageState' value = 'Week View' />";
	}
	
	//set up a display test
	function showTest(){
		global $pageState;
		global $myName;
		global $doctorName;
		
		if($pageState == "My Appointments"){
			$dispBuild = "<h1>" . $myName . "'s Appointments</h1>";
			$dispBuild = $dispBuild . "<p>My Appointments</p>";
		}
		else if($pageState == "New Appointment"){
			$dispBuild = "<h1>Create an Appointment</h1>";
			$dispBuild = $dispBuild . "<p>New Appointment</p>";
		}
		else if($pageState == "Day View"){
			$dispBuild = "<h1>" . $doctorName . "'s Schedule For " /*Insert date here*/ . "</h1>";
			$dispBuild = $dispBuild . "<p>Day View</p>";
		}
		else if($pageState == "Week View"){
			$dispBuild = "<h1>" . $doctorName . "'s Schedule For Week Of " /*Insert date here*/ . "</h1>";
			$dispBuild = $dispBuild . "<p>Week View</p>";
		}
		echo($dispBuild);
	}
	
	//logs IP address and date upon submission
	/*function logWrite(){
		$logFile = "log/log.txt"; //opens the log file
		$fh = fopen($logFile, 'a') or die("Log file not found"); //sets up the file handler to append
		
		if(!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		fwrite($fh, $ip . " - " . strftime('%c') . "\n");	//writes the user's IP address to the log file
		fclose($fh);				//closes the file
	}*/
?>