<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_purchase_request_doc_d extends CI_Model{
var $doc_no, $line, $description, $qty, $oum, $request_img, $request_link, $remarks, $src_creation_datetime;
function insert_d(){
    $db2 = $this->load->database('tpimx_purchasing', true);
    $data = array(
        "doc_no" => $this->doc_no,
        "line_no" => $this->line_no,
        "item_code" => $this->item_code,
        "description" => $this->description,
        "qty" => $this-> qty,
        "uom" => $this-> uom,
        "src_loc" => $this-> src_loc,
        "request_img" => $this-> request_img,
        "request_link" => $this-> request_link,
        "remarks" => $this-> remarks,
        "src_creation_datetime" => $this-> src_creation_datetime,
    );
    $result = $db2->insert('purchase_request_doc_d', $data);
    if ($result) return true; else return false;
}

}
?>