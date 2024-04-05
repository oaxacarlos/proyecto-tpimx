<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_operacion_report extends CI_Model{

    function get_total_qty_value_by_postcode_from_to($from, $to){
        $db = $this->load->database('default2', true);

        $query_temp="select id, round(sum(line_amount)/1000) as amount,sum(qty) as qty from(
          select tbl_sales.no,tbl_sales.ship_to_post_code,qty,line_amount, line_no,id from (
          SELECT h.no as no, h.bill_to_customer_no, h.ship_to_post_code,d.quantity as qty, d.line_amount as line_amount,line_no
                    FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                    where h.posting_date between '".$from."' and '".$to."' ) as tbl_sales

          inner join mst_map_postcode pscd on(tbl_sales.ship_to_post_code=pscd.post_code)) as tbl_sales_geo group by id";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_consigment_data_period($period, $date_from, $date_to, $consign, $concat, $customer){

        $db = $this->load->database('default2', true);

        $query_temp = "select bin_code,item_no, qty_initial, ";
        foreach($period as $row){
            $query_temp.="qty_in_".$row['year']."_".$row['month'].",";
            $query_temp.="qty_out_".$row['year']."_".$row['month'].",";
        }
        $query_temp.="qty_initial";
        foreach($period as $row){
            $query_temp.="+qty_in_".$row['year']."_".$row['month']."+qty_out_".$row['year']."_".$row['month'];
        }
        $query_temp.=" as qty_ending_balance, ";

        foreach($period as $row){
            $query_temp.="if(amount_".$row['year']."_".$row['month']." is null,0,round(amount_".$row['year']."_".$row['month'].",2)) as amount_".$row['year']."_".$row['month'].",";
        }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" from(
          SELECT bin_code,item_no, ";

        $query_temp.="sum(if(registering_date < '".$date_from."',qty,0)) as qty_initial,";
        foreach($period as $row){
            $query_temp.="sum(if(registering_date between '".$row['from']."' and '".$row['to']."' and qty > 0,qty,0)) as qty_in_".$row['year']."_".$row['month'].",";
            $query_temp.="sum(if(registering_date between '".$row['from']."' and '".$row['to']."' and qty < 0,qty,0)) as qty_out_".$row['year']."_".$row['month'].",";

        }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM warehouse_entry w where location_code='".$consign."'
        and registering_date group by bin_code,item_no) as tbl_warehouse ";

        $query_temp.=" left join (
        SELECT ship_to_code,concat('".$concat."',ship_to_code) as bin_code2,d.no as item_code, ";

        foreach($period as $row){
            $query_temp.="sum(if(h.posting_date between '".$row['from']."' and '".$row["to"]."',line_amount,0)) as amount_".$row["year"]."_".$row['month'].",";
        }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM sales_invoice_header h
          inner join tpimx_nav.sales_invoice_line d on(h.no = d.document_no)
          where h.sell_to_customer_no='".$customer."' and h.posting_date between '".$date_from."' and '".$date_to."' and ship_to_code!='' group by ship_to_code, d.no
           ) as tbl_invc on(tbl_warehouse.bin_code = tbl_invc.bin_code2 and tbl_warehouse.item_no = tbl_invc.item_code);";


        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_consigment_value($consign, $cust_code){
        $db = $this->load->database('default2', true);
        $query_temp = "select tbl_csg.item_no, tbl_csg.qty,unit_price, round(qty*unit_price,2) as total_value
            from (
            SELECT item_no, sum(qty) as qty FROM tpimx_nav.warehouse_entry w where location_code='".$consign."' group by item_no) as tbl_csg
            left join ( select * from tpimx_nav.sales_price where sales_code='".$cust_code."') slsprice on(slsprice.item_code = tbl_csg.item_no) order by item_no";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---
}

?>
