<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itr_get_data extends CI_Controller{

      function get_itr_delivery_from_sap(){
          $from = date('Ym01', strtotime('-3 month'));
          $to   = date("Ymd");

          $sap = $this->config->item('sap300');
          $result = $sap->callFunction("ZFN_MM_GET_RESERVATION_FLW",
          array(
            array("IMPORT","PI_SDATE",$from),
            array("IMPORT","PI_EDATE",$to),
            array("IMPORT","PI_REQNR",""),
            array("IMPORT","PI_RSNUM",""),
			array("IMPORT","PI_ISDEL",""),
            array("TABLE","PT_RESULT",array()),
          ));

          if ($sap->getStatus() == SAPRFC_OK) {
              $this->load->model('model_itr','',TRUE);

              $count = 0;
              foreach ($result["PT_RESULT"] as $row) {
                if($row['KQUIT'] == 'X'){
                  $this->model_itr->itr_h_code = $row['REQNR'];
                  $this->model_itr->sap_code = $row['RSNUM'];
                  $this->model_itr->sap_matdoc = $row['MBLNR'];
                  $this->model_itr->sap_matid = ltrim($row['MATNR'],"0");
                  $this->model_itr->qty = $row['ERFMG'];
                  if($row['ERFME'] == 'ST') $this->model_itr->uom = "PC";
                  else $this->model_itr->uom = $row['ERFME'];
                  $this->model_itr->tbnum = $row['TBNUM'];
                  $this->model_itr->lgnum = $row['LGNUM'];
                  $this->model_itr->tanum = $row['TANUM'];
                  $this->model_itr->qdatu = $row['QDATU'];
                  $this->model_itr->zeile = $row['ZEILE'];
                  $this->model_itr->dmbtr = $row['DMBTR'];
                  $this->model_itr->waers = $row['WAERS'];
                  $this->model_itr->sap_matdoc_date = $row['BUDAT'];
                  $this->model_itr->sap_matdoc_time = $row['CPUTM_MKPF'];
                  $this->model_itr->bwart_mat = $row['BWART_MAT'];
                  $this->model_itr->itrps = $row['ITRPS'];
                  $this->model_itr->insert_datetime = date('Y-m-d H:i:s');
                  $result1 = $this->model_itr->insert_itr_resv_matdoc_to();
                  if($result1) $count++;
                }
              }

              echo "Total Data inserted = ".$count."<br>";
          }
          else echo "not connected...";
      }
      //--------------------------------

}


?>
