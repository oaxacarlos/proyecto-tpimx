<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_notif extends CI_Model{

    function insert($created_at, $created_by, $created_name, $assigned_to_id, $assigned_to_name, $message, $read, $link){
        $db = $this->load->database('default', true);
        $data = array(
            "created_at" => $created_at,
            "created_by" => $created_by,
            "created_name" => $created_name,
            "assigned_to_id" => $assigned_to_id,
            "assigned_to_name" => $assigned_to_name,
            "message" => $message,
            "readd" => $read,
            "link" => $link,
        );

        $result = $this->db->insert('tsc_notif', $data);
        if($result) return true; else return false;
    }
    //----

    function update_read_status($id,$status){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_notif set readd='".$status."' where id='".$id."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_notif_with_read_status($read, $limit, $user_assigned){
        $db = $this->load->database('default', true);
        if($limit == 0) $query_temp = "select * from tsc_notif t where readd='".$read."' and assigned_to_id='".$user_assigned."' order by created_at desc;";
        else $query_temp = "select * from tsc_notif t where readd='".$read."' and assigned_to_id='".$user_assigned."' order by created_at desc limit ".$limit.";";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function update_all_notif_as_read($id){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_notif set readd='1' where assigned_to_id='".$id."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_notif_with_limit($limit, $user_assigned){
        $db = $this->load->database('default', true);
        $query_temp = "select * from tsc_notif t where assigned_to_id='".$user_assigned."' order by created_at desc limit ".$limit.";";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

}

?>
