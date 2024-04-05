<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Model_application extends CI_Model{

    function get_make(){
        $db = $this->load->database('default', true);
        $query_temp = "select distinct(make) as make FROM tpimx_nav.crosref_app c order by make;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_model_by_make($make){
        $db = $this->load->database('default', true);
        $query_temp = "select distinct(model) as model FROM tpimx_nav.crosref_app c where make='".$make."' order by model;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_year_by_make_model($make, $model){
        $db = $this->load->database('default', true);
        $query_temp = "select distinct(year) as year FROM tpimx_nav.crosref_app c where make='".$make."' and model='".$model."' order by year;";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_engine_by_make_model_year($make, $model, $year){
        $db = $this->load->database('default', true);
        $query_temp = "select distinct(enginee) as enginee from (
          select concat(enginecylinder,' ', engineblock, ' ',engineliters,' ','L',' ',hp,' ','hp') as enginee
          FROM tpimx_nav.crosref_app c where make='".$make."' and model='".$model."' and year='".$year."') as tbl";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_application($make, $model, $year, $enginecylinder, $engineblock, $engineliters, $hp){
        $db = $this->load->database('default', true);
        $query_temp = "select * FROM tpimx_nav.crosref_app c where make='".$make."' and model like '%".$model."%' and year like '%".$year."%' and enginecylinder like '%".$enginecylinder."%' and engineblock like '%".$engineblock."%' and engineliters like '%".$engineliters."%' and hp like '%".$hp."%' order by partnumber;";

        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //--

    function get_code_search($cross_item, $item_code){
        $db = $this->load->database('default', true);
        $query_temp = "select make, model, year, product, partnumber, concat(enginecylinder, engineblock, ' ',engineliters,'L',' ',hp,' ','hp') as enginee FROM tpimx_nav.crosref_app c where partnumber in(
            SELECT distinct(item_code) FROM tpimx_nav.crosref_item c where cros_item like '%".$cross_item."%' or item_code like '%".$item_code."%');";
        $query = $db->query($query_temp);
        return $query->result_array();
    }
    //---
}
