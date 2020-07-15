<?php
function sendOTP($email,$otp) {
	require('PHPMailerAutoload.php');
	require('class.smtp.php');
   
   $mail =new PHPMailer;
   $mail->isSMTP();
   $mail->Host='smtp.gmail.com';
   $mail->SMTPAuth=true;
   $mail->SMTPSecure='tls';
   $mail->Port=587;

// $mail->SMTPDebug = 1; 

    $mail->Username='no-reply@digimonk.in';
    $mail->Password='digi@123';
	$message_body ="one time password for PHP login authentication is : <br/><br/>".$otp;
	$mail->AddReplyTo('rahulshrivas31121998@gmail.com','rahul shrivas');
	$mail->SetFrom('rahulshrivas31121998@gmail.com','rahul shrivas');
	$mail->AddAddress($email);
	$mail->Subject="OTP to LOGin";
	$mail->MsgHTML($message_body);
	$mail->isHTML(true);  
	$result=$mail->Send();
	if(!$result) {
		echo "Mailer Error :"  . $mail->ErrorInfo;
	} else {
	return $result;
	}
 	}
?>