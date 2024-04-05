<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Approve_customer extends CI_Controller{

    public function __construct(){
        parent::__construct();
        //$this->load->database();
    }

    public function index(){
        $this->load->model('model_approve_customer', '', true);
        //$this->load->view('templates/header');
        $this->load->view('templates/navigation');
        $result = $this->model_approve_customer->get_all_customer_list();
        $session_data = $this->session->userdata('z_tpimx_logged_in');

        foreach($result as $row){
          $cno = $row['custno'];
          $result2 = $this->model_approve_customer->get_approved_custno($cno);

          if($result2){
           // return false;
          }else{

            $data['customers'][] = array(

                'slsno'       => $row['slsno'],
                'slsname'     => $row['slsname'],
                'custno'      => $row['custno'],
                'custname'    => $row['custname'],
                'phone'       => $row['phone'],
                'lga'         => $row['lga'],
                'state'       => $row['state'],
                'h1'          => $row['h1'],
                'h2'          => $row['h2'],
                'h3'          => $row['h3'],
                'h4'          => $row['h4'],
                'h5'          => $row['h5'],
                'h6'          => $row['h6'],
                'h7'          => $row['h7'],
                'm1'          => $row['m1'],
                'm2'          => $row['m2'],
                'm3'          => $row['m3'],
                'm4'          => $row['m4'],
                'create_date' => $row['create_date'],
                'email_id'    => $session_data['z_tpimx_user_id']
            );

            }

        }

        $this->load->view('sfa/v_approve_customer',$data);

    }
    //------------------------------------

    function edit_and_approve_customer(){
        $this->load->model('model_approve_customer', '', true);
        $custname = $this->input->post('custname');
        $custno   = $this->input->post('custno');
        $slsname  = $this->input->post('slsname');
        $slsno    = $this->input->post('slsno');
        $date     = $this->input->post('approve_date');
        $header   = 'SFA CUSTOMER APPROVED BY SUPERVISOR';
        $result   = $this->model_approve_customer->edit_and_approve_customer();

        if($result){
            $this->load->library('MY_phpmailer');
            $send_email = $this->model_approve_customer->get_it_email();
            //sending email-------------------
             foreach($send_email as $row){
                $body = $this->my_phpmailer->email_body_sfa_supervisor_approve_customer($header,$custname,$custno,$slsname,$slsno,$date);
                $to = $row['it_email'];
                $subject = "SFA CUSTOMER APPROVAL BY SUPERVISOR WAS DONE";
                $from_info = "SFA Euromega";
                $altbody = "";
                $cc = "";
                $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
             }
            //--------------------------------------
            $this->session->set_flashdata('success_msg', 'Customer with name '.strtoupper($custname).' and CUSTNO of '.$custno.' approved successfully !!');
        }else{
            $this->session->set_flashdata('error_msg', 'Customer with name '.strtoupper($custname).' and CUSTNO of '.$custno.' already approved before !!');
        }

        redirect(base_url('index.php/approve_customer'));
    }
    //------------------------------------

    function get_all_approve_customer(){
        $this->load->model('model_approve_customer', '', true);
        $this->load->view('templates/navigation');
        $customer = $this->model_approve_customer->get_all_approve_customer();

        foreach($customer as $r){

            $details['approved_customers'][] = array(

                'slsno'       => $r['slsno'],
                'slsname'     => $r['slsname'],
                'custno'      => $r['custno'],
                'custname'    => $r['custname'],
                'phone'       => $r['phone'],
                'lga'         => $r['lga'],
                'state'       => $r['state'],
                'h1'          => $r['h1'],
                'h2'          => $r['h2'],
                'h3'          => $r['h3'],
                'h4'          => $r['h4'],
                'h5'          => $r['h5'],
                'h6'          => $r['h6'],
                'h7'          => $r['h7'],
                'm1'          => $r['m1'],
                'm2'          => $r['m2'],
                'm3'          => $r['m3'],
                'm4'          => $r['m4'],
                'approve_date' => $r['approve_date']

            );

        }

        $this->load->view('sfa/v_customer_approved', $details);

    }//-------------------------------------------



}

?>