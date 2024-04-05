<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Po_process extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->database();
        $this->load->model('model_login', '', true);
        $this->load->model('model_admin', '', true);
    }

    public function proces_po_by_doc(){ 
        $this->load->view('templates/navigation');
        if (!isset($_SESSION['menus_list_user'][$this->config->item('purchasing_folder').'po_request'])) {
            $this->load->view('view_home');
        } else {
            $doc_no = $_POST['doc_no'];
            $response['doc_no'] = $doc_no;
            echo json_encode($response);
        }
    }
    public function proces_doc_view(){
        $doc_no = $_GET['doc_no'];
        $data['doc_no'] = $doc_no;
        print_r($doc_no);
        $this->load->view('purchasing/v_po_details',$data);
    }
}
?>