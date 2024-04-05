<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_email extends CI_Model{

  function insert($email_type, $doc_no, $to, $cc, $subject, $from, $added_at, $from_info, $message, $user){
    $db = $this->load->database('default', true);
    $data = array(
        "email_type"  => $email_type,
        "doc_no"      => $doc_no,
        "to"          => $to,
        "cc"          => $cc,
        "subject"     => $subject,
        "from"        => $from,
        "added_at"    => $added_at,
        "from_info"   => $from_info,
        "message"     => $message,
        "created_by"  => $user
    );

    $result = $this->db->insert('tsc_email', $data);
    if($result) return true; else return false;
  }
  //--

  function get_email_not_sent(){
      $db = $this->load->database('default', true);

      $query_temp = "select * from tsc_email t where sent is null or sent='0';";

      $query = $db->query($query_temp);
      return $query->result_array();
  }
  //--

  function update_sent($sent, $sent_at, $id){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_email set sent='".$sent."',sent_at='".$sent_at."' where id='".$id."';";
      $query = $db->query($query_temp);
      return true;
  }
  //---

  function get_data_by_period($date_from, $date_to){
      $db = $this->load->database('default', true);
      $query_temp = "select * from tsc_email t left join user u on(u.user_id=t.created_by) where date_format(added_at,'%Y-%m-%d') between '".$date_from."' and '".$date_to."';";
      $query = $db->query($query_temp);
      return $query->result_array();
  }
  //---

}
