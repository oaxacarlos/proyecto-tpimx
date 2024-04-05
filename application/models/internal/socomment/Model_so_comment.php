<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_so_comment extends CI_Model{

    function get_so_by_comment($comment){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select [no_] as no, [comment] as comment from [".$this->config->item('sqlserver_pref')."Sales Comment Line] where [Comment] like '%".$comment."%' and [Document Type]='1'; ";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--
}
