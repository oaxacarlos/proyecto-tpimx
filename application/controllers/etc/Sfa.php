<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sfa extends CI_Controller{
  function __construct(){
    parent::__construct();
    //$this->load->database();
  }

  function index(){
      $this->load->view('templates/navigation');
  }
  //----------------

  function sir_on_pc(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/sir_on_pc'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_sir_on_pc');
    }

  }
  //------------------------

  function pcode_vaso(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/pcode_vaso'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_pcode_vaso');
    }

  }
  //------------------------

  function pcode_vafo(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/pcode_vafo'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_pcode_vafo');
    }

  }
  //------------------------

  function dir(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/dir'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_dir');
    }

  }
  //------------------------

  function dailystock(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/dailystock'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_daily_stock');
    }

  }
  //------------------------

  function demandtracking(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/demandtracking'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_demand_tracking');
    }

  }
  //------------------------

  function odp(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/odp'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_odp');
    }

  }
  //------------------------

  function priceto(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/priceto'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_price_to');
    }

  }
  //------------------------

  function pricecanvass(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/pricecanvass'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_price_canvass');
    }

  }
  //------------------------

  function routevaso(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/routevaso'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_route_vaso');
    }

  }
  //------------------------

  function routevafo(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/routevafo'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_route_vafo');
    }

  }
  //------------------------

  function routekeso(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/routekeso'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_route_keso');
      }
  }
  //------------------------

  function salesvariancanvass(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/salesvariancanvass'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_sales_varian_canvass');
      }
  }
  //------------------------

  function graphsellingpoint(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/graphsellingpoint'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_graph_selling_point');
      }
  }
  //------------------------

  function uploadinventoryfood(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/uploadinventoryfood'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_upload_inventory_food');
      }
  }
  //------------------------

  function uploadinventorynonfood(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/uploadinventorynonfood'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_upload_inventory_nonfood');
      }
  }
  //------------------------

  function checkweekday(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/checkweekday'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_checkweekday');
      }
  }
  //------------------------

  function summaryanalysisvisit(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/summaryanalysisvisit'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_summary_analysis_visit');
      }
  }
  //------------------------

  function dirmoderntrade(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/dirmoderntrade'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_dir_modern_trade');
      }
  }
  //------------------------

  function summarycanvass(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user']['sfa/summarycanvass'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('sfa/v_summary_canvass');
      }
  }
  //------------------------
  
  function dirnationaldetail(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/dirnationaldetail'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_dir_national_detail');
    }

  }
  //------------------------
  
  function sttreport(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/sttreport'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_sttreport');
    }

  }
  //------------------------
  
  function sttsummaryreport(){
    $this->load->view('templates/navigation');

    if(!isset($_SESSION['menus_list_user']['sfa/sttsummaryreport'])){
        $this->load->view('view_home');
    }
    else{
        $this->load->view('sfa/v_sttsummaryreport');
    }

  }
  //------------------------

	function vansalesdetail(){
		$this->load->view('templates/navigation');

		if(!isset($_SESSION['menus_list_user']['sfa/vansalesdetail'])){
			$this->load->view('view_home');
		}
		else{
			$this->load->view('sfa/v_vansales_detail');
		}
	}
	//------------------------

}

?>
