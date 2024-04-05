<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_inbound extends CI_Model{

    function whrcpt_list_h(){
      $db = $this->load->database('sql_server', true);
      /*$query_temp = "select [No_] as no, [Location Code] as loc_code, [Document Status] as doc_status, [Posting Date] as posting_date from [".$this->config->item('sqlserver_pref')."Warehouse receipt header] where [Location Code]='WH2' and [WH Receipt Status]='1' order by [No_] desc;";
      */

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp2= $user_plant; // 2023-03-02 WH3

      if($query_temp2 == "") $query_temp2 = "'WH2','WH3'";

      $query_temp = "select no, loc_code, posting_date, comment as ext_doc_no
      	from (select [No_] as no, [Location Code] as loc_code, [Document Status] as doc_status, [Posting Date] as posting_date
      	from [".$this->config->item('sqlserver_pref')."Warehouse Receipt Header]
      	where [Location Code] in (".$query_temp2.") and [WH Receipt Status]='1' ) as tbl_inbound
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
          ) [Main] where [No_] like 'TPM-WREC%') as tbl_comment on(tbl_inbound.no=tbl_comment.No_);";


      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function whrcpt_list_d($doc_no){
      $db = $this->load->database('sql_server', true);
      $query_temp = "select [No_] as no, [Line No_] as line_no, [Source No_] as src_no, [Source Line No_] as src_line_no, [Location Code] as location_code, [item no_] as item_no, [Qty_ (Base)] as qty_base, [Qty_ to receive (Base)] as qty_to_receive, [Unit of Measure Code] as uom, [Description] as description,  [Starting Date] as starting_date
      from [".$this->config->item('sqlserver_pref')."Warehouse receipt line] ";

      // where condition
      $query_temp.=" where [No_] in( ";
      foreach($doc_no as $row){ $query_temp.="'".$row."',";}
      $query_temp = substr($query_temp,0,-1);
      $query_temp.=" ) order by [item no_] ;";
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

    function whrcpt_list_h_by_no($no){
      $db = $this->load->database('sql_server', true);
      /*$query_temp = "select [No_] as no, [Location Code] as loc_code, [Document Status] as doc_status, [Posting Date] as posting_date from [".$this->config->item('sqlserver_pref')."Warehouse receipt header] where [Location Code]='WH2' and [No_]='".$no."' order by [No_] desc;";*/

      $user_plant = get_plant_code_user();// 2023-03-02 WH3
      $query_temp2= $user_plant; // 2023-03-02 WH3

      if($query_temp2 == "") $query_temp2 = "'WH2','WH3','WH4'";

      $query_temp = "select no, loc_code, posting_date, comment as ext_doc_no
      	from (select [No_] as no, [Location Code] as loc_code, [Document Status] as doc_status, [Posting Date] as posting_date
      	from [".$this->config->item('sqlserver_pref')."Warehouse Receipt Header]
      	where [Location Code] in(".$query_temp2.") and [No_]='".$no."' ) as tbl_inbound
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
          ) [Main] where [No_]='".$no."') as tbl_comment on(tbl_inbound.no=tbl_comment.No_);";

      $query = $db->query($query_temp);
      return $query->result_array();
    }
    //---

    function whrcpt_update_status($no, $status){
      $db = $this->load->database('sql_server', true);
      $query_temp = "update [".$this->config->item('sqlserver_pref')."Warehouse receipt header] set [WH Receipt Status]='".$status."' where [No_]='".$no."';";
      $query = $db->query($query_temp);
      return $query;
    }
    //---

    function get_to_no_from_whse_receipt_line($doc_no){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select distinct([Source No_]) as source_no from [".$this->config->item('sqlserver_pref')."Warehouse Receipt Line] where [No_]='".$doc_no."';";
        $query = $db->query($query_temp)->row();
        return $query->source_no;
    }
    //---

    function get_transfer_from($doc_no){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select [Transfer-from Code] as transfer_from_code from [".$this->config->item('sqlserver_pref')."Transfer Header] where [No_]='".$doc_no."';";
        $query = $db->query($query_temp)->row();
        return $query->transfer_from_code;
    }
    //---

    function get_whse_shipment_no($doc_no){
        $db = $this->load->database('sql_server', true);
        $query_temp = "select distinct([Whse_ Shipment No_]) as whs_ship_no from [".$this->config->item('sqlserver_pref')."Posted Whse_ Shipment Line]
        where [Source No_]='".$doc_no."';";
        $query = $db->query($query_temp)->row();
        return $query->whs_ship_no;
    }
    //--
}


?>
