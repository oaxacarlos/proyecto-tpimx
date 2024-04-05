<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_redeem extends CI_Model{

    function get_redeem_not_completed_yet(){
        $db = $this->load->database('default_client', true);
          $query_temp = "SELECT doc_no, r.user_id,u.name, u.email,created_at, point_redeem, lastest_point, remain_point, r.qty, r.remark, buy_at, buy_text, buy_verified_by, sent_text, sent_verified_by, sent_at,
          delivered_at, delivered_verified_by,buy_date,sent_date,delivered_date, r.product, product.product_name,r.addr_name, r.addr_contact, r.addr_phone, r.addr_add, r.addr_add2, r.addr_colonia, r.addr_ciudad, r.addr_estado, r.addr_postcode, r.addr_country
          FROM tsc_redeem r inner join user u on(r.user_id=u.user_id)
          inner join mst_product product on(product.product_id = r.product)
          where delivered_at is null;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function update_buy($buy_at, $buy_text, $buy_verified_by, $buy_date, $doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_redeem set buy_at='".$buy_at."', buy_text='".$buy_text."', buy_verified_by='".$buy_verified_by."', buy_date='".$buy_date."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //--

    function update_sent($sent_at, $sent_text, $sent_verified_by, $sent_date, $doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_redeem set sent_at='".$sent_at."', sent_text='".$sent_text."', sent_verified_by='".$sent_verified_by."', sent_date='".$sent_date."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function update_delivered($delivered_at, $delivered_verified_by, $delivered_date, $doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "update tsc_redeem set delivered_at='".$delivered_at."', delivered_verified_by='".$delivered_verified_by."', delivered_date='".$delivered_date."' where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_redeem_report($from, $to){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT doc_no, redeem.user_id, redeem.created_at, product, product_name, point_redeem, lastest_point, remain_point, product.link_image,redeem.qty,
        u.name, u.email, buy_date, sent_date, delivered_date
          FROM tsc_redeem redeem
          inner join mst_product product on(redeem.product = product.product_id)
          inner join user u on(u.user_id = redeem.user_id)
          where date_format(redeem.created_at,'%Y-%m-%d') between '".$from."' and '".$to."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_redeem_detail_by_doc_no($doc_no){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT * FROM tsc_redeem r
          inner join user u on(r.user_id = u.user_id)
          inner join mst_product p on (p.product_id = r.product)
          where doc_no='".$doc_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_total_count_not_redeem(){
        $db = $this->load->database('default_client', true);
        $query_temp = "SELECT count(doc_no) as total FROM tsc_redeem t where delivered_at is null;";
        $query = $db->query($query_temp);
        $total = $query->row()->total;
        return $total;
    }
    //--
}

?>
