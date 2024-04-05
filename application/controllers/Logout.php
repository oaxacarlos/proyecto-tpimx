<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start(); //we need to call PHP's session object to access it through CI
class Logout extends CI_Controller {

	function __construct()
	{
	   parent::__construct();
		 $this->load->model('model_zlog','',TRUE);
	}
	function index()
	{
			$this->model_zlog->insert("Logout"); // insert log
		  $this->session->unset_userdata('z_tpimx_logged_in');
	   	session_destroy();
	   	redirect('login', 'refresh');
	}


}

?>
