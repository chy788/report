<?php
/*
	header("Accept-Ranges: bytes");  
    header("Content-type: application/vnd.ms-excel");  
    header("Content-Disposition: attachment; filename=".$filename);  
 */
	header("Content-Type: application/download");
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:filename=111.xls");
    mysql_connect('localhost','root','') or die('mysql connected fail');  
    mysql_select_db('sea_election');  
      
    $sql = "select * from host";  
    $query = mysql_query($sql);  

	//u_id	username	password	nickname	gender	phone	extre_info
      
    echo "id\tusername\nickname\gender\phone\extre_info";
    while($row = mysql_fetch_array($query)){  
        echo "\n";  
        echo $row['host_id']."\t".$row['username']."\t".$row['nickname']."\t".$row['gender']."\t".$row['phone']."\t".$row['extre_info'];  
    }  

