<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_it_approve_customer extends CI_Model{

    public function get_all_approve_customer_list(){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from sfa_approve_customer sac where custno not in (select custno from sfa_it_dev_approve_customer) order by sac.approve_date desc");
        return $query->result_array();
    }//-----------------------------------------------------

    public function it_edit_and_approve_customer_sfa_live(){
        $db2 = $this->load->database('sfa_live', true); //live database
        $db = $this->load->database('default', true); //local database

        $custno = $this->input->post('custno');

        $getid = $db2->where('CUSTNO', $custno);
        $chk = $db2->get('frute', $getid); //checking if custno exist in table frute on live table

        if($chk->num_rows() < 1){
            
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
                'created_date' => $this->input->post('created_date')

            );
            $result = $db->insert('sfa_it_dev_approve_customer', $data);
            //end of inserting into table on default

            $data_insert_live = array(

                'SLSNO' => $this->input->post('slsno'),
                'NORUTE' => '1',
                'CUSTNO' => $this->input->post('custno'),
                'H1' => strtoupper($this->input->post('monday')),
                'H2' => strtoupper($this->input->post('tuesday')),
                'H3' => strtoupper($this->input->post('wednesday')),
                'H4' => strtoupper($this->input->post('thursday')),
                'H5' => strtoupper($this->input->post('friday')),
                'H6' => strtoupper($this->input->post('saturday')),
                'H7' => strtoupper($this->input->post('sunday')),
                'M1' => strtoupper($this->input->post('week1')),
                'M2' => strtoupper($this->input->post('week2')),
                'M3' => strtoupper($this->input->post('week3')),
                'M4' => strtoupper($this->input->post('week4')),
                'S1' => '',
                'S2' => '',
                'S3' => '',
                'S4' => '',
                'S5' => '',
                'S6' => '',
                'TP' => '',
                'KODECABANG' => 'D111',
                'TYPEOUT' => '',
                'CLIMIT' => '',
                'RPP' => '',
                'LDATETRS' => '',
                'LSALES' => '',
                'REGION' => 'D100',
                'CABANG' => 'D110',
                'CREATE_DATE' => $this->input->post('created_date'),
                'CREATE_BY' => 'webservice',
                'UPDATE_DATE' => $this->input->post('approve_date'),
                'UPDATE_BY' => $this->input->post('admin')

            );
            $result2 = $db2->insert('frute', $data_insert_live);
            if($result2){
                return true;
            }else{
                return false;
            }
            //----end of inserting deleted customer from live table back with updated schedule
            
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
                'created_date' => $this->input->post('created_date')

            );
            $result = $db->insert('sfa_it_dev_approve_customer', $data);
            //end of inserting to local table on 7.65

            $data_dev_live = array(

                'H1' => strtoupper($this->input->post('monday')),
                'H2' => strtoupper($this->input->post('tuesday')),
                'H3' => strtoupper($this->input->post('wednesday')),
                'H4' => strtoupper($this->input->post('thursday')),
                'H5' => strtoupper($this->input->post('friday')),
                'H6' => strtoupper($this->input->post('saturday')),
                'H7' => strtoupper($this->input->post('sunday')),
                'M1' => strtoupper($this->input->post('week1')),
                'M2' => strtoupper($this->input->post('week2')),
                'M3' => strtoupper($this->input->post('week3')),
                'M4' => strtoupper($this->input->post('week4')),
                'UPDATE_BY' => $this->input->post('admin')

            );
            /*$query = "update frute set H1='.$this->input->post('monday').', H2='.$this->input->post('monday').'
            where custno='".$getid."'";*/

            $getid = $db2->where('CUSTNO', $custno);
            $update = $db2->update('frute', $data_dev_live);

            if($update){
                return true;
            }else{
                return false;
            }

        }//end of else

    }//---------------------------

     public function get_all_it_approve_customer(){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from sfa_it_dev_approve_customer order by approve_date desc");
        return $query->result_array();
    }//---------------------------

    public function get_supervisor_email($custno){
        $db = $this->load->database('default', true);
        $query = $db->query("select supervisor from sfa_approve_customer where custno = '$custno'");

        if($query->num_rows() > 0){
            $email_id = $query->row()->supervisor;
            $q2 = $db->query("select * from user where user_id = '$email_id'");
            return $q2->result_array();
        }
        //return $query->result_array();
    }//---------------------------------

    public function it_reject_customer_approval(){
        $db = $this->load->database('default', true);
        $custno = $this->input->post('custno');
        $result = $db->query("delete from sfa_approve_customer where custno = '$custno'");
        if($result){
            return true;
        }else{
            return false;
        }
    }//------------------------------------


}

?>