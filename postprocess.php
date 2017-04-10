<?php 
session_start();
// start a session to carry variables between pages

//start an is statement using the ISSET method to validate that there is a value in the "email field" 
// $_POST is a Super Global in PHP that carried the entered data from specific form fields. 
//In this case it's the form field with the NAME of EMAIL.
if(isset($_POST['email'])) {
	//take the NAME and EMAIL data and stor them into variables $name and $email
	$name = $_POST['name'];
	$email = $_POST['email'];
	
	//Similarly take data from the USER field and assign to 2 variables: $user will be used for the rest of this script
	// $_SESSION is a super global that can be called and edited from Page to page since we started with a session on line 3
	$user = $_SESSION['user'] = $_POST['user'];

	//Now that we have the form data stored, We can do things with it. Like sending an email.
	//To do so, create a variable that contains the email body using a combo of srtings and variables
	$email_message = "Form details below.\n 
	Name: $name \n 
	Email: $email\n 
	UserName: $user";

	//store the subject in a variable
	$email_subject = "Message from $name"; 

	//store the email recipient in a variable
	$email_to = "artbyeloi@gmail.com";

	//create email headers, the hidden code on top of all emails, read by the mail client.
	//regardless this configuration, the only thing that you would change on this line is if the $emails variable is the email address you want in the REPLY and FROM lines.
	$headers = 'From: '.$email."\r\n".'Reply-To: '.$email."\r\n".'X-Mailer: PHP/'.phpversion();

	//build your email using the @Mail method, using the parameters of the variables that you just created in this order...
	// email recipient, email subject, email message, email headers
	@mail($email_to, $email_subject, $email_message, $headers);
	
	//Now that we have sent out an email let's also take Data from the form and store it.
	//We will create a folder with The name of the user. And within this folder will be an image the user uploaded and a text file  containing the text input Data values

	// since the text document and the image will have the same file names regardless of the user
	//we will uniquely name each directory by the username
	mkdir($user);
	
	// is the Fopen method to Create a text file with the name profile.txt, stored in the directory we just created.
	// the second parameter "W" Gives us permission write in this file
	$fh = fopen("$user/profile.txt", 'w') or die("Failed to create file");
	
	//Create a variable that contains the strings of all the data we want to store in the text document
	$text = "$name \n $email \n $user"; 
	
	// use the fwrite method to call the file we created and insert the text please stories in the Previous
	fwrite( $fh, $text) or die("Could not write to file"); 
	
	// use the Fclose method to close the text document after we've written to it
	fclose( $fh); 
	
	// run another  if statement to validate if a photo was uploaded by the user.
	// if true do the following…
	if ( $_FILES ) { 
		// Store the file name and a variable $Image
		$image = $_FILES['photo']['name'];
		
		// call the file type and check it against the following cases
		switch( $_FILES['photo']['type'] ) { 
			// check if it's a JPEG. if so assign $EXT the value of JPG
			case 'image/jpeg': 
				$ext = 'jpg'; 
				break;
			// check if it's a PNG. If so assign $EXT the value of PNG
			case 'image/png': 
				$ext = 'png'; 
				break;
			// is neither of the two checks are true, assign $EXT value null
			default: 
				$ext = ''; 
				break;
		}
		// run another if statement checking if $EXT has a value other than null
		// as seen above this would mean $EXT would be either a PNG or a JPG
		if ($ext) { 
			
			// creative variable with the desired files path and name
			$n = "$user/image.$ext"; 
			
			// the file to the server  and assign it the Path and name we specified previously
			move_uploaded_file( $_FILES['photo']['tmp_name'], $n); 
		}
	} else {
		echo "No image has been uploaded";
	};

	// once all the data is processed proceeded to user to the confirmation Page
	header('Location: confirmed.php');

}	
?>