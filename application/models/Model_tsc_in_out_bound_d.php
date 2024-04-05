<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Tsc_in_out_bound_d extends CI_Model{
    var $doc_no, $line_no, $src_location_code, $src_code, $src_line_no, $item_code, $qty_to_ship, $qty_to_picked;
    var $qty_outstanding, $uom, $dest_no, $completely_picked, $completely_packed,$qty, $description, $qty_received, $qty_to_packed, $qty_packed_outstanding, $master_barcode, $valuee, $valuee_per_pcs;  // 2023-01-17 master barcode

    function insert_d(){
        $db = $this->load->database('default', true);
        $data = array(
            "doc_no" => $this->doc_no,
            "line_no" => $this->line_no,
            "src_location_code" => $this->src_location_code,
            "src_no" => $this->src_no,
            "src_line_no" => $this->src_line_no,
            "item_code" => $this->item_code,
            "uom" => $this->uom,
            "description" => $this->description,
            "qty" => $this->qty,
            "qty_received" => '0',
            "qty_to_ship" => $this->qty_to_ship,
            "qty_to_packed" => $this->qty_to_packed,
            "qty_packed_outstanding" => $this->qty_packed_outstanding,
            "dest_no" => $this->dest_no,
            "qty_to_picked" => $this->qty_to_picked,
            "qty_outstanding" => $this->qty_outstanding,
            "master_barcode" => $this->master_barcode, // 2023-01-17 master barcode
            "valuee" => $this->valuee, // valuee 2023-01-30
            "valuee_per_pcs" => $this->valuee_per_pcs, // valuee 2023-01-30
        );

        $result = $this->db->insert('tsc_in_out_bound_d', $data);
        if($result) return true; else return false;
    }
    //----

    function get_list($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc_no,line_no,src_location_code,src_no,src_line_no,item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed, master_barcode, valuee, valuee_per_pcs
        FROM tsc_in_out_bound_d t "; // master barcode 2023-01-17

        // where condition
        $query_temp.=" where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) order by item_code ;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_qty_received(){
      $db = $this->load->database('default', true);
      $query_temp = "update tsc_in_out_bound_d t set qty_received='".$this->qty_received."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";
      $query = $db->query($query_temp);
      return true;
    }
    //---

    function get_lastest_qty_received(){
      $db = $this->load->database('default', true);
      $query_temp = "SELECT qty_received FROM tsc_in_out_bound_d t where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";
      $query = $db->query($query_temp)->row();
      return $query->qty_received;
    }
    //---

    function update_qtytopicked_and_outstanding(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT if(qty_to_ship is null,0,qty_to_ship) as qty_to_ship,
        if(qty_outstanding is null, 0, qty_outstanding) as qty_outstanding,
        if(qty_to_picked is null, 0, qty_to_picked) as qty_to_picked
        FROM tsc_in_out_bound_d t where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";

        $query = $db->query($query_temp)->row();
        $qty_outstanding = $query->qty_outstanding;
        $qty_to_picked = $query->qty_to_picked;

        $qty_outstanding = $qty_outstanding + ($this->qty_to_picked * -1);
        $qty_to_picked = $qty_to_picked + $this->qty_to_picked;
        $query_temp = "update tsc_in_out_bound_d t set qty_to_picked='".$qty_to_picked."', qty_outstanding='".$qty_outstanding."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";

        $query = $db->query($query_temp);
        return true;
    }
    //--

    function update_qtytopacked_and_outstanding_packed(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT if(qty_to_ship is null,0,qty_to_ship) as qty_to_ship,
        if(qty_packed_outstanding is null, 0, qty_packed_outstanding) as qty_packed_outstanding,
        if(qty_to_packed is null, 0, qty_to_packed) as qty_to_packed
        FROM tsc_in_out_bound_d t where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";

        $query = $db->query($query_temp)->row();
        $qty_packed_outstanding = $query->qty_packed_outstanding;
        $qty_to_packed = $query->qty_to_packed;

        $qty_packed_outstanding = $qty_packed_outstanding + ($this->qty_to_packed * -1);
        $qty_to_packed = $qty_to_packed + $this->qty_to_packed;
        $query_temp = "update tsc_in_out_bound_d t set qty_to_packed='".$qty_to_packed."', qty_packed_outstanding='".$qty_packed_outstanding."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";


        $query = $db->query($query_temp);
        return true;
    }
    //---

    function get_list_with_so($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc_no,line_no,src_location_code,src_no,src_line_no,item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed,
        ship_to_name, ship_to_addr, ship_to_addr2, ship_to_city, ship_to_contact, ship_to_post_code, ship_to_county, ship_to_ctry_region_code, so_no
        FROM tsc_in_out_bound_d d
        left join tsc_so so on(d.src_no=so.so_no) ";

        // where condition
        $query_temp.=" where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) order by so_no,item_code ;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_dest_no_by_doc_no(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT dest_no, ship_to_name, ship_to_addr, ship_to_addr2, ship_to_city, ship_to_contact, ship_to_post_code, ship_to_county, ship_to_ctry_region_code, so_no
        FROM tsc_in_out_bound_d d
        left join tsc_so so on(d.src_no=so.so_no) where doc_no='".$this->doc_no."' group by so_no;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_docno_by_shipmentno(){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT doc_no FROM tsc_in_out_bound_d t where src_no='".$this->src_no."' group by doc_no;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_list_v2($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "
        select doc_no,line_no,src_location_code,src_no,src_line_no,tbl_a.item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed, available
        from(
        SELECT doc_no,line_no,src_location_code,src_no,src_line_no,item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed
        FROM tsc_in_out_bound_d t ";

        // where condition
        $query_temp.=" where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ) as tbl_a
        inner join tsc_item_invt invt on(tbl_a.item_code=invt.item_code) order by item_code;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_qtytopicked_and_outstanding_v2($data){
        $db = $this->load->database('default', true);
        $query = $db->update_batch('tsc_in_out_bound_d',$data,'serial_number');
        return true;
    }
    //---

    function get_list_with_picking($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT d.doc_no,d.line_no,d.src_location_code,d.src_no,d.src_line_no,d.item_code,
          d.description,d.uom,d.qty, qty_received, qty_to_ship, d.qty_to_picked, qty_outstanding,dest_no, qty_to_packed, pickd.doc_no as pick_no, pickd.line_no as pick_line_no, so.bill_cust_no, so.bill_cust_name,pickd.qty_to_picked as qty_to_picked2, pickd.location_code, zone_code, area_code, rack_code, bin_code
        FROM tsc_in_out_bound_d d
        left join tsc_pick_d pickd on(d.doc_no=pickd.src_no and d.line_no=pickd.src_line_no)
        left join tsc_so so on(so.so_no = d.src_no) ";

        // where condition
        $query_temp.=" where d.doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) order by item_code ;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function get_list_with_docno_lineno_itemcode($doc_no, $line_no, $item_code){
        $db = $this->load->database('default', true);
        $query_temp = "SELECT * FROM tsc_in_out_bound_d t where doc_no='".$doc_no."' and line_no='".$line_no."' and item_code='".$item_code."';";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function update_qty_ship_picked_outstanding_packed_packedoutstanding(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_in_out_bound_d t set
        qty_to_ship='".$this->qty_to_ship."',
        qty_to_picked='".$this->qty_to_picked."',
        qty_outstanding='".$this->qty_outstanding."',
        qty='".$this->qty."',
        qty_packed_outstanding='".$this->qty_packed_outstanding."'
        where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";

        $query = $db->query($query_temp);
        return $query;
    }
    //---

    function delete_line(){
        $db = $this->load->database('default', true);
        $query_temp = "delete from tsc_in_out_bound_d where doc_no='".$this->doc_no."'
        and line_no='".$this->line_no."' and item_code='".$this->item_code."';";
        $query = $db->query($query_temp);
        return true;
    }
    //---

    // 2022-11-29
    function get_list_v3($doc_no){
        $db = $this->load->database('default', true);
        $query_temp = "
        select doc_no,line_no,src_location_code,src_no,src_line_no,tbl_a.item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed, available
        from(
        SELECT doc_no,line_no,src_location_code,src_no,src_line_no,item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed
        FROM tsc_in_out_bound_d t ";

        // where condition
        $query_temp.=" where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ) as tbl_a
        left join (SELECT item_code,count(item_code) as available FROM tsc_item_sn t where statuss='1' and item_code in(
          select item_code from tsc_in_out_bound_d where doc_no in( ";

        foreach($doc_no as $row){ $query_temp.="'".$row."',"; }
          $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) )
        group by item_code) as invt on(tbl_a.item_code=invt.item_code) order by item_code;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    // 2022-11-07 master barcode
    function update_v3($qty,$doc_no,$line_no){
        $db = $this->load->database('default', true);

        unset($qty_outstanding); unset($qty_to_picked);

        for($i=0;$i<count($doc_no);$i++){
            $query_temp = "SELECT if(qty_to_ship is null,0,qty_to_ship) as qty_to_ship,
            if(qty_outstanding is null, 0, qty_outstanding) as qty_outstanding,
            if(qty_to_picked is null, 0, qty_to_picked) as qty_to_picked
            FROM tsc_in_out_bound_d t where doc_no='".$doc_no[$i]."' and line_no='".$line_no[$i]."';";
            $query = $db->query($query_temp)->row();
            $qty_outstanding[$i] = $query->qty_outstanding;
            $qty_to_picked[$i] = $query->qty_to_picked;
        }

        $update_rows = array();
        $multipleWhere = array();

        for($i=0;$i<count($doc_no);$i++){

          $qty_outstanding_temp = $qty_outstanding[$i] + ($qty[$i] * -1);
          $qty_to_picked_temp = $qty_to_picked[$i] + $qty[$i];

            $update_rows_temp = array(
                "qty_to_picked" => $qty_to_picked_temp,
                "qty_outstanding" => $qty_outstanding_temp,
            );
            $update_rows[] = $update_rows_temp;

            $multipleWhere_temp = array('doc_no' => $doc_no[$i], 'line_no' => $line_no[$i]);
            $multipleWhere[] = $multipleWhere_temp;
        }

        for($i=0;$i<count($multipleWhere);$i++){
          $this->db->where($multipleWhere[$i]);
          $this->db->update('tsc_in_out_bound_d', $update_rows[$i]);
        }

        return true;
    }
    //--

    function get_list_v4($doc_no, $whs){
        $db = $this->load->database('default', true);
        $query_temp = "
        select doc_no,line_no,src_location_code,src_no,src_line_no,tbl_a.item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed, available, pcs
        from(
        SELECT doc_no,line_no,src_location_code,src_no,src_line_no,item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed
        FROM tsc_in_out_bound_d t ";

        // where condition
        $query_temp.=" where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) ) as tbl_a
        left join (SELECT item_code,count(item_code) as available FROM tsc_item_sn t where statuss='1' and location_code='".$whs."' and item_code in(
          select item_code from tsc_in_out_bound_d where doc_no in( ";

        foreach($doc_no as $row){ $query_temp.="'".$row."',"; } $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) )
        group by item_code) as invt on(tbl_a.item_code=invt.item_code) ";

        $query_temp.=" left join(
          select item_code,pcs from mst_item_uom_conv where item_code in(select item_code from tsc_in_out_bound_d where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',"; } $query_temp = substr($query_temp,0,-1);
        $query_temp.=" )) group by item_code) as tbl_conv on(tbl_conv.item_code=tbl_a.item_code) order by item_code;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---

    function insert_d_ver2($data){
        $db = $this->load->database('default', true);
        $query = $db->insert_batch('tsc_in_out_bound_d',$data);
        return true;
    }
    //---

    // 2023-06-22
    function get_list_with_so_cs($doc_no){
        $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no,line_no,src_location_code,src_no,src_line_no,item_code,description,uom,qty, qty_received, qty_to_ship, qty_to_picked, qty_outstanding,dest_no, qty_to_packed,
          ship_to_name, ship_to_addr, ship_to_addr2, ship_to_city, ship_to_contact, ship_to_post_code, ship_to_county, ship_to_ctry_region_code, so_no,
          bill_cust_name, u.name as cs_name
          FROM tsc_in_out_bound_d d
          left join tsc_so so on(d.src_no=so.so_no)
          left join mst_cust cust on(cust.cust_no=so.sell_cust_no)
          left join user u on(u.userid_1=cust.cs_person) ";

        // where condition
        $query_temp.=" where doc_no in( ";
        foreach($doc_no as $row){ $query_temp.="'".$row."',";}
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=" ) order by so_no,item_code ;";
        //---

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---
}

?>
