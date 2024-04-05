<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_sales_report extends CI_Model{

    function get_customer_nav_local(){
        $db = $this->load->database('default2', true);
        $query = $db->query("SELECT * FROM mst_cust m");
        return $query->result_array();
    }
    //---

    function get_customer_sales_summary_report($cust_code, $year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.="
          from(
            select distinct(tbl_item.item_no) as item_no from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

            union

            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

            left join (
            select item_no, ";

            foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.","; }
            $query_temp = substr($query_temp,0,-1);

            $query_temp.="
            from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
              on(tbl_si_this_year.item_no=tbl_item.item_no)

              left join (
              select item_no,sum(qty) as qty
              from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
              on(si_last_year.item_no=tbl_item.item_no)

              left join (
              select item_no,sum(qty) as qty
              from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
              on(si_last_2year.item_no=tbl_item.item_no)

              order by tbl_item.item_no;
            ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_customer_sales_summary_amount_report($cust_code, $year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,
          if(si_last_2year.amount is null,0,round(si_last_2year.amount,2)) as si_qty_last_2year,
          if(si_last_year.amount is null,0,round(si_last_year.amount,2)) as si_qty_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,round(now_".$year."_".$row.",2)) as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.="
          from(
            select distinct(tbl_item.item_no) as item_no from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.line_amount as amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

            union

            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.line_amount*-1) as amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

            left join (
            select item_no, ";

            foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',amount,0)) as now_".$year."_".$row.","; }
            $query_temp = substr($query_temp,0,-1);

            $query_temp.="
            from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.line_amount as amount, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.line_amount*-1) as amount, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
              on(tbl_si_this_year.item_no=tbl_item.item_no)

              left join (
              select item_no,sum(amount) as amount
              from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.line_amount as amount, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.line_amount*-1) as amount, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
              on(si_last_year.item_no=tbl_item.item_no)

              left join (
              select item_no,sum(amount) as amount
              from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.line_amount as amount, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.line_amount*-1) as amount, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
              on(si_last_2year.item_no=tbl_item.item_no)

              order by tbl_item.item_no;
            ";

            //debug($query_temp);

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_customer_sales_review_qty_report($cust_code, $year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty,line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.",";
          }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            order by tbl_item.item_no;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_customer_sales_review_amount_report($cust_code, $year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty,line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){ $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no,d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            order by tbl_item.item_no;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_product_review_qty_report($year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."')

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."')  and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.",";
          }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            order by tbl_item.item_no;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_product_review_amount_report($year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."')

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty,line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){ $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no,d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            order by tbl_item.item_no;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_cust_review_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, name, payment_terms_code,
          if(tbl_si_last_2year.qty is null,0,tbl_si_last_2year.qty) as si_qty_last_2year,
          if(tbl_si_last_year.qty is null,0,tbl_si_last_year.qty) as si_qty_last_year,
          if(avg_collection_days is null,0,avg_collection_days) as avg_collection_days,
          if(dist_cost_percent is null,0, dist_cost_percent) as dist_cost_percent, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          SELECT cust_no, name, payment_terms_code FROM mst_cust m where cust_no like '1%' or cust_no like '2%') as tbl_cust

          left join(
          select customer, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_si_this_year
            on(tbl_si_this_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_year group by customer) as tbl_si_last_year
            on(tbl_si_last_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_2year group by customer) as tbl_si_last_2year
            on(tbl_si_last_2year.customer=tbl_cust.cust_no)

            left join(
              select cust_no as customer, round(total_days/total_count,2) as avg_collection_days
              from (
              select cust_no, sum(total_days) as total_days, count(cust_no) as total_count from (
              select *,datediff(payment_date,invc_date) as total_days from (
              select tbl_payment.entry_no, tbl_payment.cust_no, tbl_payment.document_no as payment_doc, tbl_payment.posting_date as payment_date, tbl_payment.closed_by_entry_no,
              tbl_invc.cust_ledger_entry_no, tbl_invc.posting_date as invc_date, tbl_invc.doc_invc
              from (
              SELECT entry_no, cust_no,document_no, closed_by_entry_no, posting_date FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') as tbl_payment

              inner join(
              select tbl_cust_payment.document_no as doc_payment, cust_ledger_entry_no, posting_date, tbl_invc.document_no as doc_invc
              from (
              SELECT document_no, cust_ledger_entry_no
              FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment

              inner join(
              SELECT * FROM cust_ldgr_ent c where entry_no in(select cust_ledger_entry_no from (
              SELECT document_no, cust_ledger_entry_no
              FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment)) as tbl_invc
              on tbl_invc.entry_no=tbl_cust_payment.cust_ledger_entry_no) as tbl_invc on(tbl_invc.doc_payment=tbl_payment.document_no)) as tbl_payment_invc) as tbl_payment_invc
              group by cust_no ) as tbl_payment_invc) as tbl_payment_invc
              on(tbl_payment_invc.customer=tbl_cust.cust_no)

            left join(
              select customer, line_amount, if(total_dist is null, 0, total_dist) as total_dist, if(total_dist is null,0,round((total_dist/line_amount)*100,2)) as dist_cost_percent
              from (
              select customer, sum(line_amount) as line_amount from (
                SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
                (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
                FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                where year(h.posting_date) in ('".$year."')
                union
                SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
                (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
                FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_cust_invc

              left join (
              SELECT cust_no, sum(amount) total_dist
              FROM gl_entry g where gl_account_no='8423' and document_type='2' and posting_date between '".$firstdate_this_year."' and '".$lastdate_this_year."' group by cust_no
              ) as tbl_cost_transporter on(tbl_cost_transporter.cust_no=tbl_cust_invc.customer)) as tbl_dist_cost
              on (tbl_dist_cost.customer=tbl_cust.cust_no)

              where tbl_si_last_2year.qty is not null or tbl_si_last_year.qty is not null or line_amount_this_year is not null ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_cust_review_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, name, payment_terms_code,
          if(tbl_si_last_2year.line_amount is null,0,tbl_si_last_2year.line_amount) as si_qty_last_2year,
          if(tbl_si_last_year.line_amount is null,0,tbl_si_last_year.line_amount) as si_qty_last_year,
          if(avg_collection_days is null,0,avg_collection_days) as avg_collection_days,";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          SELECT cust_no, name, payment_terms_code FROM mst_cust m where cust_no like '1%' or cust_no like '2%') as tbl_cust

          left join(
          select customer, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_si_this_year
            on(tbl_si_this_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty, sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_year group by customer) as tbl_si_last_year
            on(tbl_si_last_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_2year group by customer) as tbl_si_last_2year
            on(tbl_si_last_2year.customer=tbl_cust.cust_no)

            left join(
              select cust_no as customer, round(total_days/total_count,2) as avg_collection_days
            from (
            select cust_no, sum(total_days) as total_days, count(cust_no) as total_count from (
            select *,datediff(payment_date,invc_date) as total_days from (
            select tbl_payment.entry_no, tbl_payment.cust_no, tbl_payment.document_no as payment_doc, tbl_payment.posting_date as payment_date, tbl_payment.closed_by_entry_no,
            tbl_invc.cust_ledger_entry_no, tbl_invc.posting_date as invc_date, tbl_invc.doc_invc
            from (
            SELECT entry_no, cust_no,document_no, closed_by_entry_no, posting_date FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') as tbl_payment

            inner join(
            select tbl_cust_payment.document_no as doc_payment, cust_ledger_entry_no, posting_date, tbl_invc.document_no as doc_invc
            from (
            SELECT document_no, cust_ledger_entry_no
            FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment

            inner join(
            SELECT * FROM cust_ldgr_ent c where entry_no in(select cust_ledger_entry_no from (
            SELECT document_no, cust_ledger_entry_no
            FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment)) as tbl_invc
            on tbl_invc.entry_no=tbl_cust_payment.cust_ledger_entry_no) as tbl_invc on(tbl_invc.doc_payment=tbl_payment.document_no)) as tbl_payment_invc) as tbl_payment_invc
            group by cust_no ) as tbl_payment_invc) as tbl_payment_invc
            on(tbl_payment_invc.customer=tbl_cust.cust_no)

            where tbl_si_last_2year.line_amount is not null or tbl_si_last_year.line_amount is not null or line_amount_this_year is not null ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_salesman_user($user){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT sls_code FROM mst_salesman_user where user='".$user."'";
        $query = $db->query($query_temp)->row();
        $result = $query->sls_code;
        return $result;
    }
    //---

    function get_customer_by_sls_person_code_nav_local($sls_code){
        $db = $this->load->database('default2', true);
        if($sls_code == "") $query_temp = "SELECT * FROM mst_cust m;";
        else $query_temp = "SELECT * FROM mst_cust m where sales_person_code='".$sls_code."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_salesman_sales_report($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear,$jan_date){
        $db = $this->load->database('default2', true);

        $query_temp = "select brand_desc, item_desc,tbl_item.item_no as item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year,
          if(si_last_year_this_month.qty is null,0,si_last_year_this_month.qty) as si_last_year_this_month,
          if(now_".$year." is null,0,now_".$year.") as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.="  ,
            if(tbl_si_this_year.line_amount is null,0,round(tbl_si_this_year.line_amount,2)) as line_amount_this_year,
            if(tbl_si_this_year.line_cost_amount is null,0,round(tbl_si_this_year.line_cost_amount,2)) as line_cost_amount_this_year,
            if(tbl_si_this_year.line_amount is null or tbl_si_this_year.line_cost_amount is null,0, round((tbl_si_this_year.line_amount-tbl_si_this_year.line_cost_amount)/tbl_si_this_year.line_amount*100,2))  as gp_percent,
            if(unit_price_".$year." is null,0,round(unit_price_".$year.",2)) as unit_price_".$year."
          from (
            select item_no,item_category_code,tbl_item_wms.name as item_desc,mst_brand.description as brand_desc from (
            select distinct(tbl_item.item_no) as item_no,item_category_code from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

              union

              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, item_category_code,(cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item
            ) as tbl_item inner join mst_brand on(tbl_item.item_category_code=mst_brand.item_cat)
            inner join tpimx_wms.mst_item as tbl_item_wms on(tbl_item.item_no=tbl_item_wms.code)) as tbl_item

            left join (
            select item_no,line_cost_amount, line_amount, sum(if(year(posting_date)='".$year."',qty,0)) as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" , max(unit_price) as unit_price_".$year." from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount, line_amount, unit_price
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, (cm_d.line_amount*-1) as line_amount, unit_price
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where h.posting_date between '".$jan_date."' and '".$today_lastyear."' and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where cm_h.posting_date between '".$jan_date."' and '".$today_lastyear."' and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year_this_month
            on(si_last_year_this_month.item_no=tbl_item.item_no)";

            $query = $db->query($query_temp);
            return $query->result_array();
    }
    //---

    function get_salesman_sales_amount_report($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear,$jan_date){
        $db = $this->load->database('default2', true);

        $query_temp = "select brand_desc, item_desc,tbl_item.item_no as item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year,
          if(si_last_year_this_month.line_amount is null,0,si_last_year_this_month.line_amount) as si_last_year_this_month,
          if(now_".$year." is null,0,now_".$year.") as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" , if(tbl_si_this_year.line_amount is null,0,round(tbl_si_this_year.line_amount,2)) as line_amount_this_year,
          if(tbl_si_this_year.line_cost_amount is null,0,round(tbl_si_this_year.line_cost_amount,2)) as line_cost_amount_this_year,
          if(tbl_si_this_year.line_amount is null or tbl_si_this_year.line_cost_amount is null,0, round((tbl_si_this_year.line_amount-tbl_si_this_year.line_cost_amount)/tbl_si_this_year.line_amount*100,2))  as gp_percent
          from (
            select item_no,item_category_code,tbl_item_wms.name as item_desc,mst_brand.description as brand_desc from (
            select distinct(tbl_item.item_no) as item_no,item_category_code from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

              union

              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, item_category_code,(cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item
            ) as tbl_item inner join mst_brand on(tbl_item.item_category_code=mst_brand.item_cat)
            inner join tpimx_wms.mst_item as tbl_item_wms on(tbl_item.item_no=tbl_item_wms.code)) as tbl_item

            left join (
            select item_no, line_cost_amount, line_amount, sum(if(year(posting_date)='".$year."',line_amount,0)) as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) between '".$jan_date."' and '".$today_lastyear."' and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) between '".$jan_date."' and '".$today_lastyear."' and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year_this_month
            on(si_last_year_this_month.item_no=tbl_item.item_no)";

            $query = $db->query($query_temp);
            return $query->result_array();
    }
    //---

    function product_customer_sales_qty_report($year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp = "select tbl_item.item_no as item_no, tbl_item.customer,
          if(tbl_si_last_2year.qty is null,0,tbl_si_last_2year.qty) as si_qty_last_2year,
          if(tbl_si_last_year.qty is null,0,tbl_si_last_year.qty) as si_qty_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
            if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
            if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
            from(

            select item_no,customer,item_category_code from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."')

            union

            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."')  and (cm_d.type='2' and cm_d.no!='DISC')) as tbl group by item_no, customer) as tbl_item

            left join(
            select item_no, customer, ";

            foreach($months as $row){
                $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.",";
            }

            $query_temp.="sum(line_amount) as line_amount_this_year,
              sum(line_cost_amount) as line_cost_amount_this_year
              from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
              (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$year."')
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
              (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no,customer) as tbl_si_this_year
              on(tbl_si_this_year.item_no=tbl_item.item_no and tbl_si_this_year.customer=tbl_item.customer)

              left join(
              select item_no,customer, sum(qty) as qty
              from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_year."')
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_year group by item_no, customer) as tbl_si_last_year
              on(tbl_si_last_year.item_no=tbl_item.item_no and tbl_si_last_year.customer=tbl_item.customer)

              left join(
              select item_no, customer,sum(qty) as qty
              from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_2year."')
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_2year group by item_no, customer) as tbl_si_last_2year
              on(tbl_si_last_2year.item_no=tbl_item.item_no and tbl_si_last_2year.customer=tbl_item.customer)
              order by tbl_item.item_no, tbl_item.customer";

              $query = $db->query($query_temp);
              return $query->result_array();
    }
    //---

    function salesnational_salesvsbudget_salesperson($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_salesvsbudget_salesperson('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesnational_salesbycategory($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_salesbycategory('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesnational_actual_netsales_mtd($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_actual_netsales_mtd('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function salesnational_actual_netsales_ytd($year,$month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_actual_netsales_ytd('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function salesnational_actual_netsales_sakura($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_actual_netsales_sakura('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function salesnational_actual_netsales_typ($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_actual_netsales_typ('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function salesnational_salestrendvsbudget($year){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_salestrendvsbudget('".$year."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function salesnational_sales_by_day_month($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_sales_by_day_month('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesnational_sales_geographic_mtd($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_sales_geographic_mtd('".$year."', '".$month."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_salesman_active($slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT sa.code as slscode, sls.name as slsname FROM mst_salesman_active sa inner join mst_salesman sls on(sa.code=sls.code) where sa.code like '%".$slscode."%' order by slsname";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //----

    function salesman_salesbycategory($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_salesbycategory('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesman_sales_by_day_month($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_sales_by_day_month('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesman_customer_buy_and_nobuy($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_customer_buy_and_nobuy('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result["total"] = $query->total;
        $result["buy"] = $query->buy;
        $result["notbuy"] = $query->notbuy;
        return $result;
    }
    //---

    function salesman_customer_top20($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_customer_top20('".$year."', '".$month."', '".$last_month_from."', '".$last_month_to."', '".$last_2months_from."', '".$last_2months_to."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesman_item_top40($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_item_top40('".$year."', '".$month."', '".$last_month_from."', '".$last_month_to."', '".$last_2months_from."', '".$last_2months_to."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesman_target_mtd($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp="SELECT sum(tgt_value) as tgt_value FROM sales_target s where tgt_year='".$year."' and tgt_month='".$month."' and sales_person='".$slscode."';";
        $query = $db->query($query_temp)->row();
        $result = $query->tgt_value;
        return $result;
    }
    //---

    function salesman_sales_mtd($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_sales_mtd('".$year."','".$month."','".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result = $query->amount;
        return $result;
    }
    //---

    function salesman_target_ytd($year, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp="SELECT sum(tgt_value) as tgt_value FROM sales_target s where tgt_year='".$year."' and sales_person='".$slscode."';";
        $query = $db->query($query_temp)->row();
        $result = $query->tgt_value;
        return $result;
    }
    //---

    function salesman_target_ytd_ver2($year, $slscode,$month){
        $db = $this->load->database('default2', true);
        $query_temp="SELECT sum(tgt_value) as tgt_value FROM sales_target s where tgt_year='".$year."' and sales_person='".$slscode."' and tgt_month in( ";
        foreach($month as $row){ $query_temp.="'".$row."',"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        $query = $db->query($query_temp)->row();
        $result = $query->tgt_value;
        return $result;
    }
    //---

    function salesman_sales_ytd($year, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_sales_ytd('".$year."','".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result = $query->amount;
        return $result;
    }
    //---

    function salesman_sales_mtd_by_period($from, $to, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_sales_mtd_by_period('".$from."', '".$to."', '".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result = $query->amount;
        return $result;
    }
    //---

    function salesnational_sales_by_period($from, $to){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_sales_by_period('".$from."', '".$to."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function salesman_sales_by_period($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_sales_by_period('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_cust_review_qty_report_by_salesman($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year, $slscode){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, name,county, payment_terms_code,slsname,
          if(tbl_si_last_2year.qty is null,0,tbl_si_last_2year.qty) as si_qty_last_2year,
          if(tbl_si_last_year.qty is null,0,tbl_si_last_year.qty) as si_qty_last_year,
          if(avg_collection_days is null,0,avg_collection_days) as avg_collection_days,
          if(dist_cost_percent is null,0, dist_cost_percent) as dist_cost_percent, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          ,total_qty_this_year
          from(
          SELECT cust_no, m.name as name, payment_terms_code, sls.name as slsname,county FROM mst_cust m
          inner join mst_salesman sls on(m.sales_person_code=sls.code)
          where (cust_no like '1%' or cust_no like '2%') and sales_person_code like '%".$slscode."%') as tbl_cust

          left join(
          select customer, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year, sum(qty) as total_qty_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_si_this_year
            on(tbl_si_this_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_year group by customer) as tbl_si_last_year
            on(tbl_si_last_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_2year group by customer) as tbl_si_last_2year
            on(tbl_si_last_2year.customer=tbl_cust.cust_no)

            left join(
              select cust_no as customer, round(total_days/total_count,2) as avg_collection_days
              from (
              select cust_no, sum(total_days) as total_days, count(cust_no) as total_count from (
              select *,datediff(payment_date,invc_date) as total_days from (
              select tbl_payment.entry_no, tbl_payment.cust_no, tbl_payment.document_no as payment_doc, tbl_payment.posting_date as payment_date, tbl_payment.closed_by_entry_no,
              tbl_invc.cust_ledger_entry_no, tbl_invc.posting_date as invc_date, tbl_invc.doc_invc
              from (
              SELECT entry_no, cust_no,document_no, closed_by_entry_no, posting_date FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') as tbl_payment

              inner join(
              select tbl_cust_payment.document_no as doc_payment, cust_ledger_entry_no, posting_date, tbl_invc.document_no as doc_invc
              from (
              SELECT document_no, cust_ledger_entry_no
              FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment

              inner join(
              SELECT * FROM cust_ldgr_ent c where entry_no in(select cust_ledger_entry_no from (
              SELECT document_no, cust_ledger_entry_no
              FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment)) as tbl_invc
              on tbl_invc.entry_no=tbl_cust_payment.cust_ledger_entry_no) as tbl_invc on(tbl_invc.doc_payment=tbl_payment.document_no)) as tbl_payment_invc) as tbl_payment_invc
              group by cust_no ) as tbl_payment_invc) as tbl_payment_invc
              on(tbl_payment_invc.customer=tbl_cust.cust_no)

            left join(
              select customer, line_amount, if(total_dist is null, 0, total_dist) as total_dist, if(total_dist is null,0,round((total_dist/line_amount)*100,2)) as dist_cost_percent
              from (
              select customer, sum(line_amount) as line_amount from (
                SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
                (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
                FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                where year(h.posting_date) in ('".$year."')
                union
                SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
                (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
                FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_cust_invc

              left join (
              SELECT cust_no, sum(amount) total_dist
              FROM gl_entry g where gl_account_no='8423' and document_type='2' and posting_date between '".$firstdate_this_year."' and '".$lastdate_this_year."' group by cust_no
              ) as tbl_cost_transporter on(tbl_cost_transporter.cust_no=tbl_cust_invc.customer)) as tbl_dist_cost
              on (tbl_dist_cost.customer=tbl_cust.cust_no)

              where tbl_si_last_2year.qty is not null or tbl_si_last_year.qty is not null or line_amount_this_year is not null
              order by total_qty_this_year desc ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_cust_review_amount_report_by_salesman($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year, $slscode){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, name,county, payment_terms_code,slsname,
          if(tbl_si_last_2year.line_amount is null,0,tbl_si_last_2year.line_amount) as si_qty_last_2year,
          if(tbl_si_last_year.line_amount is null,0,tbl_si_last_year.line_amount) as si_qty_last_year,
          if(avg_collection_days is null,0,avg_collection_days) as avg_collection_days,";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
            SELECT cust_no, m.name as name, payment_terms_code, sls.name as slsname, county FROM mst_cust m
            inner join mst_salesman sls on(m.sales_person_code=sls.code)
            where (cust_no like '1%' or cust_no like '2%') and sales_person_code like '%".$slscode."%') as tbl_cust

          left join(
          select customer, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_si_this_year
            on(tbl_si_this_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty, sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_year group by customer) as tbl_si_last_year
            on(tbl_si_last_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_2year group by customer) as tbl_si_last_2year
            on(tbl_si_last_2year.customer=tbl_cust.cust_no)

            left join(
              select cust_no as customer, round(total_days/total_count,2) as avg_collection_days
            from (
            select cust_no, sum(total_days) as total_days, count(cust_no) as total_count from (
            select *,datediff(payment_date,invc_date) as total_days from (
            select tbl_payment.entry_no, tbl_payment.cust_no, tbl_payment.document_no as payment_doc, tbl_payment.posting_date as payment_date, tbl_payment.closed_by_entry_no,
            tbl_invc.cust_ledger_entry_no, tbl_invc.posting_date as invc_date, tbl_invc.doc_invc
            from (
            SELECT entry_no, cust_no,document_no, closed_by_entry_no, posting_date FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') as tbl_payment

            inner join(
            select tbl_cust_payment.document_no as doc_payment, cust_ledger_entry_no, posting_date, tbl_invc.document_no as doc_invc
            from (
            SELECT document_no, cust_ledger_entry_no
            FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment

            inner join(
            SELECT * FROM cust_ldgr_ent c where entry_no in(select cust_ledger_entry_no from (
            SELECT document_no, cust_ledger_entry_no
            FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment)) as tbl_invc
            on tbl_invc.entry_no=tbl_cust_payment.cust_ledger_entry_no) as tbl_invc on(tbl_invc.doc_payment=tbl_payment.document_no)) as tbl_payment_invc) as tbl_payment_invc
            group by cust_no ) as tbl_payment_invc) as tbl_payment_invc
            on(tbl_payment_invc.customer=tbl_cust.cust_no)

            where tbl_si_last_2year.line_amount is not null or tbl_si_last_year.line_amount is not null or line_amount_this_year is not null
            order by line_amount_this_year desc";


        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_sales_order_daily_from_navision($year, $month){
      $db = $this->load->database('sql_server_live', true);
      $query_temp = "select format([Order Date],'yyyy-MM-dd') as order_date, date_name,total_so, total_amount
        from (
        select h.[Order Date],DATENAME(weekday,h.[Order Date]) as date_name,
        sum(d.[Line Amount]) as total_amount
        from [".$this->config->item('sqlserver_live')."Sales Header] h
        inner join [".$this->config->item('sqlserver_live')."Sales Line] d on(h.[no_]=d.[Document No_]) where h.[Document Type] = '1'
        and year(h.[Order Date])='".$year."' and month(h.[Order Date])='".$month."' group by h.[Order Date]) as tbl_so

        left join(
        select [Order Date] as order_date2,count([No_]) as total_so
        from [".$this->config->item('sqlserver_live')."Sales Header] where year([Order Date])='".$year."' and month([Order Date])='".$month."'
        and [Document Type] = '1'
        group by [Order Date]) as tbl_so2 on(tbl_so.[Order Date]=tbl_so2.order_date2) order by tbl_so.[Order Date]";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_sales_order_daily_by_salesman_from_navision($year, $month, $slscode){
      $db = $this->load->database('sql_server_live', true);
      $query_temp="select format([Order Date],'yyyy-MM-dd') as order_date, date_name,total_so, total_amount
        from (
        select h.[Order Date],DATENAME(weekday,h.[Order Date]) as date_name,
        sum(d.[Line Amount]) as total_amount
        from [".$this->config->item('sqlserver_live')."Sales Header] h
        inner join [".$this->config->item('sqlserver_live')."Sales Line] d on(h.[no_]=d.[Document No_])
        inner join [".$this->config->item('sqlserver_live')."Customer] cust on h.[Sell-to Customer No_]=cust.[No_]
        where h.[Document Type] = '1'
        and cust.[Salesperson Code]='".$slscode."'
        and year(h.[Order Date])='".$year."' and month(h.[Order Date])='".$month."' group by h.[Order Date]) as tbl_so

        left join(
        select [Order Date] as order_date2,count(h.[No_]) as total_so
        from [".$this->config->item('sqlserver_live')."Sales Header] h
        inner join [".$this->config->item('sqlserver_live')."Customer] cust on h.[Sell-to Customer No_]=cust.[No_]
        where year([Order Date])='".$year."' and month([Order Date])='".$month."' and [Document Type] = '1' and cust.[Salesperson Code]='".$slscode."'
        group by [Order Date]) as tbl_so2 on(tbl_so.[Order Date]=tbl_so2.order_date2) order by tbl_so.[Order Date]";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_salesman_sales_weekly($year, $month, $period){
        $db = $this->load->database('default2', true);
        $query_temp = " select slscode,name, ";

        $i=1;
        foreach($period as $row){ $query_temp.=" if(week1 is not null,week".$i.",0) as week".$i.","; $i++; }

        $query_temp.=" if(amount is not null,amount,0) as amount,
          if(tgt_value is not null,tgt_value,0) as tgt_value
          from(
          SELECT sls_active.code as slscode, sls.name as name
          FROM mst_salesman_active sls_active inner join mst_salesman sls on(sls_active.code=sls.code)) as tbl_sls

          left join(
          select sales_person_code, ";

        $i=1;
        foreach($period as $row){
          $query_temp.="round(sum(if(posting_date between '".$row["from"]."' and '".$row["to"]."',line_amount,0)),2) as week".$i.",";
          $i++;
        }

        $query_temp.=" round(sum(line_amount),2) as amount ";
        $query_temp.="from (
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date)='".$year."' and month(h.posting_date)='".$month."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date)='".$year."' and month(cm_h.posting_date)='".$month."') as tbl_sales
          inner join mst_cust cust on(cust.cust_no=tbl_sales.customer) group by sales_person_code
          ) as tbl_invoice_weekly on(tbl_invoice_weekly.sales_person_code=tbl_sls.slscode)

          left join(
          SELECT sales_person,sum(tgt_value) as tgt_value FROM sales_target s where tgt_year='".$year."' and tgt_month='".$month."' group by sales_person
          ) as tbl_tgt on(tbl_tgt.sales_person=tbl_sls.slscode) order by name";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_customer_sales_by_day_month($year, $month, $custno){
      $db = $this->load->database('default2', true);
      $query_temp = "call get_customer_sales_by_day_month('".$year."', '".$month."', '".$custno."')";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--

    function get_customer_sales_by_day_period($from, $to, $custno){
      $db = $this->load->database('default2', true);
      $query_temp = "call get_customer_sales_by_day_period('".$from."', '".$to."', '".$custno."')";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--

    function get_customer_sales_by_month_year($year, $custno){
      $db = $this->load->database('default2', true);
      $query_temp = "call get_customer_sales_by_month_year('".$year."', '".$custno."')";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--

    function get_invt_available_incoming(){
      $db = $this->load->database('default2', true);
      $query_temp = "select code,name,
          qty_wh1,
          qty_wh2,
          qty_wh3,
          qty_wh4,
          if(qty_incoming is null,0,qty_incoming) as qty_incoming,
          if(qty_po is null,0,qty_po) as qty_po
          from (
          SELECT code, name,
          if(qty_wh1 is null,0,qty_wh1) as qty_wh1,
          if(qty_wh2 is null,0,qty_wh2) as qty_wh2,
          if(qty_wh3 is null,0,qty_wh3) as qty_wh3,
          if(qty_wh4 is null,0,qty_wh4) as qty_wh4
          FROM mst_item item
          left join (
          SELECT item_no,
          sum(CASE WHEN location = 'WH1' THEN qty ELSE 0 END) AS qty_wh1,
          sum(CASE WHEN location = 'WH2' THEN qty ELSE 0 END) AS qty_wh2,
          sum(CASE WHEN location = 'WH3' THEN qty ELSE 0 END) AS qty_wh3,
          sum(CASE WHEN location = 'WH4' THEN qty ELSE 0 END) AS qty_wh4
          FROM item_invt_nav i group by item_no) invt on(item.code=invt.item_no)
          where (item.code not like 'XP%' and item.code not like 'XWS%')) as tbl_item
          left join (SELECT item_no, sum(qty) as qty_incoming FROM item_incoming i where statuss='0' group by item_no) as tbl_item_incoming
          on(tbl_item.code=tbl_item_incoming.item_no)
          left join(
          SELECT no, sum(qty_outstanding) as qty_po  FROM purchase_order p group by no) as tbl_po on(tbl_item.code=tbl_po.no) ";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_invoice_cn_nett_from_navision($year, $month){
      $db = $this->load->database('sql_server_live', true);
      $query_temp = "select tbl_summ.yearr,tbl_summ.monthh,
        FORMAT(tbl_summ.total_invoice, 'N', 'en-us') as total_invoice,
        FORMAT(tbl_summ.total_cm,'N', 'en-us') as total_cm,
        FORMAT((tbl_summ.total_invoice-tbl_summ.total_cm),'N', 'en-us') as total_nett, (tbl_summ.total_invoice-tbl_summ.total_cm) as total_nett2
        from (
        select tbl_period.yearr, tbl_period.monthh, tbl_invc.total_invoice,tbl_cm.total_cm
        from (
        select distinct year(h.[Posting Date]) as yearr , MONTH(h.[Posting Date]) as monthh
        from [".$this->config->item('sqlserver_live')."sales invoice header] as h
        where YEAR(h.[Posting Date])='".$year."' and month(h.[Posting Date])='".$month."' ) as tbl_period

        left join
        (
        select year(h.[Posting Date]) as yearr , MONTH(h.[Posting Date]) as monthh,
        sum(d.[amount]) as total_invoice from [".$this->config->item('sqlserver_live')."sales invoice header] as h
        inner join [".$this->config->item('sqlserver_live')."sales invoice line] as d on(h.[no_]=d.[document no_])
        where year(h.[Posting Date])='".$year."' and month(h.[Posting Date])='".$month."' group by year(h.[Posting Date]), MONTH(h.[Posting Date])
        ) as tbl_invc on(tbl_period.yearr=tbl_invc.yearr and tbl_period.monthh=tbl_invc.monthh)

        left join
        (
        select year(h.[Posting Date]) as yearr , MONTH(h.[Posting Date]) as monthh,
        sum(d.[amount]) as total_cm from [".$this->config->item('sqlserver_live')."Sales Cr_Memo Header] as h
        inner join [".$this->config->item('sqlserver_live')."Sales Cr_Memo Line] as d on(h.[no_]=d.[document no_])
        where year(h.[Posting Date])='".$year."' and month(h.[Posting Date])='".$month."' group by year(h.[Posting Date]), MONTH(h.[Posting Date])
        ) as tbl_cm on(tbl_period.yearr=tbl_cm.yearr and tbl_period.monthh=tbl_cm.monthh)
        ) as tbl_summ";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_value_wms_from_navision(){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select sum(tbl_wship.[Quantity] * so_line.[Unit Price]) as total_wms_value
          from (select * from [".$this->config->item('sqlserver_live')."Warehouse Shipment Line] wship_line where [Source No_] like 'TPM-SO%') as tbl_wship
          inner join [".$this->config->item('sqlserver_live')."Sales Line] as so_line on(so_line.[Document No_] = tbl_wship.[Source No_] and so_line.[Line No_]=tbl_wship.[Source Line No_])";

        $query = $db->query($query_temp)->row();
        return $query->total_wms_value;
    }
    //---

    function get_value_sls_shipment_not_invoice_from_navision(){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select sum([Qty_ Shipped Not Invoiced]*[Unit Price]) as total_sls_shipment
          from [".$this->config->item('sqlserver_live')."Sales Shipment Line] where [Qty_ Shipped Not Invoiced] > 0 and [type]='2';";

        $query = $db->query($query_temp)->row();
        return $query->total_sls_shipment;
    }
    //---

    function get_inventory_incoming(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT * FROM item_incoming i where statuss='0';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // CS //
    function cs_target_mtd($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp="SELECT sum(tgt_value) as tgt_value FROM sales_target_cs s where tgt_year='".$year."' and tgt_month='".$month."' and cs_person='".$slscode."';";
        $query = $db->query($query_temp)->row();
        $result = $query->tgt_value;
        return $result;
    }
    //---

    function cs_sales_mtd($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_sales_mtd('".$year."','".$month."','".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result = $query->amount;
        return $result;
    }
    //---

    function cs_target_ytd_ver2($year, $slscode,$month){
        $db = $this->load->database('default2', true);
        $query_temp="SELECT sum(tgt_value) as tgt_value FROM sales_target_cs s where tgt_year='".$year."' and cs_person='".$slscode."' and tgt_month in( ";
        foreach($month as $row){ $query_temp.="'".$row."',"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ";
        $query = $db->query($query_temp)->row();
        $result = $query->tgt_value;
        return $result;
    }
    //---

    function cs_sales_ytd($year, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_sales_ytd('".$year."','".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result = $query->amount;
        return $result;
    }
    //---

    function cs_salesbycategory($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_salesbycategory('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function cs_sales_mtd_by_period($from, $to, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_sales_mtd_by_period('".$from."', '".$to."', '".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result = $query->amount;
        return $result;
    }
    //---

    function cs_sales_by_day_month($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_sales_by_day_month('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function cs_customer_buy_and_nobuy($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_customer_buy_and_nobuy('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp)->row();
        $result["total"] = $query->total;
        $result["buy"] = $query->buy;
        $result["notbuy"] = $query->notbuy;
        return $result;
    }
    //---

    function cs_customer_top20($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_customer_top20('".$year."', '".$month."', '".$last_month_from."', '".$last_month_to."', '".$last_2months_from."', '".$last_2months_to."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function cs_item_top40($year, $month, $last_month_from, $last_month_to, $last_2months_from, $last_2months_to, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_item_top40('".$year."', '".$month."', '".$last_month_from."', '".$last_month_to."', '".$last_2months_from."', '".$last_2months_to."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_sales_order_daily_by_cs_from_navision($year, $month, $slscode){
      $db = $this->load->database('sql_server_live', true);
      $query_temp="select format([Order Date],'yyyy-MM-dd') as order_date, date_name,total_so, total_amount
        from (
        select h.[Order Date],DATENAME(weekday,h.[Order Date]) as date_name,
        sum(d.[Line Amount]) as total_amount
        from [".$this->config->item('sqlserver_live')."Sales Header] h
        inner join [".$this->config->item('sqlserver_live')."Sales Line] d on(h.[no_]=d.[Document No_])
        inner join [".$this->config->item('sqlserver_live')."Customer] cust on h.[Sell-to Customer No_]=cust.[No_]
        where h.[Document Type] = '1'
        and cust.[Service Zone Code]='".$slscode."'
        and year(h.[Order Date])='".$year."' and month(h.[Order Date])='".$month."' group by h.[Order Date]) as tbl_so

        left join(
        select [Order Date] as order_date2,count(h.[No_]) as total_so
        from [".$this->config->item('sqlserver_live')."Sales Header] h
        inner join [".$this->config->item('sqlserver_live')."Customer] cust on h.[Sell-to Customer No_]=cust.[No_]
        where year([Order Date])='".$year."' and month([Order Date])='".$month."' and [Document Type] = '1' and cust.[Service Zone Code]='".$slscode."'
        group by [Order Date]) as tbl_so2 on(tbl_so.[Order Date]=tbl_so2.order_date2) order by tbl_so.[Order Date]";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function cs_data_from_user($user){
        $db = $this->load->database('default', true);
        $query_temp = "select name,userid_1 FROM `user` u where (userid_1!=null or userid_1!='') and userid_1 like 'CS-%' and userid_1 like '%".$user."%' order by userid_1;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function cs_sales_by_period($year, $month, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_cs_sales_by_period('".$year."', '".$month."', '".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_product_review_cust_qty_report($year, $last_year, $last_2year, $months, $cust_code){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."'  and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.",";
          }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            order by tbl_item.item_no;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_product_review_cust_amount_report($year, $last_year, $last_2year, $months, $cust_code){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty,line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){ $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no,d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            order by tbl_item.item_no;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    // 2023-03-10
    function get_category_by_name($name){
      $db = $this->load->database('default2', true);
      $query_temp = "select distinct(item_category_codee) as cat FROM mst_item m where name like '".$name."%' order by cat;";
      $query = $db->query($query_temp);
      return $query->result_array();
    }

    function get_custproductcat_all($year,$cat,$name,$cust_code){
        $db = $this->load->database('default2', true);

        $query_temp = "select customer, cust_name,";
        foreach($cat as $row){
            $query_temp.="qty_".$row["cat"].", amount_".$row["cat"].", gp_amount_".$row["cat"].", cost_amount_".$row["cat"].", if(gp_percent_".$row["cat"]." is null,0,gp_percent_".$row["cat"].") as gp_percent_".$row["cat"].",";
        }

        $query_temp.= " qty_total, amount_total, gp_amount_total, if(gp_percent_total is null,0,gp_percent_total) as gp_percent_total ";

        $query_temp.=" from (
            select customer, cust_name, ";

        foreach($cat as $row){
            $query_temp.="qty_".$row["cat"].", round(amount_".$row["cat"].",2) as amount_".$row["cat"].",
            round(gp_amount_".$row["cat"].",2) as gp_amount_".$row["cat"].", round(cost_amount_".$row["cat"].",2) as cost_amount_".$row["cat"].",
            round((gp_amount_".$row["cat"]."/amount_".$row["cat"].")*100,2) as gp_percent_".$row["cat"].",";
        }

        $query_temp.="(";
        foreach($cat as $row){ $query_temp.="qty_".$row["cat"]."+"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=") as qty_total,";

        $query_temp.="round(";
        foreach($cat as $row){ $query_temp.="amount_".$row["cat"]."+"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=",2) as amount_total,";

        $query_temp.="round(";
        foreach($cat as $row){ $query_temp.="gp_amount_".$row["cat"]."+"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=",2) as gp_amount_total,";

        $query_temp.="round((";
        foreach($cat as $row){ $query_temp.="gp_amount_".$row["cat"]."+"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=") / ";

        $query_temp.="(";
        foreach($cat as $row){ $query_temp.="amount_".$row["cat"]."+"; }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=") * 100,2) as gp_percent_total ";

        $query_temp.=" from (
        select customer, cust_name, ";

        foreach($cat as $row){
            $query_temp.="sum(if(item_category_codee = '".$row["cat"]."', quantity,0)) as qty_".$row["cat"].",  sum(if(item_category_codee = '".$row["cat"]."', amount,0)) as amount_".$row["cat"].",
            sum(if(item_category_codee = '".$row["cat"]."', gp_amount,0)) as gp_amount_".$row["cat"].", sum(if(item_category_codee = '".$row["cat"]."', cost_amount,0)) as cost_amount_".$row["cat"].",";
        }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" from (
          select no, customer, posting_date, item_no, amount, line_no, quantity, cost_amount, (amount-cost_amount) as gp_amount ,
          item.name as item_name, item.manufacture_codee, item_category_codee, product_group_codee, cust.name as cust_name
          from (
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,
          d.line_amount as amount, line_no,quantity, (quantity*unit_cost_lcy) as cost_amount
                        FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                        where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
                        union
                        SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.line_amount*-1) as amount, line_no,quantity, (quantity*unit_cost_lcy*-1) as cost_amount
                        FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                        where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."'  and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_cm

          inner join mst_item item on(item.code = tbl_si_cm.item_no)
          inner join mst_cust cust on(cust.cust_no = tbl_si_cm.customer) where item.name like '".$name."%') as tbl_si_cm_cat
          group by customer, cust_name) as tbl_si_cm_cat) as tbl_si_cm_cat;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_custproductcat_customer($year,$cat,$name,$cust_code){
          $db = $this->load->database('default2', true);

          $query_temp = "select customer, cust_name,item_name,";
          foreach($cat as $row){
              $query_temp.="qty_".$row["cat"].", amount_".$row["cat"].", gp_amount_".$row["cat"].", cost_amount_".$row["cat"].", if(gp_percent_".$row["cat"]." is null,0,gp_percent_".$row["cat"].") as gp_percent_".$row["cat"].",";
          }

          $query_temp.= " qty_total, amount_total, gp_amount_total, if(gp_percent_total is null,0,gp_percent_total) as gp_percent_total ";

          $query_temp.=" from (
              select customer, cust_name, item_name,";

          foreach($cat as $row){
              $query_temp.="qty_".$row["cat"].", round(amount_".$row["cat"].",2) as amount_".$row["cat"].",
              round(gp_amount_".$row["cat"].",2) as gp_amount_".$row["cat"].", round(cost_amount_".$row["cat"].",2) as cost_amount_".$row["cat"].",
              round((gp_amount_".$row["cat"]."/amount_".$row["cat"].")*100,2) as gp_percent_".$row["cat"].",";
          }

          $query_temp.="(";
          foreach($cat as $row){ $query_temp.="qty_".$row["cat"]."+"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=") as qty_total,";

          $query_temp.="round(";
          foreach($cat as $row){ $query_temp.="amount_".$row["cat"]."+"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=",2) as amount_total,";

          $query_temp.="round(";
          foreach($cat as $row){ $query_temp.="gp_amount_".$row["cat"]."+"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=",2) as gp_amount_total,";

          $query_temp.="round((";
          foreach($cat as $row){ $query_temp.="gp_amount_".$row["cat"]."+"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=") / ";

          $query_temp.="(";
          foreach($cat as $row){ $query_temp.="amount_".$row["cat"]."+"; }
          $query_temp = substr($query_temp,0,-1);
          $query_temp.=") * 100,2) as gp_percent_total ";

          $query_temp.=" from (
          select customer, cust_name, item_name,";

          foreach($cat as $row){
              $query_temp.="sum(if(item_category_codee = '".$row["cat"]."', quantity,0)) as qty_".$row["cat"].",  sum(if(item_category_codee = '".$row["cat"]."', amount,0)) as amount_".$row["cat"].",
              sum(if(item_category_codee = '".$row["cat"]."', gp_amount,0)) as gp_amount_".$row["cat"].", sum(if(item_category_codee = '".$row["cat"]."', cost_amount,0)) as cost_amount_".$row["cat"].",";
          }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" from (
            select no, customer, posting_date, item_no, amount, line_no, quantity, cost_amount, (amount-cost_amount) as gp_amount ,
            item.name as item_name, item.manufacture_codee, item_category_codee, product_group_codee, cust.name as cust_name
            from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,
            d.line_amount as amount, line_no,quantity, (quantity*unit_cost_lcy) as cost_amount
                          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
                          union
                          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.line_amount*-1) as amount, line_no,quantity, (quantity*unit_cost_lcy*-1) as cost_amount
                          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                          where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."'  and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_cm

            inner join mst_item item on(item.code = tbl_si_cm.item_no)
            inner join mst_cust cust on(cust.cust_no = tbl_si_cm.customer) where item.name like '".$name."%') as tbl_si_cm_cat
            group by customer, cust_name, item_name) as tbl_si_cm_cat) as tbl_si_cm_cat;";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_pronto_pago($year, $cust_code){
        $db = $this->load->database('default2', true);
        $query_temp = "select if(amount is null,0,amount) as amount from ( select round(sum(line_amount),2) as amount FROM sales_cr_memo_line d where year(posting_date)='".$year."'  and description like '%PRONTO PAGO%' and sell_to_customer_no='".$cust_code."' ) as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->amount;
    }
    //--

    function get_volumen($year, $cust_code){
        $db = $this->load->database('default2', true);
        $query_temp = "select if(amount is null,0, amount) as amount from (
          SELECT sum(line_amount) as amount FROM sales_cr_memo_line d
          where year(posting_date)='".$year."'  and (description like '%VOLUME%' or description like '%VOL%') and sell_to_customer_no='".$cust_code."') as tbl;";
        $query = $db->query($query_temp)->row();
        return $query->amount;
    }
    //---

    function get_item_promo_amount_qty($year, $cust_code){
        $db = $this->load->database('default2', true);
        $query_temp = "select
          if(amount is null,0,round(amount,2)) as amount,
          if(quantity is null,0,quantity) as quantity
          from (
          select sum(amount) as amount, sum(quantity) as quantity
          from (
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,
          d.line_amount as amount, line_no,quantity, (quantity*unit_cost_lcy) as cost_amount
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."' and d.no in(select item_code from mst_item_promo where type='1')
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.line_amount*-1) as amount, line_no,quantity, (quantity*unit_cost_lcy*-1) as cost_amount
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."'  and (cm_d.type='2' and cm_d.no!='DISC') and cm_d.no in(select item_code from mst_item_promo where type='1')
           ) as tbl_a) tbl_a;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_item_review($this_year, $last_year, $last_2year, $last_3year, $last_4year){
          $db = $this->load->database('default2', true);

          $query_temp = "
          SELECT code, name,item_category_codee,

            if(qty_".$this_year." is null,0,qty_".$this_year.") as qty_".$this_year.",
            if(qty_".$last_year." is null,0,qty_".$last_year.") as qty_".$last_year.",
            if(qty_".$last_2year." is null,0,qty_".$last_2year.") as qty_".$last_2year.",
            if(qty_".$last_3year." is null,0,qty_".$last_3year.") as qty_".$last_3year.",
            if(qty_".$last_4year." is null,0,qty_".$last_4year.") as qty_".$last_4year.",

            if(cust_buy_".$this_year." is null,0,cust_buy_".$this_year.") as cust_buy_".$this_year.",
            if(cust_buy_".$last_year." is null,0,cust_buy_".$last_year.") as cust_buy_".$last_year.",
            if(cust_buy_".$last_2year." is null,0,cust_buy_".$last_2year.") as cust_buy_".$last_2year.",
            if(cust_buy_".$last_3year." is null,0,cust_buy_".$last_3year.") as cust_buy_".$last_3year.",
            if(cust_buy_".$last_4year." is null,0,cust_buy_".$last_4year.") as cust_buy_".$last_4year.",

            if(cogs is null,0,round(cogs,2)) as cogs,

            if(gp_percent_".$this_year." is null,0,round(gp_percent_".$this_year.",2)) as gp_percent_".$this_year.",
            if(gp_percent_".$last_year." is null,0,round(gp_percent_".$last_year.",2)) as gp_percent_".$last_year.",

            if(avg_sell_price is null,0,round(avg_sell_price,2)) as avg_sell_price

            FROM mst_item

            left join(
            select item_no, sum(qty) as qty_".$this_year." from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$this_year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$this_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_".$this_year." group by item_no) as tbl_si_".$this_year."
            on(tbl_si_".$this_year.".item_no = mst_item.code)

            left join(
            select item_no, sum(qty) as qty_".$last_year." from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_".$last_year." group by item_no) as tbl_si_".$last_year."
            on(tbl_si_".$last_year.".item_no = mst_item.code)

            left join(
            select item_no, sum(qty) as qty_".$last_2year." from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_2year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_".$last_2year." group by item_no) as tbl_si_".$last_2year."
            on(tbl_si_".$last_2year.".item_no = mst_item.code)

            left join(
            select item_no, sum(qty) as qty_".$last_3year." from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_3year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$last_3year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_".$last_3year." group by item_no) as tbl_si_".$last_3year."
            on(tbl_si_".$last_3year.".item_no = mst_item.code)

            left join(
            select item_no, sum(qty) as qty_".$last_4year." from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_4year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$last_4year."') and (cm_d.type='2' and cm_d.no!='DISC')) as si_".$last_4year." group by item_no) as tbl_si_".$last_4year."
            on(tbl_si_".$last_4year.".item_no = mst_item.code)

            left join(
            SELECT d.no as item_no, count(h.bill_to_customer_no) as cust_buy_".$this_year."
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$this_year."') group by d.no) as tbl_cust_buy_".$this_year." on(tbl_cust_buy_".$this_year.".item_no=mst_item.code)

            left join(
            SELECT d.no as item_no, count(h.bill_to_customer_no) as cust_buy_".$last_year."
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_year."') group by d.no) as tbl_cust_buy_".$last_year." on(tbl_cust_buy_".$last_year.".item_no=mst_item.code)

            left join(
            SELECT d.no as item_no, count(h.bill_to_customer_no) as cust_buy_".$last_2year."
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_2year."') group by d.no) as tbl_cust_buy_".$last_2year." on(tbl_cust_buy_".$last_2year.".item_no=mst_item.code)

            left join(
            SELECT d.no as item_no, count(h.bill_to_customer_no) as cust_buy_".$last_3year."
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_3year."') group by d.no) as tbl_cust_buy_".$last_3year." on(tbl_cust_buy_".$last_3year.".item_no=mst_item.code)

            left join(
            SELECT d.no as item_no, count(h.bill_to_customer_no) as cust_buy_".$last_4year."
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_4year."') group by d.no) as tbl_cust_buy_".$last_4year." on(tbl_cust_buy_".$last_4year.".item_no=mst_item.code)

            left join(
            SELECT d.no as item_no, sum((quantity*unit_cost_lcy))/sum(quantity) as cogs
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$this_year."') group by d.no) as tbl_cogs on(tbl_cogs.item_no=mst_item.code)

            left join(
            select item_no, round((sum((line_amount-line_cost_amount))/sum(line_amount))*100,2) as gp_percent_".$this_year." from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount,
                  (quantity*d.unit_cost_lcy) as line_cost_amount
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$this_year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$this_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_gp_percent_".$this_year." group by item_no) as tbl_gp_".$this_year." on(tbl_gp_".$this_year.".item_no=mst_item.code)

            left join(
            select item_no, round((sum((line_amount-line_cost_amount))/sum(line_amount))*100,2) as gp_percent_".$last_year." from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount,
                  (quantity*d.unit_cost_lcy) as line_cost_amount
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_gp_percent_".$last_year." group by item_no) as tbl_gp_".$last_year." on(tbl_gp_".$last_year.".item_no=mst_item.code)

            left join(
            select item_no, (amount/qty) as avg_sell_price
            from(
            select item_no, sum(qty) as qty, sum(amount) as amount
            from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no,amount
                  FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                  where year(h.posting_date) in ('".$last_year."')
                  union
                  SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no,(amount*-1) as amount
                  FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                  where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_avg_sell_price group by item_no) as tbl_avg_sell_price
            ) as tbl_avg_sell_price on(tbl_avg_sell_price.item_no = mst_item.code)
          ";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_selling_price_cust_item($cust,$item){
      $db = $this->load->database('sql_server_live', true);

      $query_temp = "select [Item No_] as item_no,[Sales Type] as sales_type, [Sales Code] as sales_code,[Currency Code] as currency_code,
      [Minimum Quantity] as min_qty,[Unit Price] as unit_price, [Starting Date] as starting_date, [Ending Date] as ending_date
      from [".$this->config->item('sqlserver_live')."Sales Price] where [Ending Date] >= getdate() and [Sales Code]='".$cust."' and [Sales Type]='0'
      and [Item No_]='".$item."'

      union

      select [Item No_],[Sales Type], [Sales Code],[Currency Code],[Minimum Quantity],[Unit Price],[Starting Date],[Ending Date]
      from [".$this->config->item('sqlserver_live')."Sales Price] where [Ending Date] >= getdate()
      and [Sales Type]='1' and [Sales Code]=(select [Customer Price Group] from [".$this->config->item('sqlserver_live')."Customer] where [No_]='".$cust."')
      and [Item No_] = '".$item."'

      order by [Sales Type],[Sales Code];";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_item_nav_local(){
      $db = $this->load->database('default2', true);
      $query = $db->query("SELECT * FROM mst_item m");
      return $query->result_array();
    }
    //---

    function get_crosref_company(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT * FROM crosref_company";
        $query = $db->query($query_temp);
        return $query->result_array();
    }

    //---

    function get_cross_reference($company){
        $db = $this->load->database('default2', true);

        $query_temp = "select * from ( SELECT item_code, ";
        foreach($company as $row){
            $query_temp.="max(if(company_id = '".$row["id"]."',cros_item,'')) as ".$row["id"].",";
        }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM crosref_item c inner join crosref_company cp on(c.company_id = cp.id) group by item_code) as tbl_a left join mst_item item on(item.code=tbl_a.item_code);";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_cross_reference_with_search($company,$search){
        $db = $this->load->database('default2', true);

        $query_temp = "select * from ( SELECT item_code, ";
        foreach($company as $row){
            $query_temp.="max(if(company_id = '".$row["id"]."',cros_item,'')) as ".$row["id"].",";
        }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" FROM crosref_item c inner join crosref_company cp on(c.company_id = cp.id) group by item_code) as tbl_a left join mst_item item on(item.code=tbl_a.item_code) where item_code like '%".$search."%' or ";

        foreach($company as $row){
            $query_temp.= " ".$row["id"]." like '%".$search."%' or";
        }
        $query_temp = substr($query_temp,0,-2);

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_application_distinct(){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT distinct(application) as application FROM crosref_app c;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_application_by_search($search){
        $db = $this->load->database('default2', true);
        $query_temp = "SELECT * FROM crosref_app c where application like '%".$search."%';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-04-17
    function get_customer_by_cs_code_nav_local($cs){
        $db = $this->load->database('default2', true);
        if($cs == "") $query_temp = "SELECT * FROM mst_cust m;";
        else $query_temp = "SELECT * FROM mst_cust m where cs_person='".$cs."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-04-17
    function get_invoices_by_period_customer($from, $to, $cust_no, $doc_type, $brand, $brand_not){
        $db = $this->load->database('default2', true);

        if($doc_type == "invc"){
            $query_temp = "select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no,'INVC' as doc_type,  d.line_no,d.location_code,d.no as item_code,
              d.sell_to_customer_no,d.description,d.quantity, 'MXN' as currency_code, round(d.unit_price,2) as unit_price, round(d.amount,2) as amount, d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
              d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname, h.ship_to_city, h.ship_to_county, h.ship_to_code, your_ref
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              left join mst_salesman slsman on(slsman.code=h.sales_person_code)
              where h.posting_date between '".$from."' and '".$to."' and d.no ".$brand_not." like '".$brand."%'
              and h.bill_to_customer_no like '%".$cust_no."%';";
        }
        else if($doc_type == "cm"){
          $query_temp = "select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no, 'CN' as doc_type, d.line_no,d.location_code,d.no as item_code,
            d.sell_to_customer_no,d.description,d.quantity, 'MXN' as currency_code, round(d.unit_price,2) as unit_price, round(d.amount,2) as amount, d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
            d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname, h.ship_to_city, h.ship_to_county, '' as your_ref
            FROM sales_cr_memo_header h inner join sales_cr_memo_line d on(h.no=d.document_no)
            left join mst_salesman slsman on(slsman.code=h.sales_person_code)
            where h.posting_date between '".$from."' and '".$to."' and d.no ".$brand_not." like '".$brand."%'
            and h.bill_to_customer_no like '%".$cust_no."%';";
        }
        else if($doc_type == "all"){
          $query_temp = "select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no,'INVC' as doc_type,  d.line_no,d.location_code,d.no as item_code,
              d.sell_to_customer_no,d.description,d.quantity, 'MXN' as currency_code, round(d.unit_price,2) as unit_price, round(d.amount,2) as amount, d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
              d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname, h.ship_to_city, h.ship_to_county, your_ref
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              left join mst_salesman slsman on(slsman.code=h.sales_person_code)
              where h.posting_date between '".$from."' and '".$to."' and d.no ".$brand_not." like '".$brand."%'
              and h.bill_to_customer_no like '%".$cust_no."%'

              union

              select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no, 'CN' as doc_type,  d.line_no,d.location_code,d.no as item_code,
              d.sell_to_customer_no,d.description,d.quantity*-1, 'MXN' as currency_code, round(d.unit_price*-1,2) as unit_price, round(d.amount*-1,2) as amount, d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
              d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname, h.ship_to_city, h.ship_to_county, '' as your_ref
              FROM sales_cr_memo_header h inner join sales_cr_memo_line d on(h.no=d.document_no)
              left join mst_salesman slsman on(slsman.code=h.sales_person_code)
              where h.posting_date between '".$from."' and '".$to."' and d.no ".$brand_not." like '".$brand."%'
              and h.bill_to_customer_no like '%".$cust_no."%';";
        }

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-04-17
    function get_8020_review_qty_report($year, $last_year, $last_2year, $months, $brand, $cat){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."')  and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.",";
          }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_8020_review_amount_report($year, $last_year, $last_2year, $months, $brand, $cat){
        $db = $this->load->database('default2', true);

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty,line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){ $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no,d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%') as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_item_moving($today, $last_120days, $last_180days, $last_240days){
        $db = $this->load->database('default2', true);

        $query_temp = "select code, name,
            if(tbl_last_120days.qty is null,0,tbl_last_120days.qty ) as last_120days_qty,
            if(tbl_last_180days.qty is null,0,tbl_last_180days.qty ) as last_180days_qty,
            if(tbl_last_240days.qty is null,0,tbl_last_240days.qty ) as last_240days_qty,
            if(tbl_last_120days.amount is null,0,round(tbl_last_120days.amount,2) ) as last_120days_amount,
            if(tbl_last_180days.amount is null,0,round(tbl_last_180days.amount,2) ) as last_180days_amount,
            if(tbl_last_240days.amount is null,0,round(tbl_last_240days.amount,2) ) as last_240days_amount
            from mst_item as tbl_item

            left join (
              select item_no, sum(qty) as qty, sum(line_amount) as amount
              from (
                SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
                FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                where h.posting_date between '".$last_120days."' and '".$today."'
                union
                SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no,
                (cm_d.line_amount*-1) as line_amount
                FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                where cm_h.posting_date between '".$last_120days."' and '".$today."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_invc group by item_no
            ) as tbl_last_120days on(tbl_last_120days.item_no=tbl_item.code)

            left join (
              select item_no, sum(qty) as qty, sum(line_amount) as amount
              from (
                SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
                FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                where h.posting_date between '".$last_180days."' and '".$today."'
                union
                SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no,
                (cm_d.line_amount*-1) as line_amount
                FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                where cm_h.posting_date between '".$last_180days."' and '".$today."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_invc group by item_no
            ) as tbl_last_180days on(tbl_last_180days.item_no=tbl_item.code)

            left join (
              select item_no, sum(qty) as qty, sum(line_amount) as amount
              from (
                SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
                FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                where h.posting_date between '".$last_240days."' and '".$today."'
                union
                SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no,
                (cm_d.line_amount*-1) as line_amount
                FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                where cm_h.posting_date between '".$last_240days."' and '".$today."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_invc group by item_no
            ) as tbl_last_240days on(tbl_last_240days.item_no=tbl_item.code)";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-10
    function get_cust_review2_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months, $firstdate_this_year, $lastdate_this_year){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, name, payment_terms_code,sales_person_code, slsname, cs_person, cs_name,
          if(tbl_si_last_2year.qty is null,0,tbl_si_last_2year.qty) as si_qty_last_2year,
          if(tbl_si_last_year.qty is null,0,tbl_si_last_year.qty) as si_qty_last_year,
          if(avg_collection_days is null,0,avg_collection_days) as avg_collection_days,
          if(dist_cost_percent is null,0, dist_cost_percent) as dist_cost_percent, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
            SELECT cust_no, m.name as name, payment_terms_code,sales_person_code, slsman.name as slsname, cs_person, u.name as cs_name FROM mst_cust m
            left join mst_salesman slsman on(slsman.code=m.sales_person_code)
            left join tpimx_wms.user u on(u.userid_1=m.cs_person)
            where cust_no like '1%' or cust_no like '2%') as tbl_cust

          left join(
          select customer, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_si_this_year
            on(tbl_si_this_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_year group by customer) as tbl_si_last_year
            on(tbl_si_last_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_2year group by customer) as tbl_si_last_2year
            on(tbl_si_last_2year.customer=tbl_cust.cust_no)

            left join(
              select cust_no as customer, round(total_days/total_count,2) as avg_collection_days
              from (
              select cust_no, sum(total_days) as total_days, count(cust_no) as total_count from (
              select *,datediff(payment_date,invc_date) as total_days from (
              select tbl_payment.entry_no, tbl_payment.cust_no, tbl_payment.document_no as payment_doc, tbl_payment.posting_date as payment_date, tbl_payment.closed_by_entry_no,
              tbl_invc.cust_ledger_entry_no, tbl_invc.posting_date as invc_date, tbl_invc.doc_invc
              from (
              SELECT entry_no, cust_no,document_no, closed_by_entry_no, posting_date FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') as tbl_payment

              inner join(
              select tbl_cust_payment.document_no as doc_payment, cust_ledger_entry_no, posting_date, tbl_invc.document_no as doc_invc
              from (
              SELECT document_no, cust_ledger_entry_no
              FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment

              inner join(
              SELECT * FROM cust_ldgr_ent c where entry_no in(select cust_ledger_entry_no from (
              SELECT document_no, cust_ledger_entry_no
              FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
              and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment)) as tbl_invc
              on tbl_invc.entry_no=tbl_cust_payment.cust_ledger_entry_no) as tbl_invc on(tbl_invc.doc_payment=tbl_payment.document_no)) as tbl_payment_invc) as tbl_payment_invc
              group by cust_no ) as tbl_payment_invc) as tbl_payment_invc
              on(tbl_payment_invc.customer=tbl_cust.cust_no)

            left join(
              select customer, line_amount, if(total_dist is null, 0, total_dist) as total_dist, if(total_dist is null,0,round((total_dist/line_amount)*100,2)) as dist_cost_percent
              from (
              select customer, sum(line_amount) as line_amount from (
                SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
                (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
                FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                where year(h.posting_date) in ('".$year."')
                union
                SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
                (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
                FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_cust_invc

              left join (
              SELECT cust_no, sum(amount) total_dist
              FROM gl_entry g where gl_account_no='8423' and document_type='2' and posting_date between '".$firstdate_this_year."' and '".$lastdate_this_year."' group by cust_no
              ) as tbl_cost_transporter on(tbl_cost_transporter.cust_no=tbl_cust_invc.customer)) as tbl_dist_cost
              on (tbl_dist_cost.customer=tbl_cust.cust_no)

              where tbl_si_last_2year.qty is not null or tbl_si_last_year.qty is not null or line_amount_this_year is not null ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-05-10
    function get_cust_review2_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, name, payment_terms_code,sales_person_code, slsname, cs_person, cs_name,
          if(tbl_si_last_2year.line_amount is null,0,tbl_si_last_2year.line_amount) as si_qty_last_2year,
          if(tbl_si_last_year.line_amount is null,0,tbl_si_last_year.line_amount) as si_qty_last_year,
          if(avg_collection_days is null,0,avg_collection_days) as avg_collection_days,";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
            SELECT cust_no, m.name as name, payment_terms_code,sales_person_code, slsman.name as slsname, cs_person, u.name as cs_name FROM mst_cust m
            left join mst_salesman slsman on(slsman.code=m.sales_person_code)
            left join tpimx_wms.user u on(u.userid_1=m.cs_person)
            where cust_no like '1%' or cust_no like '2%') as tbl_cust

          left join(
          select customer, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer) as tbl_si_this_year
            on(tbl_si_this_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(qty) as qty, sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_year group by customer) as tbl_si_last_year
            on(tbl_si_last_year.customer=tbl_cust.cust_no)

            left join(
            select customer,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_last_2year group by customer) as tbl_si_last_2year
            on(tbl_si_last_2year.customer=tbl_cust.cust_no)

            left join(
              select cust_no as customer, round(total_days/total_count,2) as avg_collection_days
            from (
            select cust_no, sum(total_days) as total_days, count(cust_no) as total_count from (
            select *,datediff(payment_date,invc_date) as total_days from (
            select tbl_payment.entry_no, tbl_payment.cust_no, tbl_payment.document_no as payment_doc, tbl_payment.posting_date as payment_date, tbl_payment.closed_by_entry_no,
            tbl_invc.cust_ledger_entry_no, tbl_invc.posting_date as invc_date, tbl_invc.doc_invc
            from (
            SELECT entry_no, cust_no,document_no, closed_by_entry_no, posting_date FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') as tbl_payment

            inner join(
            select tbl_cust_payment.document_no as doc_payment, cust_ledger_entry_no, posting_date, tbl_invc.document_no as doc_invc
            from (
            SELECT document_no, cust_ledger_entry_no
            FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment

            inner join(
            SELECT * FROM cust_ldgr_ent c where entry_no in(select cust_ledger_entry_no from (
            SELECT document_no, cust_ledger_entry_no
            FROM cust_ldgr_ent_detail where document_no in (SELECT document_no FROM cust_ldgr_ent c where document_type='1' and closed_by_entry_no!=''
            and posting_date between '".$last_6months."' and '".$today."') and document_type='1' and entry_type='2' group by document_no) as tbl_cust_payment)) as tbl_invc
            on tbl_invc.entry_no=tbl_cust_payment.cust_ledger_entry_no) as tbl_invc on(tbl_invc.doc_payment=tbl_payment.document_no)) as tbl_payment_invc) as tbl_payment_invc
            group by cust_no ) as tbl_payment_invc) as tbl_payment_invc
            on(tbl_payment_invc.customer=tbl_cust.cust_no)

            where tbl_si_last_2year.line_amount is not null or tbl_si_last_year.line_amount is not null or line_amount_this_year is not null ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-05-25
    function salesnational_salesvsbudget_salesperson_type($year, $month, $type, $cust_pref){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_salesvsbudget_salesperson_type('".$year."', '".$month."','".$type."','".$cust_pref."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    //2023-05-25
    function salesnational_salestrendvsbudget_type($year,  $type, $cust_pref){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_salestrendvsbudget_type('".$year."','".$type."','".$cust_pref."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-25
    function salesnational_actual_netsales_mtd_type($year, $month, $type, $cust_pref){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_actual_netsales_mtd_type('".$year."', '".$month."','".$type."','".$cust_pref."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-25
    function salesnational_actual_netsales_ytd_type($year,$month, $type, $cust_pref){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesnational_actual_netsales_ytd_type('".$year."', '".$month."','".$type."','".$cust_pref."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-06-02
    function get_total_value_backorder_without_stock(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select sum(amount) as total_bo_value from (
          select  [Document No_], [Line No_] ,[no_],[Outstanding Quantity], [Unit Price],qty_stock,
          [Outstanding Quantity]*[Unit Price] as amount
          from (
          select [Document No_], [Line No_] ,[no_],[Outstanding Quantity], [Unit Price]
          from [".$this->config->item('sqlserver_live')."Sales Line] where [Document Type]='1' and [Outstanding Quantity] > 0) as tbl_si_line

          left join(
          select [Item No_],sum([Remaining Quantity]) as qty_stock
                    from [".$this->config->item('sqlserver_live')."Item Ledger Entry]
          		  where [Remaining Quantity] > 0 and ([Location Code] not like 'MX%'
          		  and [Location Code]!='WH2_QRTN') and [Item No_]
          		  in ( select distinct([No_]) as item_no
          from [".$this->config->item('sqlserver_live')."Sales Line] where [Document Type]='1' and [Outstanding Quantity] > 0 ) group by [Item No_]
          ) as tbl_item_qty on(tbl_item_qty.[Item No_]=tbl_si_line.[No_])
          where [Outstanding Quantity] > qty_stock or qty_stock is null) as tbl_a;";

          $query = $db->query($query_temp)->row();
          return $query->total_bo_value;
    }
    //--

    // 2023-06-09
    function get_backorder_with_item_sn(){
      $db = $this->load->database('default2', true);
      /*$query_temp = "select *
        FROM tpimx_nav.sales_backorder bo
        left join(
        SELECT item_code, count(item_code) as qty_available FROM tpimx_wms.tsc_item_sn where statuss='1'  group by item_code) as tbl_sn on(tbl_sn.item_code=bo.item_no)
        left join(
        SELECT item_no as item_no_incoming, estimation_arrived
        FROM item_incoming i where statuss='0' group by item_no) as tbl_incoming on(tbl_incoming.item_no_incoming=bo.item_no)
        left join mst_salesman slsman on(slsman.code=bo.salesperson_code)
        left join mst_item_set itemset on(itemset.item_code=bo.item_no)";*/

      $query_temp = "select *
          FROM tpimx_nav.sales_backorder bo
          left join(
          SELECT item_no as item_code, sum(qty) as qty_available FROM item_invt_nav i group by item_no) as tbl_sn on(tbl_sn.item_code=bo.item_no)
          left join(
          SELECT item_no as item_no_incoming, estimation_arrived
          FROM item_incoming i where statuss='0' group by item_no) as tbl_incoming on(tbl_incoming.item_no_incoming=bo.item_no)
          left join mst_salesman slsman on(slsman.code=bo.salesperson_code)
          left join mst_item_set itemset on(itemset.item_code=bo.item_no)";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--

    // 2023-06-29
    function get_sales_top_by_name($name, $year, $month, $top){
      $db = $this->load->database('default2', true);
      $query_temp = "select pscd.id,mapname.name, item_code,description, sum(qty) as qty, round(sum(line_amount),2) as amount
          from (
          SELECT h.no as no, h.bill_to_customer_no, h.ship_to_post_code,d.quantity as qty, d.line_amount as line_amount,line_no,h.posting_date, d.no as item_code, d.description
                              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                              where year(h.posting_date) ='".$year."' and month(h.posting_date)='".$month."') as tbl_sales
                              inner join mst_map_postcode pscd on(tbl_sales.ship_to_post_code=pscd.post_code)
          inner join mst_map_name mapname on(mapname.id=pscd.id) where name ='".$name."' group by item_code order by amount desc limit ".$top.";";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--

    // 2023-07-04
    function get_list_cust_from_nav_db(){
        $db = $this->load->database('default2', true);
        $query_temp = "select cust_no, cust.name as cust_name, city, county,country_region_code, phone_no, vat_no, payment_terms_code, sales_person_code, sls.name as sls_name, cs_person, u.name as cs_name FROM mst_cust cust
          left join mst_salesman sls on(sls.code=cust.sales_person_code)
          left join tpimx_wms.user u on(u.userid_1=cust.cs_person) order by cust_no";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_sales_report_by_item_cat_year($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear,$jan_date){
        $db = $this->load->database('default2', true);

        $first_character = substr($cust_code, 0, 1);

        if($first_character == "1"){
          $item_desc = "item_desc";
          $group_by = ", item_desc";
        }
        else if($first_character == "2"){
          $item_desc = "''";
          $group_by = "";
        }

        $query_temp = "select item_category_code, item_desc as ".$item_desc.", sum(si_qty_last_2year) as si_qty_last_2year, sum(si_qty_last_year) as si_qty_last_year, sum(si_last_year_this_month) as si_last_year_this_month, sum(now_".$year.") as now_".$year.", ";

        foreach($months as $row){ $query_temp.= "sum(now_".$year."_".$row.") as now_".$year."_".$row.","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" ,sum(line_amount_this_year) as line_amount_this_year, sum(line_cost_amount_this_year) as line_cost_amount_this_year
        from ( ";

        $query_temp.= "select brand_desc, item_desc,tbl_item.item_no as item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year,
          if(si_last_year_this_month.qty is null,0,si_last_year_this_month.qty) as si_last_year_this_month,
          if(now_".$year." is null,0,now_".$year.") as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.="  ,
            if(tbl_si_this_year.line_amount is null,0,round(tbl_si_this_year.line_amount,2)) as line_amount_this_year,
            if(tbl_si_this_year.line_cost_amount is null,0,round(tbl_si_this_year.line_cost_amount,2)) as line_cost_amount_this_year,
            if(tbl_si_this_year.line_amount is null or tbl_si_this_year.line_cost_amount is null,0, round((tbl_si_this_year.line_amount-tbl_si_this_year.line_cost_amount)/tbl_si_this_year.line_amount*100,2))  as gp_percent
          from (
            select item_no,item_category_code,tbl_item_wms.name as item_desc,mst_brand.description as brand_desc from (
            select distinct(tbl_item.item_no) as item_no,item_category_code from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

              union

              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, item_category_code,(cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item
            ) as tbl_item inner join mst_brand on(tbl_item.item_category_code=mst_brand.item_cat)
            inner join tpimx_wms.mst_item as tbl_item_wms on(tbl_item.item_no=tbl_item_wms.code)) as tbl_item

            left join (
            select item_no,line_cost_amount, line_amount, sum(if(year(posting_date)='".$year."',qty,0)) as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount, line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where h.posting_date between '".$jan_date."' and '".$today_lastyear."' and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where cm_h.posting_date between '".$jan_date."' and '".$today_lastyear."' and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year_this_month
            on(si_last_year_this_month.item_no=tbl_item.item_no) ) as tbl group by item_category_code ".$group_by;

            $query = $db->query($query_temp);
            return $query->result_array();
    }
    //---

    function get_sales_report_by_item_cat_year_amount($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear,$jan_date){
        $db = $this->load->database('default2', true);

        $first_character = substr($cust_code, 0, 1);

        if($first_character == "1"){
          $item_desc = "item_desc";
          $group_by = ", item_desc";
        }
        else if($first_character == "2"){
          $item_desc = "''";
          $group_by = "";
        }

        $query_temp = "select item_category_code, item_desc as ".$item_desc.", sum(si_qty_last_2year) as si_qty_last_2year, sum(si_qty_last_year) as si_qty_last_year, sum(si_last_year_this_month) as si_last_year_this_month, sum(now_".$year.") as now_".$year.", ";

        foreach($months as $row){ $query_temp.= "sum(now_".$year."_".$row.") as now_".$year."_".$row.","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" ,sum(line_amount_this_year) as line_amount_this_year, sum(line_cost_amount_this_year) as line_cost_amount_this_year
        from ( ";

        $query_temp.= "select brand_desc, item_desc,tbl_item.item_no as item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year,
          if(si_last_year_this_month.line_amount is null,0,si_last_year_this_month.line_amount) as si_last_year_this_month,
          if(now_".$year." is null,0,now_".$year.") as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" , if(tbl_si_this_year.line_amount is null,0,round(tbl_si_this_year.line_amount,2)) as line_amount_this_year,
          if(tbl_si_this_year.line_cost_amount is null,0,round(tbl_si_this_year.line_cost_amount,2)) as line_cost_amount_this_year,
          if(tbl_si_this_year.line_amount is null or tbl_si_this_year.line_cost_amount is null,0, round((tbl_si_this_year.line_amount-tbl_si_this_year.line_cost_amount)/tbl_si_this_year.line_amount*100,2))  as gp_percent
          from (
            select item_no,item_category_code,tbl_item_wms.name as item_desc,mst_brand.description as brand_desc from (
            select distinct(tbl_item.item_no) as item_no,item_category_code from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no='".$cust_code."'

              union

              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, item_category_code,(cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item
            ) as tbl_item inner join mst_brand on(tbl_item.item_category_code=mst_brand.item_cat)
            inner join tpimx_wms.mst_item as tbl_item_wms on(tbl_item.item_no=tbl_item_wms.code)) as tbl_item

            left join (
            select item_no, line_cost_amount, line_amount, sum(if(year(posting_date)='".$year."',line_amount,0)) as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) between '".$jan_date."' and '".$today_lastyear."' and h.bill_to_customer_no='".$cust_code."'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) between '".$jan_date."' and '".$today_lastyear."' and cm_h.bill_to_customer_no='".$cust_code."' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year_this_month
            on(si_last_year_this_month.item_no=tbl_item.item_no) ) as tbl group by item_category_code ".$group_by;

            $query = $db->query($query_temp);
            return $query->result_array();
    }
    //---

    function customer_fill_rate($cust_no, $year, $type){
        $db = $this->load->database('default2', true);

        if($type == 1){
          $query_temp = "select year(order_date) as yearr, month(order_date) as monthh, sum(order_qty) as orderr, sum(proceed_qty) as proceed, sum(outstanding_qty) as outstanding,
          round(sum(proceed_qty)/sum(order_qty)*100,2)  as percent_fill_rate,round(sum(outstanding_qty)/sum(order_qty)*100,2)  as percent_outstanding
          FROM so_detail_monthly s where cust_no='".$cust_no."' and year(order_date)='".$year."' group by year(order_date), month(order_date);";
        }
        else if($type == 2){
          $query_temp = "select year(order_date) as yearr, month(order_date) as monthh, sum(order_amount) as orderr, sum(proceed_amount) as proceed, sum(outstanding_amount) as outstanding,
          round(sum(proceed_amount)/sum(order_amount)*100,2)  as percent_fill_rate,round(sum(outstanding_amount)/sum(order_amount)*100,2)  as percent_outstanding
          FROM so_detail_monthly s where cust_no='".$cust_no."' and year(order_date)='".$year."' group by year(order_date), month(order_date);";
        }

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_so_bo_nav($year, $month, $cust_no, $type){
        $db = $this->load->database('sql_server_live', true);

        if($type == "1"){
            $query_temp = "select
            year(order_date) as yearr, month(order_date) as monthh, sum(qty_order_amount) as orderr, sum(qty_proceed_amount) as proceed, sum(amount_outstanding) as outstanding,
            round(sum(qty_proceed_amount)/sum(qty_order_amount)*100,2)  as percent_fill_rate,round(sum(amount_outstanding)/sum(qty_order_amount)*100,2)  as percent_outstanding
            from (
            select h.[Order Date] as order_date, h.[No_] as so_no,h.[Sell-to Customer No_],h.[sell-to customer name],
            d.[Line No_],d.[No_] as item_no,d.[Unit Price],
            d.[Quantity] as qty_order,d.[Quantity]*d.[Unit Price] as qty_order_amount,
            d.[Quantity]-d.[Outstanding Quantity] as qty_proceed,
            (d.[Quantity]-d.[Outstanding Quantity])*d.[Unit Price] as qty_proceed_amount,
            [Outstanding Quantity] as qty_outstanding,
            [Outstanding Quantity]*d.[Unit Price] as amount_outstanding
            from [".$this->config->item('sqlserver_live')."Sales Header] as h
            inner join [".$this->config->item('sqlserver_live')."Sales Line] as d on(d.[Document No_]=h.[No_])
            where year([Order Date])='".$year."' and month([Order Date])='".$month."' and h.[Document Type]='1' and h.[Sell-to Customer No_]='".$cust_no."') as tbl group by  year(order_date), month(order_date);";
        }
        else if($type == "2"){
            $query_temp = "select
            year(order_date) as yearr, month(order_date) as monthh, sum(qty_order_amount) as orderr, sum(qty_proceed_amount) as proceed, sum(amount_outstanding) as outstanding,
            round(sum(qty_proceed_amount)/sum(qty_order_amount)*100,2)  as percent_fill_rate,round(sum(amount_outstanding)/sum(qty_order_amount)*100,2)  as percent_outstanding
            from (
            select h.[Order Date] as order_date, h.[No_] as so_no,h.[Sell-to Customer No_],h.[sell-to customer name],
            d.[Line No_],d.[No_] as item_no,d.[Unit Price],
            d.[Quantity] as qty_order,d.[Quantity]*d.[Unit Price] as qty_order_amount,
            d.[Quantity]-d.[Outstanding Quantity] as qty_proceed,
            (d.[Quantity]-d.[Outstanding Quantity])*d.[Unit Price] as qty_proceed_amount,
            [Outstanding Quantity] as qty_outstanding,
            [Outstanding Quantity]*d.[Unit Price] as amount_outstanding
            from [".$this->config->item('sqlserver_live')."Sales Header] as h
            inner join [".$this->config->item('sqlserver_live')."Sales Line] as d on(d.[Document No_]=h.[No_])
            where year([Order Date])='".$year."' and month([Order Date])='".$month."' and h.[Document Type]='1' and h.[Sell-to Customer No_]='".$cust_no."') as tbl group by  year(order_date), month(order_date);";
        }

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_bo_nav_by_customer($cust_no, $type){
        $db = $this->load->database('sql_server_live', true);

        if($type == "1"){
            $query_temp = "select sum([Outstanding Quantity]) as outstanding
            from [".$this->config->item('sqlserver_live')."Sales Line] where [Sell-to Customer No_]='".$cust_no."' and [Document Type]='1' and [Outstanding Quantity] > 0;";
        }
        else if($type == "2"){
            $query_temp = "select sum([Outstanding Quantity]*[Unit Price]) as outstanding
            from [".$this->config->item('sqlserver_live')."Sales Line] where [Sell-to Customer No_]='".$cust_no."' and [Document Type]='1' and [Outstanding Quantity] > 0;";
        }


        $query = $db->query($query_temp)->row();
        return $query->outstanding;
    }
    //--

    //2023-07-28
    function salesman_actual_netsales_ytd_type($year, $month, $type, $cust_pref, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_actual_netsales_ytd_type('".$year."', '".$month."','".$type."','".$cust_pref."','".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-07-28
    function salesman_actual_netsales_mtd_type($year, $month, $type, $cust_pref, $slscode){
        $db = $this->load->database('default2', true);
        $query_temp = "call get_salesman_actual_netsales_mtd_type('".$year."', '".$month."','".$type."','".$cust_pref."','".$slscode."')";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-07-31
    function get_sales_report_by_item_cat_year_amount_mtd($year, $month, $slscode, $type){
        $db = $this->load->database('default2', true);

        if($type == 1){
          $query_temp = "select item_category_code, description, sum(qty) as qty, round(sum(line_amount),2) as amount
            from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount, line_amount,item_category_code, description
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            inner join mst_cust cust on(cust.cust_no=h.bill_to_customer_no)
            where year(h.posting_date)='".$year."' and month(h.posting_date)='".$month."' and cust.sales_person_code='".$slscode."' and h.bill_to_customer_no like '".$type."%' and item_category_code!=''
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,(line_amount*-1) as line_amount ,item_category_code, description
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            inner join mst_cust cust on(cust.cust_no=cm_h.bill_to_customer_no)
            where year(cm_h.posting_date)='".$year."' and month(cm_h.posting_date)='".$month."' and cust.sales_person_code='".$slscode."' and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$type."%' and item_category_code!='') as tbl group by item_category_code, description;";
        }
        else if($type == 2){
            $query_temp = "select item_category_code, '' as description, sum(qty) as qty, round(sum(line_amount),2) as amount
              from (
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount, line_amount,item_category_code, description
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              inner join mst_cust cust on(cust.cust_no=h.bill_to_customer_no)
              where year(h.posting_date)='".$year."' and month(h.posting_date)='".$month."' and cust.sales_person_code='".$slscode."' and h.bill_to_customer_no like '".$type."%' and item_category_code!=''
              union
              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,(line_amount*-1) as line_amount ,item_category_code, description
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              inner join mst_cust cust on(cust.cust_no=cm_h.bill_to_customer_no)
              where year(cm_h.posting_date)='".$year."' and month(cm_h.posting_date)='".$month."' and cust.sales_person_code='".$slscode."' and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$type."%' and item_category_code!='') as tbl group by item_category_code";
        }

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-07-31
    function get_sales_report_by_item_cat_year_amount_ytd($year, $month, $slscode, $type){
        $db = $this->load->database('default2', true);

        if($type == 1){
          $query_temp = "select item_category_code, description, sum(qty) as qty, round(sum(line_amount),2) as amount
            from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount, line_amount,item_category_code, description
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            inner join mst_cust cust on(cust.cust_no=h.bill_to_customer_no)
            where year(h.posting_date)='".$year."' and month(h.posting_date)<='".$month."' and cust.sales_person_code='".$slscode."' and h.bill_to_customer_no like '".$type."%' and item_category_code!=''
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,(line_amount*-1) as line_amount ,item_category_code, description
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            inner join mst_cust cust on(cust.cust_no=cm_h.bill_to_customer_no)
            where year(cm_h.posting_date)='".$year."' and month(cm_h.posting_date)<='".$month."' and cust.sales_person_code='".$slscode."' and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$type."%' and item_category_code!='') as tbl group by item_category_code, description;";
        }
        else if($type == 2){
          $query_temp = "select item_category_code, '' as description, sum(qty) as qty, round(sum(line_amount),2) as amount
            from (
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount, line_amount,item_category_code, description
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            inner join mst_cust cust on(cust.cust_no=h.bill_to_customer_no)
            where year(h.posting_date)='".$year."' and month(h.posting_date)<='".$month."' and cust.sales_person_code='".$slscode."' and h.bill_to_customer_no like '".$type."%' and item_category_code!=''
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,(line_amount*-1) as line_amount ,item_category_code, description
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            inner join mst_cust cust on(cust.cust_no=cm_h.bill_to_customer_no)
            where year(cm_h.posting_date)='".$year."' and month(cm_h.posting_date)<='".$month."' and cust.sales_person_code='".$slscode."' and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$type."%' and item_category_code!='') as tbl group by item_category_code;";
        }

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-07-31
    function salesman_fill_rate($slscode, $year){
        $db = $this->load->database('default2', true);

      $query_temp = "select year(order_date) as yearr, month(order_date) as monthh, sum(order_qty) as orderr, sum(proceed_qty) as proceed, sum(outstanding_qty) as outstanding, round(sum(proceed_qty)/sum(order_qty)*100,2)  as percent_fill_rate,round(sum(outstanding_qty)/sum(order_qty)*100,2)  as percent_outstanding, sum(outstanding_amount) as amount_outstanding
          FROM so_detail_monthly s
          inner join mst_cust cust on(cust.cust_no=s.cust_no)
          where sales_person_code='".$slscode."' and year(order_date)='".$year."' group by year(order_date), month(order_date);";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-07-31
    function salesman_so_bo_nav_by_month($year, $month, $slscode){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select year(order_date) as yearr, month(order_date) as monthh, sum(qty_order) as orderr, sum(qty_proceed) as proceed, sum(qty_outstanding) as outstanding, round(sum(qty_proceed)/sum(qty_order)*100,2)  as percent_fill_rate,round(sum(qty_outstanding)/sum(qty_order)*100,2)  as percent_outstanding,
        sum(amount_outstanding) as amount_outstanding
            from (
            select h.[Order Date] as order_date, h.[No_] as so_no,
            d.[Line No_],d.[No_] as item_no,d.[Unit Price],
            d.[Quantity] as qty_order,d.[Quantity]*d.[Unit Price] as qty_order_amount,
            d.[Quantity]-d.[Outstanding Quantity] as qty_proceed,
            (d.[Quantity]-d.[Outstanding Quantity])*d.[Unit Price] as qty_proceed_amount,
            [Outstanding Quantity] as qty_outstanding,
            [Outstanding Quantity]*d.[Unit Price] as amount_outstanding
            from [".$this->config->item('sqlserver_live')."Sales Header] as h
            inner join [".$this->config->item('sqlserver_live')."Sales Line] as d on(d.[Document No_]=h.[No_])
            inner join [".$this->config->item('sqlserver_live')."Customer] as cust on(cust.[No_]=h.[Sell-to Customer No_])
            where year([Order Date])='".$year."' and month([Order Date])='".$month."' and h.[Document Type]='1' and cust.[Salesperson Code]='".$slscode."') as tbl group by  year(order_date), month(order_date);";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-07-31
    function salesman_so_bo_nav($slscode){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select cust.[Salesperson Code], sum([Outstanding Quantity]) as outstanding, sum([Outstanding Quantity]*[Unit Price]) as amount_outstanding
        from [".$this->config->item('sqlserver_live')."Sales Line] as d
        inner join [".$this->config->item('sqlserver_live')."Customer] as cust on(cust.[No_]=d.[Sell-to Customer No_])
        where cust.[Salesperson Code]='".$slscode."' and [Document Type]='1' and [Outstanding Quantity] > 0 group by cust.[Salesperson Code];";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_sales_report_by_item_cat_all_year($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear,$jan_date){
        $db = $this->load->database('default2', true);

        if($cust_code == 1){
            $item_desc = "item_desc";
            $group_by = ", item_desc";
            $where = "where item_category_code in('HD','AT','MC','SMP')";
        }
        else{
            $item_desc = "''";
            $group_by = "";
            $where = "where item_category_code not in('HD','AT','MC','SMP')";
        }

        $query_temp = "select item_category_code, item_desc as ".$item_desc.", sum(si_qty_last_2year) as si_qty_last_2year, sum(si_qty_last_year) as si_qty_last_year, sum(si_last_year_this_month) as si_last_year_this_month, sum(now_".$year.") as now_".$year.", ";

        foreach($months as $row){ $query_temp.= "sum(now_".$year."_".$row.") as now_".$year."_".$row.","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" ,sum(line_amount_this_year) as line_amount_this_year, sum(line_cost_amount_this_year) as line_cost_amount_this_year
        from ( ";

        $query_temp.= "select brand_desc, item_desc,tbl_item.item_no as item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year,
          if(si_last_year_this_month.qty is null,0,si_last_year_this_month.qty) as si_last_year_this_month,
          if(now_".$year." is null,0,now_".$year.") as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.="  ,
            if(tbl_si_this_year.line_amount is null,0,round(tbl_si_this_year.line_amount,2)) as line_amount_this_year,
            if(tbl_si_this_year.line_cost_amount is null,0,round(tbl_si_this_year.line_cost_amount,2)) as line_cost_amount_this_year,
            if(tbl_si_this_year.line_amount is null or tbl_si_this_year.line_cost_amount is null,0, round((tbl_si_this_year.line_amount-tbl_si_this_year.line_cost_amount)/tbl_si_this_year.line_amount*100,2))  as gp_percent
          from (
            select item_no,item_category_code,tbl_item_wms.name as item_desc,mst_brand.description as brand_desc from (
            select distinct(tbl_item.item_no) as item_no,item_category_code from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no like '".$cust_code."%'

              union

              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, item_category_code,(cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item
            ) as tbl_item inner join mst_brand on(tbl_item.item_category_code=mst_brand.item_cat)
            inner join tpimx_wms.mst_item as tbl_item_wms on(tbl_item.item_no=tbl_item_wms.code)) as tbl_item

            left join (
            select item_no,line_cost_amount, line_amount, sum(if(year(posting_date)='".$year."',qty,0)) as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)) as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount, line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where h.posting_date between '".$jan_date."' and '".$today_lastyear."' and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where cm_h.posting_date between '".$jan_date."' and '".$today_lastyear."' and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year_this_month
            on(si_last_year_this_month.item_no=tbl_item.item_no) ) as tbl ".$where." group by item_category_code ".$group_by;

            $query = $db->query($query_temp);
            return $query->result_array();
    }
    //---

    function get_sales_report_by_item_cat_all_year_amount($cust_code, $year, $last_year, $last_2year, $months, $today_lastyear,$jan_date){
        $db = $this->load->database('default2', true);

        if($cust_code == 1){
            $item_desc = "item_desc";
            $group_by = ", item_desc";
            $where = "where item_category_code in('HD','AT','MC','SMP')";
        }
        else{
            $item_desc = "''";
            $group_by = "";
            $where = "where item_category_code not in('HD','AT','MC','SMP')";
        }

        $query_temp = "select item_category_code, item_desc as ".$item_desc.", sum(si_qty_last_2year) as si_qty_last_2year, sum(si_qty_last_year) as si_qty_last_year, sum(si_last_year_this_month) as si_last_year_this_month, sum(now_".$year.") as now_".$year.", ";

        foreach($months as $row){ $query_temp.= "sum(now_".$year."_".$row.") as now_".$year."_".$row.","; }
        $query_temp = substr($query_temp,0,-1);

        $query_temp.=" ,sum(line_amount_this_year) as line_amount_this_year, sum(line_cost_amount_this_year) as line_cost_amount_this_year
        from ( ";

        $query_temp.= "select brand_desc, item_desc,tbl_item.item_no as item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year,
          if(si_last_year_this_month.line_amount is null,0,si_last_year_this_month.line_amount) as si_last_year_this_month,
          if(now_".$year." is null,0,now_".$year.") as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" , if(tbl_si_this_year.line_amount is null,0,round(tbl_si_this_year.line_amount,2)) as line_amount_this_year,
          if(tbl_si_this_year.line_cost_amount is null,0,round(tbl_si_this_year.line_cost_amount,2)) as line_cost_amount_this_year,
          if(tbl_si_this_year.line_amount is null or tbl_si_this_year.line_cost_amount is null,0, round((tbl_si_this_year.line_amount-tbl_si_this_year.line_cost_amount)/tbl_si_this_year.line_amount*100,2))  as gp_percent
          from (
            select item_no,item_category_code,tbl_item_wms.name as item_desc,mst_brand.description as brand_desc from (
            select distinct(tbl_item.item_no) as item_no,item_category_code from(
              SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty, line_no
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in('".$year."','".$last_year."','".$last_2year."') and h.bill_to_customer_no like '".$cust_code."%'

              union

              SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, item_category_code,(cm_d.quantity*-1) as qty, line_no
              FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
              where year(cm_h.posting_date) in('".$year."','".$last_year."','".$last_2year."')  and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_item
            ) as tbl_item inner join mst_brand on(tbl_item.item_category_code=mst_brand.item_cat)
            inner join tpimx_wms.mst_item as tbl_item_wms on(tbl_item.item_no=tbl_item_wms.code)) as tbl_item

            left join (
            select item_no, line_cost_amount, line_amount, sum(if(year(posting_date)='".$year."',line_amount,0)) as now_".$year.", ";

          foreach($months as $row){ $query_temp.= "sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }
          $query_temp = substr($query_temp,0,-1);

          $query_temp.=" from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) between '".$jan_date."' and '".$today_lastyear."' and h.bill_to_customer_no like '".$cust_code."%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) between '".$jan_date."' and '".$today_lastyear."' and cm_h.bill_to_customer_no like '".$cust_code."%' and (cm_d.type='2' and cm_d.no!='DISC')) as si_last_year group by item_no) as si_last_year_this_month
            on(si_last_year_this_month.item_no=tbl_item.item_no) ) as tbl ".$where." group by item_category_code ".$group_by;

            $query = $db->query($query_temp);
            return $query->result_array();
    }
    //---

    // 2023-10-02
    function get_salesnational_sales_by_month_year($year){
      $db = $this->load->database('default2', true);
      $query_temp = "call get_salesnational_actual_netsales_by_month_year('".$year."')";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //--
    // 2024-02-26 posible a cambiar
    function get_sales_year($cust_code,$last_year, $last_2year){
      $db = $this->load->database('default2', true);
      $query_temp = "SELECT m.description as category,d.description,d.no as item_no
      ,SUM(IF(YEAR(h.posting_date)='".$last_2year."',quantity,0)) as last_2_year
      ,SUM(IF(YEAR(h.posting_date)='".$last_year."',quantity,0)) as last_year
      ,(SUM(IF(YEAR(h.posting_date)='".$last_2year."',quantity,0))-SUM(IF(YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference
      FROM sales_invoice_header h inner join sales_invoice_line d on (h.no=d.document_no)
      INNER JOIN mst_brand m on (d.item_category_code = m.item_cat)
      WHERE h.sell_to_customer_no='".$cust_code."' AND h.posting_date BETWEEN  '".$last_2year."-01-01' and '".$last_year."-12-31'
      group by d.no
      ORDER BY d.no;";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    // 2024-02-26
    function get_sales_month($cust_code, $year,$last_year, $month){
      $db = $this->load->database('default2', true);
      $query_temp = "SELECT * FROM (SELECT m.description as category,d.description,d.no as item_no
      ,SUM(IF(MONTH(h.posting_date)='".$month."' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as month_1
      ,SUM(IF(MONTH(h.posting_date)='".$month."' AND YEAR(h.posting_date)='".$year."',quantity,0)) as month_2
      ,(SUM(IF(MONTH(h.posting_date)='".$month."' AND YEAR(h.posting_date)='".$last_year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='".$month."' AND YEAR(h.posting_date)='".$year."',quantity,0))) as diference
      FROM sales_invoice_header h inner join sales_invoice_line d on (h.no=d.document_no)
      INNER JOIN mst_brand m on (d.item_category_code = m.item_cat)
      WHERE h.sell_to_customer_no='".$cust_code."' AND h.posting_date BETWEEN  '".$last_year."-01-01' and '".$year."-12-31'
      group by d.no ) as tbl where month_1 !=0 OR month_2 !=0;";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    // 
    function get_sales_month_comp($cust_code, $year,$last_year){
      $db = $this->load->database('default2', true);
      $query_temp = "SELECT m.description as category,d.description,d.no as item_no
      ,SUM(IF(YEAR(h.posting_date)=".$last_year."',quantity,0)) as total_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='01' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as ene_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='01' AND YEAR(h.posting_date)='".$year."',quantity,0)) as ene_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='01' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='01' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference01
      ,SUM(IF(MONTH(h.posting_date)='02' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as feb_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='02' AND YEAR(h.posting_date)='".$year."',quantity,0)) as feb_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='02' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='02' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference02
      ,SUM(IF(MONTH(h.posting_date)='03' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as mar_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='03' AND YEAR(h.posting_date)='".$year."',quantity,0)) as mar_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='03' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='03' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference03
      ,SUM(IF(MONTH(h.posting_date)='04' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as abr_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='04' AND YEAR(h.posting_date)='".$year."',quantity,0)) as abr_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='04' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='04' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference04
      ,SUM(IF(MONTH(h.posting_date)='05' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as may_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='05' AND YEAR(h.posting_date)='".$year."',quantity,0)) as may_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='05' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='05' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference05
      ,SUM(IF(MONTH(h.posting_date)='06' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as jun_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='06' AND YEAR(h.posting_date)='".$year."',quantity,0)) as jun_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='06' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='06' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference06
      ,SUM(IF(MONTH(h.posting_date)='07' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as jul_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='07' AND YEAR(h.posting_date)='".$year."',quantity,0)) as jul_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='07' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='07' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference07
      ,SUM(IF(MONTH(h.posting_date)='08' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as ago_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='08' AND YEAR(h.posting_date)='".$year."',quantity,0)) as ago_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='08' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='08' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference08
      ,SUM(IF(MONTH(h.posting_date)='09' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as sep_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='09' AND YEAR(h.posting_date)='".$year."',quantity,0)) as sep_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='09' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='09' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference09
      ,SUM(IF(MONTH(h.posting_date)='10' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as oct_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='10' AND YEAR(h.posting_date)='".$year."',quantity,0)) as oct_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='10' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='10' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference10
      ,SUM(IF(MONTH(h.posting_date)='11' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as nov_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='11' AND YEAR(h.posting_date)='".$year."',quantity,0)) as nov_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='08' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='11' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference11
      ,SUM(IF(MONTH(h.posting_date)='12' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as dic_".$last_year."
      ,SUM(IF(MONTH(h.posting_date)='12' AND YEAR(h.posting_date)='".$year."',quantity,0)) as dic_".$year."
      ,(SUM(IF(MONTH(h.posting_date)='12' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='12' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference12
      ,SUM(IF(YEAR(h.posting_date)='".$year."',quantity,0)) as total_".$year."
      ,(SUM(IF(YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference_year
      FROM sales_invoice_header h inner join sales_invoice_line d on (h.no=d.document_no)
      INNER JOIN mst_brand m on (d.item_category_code = m.item_cat)
      WHERE h.sell_to_customer_no='".$cust_code."' AND h.posting_date BETWEEN  '".$last_year."-01-01' and '".$year."-12-31'
      group by d.no;";
      debug($query_temp);
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    function get_sales_month_comp_now($cust_code, $year,$last_year, $months){
      $db = $this->load->database('default2', true);
      $query_temp = "SELECT m.description as category,d.description,d.no as item_no
      ,SUM(IF(YEAR(h.posting_date)='".$last_year."',quantity,0)) as total_".$last_year;
      foreach($months as $row){ 
        $query_temp.= "
        ,SUM(IF(MONTH(h.posting_date)='".$row."' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as ".$row."_".$last_year."
        ,SUM(IF(MONTH(h.posting_date)='".$row."' AND YEAR(h.posting_date)='".$year."',quantity,0)) as ".$row."_".$year."
        ,(SUM(IF(MONTH(h.posting_date)='".$row."' AND YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(MONTH(h.posting_date)='".$row."' AND YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference".$row;
      }
      $query_temp = substr($query_temp,0,-1);
      $query_temp .=",SUM(IF(YEAR(h.posting_date)='".$year."',quantity,0)) as total_".$year."
      ,(SUM(IF(YEAR(h.posting_date)='".$year."',quantity,0))-SUM(IF(YEAR(h.posting_date)='".$last_year."',quantity,0))) as diference_year
      FROM sales_invoice_header h inner join sales_invoice_line d on (h.no=d.document_no)
      INNER JOIN mst_brand m on (d.item_category_code = m.item_cat)
      WHERE h.sell_to_customer_no='".$cust_code."' AND h.posting_date BETWEEN  '".$last_year."-01-01' and '".$year."-12-31'
      group by d.no;";
     
      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //-- 2024-03-20 carlos sales per branch
    function get_number_branch($cust_code){
      $db = $this->load->database('default2', true);
      $query_temp = "SELECT h.ship_to_city as city_name
      FROM sales_invoice_header h
      WHERE h.sell_to_customer_no='".$cust_code."' group by ship_to_city;";
   
       $query = $db->query($query_temp);
       return $query->result_array();
    }
    function get_data_branch($cust_code,$branchs, $month, $year, $last_year, $last_2year){
      $db = $this->load->database('default2', true);
      $query_temp = "SELECT m.description as category,d.description,d.no as item_no";
      foreach($branchs as $row){ 
        $name_b = $row["city_name"];
        $result = str_replace(' ','_', $name_b);
        $result_2 = str_replace(".","", $result);
     
        $query_temp.= ",SUM(IF(h.ship_to_city='".$name_b."' AND MONTH(h.posting_date)='".$month."' AND YEAR(h.posting_date)='".$last_2year."',quantity,0)) as ".$result_2."_".$last_2year." 
                       ,SUM(IF(h.ship_to_city='".$name_b."' AND MONTH(h.posting_date)='".$month."' AND YEAR(h.posting_date)='".$last_year."',quantity,0)) as ".$result_2."_".$last_year."
                       ,SUM(IF(h.ship_to_city='".$name_b."' AND MONTH(h.posting_date)='".$month."' AND YEAR(h.posting_date)='".$year."',quantity,0)) as ".$result_2."_".$year." 
                       ";
      }
      $query_temp.="FROM sales_invoice_header h INNER JOIN sales_invoice_line d on(h.no=d.document_no)
      INNER JOIN mst_brand m on (d.item_category_code = m.item_cat)
      where h.sell_to_customer_no='".$cust_code."' group by item_no;";
      $query = $db->query($query_temp);
      return $query->result_array();
    }
}
