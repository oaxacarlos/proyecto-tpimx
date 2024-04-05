<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_history extends CI_Model{

  function insert($doc_no, $created_at, $created_by, $status, $remarks){

    $db = $this->load->database('default_oprc', true);
    $data = array(
        "doc_no" => $doc_no,
        "created_at" => $created_at,
        "created_by" => $created_by,
        "remarks" => $remarks,
        "statuss" => $status,
    );

    $result = $db->insert('tsc_delivery_history', $data);
    if($result) return true; else return false;
  }
  //---
}

?>
