<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class It_approve_customer extends CI_Controller{

	function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->model('model_it_approve_customer', '', true);
		$this->load->view('templates/navigation');
		$result = $this->load->model_it_approve_customer->get_all_approve_customer_list();
        //$session_data = $this->session->userdata('z_tpimx_logged_in');

		foreach($result as $row){

            $data['customers'][] = array(

                'slsno'        => $row['slsno'],
                'slsname'      => $row['slsname'],
                'custno'       => $row['custno'],
                'custname'     => $row['custname'],
                'phone'        => $row['phone'],
                'lga'          => $row['lga'],
                'state'        => $row['state'],
                'h1'           => $row['h1'],
                'h2'           => $row['h2'],
                'h3'           => $row['h3'],
                'h4'           => $row['h4'],
                'h5'           => $row['h5'],
                'h6'           => $row['h6'],
                'h7'           => $row['h7'],
                'm1'           => $row['m1'],
                'm2'           => $row['m2'],
                'm3'           => $row['m3'],
                'm4'           => $row['m4'],
                'approve_date' => $row['approve_date']

            );

        }
        $this->load->view('sfa/v_it_approve_customer', $data);

	}//-------------------------------------

    public function it_edit_and_approve_customer_sfa_live(){
        $this->load->model('model_it_approve_customer', '', true);
        $custname = $this->input->post('custname');
        $custno   = $this->input->post('custno');
        $slsname  = $this->input->post('slsname');
        $slsno    = $this->input->post('slsno');
        $date     = $this->input->post('approve_date');
        $header   = 'I.T APPROVED CUSTOMER APPROVED BY SFA SUPERVISOR';
        $result = $this->model_it_approve_customer->it_edit_and_approve_customer_sfa_live();

        if($result){
            $this->load->library('MY_phpmailer');
            $send_email = $this->model_it_approve_customer->get_supervisor_email($custno);
            //sending email-------------------
             foreach($send_email as $row){
                $body = $this->my_phpmailer->email_body_sfa_supervisor_approve_customer($header,$custname,$custno,$slsname,$slsno,$date);
                $to = $row['email'];
                $subject = "I.T APPROVED SFA CUSTOMER";
                $from_info = "SFA Euromega";
                $altbody = "";
                $cc = "";
                $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
             }
            //--------------------------------------
            $this->session->set_flashdata('success_msg', 'Customer with name '.strtoupper($custname).' and CUSTNO of '.$custno.' approved successfully !!');
        }else{
            $this->session->set_flashdata('error_msg', 'Customer with name '.strtoupper($custname).' and CUSTNO of '.$custno.' Cannot be approved, Not in live database table !!');
        }

        redirect(base_url('index.php/it_approve_customer'));
    }//--------------------------------

	function get_all_it_approve_customer(){
        $this->load->model('model_it_approve_customer', '', true);
        $this->load->view('templates/navigation');
        $customer = $this->model_it_approve_customer->get_all_it_approve_customer();

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

        $this->load->view('sfa/v_it_customer_approved', $details);

    }//-------------------------------------------

    function it_reject_customer_approval(){
        $this->load->model('model_it_approve_customer', '', true);
        $custname = $this->input->post('custname');
        $custno   = $this->input->post('custno');
        $slsname  = $this->input->post('slsname');
        $slsno    = $this->input->post('slsno');
        $date     = $this->input->post('rejected_date');
        $header   = 'I.T REJECTED CUSTOMER APPROVAL BY YOU';
        
        $this->load->library('MY_phpmailer');
        $send_email = $this->model_it_approve_customer->get_supervisor_email($custno);
        //sending email-------------------
        foreach($send_email as $row){
            $body = $this->my_phpmailer->email_body_sfa_supervisor_approve_customer($header,$custname,$custno,$slsname,$slsno,$date);
            $to = $row['email'];
            $subject = "I.T REJECT SFA CUSTOMER APPROVAL";
            $from_info = "SFA Euromega";
            $altbody = "";
            $cc = "";
            $this->my_phpmailer->send($to,$subject,$body,$altbody,$cc,$from_info);
        }
        //perform delete operation
        $result = $this->model_it_approve_customer->it_reject_customer_approval();
        //send alert message to view
        if($result){
            $this->session->set_flashdata('reject_success_msg', 'Customer with name '.strtoupper($custname).' and CUSTNO of '.$custno.' approval rejected successfully !!');
        }
        redirect(base_url('index.php/it_approve_customer'));
    }//--------------------------------------------------------


}


?>