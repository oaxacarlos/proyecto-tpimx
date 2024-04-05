<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Test extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('purchasing/Model_test','model_p_test',TRUE);
    }
   
function index(){
    $this->load->view('templates/navigation');
    $result = $this->model_p_test->test_get();
    echo count($result);
    $data["var_data"] = assign_data($result);
    
    $this->load->view('purchasing/test',$data);
    
  }
}