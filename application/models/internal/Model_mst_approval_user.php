<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_mst_approval_user extends CI_Model{

    function get_next_status_by_code_status($status, $appvcode){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT status_to FROM mst_approval_user appvuser inner join mst_approval appv on(appv.approval_code=appvuser.appv_code and appv.level=appvuser.level) where status_from='".$status."' and appv_code='".$appvcode."' limit 1;";
        $query = $db->query($query_temp)->row();
        return $query->status_to;
    }
}

?>
