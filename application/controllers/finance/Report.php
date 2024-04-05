<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Report extends CI_Controller{
  function __construct(){
    parent::__construct();
       $this->load->model('model_finance_report','',TRUE);
  }

  function fixedassetbarcode(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('finance_report_folder').'fixedassetbarcode'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('finance/report/fixedassetbarcode/v_index', $data);
      }
  }
  //--

  function fixedassetbarcode_list(){
      $data["var_fixedasset"] = assign_data($this->model_finance_report->get_fixed_asset_from_nav());
      $this->load->view('finance/report/fixedassetbarcode/v_list', $data);
  }
  //--

  function fixedassetbarcode_print(){
      $total = $_GET["total"];

      unset($barcode);
      for($i=0;$i<$total;$i++){
          $barcode[] = $_GET["data".$i];
      }

      $file_folder_items = $this->config->item('wms_barcode_file_items');
      $file_ext = ".png";

      foreach($barcode as $row){
          $filename_items = $file_folder_items.$row.$file_ext;
          //if (!file_exists($filename_items)){
          $this->set_barcode($row,$file_folder_items,$file_ext,20,1,1,15);
      }

      $data["barcode_data"] = $barcode;
      $data["file_folder_items"] = $file_folder_items;
      $data["file_ext"] = $file_ext;

      $this->load->view('finance/report/fixedassetbarcode/v_print',$data);
  }
  //---

  private function set_barcode($code,$file_folder,$file_ext, $height, $factor, $barthinwidth, $fontSize)
  {
      //load library
      $this->load->library('ci_zend');

      //load in folder Zend
      $this->ci_zend->load('Zend/Barcode');

      //Zend_Barcode::setBarcodeFont(APPPATH . '\libraries\Zend\Fonts\Helvetica.ttf');
      //generate barcode
      $barcodeOptions = array(
          'text' => $code,
          'barHeight'=> $height,
          'factor'=>$factor,
          'withQuietZones' => true,
          'fontSize' => $fontSize
      );

      $file = Zend_Barcode::factory('code128', 'image',$barcodeOptions,array())->draw();
      imagepng($file,$file_folder.$code.$file_ext);
  }
  //--

  function arreport(){
      $this->load->view('templates/navigation');

      if(!isset($_SESSION['menus_list_user'][$this->config->item('finance_report_folder').'arreport'])){
          $this->load->view('view_home');
      }
      else{
          $this->load->view('finance/report/arreport/v_index', $data);
      }
  }
  //----

  function arreport_one_data(){
      $year  = $_POST["year"];
      $month = $_POST["month"];

      $result = $this->model_finance_report->get_ar_amount_by_invoice_date($year, $month);
      $data["var_report"] = assign_data($result);

      $this->load->view('finance/report/arreport/v_arreportone', $data);
  }
  //--

  function arreport_two_data(){
      $year  = $_POST["year"];
      $month = $_POST["month"];

      $result = $this->model_finance_report->get_ar_credit_term_by_invoice_date($year, $month);
      $data["var_report"] = assign_data($result);

      $this->load->view('finance/report/arreport/v_arreporttwo', $data);
  }
  //--

  function arreport_three_data(){
      $year  = $_POST["year"];
      $month = $_POST["month"];

      $year =   substr($year,-2);

      $result = $this->model_finance_report->get_ar_amount_by_payment_date($year, $month);
      $data["var_report"] = assign_data($result);

      $this->load->view('finance/report/arreport/v_arreportthree', $data);
  }
  //--

  function arreport_four_data(){
      $year  = $_POST["year"];
      $month = $_POST["month"];

      $year =   substr($year,-2);

      $result = $this->model_finance_report->get_ar_credit_term_by_payment_date($year, $month);
      $data["var_report"] = assign_data($result);

      $this->load->view('finance/report/arreport/v_arreportfour', $data);
  }
  //--
}

?>
