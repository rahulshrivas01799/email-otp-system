<?php include('needconnection.php'); ?>


<?php
$success = "";
$error_message = "";

if(!empty($_POST["submit_email"])) {
	$result = mysqli_query($conn,"SELECT * FROM  registered_user WHERE email= '" . $_POST["email"] . "' ");
	$count  = mysqli_num_rows($result);
	if($count>0) {
		// generate OTP
		$otp = rand(1000,9999);
		// Send OTP
		require_once("mail_function.php");
		$mail_status = sendOTP($_POST["email"],$otp);
		
		if($mail_status == 1) {
			$result = mysqli_query($conn,"INSERT INTO otp_expiry (otp, is_expired, create_at) VALUES ('" . $otp . "', 0, '" . date("Y-m-d"). "')");
			$current_id = mysqli_insert_id($conn);

			if(!empty($current_id)) {
			$success=1;
			}
		}
	}  else {
		$error_message = "Email not exists!";

	} 
}
if(!empty($_POST["submit_otp"])) {
	$result = mysqli_query($conn,"SELECT * FROM otp_expiry WHERE otp='" . $_POST["otp"] . "' AND is_expired =0 AND NOW() <= DATE_ADD(create_at, INTERVAL 24 HOUR)");
	$count  = mysqli_num_rows($result);
	//echo ($count);die;
	if(!empty($count)) {
		$result = mysqli_query($conn,"UPDATE otp_expiry SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "' ");
		
    	$otp=$_POST["otp"];
        $success = 2;
	} else {
		$success =1;
		$error_message = "Invalid OTP!";
	}	
}
?>








<html>
<head>
	<title>otp generator</title>
</head>
<body>
<?php
if(!empty($error_message)) {

?>
<div class="massage error_message"><?php echo $error_message; ?></div>

<?php 
}  ?>

<form name="frmuser" method="post" action="">
	<div class="tbLogin">
		<?php
		if(!empty($success==1)){  

		   ?>
		   <div class="tableheader"> Enter OTP</div>
		   <p style="color: #31ab00;">Check your email for the OTP</p>

		   <div class="tablerow ">
		   	<input type="text" name="otp" placeholder="one time password" class="login-input" required>
		   </div>
		   <div class="tableheader"><input type="submit" name="submit_otp" value="submit" class="btnSubmit"></div>

		<?php  }
		else if ($success ==2) {

		?>
		<p style="color: #31ab00"> Welcome,you have successful logged in</p>
		<?php
		}else{

		?>
		<div class="tableheader">Enter your login email</div>
		<div class="tablerow"><input type="email" name="email" placeholder="email" class="login-input" required></div>
		<div class="tableheader"><input type="submit" name="submit_email" value="submit" class="btnSubmit"></div>
		<?php
		}  
		?>
</div>
</form>
</body>
</html>