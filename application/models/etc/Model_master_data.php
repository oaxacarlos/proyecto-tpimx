<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_mster_data extends CI_Model{

    function list_all_movement_type(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_movt_type where active = 'Y'");
      return $query->result_array();
    }
    //--------------------

    function list_all_employee(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_employee;");
      return $query->result_array();
    }
    //--------------------

}
