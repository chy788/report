<?php		

	function getimg()
	{
		$resultm = mysql_query("SELECT * FROM user_info where u_id='".$_SESSION['u_id']."'");
			
        //$arr_activename = array();
		$rowm = mysql_fetch_array($resultm);
			$imgsrc = "http://".$_SERVER['HTTP_HOST']."/upload/" . $rowm['imgsrc'];
			return $imgsrc;
	}