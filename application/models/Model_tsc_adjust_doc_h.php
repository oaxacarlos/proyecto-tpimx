<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_adjust_doc_h extends CI_Model{

    var $doc_no, $created_datetime, $created_user, $text1, $confirm_datetime, $confirm, $canceled, $canceled_datetime;

    function insert(){
        $db = $this->load->database('default', true);
        $data = array(
            "doc_no" => $this->doc_no,
            "created_datetime" => $this->created_datetime,
            "created_user" => $this->created_user,
            "text1" => $this->text1,
            "confirm" => $this->confirm,
        );

        $result = $this->db->insert('tsc_adjust_doc_h', $data);
        if($result) return true; else return false;
    }
    //---

    function get_list(){
        $db = $this->load->database('default', true);
        $query_temp="SELECT doc_no, created_datetime, created_user, text1, confirm, confirm_datetime,canceled, canceled_datetime,name
          FROM tsc_adjust_doc_h h inner join user u on(u.user_id=h.created_user)
          order by doc_no desc;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_list_one_doc($doc_no){
        $db = $this->load->database('default', true);
        $query_temp="SELECT doc_no, created_datetime, created_user, text1, confirm, confirm_datetime,canceled, canceled_datetime
          FROM tsc_adjust_doc_h h where doc_no='".$doc_no."'";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_confirmed(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_adjust_doc_h set confirm=1, confirm_datetime='".$this->confirm_datetime."'  where doc_no='".$this->doc_no."';";

        $query = $db->query($query_temp);
        return $query;
    }
    //--

    function update_cancel(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_adjust_doc_h set canceled=1, canceled_datetime='".$this->canceled_datetime."'  where doc_no='".$this->doc_no."';";

        $query = $db->query($query_temp);
        return $query;
    }
    //---

    //2023-06-22
    function update_confirmed2(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_adjust_doc_h set confirm=".$this->confirm.", confirm_datetime='".$this->confirm_datetime."'  where doc_no='".$this->doc_no."';";

        $query = $db->query($query_temp);
        return $query;
    }
    //--
}

?>
