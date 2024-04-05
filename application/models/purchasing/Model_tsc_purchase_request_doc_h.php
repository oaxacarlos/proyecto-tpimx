<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_purchase_request_doc_h extends CI_Model{

   var $doc_no, $doc_creation_datetime, $doc_datetime, $request_by, $delivery_to, $shopping_purpose, $urgent, $delivery_deadline, $id_statuss, $doc_location_code, $from_department, $remarks;
      function insert_h(){

        $db2 = $this->load->database('tpimx_purchasing', true);
        $data = array(
            "doc_no" => $this->doc_no,
            "doc_creation_datetime" => $this->doc_creation_datetime,
            "doc_datetime" => $this->doc_datetime,
            "request_by" => $this->request_by,
            "delivery_to" => $this->delivery_to,
            "shopping_purpose" => $this->shopping_purpose,
            "urgent" => $this->urgent,
            "delivery_deadline" => $this->delivery_deadline,
            "id_statuss" => $this->id_statuss,
            "doc_location_code" => $this->doc_location_code,
            "from_department" => $this->from_department,
            "remarks" => $this->remarks,
        );
        
        $result = $db2->insert('purchase_request_doc_h', $data);
        
        if($result) return true; else return false;
    }
}
?>