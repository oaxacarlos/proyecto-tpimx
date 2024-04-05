<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_price_analysis extends CI_Model{

    function get_mst_item_nav(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT code as item_no,name,uom,unit_costt from mst_item";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_detail_price_by_item_no($year, $item_code){
          $db = $this->load->database('default2', true);
          $query_temp = "SELECT no,unit_price,sum(quantity) as qty, ";

          foreach($year as $row){ $query_temp.="sum(if(year(posting_date)='".$row."',quantity,0)) as qty_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" FROM sales_invoice_line s where no='".$item_code."' and unit_price > 1";

          $query_temp.=" and year(posting_date) in ( ";
          foreach($year as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=" ) ";

          $query_temp.=" group by no,unit_price;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //--

    function get_total_qty_by_year($year, $item_code){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT no,sum(quantity) as qty, ";

        foreach($year as $row){ $query_temp.="sum(if(year(posting_date)='".$row."',quantity,0)) as qty_".$row.","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM sales_invoice_line s where no='".$item_code."' and unit_price > 1";

        $query_temp.=" and year(posting_date) in ( ";
        foreach($year as $row){ $query_temp.="'".$row."',"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_cross_reference($item_code){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM catalogo_1.catalogo_f c
            where filtro_de_aceite_sakura like '".$item_code."%'
            or filtro_de_aire_sakura like '".$item_code."%'
            or filtro_de_combustible_sakura like '".$item_code."%'
            or filtro_de_cabina_sakura like '".$item_code."%' order by brand,anio,modelo,no_cli;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----
}
