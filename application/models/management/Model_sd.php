<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_sd extends CI_Model{

    function get_inventory_monitoring($date,$type){
        $db = $this->load->database('default2', true);
        $query_temp = "select item.item_category_codee, item.manufacture_codee,

        if(invt.no_sku is not null,invt.no_sku,0) as invt_no_sku,
        if(invt.qty is not null,invt.qty,0) as invt_qty,
        if(invt.amount is not null, invt.amount,0) as invt_amount,

        if(sls_ytd.no_sku is not null, sls_ytd.no_sku,0) as sls_ytd_no_sku,
        if(sls_ytd.qty is not null,sls_ytd.qty,0) as sls_ytd_qty,
        if(sls_ytd.amount is not null,sls_ytd.amount,0) as sls_ytd_amount,

        if(sls_lastyear.no_sku is not null, sls_lastyear.no_sku,0) as sls_lastyear_no_sku,
        if(sls_lastyear.qty is not null,sls_lastyear.qty,0) as sls_lastyear_qty,
        if(sls_lastyear.amount is not null,sls_lastyear.amount,0) as sls_lastyear_amount,

        if(item_not_moving6month.no_sku is not null, item_not_moving6month.no_sku,0) as item_not_moving6month_no_sku,
        if(item_not_moving6month.qty is not null,item_not_moving6month.qty,0) as item_not_moving6month_qty,
        if(item_not_moving6month.amount is not null,item_not_moving6month.amount,0) as item_not_moving6month_amount,

        if(item_not_moving12month.no_sku is not null, item_not_moving12month.no_sku,0) as item_not_moving12month_no_sku,
        if(item_not_moving12month.qty is not null,item_not_moving12month.qty,0) as item_not_moving12month_qty,
        if(item_not_moving12month.amount is not null,item_not_moving12month.amount,0) as item_not_moving12month_amount,

        if(item_not_moving_lastyear.no_sku is not null, item_not_moving_lastyear.no_sku,0) as item_not_moving_lastyear_no_sku,
        if(item_not_moving_lastyear.qty is not null,item_not_moving_lastyear.qty,0) as item_not_moving_lastyear_qty,
        if(item_not_moving_lastyear.amount is not null,item_not_moving_lastyear.amount,0) as item_not_moving_lastyear_amount

        from(
        SELECT item_category_codee, manufacture_codee FROM mst_item m where name like '".$type."%' group by item_category_codee, manufacture_codee) as item

        left join(
          select item_category_codee, manufacture_codee, count(distinct(item_no)) as no_sku,sum(qty) as qty, sum(amount) as amount
          from (
          SELECT item_no,item_category_codee, manufacture_codee,qty,unit_costt, round(qty*unit_costt,2) as amount
          FROM (select * from item_invt_nav where location in('WH2','WH3','WH4')) nav
          inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=nav.item_no)) as item_invt group by item_category_codee, manufacture_codee) as invt
        on(invt.item_category_codee=item.item_category_codee and invt.manufacture_codee=item.manufacture_codee)

        left join(
        select item_category_codee, manufacture_codee,count(distinct(item_no)) as no_sku,sum(qty) as qty, round(sum(line_amount),2) as amount
        from (
        SELECT h.no as no,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,line_no
                      FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                      where h.posting_date between '".$date["ytd_from"]."' and '".$date["ytd_to"]."'
        union
                    SELECT cm_h.no as no,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
                    FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                    where cm_h.posting_date between '".$date["ytd_from"]."' and '".$date["ytd_to"]."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si
        inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=tbl_si.item_no) group by item_category_codee, manufacture_codee) as sls_ytd
        on(sls_ytd.item_category_codee=item.item_category_codee and sls_ytd.manufacture_codee=item.manufacture_codee)

        left join(
        select item_category_codee, manufacture_codee,count(distinct(item_no)) as no_sku,sum(qty) as qty, round(sum(line_amount),2) as amount
        from (
        SELECT h.no as no,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,line_no
                      FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                      where h.posting_date between '".$date["last_year_from"]."' and '".$date["last_year_to"]."'
        union
                    SELECT cm_h.no as no,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
                    FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
                    where cm_h.posting_date between '".$date["last_year_from"]."' and '".$date["last_year_to"]."' and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si
        inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=tbl_si.item_no) group by item_category_codee, manufacture_codee) as sls_lastyear
        on(sls_lastyear.item_category_codee=item.item_category_codee and sls_lastyear.manufacture_codee=item.manufacture_codee)

        left join(
          select item_category_codee, manufacture_codee, count(distinct(code)) as no_sku, sum(qty) as qty, sum(amount) as amount
          from (
          select code, item_category_codee, manufacture_codee,unit_costt,qty, (qty*unit_costt) as amount
          from (
          select code,item_category_codee, manufacture_codee, unit_costt
          FROM mst_item m where name like '".$type."%' and code not in (select distinct(item_no) as item_no
          from (
          SELECT distinct(d.no) as item_no
                        FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                        where h.posting_date between '".$date["last_6months_from"]."' and '".$date["last_6months_to"]."') as item_sold
          inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=item_sold.item_no))) as tbl_item_not_sold
          inner join (select * from item_invt_nav where location in('WH2','WH3','WH4')) invt_nav on(invt_nav.item_no=tbl_item_not_sold.code)) as item_not_moving group by item_category_codee, manufacture_codee) as item_not_moving6month
        on(item_not_moving6month.item_category_codee=item.item_category_codee and item_not_moving6month.manufacture_codee=item.manufacture_codee)

        left join(
          select item_category_codee, manufacture_codee, count(distinct(code)) as no_sku, sum(qty) as qty, sum(amount) as amount
          from (
          select code, item_category_codee, manufacture_codee,unit_costt,qty, (qty*unit_costt) as amount
          from (
          select code,item_category_codee, manufacture_codee, unit_costt
          FROM mst_item m where name like '".$type."%' and code not in (select distinct(item_no) as item_no
          from (
          SELECT distinct(d.no) as item_no
                        FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                        where h.posting_date between '".$date["last_12months_from"]."' and '".$date["last_12months_to"]."') as item_sold
          inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=item_sold.item_no))) as tbl_item_not_sold
          inner join (select * from item_invt_nav where location in('WH2','WH3','WH4')) invt_nav on(invt_nav.item_no=tbl_item_not_sold.code)) as item_not_moving group by item_category_codee, manufacture_codee) as item_not_moving12month
        on(item_not_moving12month.item_category_codee=item.item_category_codee and item_not_moving12month.manufacture_codee=item.manufacture_codee)

        left join(
          select item_category_codee, manufacture_codee, count(distinct(code)) as no_sku, sum(qty) as qty, sum(amount) as amount
          from (
          select code, item_category_codee, manufacture_codee,unit_costt,qty, (qty*unit_costt) as amount
          from (
          select code,item_category_codee, manufacture_codee, unit_costt
          FROM mst_item m where name like '".$type."%' and code not in (select distinct(item_no) as item_no
          from (
          SELECT distinct(d.no) as item_no
                        FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                        where h.posting_date between '".$date["last_year_from"]."' and '".$date["last_year_to"]."') as item_sold
          inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=item_sold.item_no))) as tbl_item_not_sold
          inner join (select * from item_invt_nav where location in('WH2','WH3','WH4')) invt_nav on(invt_nav.item_no=tbl_item_not_sold.code)) as item_not_moving group by item_category_codee, manufacture_codee) as item_not_moving_lastyear
        on(item_not_moving_lastyear.item_category_codee=item.item_category_codee and item_not_moving_lastyear.manufacture_codee=item.manufacture_codee)
        ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_inventory_monitoring_invoice($date_from,$date_to,$type,$item_cat,$manf_code){
        $db = $this->load->database('default2', true);
        $query_temp = "select item_no,item_category_codee, manufacture_codee,qty,amount,date_format(in_time,'%Y-%m-%d') as in_time, date_format(out_time,'%Y-%m-%d') as out_time
        from (
          select item_no,item_category_codee, manufacture_codee, sum(qty) as qty, round(sum(line_amount),2) as amount
            from (
            SELECT h.no as no,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where h.posting_date between '".$date_from."' and '".$date_to."'
            union
            SELECT cm_h.no as no,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where cm_h.posting_date between '".$date_from."' and '".$date_to."' and cm_d.no!='DISC') as tbl_si
            inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=tbl_si.item_no)
            where item_category_codee='".$item_cat."' and manufacture_codee='".$manf_code."' group by item_no,item_category_codee, manufacture_codee) as tbl_a
            left join (SELECT item_code, max(created_datetime) as in_time FROM tpimx_wms.tsc_item_entry t where type='1' group by item_code) as tbl_in on(tbl_in.item_code=tbl_a.item_no)
            left join (SELECT item_code, max(created_datetime) as out_time FROM tpimx_wms.tsc_item_entry t where type='2' group by item_code) as tbl_out on(tbl_out.item_code=tbl_a.item_no);";

            $query = $db->query($query_temp);
            return $query->result_array();
    }
    //---

    function get_inventory_monitoring_invt($type,$item_cat,$manf_code){
        $db = $this->load->database('default2', true);
        $query_temp = "select item_no,item_category_codee, manufacture_codee,qty,amount,date_format(in_time,'%Y-%m-%d') as in_time, date_format(out_time,'%Y-%m-%d') as out_time
        from (select item_no,item_category_codee, manufacture_codee,sum(qty) as qty,  round(sum(qty)*unit_costt,2) as amount
        FROM (select * from item_invt_nav
        where location in('WH2','WH3')) nav inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=nav.item_no)
        where item_category_codee='".$item_cat."' and manufacture_codee='".$manf_code."'
        group by item_no,item_category_codee, manufacture_codee) as tbl_a
        left join (SELECT item_code, max(created_datetime) as in_time FROM tpimx_wms.tsc_item_entry t where type='1' group by item_code) as tbl_in on(tbl_in.item_code=tbl_a.item_no)
        left join (SELECT item_code, max(created_datetime) as out_time FROM tpimx_wms.tsc_item_entry t where type='2' group by item_code) as tbl_out on(tbl_out.item_code=tbl_a.item_no);";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_inventory_monitoring_item_not_moving($date_from,$date_to,$type,$item_cat,$manf_code){
        $db = $this->load->database('default2', true);
        $query_temp = "select item_no,item_category_codee, manufacture_codee,qty,amount,date_format(in_time,'%Y-%m-%d') as in_time, date_format(out_time,'%Y-%m-%d') as out_time
        from (
          select code as item_no,item_category_codee, manufacture_codee, sum(qty) as qty, round(sum(qty)*unit_costt,2) as amount
        from (
        select code,item_category_codee, manufacture_codee, unit_costt
        FROM mst_item m where name like '".$type."%' and code not in (select distinct(item_no) as item_no
        from (
        SELECT distinct(d.no) as item_no
                      FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                      where h.posting_date between '".$date_from."' and '".$date_to."') as item_sold
        inner join (select * FROM mst_item m where name like '".$type."%') item on(item.code=item_sold.item_no))) as tbl_item_not_sold
        inner join (select * from item_invt_nav where location in('WH2','WH3')) invt_nav on(invt_nav.item_no=tbl_item_not_sold.code)
        where item_category_codee='".$item_cat."' and manufacture_codee='".$manf_code."'
        group by code,item_category_codee, manufacture_codee) as tbl_a
        left join (SELECT item_code, max(created_datetime) as in_time FROM tpimx_wms.tsc_item_entry t where type='1' group by item_code) as tbl_in on(tbl_in.item_code=tbl_a.item_no)
        left join (SELECT item_code, max(created_datetime) as out_time FROM tpimx_wms.tsc_item_entry t where type='2' group by item_code) as tbl_out on(tbl_out.item_code=tbl_a.item_no);";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-04-17
    function get_8020_review_qty_report($year, $last_year, $last_2year, $months, $brand, $cat){
        $db = $this->load->database('default2', true);

        if($brand == 1) $item_no=" not like 'TYP-%'";
        else if($brand == 2) $item_no=" like 'TYP-%'";

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.qty is null,0,si_last_2year.qty) as si_qty_last_2year,
          if(si_last_year.qty is null,0,si_last_year.qty) as si_qty_last_year,
          if(tbl_cust_buy_this_year.total_cust_buy is null,0,tbl_cust_buy_this_year.total_cust_buy) as cust_buy_this_year,
          if(tbl_cust_buy_last_year.total_cust_buy is null,0,tbl_cust_buy_last_year.total_cust_buy) as cust_buy_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%'
          and d.no ".$item_no."

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."')  and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as tbl_item) as tbl_item

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
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no."
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no."
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(qty) as qty
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '%".$cat."%' and d.no ".$item_no."
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join(
              SELECT d.no as item_no,count(distinct(h.bill_to_customer_no)) as total_cust_buy
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no." group by d.no ) as tbl_cust_buy_this_year
              on(tbl_cust_buy_this_year.item_no=tbl_item.item_no)

            left join(
              SELECT d.no as item_no,count(distinct(h.bill_to_customer_no)) as total_cust_buy
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no." group by d.no ) as tbl_cust_buy_last_year
              on(tbl_cust_buy_last_year.item_no=tbl_item.item_no)";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    function get_8020_review_amount_report($year, $last_year, $last_2year, $months, $brand, $cat){
        $db = $this->load->database('default2', true);

        if($brand == 1) $item_no=" not like 'TYP-%'";
        else if($brand == 2) $item_no=" like 'TYP-%'";

        $query_temp="select tbl_item.item_no,item_category_code,
          if(si_last_2year.line_amount is null,0,si_last_2year.line_amount) as si_qty_last_2year,
          if(si_last_year.line_amount is null,0,si_last_year.line_amount) as si_qty_last_year,
          if(tbl_cust_buy_this_year.total_cust_buy is null,0,tbl_cust_buy_this_year.total_cust_buy) as cust_buy_this_year,
          if(tbl_cust_buy_last_year.total_cust_buy is null,0,tbl_cust_buy_last_year.total_cust_buy) as cust_buy_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.=" if(line_amount_this_year is null,0,round(line_amount_this_year,2)) as line_amount_this_year,
          if(line_cost_amount_this_year is null,0,round(line_cost_amount_this_year,2)) as line_cost_amount_this_year,
          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent
          from(
          select distinct(tbl_item.item_no) as item_no,item_category_code from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,item_category_code,d.quantity as qty,line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no."

          union

          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no,item_category_code, (cm_d.quantity*-1) as qty,line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."','".$last_year."', '".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as tbl_item) as tbl_item

          left join (
          select item_no,";

          foreach($months as $row){ $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)) as now_".$year."_".$row.","; }

          $query_temp.=" sum(line_amount) as line_amount_this_year,
            sum(line_cost_amount) as line_cost_amount_this_year
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no."
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as tbl_si_this_year group by item_no) as tbl_si_this_year
            on(tbl_si_this_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no,d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no."
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as si_last_year group by item_no) as si_last_year
            on(si_last_year.item_no=tbl_item.item_no)

            left join (
            select item_no,sum(line_amount) as line_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,line_no, d.line_amount as line_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no."
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,line_no, (cm_d.line_amount*-1) as line_amount
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and cm_d.no ".$item_no." and cm_d.no!='VD-B' and cm_d.no!='VD-F') as si_last_2year group by item_no) as si_last_2year
            on(si_last_2year.item_no=tbl_item.item_no)

            left join(
              SELECT d.no as item_no,count(distinct(h.bill_to_customer_no)) as total_cust_buy
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no." group by d.no ) as tbl_cust_buy_this_year
              on(tbl_cust_buy_this_year.item_no=tbl_item.item_no)

            left join(
              SELECT d.no as item_no,count(distinct(h.bill_to_customer_no)) as total_cust_buy
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '".$brand."%' and item_category_code like '".$cat."%' and d.no ".$item_no." group by d.no ) as tbl_cust_buy_last_year
              on(tbl_cust_buy_last_year.item_no=tbl_item.item_no)";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    //---

    // 2023-05-05
    function get_detail_so_monthly($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "select *,date_format(order_date,'%Y-%m-%d') as order_date FROM so_detail_monthly s where year(order_date)='".$year."' and month(order_date)='".$month."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-05
    function get_summary_so_monthly($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "select date_format(order_date,'%Y-%m-%d') as order_date, doc_no,
          sum(order_qty) as order_qty, round(sum(order_amount),2) as order_amount,
          sum(proceed_qty) as proceed_qty, round(sum(proceed_amount),2) as proceed_amount,
          round(((sum(proceed_qty))/sum(order_qty))*100,2) as fullfill_percent_qty,
          round(((sum(proceed_amount))/sum(order_amount))*100,2) as fullfill_percent_amount,
          round(((sum(outstanding_qty))/sum(order_qty))*100,2) as backorder_percent_qty,
          round(((sum(outstanding_amount))/sum(order_amount))*100,2) as backorder_percent_amount
          FROM so_detail_monthly s where year(order_date)='".$year."' and month(order_date)='".$month."'
          group by doc_no, order_date order by order_date, doc_no;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-05
    function get_cust_bo_so_monthly($year, $month){
        $db = $this->load->database('default2', true);
        $query_temp = "select cust_no, cust_name, count(distinct(item_no)) as total_sku,
          sum(order_qty) as order_qty, round(sum(order_amount),2) as order_amount,
          sum(proceed_qty) as proceed_qty, round(sum(proceed_amount),2) as proceed_amount,
          sum(outstanding_qty) as outstanding_qty, round(sum(outstanding_amount),2) as outstanding_amount,
          round(((sum(proceed_qty))/sum(order_qty))*100,2) as proceed_percent_qty,
          round(((sum(proceed_amount))/sum(order_amount))*100,2) as proceed_percent_amount,
          round(((sum(outstanding_qty))/sum(order_qty))*100,2) as backorder_percent_qty,
          round(((sum(outstanding_amount))/sum(order_amount))*100,2) as backorder_percent_amount
          FROM so_detail_monthly s where year(order_date)='".$year."' and month(order_date)='".$month."'
          group by cust_no order by cust_no;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-05
    function get_cust_top30_so_monthly($year, $month){
        $db = $this->load->database('default2', true);

        $top = 30;

        $query_temp = "
          select * from(
          SELECT cust_no, cust_name, count(distinct(item_no)) as total_sku,
          sum(order_qty) as order_qty, round(sum(order_amount),2) as order_amount,
          sum(proceed_qty) as proceed_qty, round(sum(proceed_amount),2) as proceed_amount,
          sum(outstanding_qty) as outstanding_qty, round(sum(outstanding_amount),2) as outstanding_amount,
          round(((sum(proceed_qty))/sum(order_qty))*100,2) as proceed_percent_qty,
          round(((sum(proceed_amount))/sum(order_amount))*100,2) as proceed_percent_amount,
          round(((sum(outstanding_qty))/sum(order_qty))*100,2) as backorder_percent_qty,
          round(((sum(outstanding_amount))/sum(order_amount))*100,2) as backorder_percent_amount
          FROM so_detail_monthly s where year(order_date)='".$year."' and month(order_date)='".$month."'
          group by cust_no) as tbl_a order by order_amount desc limit ".$top.";";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-05
    function get_sku_so_monthly($year, $month){
        $db = $this->load->database('default2', true);

        $query_temp = " select tbl_a.item_no, name, order_qty, order_amount, proceed_qty, proceed_amount, fullfill_percent_qty, fullfill_percent_amount, backorder_percent_qty, backorder_percent_amount,
          if(qty_stock is null,0, qty_stock) as qty_stock,
          if(backorder_percent_qty > 0,if(qty_stock < (order_qty-proceed_qty) or qty_stock is null,1,0),2) as no_stock
          from (
          select item_no, name, order_qty, order_amount, proceed_qty, proceed_amount, fullfill_percent_qty, fullfill_percent_amount, backorder_percent_qty, backorder_percent_amount
                    from (
                    SELECT item_no,
                    sum(order_qty) as order_qty, round(sum(order_amount),2) as order_amount,
                    sum(proceed_qty) as proceed_qty, round(sum(proceed_amount),2) as proceed_amount,
                    round(((sum(proceed_qty))/sum(order_qty))*100,2) as fullfill_percent_qty,
                    round(((sum(proceed_amount))/sum(order_amount))*100,2) as fullfill_percent_amount,
                    round(((sum(outstanding_qty))/sum(order_qty))*100,2) as backorder_percent_qty,
                    round(((sum(outstanding_amount))/sum(order_amount))*100,2) as backorder_percent_amount
                    FROM so_detail_monthly s where year(order_date)='".$year."' and month(order_date)='".$month."' and item_no!=''
                    group by item_no) as tbl_a left join mst_item item on(item.code=tbl_a.item_no)) as tbl_a

          left join(
          SELECT item_no, sum(qty) as qty_stock
          FROM item_invt_nav_month_end i where month(doc_date)='".$month."' and year(doc_date)='".$year."' and location in('WH2','WH3','WH4') group by item_no) as tbl_invt
          on tbl_a.item_no=tbl_invt.item_no;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-16
    function get_invoices_by_period_customer($from, $to, $cust_no, $doc_type){
        $db = $this->load->database('default2', true);

        if($doc_type == "invc"){
            $query_temp = "select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no,'INVC' as doc_type,  d.line_no,d.location_code,d.no as item_code,
              d.sell_to_customer_no,d.description,d.quantity, 'MXN' as currency_code, round(d.unit_price,2) as unit_price, round(d.amount,2) as amount,
              round(unit_cost_lcy,2) as unit_cost, round(unit_cost_lcy*d.quantity,2) as total_cost,
              round(d.amount-(unit_cost_lcy*d.quantity),2) as gross_margin , round((d.amount-(unit_cost_lcy*d.quantity))/amount*100,2) as percent_margin,

              d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
                            d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname, h.ship_to_city, h.ship_to_county
                            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                            left join mst_salesman slsman on(slsman.code=h.sales_person_code)
                            where h.posting_date between '".$from."' and '".$to."'
                            and h.bill_to_customer_no like '%".$cust_no."%';";
        }
        /*else if($doc_type == "cm"){
          $query_temp = "select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no, 'CN' as doc_type, d.line_no,d.location_code,d.no as item_code,
            d.sell_to_customer_no,d.description,d.quantity, 'MXN' as currency_code, round(d.unit_price,2) as unit_price, round(d.amount,2) as amount, d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
            d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname
            FROM sales_cr_memo_header h inner join sales_cr_memo_line d on(h.no=d.document_no)
            left join mst_salesman slsman on(slsman.code=h.sales_person_code)
            where h.posting_date between '".$from."' and '".$to."'
            and h.bill_to_customer_no like '%".$cust_no."%';";
        }
        else if($doc_type == "all"){
          $query_temp = "select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no,'INVC' as doc_type,  d.line_no,d.location_code,d.no as item_code,
              d.sell_to_customer_no,d.description,d.quantity, 'MXN' as currency_code, round(d.unit_price,2) as unit_price, round(d.amount,2) as amount, d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
              d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname
              FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
              left join mst_salesman slsman on(slsman.code=h.sales_person_code)
              where h.posting_date between '".$from."' and '".$to."'
              and h.bill_to_customer_no like '%".$cust_no."%'

              union

              select h.posting_date as posting_date, h.bill_to_customer_no, bill_to_name, sales_person_code, d.document_no as invoice_no, 'CN' as doc_type,  d.line_no,d.location_code,d.no as item_code,
              d.sell_to_customer_no,d.description,d.quantity*-1, 'MXN' as currency_code, round(d.unit_price*-1,2) as unit_price, round(d.amount*-1,2) as amount, d.item_category_code,year(h.posting_date) as yearr, month(h.posting_date) as monthh,
              d.vat_bus_posting_group,h.ship_to_post_code, external_document_no, slsman.name as slsname
              FROM sales_cr_memo_header h inner join sales_cr_memo_line d on(h.no=d.document_no)
              left join mst_salesman slsman on(slsman.code=h.sales_person_code)
              where h.posting_date between '".$from."' and '".$to."'
              and h.bill_to_customer_no like '%".$cust_no."%';";
        }*/

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    // 2023-05-25
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

          if(tbl_si_last_year.line_amount is null,0,round(tbl_si_last_year.line_amount,2)) as line_amount_last_year,
          if(tbl_si_last_year.line_cost_amount is null,0,round(tbl_si_last_year.line_cost_amount,2)) as line_cost_amount_last_year,

          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent,

          if(tbl_si_last_year.line_amount is null or tbl_si_last_year.line_cost_amount is null,0, round((tbl_si_last_year.line_amount-tbl_si_last_year.line_cost_amount)/tbl_si_last_year.line_amount*100,2))  as gp_percent_last_year,

          subtotal_delv

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
            select customer,sum(qty) as qty,sum(line_amount) as line_amount, sum(line_cost_amount) as line_cost_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, line_no, d.line_amount as line_amount, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, line_no, (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
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
              SELECT invc_cust_no, round(sum(h.subtotal),2) as subtotal_delv
              FROM tpimx_oprc.tsc_delivery_h h
              inner join tpimx_oprc.tsc_delivery_d d on(h.doc_no=d.doc_no)
              where  year(h.created_at)='".$year."' group by invc_cust_no
            ) as tbl_delv on(tbl_delv.invc_cust_no=tbl_cust.cust_no)

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

    // 2023-05-25
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

          if(tbl_si_last_year.line_amount is null,0,round(tbl_si_last_year.line_amount,2)) as line_amount_last_year,
          if(tbl_si_last_year.line_cost_amount is null,0,round(tbl_si_last_year.line_cost_amount,2)) as line_cost_amount_last_year,

          if(line_amount_this_year is null or line_cost_amount_this_year is null,0, round((line_amount_this_year-line_cost_amount_this_year)/line_amount_this_year*100,2))  as gp_percent,

          if(tbl_si_last_year.line_amount is null or tbl_si_last_year.line_cost_amount is null,0, round((tbl_si_last_year.line_amount-tbl_si_last_year.line_cost_amount)/tbl_si_last_year.line_amount*100,2))  as gp_percent_last_year,

          subtotal_delv

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
            select customer,sum(qty) as qty, sum(line_amount) as line_amount, sum(line_cost_amount) as line_cost_amount
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty, d.line_amount as line_amount, line_no, (quantity*d.unit_cost_lcy) as line_cost_amount
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty, (cm_d.line_amount*-1) as line_amount, line_no, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount
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
              SELECT invc_cust_no, round(sum(h.subtotal),2) as subtotal_delv
              FROM tpimx_oprc.tsc_delivery_h h
              inner join tpimx_oprc.tsc_delivery_d d on(h.doc_no=d.doc_no)
              where  year(h.created_at)='".$year."' and h.canceled=0 group by invc_cust_no) as tbl_delv on(tbl_delv.invc_cust_no=tbl_cust.cust_no)

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

    function get_cust_review_detail($year, $last_year, $last_2year, $months){
        $db = $this->load->database('default2', true);

        $query_temp = "select customer,item_category_code,
          round(sum(if(year(posting_date)='".$last_2year."',line_amount,0)),2) as amount_last_2year,
          round(sum(if(year(posting_date)='".$last_year."',line_amount,0)),2) as amount_last_year,
          round(sum(if(year(posting_date)='".$last_year."',line_cost_amount,0)),2) as cost_amount_last_year,

          round((sum(if(year(posting_date)='".$last_year."',line_amount,0))-sum(if(year(posting_date)='".$last_year."',line_cost_amount,0)))/sum(if(year(posting_date)='".$last_year."',line_amount,0))*100,2) as gp_last_year, ";

          foreach($months as $row){
              $query_temp.="round(sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',line_amount,0)),2) as now_".$year."_".$row."_amount, ";
          }

          $query_temp.="round(sum(if(year(posting_date)='".$year."',line_amount,0)),2) as amount_this_year,
            round(sum(if(year(posting_date)='".$year."',line_cost_amount,0)),2) as cost_amount_this_year,

            round(sum(if(year(posting_date)='".$last_2year."',qty,0)),2) as qty_last_2year,
            round(sum(if(year(posting_date)='".$last_year."',qty,0)),2) as qty_last_year,";

          foreach($months as $row){
              $query_temp.="round(sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',qty,0)),2) as now_".$year."_".$row."_qty, ";
          }

          $query_temp.="round((sum(if(year(posting_date)='".$year."',line_amount,0))-sum(if(year(posting_date)='".$year."',line_cost_amount,0)))/sum(if(year(posting_date)='".$year."',line_amount,0))*100,2) as gp_this_year

            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no, item_category_code
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."','".$last_year."','".$last_2year."')
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no, item_category_code
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."','".$last_year."','".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC')) as tbl_si_this_year group by customer, item_category_code";

          $query = $db->query($query_temp);
          return $query->result_array();
    }
    ///---

    // 2023-09-18
    function get_cust_review_detail_filter_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, cust_name, slsname, county, tbl_cust_cat.item_category_codee, tbl_cust_cat.item_name,
          if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
          if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(total_".$year." is null,0,total_".$year.") as total_".$year.",
          if(gp_percent is null,0, gp_percent) as gp_percent
          from (
          select cust_no, name as cust_name, slsname,county,item_category_codee, item_name
          from (
          SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
          inner join mst_salesman sls on(m.sales_person_code=sls.code)
          where (cust_no like '1%') and sales_person_code like '%%') as tbl_cust

          inner join(
          select customer, item.item_category_codee, item.name as item_name
          from(
          SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
                      FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                      where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (h.bill_to_customer_no like '1%')
          union
          SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '1%')) as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no) group by customer, item.item_category_codee, item.name
          ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no, item_category_codee, item_name) as tbl_cust_cat

          left join(
          select customer,item_category_codee,item_name, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(line_amount,2),0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(if(year(posting_date)='".$year."',round(line_amount,2),0)) as total_".$year.",
          if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
          from(
          select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee,item_name
          ) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no
          and tbl_si_this_year.item_category_codee=tbl_cust_cat.item_category_codee
          and tbl_si_this_year.item_name=tbl_cust_cat.item_name)

          left join(
          select customer,item_category_codee,item_name,round(sum(line_amount),2) as si_last_year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee,item_name
          ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_year.item_category_codee=tbl_cust_cat.item_category_codee
          and tbl_si_last_year.item_name=tbl_cust_cat.item_name)

          left join(
          select customer,item_category_codee,item_name,round(sum(line_amount),2) as si_last_2year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee,item_name
          ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_2year.item_category_codee=tbl_cust_cat.item_category_codee
          and tbl_si_last_2year.item_name=tbl_cust_cat.item_name)

          order by cust_no,tbl_cust_cat.item_category_codee, tbl_cust_cat.item_name ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-09-18
    function get_cust_review_detail_filter_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, cust_name, slsname, county, tbl_cust_cat.item_category_codee, tbl_cust_cat.item_name,
          if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
          if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(total_".$year." is null,0,total_".$year.") as total_".$year.",
          if(gp_percent is null,0, gp_percent) as gp_percent
          from (
          select cust_no, name as cust_name, slsname,county,item_category_codee, item_name
          from (
          SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
          inner join mst_salesman sls on(m.sales_person_code=sls.code)
          where (cust_no like '1%') and sales_person_code like '%%') as tbl_cust

          inner join(
          select customer, item.item_category_codee, item.name as item_name
          from(
          SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
                      FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                      where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (h.bill_to_customer_no like '1%')
          union
          SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '1%')) as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no) group by customer, item.item_category_codee, item.name
          ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no, item_category_codee, item_name) as tbl_cust_cat

          left join(
          select customer,item_category_codee,item_name, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(qty,0),0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(if(year(posting_date)='".$year."',round(qty,0),0)) as total_".$year.",
          if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
          from(
          select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee,item_name
          ) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no
          and tbl_si_this_year.item_category_codee=tbl_cust_cat.item_category_codee
          and tbl_si_this_year.item_name=tbl_cust_cat.item_name)

          left join(
          select customer,item_category_codee,item_name,round(sum(qty),0) as si_last_year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee,item_name
          ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_year.item_category_codee=tbl_cust_cat.item_category_codee
          and tbl_si_last_year.item_name=tbl_cust_cat.item_name)

          left join(
          select customer,item_category_codee,item_name,round(sum(qty),0) as si_last_2year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee,item_name
          ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_2year.item_category_codee=tbl_cust_cat.item_category_codee
          and tbl_si_last_2year.item_name=tbl_cust_cat.item_name)

          order by cust_no,tbl_cust_cat.item_category_codee, tbl_cust_cat.item_name ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-09-18
    function get_cust_review_detail_banda_amount_report($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, cust_name, slsname, county, tbl_cust_cat.item_category_codee,
          if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
          if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(total_".$year." is null,0,total_".$year.") as total_".$year.",
          if(gp_percent is null,0, gp_percent) as gp_percent
          from (
          select cust_no, name as cust_name, slsname,county,item_category_codee
          from (
          SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
          inner join mst_salesman sls on(m.sales_person_code=sls.code)
          where (cust_no like '2%') and sales_person_code like '%%') as tbl_cust

          inner join(
          select customer, item.item_category_codee
          from(
          SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (h.bill_to_customer_no like '2%')
          union
          SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '2%')) as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no) group by customer, item.item_category_codee
          ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no, item_category_codee) as tbl_cust_cat

          left join(
          select customer,item_category_codee, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(line_amount,2),0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(if(year(posting_date)='".$year."',round(line_amount,2),0)) as total_".$year.",
            if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
            from(
            select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '2%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
            inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
            ) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no
            and tbl_si_this_year.item_category_codee=tbl_cust_cat.item_category_codee)

            left join(
            select customer,item_category_codee,round(sum(line_amount),2) as si_last_year
            from(
            select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '2%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
            inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
            ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no
            and tbl_si_last_year.item_category_codee=tbl_cust_cat.item_category_codee)

            left join(
            select customer,item_category_codee,round(sum(line_amount),2) as si_last_2year
            from(
            select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '2%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
            inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
            ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no
            and tbl_si_last_2year.item_category_codee=tbl_cust_cat.item_category_codee)

            order by cust_no,tbl_cust_cat.item_category_codee ";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-09-18
    function get_cust_review_detail_banda_qty_report($year, $last_year, $last_2year, $months, $today, $last_6months){
      $db = $this->load->database('default2', true);

      $query_temp="select cust_no, cust_name, slsname, county, tbl_cust_cat.item_category_codee,
        if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
        if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.="
        if(total_".$year." is null,0,total_".$year.") as total_".$year.",
        if(gp_percent is null,0, gp_percent) as gp_percent
        from (
        select cust_no, name as cust_name, slsname,county,item_category_codee
        from (
        SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
        inner join mst_salesman sls on(m.sales_person_code=sls.code)
        where (cust_no like '2%') and sales_person_code like '%%') as tbl_cust

        inner join(
        select customer, item.item_category_codee
        from(
        SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
        FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
        where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (h.bill_to_customer_no like '2%')
        union
        SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
        FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
        where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '2%')) as tbl_si
        inner join mst_item item on(item.code=tbl_si.item_no) group by customer, item.item_category_codee
        ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no, item_category_codee) as tbl_cust_cat

        left join(
        select customer,item_category_codee, ";

        foreach($months as $row){
            $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(qty,2),0)) as now_".$year."_".$row.",";
        }

        $query_temp.="sum(if(year(posting_date)='".$year."',round(qty,0),0)) as total_".$year.",
          if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
          from(
          select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '2%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no
          and tbl_si_this_year.item_category_codee=tbl_cust_cat.item_category_codee)

          left join(
          select customer,item_category_codee,round(sum(qty),0) as si_last_year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '2%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_year.item_category_codee=tbl_cust_cat.item_category_codee)

          left join(
          select customer,item_category_codee,round(sum(qty),0) as si_last_2year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '2%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_2year.item_category_codee=tbl_cust_cat.item_category_codee)

          order by cust_no,tbl_cust_cat.item_category_codee ";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---
    //---

    // 2023-09-19
    function get_cust_review_detail_filter_amount_report2($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, cust_name, slsname, county, tbl_cust_cat.item_category_codee,
          if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
          if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(total_".$year." is null,0,total_".$year.") as total_".$year.",
          if(gp_percent is null,0, gp_percent) as gp_percent
          from (
          select cust_no, name as cust_name, slsname,county,item_category_codee
          from (
          SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
          inner join mst_salesman sls on(m.sales_person_code=sls.code)
          where (cust_no like '1%') and sales_person_code like '%%') as tbl_cust

          inner join(
          select customer, item.item_category_codee
          from(
          SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
                      FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                      where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (h.bill_to_customer_no like '1%')
          union
          SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '1%')) as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no) group by customer, item.item_category_codee
          ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no, item_category_codee) as tbl_cust_cat

          left join(
          select customer,item_category_codee,";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(line_amount,2),0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(if(year(posting_date)='".$year."',round(line_amount,2),0)) as total_".$year.",
          if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
          from(
          select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no
          and tbl_si_this_year.item_category_codee=tbl_cust_cat.item_category_codee)

          left join(
          select customer,item_category_codee,round(sum(line_amount),2) as si_last_year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_year.item_category_codee=tbl_cust_cat.item_category_codee)

          left join(
          select customer,item_category_codee,round(sum(line_amount),2) as si_last_2year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_2year.item_category_codee=tbl_cust_cat.item_category_codee)

          order by cust_no,tbl_cust_cat.item_category_codee;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-09-19
    function get_cust_review_detail_filter_qty_report2($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, cust_name, slsname, county, tbl_cust_cat.item_category_codee,
          if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
          if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(total_".$year." is null,0,total_".$year.") as total_".$year.",
          if(gp_percent is null,0, gp_percent) as gp_percent
          from (
          select cust_no, name as cust_name, slsname,county,item_category_codee
          from (
          SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
          inner join mst_salesman sls on(m.sales_person_code=sls.code)
          where (cust_no like '1%') and sales_person_code like '%%') as tbl_cust

          inner join(
          select customer, item.item_category_codee
          from(
          SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
                      FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
                      where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (h.bill_to_customer_no like '1%')
          union
          SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '1%')) as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no) group by customer, item.item_category_codee
          ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no, item_category_codee) as tbl_cust_cat

          left join(
          select customer,item_category_codee, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(qty,0),0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(if(year(posting_date)='".$year."',round(qty,0),0)) as total_".$year.",
          if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
          from(
          select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no
          and tbl_si_this_year.item_category_codee=tbl_cust_cat.item_category_codee)

          left join(
          select customer,item_category_codee,round(sum(qty),0) as si_last_year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_year.item_category_codee=tbl_cust_cat.item_category_codee)

          left join(
          select customer,item_category_codee,round(sum(qty),0) as si_last_2year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '1%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '1%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer, item_category_codee
          ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no
          and tbl_si_last_2year.item_category_codee=tbl_cust_cat.item_category_codee)

          order by cust_no,tbl_cust_cat.item_category_codee";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-09-19
    function get_cust_review_detail_banda_amount_report2($year, $last_year, $last_2year, $months, $today, $last_6months){
        $db = $this->load->database('default2', true);

        $query_temp="select cust_no, cust_name, slsname, county,
          if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
          if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

          foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

          $query_temp.="
          if(total_".$year." is null,0,total_".$year.") as total_".$year.",
          if(gp_percent is null,0, gp_percent) as gp_percent
          from (
          select cust_no, name as cust_name, slsname, county
          from (
          SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
          inner join mst_salesman sls on(m.sales_person_code=sls.code)
          where (cust_no like '2%') and sales_person_code like '%%') as tbl_cust

          inner join(
          select customer
          from(
          SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (h.bill_to_customer_no like '2%')
          union
          SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '2%')) as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no) group by customer
          ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no) as tbl_cust_cat

          left join(
          select customer, ";

          foreach($months as $row){
              $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(line_amount,2),0)) as now_".$year."_".$row.",";
          }

          $query_temp.="sum(if(year(posting_date)='".$year."',round(line_amount,2),0)) as total_".$year.",
            if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
            from(
            select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '2%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
            inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer
            ) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no)

            left join(
            select customer, round(sum(line_amount),2) as si_last_year
            from(
            select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '2%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
            inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer
            ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no)

            left join(
            select customer, round(sum(line_amount),2) as si_last_2year
            from(
            select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
            from(
            SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
            (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
            FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
            where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '2%'
            union
            SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
            (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
            FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
            where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
            inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer
            ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no)

            order by cust_no;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2023-09-19
    function get_cust_review_detail_banda_qty_report2($year, $last_year, $last_2year, $months, $today, $last_6months){
      $db = $this->load->database('default2', true);

      $query_temp="select cust_no, cust_name, slsname, county,
        if(tbl_si_last_2year.si_last_2year is null,0,tbl_si_last_2year.si_last_2year) as si_last_2year,
        if(tbl_si_last_year.si_last_year is null,0,tbl_si_last_year.si_last_year) as si_last_year, ";

        foreach($months as $row){ $query_temp.= "if(now_".$year."_".$row." is null,0,now_".$year."_".$row.") as now_".$year."_".$row.","; }

        $query_temp.="
        if(total_".$year." is null,0,total_".$year.") as total_".$year.",
        if(gp_percent is null,0, gp_percent) as gp_percent
        from (
        select cust_no, name as cust_name, slsname,county
        from (
        SELECT cust_no, m.name as name, sls.name as slsname, county FROM mst_cust m
        inner join mst_salesman sls on(m.sales_person_code=sls.code)
        where (cust_no like '2%') and sales_person_code like '%%') as tbl_cust

        inner join(
        select customer
        from(
        SELECT h.bill_to_customer_no as customer, d.no as item_no, line_no
        FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
        where year(h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (h.bill_to_customer_no like '2%')
        union
        SELECT cm_h.bill_to_customer_no as customer, cm_d.no as item_no, line_no
        FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
        where year(cm_h.posting_date) in ('".$last_2year."','".$last_year."','".$year."')  and (cm_d.type='2' and cm_d.no!='DISC') and (cm_h.bill_to_customer_no like '2%')) as tbl_si
        inner join mst_item item on(item.code=tbl_si.item_no) group by customer
        ) as tbl_cust_cat on(tbl_cust_cat.customer=tbl_cust.cust_no) order by cust_no) as tbl_cust_cat

        left join(
        select customer, ";

        foreach($months as $row){
            $query_temp.="sum(if(year(posting_date)='".$year."' and month(posting_date)='".$row."',round(qty,2),0)) as now_".$year."_".$row.",";
        }

        $query_temp.="sum(if(year(posting_date)='".$year."',round(qty,0),0)) as total_".$year.",
          if(line_cost_amount is null or line_amount is null,0, round((line_amount-line_cost_amount)/line_amount*100,2)) as gp_percent
          from(
          select no, customer, posting_date, item_no, qty, line_amount,line_cost_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$year."') and h.bill_to_customer_no like '2%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer) as tbl_si_this_year on(tbl_si_this_year.customer=tbl_cust_cat.cust_no)

          left join(
          select customer, round(sum(qty),0) as si_last_year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_year."') and h.bill_to_customer_no like '2%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer
          ) as tbl_si_last_year on(tbl_si_last_year.customer=tbl_cust_cat.cust_no)

          left join(
          select customer,round(sum(qty),0) as si_last_2year
          from(
          select no, customer, posting_date, item_no, qty, line_amount,item.name as item_name, item.manufacture_codee, item.item_category_codee, product_group_codee
          from(
          SELECT h.no as no,h.bill_to_customer_no as customer,h.posting_date as posting_date, d.no as item_no,d.quantity as qty,d.line_amount as line_amount,
          (quantity*d.unit_cost_lcy) as line_cost_amount, line_no
          FROM sales_invoice_header h inner join sales_invoice_line d on(h.no=d.document_no)
          where year(h.posting_date) in ('".$last_2year."') and h.bill_to_customer_no like '2%'
          union
          SELECT cm_h.no as no, cm_h.bill_to_customer_no as customer,cm_h.posting_date as posting_date, cm_d.no as item_no, (cm_d.quantity*-1) as qty,
          (cm_d.line_amount*-1) as line_amount, (quantity*cm_d.unit_cost_lcy*-1) as line_cost_amount, line_no
          FROM sales_cr_memo_header cm_h inner join sales_cr_memo_line cm_d on(cm_h.no=cm_d.document_no)
          where year(cm_h.posting_date) in ('".$last_2year."') and (cm_d.type='2' and cm_d.no!='DISC') and cm_h.bill_to_customer_no like '2%') as tbl_si
          inner join mst_item item on(item.code=tbl_si.item_no)) as tbl_si_this_year group by customer
          ) as tbl_si_last_2year on(tbl_si_last_2year.customer=tbl_cust_cat.cust_no)

          order by cust_no ";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    // 2023-10-12
    function get_po_created_received($year){
        $db = $this->load->database('sql_server_live', true);

        $query_temp = "select tbl_po.[No_] as po_doc, tbl_po.[Buy-from Vendor No_] as po_Vendor, convert(date,tbl_po.[Order Date]) as po_date,
          tbl_po.[Location Code] as location_code, tbl_po.[Line No_], tbl_po.item_no, round(tbl_po.Quantity,0) as po_qty, round(tbl_pr.[Quantity],0) as pr_rcv,
          tbl_pr.[Document No_] as pr_doc, convert(date,tbl_pr.[Posting Date]) as gr_date, datediff(day, tbl_po.[Order Date],tbl_pr.[Posting Date]) as diff_po_gr_day
          from (
          select h.[Document Type], h.[No_],h.[Buy-from Vendor No_],[Posting Date],h.[Order Date],d.[Location Code],[Line No_],d.[No_] as item_no,
          [quantity], [Quantity Received]
          from [".$this->config->item('sqlserver_live')."Purchase Header] as h
          inner join [".$this->config->item('sqlserver_live')."Purchase line] as d on(h.[No_]=d.[Document No_])
          where year(h.[Order Date])='".$year."' and d.[Quantity]!=d.[Outstanding Quantity]
          and h.[Buy-from Vendor No_] in ('01ATRAINT','01ATRAINT-B')) as tbl_po

          left join(
          select [Document No_], [Line No_], [No_], [Location Code], [Order No_], [Order Line No_],[Posting Date],[Quantity]
          from [".$this->config->item('sqlserver_live')."Purch_ Rcpt_ Line]
          where [Order No_] in (select distinct(h.[No_]) as doc_no
          from [".$this->config->item('sqlserver_live')."Purchase Header] as h
          inner join [".$this->config->item('sqlserver_live')."Purchase line] as d on(h.[No_]=d.[Document No_])
          where year(h.[Order Date])='".$year."' and d.[Quantity]!=d.[Outstanding Quantity]
          and h.[Buy-from Vendor No_] in ('01ATRAINT','01ATRAINT-B')) and [Quantity]>0) as tbl_pr
          on(tbl_po.[No_]=tbl_pr.[Order No_] and tbl_po.[Line No_]=tbl_pr.[Order Line No_])

          order by [po_Vendor],[po_date],[Line No_],[item_no],[pr_doc],[gr_date];";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---
}

?>
