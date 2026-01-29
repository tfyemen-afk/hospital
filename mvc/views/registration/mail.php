<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
</head>
<body>
	<p>Dear <b><?=$name?></b>, </p>
	<p>Thank you for registering to <?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?>. Your registration has been received.</p>
	<p>If you would like to view your registration details, you can login at the following link:</p>
	<p>URL : <a href="<?=site_url('signin/index')?>"><b><?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?></b></a></p>
	<?=($email) ? '<p>You registered with this email: <b>'. $email.'</b></p>' : ''?>
	<?=($username) ? '<p>You registered with this username: <b>'. $username.'</b></p>' : ''?>
	<?=($password) ? '<p>You registered with this password: <b>'. $password.'</b></p>' : ''?>
	<p>If you forgot your password, simply hit "Forgot password" and you'll be prompted to reset it.</p>
	<p>If you have any questions leading up to the system, feel free to reply to this email.</p>
	<p>We look forward to serve you.</p>
	<p><b>Kind Regards,</b></p>
	<a href="<?=$_SERVER['HTTP_HOST']?>"><h2><b><?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?></h2></b></a>
	<p><b><?=isset($generalsettings->phone) ? $generalsettings->phone : ''?></b></p>
</body>
</html>