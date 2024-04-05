<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_doc_history extends CI_Model{

    var $doc1, $doc2, $doc3, $status1, $status2, $created_datetime, $text1, $text2;

    function insert($doc1, $doc2, $doc3, $status1, $status2, $created_datetime, $text1, $text2){
            $db = $this->load->database('default', true);

            $session_data = $this->session->userdata('z_tpimx_logged_in');
            $user = $session_data['z_tpimx_user_id'];

            $data = array(
                "doc1" => $doc1,
                "doc2" => $doc2,
                "doc3" => $doc3,
                "status1" => $status1,
                "status2" => $status2,
                "created_datetime" => $created_datetime,
                "text1" => $text1,
                "text2" => $text2,
                "userid" => $user,
            );

            $result = $this->db->insert('tsc_doc_history', $data);
    }
    //---

    function get_whsreceipt_whshipment(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2, name FROM tsc_doc_history t left join user u on(u.user_id=t.userid) where doc1='".$this->doc1."' and doc2='' and status1='1';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function get_received(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2 FROM tsc_doc_history t
        where doc2='".$this->doc2."' and status1 in ('2','3');";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_received_verified($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2 FROM tsc_doc_history t ";

        // where condition
        $query_temp.=" where doc1 in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        //---
        $query_temp.=" and status1 in('4');";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_gen_sn($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2 FROM tsc_doc_history t ";

        // where condition
        $query_temp.=" where doc1 in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        //---
        $query_temp.=" and status1 in('5');";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_put_away($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2 FROM tsc_doc_history t ";

        // where condition
        $query_temp.=" where doc2 in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        //---
        $query_temp.=" and status1 in('6') group by doc1, doc2 order by created_datetime";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_put_away_finished($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2 FROM tsc_doc_history t ";

        // where condition
        $query_temp.=" where doc1 in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        //---
        $query_temp.=" and status1 in('7') group by doc1, doc2 order by created_datetime";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_whsreceipt_release(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2 FROM tsc_doc_history t where doc1='".$this->doc1."' and status1='8';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function get_submit_to_nav(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2, name FROM tsc_doc_history t
        left join user u on(u.user_id=t.userid)
        where doc1='".$this->doc1."' and status1='16';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function get_picking(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,t.created_datetime,t.text1,t.text2, assign_user, u.name, start_datetime
        FROM tsc_doc_history t
        left join tsc_pick_h pick_h on(pick_h.doc_no=t.doc1)
        left join user u on(u.user_id=pick_h.assign_user)
        where doc2='".$this->doc2."' and status1 in ('1');";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_picking_finished($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2, name FROM tsc_doc_history t left join user u on(u.user_id=t.userid)";

        // where condition
        $query_temp.=" where doc1 in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        //---
        $query_temp.=" and status1 in('12') group by doc1, doc2 order by created_datetime";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_qc($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2, name FROM tsc_doc_history t
        left join user u on(u.user_id=t.userid)
        where doc1='".$doc_no."' and status1='13';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function get_packing($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc1,doc2,doc3,status1,status2,created_datetime,text1,text2, name FROM tsc_doc_history t
        left join user u on(u.user_id=t.userid)
        where doc2='".$doc_no."' and status1='13' and doc1 like 'PAK%';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    // 2023-06-08
    function get_created_picking(){
        $db = $this->load->database('default', true);
        $query_temp = "select doc1,doc2,doc3,status1,status2,t.created_datetime,t.text1,t.text2,u.name
        FROM tsc_doc_history t
        left join user u on(u.user_id=t.userid)
        where doc2='".$this->doc2."' and status1 in ('1');";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

}

?>
