<!DOCTYPE html>
<html>
<head>
	<title>Admission</title>
</head>
<body>
	<p>Dear <b><?=$patient->name?></b>,</p>
	<p>We would like to inform you that your admission in <b><?=$generalsettings->system_name?></b> has been confirmed.</p>
	<p>Your ward name is <b><?=$wardName?></b> and bed no <b><?=$bedName?></b>.</p>
	<p>If you would like to view your admission details, you can login at the following link:</p>
	<p>URL : <a href="<?=site_url('signin/index')?>"><b><?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?></b></a></p>
	<p>You admitted with this email: <?=$patient->email?></p>
	<p>If you forgot your password, simply hit "Forgot password" and you'll be prompted to reset it.</p>
	<p>If you have any questions leading up to the system, feel free to reply to this email.</p>
	<p>We look forward to serve you.</p>
	<p><b>Kind Regards,</b></p>
	<a href="<?=$_SERVER['HTTP_HOST']?>"><h2><b><?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?></h2></b></a>
	<p><b><?=isset($generalsettings->phone) ? $generalsettings->phone : ''?></b></p>
</body>
</html>