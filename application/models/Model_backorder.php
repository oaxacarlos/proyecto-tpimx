<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_backorder extends CI_Model{

    function get_distinct_year_month($slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_backorder_distinct_year_month('".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function get_backorder_by_customer_period($period, $slscode){
        $db = $this->load->database('default2', true);

        $query_temp = "select sell_to_customer_no,name,qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" from ( SELECT sell_to_customer_no,sum(qty_outstanding) as qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.= "sum(if(year(document_date)='".$row['yearr']."' and month(document_date)='".$row['monthh']."',qty_outstanding,0)) as qty_outstanding_".$row['yearr']."_".$row['monthh'].",";}
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM sales_backorder s
          group by sell_to_customer_no ) as tbl_backorder
          inner join mst_cust cust on(cust.cust_no = tbl_backorder.sell_to_customer_no) where cust.sales_person_code='".$slscode."';";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_backorder_by_customer_item_period($period, $slscode){
        $db = $this->load->database('default2', true);

        /*$query_temp = "select *,tbl_backorder.item_no as item_no_backorder,
          if(qty is null,0, qty) as qty_nav
          from ( select sell_to_customer_no,name,item_no,item_category_code,document_no,qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" from ( SELECT sell_to_customer_no,item_no,item_category_code,document_no ,sum(qty_outstanding) as qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.= "sum(if(year(document_date)='".$row['yearr']."' and month(document_date)='".$row['monthh']."',qty_outstanding,0)) as qty_outstanding_".$row['yearr']."_".$row['monthh'].",";}
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM sales_backorder s  where salesperson_code='".$slscode."'
          group by sell_to_customer_no,item_no,item_category_code, document_no ) as tbl_backorder
          inner join mst_cust cust on(cust.cust_no = tbl_backorder.sell_to_customer_no)
          ) as tbl_backorder left join item_invt_nav invt on(tbl_backorder.item_no=invt.item_no) order by sell_to_customer_no, tbl_backorder.item_no,document_no ";
*/

        $query_temp = " select sell_to_customer_no,name,tbl_backorder.item_no,item_category_code,document_no,qty_outstanding_total, ";
        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].", "; }
        $query_temp.=" qty_nav, qty_total_outstanding_all, if(qty_incoming is null,0,qty_incoming) as qty_incoming, estimation_arrived ";

        $query_temp.= "from (
            select sell_to_customer_no,name,tbl_backorder.item_no,item_category_code,document_no,qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].", "; }
        $query_temp.= " if(qty is null,0, qty) as qty_nav
          from ( select sell_to_customer_no,name,item_no,item_category_code,document_no,qty_outstanding_total,  ";

        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" from ( SELECT sell_to_customer_no,item_no,item_category_code,document_no ,sum(qty_outstanding) as qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.= "sum(if(year(document_date)='".$row['yearr']."' and month(document_date)='".$row['monthh']."',qty_outstanding,0)) as qty_outstanding_".$row['yearr']."_".$row['monthh'].",";}
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM sales_backorder s
          group by sell_to_customer_no,item_no,item_category_code, document_no ) as tbl_backorder
          inner join mst_cust cust on(cust.cust_no = tbl_backorder.sell_to_customer_no) where sales_person_code='".$slscode."'
          ) as tbl_backorder left join (SELECT item_no, sum(qty) as qty FROM item_invt_nav i group by item_no) invt on(tbl_backorder.item_no=invt.item_no)) as tbl_backorder

          left join( SELECT item_no as item_no, sum(qty_outstanding) as qty_total_outstanding_all FROM sales_backorder group by item_no ) as tbl_backorder2
          on(tbl_backorder2.item_no = tbl_backorder.item_no)

          left join (SELECT item_no,estimation_arrived, sum(qty) as qty_incoming FROM item_incoming i where statuss='0' group by item_no, estimation_arrived) as tbl_incoming
          on(tbl_incoming.item_no = tbl_backorder.item_no)

          order by sell_to_customer_no, tbl_backorder.item_no,document_no";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_backorder_by_item_period($period, $slscode){
        $db = $this->load->database('default2', true);

        $query_temp = "select item_no,description as name,qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" from ( SELECT item_no,description,sell_to_customer_no,sum(qty_outstanding) as qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.= "sum(if(year(document_date)='".$row['yearr']."' and month(document_date)='".$row['monthh']."',qty_outstanding,0)) as qty_outstanding_".$row['yearr']."_".$row['monthh'].",";}
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM sales_backorder s
          group by item_no ) as tbl_backorder
          inner join mst_cust cust on(cust.cust_no = tbl_backorder.sell_to_customer_no) where cust.sales_person_code='".$slscode."';";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_backorder_by_item_customer_period($period, $slscode){
        $db = $this->load->database('default2', true);

        $query_temp = " select tbl_backorder.item_no,item_category_code,sell_to_customer_no,name,document_no,qty_outstanding_total, ";
        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].", "; }
        $query_temp.=" qty_nav, qty_total_outstanding_all, if(qty_incoming is null,0,qty_incoming) as qty_incoming, estimation_arrived ";

        $query_temp.= "from (
            select tbl_backorder.item_no,item_category_code,sell_to_customer_no,name,document_no,qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].", "; }
        $query_temp.= " if(qty is null,0, qty) as qty_nav
          from ( select item_no,item_category_code, sell_to_customer_no,name,document_no,qty_outstanding_total,  ";

        foreach($period as $row){ $query_temp.="qty_outstanding_".$row['yearr']."_".$row['monthh'].","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" from ( SELECT item_no,item_category_code, sell_to_customer_no,document_no ,sum(qty_outstanding) as qty_outstanding_total, ";

        foreach($period as $row){ $query_temp.= "sum(if(year(document_date)='".$row['yearr']."' and month(document_date)='".$row['monthh']."',qty_outstanding,0)) as qty_outstanding_".$row['yearr']."_".$row['monthh'].",";}
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM sales_backorder s
          group by item_no,item_category_code,sell_to_customer_no,document_no ) as tbl_backorder
          inner join mst_cust cust on(cust.cust_no = tbl_backorder.sell_to_customer_no) where sales_person_code='".$slscode."'
          ) as tbl_backorder left join (SELECT item_no, sum(qty) as qty FROM item_invt_nav i group by item_no) invt on(tbl_backorder.item_no=invt.item_no)) as tbl_backorder

          left join( SELECT item_no as item_no, sum(qty_outstanding) as qty_total_outstanding_all FROM sales_backorder group by item_no ) as tbl_backorder2
          on(tbl_backorder2.item_no = tbl_backorder.item_no)

          left join (SELECT item_no,estimation_arrived, sum(qty) as qty_incoming FROM item_incoming i where statuss='0' group by item_no, estimation_arrived) as tbl_incoming
          on(tbl_incoming.item_no = tbl_backorder.item_no)

          order by tbl_backorder.item_no, sell_to_customer_no,document_no";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---
}

?>
