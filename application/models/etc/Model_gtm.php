<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_gtm extends CI_Model{

    var $custno,$last_inventory_month,$last_inventory_year,$last_3month_from,$last_3month_to,$month,$year;

    function list_region(){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select kodescabang,ket from m_scabang where kodescabang like 'NGR%';");
        return $query->result_array();
    }
    //----------------

    function list_sales_supervisor_by_region($region){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select slsno,slsname,slstp,divisi,kodecabang
                            from fsalesman where slstp='T' and divisi='00'
                            and ( slsno not like 'AA%' and slsno not like 'BA%' and slsno not like 'EA%'
                            and slsno not like 'DA%' and slsno not like 'EB%' and slsno not like 'CA%'
                            and slsno not like 'CB%' and slsno not like 'BB%' and slsno not like 'C51%'
                            and slsno not like 'DB%' and slsno not like 'GA%' and slsno not like 'GB%')
                            and kodecabang like '".$region."%' order by slsno;");
        return $query->result_array();
    }
    //----------------

    function list_keywhosaler_by_supervisor($supervisor){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select fr.slsno,fcm.custno,custname from frute fr
                            inner join fcustmst fcm on(fr.custno=fcm.custno) where slsno='".$supervisor."';");
        return $query->result_array();
    }
    //----------------

    function get_supervisor_name($slsno){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select slsname from fsalesman where slsno='".$slsno."';")->row();
        return $query->slsname;
    }
    //-------------------

    function get_keywhosaler_name($custno){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select custname from fcustmst where custno='".$custno."';")->row();
        return $query->custname;
    }
    //-------------------

    function get_frequency_frute_order($slsno,$custno){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select M1+M2+M3+M4 as freq from (
                              SELECT
                              if(M1 = 'Y',1,0) as M1,
                              if(M2 = 'Y',1,0) as M2,
                              if(M3 = 'Y',1,0) as M3,
                              if(M4 = 'Y',1,0) as M4
                              FROM frute_order f where slsno='".$slsno."' and custno='".$custno."') as a;")->row();
        return $query->freq;
    }
    //----------------

    function get_top_credit_limit($custno){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select top,top_desc,climit from fcustmst fcm inner join ftop top on(fcm.cterm=top.top)
                              where custno='".$custno."';");
        return $query->result_array();
    }
    //---------------------

    function mps_report(){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select sku.brand,sku.brandname,
                      if(last_month_inventory.qty is null,0,last_month_inventory.qty) as last_inv,
                      if(last_3month_invoice.qty is null,0,last_3month_invoice.qty/3) as last_3month_invoice,
                      if(last_3month_invoice.qty is null,0,last_3month_invoice.qty/3/26) as last_3month_invoice_per_day,
                      if(last_month_inventory.qty is null or last_3month_invoice.qty is null,0,
                      last_month_inventory.qty / (last_3month_invoice.qty/3/24) ) as dil,
                      if(target.qty is null,0,target.qty) as target
                      from(
                      select * from (
                      select * from dbmobile.fbrand where flag_aktif='Y'
                      and brand!='041' and brand!='004' and brand!='032' and brand!='033' and brand!='027'
                      ) as fbrand
                      inner join emanl.mg3_order mg3o on(fbrand.brand=mg3o.mg3) order by mg3o.zorder) as sku

                      left join (
                      select * from emanl.z_inventory_last_date_mps where custno='".$this->custno."'
                      and (month(month_year)='".$this->last_inventory_month."'
                      and year(month_year)='".$this->last_inventory_year."')) as last_month_inventory
                      on (sku.brand =  last_month_inventory.brand)

                      left join (SELECT fb.brand,sum(s.qty) as qty FROM dbmobile.sap_web_inv_sfa s
                      inner join (select * from dbmobile.fmster where flag_aktif='Y') as fm on fm.pcode=s.pcode
                      inner join dbmobile.fbrand fb on(fm.mg3=fb.brand)
                      where custno = '".$this->custno."'
                      and invoice_date >= '".$this->last_3month_from."'
                      and invoice_date <= '".$this->last_3month_to."' group by fb.brand) as last_3month_invoice
                      on (sku.brand = last_3month_invoice.brand)

                      left join (SELECT prlin,sum(qty) as qty FROM z_ftarget z
                      where bulan='".$this->month."' and tahun='".$this->year."' and custno='".$this->custno."'
                      group by prlin) as target on(sku.brand=target.prlin)
                      ;");
        return $query->result_array();
    }
    //--------------------

    function get_sales_invoice($date_from,$date_to){

        $db = $this->load->database('sfa_live', true);

        $query = $db->query("select docnumber,date_format(docdate,'%Y-%m-%d') as docdate,
                    si.cardcode as custcode, cardname as custname,divch,cardgroup as custgroup,region,
                    qty,netweight,price,priceaftervat,discpercent,grosssales,netsales,doccurr as curr,
                    itemcodesrc as product_id,prod.item_description,prod.division,prod.category,prod.brand,prod.sku
                    from(
                    select * from emanl.sales_invoice where docdate between '".$date_from."' and '".$date_to."') as si
                    left join emanl.emanl_cust cust on(si.cardcode=cust.cardcode)
                    left join emanl.emanl_prod prod on(si.itemcodesrc=prod.productkey);");

        return $query->result_array();
    }
    //---
	
	function get_sales_order($date_from,$date_to){

        $db = $this->load->database('sfa_live', true);

        $query = $db->query("select docnumber,date_format(docdate,'%Y-%m-%d') as docdate,
                    so.cardcode as custcode, cardname as custname,divch,cardgroup as custgroup,region,
                    qty,netweight,price,priceaftervat,discpercent,grosssales,netsales,doccurr as curr,
                    itemcodesrc as product_id,prod.item_description,prod.division,prod.category,prod.brand,prod.sku
                    from(
                    select * from emanl.sales_order where docdate between '".$date_from."' and '".$date_to."') as so
                    left join emanl.emanl_cust cust on(so.cardcode=cust.cardcode)
                    left join emanl.emanl_prod prod on(so.itemcodesrc=prod.productkey);");

        return $query->result_array();
    }
    //---
}


?>
