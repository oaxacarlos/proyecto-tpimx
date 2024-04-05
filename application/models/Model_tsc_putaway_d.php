<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_putaway_d extends CI_Model{
      var $doc_no, $line_no, $src_location_code, $src_no, $src_line_no, $item_code, $qty_to_put, $uom,
      $completely_put, $put_datetime, $created_datetime, $desc;

      function insert(){
          $db = $this->load->database('default', true);
          $data = array(
              "doc_no" => $this->doc_no,
              "line_no" => $this->line_no,
              "src_location_code" => $this->src_location_code,
              "src_no" => $this->src_no,
              "src_line_no" => $this->src_line_no,
              "item_code" => $this->item_code,
              "uom" => $this->uom,
              "qty_to_put" => $this->qty_to_put,
              "description" => $this->desc,
              "created_datetime" => $this->created_datetime,
          );

          $result = $this->db->insert('tsc_put_away_d', $data);
          if($result) return true; else return false;
      }

      //---

      function get_list_data(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT doc_no, line_no, src_location_code, src_no, src_line_no, item_code, qty_to_put, uom, completely_put, put_datetime, created_datetime, description
          FROM tsc_put_away_d t where doc_no='".$this->doc_no."';";
          $query = $db->query($query_temp);
          return $query->result_array();
      }
      //---

      function update_start_finish_time(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d set put_datetime='".$this->put_datetime."', completely_put='".$this->completely_put."' where doc_no='".$this->doc_no."' and line_no='".$this->line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2022-11-16 master barcode
      function insert_v2($doc_no, $line_no, $src_location_code, $src_no, $src_line_no, $item_code, $uom, $qty_to_put, $desc, $created_datetime, $total_row){
          $db = $this->load->database('default', true);

          unset($put_away_d_line);
          $query_temp = array();
          for($i=0;$i<$total_row;$i++){
              $data_put_away_d = array(
                  "doc_no" => $doc_no,
                  "line_no" => $i+1,
                  "src_location_code" => $src_location_code[$i],
                  "src_no" => $src_no[$i],
                  "src_line_no" => $src_line_no[$i],
                  "item_code" => $item_code[$i],
                  "uom" => $uom[$i],
                  "qty_to_put" => $qty_to_put[$i],
                  "desc" => $desc[$i],
                  "created_datetime" => $created_datetime,
              );
              $query_temp[] = $query_temp2;
              $put_away_d_line[] = $i+1;
          }

          $query = $db->insert_batch('tsc_put_away_d',$query_temp);
          return $put_away_d_line;
      }
      //--

      // 2022-11-17 master barcode
      function update_start_time($doc_no, $line_no, $datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d set put_datetime='".$datetime."' where doc_no='".$doc_no."' and line_no='".$line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2022-11-18 master barcode
      function check_start_time_is_null($doc_no, $line_no){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT put_datetime FROM tsc_put_away_d t where doc_no='".$doc_no."' and line_no='".$line_no."';";
          $query = $db->query($query_temp)->row();
          if(is_null($query->put_datetime) or $query->put_datetime == "") return 1;
          else return 0;
      }
      //---

      // 2022-11-18 master barcode
      function update_completely_time($doc_no, $line_no, $datetime){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_put_away_d set completely_put='".$datetime."' where doc_no='".$doc_no."' and line_no='".$line_no."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      // 2022-11-16 master barcode
      function insert_v3($doc_no, $line_no, $src_location_code, $src_no, $src_line_no, $item_code, $uom, $qty_to_put, $desc, $created_datetime, $total_row){
          $db = $this->load->database('default', true);

          $query_temp = array();
          for($i=0;$i<$total_row;$i++){
              $query_temp2 = array(
                  "doc_no" => $doc_no[$i],
                  "line_no" => $line_no[$i],
                  "src_location_code" => $src_location_code[$i],
                  "src_no" => $src_no[$i],
                  "src_line_no" => $src_line_no[$i],
                  "item_code" => $item_code[$i],
                  "uom" => $uom[$i],
                  "qty_to_put" => $qty_to_put[$i],
                  "description" => $desc[$i],
                  "created_datetime" => $created_datetime[$i],
              );
              $query_temp[] = $query_temp2;
          }

          $query = $db->insert_batch('tsc_put_away_d',$query_temp);
          return true;
      }
      //--

}

?>
