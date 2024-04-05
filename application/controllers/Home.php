<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Home extends CI_Controller
{

    function index(){
        $this->load->view('templates/navigation');
        $this-> load->view('view_home');
    }
    //---

    

}

?>
