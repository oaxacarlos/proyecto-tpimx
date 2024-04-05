<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Insert_cust_sap_from_nimbus extends CI_Controller{
	
	function insert_cust(){
		$con1 = mysqli_connect("172.23.7.3", "root2", "r00tl4g0s","dbmobile");
		$con2 = mysqli_connect("localhost", "root", "r00tl4g0s","z_tpimx");
		
		if (!$con1) {die('Could not connect 1: ' . mysql_error());}
		$result = mysqli_query($con1,"SELECT * FROM fcustmst where typeout='01' and custno like '1%' and kodecabang like 'NGR%'; ");
		
		$query = array();
		while ($row = mysqli_fetch_array($result)) {
			$query[]= "('".implode("','", $row)."')";			
			$text = "INSERT INTO mst_cust_sap VALUES ".implode(',',$query).";";
			mysqli_query( $con2,$text);
			echo $text."<br><br>";
			unset($query);
		}
		
		
		
		
	}
	
}


?>