<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Others extends CI_Controller{

    function __construct(){
      parent::__construct();
      //$this->load->database();
    }

    function kws_buy_past3months(){
        $this->load->view('templates/navigation');

        if(!isset($_SESSION['menus_list_user']['others/kws_buy_past3months'])){
            $this->load->view('view_home');
        }
        else{
            $this->load->view('others/v_kws_buy_past3months');
        }
    }
    //----

    function get_kws_buy_past3months(){
        $this->load->model('model_others','',TRUE);

        $date_from  = $_POST['date_from'];
        $date_to    = $_POST['date_to'];

        $result = $this->model_others->kws_buy_past3months($date_from,$date_to);

        if($result){
          foreach($result as $row){
             $data['v_list_kws_buy_past3months'][] = array(
                                       "region" => $row['region'],
                                       "last3monthbuy" => $row['last3monthbuy'],
                                       );
          }
        }
        else $data['v_list_kws_buy_past3months'] = 0;

        $this->load->view('others/v_kws_buy_past3months_generate',$data);
    }
    //---

}
