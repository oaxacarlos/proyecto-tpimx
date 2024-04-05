<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Socomment extends CI_Controller{

  function __construct(){
    parent::__construct();
    $this->load->model('internal/socomment/model_so_comment','model_so_comment');
  }
  //--

  function index(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('sales_folder').'internal/socomment'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sales/internal/socomment/v_index');
      }
  }
  //---

  function get_so(){
      $track_no = $_POST['inp_track_no'];

      $result = $this->model_so_comment->get_so_by_comment($track_no);
      $data["var_report"] = assign_data($result);

      $this->load->view('sales/internal/socomment/v_report',$data);
  }
  //--

}
