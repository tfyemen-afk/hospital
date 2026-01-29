<!DOCTYPE html>
<html>
<head>
	<title>User Registration</title>
</head>
<body>
	<h2>Welcome to <?=$generalsettings->system_name?></h2>
    <p>Please log-in to this website and change the password as soon as possible </p>
    <p>URL : <a href="<?=site_url('signin/index')?>"><b><?=isset($generalsettings->system_name) ? $generalsettings->system_name : ''?></b></a></p>
    <p>Username : <b><?=$username?></b></p>
    <p>Password : <b><?=$password?></b></p>
    <br>
    <p>Once again, thank you for choosing <?=$generalsettings->system_name?></p>
    <p><b>Best Wishes,</b></p>
    <p><a href="<?=$_SERVER['HTTP_HOST']?>"><b>The <?=$generalsettings->system_name?> Team</b></a></p>
</body>
</html>