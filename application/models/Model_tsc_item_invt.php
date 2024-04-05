<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_tsc_item_invt extends CI_Model{
      var $item_code, $available, $picking, $picked, $packing, $extraction;

      function update_available(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_item_invt set available='".$this->available."' where item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function get_lasted_available(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT available FROM tsc_item_invt where item_code='".$this->item_code."';";
          $query = $db->query($query_temp)->row();
          return $query->available;
      }
      //---

      function update_invt(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT available,picking,picked,packing FROM tsc_item_invt where item_code='".$this->item_code."';";
          $query = $db->query($query_temp)->row();
          $available = $query->available;
          $picking = $query->picking;
          $picked = $query->picked;
          $packing = $query->packing;

          $available += $this->available;
          $picking += $this->picking;
          $picked += $this->picked;
          $packing += $this->packing;

          $query_temp = "update tsc_item_invt set available='".$available."', picking='".$picking."', picked='".$picked."', packing='".$packing."' where item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_available2(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_item_invt set available=available + ".$this->available." where item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function update_invt2(){
          $db = $this->load->database('default', true);
          $query_temp = "update tsc_item_invt set available=available + ".$this->available.",picking=picking + ".$this->picking.", picked=picked + ".$this->picked.", packing=packing + ".$this->packing."  where item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---

      function stock_invt(){
          $db = $this->load->database('default', true);
          $query = $db->query("SELECT item_code, itm.name, available, picking, picked, packing, extraction FROM tsc_item_invt invt left join mst_item itm on(invt.item_code=itm.code)");
          return $query->result_array();
      }
      //---

      function stock_invt_by_code(){
          $db = $this->load->database('default', true);
          $query_temp = "SELECT item_code, itm.name, available, picking, picked, packing, extraction FROM tsc_item_invt invt left join mst_item itm on(invt.item_code=itm.code) where item_code='".$this->item_code."';";
          $query = $db->query($query_temp)->row();
          $data["available"]  = $query->available;
          $data["picking"]    = $query->picking;
          $data["picked"]     = $query->picked;
          $data["packing"]    = $query->packing;
          $data["extraction"]  = $query->extraction;
          return $data;
      }
      //---

      function update_invt3(){
          $db = $this->load->database('default', true);

          $query_temp = "update tsc_item_invt set extraction=extraction + ".$this->extraction." ,available=available + ".$this->available."  where item_code='".$this->item_code."';";
          $query = $db->query($query_temp);
          return true;
      }
      //---
}

?>
