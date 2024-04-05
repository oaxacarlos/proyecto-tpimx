<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_itr extends CI_Model{

    var $itr_h_code,$itr_h_created_date,$itr_h_created_datetime,$counter,$itr_h_created_user;
    var $itr_h_doc_date,$itr_status,$canceled,$itr_h_text1,$itr_type_code,$depart_code;
    var $email_user,$itr_approval_code,$itr_h_text2,$itr_h_text3,$gl_id,$costcenter_code;
    var $mat_id,$qty,$uom,$posnr,$plant_code,$approval_date,$approval_datetime,$itr_h_approval_text1;
    var $itr_d_text1,$customer,$project,$prefix_code,$attachment;

    var $sap_code,$sap_matdoc,$sap_matid,$tbnum,$lgnum,$tanum,$kquit,$qdalu,$zeile;
    var $bwart_mat,$sap_matdoc_date,$dmbtr,$waers,$sap_matdoc_time,$itrps,$insert_datetime;

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

    function list_all_itr_type(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from itr_type where active = 'Y'");
      return $query->result_array();
    }
    //--------------------

    function list_all_uom(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from mst_uom m order by m.order");
      return $query->result_array();
    }
    //--------------------

    function insert_itr_h(){
        $db = $this->load->database('default', true);
        $data = array(
          'itr_h_code' => $this->itr_h_code ,
          'itr_h_created_date' => $this->itr_h_created_date ,
          'itr_h_created_datetime' => $this->itr_h_created_datetime,
          'counter' => $this->counter,
          'itr_h_created_user' => $this->itr_h_created_user,
          'itr_h_doc_date' => $this->itr_h_doc_date,
          'itr_status' => $this->itr_status,
          'itr_h_text1' => $this->itr_h_text1,
          'itr_h_text2' => $this->itr_h_text2,
          'itr_h_text3' => $this->itr_h_text3,
          'itr_type_code' => $this->itr_type_code,
          'depart_code' => $this->depart_code,
          'itr_approval_code' => $this->itr_approval_code,
          'email_user' => $this->email_user,
          'costcenter_code' => $this->costcenter_code,
          'gl_id' => $this->gl_id,
          'plant_code' => $this->plant_code,
          'canceled' => '',
          'customer_text' => $this->customer,
          'itr_project_code' => $this->project
          );

          $result = $this->db->insert('itr_h', $data);

          if($result) return true;
          else return false;
    }
    //-----------------------

    function insert_itr_d(){
        $db = $this->load->database('default', true);
        $data = array(
          'itr_h_code'  => $this->itr_h_code,
          'mat_id'      => $this->mat_id,
          'qty'         => $this->qty,
          'uom'         => $this->uom,
          'posnr'       => $this->posnr,
          'itr_d_text1' => $this->itr_d_text1,
          );

          $result = $this->db->insert('itr_d', $data);

          if($result) return true;
          else return false;
    }
    //-----------------

    function insert_itr_d_version2($table_itr_d){
        $db = $this->load->database('default', true);
        $data = array();
        foreach($table_itr_d as $row){
              $data[] = array(
                  'itr_h_code'  => $this->itr_h_code,
                  'mat_id'      => $row['matid'],
                  'qty'         => $row['qty'],
                  'uom'         => $row['uom'],
                  'posnr'       => $row['posnr'],
                  'itr_d_text1' => $row['itr_d_text1'],
              );
        }
        $result = $this->db->insert_batch('itr_d', $data);

        if($result) return true; else return false;
    }
    //-----------------

    function create_new_itr_code($prefix_value,$digits_after_prefix,$last_code,$datecode){
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

    function get_last_code_itr_h($datecode,$code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT if(itr_h_code is NULL,0,itr_h_code) as itr_h_code FROM itr_h i
                      where itr_h_code like '".$code.$datecode."%' order by itr_h_code desc limit 1;");
        return $query->result();
    }
    //----------------

    function list_approval($user_id,$depart_code,$itr_approval_code_user){

        if(count($itr_approval_code_user)==0){
            $query_approval_code = "";
        }
        else{
            $query_approval_code = "and ( ";
            for($i=0;$i<count($itr_approval_code_user);$i++){
                $query_approval_code.= "ih.itr_approval_code='".$itr_approval_code_user[$i]."' or";
            }

            if($query_approval_code!=""){
                $query_approval_code = substr($query_approval_code,0,-2);
                $query_approval_code.=" )";
            }
        }


        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join itr_approval_user iappu on(ih.itr_approval_code=iappu.itr_approval_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                where iappu.user_id='".$user_id."' and ih.depart_code='".$depart_code."'
                and ih.canceled!='X' ".$query_approval_code." order by itr_h_created_datetime;");

        return $query->result_array();
    }
    //-------------

    function get_approval_level1(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT itr_approval_code FROM itr_approval i where itr_approval_order is not null
                            order by itr_approval_order limit 1;");
        return $query->result();
    }
    //---------

    function get_approval_person(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM itr_approval_user i inner join user on(i.user_id=user.user_id)
                            where depart_code='".$this->depart_code."'
                            and itr_approval_code='".$this->itr_approval_code."';");
        return $query->result_array();
    }
    //----------

    function list_approval_by_itr_code($itr_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                where ih.itr_h_code='".$itr_code."'
                order by itr_h_created_datetime;");
                  //and ih.canceled!='X'
        return $query->result_array();
    }
    //-------------

    function list_itr_d_by_code($itr_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,i.uom as d_uom FROM itr_d i inner join mst_material mm on(i.mat_id=mm.mat_id)
                            where i.itr_h_code='".$itr_code."';");
        return $query->result_array();
    }

    function update_status_itr_to_canceled($user_id,$date,$datetime,$itr_code,$remarks){
        $db = $this->load->database('default', true);
        $query = $db->query("update itr_h set itr_status='ITRST004',canceled='X', itr_h_text2='".$remarks."',
                      itr_canceled_user='".$user_id."',canceled_date='".$date."',canceled_datetime='".$datetime."'
                      where itr_h_code='".$itr_code."';");
        return true;
    }
    //-------------------

    function get_approval_level_user($user_id){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT itr_approval_code
                      FROM itr_approval_user i where user_id='".$user_id."'");
        return $query->result_array();
    }
    //------

    function get_approval_one_level_up($approval_code){
        $db = $this->load->database('default', true);
        $query = $db->query("select b.itr_approval_code as code from(
                      SELECT * FROM itr_approval i where itr_approval_code='".$approval_code."') as a,
                      (SELECT * FROM itr_approval i) as b
                      where b.itr_approval_order > a.itr_approval_order
                      order by b.itr_approval_order limit 1;");
        if($query->num_rows() == 0) return 0;

        $query = $db->query("select b.itr_approval_code as code from(
                             SELECT * FROM itr_approval i where itr_approval_code='".$approval_code."') as a,
                            (SELECT * FROM itr_approval i) as b
                            where b.itr_approval_order > a.itr_approval_order
                            order by b.itr_approval_order limit 1;")->row();
        return $query->code;
    }
    //---------------

    function update_status_itr_to_approval($itr_code,$approval_code,$itr_status){

		$query1 = "update itr_h set itr_status='".$itr_status."', itr_approval_code='".$approval_code."'
                      where itr_h_code='".$itr_code."';";

        $db = $this->load->database('default', true);
        $query = $db->query("update itr_h set itr_status='".$itr_status."', itr_approval_code='".$approval_code."'
                      where itr_h_code='".$itr_code."';");
        return true;
    }
    //-------------------

    function get_status_from_approval_code($approval_code){

		$query1 = "SELECT itr_status_code FROM itr_status_n_approval i
                      where itr_approval_code='".$approval_code."';";

	/*	$filename = "hasil.txt";
		$myfile = fopen($filename, "a");
		fwrite($myfile,$query1."\r\n");
		fclose($myfile);*/

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT itr_status_code FROM itr_status_n_approval i
                      where itr_approval_code='".$approval_code."';")->row();
        return $query->itr_status_code;
    }
    //---------------

    function get_last_approval_status_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM itr_setting i where itr_setting_name='last_approval_status';")->row();
      return $query->value1;
    }
    //-------------

    function get_last_approval_userid_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM itr_setting i where itr_setting_name='last_approval_user_id';")->row();
      return $query->value1;
    }
    //-------------

    function get_approval_code_done_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM itr_setting i where itr_setting_name='approval_code_done';")->row();
      return $query->value1;
    }
    //-------------

    function get_email_from_userid($user_id){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT email FROM user u where user_id='".$user_id."';")->row();
      return $query->email;
    }
    //-------------

    function insert_itr_h_approval($user_id){
        $db = $this->load->database('default', true);

        $data = array(
          'itr_h_code'              => $this->itr_h_code,
          'itr_approval_code'       => $this->itr_approval_code,
          'approval_date'           => $this->approval_date,
          'approval_datetime'       => $this->approval_datetime,
          'itr_h_approval_text1'    => $this->itr_h_approval_text1,
          'email_approval'          => $this->email_user,
          'user_id'                 => $user_id,
          );

          $result = $this->db->insert('itr_h_approval', $data);

          if($result) return true;
          else return false;
    }
    //----------------

    function list_approval_sap($user_id,$itr_approval_code_user){

        $query_approval_code = "";
        for($i=0;$i<count($itr_approval_code_user);$i++){
            $query_approval_code.= "ih.itr_approval_code='".$itr_approval_code_user[$i]."' or";
        }

        if($query_approval_code!=""){
            $query_approval_code = substr($query_approval_code,0,-2);
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join itr_approval_user iappu on(ih.itr_approval_code=iappu.itr_approval_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                where ih.canceled!='X' and (".$query_approval_code.")
                order by itr_h_created_datetime;");

        return $query->result_array();
    }
    //-------------

    function list_email_participant($itr_code){

		$query1 = "SELECT itr_h_code,email_user as email FROM itr_h h where itr_h_code='".$itr_code."'
                      union
                      select itr_h_code,email_approval as email from itr_h_approval i where itr_h_code='".$itr_code."';";

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT itr_h_code,email_user as email FROM itr_h h where itr_h_code='".$itr_code."'
                      union
                      select itr_h_code,email_approval as email from itr_h_approval i where itr_h_code='".$itr_code."';");
        return $query->result_array();
    }
    //--------------

    function list_itr_h_approval($itr_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM itr_h_approval i
                      inner join itr_approval ip on(i.itr_approval_code=ip.itr_approval_code)
                      inner join user u on(u.user_id=i.user_id)
                      where i.itr_h_code='".$itr_code."' order by i.approval_datetime;");
        return $query->result_array();
    }
    //--------------

    function update_sap_no_itr_h($itr_code,$sap_no){
        $db = $this->load->database('default', true);
        $query = $db->query("update itr_h set sap_no='".$sap_no."' where itr_h_code='".$itr_code."';");
        return true;
    }
    //-------------------

    function list_itr_h_approval_with_approval_list($itr_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM itr_h_approval i
                      inner join itr_approval ip on(i.itr_approval_code=ip.itr_approval_code)
                      inner join user u on(u.user_id=i.user_id)
                      where i.itr_h_code='".$itr_code."'

                      union

                      SELECT '',itr_approval_code,'','','','','','','','','',itr_approval_name,'','','','','','','','','','',''
                      FROM itr_approval i
                      where itr_approval_code not in (SELECT itr_approval_code FROM itr_h_approval i
                      where i.itr_h_code='".$itr_code."')
                      and itr_approval_code!='ITRAP000'");
        return $query->result_array();
    }
    //--------------

    function report_itr_h_by_user($user_id,$from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                where ih.itr_h_created_user='".$user_id."' and
                itr_h_created_date >='".$from."' and itr_h_created_date <='".$to."'
                order by itr_h_created_datetime;");
        return $query->result_array();
    }

    //----------------------------------

    function report_itr_h_by_department($depart_code,$from,$to,$plant_code){
        if($plant_code == ""){
            $query_plant_code = " ih.plant_code like '%%' ";
        }
        else{
           $query_plant_code = " ih.plant_code rlike '".$plant_code."' ";
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                where ih.depart_code like '%".$depart_code."%'
                and itr_h_created_date >='".$from."' and itr_h_created_date <='".$to."'
                and ".$query_plant_code."
                order by itr_h_created_datetime;");
        return $query->result_array();
    }

    //-------------------

    function report_itr_show_all($from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                where itr_h_created_date >='".$from."' and itr_h_created_date <='".$to."'
                order by itr_h_created_datetime;");
        return $query->result_array();
    }
    //-----------------------

    function get_approval_order_below_from_approval_code_user($user_id){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM itr_approval i
                  where itr_approval_order < (SELECT itr_approval_order FROM itr_approval_user i
                  inner join itr_approval itp on(i.itr_approval_code = itp.itr_approval_code)
                  where i.user_id='".$user_id."');");
        return $query->result_array();
    }
    //------------------------

    function update_itr_h_attachment($attachment){
        $db = $this->load->database('default', true);
        $query = $db->query("update itr_h set attachment='".$attachment."' where itr_h_code='".$this->itr_h_code."';");
        return true;
    }
    //----------------

    function list_department_cross_approval($user_id,$depart_code,$itr_approval_code_user){
        if(count($itr_approval_code_user)==0){
            $query_approval_code = "";
        }
        else{
            $query_approval_code = "and ( ";
            for($i=0;$i<count($itr_approval_code_user);$i++){
                $query_approval_code.= "i.itr_approval_code='".$itr_approval_code_user[$i]."' or";
            }

            if($query_approval_code!=""){
                $query_approval_code = substr($query_approval_code,0,-2);
                $query_approval_code.=" )";
            }
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT depart_code FROM itr_approval_user_cross i
                  inner join itr_approval_user u on(i.user_id=u.user_id)
                  where i.user_id='".$user_id."' and i.depart_code != '".$depart_code."'
                  ".$query_approval_code.";");

        return $query->result_array();
    }
    //-----------------------

    function list_approval_cross_department($user_id,$list_depart,$itr_approval_code_user){

        // query list approval
        $query_approval_code = "";
        if(count($itr_approval_code_user)==0){
            $query_approval_code = "";
        }
        else{
            $query_approval_code = "and ( ";
            for($i=0;$i<count($itr_approval_code_user);$i++){
                $query_approval_code.= "ih.itr_approval_code='".$itr_approval_code_user[$i]."' or";
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
        $query = $db->query("SELECT * FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join itr_approval_user iappu on(ih.itr_approval_code=iappu.itr_approval_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                where iappu.user_id='".$user_id."' ".$query_list_depart."
                and ih.canceled!='X' ".$query_approval_code."
                order by itr_h_created_datetime;");

        return $query->result_array();
    }
    //-------------

    function get_approval_person_cross(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * FROM itr_approval_user_cross i inner join user on(i.user_id=user.user_id)
                            where i.depart_code='".$this->depart_code."'
                            and itr_approval_code='".$this->itr_approval_code."';");
        return $query->result_array();
    }
    //----------

    function list_all_project(){
      $db = $this->load->database('default', true);
      $query = $db->query("select * from itr_project where active = 'Y'");
      return $query->result_array();
    }
    //--------------------

    function insert_itr_resv_matdoc_to(){
        $db = $this->load->database('default', true);

        $data = array(
          'itr_h_code'    => $this->itr_h_code,
          'sap_code'      => $this->sap_code,
          'sap_matdoc'    => $this->sap_matdoc,
          'sap_matdoc_date' => $this->sap_matdoc_date,
          'sap_matid'     => $this->sap_matid,
          'qty'           => $this->qty,
          'uom'           => $this->uom,
          'lgnum'         => $this->lgnum,
          'tbnum'         => $this->tbnum,
          'tanum'         => $this->tanum,
          'qdatu'         => $this->qdatu,
          'zeile'         => $this->zeile,
          'dmbtr'         => $this->dmbtr,
          'waers'         => $this->waers,
          'sap_matdoc_time' => $this->sap_matdoc_time,
          'bwart_mat'     => $this->bwart_mat,
          'itrps'         => $this->itrps,
          'insert_datetime'  => $this->insert_datetime,
          );

          $result = $this->db->insert_string('itr_resv_matdoc_to', $data);
          $result= str_replace('INSERT INTO','INSERT IGNORE INTO',$result);
          $this->db->query($result);

          if($result) return true;
          else return false;
    }
    //------------------------

    function report_itr_tracking($from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * ,itrsv.qty as rsv_qty,dpt.depart_name as depart_name_user,itrsv.uom as rsv_uom,
                          dpt.depart_name as depart_name_user,ih.itr_h_code as itr_h_code_itr,
                          id.qty as itr_d_qty,id.uom as itr_d_uom,
                          concat(sap_matdoc_date, ' ', sap_matdoc_time) as timestampSapMatDoc,customer_text
                          FROM itr_h ih inner join itr_d id on(ih.itr_h_code=id.itr_h_code)
                          left join itr_resv_matdoc_to itrsv on(ih.itr_h_code=itrsv.itr_h_code
                          and ih.sap_no=itrsv.sap_code and id.mat_id=itrsv.sap_matid
                          and id.posnr=itrsv.itrps)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                          inner join user u on(ih.itr_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                          inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                          where itr_h_created_date >= '".$from."' and itr_h_created_date <='".$to."'
                          and ih.canceled!='X'
                          order by ih.itr_h_code,sap_matdoc,timestampSapMatDoc;
                          ");
        return $query->result_array();
    }
    //-----------------------

    function report_itr_tracking_by_depart_code($from,$to,$depart_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * ,itrsv.qty as rsv_qty,dpt.depart_name as depart_name_user,itrsv.uom as rsv_uom,
                          dpt.depart_name as depart_name_user,ih.itr_h_code as itr_h_code_itr,
                          id.qty as itr_d_qty,id.uom as itr_d_uom,
                          concat(sap_matdoc_date, ' ', sap_matdoc_time) as timestampSapMatDoc
                          FROM itr_h ih inner join itr_d id on(ih.itr_h_code=id.itr_h_code)
                          left join itr_resv_matdoc_to itrsv on(ih.itr_h_code=itrsv.itr_h_code
                          and ih.sap_no=itrsv.sap_code and id.mat_id=itrsv.sap_matid
                          and id.posnr=itrsv.itrps)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                          inner join user u on(ih.itr_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                          inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                          where itr_h_created_date >= '".$from."' and itr_h_created_date <='".$to."'
                          and ih.depart_code = '".$depart_code."'
                          and ih.canceled!='X'
                          order by ih.itr_h_code,sap_matdoc,timestampSapMatDoc;
                          ");
        return $query->result_array();
    }
    //-----------------------

    function report_itr_tracking_by_plant_code($from,$to,$plant_code){
        if($plant_code == ""){
            $query_plant_code = " ih.plant_code like '%%' ";
        }
        else{
           $query_plant_code = " ih.plant_code rlike '".$plant_code."' ";
        }

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT * ,itrsv.qty as rsv_qty,dpt.depart_name as depart_name_user,itrsv.uom as rsv_uom,
                          dpt.depart_name as depart_name_user,ih.itr_h_code as itr_h_code_itr,
                          id.qty as itr_d_qty,id.uom as itr_d_uom,
                          concat(sap_matdoc_date, ' ', sap_matdoc_time) as timestampSapMatDoc
                          FROM itr_h ih inner join itr_d id on(ih.itr_h_code=id.itr_h_code)
                          left join itr_resv_matdoc_to itrsv on(ih.itr_h_code=itrsv.itr_h_code
                          and ih.sap_no=itrsv.sap_code and id.mat_id=itrsv.sap_matid
                          and id.posnr=itrsv.itrps)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                          inner join user u on(ih.itr_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                          inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                          where itr_h_created_date >= '".$from."' and itr_h_created_date <='".$to."'
                          and ".$query_plant_code."
                          and ih.canceled!='X'
                          order by ih.itr_h_code,sap_matdoc,timestampSapMatDoc;
                          ");
        return $query->result_array();
    }
    //-----------------------

    function update_itr_h_gl_id(){
        $db = $this->load->database('default', true);
        $query = $db->query("update itr_h set gl_id='".$this->gl_id."' where itr_h_code='".$this->itr_h_code."';");
        return true;
    }
    //----------------

    function list_email_last_person_get_notif_send_to_sap_emt001(){

		$query1 = "SELECT email FROM itr_list_email im
                      inner join user u on(im.user_id=u.user_id) where email_type='EMT001';";

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT email FROM itr_list_email im
                      inner join user u on(im.user_id=u.user_id) where email_type='EMT001';");
        return $query->result_array();
    }
    //--------------

    function get_user_can_see_all_depart_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM itr_setting i
                    where itr_setting_name='user_can_see_all_depart';")->row();
      return $query->value1;
    }
    //-------------

    function get_user_can_see_only_his_depart_from_setting(){
      $db = $this->load->database('default', true);
      $query = $db->query("SELECT value1 FROM itr_setting i
                    where itr_setting_name='user_can_see_only_his_depart';")->row();
      return $query->value1;
    }
    //-------------

    function report_itr_balance($from,$to,$depart_code,$plant_code){
      if($plant_code == ""){
          $query_plant_code = " ih.plant_code like '%%' ";
      }
      else{
         $query_plant_code = " ih.plant_code rlike '".$plant_code."' ";
      }

      $db = $this->load->database('default', true);
      $query = $db->query("select *,itr.itr_h_code as itr_code,(itr.qty-(if(resv.gi_qty is null or resv.gi_qty='',0,resv.gi_qty))) as balance
                          from
                          (SELECT ih.itr_h_code,ih.itr_h_created_date,ih.itr_h_created_datetime,
                          itr_status,name,itr_type_name,itr_project_name,ih.plant_code,plant_name,
                          id.mat_id,id.qty,id.uom,id.posnr,ih.sap_no,ih.depart_code,dpt.depart_name
                          FROM itr_h ih inner join itr_d id on(ih.itr_h_code=id.itr_h_code)
                          inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                          inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                          inner join user u on(ih.itr_h_created_user=u.user_id)
                          inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                          inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                          inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                          inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                          inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                          inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                          where itr_h_created_date >= '".$from."' and itr_h_created_date <='".$to."'
                          and ih.depart_code like '%".$depart_code."%'
                          and ".$query_plant_code."
                          and ih.canceled!='X') as itr
                          left join
                          (select  itr_h_code,sap_code,sap_matid,gi_uom,itrps,sum(resv_group1.gi_qty) as gi_qty from(
                          SELECT itr_h_code,sap_code,sap_matid,itrps,uom as gi_uom,bwart_mat,
                          if(bwart_mat='201' or bwart_mat='',qty,qty*-1) as gi_qty
                          FROM itr_resv_matdoc_to i
                          group by itr_h_code,sap_code,sap_matdoc,sap_matid,uom,bwart_mat,itrps ) as resv_group1
                          group by itr_h_code,sap_code,sap_matid,gi_uom,itrps) as resv
                          on(itr.itr_h_code=resv.itr_h_code and itr.sap_no=resv.sap_code
                          and itr.mat_id=resv.sap_matid and itr.posnr=resv.itrps)
                          order by itr.itr_h_created_datetime
                        ");
      return $query->result_array();
    }
    //--------------------------

    function list_itr_show_all_without_cancel($from,$to){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT *,dpt.depart_name as requestor_depart_name,
                dpt1.depart_name as gl_depart_name
                FROM itr_h ih
                inner join mst_department dpt on(ih.depart_code=dpt.depart_code)
                inner join itr_type on(ih.itr_type_code=itr_type.itr_type_code)
                inner join user u on(ih.itr_h_created_user=u.user_id)
                inner join mst_plant plt on(ih.plant_code=plt.plant_code)
                inner join itr_status on(itr_status.itr_status_code=ih.itr_status)
                inner join mst_gl on(mst_gl.gl_id=ih.gl_id)
                inner join mst_costcenter on(mst_costcenter.costcenter_code=ih.costcenter_code)
                inner join mst_department dpt1 on(mst_gl.depart_code=dpt1.depart_code)
                inner join itr_project ipr on(ih.itr_project_code=ipr.itr_project_code)
                where itr_h_created_date >='".$from."' and itr_h_created_date <='".$to."'
                order by itr_h_created_datetime;");
        return $query->result_array();
    }
    //-----------------------

    function list_email_last_person_get_notif_send_to_sap_emt002(){

		$query1 = "SELECT email FROM itr_list_email im
                      inner join user u on(im.user_id=u.user_id)
                      where email_type='EMT002' and u.plant_code='".$this->plant_code."';";

        $db = $this->load->database('default', true);
        $query = $db->query("SELECT email FROM itr_list_email im
                      inner join user u on(im.user_id=u.user_id)
                      where email_type='EMT002' and u.plant_code='".$this->plant_code."';");
        return $query->result_array();
    }
    //--------------

    function call_store_procedure_new_itr(){
        $db = $this->load->database('default', true);
        $query = $db->query("call newITR('".$this->prefix_code."',".$this->itr_h_created_user .",'".$this->itr_status."',
                      '".$this->itr_h_text1."','','','".$this->itr_type_code."','".$this->depart_code."',
                      '".$this->itr_approval_code."','".$this->email_user."','".$this->gl_id."',
                      '".$this->costcenter_code."','".$this->plant_code."','".$this->customer."','".$this->project."',
                      '".$this->attachment."');")->row();

        return $query->trsc_no;
    }
    //------------------

    function list_itr_complete_by_date_user($user,$date){
        $db = $this->load->database('default', true);
        $query = $db->query("select * from (
                      SELECT h.itr_h_code,count(d.itr_h_code) as count_detail,h.attachment,
                      itr_h_created_datetime,itr_h_created_date,email_user,itr_status,customer_text
                      FROM itr_h h
                      left join itr_d d on(h.itr_h_code = d.itr_h_code)
                      where itr_h_created_date='".$date."' and itr_h_created_user='".$user."'
                      group by h.itr_h_code) as itr_h
                      inner join itr_status itrs on(itr_h.itr_status=itrs.itr_status_code)
                      order by itr_h.itr_h_created_datetime desc;");
        return $query->result_array();
    }
    //-----------------------

    function count_detail_by_itr_code($itr_h_code){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT count(d.itr_h_code) as count_detail FROM itr_h h
                      left join itr_d d on(h.itr_h_code = d.itr_h_code)
                      where h.itr_h_code='".$itr_h_code."';")->row();
        return $query->count_detail;
    }
    //-------------

    function list_itr_selected_hasnot_canceled($itr_h_list){
        $db = $this->load->database('default', true);

        $query_list = "";

        foreach($itr_h_list as $row){
            $query_list.=" itr_h_code='".$row."' or";
        }

        $query_list = substr($query_list,0,-2);

        $query = $db->query("SELECT * FROM itr_h i where ( $query_list ) and canceled='';");
        return $query->result_array();
    }
    //--------------

    function list_email_notif_itr_rejected_by_system_emt003(){
        $db = $this->load->database('default', true);
        $query = $db->query("SELECT email FROM itr_list_email im
                      inner join user u on(im.user_id=u.user_id) where email_type='EMT003';");
        return $query->result_array();
    }
    //--------------

	function list_cust_sap(){
        $db = $this->load->database('sfa_live', true);
        $query = $db->query("select custno,custname,distrik,distrikname from
                      (SELECT kodecabang,custno,custname FROM fcustmst where custno like '1%' and
                      kodecabang like 'NGR%' and kindus in('10','20','30') and typeout in('01','05','06','33','34')
                      and custno not in('1','11')) as tbl_cust
                      left join fdistrik on(tbl_cust.kodecabang=fdistrik.distrik);");
        return $query->result_array();
    }
    //--------------

	function delete_material($active){
		$db = $this->load->database('default', true);
        $query = $db->query( "delete from mst_material where active='".$active."'");
        return true;
	}
	//---

	function insert_material_to_db($mat_id,$mat_desc,$mat_type,$uom,$active){
        $db = $this->load->database('default', true);

        $data = array(
          'mat_id'   	=> $mat_id,
          'mat_desc'    => $mat_desc,
          'mat_type'    => $mat_type,
          'uom' 		=> $uom,
          'active'     	=> $active,
          );

          //$result = $this->db->insert('mst_material', $data);
		 $result = $this->db->insert_string('mst_material', $data);
		 $result= str_replace('INSERT INTO','INSERT IGNORE INTO',$result);
         $this->db->query($result);

          if($result) return true;
          else return false;
    }
    //------------------------

}

?>
