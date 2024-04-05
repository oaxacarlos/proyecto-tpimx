<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Catalog extends CI_Controller{

    function index(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'catalog'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->model('model_zlog','',TRUE);
            $this->model_zlog->insert("Catalog"); // insert log

            $this->load->view('sales/catalog/v_index', $data);
        }


    }
}
