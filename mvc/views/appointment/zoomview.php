<!DOCTYPE html>
	<head>
	    <title><?=$this->lang->line('panel_title')?></title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.6/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.9.6/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="origin-trial" content="">
	</head>

	<body>
		<?php 
			$role = 0;
			if(($appointment->create_userID == $this->session->userdata('loginuserID') && $appointment->create_roleID == $this->session->userdata('roleID')) || ($this->session->userdata('roleID') == 1) || ($appointment->create_roleID == 1 && $this->session->userdata('loginuserID') == 2)) {
				$role = 1;
			} else {
				$role = 0;
			}
		?>
		<script>
	        var API_KEY 		= '<?=$zoomsetting->api_key?>';
	        var API_SECRET 		= '<?=$zoomsetting->api_secret?>';
	        var METTING_NUMBER 	= '<?=$appointment->metting_id?>';
	        var USER_NAME 		= '<?=$this->session->userdata('name')?>';
	        var PASSWORD 		= '<?=$appointment->password?>';
	        var LEAVE_URL		= '<?=base_url('appointment/index')?>';
	        var ROLE 			= '<?=$role?>';
		</script>
        <script src="https://source.zoom.us/1.9.6/lib/vendor/react.min.js"></script>
        <script src="https://source.zoom.us/1.9.6/lib/vendor/react-dom.min.js"></script>
        <script src="https://source.zoom.us/1.9.6/lib/vendor/redux.min.js"></script>
        <script src="https://source.zoom.us/1.9.6/lib/vendor/redux-thunk.min.js"></script>
        <script src="https://source.zoom.us/1.9.6/lib/vendor/lodash.min.js"></script>
        <script src="https://source.zoom.us/zoom-meeting-1.9.6.min.js"></script>
		<script src="<?=base_url('assets/inilabs/appointmentzoom/view.js')?>"></script>
	</body>
</html>
