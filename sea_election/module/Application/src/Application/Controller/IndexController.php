<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {
        return new ViewModel();
    }

    public function mainAction() {
        return new ViewModel();
    }
//注册后台方法
    public function getpostAction() {
		$time = time();
		$username = $_POST['username'];
		$nickname = $_POST['nickname'];
		$gender = $_POST['gender'];
		$phone = $_POST['phone'];
		$extra = $_POST['extra'];
		$img_arr = explode(".",$_FILES["file"]["name"]);
		$img_src = md5($time.$img_arr['0']);
		move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT'].'/upload/'. $img_src.'.'.$img_arr['1']);
		$img_add = $img_src.'.'.$img_arr['1'];
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        mysql_query("INSERT INTO user_info (u_id, username, password, nickname, gender, phone, extre_info, imgsrc) VALUES ('', '".$username."', '111111','".$nickname."', '".$gender."', '".$phone."', '".$extra."', '".$img_add."')");
		$u_id = mysql_insert_id();

        mysql_close($con);
		//var_dump($arr_extra);
        $viewInfo = array(
			'u_id' => $u_id,
			'username' => $username,
            'nickname' => $nickname,
			'gender' => $gender,
			'phone' => $phone,
			'extra' => $extra,
			'img_add' => "http://".$_SERVER['HTTP_HOST']."/upload/" .$img_add);
        return new ViewModel($viewInfo);
    }

	public function getposthAction() {
		$time = time();
		$username = $_POST['username'];
		$phone = $_POST['phone'];
		$extra = $_POST['extra'];
		$img_arr = explode(".",$_FILES["file"]["name"]);
		$img_src = md5($time.$img_arr['0']);
		move_uploaded_file($_FILES["file"]["tmp_name"],
			$_SERVER['DOCUMENT_ROOT'].'/upload/'. $img_src.'.'.$img_arr['1']);
		$img_add = $img_src.'.'.$img_arr['1'];
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        mysql_query("INSERT INTO host (host_id, username, password, phone, extre_info, imgsrc) VALUES ('', '".$username."', '111111', '".$phone."', '".$extra."', '".$img_add."')");
		$u_id = mysql_insert_id();

        mysql_close($con);
		//var_dump($arr_extra);
        $viewInfo = array(
			'u_id' => $u_id,
			'username' => $username,
			'phone' => $phone,
			'extra' => $extra,
			'img_add' => "http://".$_SERVER['HTTP_HOST']."/upload/" .$img_add);
        return new ViewModel($viewInfo);
    }
//用户编辑页面数据获取
	public function edituserAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;
		$search_id = $_SESSION['u_id'];

        $result = mysql_query("SELECT * FROM user_info where u_id = ".$search_id);

        $arr_activename = array();

        //$i = '0';
//var_dump(mysql_fetch_array($result));exit;
        while ($row = mysql_fetch_array($result)) {
			//username, password, nickname, gender, phone, extre_info, imgsrc
			$u_id = $row['u_id'];
            $username = $row['username'];
			$nickname = $row['nickname'];
			$gender = $row['gender'];
			$phone = $row['phone'];
			$extre_info = $row['extre_info'];
			$imgsrc = "http://".$_SERVER['HTTP_HOST']."/upload/" . $row['imgsrc'];
            //$i++;
        }

        mysql_close($con);

        $viewInfo = array(
			'u_id' => $u_id,
			'username' => $username,
			'nickname' => $nickname,
			'gender' => $gender,
			'phone' => $phone,
			'extra' => $extre_info,
			'imgsrc' => $imgsrc);
        return new ViewModel($viewInfo);
    }

	public function edithostAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;
		$search_id = $_SESSION['h_id'];

        $result = mysql_query("SELECT * FROM host where host_id = ".$search_id);

        $arr_activename = array();

        //$i = '0';
//var_dump(mysql_fetch_array($result));exit;
        while ($row = mysql_fetch_array($result)) {
			//username, password, nickname, gender, phone, extre_info, imgsrc
			$h_id = $row['host_id'];
            $username = $row['username'];
			$phone = $row['phone'];
			$extre_info = $row['extre_info'];
			$imgsrc = "http://".$_SERVER['HTTP_HOST']."/upload/" . $row['imgsrc'];
            //$i++;
        }

        mysql_close($con);

        $viewInfo = array(
			'h_id' => $h_id,
			'username' => $username,
			'phone' => $phone,
			'extra' => $extre_info,
			'imgsrc' => $imgsrc);
        return new ViewModel($viewInfo);
    }
//创建主办方方法
    public function createhostAction() {
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        mysql_query("INSERT INTO host (host_id, host_name, host_extra) VALUES ('', '" . $post_data["username"] . "', '" . $post_data["extrainfo"] . "')");

        $host_id = mysql_insert_id();

        mysql_close($con);

        $viewInfo = array('username' => $post_data["username"],
            'extrainfo' => $post_data["extrainfo"],
            'hostid' => $host_id);
        return new ViewModel($viewInfo);
    }

	 public function signactiveAction() {
		 return new ViewModel();
	 }
//创建活动方法
    public function createactiveAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        mysql_query("INSERT INTO active (active_id, host_id, active_name, active_extra) VALUES ('', '" . $_SESSION['h_id'] . "', '" . $post_data["activename"] . "', '" . $post_data["extrainfo"] . "')");

        mysql_close($con);

        $viewInfo = array('username' => $post_data["activename"],
            'extrainfo' => $post_data["extrainfo"],
            'hostid' => $post_data["hostid"]);
        return new ViewModel($viewInfo);
    }
//主办发查看信息列表
	public function activelistAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT * FROM active where host_id = " . $_SESSION['h_id']);

        $arr_activename = array();
		$arr_activeid = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
			$arr_activeid[$i] = $row['active_id'];
            $arr_activename[$i] = $row['active_name'];
            $i++;
        }

        mysql_close($con);

        $viewInfo = array('activename' => $arr_activename,
			'arr_activeid' => $arr_activeid,
			'flag' => $i);
        return new ViewModel($viewInfo);
    }
//查看活动信息
	public function activeshowAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $get_data = $_GET;

        $result = mysql_query("SELECT * FROM active where active_id = " . $get_data['id'] . " and host_id = " . $_SESSION['h_id']);

        $arr_activename = array();
		$arr_activeid = array();

		$row = mysql_fetch_array($result);
		$activeid = $row['active_id'];
        $activename = $row['active_name'];
		$activeextra = $row['active_extra'];
		$hostid = $row['host_id'];

        mysql_close($con);

        $viewInfo = array('activename' => $activename,
			'activeid' => $activeid,
			'activeextra' => $activeextra,
			'hostid' => $hostid);
        return new ViewModel($viewInfo);
    }
//编辑活动信息
	public function editactiveAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $get_data = $_GET;

        $result = mysql_query("SELECT * FROM active where active_id = " . $get_data['id'] . " and host_id = " . $_SESSION['h_id']);

        $arr_activename = array();
		$arr_activeid = array();

		$row = mysql_fetch_array($result);
		$activeid = $row['active_id'];
        $activename = $row['active_name'];
		$activeextra = $row['active_extra'];
		$hostid = $row['host_id'];

        mysql_close($con);

        $viewInfo = array('activename' => $activename,
			'activeid' => $activeid,
			'activeextra' => $activeextra,
			'hostid' => $hostid);
        return new ViewModel($viewInfo);
    }
//编辑活动方法
	public function activedoeditAction() {
		session_start();
		//var_dump($_POST);exit;
		$activeid = $_POST['activeid'];
		$activename = $_POST['activename'];
		$extrainfo = $_POST['extrainfo'];
		$hostid = $_POST['hostid'];
		$updatesql = "UPDATE active  set active_name = '".$activename."', active_extra = '".$extrainfo."' where active_id = '".$activeid."'";
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        mysql_query($updatesql);
		$u_id = mysql_insert_id();

        mysql_close($con);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/application/Index/activeshow?id='.$activeid);
		exit;
	}
//展示主办方信息
    public function showhostinfoAction() {
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT * FROM host_active where host_id = " . $post_data['hostid']);

        $arr_activename = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $arr_activename[$i] = $row['active_name'];
            $i++;
        }

        mysql_close($con);

        $viewInfo = array('activename' => $arr_activename);
        return new ViewModel($viewInfo);
    }
//创建用户方法
    public function createuserAction() {
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        //var_dump($post_data);

        mysql_query("INSERT INTO user_info (u_id, username, extra_info) VALUES ('', '" . $post_data["username"] . "', '" . $post_data["extrainfo"] . "')");

        $user_id = mysql_insert_id();

        mysql_close($con);

        $viewInfo = array('username' => $post_data["username"],
            'extrainfo' => $post_data["extrainfo"],
            'userid' => $user_id);

        return new ViewModel($viewInfo);
    }
//用户报名参加活动页面
    public function allactiveAction() {

        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT * FROM active");

        $arr_activename = array();
        $arr_activeid = array();
		$arr_hostid = array();
        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $arr_activename[$i] = $row['active_name'];
            $arr_activeid[$i] = $row['active_id'];
			$arr_hostid[$i] = $row['host_id'];
            $i++;
        }

        mysql_close($con);

        $viewInfo = array('activename' => $arr_activename,
            'activeid' => $arr_activeid,
			'hostid' => $arr_hostid,
            'flag' => $i);
        return new ViewModel($viewInfo);
    }

//用户查看活动详情页面
	public function useractiveshowAction()
	{
		$con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $get_data = $_GET;

        $result = mysql_query("SELECT * FROM active where active_id = " . $get_data['id']);

        $arr_activename = array();
		$arr_activeid = array();

		$row = mysql_fetch_array($result);
		$activeid = $row['active_id'];
        $activename = $row['active_name'];
		$activeextra = $row['active_extra'];
		$hostid = $row['host_id'];

        mysql_close($con);

        $viewInfo = array('activename' => $activename,
			'activeid' => $activeid,
			'activeextra' => $activeextra,
			'hostid' => $hostid);
        return new ViewModel($viewInfo);
	}

//用户报名参加活动方法
    public function signprocessAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;
		//$get_data = $_GET;

        mysql_query("INSERT INTO relation (user_id, active_id, update_time) VALUES ('" . $_SESSION['u_id'] . "', '" . $post_data["active_id"] . "', '" . time() . "')");

        $user_id = mysql_insert_id();

        mysql_close($con);

		header('Location: http://'.$_SERVER['HTTP_HOST'].'/application/Index/allactive');
		exit;

        //$viewInfo = array('result' => 'success');

        //return new ViewModel($viewInfo);
    }
//用户查看已参加的活动
    public function searchactiveAction() {
		session_start();
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT distinct * FROM relation where user_id = " . $_SESSION['u_id']);

        $arr_activename = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $activeid = $row['active_id'];
            $result_activename = mysql_query("SELECT * FROM active where active_id = " . $activeid);
            while ($row_active = mysql_fetch_array($result_activename)) {
                $arr_activename[$i] = $row_active['active_name'];
                $i++;
            }     
        }

        mysql_close($con);

        $viewInfo = array('activename' => $arr_activename);
        return new ViewModel($viewInfo);
    }
    //主办方查看活动的报名信息
    public function searchuserAction() {
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;
		$get_data = $_GET;

        $result = mysql_query("SELECT distinct * FROM relation where active_id = " . $get_data['id']);

        $arr_username = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $userid = $row['user_id'];
            $result_username = mysql_query("SELECT * FROM user_info where u_id = " . $userid);
            while ($row_user = mysql_fetch_array($result_username)) {
                $arr_username[$i] = $row_user['username'];
                $i++;
            }     
        }

        mysql_close($con);

        $viewInfo = array('username' => $arr_username);
        return new ViewModel($viewInfo);
    }

//上传方法
	public function uploadAction()
	{		
		if ($_FILES["file"]["error"] > 0)
		{
			$return_res = "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
			else
			{
			$file_name = "Upload: " . $_FILES["file"]["name"] . "<br />";
			$file_type = "Type: " . $_FILES["file"]["type"] . "<br />";
			$file_size = "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

			if (file_exists("d://xampp/htdocs/chyproject/sea_election/public/upload/" . $_FILES["file"]["name"]))
			{
			$return_res = $_FILES["file"]["name"] . " already exists. <br />";
			}
			else
			{
			move_uploaded_file($_FILES["file"]["tmp_name"],
			"d://xampp/htdocs/chyproject/sea_election/public/upload/" . $_FILES["file"]["name"]);
			$return_res = "Stored in: " . "upload/" . $_FILES["file"]["name"]. "<br />";
			}
		}
		$viewInfo = array('return_res' => $return_res,
			'file_name' => $file_name,
			'file_type' => $file_type,
			'file_size' => $file_size,
			'src' => "http://".$_SERVER['HTTP_HOST']."/upload/" . $_FILES["file"]["name"]);
        return new ViewModel($viewInfo);
	}
//普通用户注册页面
	public function registAction()
	{
		return new ViewModel();
	}
//主办方注册页面
	public function registhAction()
	{
		return new ViewModel();
	}
//登陆页面
	public function loginAction()
	{
		session_start();
		$con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;
		$type = $_POST['type'];
		if($type == '1')
		{

			$result = mysql_query("SELECT * FROM user_info where username='".$post_data['username']."' and password='".$post_data['password']."'");
			//var_dump("SELECT * FROM user_info where username='".$post_data['username']."' and password='".$post_data['password']."'");
			$arr_activename = array();
			$row = mysql_fetch_array($result);
			if($row['u_id'])
			{
				$_SESSION['u_id']=$row['u_id'];
				header('Location: http://'.$_SERVER['HTTP_HOST'].'/application/Index/usermain');
				exit;
			}else
			{
				$viewInfo = array('result' => '1');
			}
		}else if($type == '2')
			{
				$result = mysql_query("SELECT * FROM host where username='".$post_data['username']."' and password='".$post_data['password']."'");

				$arr_activename = array();
				$row = mysql_fetch_array($result);
				if($row)
				{
					$_SESSION['h_id']=$row['host_id'];
					header('Location: http://'.$_SERVER['HTTP_HOST'].'/application/Index/hostmain');
					exit;
				}else
				{
					$viewInfo = array('result' => '1');
				}
			}
	}
//用户主页
	public function usermainAction()
	{
		session_start();
		$con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT * FROM user_info where u_id='".$_SESSION['u_id']."'");
			
        $arr_activename = array();
		$row = mysql_fetch_array($result);
			//username, password, nickname, gender, phone, extre_info, imgsrc
			$u_id = $row['u_id'];
            $username = $row['username'];
			$nickname = $row['nickname'];
			$gender = $row['gender'];
			$phone = $row['phone'];
			$extre_info = $row['extre_info'];
			$imgsrc = "http://".$_SERVER['HTTP_HOST']."/upload/" . $row['imgsrc'];
            //$i++;
		
		$_SESSION['u_id']=$u_id;
        mysql_close($con);
        $viewInfo = array(
			'username' => $username,
			'nickname' => $nickname,
			'gender' => $gender,
			'phone' => $phone,
			'extra' => $extre_info,
			'imgsrc' => $imgsrc);
        return new ViewModel($viewInfo);
	}
//主办方主页 
	public function hostmainAction()
	{
		session_start();
		$con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT * FROM host where host_id='".$_SESSION['h_id']."'");
			
        $arr_activename = array();
		$row = mysql_fetch_array($result);
			//username, password, nickname, gender, phone, extre_info, imgsrc
			$host_id = $row['host_id'];
            $username = $row['username'];
			$phone = $row['phone'];
			$extre_info = $row['extre_info'];
			$imgsrc = "http://".$_SERVER['HTTP_HOST']."/upload/" . $row['imgsrc'];
            //$i++;
		
		$_SESSION['h_id']=$host_id;
        mysql_close($con);
        $viewInfo = array(
			'result' => '0',
			'username' => $username,
			'phone' => $phone,
			'extra' => $extre_info,
			'imgsrc' => $imgsrc);
        return new ViewModel($viewInfo);
	}
//用户编辑方法
	public function doeditAction() {
		session_start();
		$time = time();
		$username = $_POST['username'];
		$nickname = $_POST['nickname'];
		$gender = $_POST['gender'];
		$phone = $_POST['phone'];
		$extra = $_POST['extra'];
		if($_FILES["file"]["name"])
		{
		$img_arr = explode(".",$_FILES["file"]["name"]);
		$img_src = md5($time.$img_arr['0']);
		move_uploaded_file($_FILES["file"]["tmp_name"],
			"d://xampp/htdocs/chyproject/sea_election/public/upload/" . $img_src.'.'.$img_arr['1']);
		$img_add = $img_src.'.'.$img_arr['1'];

		$updatesql = "UPDATE user_info  set username = '".$username."', nickname = '".$nickname."', gender = '".$gender."', phone = '".$phone."', extre_info = '".$extra."', imgsrc = '".$img_add."' where u_id = '".$_SESSION['u_id']."'";

		}else
		{
			$updatesql = "UPDATE user_info  set username = '".$username."', nickname = '".$nickname."', gender = '".$gender."', phone = '".$phone."', extre_info = '".$extra."' where u_id = '".$_SESSION['u_id']."'";

		}
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        mysql_query($updatesql);
		$u_id = mysql_insert_id();

        mysql_close($con);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/application/Index/usermain');
		exit;
	}
//主办方编辑方法
	public function hostdoeditAction() {
		session_start();
		//var_dump($_POST);exit;
		$time = time();
		$username = $_POST['username'];
		$phone = $_POST['phone'];
		$extra = $_POST['extra'];
		if($_FILES["file"]["name"])
		{
		$img_arr = explode(".",$_FILES["file"]["name"]);
		$img_src = md5($time.$img_arr['0']);
		move_uploaded_file($_FILES["file"]["tmp_name"],
			"d://xampp/htdocs/chyproject/sea_election/public/upload/" . $img_src.'.'.$img_arr['1']);
		$img_add = $img_src.'.'.$img_arr['1'];

		$updatesql = "UPDATE host  set username = '".$username."', phone = '".$phone."', extre_info = '".$extra."', imgsrc = '".$img_add."' where host_id = '".$_SESSION['h_id']."'";

		}else
		{
			$updatesql = "UPDATE host  set username = '".$username."', phone = '".$phone."', extre_info = '".$extra."' where host_id = '".$_SESSION['h_id']."'";

		}
        $con = mysql_connect('localhost', 'root', '');
        if (!$con) {
            var_dump(mysql_error());
        }

		mysql_query("SET NAMES utf8"); 
        mysql_select_db("sea_election", $con);

        mysql_query($updatesql);
		$u_id = mysql_insert_id();

        mysql_close($con);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/application/Index/hostmain');
		exit;
	}
}

