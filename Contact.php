<?php 
	// N/B Note the unique way of declaring variables with isset to avoid  "  PHP error: Notice: Undefined index:"
	// http://stackoverflow.com/questions/4465728/php-error-notice-undefined-index 

	$LastName    = ( isset($_POST['LastName']) ) ? $_POST['LastName'] : '';
	$FirstName   = ( isset($_POST['FirstName']) ) ? $_POST['FirstName'] : '';
	$Valid_Email = ( isset($_POST['Valid_Email']) ) ? $_POST['Valid_Email'] : '';
	$Phone       =  ( isset($_POST['Phone']) ) ? $_POST['Phone'] : '';
	$Subject     =  ( isset($_POST['subject']) ) ? $_POST['subject'] : '';
	$Gutariria   = ( isset($_POST['gutariria']) ) ? $_POST['gutariria'] : '';

	$State      =  (isset($_POST['State']));  # Selection
	$optin      = (isset( $_POST['optin']));   # checkbox


	$headers	 = " "; # mail setion

	$Captcha     =  ( isset($_POST['Captcha']) ) ? $_POST['Captcha'] : '';
	$CaptchaC     =  ( isset($_POST['CaptchaC']) ) ? $_POST['CaptchaC'] : '';

	#the variables  are supposed tobe gotten from the form after filling and submitting

	/* Lets validate data by:
	1.Striping unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
	2.Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
	*/

	function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	/*Now lets make some fields required -
	Notice the the field after the last equal sign is Blank  ie = " "
	*/

	$LastNameErr = $FirstNameErr = $Valid_EmailErr =  $Phone_Err = $SubjectErr     = $CaptchaErr       =  $EmptyFieldErr = " ";
	$LastNameC   = $FirstNameC   = $Valid_EmailC   =  $PhoneC    = $SubjectC       =   $Phone_Validate =  $EmptyField = " ";  # " C " for Clean


	if ($_SERVER['REQUEST_METHOD']== "POST"){

		if (empty($_POST["LastName"])) { 
		$LastNameErr=" <font color= red> Last Name Is required </font>" ; 
		}
		else{
			#Character validation	
			if (!preg_match("/^[a-zA-Z ]*$/",$LastName)){
				$LastNameErr = "<font color= red> Only letters and white space allowed  </font>"; 
			}
			else{
				$LastNameC = test_input($_POST["LastName"]);

				$LastName= TRUE;
			} 
		}


		if (empty($_POST["FirstName"])){ 
			$FirstNameErr=" <font color= red> Last Name Is required </font>" ; 
		}
		else{
			#Character validation	
			if (!preg_match("/^[a-zA-Z ]*$/",$FirstName)){
				$FirstNameErr = "<font color= red>Only letters and white space allowed </font>   ";  
				}
				else{
					$FirstNameC = test_input($_POST["FirstName"]);

					$FirstName = TRUE;
				}
			}


		//Email
		if (empty($_POST["Valid_Email"])){ 
			$Valid_EmailErr=" <font color= red> Valid Email Is required </font>" ; 
			}
			else{
				#Character validation	
				if (!filter_var($Valid_Email, FILTER_VALIDATE_EMAIL)){
					$Valid_EmailErr = "<font color= red>Wrong email Format </font>  ";
				}
				else{
					$Valid_EmailC  = " ".$Valid_Email;
					$Valid_Email = TRUE;
				}
			}


		// Phone Field 
		if (empty($_POST['Phone'])){
			# code...
			$Phone_Err= "<font color= red>Phone Number required </font>"; 
			}
			else{
				if (ctype_digit($_POST['Phone']) == TRUE){  // validating only  digits
					# code...
					$PhoneC = "".$Phone;
					$Phone_Validate = TRUE;  // $Phone = True  contradicts with the inputs.
				}
				else{
					$Phone_Err = " <font color= red>Invalid Phone Format </font>" ; 
				}
			}

		//Subject 
		if (empty($_POST["subject"])){ 
			$SubjectErr=" <font color= red> subject Is required </font>" ; 
			}
			else{
				#Character validation	
				if (!preg_match("/^[a-zA-Z ]*$/",$Subject)){
					$SubjectErr = "<font color= red>Only letters and white space allowed </font>  ";  
					}
					else {
						$Subject = test_input($_POST["subject"]);

						#  $Subject = TRUE;
					}
				}

		// State Selection  Validation
		//https://support.formstack.com/customer/portal/articles/1239033-dropdown-list-fields
		# Robots Validation
		//A SIMPLE CAPTCHA FOR PREVENTING ROBOTS
		if (empty($_POST['Captcha'])){
			# code...
			$CaptchaErr = "<font color= 'red'>  Please fill The security code field </font> ";
			}
			elseif(preg_match("/CapTcHa/", $_POST['Captcha'])){
				# code...
				$CaptchaC =$Captcha;
				$Captcha = TRUE;		
			}
			else{
				$CaptchaErr = " <font color= 'red'> Please Try Again </font>";
			}

		// Empty Field method (honey Pot - http://devgrow.com/simple-php-honey-pot/)
		if (empty($_POST['EmptyField'])){
			#
			$EmptyFieldC = $EmptyField;
			$EmptyField  = TRUE;
		}
		else{
			$EmptyFieldErr = " <font color='red'> Only Human beings can submit this form </font>";
			}
	}//THE GRAND OPENING IFF



	//MAILING  SECTION  -- ATTENTION  REFRESH BOTH THE GAE CONSOLE, AND THE  APP VERSION AFTER UPDATING FOR EMAIL TO WORK.
	if ($_POST['optin'] == TRUE){
		# send mail to 
		$to = "waganaa@yahoo.com, $Valid_EmailC";
		}
		else{
			$to = "waganaa@yahoo.com";
	}


	# Notice the "\n" to break the line and help format the email body.
	$Message = "Last Name: $LastNameC  \n First Name: $FirstNameC \n  Email: $Valid_EmailC \n Phone: $PhoneC \n State: $State \n  Wrote:  \n  $Gutariria"  ;

	if ($LastName + $FirstName + $Valid_Email + $Phone_Validate +$Captcha == 5 ){ 
		# code	
		mail($to, $Subject, $Message, $headers);
		header("refresh:2;");
		//header ("refresh:0; url= https://www.yahoo.com/");
		// see Also header('location: https://www.yahoo.com/');
	}

	/* IF YOU WANT TO CAPTURE SOME IONFO SUCH AS EMAIL AND NAME INTO YOUR DATABASE, the $sql details goes below this line
	$sql = "INSERT INTO staff_list2 (LastNameC, FirstNameC, Valid_EmailC, PhoneC, Subject, Gutariria, Country)
	VALUES ('$LastNameC', '$FirstNameC', '$Valid_EmailC', '$Phone', '$Subject', '$Gutariria', '$Country')";	
	*/
?>


<!DOCTYPE HTML public  "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1.0">
		<meta name="description" content="homepage">
		<title> contact.php </title>
		<link rel="stylesheet"   type="text/css" href="style.css"> 

	</head>

	<body>
		<div class="site_container">
			<center> <h1> New England Audio Visual Services </h1> </center>

			<div class="below_header"> 

				<!--Stop Submit With Empty Input Values Using PHP
				http://stackoverflow.com/questions/7714732/stop-submit-with-empty-input-values-using-php      -->

				<div class="navbar" style="border:none;"> <!-- horizontal Navbar -->
		  			<ul>
		   				<li> <a Target="_self" href="index.php"><font size="4"> <b>Back Home</b></font> </a> </li>
		   			</ul>
		   		</div>

				<!-- Html Form starts here   -website  -->
				<div class="form" >

					<form method="post" action= "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" >

						<fieldset><!--https://www.youtube.com/watch?v=F7A5p2Xah0U -->

							<legend><b>Please fill Out the Contact Us Form:</b></legend>

							<ul>
								<li>
									<label id="lastname"> Last Name: </label>
									<input name="LastName" type="text" value= "<?php echo isset($_POST['LastName']) ? $_POST['LastName'] : '' ?>">*
									<span><?php echo $LastNameErr;?></span> <!-- this php code  is for the message to be echoed if the field is Blank --> 
								</li>
								<li>
									<label id="First_Name">First Name: </label>
									<input name="FirstName" type="text" value= "<?php echo isset($_POST['FirstName']) ? $_POST['FirstName'] : '' ?>">*
									<span><?php echo  $FirstNameErr;?></span> <!-- this php code  is for the message to be echoed if the field is Blank -->  
								</li>

								<li>
									<!-- <p>Contact info:</p> -->
									<label id = "Valid_Email">Valid Email: </label>
									<input type="text" name ="Valid_Email" value= "<?php echo isset($_POST['Valid_Email']) ? $_POST['Valid_Email'] : '' ?>" >*
									<span><?php echo $Valid_EmailErr;?></span>  <!-- this php code  is for the message to be echoed if the field is Blank --> 

								</li>
								<li>
									<p><label id = "Phone"> Phone: </label>
									<input type="text" name ="Phone" maxlength="10"  value= "<?php echo isset($_POST['Phone']) ? $_POST['Phone'] : '' ?>">*  
									<span><?php echo $Phone_Err;?></span></p>  <!--issue-with-10-digit -data-in-mysq  N/B http://stackoverflow.com/questions/7570960/what-is-the-l-->

								</li>
								<li>
									<p>This contact is about what? <br>
									<label id="subject"> Subject:</label>
									<input type="text" name ="subject" value="<?php echo isset($_POST['subject']) ? $_POST['subject'] : '' ?>">
									<span><?php echo $SubjectErr;?></span></p> 

								</li>
								<li>
									<p>Gutariria:<br>
										Please Type or Paste a Detailed Gutariria of your Subject.<br>
										Remember to Include the Best Way and Time to contact you.
										<label id = "gutariria"> Gutariria: </label>
										<textarea rows="10" cols="50" name="gutariria" > </textarea> 
									</p>

								</li>
								<li>
									<p>*If residing in USA,<br>
										Please  Select Your State: 
										<select name="State" >
											<option value=" "> </option>
											<option value="kenya">Kenya</option>
											<option value="Uganda">Uganda</option>
											<option value="Tanzania">Tanzania</option>
											<option value="Zimbambwe">Zimbambwe</option>
										</select> 
									</p>

								</li>
								
								<li>
						
									<p>
										If you want a copy of the form data emailed to you, <br>
										Check this box: 
										<input type = "checkbox" name = "optin" value="0" >
									</p>

								</li>
								<li>
									<!--Robots Validation --> 
									<p> Verification Code: <img src="Captcha.gif"  alt="Captcha_Image"><br> 
										Enter the above Code: <input  name="Captcha" type="text" value=""> 
										<span><?php echo $CaptchaErr;?></span></p><!-- this php code  is for the message to be echoed if the field is Blank --> 
								
									<div class="CaptchaRoberts">
										Empty Field:  <input name="EmptyField" type="text" value= "<?php echo isset($_POST['EmptyField']) ? $_POST['EmptyField'] :'' ?>"> *
										<span> </span>  <!-- this php code  is for the message to be echoed if the field is Blank --> 
									</div>

								</li>

								<li>
									<input type="submit" value="Send">
								</li>
							</ul>
						</fieldset>
					</form>
				</div><!--form -->

				<Center> <p style="background-color:rgb(255, 194, 0)">   NewEngland Audio Visual Services @2014 </p> </center>
			
			</div><!--below_header -->

			<footer>
				<h2> this is footer </h2>
			</footer>
		</div><!-- site_container -->	
	</body>
</html>
