<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Purchase_orders extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('model_mst_location', '', true);
        $this->load->model('model_admin', '', true);
        $this->load->model('purchasing/Model_tsc_po_approval','model_tsc_approval',TRUE);
    }
    public function index()
    {
        $this->load->view('templates/navigation');

        if (!isset($_SESSION['menus_list_user'][$this->config->item('purchasing_folder').'po_request'])) {
            $this->load->view('view_home');
        } else {
            $data['var_location'] = assign_data($this->model_mst_location->get_data2());
            $data['user_list'] = assign_data($result);
            $result = $this->model_admin->list_department();
            $this->load->view('purchasing/view_purchase_orders');
        }
    }
    public function get_list_po(){
        $status = ["2"];
        $level_id = $this->model_tsc_approval->get_level_user();
        foreach ($level_id as $row) {  $level_user.="'".$row['level_user']."',"; }
        $level_user = substr($level_user,0,-1);
        $result = $this->model_tsc_approval->list_approval_h_by_status($status,$level_user);
        $data['var_po_list'] = assign_data($result);
        $data['var_status']= $status;
        $this->load->view('purchasing/view_po_list',$data);
    } 
}
?>