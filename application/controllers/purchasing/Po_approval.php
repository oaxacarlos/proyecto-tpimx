<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Po_approval extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('model_mst_location', '', true);
        $this->load->model('model_login', '', true);
        $this->load->model('purchasing/model_config_po', 'model_config_po', true);
        $this->load->model('model_admin', '', true);
        $this->load->model('model_zlog', '', true);
        $this->load->model('purchasing/Model_tsc_po_history', 'model_tsc_po_history', true);
        $this->load->model('purchasing/Model_tsc_po_approval','model_tsc_approval',TRUE);
        $this->load->model('purchasing/model_item', 'model_item', true); 

        
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
            $this->load->view('purchasing/view_po_approval');
        }
    }
    public function get_list_po_approval()
    {
        $status = ["1"];
        $level_id = $this->model_tsc_approval->get_level_user();
        foreach ($level_id as $row) {  $level_user.="'".$row['level_user']."',"; }
        $level_user = substr($level_user,0,-1);
        $result = $this->model_tsc_approval->list_approval_h_by_status($status,$level_user);
        $data['var_po_list'] = assign_data($result);
        $data['var_status']= $status;
        $this->load->view('purchasing/view_po_list',$data);
    }
    public function get_details_po(){
        $id_statuss = $_POST['id_statuss'];
        $doc_no = $_POST['doc_no'];
        $result = $this->model_tsc_approval->list_approval_d_by_doc($doc_no);
        $data['doc_no'] = $doc_no;
        $data['id_statuss'] = $id_statuss;
        $data['var_d_approval'] = assign_data($result);
        $this->load->view('purchasing/v_po_details',$data);
    }
    public function update_approval_po(){
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user_id = $session_data['z_tpimx_user_id'];
        $id_statuss = '2';
        $doc_no = $_POST['doc_no'];
        $result = $this->model_tsc_approval->id_status= $id_statuss;
        $result = $this->model_tsc_approval->doc_no= $doc_no;
        $result = $this->model_tsc_approval->update_approval_po_by_doc();
        $h_remarks = "aproval type: ".$id_statuss;

        $this->model_tsc_po_history->insert($doc_no, $id_statuss, $h_remarks, '', ''); // input history
        if($result){
            $response['status'] = "1";
            $response['msg'] = "Updated Doc ".$doc_no;
            echo json_encode($response);
        } else{
            $response['status'] = "0";
            $response['msg'] = "Error";
            echo json_encode($response);
        }
    }
    public function cancel_doc_po(){
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user_id = $session_data['z_tpimx_user_id'];
        $doc_no = $_POST['doc_no'];
        $id_statuss = $_POST['id_status'];
        $message = "Canceled: ".$_POST['message'];
        $result = $this->model_tsc_approval->doc_no= $doc_no;
        $result = $this->model_tsc_approval->canceled_text= $message;
        $result = $this->model_tsc_approval->id_user= $user_id;
        $result = $this->model_tsc_approval->status_canceled= '1';
        $result = $this->model_tsc_approval->datetime = get_datetime_now();
        $result = $this->model_tsc_approval->cancel_po_by_user();

        $this->model_tsc_po_history->insert($doc_no, $id_statuss, $message, '', ''); // input history
        if($result){
            $response['status'] = "1";
            $response['msg'] = "Canceled Doc ".$doc_no;
            echo json_encode($response);
        } else{
            $response['status'] = "0";
            $response['msg'] = "Error";
            echo json_encode($response);
        }
    }
}
?>