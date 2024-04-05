<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Tsc_received_d2 extends CI_Model{
      var $line_no, $serial_number, $doc_no, $created_datetime_d2, $statuss;

      function insert_d(){
          $db = $this->load->database('default', true);
          $data = array(
              "line_no" => $this->line_no,
              "serial_number" => $this->serial_number,
              "doc_no" => $this->doc_no,
              "created_datetime_d2" => $this->created_datetime_d2,
              "statuss" => $this->statuss,
          );

          $result = $this->db->insert('tsc_received_d2', $data);
          if($result) return true; else return false;
      }
      //----

      function get_data_by_doc_no_line_no_statuss_order_by_datetime_limit($limit){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT line_no, serial_number, doc_no
          FROM tsc_received_d2 t where doc_no='".$this->doc_no."' and statuss='".$this->statuss."'
          and line_no='".$this->line_no."'
          order by created_datetime_d2 limit ".$limit.";";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //--

      function update_status(){
        $db = $this->load->database('default', true);
        $query_temp = "update tsc_received_d2 set statuss='".$this->status."' where serial_number='".$this->serial_number."';";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      function get_data(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT d2.line_no as line_no, serial_number, d2.doc_no as doc_no, created_datetime_d2, statuss, item_code,sn2
          FROM tsc_received_d2 d2
          inner join tsc_received_d d on(d.doc_no=d2.doc_no and d.line_no=d2.line_no) where d2.doc_no='".$this->doc_no."'
          order by item_code, serial_number;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function insert_d_v2($line_no,$doc_no,$status,$sn_temp){
          $db = $this->load->database('default', true);

          $query_temp="insert into tsc_received_d2(line_no, serial_number, doc_no, created_datetime_d2, statuss) values";
          foreach($sn_temp as $row){
              $query_temp.="('".$line_no."','".$row."','".$doc_no."','".date("Y-m-d H:i:s")."','".$status."'),";
          }
          $query_temp = substr($query_temp,0,-1);
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_status_v2($sn,$status){
        $db = $this->load->database('default', true);
        $query_temp="update tsc_received_d2 set statuss='".$status."' where serial_number in(";
        foreach($sn as $row){
            $query_temp.="'".$row."',";
        }
        $query_temp = substr($query_temp,0,-1);
        $query_temp.=")";
        $query = $db->query($query_temp);
        return true;
      }
      //---

      // 2022-11-14 master barcode
      function insert_d_v2_master_barcode($line_no,$doc_no,$status,$sn_temp,$sn2_temp,$valuee){
          $db = $this->load->database('default', true);

          /*$query_temp="insert into tsc_received_d2(line_no, serial_number, doc_no, created_datetime_d2, statuss, sn2) values";
          $j=0;
          foreach($sn_temp as $row){
              $query_temp.="('".$line_no."','".$row."','".$doc_no."','".date("Y-m-d H:i:s")."','".$status."','".$sn2_temp[$j]."'),";
              $j++;
          }
          $query_temp = substr($query_temp,0,-1);
          $query = $db->query($query_temp);
          return true;*/

          $query_temp = array();
          $j=0;
          foreach($sn_temp as $row){
              $query_temp2 = array(
                  "line_no" => $line_no,
                  "serial_number" => $row,
                  "doc_no" => $doc_no,
                  "created_datetime_d2" => date("Y-m-d H:i:s"),
                  "statuss" => $status,
                  "sn2" => $sn2_temp[$j],
                  "valuee" => $valuee,
              );
              $query_temp[] = $query_temp2;
              $j++;
          }

          $query = $db->insert_batch('tsc_received_d2',$query_temp);
          return true;
      }
      //---

      // 2022-11-15 master barcode
      function get_data_per_master_barcode(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT *,count(sn2) as qty FROM tsc_received_d2 t where doc_no='".$this->doc_no."' and line_no='".$this->line_no."' and statuss='0' group by sn2 order by sn2;";
          $query = $db->query($query_temp);

          return $query->result_array();

      }
      //--

        // 2022-11-15 master barcode
      function get_data_per_master_barcode_exlcude_existing($master_code_exclude){
          $db = $this->load->database('default', true);

          if(count($master_code_exclude) == 0){
              $query_temp = "SELECT *,count(sn2) as qty FROM tsc_received_d2 t where doc_no='".$this->doc_no."' and line_no='".$this->line_no."' and statuss='0' group by sn2 order by sn2;";
          }
          else{
              $query_temp = "SELECT *,count(sn2) as qty FROM tsc_received_d2 t where doc_no='".$this->doc_no."' and line_no='".$this->line_no."' and statuss='0' and sn2 not in ( ";

              foreach($master_code_exclude as $row){
                  $query_temp.="'".$row."',";
              }
              $query_temp = substr($query_temp,0,-1);
              $query_temp.=" ) group by sn2 order by sn2;";
          }

          $query = $db->query($query_temp);
          return $query->result_array();

      }
      //---

      // 2022-11-16 master barcode
      function get_data_sn_by_sn2($doc_no, $line_no, $sn2){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT line_no, serial_number, doc_no, sn2 FROM tsc_received_d2 t where doc_no='".$doc_no."' and line_no='".$line_no."' and sn2='".$sn2."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      // 2023-01-17 master barcode
      function get_data_by_master_code(){
          $db = $this->load->database('default', true);
          $query_temp = "select sn2,item_code,count(sn2) as qty_sn2
          FROM tsc_received_d2 d2
          inner join tsc_received_d d on(d.doc_no=d2.doc_no and d.line_no=d2.line_no) where d2.doc_no='".$this->doc_no."'
          group by sn2,item_code;";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function insert_d_v3_master_barcode($line_no,$doc_no,$status,$sn_temp,$sn2_temp,$valuee,$datetime){
          $db = $this->load->database('default', true);

          $query_temp = array();
          for($i=0;$i<count($sn_temp);$i++){
              $query_temp2 = array(
                  "line_no" => $line_no[$i],
                  "serial_number" => $sn_temp[$i],
                  "doc_no" => $doc_no[$i],
                  "created_datetime_d2" => $datetime,
                  "statuss" => $status[$i],
                  "sn2" => $sn2_temp[$i],
                  "valuee" => $valuee[$i],
              );
              $query_temp[] = $query_temp2;
              $j++;
          }

          $query = $db->insert_batch('tsc_received_d2',$query_temp);
          return true;
      }
      //---

      // 2023-09-22
      function update_status_v3($sn,$status){
          $db = $this->load->database('default', true);

          debug("7 = ".date("Y-m-d h:i:s"));

          $query_temp = array();
          foreach($sn as $row){
              $query_temp2 = array(
                  "serial_number" => $row,
                  "statuss" => $status,
              );
              $query_temp[] = $query_temp2;
          }

          debug("8 = ".date("Y-m-d h:i:s"));

          $query = $db->update_batch('tsc_received_d2',$query_temp,'serial_number');

debug("9 = ".date("Y-m-d h:i:s"));

          return true;
      }
      //---

}

?>
