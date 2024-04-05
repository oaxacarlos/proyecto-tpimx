<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class Navigation extends CI_Controller
{

    function __constructor() {
      parent::__constructor();


   }
/*
  function get_menu1(){
    $this->load->model('model_navigation','',TRUE);
    $output_string = "";

    $session_data = $this->session->userdata('z_tpimx_logged_in');
    $userid = $session_data['z_tpimx_user_id'];

    $result = $this->model_navigation->list_menu1_by_user($userid);
    foreach($result as $row){
        $output_string.="<li class='nav-item'>";
          $output_string.="<a class='nav-link' onclick=openMenu('".$row['menu1_initial']."') href='".$row['menu1_link']."'>".$row['menu1_name']."</a>";
        $output_string.="</li>";
    }

     echo $output_string;
  }

    function get_menu2(){
        $this->load->model('model_navigation','',TRUE);
        $output_string = "";

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $userid = $session_data['z_tpimx_user_id'];

        $result1 = $this->model_navigation->list_menu1_by_user($userid);
        foreach($result1 as $row1){
            $output_string.= "<div id='".$row1['menu1_initial']."' class='menubar container-fluid menuShow bg-light border-bottom' style='display:none;'>";
              $output_string.= "<span onclick=this.parentElement.style.display='none' style='font-size:30px; cursor:pointer;'>&times;</span>";
              $output_string.= "<h2 style='padding-top:20px;'>".$row1['menu1_name']."</h2>";
              $output_string.= "<div class='row' style='padding-top:10px;'></div>";
              $output_string.= "<div class='row'>";
                  $result2 = $this->model_navigation->list_menu2_by_user($userid,$row1['menu1_code']);
                  foreach($result2 as $row2){
                      $output_string.= "<div class='col-md-3'>";
                      $output_string.= "<div class='border-bottom'><h5>".$row2['menu2_name']."</h5></div>";
                      $result3 = $this->model_navigation->list_menu3_by_user($userid,$row2['menu2_code']);
                      foreach($result3 as $row3){
                          $output_string.= "<div class='menuDetail'><a href='".$row3['menu3_initial']."'>".$row3['menu3_name']."</a></div>";
                      }
                      $output_string.= "</div>";
                      $output_string.= "<div class='col-sm-1'></div>";
                  }

              $output_string.= "</div>";
              $output_string.= "<div style='padding-top:30px;'></div>";
            $output_string.= "</div>";
        }

        echo $output_string;
    }
*/

    function notif(){

        $this->load->model('loyalty/model_verified','loyalty_model_verified');
        $this->load->model('loyalty/model_redeem','loyalty_model_redeem');

        // notif loyalty verification
        $response["link_notif_loyalty_verification"] = "notif_loyalty_verification";
        $response["notif_count_loyalty_verification"] = (int)$this->loyalty_model_verified->get_total_count_not_verified();

        // notif customer redeem
        $response["link_notif_customer_redeem"] = "notif_customer_redeem";
        $response["notif_count_customer_redeem"] = (int)$this->loyalty_model_redeem->get_total_count_not_redeem();

        echo json_encode($response);
    }
    //---

    // notif 2023-05-19
    function notif_global(){
        $this->load->model('model_tsc_notif','',TRUE);

        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];

        $result = $this->model_tsc_notif->get_notif_with_read_status(0,0,$user);
        $response["total_notif"] = count($result);

        echo json_encode($response);
    }
    //---

    // notif 2023-05-19
    function update_notif_global_as_read(){
        $this->load->model('model_tsc_notif','',TRUE);
        $session_data = $this->session->userdata('z_tpimx_logged_in');
        $user = $session_data['z_tpimx_user_id'];

        $result = $this->model_tsc_notif->get_notif_with_read_status(0,0,$user);

        if(count($result) == 0){
            $response["total_notif"] = "0";
            $result = $this->model_tsc_notif->get_notif_with_limit(10, $user);
            foreach($result as $row){
                $response["detail"][] = array(
                    "message" => $row["message"],
                    "read"    => $row["readd"],
                    "link"    => $row["link"],
                );
            }
        }
        else{
            $response["total_notif"] = count($result);
            foreach($result as $row){
                $response["detail"][] = array(
                    "message" => $row["message"],
                    "read"    => $row["readd"],
                    "link"    => $row["link"],
                );
            }
        }

        $result = $this->model_tsc_notif->update_all_notif_as_read($user);
        $response["status"] = "1";
        echo json_encode($response);
    }
    //---
}

?>
