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

    public function getpostAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

        // mysql_query("INSERT INTO user_info (u_id, username, extra_info) VALUES ('', 'chy', '123123')");

        $result = mysql_query("SELECT * FROM user_info");

        $arr_username = array();
        $arr_extra = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $arr_extra[$i] = $row['extra_info'];
            $arr_username[$i] = $row['username'];
            $i++;
        }

        mysql_close($con);

        $viewInfo = array('attr_extra' => $arr_extra,
            'attr_name' => $arr_username);
        return new ViewModel($viewInfo);
    }

    public function createhostAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

        $post_data = $_POST;

        mysql_query("INSERT INTO host (host_id, host_name, host_extra) VALUES ('', '" . $post_data["username"] . "', '" . $post_data["extrainfo"] . "')");

        $host_id = mysql_insert_id();

        mysql_close($con);

        $viewInfo = array('username' => $post_data["username"],
            'extrainfo' => $post_data["extrainfo"],
            'hostid' => $host_id);
        return new ViewModel($viewInfo);
    }

    public function createactiveAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

        $post_data = $_POST;

        var_dump($post_data);

        mysql_query("INSERT INTO host_active (active_id, active_name, active_extra, host_id) VALUES ('', '" . $post_data["username"] . "', '" . $post_data["extrainfo"] . "', '" . $post_data["hostid"] . "')");

        mysql_close($con);

        $viewInfo = array('username' => $post_data["username"],
            'extrainfo' => $post_data["extrainfo"],
            'hostid' => $post_data["hostid"]);
        return new ViewModel($viewInfo);
    }

    public function showhostinfoAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

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

    public function createuserAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

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

    public function signinAction() {

        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT * FROM host_active");

        $arr_activename = array();
        $arr_activeid = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $arr_activename[$i] = $row['active_name'];
            $arr_activeid[$i] = $row['active_id'];
            $i++;
        }

        mysql_close($con);

        $viewInfo = array('activename' => $arr_activename,
            'activeid' => $arr_activeid,
            'flag' => $i);
        return new ViewModel($viewInfo);
    }

    public function signprocessAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

        $post_data = $_POST;

        mysql_query("INSERT INTO relation (u_id, active_id) VALUES ('" . $post_data["userid"] . "', '" . $post_data["activeid"] . "')");

        $user_id = mysql_insert_id();

        mysql_close($con);

        $viewInfo = array('result' => 'success');

        return new ViewModel($viewInfo);
    }

    public function searchactiveAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT distinct * FROM relation where u_id = " . $post_data['userid']);

        $arr_activename = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $activeid = $row['active_id'];
            $result_activename = mysql_query("SELECT * FROM host_active where active_id = " . $activeid);
            while ($row_active = mysql_fetch_array($result_activename)) {
                $arr_activename[$i] = $row_active['active_name'];
                $i++;
            }     
        }

        mysql_close($con);

        $viewInfo = array('activename' => $arr_activename);
        return new ViewModel($viewInfo);
    }
    
    public function searchuserAction() {
        $con = mysql_connect('localhost', 'root', 'epals');
        if (!$con) {
            var_dump(mysql_error());
        }

        mysql_select_db("sea_selection", $con);

        $post_data = $_POST;

        $result = mysql_query("SELECT distinct * FROM relation where active_id = " . $post_data['activeid']);

        $arr_username = array();

        $i = '0';

        while ($row = mysql_fetch_array($result)) {
            $userid = $row['u_id'];
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

}
