<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_empc extends CI_Model{

    var $empc_h_code,$empc_h_created_date,$empc_h_created_datetime,$counter,$empc_h_created_user;
    var $empc_h_doc_date,$empc_status,$canceled,$empc_h_text1,$empc_type_code,$depart_code;
    var $email_user,$empc_approval_code,$empc_h_text2,$empc_h_text3,$gl_id,$costcenter_code;
    var $mat_id,$qty,$uom,$posnr,$plant_code,$approval_date,$approval_datetime,$empc_h_approval_text1;
    var $empc_d_text1,$customer,$project,$prefix_code,$attachment;

    var $sap_code,$sap_matdoc,$sap_matid,$tbnum,$lgnum,$tanum,$kquit,$qdalu,$zeile;
    var $bwart_mat,$sap_matdoc_date,$dmbtr,$waers,$sap_matdoc_time,$empcps,$insert_datetime;

    function list_all_material($status){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from mst_material where active like '%".$status."%'");
        return $query->result_array();
    }
    //----------------

    function list_all_plant(){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from mst_plant where active = 'Y'");
        return $query->result_array();
    }
    //----------------

    function list_all_costcenter(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_costcenter where active = 'Y'");
      return $query->result_array();
    }
    //--------------------

    function list_costcenter_bydepot($plant_code){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_costcenter where active = 'Y'
                    and costcenter_code like '".$plant_code."%'");
      return $query->result_array();
    }
    //--------------------

    function list_all_gl(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_gl gl inner join mst_department dpt on(gl.depart_code=dpt.depart_code)
                          where active = 'Y'");
      return $query->result_array();
    }
    //--------------------

    function list_all_mst_movt_type(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_movt_type where active = 'Y'");
      return $query->result_array();
    }
    //--------------------

    function list_all_uom(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_uom m order by m.order");
      return $query->result_array();
    }
    //--------------------

    function insert_empc_h(){
        $db = $this->load->database('default', true);
        $data = array(
          'empc_h_code' => $this->empc_h_code ,
          'empc_h_created_date' => $this->empc_h_created_date ,
          'empc_h_created_datetime' => $this->empc_h_created_datetime,
          'counter' => $this->counter,
          'empc_h_created_user' => $this->empc_h_created_user,
          'empc_h_doc_date' => $this->empc_h_doc_date,
          'empc_status' => $this->empc_status,
          'empc_h_text1' => $this->empc_h_text1,
          'empc_h_text2' => $this->empc_h_text2,
          'empc_h_text3' => $this->empc_h_text3,
          'mst_movt_type_code' => $this->mst_movt_type_code,
          'depart_code' => $this->depart_code,
          'empc_approval_code' => $this->empc_approval_code,
          'email_user' => $this->email_user,
          'costcenter_code' => $this->costcenter_code,
          'gl_id' => $this->gl_id,
          'plant_code' => $this->plant_code,
          'canceled' => '',
          'customer_text' => $this->customer,
          'empc_project_code' => $this->project
          );

          $result = $this->db->insert('empc_h', $data);

          if($result) return true;
          else return false;
    }
    //-----------------------

    function insert_empc_d(){
        $db = $this->load->database('default', true);
        $data = array(
          'empc_h_code'  => $this->empc_h_code,
          'mat_id'      => $this->mat_id,
          'qty'         => $this->qty,
          'uom'         => $this->uom,
          'posnr'       => $this->posnr,
          'empc_d_text1' => $this->empc_d_text1,
          );

          $result = $this->db->insert('empc_d', $data);

          if($result) return true;
          else return false;
    }
    //-----------------

    function insert_empc_d_version2($table_empc_d){
        $db = $this->load->database('default', true);
        $data = array();
        foreach($table_empc_d as $row){
              $data[] = array(
                  'empc_h_code'  => $this->empc_h_code,
                  'mat_id'      => $row['matid'],
                  'qty'         => $row['qty'],
                  'uom'         => $row['uom'],
                  'posnr'       => $row['posnr'],
                  'empc_d_text1' => $row['empc_d_text1'],
              );
        }
        $result = $this->db->insert_batch('empc_d', $data);

        if($result) return true; else return false;
    }
    //-----------------

    function create_new_empc_code($prefix_value,$digits_after_prefix,$last_code,$datecode){
        if($last_code == 0){
            $newcode = $prefix_value.$datecode."0001";
        }
        else{
            $last_code = $last_code + 1;
            $numlength = strlen((string)$last_code);
            if($numlength == $digits_after_prefix){
                $newcode = $prefix_value.$datecode.$last_code;
            }
            else{
                $newcode = $prefix_value.$datecode;
                $to = $digits_after_prefix - $numlength;
                for($i=0;$i<$to;$i++){
                  $newcode.= "0";
                }
                $newcode.= $last_code;
            }
        }

        return $newcode;
    }

    //-----------------

    function get_last_code_empc_h($datecode,$code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT if(empc_h_code is NULL,0,empc_h_code) as empc_h_code FROM empc_h i
                      where empc_h_code like '".$code.$datecode."%' order by empc_h_code desc limit 1;");
        return $query->result();
    }
    //----------------

    function list_approval($user_id,$depart_code,$empc_approval_code_user){

        if(count($empc_approval_code_user)==0){
            $query_approval_code = "";
        }
        else{
            $query_approval_code = "and ( ";
            for($i=0;$i<count($empc_approval_code_user);$i++){
                $query_approval_code.= "ih.empc_approval_code='".$empc_approval_code_user[$i]."' or";
            }

            if($query_approval_code!=""){
                $query_approval_code = substr($query_approval_code,0,-2);
                $query_approval_code.=" )";
            }
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join mst_movt_type on(ih.empc_type_code=mst_movt_type.movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join empc_approval_user iappu on(ih.empc_approval_code=iappu.empc_approval_code)
                where iappu.user_id='".$user_id."'
                and ih.canceled!='X' ".$query_approval_code." order by empc_h_created_datetime;");

        //where iappu.user_id='".$user_id."' and ih.depart_code='".$depart_code."'

        return $query->result_array();
    }
    //-------------

    function get_approval_level1(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT empc_approval_code FROM empc_approval i where empc_approval_order is not null
                            order by empc_approval_order limit 1;");
        return $query->result();
    }
    //---------

    function get_approval_person(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_approval_user i inner join user on(i.user_id=user.user_id)
                            where empc_approval_code='".$this->empc_approval_code."';");
        return $query->result_array();
    }
    //----------

    function list_approval_by_empc_code($empc_code){
        $db = $this->load->database('default', true);

        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code = dpt.depart_code)
                inner join mst_movt_type on(ih.empc_type_code = mst_movt_type.movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                where ih.empc_h_code='".$empc_code."'
                order by empc_h_created_datetime;");
                  //and ih.canceled!='X'
        return $query->result_array();
    }
    //-------------

    function list_empc_d_by_code($empc_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,i.uom as d_uom FROM empc_d i inner join mst_material mm on(i.mat_id=mm.mat_id)
                            where i.empc_h_code='".$empc_code."';");
        return $query->result_array();
    }

    function update_status_empc_to_canceled($user_id,$date,$datetime,$empc_code,$remarks){
        $db = $this->load->database('default', true);
        $query = $db->query("update empc_h set empc_status='EMPCST005',canceled='X', empc_h_text2='".$remarks."',
                      empc_canceled_user='".$user_id."',canceled_date='".$date."',canceled_datetime='".$datetime."'
                      where empc_h_code='".$empc_code."';");
        return true;
    }
    //-------------------

    function get_approval_level_user($user_id){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT empc_approval_code
                      FROM empc_approval_user i where user_id='".$user_id."'");
        return $query->result_array();
    }
    //------

    function get_approval_one_level_up($approval_code){
        $db = $this->load->database('default', true);
        $query = $db->query("select b.empc_approval_code as code from(
                      SELECT * FROM empc_approval i where empc_approval_code='".$approval_code."') as a,
                      (SELECT * FROM empc_approval i) as b
                      where b.empc_approval_order > a.empc_approval_order
                      order by b.empc_approval_order limit 1;");
        if($query->num_rows() == 0) return 0;

        $query = $db->query("select b.empc_approval_code as code from(
                             SELECT * FROM empc_approval i where empc_approval_code='".$approval_code."') as a,
                            (SELECT * FROM empc_approval i) as b
                            where b.empc_approval_order > a.empc_approval_order
                            order by b.empc_approval_order limit 1;")->row();
        return $query->code;
    }
    //---------------

    function update_status_empc_to_approval($empc_code,$approval_code,$empc_status){
        $db = $this->load->database('default', true);
        $query = $db->query("update empc_h set empc_status='".$empc_status."', empc_approval_code='".$approval_code."'
                      where empc_h_code='".$empc_code."';");
        return true;
    }
    //-------------------

    function get_status_from_approval_code($approval_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT empc_status_code FROM empc_status_n_approval i
                      where empc_approval_code='".$approval_code."';")->row();
        return $query->empc_status_code;
    }
    //---------------

    function get_email_from_userid($user_id){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT email FROM user u where user_id='".$user_id."';")->row();
      return $query->email;
    }
    //-------------

    function insert_empc_h_approval($user_id){
        $db = $this->load->database('default', true);

        $data = array(
          'empc_h_code'              => $this->empc_h_code,
          'empc_approval_code'       => $this->empc_approval_code,
          'approval_date'           => $this->approval_date,
          'approval_datetime'       => $this->approval_datetime,
          'empc_h_approval_text1'    => $this->empc_h_approval_text1,
          'email_approval'          => $this->email_user,
          'user_id'                 => $user_id,
          );

          $result = $this->db->insert('empc_h_approval', $data);

          if($result) return true;
          else return false;
    }
    //----------------

    function list_approval_sap($user_id,$empc_approval_code_user){

        $query_approval_code = "";
        for($i=0;$i<count($empc_approval_code_user);$i++){
            $query_approval_code.= "ih.empc_approval_code='".$empc_approval_code_user[$i]."' or";
        }

        if($query_approval_code!=""){
            $query_approval_code = substr($query_approval_code,0,-2);
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join mst_movt_type on(ih.mst_movt_type_code=mst_movt_type.mst_movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join empc_approval_user iappu on(ih.empc_approval_code=iappu.empc_approval_code)
                where ih.canceled!='X' and (".$query_approval_code.")
                order by empc_h_created_datetime;");

        return $query->result_array();
    }
    //-------------

    function list_email_participant($empc_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT empc_h_code,email_user as email FROM empc_h h where empc_h_code='".$empc_code."'
                      union
                      select empc_h_code,email_approval as email from empc_h_approval i where empc_h_code='".$empc_code."';");
        return $query->result_array();
    }
    //--------------

    function list_empc_h_approval($empc_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_h_approval i
                      inner join empc_approval ip on(i.empc_approval_code=ip.empc_approval_code)
                      inner join user u on(u.user_id=i.user_id)
                      where i.empc_h_code='".$empc_code."' order by i.approval_datetime;");
        return $query->result_array();
    }
    //--------------

    function update_sap_no_empc_h($empc_code,$sap_no){
        $db = $this->load->database('default', true);
        $query = $db->query("update empc_h set sap_no='".$sap_no."' where empc_h_code='".$empc_code."';");
        return true;
    }
    //-------------------

    function report_empc_h_by_user($user_id,$from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join mst_movt_type on(ih.mst_movt_type_code=mst_movt_type.mst_movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                where ih.empc_h_created_user='".$user_id."' and
                empc_h_created_date >='".$from."' and empc_h_created_date <='".$to."'
                order by empc_h_created_datetime;");
        return $query->result_array();
    }

    //----------------------------------

    function report_empc_h_by_department($depart_code,$from,$to,$plant_code){
        if($plant_code == ""){
            $query_plant_code = " ih.plant_code like '%%' ";
        }
        else{
           $query_plant_code = " ih.plant_code rlike '".$plant_code."' ";
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join mst_movt_type on(ih.empc_type_code=mst_movt_type.movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                where ih.depart_code like '%".$depart_code."%'
                and empc_h_created_date >='".$from."' and empc_h_created_date <='".$to."'
                and ".$query_plant_code."
                order by empc_h_created_datetime;");
        return $query->result_array();
    }

    //-------------------

    function report_empc_show_all($from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join mst_movt_type on(ih.empc_type_code=mst_movt_type.movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                where empc_h_created_date >='".$from."' and empc_h_created_date <='".$to."'
                order by empc_h_created_datetime;");
        return $query->result_array();
    }
    //-----------------------

    function get_approval_order_below_from_approval_code_user($user_id){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_approval i
                  where empc_approval_order < (SELECT empc_approval_order FROM empc_approval_user i
                  inner join empc_approval itp on(i.empc_approval_code = itp.empc_approval_code)
                  where i.user_id='".$user_id."');");
        return $query->result_array();
    }
    //------------------------

    function update_empc_h_attachment($attachment){
        $db = $this->load->database('default', true);
        $query = $db->query("update empc_h set attachment='".$attachment."' where empc_h_code='".$this->empc_h_code."';");
        return true;
    }
    //----------------

    function list_department_cross_approval($user_id,$depart_code,$empc_approval_code_user){
        if(count($empc_approval_code_user)==0){
            $query_approval_code = "";
        }
        else{
            $query_approval_code = "and ( ";
            for($i=0;$i<count($empc_approval_code_user);$i++){
                $query_approval_code.= "i.empc_approval_code='".$empc_approval_code_user[$i]."' or";
            }

            if($query_approval_code!=""){
                $query_approval_code = substr($query_approval_code,0,-2);
                $query_approval_code.=" )";
            }
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT depart_code FROM empc_approval_user_cross i
                  inner join empc_approval_user u on(i.user_id=u.user_id)
                  where i.user_id='".$user_id."' and i.depart_code != '".$depart_code."'
                  ".$query_approval_code.";");

        return $query->result_array();
    }
    //-----------------------

    function list_approval_cross_department($user_id,$list_depart,$empc_approval_code_user){

        // query list approval
        $query_approval_code = "";
        if(count($empc_approval_code_user)==0){
            $query_approval_code = "";
        }
        else{
            $query_approval_code = "and ( ";
            for($i=0;$i<count($empc_approval_code_user);$i++){
                $query_approval_code.= "ih.empc_approval_code='".$empc_approval_code_user[$i]."' or";
            }

            if($query_approval_code!=""){
                $query_approval_code = substr($query_approval_code,0,-2);
                $query_approval_code.=" )";
            }
        }

        // query list department
        $query_list_depart = "";
        if($list_depart == 0){
          $query_list_depart = "";
        }
        else{
            $query_list_depart = "and ( ";
            for($i=0;$i<count($list_depart);$i++){
                $query_list_depart.= " ih.depart_code='".$list_depart[$i]."' or";
            }

            if($query_list_depart != ""){
                $query_list_depart = substr($query_list_depart,0,-2);
                $query_list_depart.=" )";
            }
        }


        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join mst_movt_type on(ih.mst_movt_type_code=mst_movt_type.mst_movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join empc_approval_user iappu on(ih.empc_approval_code=iappu.empc_approval_code)
                where iappu.user_id='".$user_id."' ".$query_list_depart."
                and ih.canceled!='X' ".$query_approval_code."
                order by empc_h_created_datetime;");

        return $query->result_array();
    }
    //-------------

    function get_approval_person_cross(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_approval_user_cross i inner join user on(i.user_id=user.user_id)
                            where i.depart_code='".$this->depart_code."'
                            and empc_approval_code='".$this->empc_approval_code."';");
        return $query->result_array();
    }
    //----------

    function list_all_project(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from empc_project where active = 'Y'");
      return $query->result_array();
    }
    //--------------------

    function insert_empc_resv_matdoc_to(){
        $db = $this->load->database('default', true);

        $data = array(
          'empc_h_code'     => $this->empc_h_code,
          'sap_code'        => $this->sap_code,
          'sap_matdoc'      => $this->sap_matdoc,
          'sap_matdoc_date' => $this->sap_matdoc_date,
          'sap_matid'       => $this->sap_matid,
          'qty'             => $this->qty,
          'uom'             => $this->uom,
          'lgnum'           => $this->lgnum,
          'tbnum'           => $this->tbnum,
          'tanum'           => $this->tanum,
          'qdatu'           => $this->qdatu,
          'zeile'           => $this->zeile,
          'dmbtr'           => $this->dmbtr,
          'waers'           => $this->waers,
          'sap_matdoc_time' => $this->sap_matdoc_time,
          'bwart_mat'       => $this->bwart_mat,
          'empcps'          => $this->empcps,
          'insert_datetime' => $this->insert_datetime,
          );

          $result = $this->db->insert_string('empc_resv_matdoc_to', $data);
          $result= str_replace('INSERT INTO','INSERT IGNORE INTO',$result);
          $this->db->query($result);

          if($result) return true;
          else return false;
    }
    //------------------------

    function report_empc_tracking($from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * ,empcsv.qty as rsv_qty,dpt.depart_name as depart_name_user,empcsv.uom as rsv_uom,
                          dpt.depart_name as depart_name_user,ih.empc_h_code as empc_h_code_empc,
                          id.qty as empc_d_qty,id.uom as empc_d_uom,
                          concat(sap_matdoc_date, ' ', sap_matdoc_time) as timestampSapMatDoc
                          FROM empc_h ih inner join empc_d id on(ih.empc_h_code=id.empc_h_code)
                          left join empc_resv_matdoc_to empcsv on(ih.empc_h_code=empcsv.empc_h_code
                          and ih.sap_no=empcsv.sap_code and id.mat_id=empcsv.sap_matid
                          and id.posnr=empcsv.empcps)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join mst_movt_type on(ih.empc_type_code=mst_movt_type.movt_type_code)
                          inner join user u on(ih.empc_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                          where empc_h_created_date >= '".$from."' and empc_h_created_date <='".$to."'
                          and ih.canceled!='X'
                          order by ih.empc_h_code,sap_matdoc,timestampSapMatDoc;
                          ");
        return $query->result_array();
    }
    //-----------------------

    function report_empc_tracking_by_depart_code($from,$to,$depart_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * ,empcsv.qty as rsv_qty,dpt.depart_name as depart_name_user,empcsv.uom as rsv_uom,
                          dpt.depart_name as depart_name_user,ih.empc_h_code as empc_h_code_empc,
                          id.qty as empc_d_qty,id.uom as empc_d_uom,
                          concat(sap_matdoc_date, ' ', sap_matdoc_time) as timestampSapMatDoc
                          FROM empc_h ih inner join empc_d id on(ih.empc_h_code=id.empc_h_code)
                          left join empc_resv_matdoc_to empcsv on(ih.empc_h_code=empcsv.empc_h_code
                          and ih.sap_no=empcsv.sap_code and id.mat_id=empcsv.sap_matid
                          and id.posnr=empcsv.empcps)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join mst_movt_type on(ih.mst_movt_type_code=mst_movt_type.mst_movt_type_code)
                          inner join user u on(ih.empc_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                          where empc_h_created_date >= '".$from."' and empc_h_created_date <='".$to."'
                          and ih.depart_code = '".$depart_code."'
                          and ih.canceled!='X'
                          order by ih.empc_h_code,sap_matdoc,timestampSapMatDoc;
                          ");
        return $query->result_array();
    }
    //-----------------------

    function report_empc_tracking_by_plant_code($from,$to,$plant_code){
        if($plant_code == ""){
            $query_plant_code = " ih.plant_code like '%%' ";
        }
        else{
           $query_plant_code = " ih.plant_code rlike '".$plant_code."' ";
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * ,empcsv.qty as rsv_qty,dpt.depart_name as depart_name_user,empcsv.uom as rsv_uom,
                          dpt.depart_name as depart_name_user,ih.empc_h_code as empc_h_code_empc,
                          id.qty as empc_d_qty,id.uom as empc_d_uom,
                          concat(sap_matdoc_date, ' ', sap_matdoc_time) as timestampSapMatDoc
                          FROM empc_h ih inner join empc_d id on(ih.empc_h_code=id.empc_h_code)
                          left join empc_resv_matdoc_to empcsv on(ih.empc_h_code=empcsv.empc_h_code
                          and ih.sap_no=empcsv.sap_code and id.mat_id=empcsv.sap_matid
                          and id.posnr=empcsv.empcps)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join mst_movt_type on(ih.empc_type_code=mst_movt_type.movt_type_code)
                          inner join user u on(ih.empc_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                          where empc_h_created_date >= '".$from."' and empc_h_created_date <='".$to."'
                          and ".$query_plant_code."
                          and ih.canceled!='X'
                          order by ih.empc_h_code,sap_matdoc,timestampSapMatDoc;
                          ");
        return $query->result_array();
    }
    //-----------------------

    function update_empc_h_gl_id(){
        $db = $this->load->database('default', true);
        $query = $db->query("update empc_h set gl_id='".$this->gl_id."' where empc_h_code='".$this->empc_h_code."';");
        return true;
    }
    //----------------

    function list_email_last_person_get_notif_send_to_sap_emt001(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT email FROM empc_list_email im
                      inner join user u on(im.user_id=u.user_id) where email_type='EMT001';");
        return $query->result_array();
    }
    //--------------

    function report_empc_balance($from,$to,$depart_code,$plant_code){
      if($plant_code == ""){
          $query_plant_code = " ih.plant_code like '%%' ";
      }
      else{
         $query_plant_code = " ih.plant_code rlike '".$plant_code."' ";
      }

      $db = $this->load->database('default', true);
      $query = $db->query("select *,empc.empc_h_code as empc_code,(empc.qty-(if(resv.gi_qty is null or resv.gi_qty='',0,resv.gi_qty))) as balance
                          from
                          (SELECT ih.empc_h_code,ih.empc_h_created_date,ih.empc_h_created_datetime,
                          empc_status,name,movt_type_name,ih.plant_code,plant_name,
                          id.mat_id,id.qty,id.uom,id.posnr,ih.sap_no,ih.depart_code,dpt.depart_name
                          FROM empc_h ih inner join empc_d id on(ih.empc_h_code=id.empc_h_code)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join mst_movt_type on(ih.empc_type_code=mst_movt_type.movt_type_code)
                          inner join user u on(ih.empc_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                          where empc_h_created_date >= '".$from."' and empc_h_created_date <='".$to."'
                          and ih.depart_code like '%".$depart_code."%'
                          and ".$query_plant_code."
                          and ih.canceled!='X') as empc
                          left join
                          (select  empc_h_code,sap_code,sap_matid,gi_uom,empcps,sum(resv_group1.gi_qty) as gi_qty from(
                          SELECT empc_h_code,sap_code,sap_matid,empcps,uom as gi_uom,bwart_mat,
                          if(bwart_mat='201' or bwart_mat='',qty,qty*-1) as gi_qty
                          FROM empc_resv_matdoc_to i
                          group by empc_h_code,sap_code,sap_matdoc,sap_matid,uom,bwart_mat,empcps ) as resv_group1
                          group by empc_h_code,sap_code,sap_matid,gi_uom,empcps) as resv
                          on(empc.empc_h_code=resv.empc_h_code and empc.sap_no=resv.sap_code
                          and empc.mat_id=resv.sap_matid and empc.posnr=resv.empcps)
                          order by empc.empc_h_created_datetime
                        ");
      return $query->result_array();
    }
    //--------------------------

    function list_empc_show_all_without_cancel($from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM empc_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join mst_movt_type on(ih.mst_movt_type_code=mst_movt_type.mst_movt_type_code)
                inner join user u on(ih.empc_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join empc_status on(empc_status.empc_status_code=ih.empc_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                where empc_h_created_date >='".$from."' and empc_h_created_date <='".$to."'
                order by empc_h_created_datetime;");
        return $query->result_array();
    }
    //-----------------------

    function list_email_last_person_get_notif_send_to_sap_emt002(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT email FROM empc_list_email im
                      inner join user u on(im.user_id=u.user_id)
                      where email_type='EMT002' and u.plant_code='".$this->plant_code."';");
        return $query->result_array();
    }
    //--------------

    function call_store_procedure_new_empc(){
        $db = $this->load->database('default', true);
        $query = $db->query("call newempc('".$this->prefix_code."',".$this->empc_h_created_user .",'".$this->empc_status."',
                      '".$this->empc_h_text1."','','','".$this->empc_type_code."','".$this->depart_code."',
                      '".$this->empc_approval_code."','".$this->email_user."','".$this->gl_id."',
                      '".$this->costcenter_code."','".$this->plant_code."','".$this->customer."','".$this->project."',
                      '".$this->attachment."');")->row();
        return $query->trsc_no;
    }
    //------------------

    function list_empc_complete_by_date_user($user,$date){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from (
                      SELECT h.empc_h_code,count(d.empc_h_code) as count_detail,h.attachment,
                      empc_h_created_datetime,empc_h_created_date,email_user,empc_status,customer_text
                      FROM empc_h h
                      left join empc_d d on(h.empc_h_code = d.empc_h_code)
                      where empc_h_created_date='".$date."' and empc_h_created_user='".$user."'
                      group by h.empc_h_code) as empc_h
                      inner join empc_status empcs on(empc_h.empc_status=empcs.empc_status_code)
                      order by empc_h.empc_h_created_datetime desc;");
        return $query->result_array();
    }
    //-----------------------

    function count_detail_by_empc_code($empc_h_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT count(d.empc_h_code) as count_detail FROM empc_h h
                      left join empc_d d on(h.empc_h_code = d.empc_h_code)
                      where h.empc_h_code='".$empc_h_code."';")->row();
        return $query->count_detail;
    }
    //-------------

    function list_empc_selected_hasnot_canceled($empc_h_list){
        $db = $this->load->database('default', true);

        $query_list = "";

        foreach($empc_h_list as $row){
            $query_list.=" empc_h_code='".$row."' or";
        }

        $query_list = substr($query_list,0,-2);

        $query = $db->query("SELECT * FROM empc_h i where ( $query_list ) and canceled='';");
        return $query->result_array();
    }
    //--------------

    function list_email_notif_empc_rejected_by_system_emt003(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT email FROM empc_list_email im
                      inner join user u on(im.user_id=u.user_id) where email_type='EMT003';");
        return $query->result_array();
    }
    //--------------

    function list_email_empcapp($empc_approval_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT email FROM empc_approval_user appu inner join user u on(appu.user_id=u.user_id)
                            where empc_approval_code='".$empc_approval_code."';");
        return $query->result_array();
    }
    //-------------

    function get_last_approval_status_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM setting i where setting_name='empc_last_approval_code';")->row();
      return $query->value1;
    }
    //-------------

    function get_approval_code_done_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM setting i where setting_name='empc_approval_code_done';")->row();
      return $query->value1;
    }
    //-------------

    function list_empc_h_approval_with_approval_list($empc_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM empc_h_approval i
                      inner join empc_approval ip on(i.empc_approval_code=ip.empc_approval_code)
                      inner join user u on(u.user_id=i.user_id)
                      where i.empc_h_code='".$empc_code."'

                      union

                      SELECT '',empc_approval_code,'','','','','','','','','',empc_approval_name,'','','','','','','','','','',''
                      FROM empc_approval i
                      where empc_approval_code not in (SELECT empc_approval_code FROM empc_h_approval i
                      where i.empc_h_code='".$empc_code."')
                      and empc_approval_code!='EMPCAPP000'");

        return $query->result_array();
    }
    //--------------

}

?>
