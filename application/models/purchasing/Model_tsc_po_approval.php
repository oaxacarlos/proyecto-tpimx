<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_po_approval extends CI_Model{
  var $id_status, $doc_no, $datetime, $canceled_text, $status_canceled, $id_user;

   function get_level_user(){
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id']; // carga de id_usuario para verificar su nivel de aprovacion
        $db2 = $this->load->database('tpimx_purchasing', true);
        $query_temp = "SELECT level_user FROM approval_level a where id_user='".$user."'";
        $query = $db2->query($query_temp);
        return $query->result_array();
   }
   function list_approval_h_by_status($status,$level_id){
        
        
        $db2 = $this->load->database('tpimx_purchasing', true);
        $query_temp = "SELECT h.doc_creation_datetime, h.doc_no, h.id_statuss, h.doc_location_code,u.name, h.shopping_purpose, h.urgent, sum(qty) as qty_t FROM purchase_request_doc_h h
        INNER JOIN purchase_request_doc_d d ON (d.doc_no = h.doc_no)
        INNER JOIN tpimx_wms.user u ON (h.request_by = u.user_id)
        INNER JOIN approval_level a ON (u.user_id = a.id_user)";
        $query_temp.=" where h.canceled is null and h.id_statuss in( ";
        foreach($status as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        $query_temp.="AND h.id_statuss in(".$level_id.")";
        $user_plant = get_plant_code_user();
        $query_temp.=" and h.doc_location_code in(".$user_plant.") ";
        $query_temp.="GROUP BY h.doc_no,h.doc_creation_datetime, h.doc_location_code;";
        $query = $db2->query($query_temp);
        return $query->result_array();
    }

    function list_approval_d_by_doc($doc_no){
        $db2 = $this->load->database('tpimx_purchasing', true);
        $query_temp = "SELECT d.doc_no,d.src_loc,d.item_code, d.description, d.qty, u.name as delivery_to, d.uom, d.request_img, d.request_link, d.remarks FROM purchase_request_doc_d d
        INNER JOIN purchase_request_doc_h h on(h.doc_no = d.doc_no)
        INNER JOIN tpimx_wms.user u ON (h.delivery_to = u.user_id)";
        $query_temp.=" where h.canceled is null AND d.doc_no = ";
        $query_temp.="'".$doc_no."'";
        // $query_temp.="";
        $user_plant = get_plant_code_user();
        $query_temp.=" and h.doc_location_code in(".$user_plant.") ";
        $query_temp.=";";
        $query = $db2->query($query_temp);
        return $query->result_array();
    }
    function update_approval_po_by_doc(){
        $db2 = $this->load->database('tpimx_purchasing', true);
        $query_temp = "update purchase_request_doc_h t set id_statuss='".$this->id_status."' WHERE doc_no='".$this->doc_no."';";
        $query = $db2->query($query_temp);
      return true;
    }
    function cancel_po_by_user(){
        $db2 = $this->load->database('tpimx_purchasing', true);
        $query_temp = "update purchase_request_doc_h  set canceled='".$this->status_canceled."', canceled_user_id='".$this->id_user."', canceled_text='".$this->canceled_text."', canceled_datetime='".$this->datetime."'  WHERE doc_no='".$this->doc_no."';";
        $query = $db2->query($query_temp);
      return true;
    }
}
?>