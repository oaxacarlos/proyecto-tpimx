<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_po_history extends CI_Model{

   var $doc_no, $doc_creation_datetime,$id_statuss, $remarks, $compration_doc, $reception_doc;
   
      function insert($doc_no,$id_statuss, $remarks, $compration_doc, $reception_doc){
        $db2 = $this->load->database('tpimx_purchasing', true);
        
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];
        $doc_creation_datetime = get_datetime_now();
        $data = array(
          "doc_no_h"=> $doc_no,
          "id_statuss"=> $id_statuss,
          "creation_datetime"=> $doc_creation_datetime,
          "id_user"=> $user,
          "remarks"=> $remarks,
          "comparation_doc"=> $compration_doc,
          "reception_doc"=>$reception_doc,
        );
        $result = $db2->insert('purchase_history', $data);
    }
}
?>