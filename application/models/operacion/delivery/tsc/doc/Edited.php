<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edited extends CI_Model{

    function insert($created_at, $created_by, $doc_no, $field, $from, $to, $remark){

      $db = $this->load->database('default_oprc', true);
      $data = array(
          "doc_no" => $doc_no,
          "created_at" => $created_at,
          "created_by" => $created_by,
          "field" => $field,
          "from" => $from,
          "to" => $to,
          "remark" => $remark,
      );

      $result = $db->insert('tsc_doc_edited', $data);
      if($result) return true; else return false;
    }
    //---

    function insert_v2($data){
        $db = $this->load->database('default_oprc', true);

        $query_temp = array();
        $j=0;
        foreach($data as $row){
            $query_temp2 = array(
              "doc_no"      => $row["doc_no"],
              "created_at"  => $row["created_at"],
              "created_by"  => $row["created_by"],
              "field"       => $row["field"],
              "from"        => $row["from"],
              "to"          => $row["to"],
              "remark"      => $row["remark"],
            );

            $query_temp[] = $query_temp2;
            $j++;
        }

        $query = $db->insert_batch('tsc_doc_edited',$query_temp);
        return true;
    }
    //---
}

?>
