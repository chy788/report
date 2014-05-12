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
            //echo $row['username'] . " " . $row['extra_info'];
            $arr_extra[$i] = $row['extra_info'];
            $arr_username[$i] = $row['username'];
            $i++;
            //echo "<br />";
        }

        mysql_close($con);

        //$post = $_POST;
        //var_dump($post);
        $viewInfo = array('attr_extra' => $arr_extra,
                    'attr_name' => $arr_username);
        return new ViewModel($viewInfo);
    }

}
