<!DOCTYPE html>
<html>
<head>
	<title>Appointment</title>
</head>
<body>
	<p>Dear <b><?=$patient->name?>,</b></p>
	<p>This is <b><?=$userName?></b> from <b><?=$generalsettings->system_name?></b>, This is a confirmation that we received a request for an checkup/visit appointment at <b><?=date('d F Y H:i A', strtotime($appointmentdate))?></b> on <b><?=date('l', strtotime($appointmentdate))?></b> with Doctor <b><?=$doctorName?> (<?=$doctorDesignation?>)</b>.</p>
	<p>We look forward to see you then! Please text or contact us if you want to reschedule.</p>
	<p>If you would like to view your appointment details, you can login at the following link:</p>
	<p>URL : <a href="<?=site_url('signin/index')?>"><b><?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?></b></a></p>
	<p>You appointment with this email: <?=$patient->email?></p>
	<p>If you forgot your password, simply hit "Forgot password" and you'll be prompted to reset it.</p>
	<p>If you have any questions leading up to the system, feel free to reply to this email.</p>
	<p>We look forward to serve you.</p>
	<p><b>Kind Regards,</b></p>
	<a href="<?=$_SERVER['HTTP_HOST']?>"><h2><b><?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?></h2></b></a>
	<p><b><?=isset($generalsettings->phone) ? $generalsettings->phone : ''?></b></p>
</body>
</html>