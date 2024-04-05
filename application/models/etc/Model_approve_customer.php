<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_approve_customer extends CI_Model{

    public function get_all_customer_list(){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select tbl_route.kodecabang as depotcode,msc.ket as depotname,tbl_route.slsno,slsname,
        tbl_route.custno,custname,custadd1,sm_hp1 as phone,kelurahan as lga,kecamatan as state,
        h1,h2,h3,h4,h5,h6,h7,m1,m2,m3,m4,create_date,create_by,update_by
        from(
        SELECT slsno,custno,h1,h2,h3,h4,h5,h6,h7,m1,m2,m3,m4,kodecabang,create_date,create_by,update_by
        FROM frute where create_by='webservice' and update_by='jobs'
        ) as tbl_route
        inner join fsalesman sls on(tbl_route.slsno=sls.slsno and tbl_route.kodecabang=sls.kodecabang)
        inner join fcustmst cust on(tbl_route.custno=cust.custno and tbl_route.kodecabang=cust.kodecabang)
        inner join m_scabang msc on(tbl_route.kodecabang=msc.kodescabang) order by create_date desc
        ");
        return $query->result_array();
    }//-----------------------------------------------------

    public function edit_and_approve_customer(){
        $db = $this->load->database('default', true);
        $custno = $this->input->post('custno');

        $getid = $db->where('custno', $custno);
        $chk = $db->get('sfa_approve_customer', $getid);
        if($chk->num_rows() > 0){
            return false;
        }else{

            $data = array(

                'slsno' => $this->input->post('slsno'),
                'slsname' => $this->input->post('slsname'),
                'custno' => $this->input->post('custno'),
                'custname' => $this->input->post('custname'),
                'phone' => $this->input->post('phone'),
                'lga' => $this->input->post('lga'),
                'state' => $this->input->post('state'),
                'h1' => strtoupper($this->input->post('monday')),
                'h2' => strtoupper($this->input->post('tuesday')),
                'h3' => strtoupper($this->input->post('wednesday')),
                'h4' => strtoupper($this->input->post('thursday')),
                'h5' => strtoupper($this->input->post('friday')),
                'h6' => strtoupper($this->input->post('saturday')),
                'h7' => strtoupper($this->input->post('sunday')),
                'm1' => strtoupper($this->input->post('week1')),
                'm2' => strtoupper($this->input->post('week2')),
                'm3' => strtoupper($this->input->post('week3')),
                'm4' => strtoupper($this->input->post('week4')),
                'approve_date' => $this->input->post('approve_date'),
                'supervisor' => $this->input->post('email_id'),
                'created_date' => $this->input->post('created_date')

            );

            $result = $db->insert('sfa_approve_customer', $data);
            if($result){
                return true;
            }else{
                return false;
            }
        }

    }//--------------------------------

    public function get_all_approve_customer(){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from sfa_approve_customer order by approve_date desc");
        return $query->result_array();
    }//-------------------------------

    public function get_approved_custno($custno){
        $db = $this->load->database('default', true);
        $query2 = $db->query("select * from sfa_approve_customer where custno = '$custno'");
        return $query2->result_array();
    }//---------------------------------

    public function get_it_email(){
        $db = $this->load->database('default', true);
        $query = $db->query("select it_email from sfa_email_list");
        return $query->result_array();
    }//---------------------------------



}

?>