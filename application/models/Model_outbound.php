<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_outbound extends CI_Model{

    var $location_code, $so_no, $to_no, $code;

    function whship_list_h(){
      $db = $this->load->database('sql_server', true);
      /*$query_temp = "select [No_] as no, [Location code] as loc_code, [posting date] as posting_date, [shipment date] as shipment_date, [External Document No_] as ext_doc_no  from [".$this->config->item('sqlserver_pref')."warehouse shipment header] where [Location Code]='WH2' and [WH Pick Status]='1' order by [No_] desc;";*/

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp2= $user_plant; // 2023-03-02 WH3

      if($query_temp2 == "") $query_temp2 = "'WH2','WH3','WH4'";

      $query_temp = "select no, loc_code, posting_date, shipment_date,qty, comment as ext_doc_no
      	from (select h.[No_] as no, h.[Location code] as loc_code, [posting date] as posting_date, h.[shipment date] as shipment_date, sum(d.[Quantity]) as qty
      	from [".$this->config->item('sqlserver_pref')."warehouse shipment header] as h
        inner join [".$this->config->item('sqlserver_pref')."Warehouse Shipment Line] as d on(h.[No_]=d.[No_])
      	where h.[Location Code] in (".$query_temp2.") and [WH Pick Status]='1' group by h.[No_],h.[Location code],[posting date],h.[shipment date]) as tbl_outbound
      	left join(
      	SELECT Main.[No_],
             LEFT(Main.[".$this->config->item('sqlserver_pref')."Warehouse Comment Line],Len(Main.[".$this->config->item('sqlserver_pref')."Warehouse Comment Line])-1) As comment
      FROM
          (
              SELECT DISTINCT ST2.[No_],
                  (
                      SELECT ST1.[Comment] + '<br/>' AS [text()]
                      FROM [".$this->config->item('sqlserver_pref')."Warehouse Comment Line] ST1
                      WHERE ST1.[No_] = ST2.[No_]
                      ORDER BY ST1.[No_]
                      FOR XML PATH (''), TYPE
                  ).value('text()[1]','nvarchar(max)') [".$this->config->item('sqlserver_pref')."Warehouse Comment Line]
              FROM [".$this->config->item('sqlserver_pref')."Warehouse Comment Line] ST2
          ) [Main] where [No_] like 'TPM-WSHIP%') as tbl_comment on(tbl_outbound.no=tbl_comment.No_)";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function whship_list_d($doc_no){
      $db = $this->load->database('sql_server', true);
      $query_temp = "select [No_] as no,[Line No_] as line_no, [Source No_] as src_no, [Source Line No_] as src_line_no,
      [Location Code] as location_code, [Item No_] as item_no,[Qty_ (Base)] as qty,[Qty_ to Ship (Base)] as qty_to_ship,
      [Unit of Measure Code] as uom, [Description] as description, [Destination No_] as destination_no
      from [".$this->config->item('sqlserver_pref')."Warehouse Shipment Line] ";

      // where condition
      $query_temp.=" where [No_] in( ";
      foreach($doc_no as $row){ $query_temp.="'".$row."',";}
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) order by line_no ;";
      //---

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function check_tsc_in_out_bound_h_existing($id){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT count(doc_no) as exist FROM tsc_in_out_bound_h t where doc_no='".$id."';";
        $query = $db->query($query_temp)->row();
        return $query->exist;
    }
    //---

    function whship_list_h_by_no($no){
      $db = $this->load->database('sql_server', true);
      /*$query_temp = "select [No_] as no, [Location code] as loc_code, [posting date] as posting_date, [shipment date] as shipment_date, [External Document No_] as ext_doc_no from [".$this->config->item('sqlserver_pref')."warehouse shipment header] where [Location Code]='WH2' and [No_]='".$no."' order by [No_] desc;";*/

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp2= $user_plant; // 2023-03-02 WH3

      if($query_temp2 == "") $query_temp2 = "'WH2','WH3','WH4'";

      $query_temp = "select no, loc_code, posting_date, shipment_date, comment as ext_doc_no
      	from (select [No_] as no, [Location code] as loc_code, [posting date] as posting_date, [shipment date] as shipment_date
      	from [".$this->config->item('sqlserver_pref')."warehouse shipment header]
      	where [Location Code] in (".$query_temp2.") and [No_]='".$no."' ) as tbl_outbound
      	left join(
      	SELECT Main.[No_],
             LEFT(Main.[".$this->config->item('sqlserver_pref')."Warehouse Comment Line],Len(Main.[".$this->config->item('sqlserver_pref')."Warehouse Comment Line])-1) As comment
      FROM
          (
              SELECT DISTINCT ST2.[No_],
                  (
                      SELECT ST1.[Comment] + '<br/>' AS [text()]
                      FROM [".$this->config->item('sqlserver_pref')."Warehouse Comment Line] ST1
                      WHERE ST1.[No_] = ST2.[No_]
                      ORDER BY ST1.[No_]
                      FOR XML PATH (''), TYPE
                  ).value('text()[1]','nvarchar(max)') [".$this->config->item('sqlserver_pref')."Warehouse Comment Line]
              FROM [".$this->config->item('sqlserver_pref')."Warehouse Comment Line] ST2
          ) [Main] where [No_]='".$no."') as tbl_comment on(tbl_outbound.no=tbl_comment.No_)";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function get_so_header_shipto(){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select [No_] as so_no, [Location Code] as loc_code,[Sell-to Customer No_] as sell_cust_no, [Bill-to Customer No_] as bill_cust_no,
        [Bill-to Name] as bill_to_name, [Bill-to Address] as bill_to_addr, [Bill-to Address 2] as bill_to_addr2, [Bill-to City] as bill_to_city, [Bill-to Contact] as bill_to_contact,
        [Bill-to Post Code] as bill_to_post_code, [Bill-to County] as bill_to_county, [Bill-to Country_Region Code] as bill_to_ctry_region_code,
        [Ship-to Name] as ship_to_name, [Ship-to Address] as ship_to_addr, [Ship-to Address 2] as ship_to_addr2, [Ship-to City] as ship_to_city,
        [Ship-to Contact] as ship_to_contact, [Ship-to Post Code] as ship_to_post_code, [Ship-to County] as ship_to_county,
        [Ship-to Country_Region Code] as ship_to_ctry_region_code, [Sell-to Customer Name] as sell_to_customer_name, [Sell-to Address] as sell_to_addr,
        [Sell-to Address 2] as sell_to_addr2, [Sell-to City] as sell_to_city,
        [Sell-to Contact] as sell_to_contact, [Sell-to Post Code] as sell_to_post_code, [Sell-to County] as sell_to_county,
        [Sell-to Country_Region Code] as sell_to_ctry_code
        from [".$this->config->item('sqlserver_pref')."Sales Header] where [No_]='".$this->so_no."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_transfer_order_line(){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select distinct([Transfer-To Bin Code]) as transfer_to_bin_code, [Transfer-to Code] as transfer_to_code
        from [".$this->config->item('sqlserver_pref')."transfer line] where [Document No_]= '".$this->to_no."';";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_bin_info(){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select [Location Code] as location_code, [code] as code, [Customer No_] as cust_no, [Ship-to Code] as ship_to
        from [".$this->config->item('sqlserver_pref')."Bin] where code='".$this->code."' and [Location Code]='".$this->location_code."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function whship_update_status($no, $status){
        $db = $this->load->database('sql_server', true);
        $query_temp = "update [".$this->config->item('sqlserver_pref')."Warehouse shipment header] set [WH Pick Status]='".$status."' where [No_]='".$no."';";
        $query = $db->query($query_temp);
        return $query;
    }
    //---

    function get_ship_to_address_from_nav(){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select [Customer No_] as cust_no, [Code] as code, [Name] as namee, [Name 2] as name2, [Address] as addresss, [Address 2] as address2, [City] as city, [Contact] as contact, [Phone No_] as phone_no, [Country_Region Code] as country_region_code, [Location Code] as location_code, [Post Code] as post_code, [County] as county from [".$this->config->item('sqlserver_live')."Ship-to Address]

        union

        select [Code] as cust_no, [Code] as code,
        [Name] as namee, [Name 2] as name2, [Address] as addresss,
        [Address 2] as address2, [City] as city, [Contact] as contact,
        [Phone No_] as phone_no, [Country_Region Code] as country_region_code,
        [Code] as location_code, [Post Code] as post_code,
        [County] as county from [".$this->config->item('sqlserver_live')."Location]"; // 2023-03-01 WH3
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_shipment_invoice_nav($ship_no){
        $db = $this->load->database('sql_server_live', true);
        $query_temp = "select tbl_shp.[Posted Source No_] as shipment_no, invoice_line.[Document No_] as invoice_no
            from (
            select [Posted Source No_] from [".$this->config->item('sqlserver_pref')."Posted Whse_ Shipment Line]
            where [Whse_ Shipment No_]='".$ship_no."' group by [Posted Source No_]) as tbl_shp
            left join [".$this->config->item('sqlserver_pref')."Sales Invoice Line] invoice_line on(invoice_line.[Shipment No_]=tbl_shp.[Posted Source No_])
            group by tbl_shp.[Posted Source No_], invoice_line.[Document No_] ";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--
}


?>
