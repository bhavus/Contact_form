<?php
// declare data as an array
$data = [];

//check for post content, if no content, declare error messages
  if ( !isset($_POST["name"])) {
      $data['success'] = false;
			$data['errors']['name'] = 'Please enter your name.';
    }
  elseif ( !isset($_POST["email"])) {
      $data['success'] = false;
      $data['errors']['email'] = 'Please enter your email address.';
        }
  elseif ( !isset($_POST["subject"]) ) {
      $data['success'] = false;
      $data['errors']['subject'] = 'Please enter the subject of the message.';
        }
  elseif ( !isset($_POST["message"]) ) {
      $data['success'] = false;
      $data['errors']['message'] = 'Please enter the message you want to send.';
        }
  elseif (!isset($_POST["captcha"]) ) {
      $data['success'] = false;
      $data['errors']['captcha'] = 'Proof that you are human';
        }
        //validate email else throw error message
  elseif ( !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
      $data['success'] = false;
      $data['errors']['email'] = 'Please chack your email address';
        }


		else{

      $name = strip_tags(trim($_POST["name"]));
      $name = str_replace(array("\r","\n"),array(" "," "),$name);
      $subject = strip_tags(trim($_POST["subject"]));
      $subject = str_replace(array("\r","\n"),array(" "," "),$subject);
      $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
      $admin_email = filter_var(trim($_POST["admin_email"]), FILTER_SANITIZE_EMAIL);
      $message = trim($_POST["message"]);
      $captcha=$_POST['captcha'];
      //verify the captcha (make sure to add your private key to the url without the parenthesis)
		  $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=​(Your-RECAPTCHA-PRIVATE-KEY-GOES-HERE)c&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

		      if($response ["success"]==false){
			         $data['errors'] = 'Captcha failed. Please try again';
               $data['success'] = false;
          }
          //if we are here it means, everything was good so we can send the email
          else {
              $recipient = $admin_email;

              $email_subject = "New Contact from $name";

              $email_message = "Name: $name\n";
		          $email_message .= "Subject: $subject\n";
              $email_message .= "Email: $email\n\n";
              $email_message .= "Message:\n$message\n";

              $email_headers = "From: $name <$email>";

                if (mail($recipient, $email_subject, $email_message, $email_headers)) {
                    $data['message'] = "Thank You! We have received your message and we would get back to you soon.";
			              $data['success'] = true;
                } else {
			              $errors['name'] = "Oops! Something went wrong and we couldn't send your message.";
                    }
            }
		}

//convert data array to json and echo the json content
	echo json_encode($data);
?>
