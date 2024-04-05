<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empc_get_data extends CI_Controller{

      function get_empc_delivery_from_sap(){
          $from = date('Ym01', strtotime('-3 month'));
          $to   = date("Ymd");

          $sap = $this->config->item('sap300');
          $result = $sap->callFunction("ZFN_MM_EMP_RESERVATION_FLW",
          array(
            array("IMPORT","PI_SDATE",$from),
            array("IMPORT","PI_EDATE",$to),
            array("IMPORT","PI_REQNR",""),
            array("IMPORT","PI_RSNUM",""),
            array("IMPORT","PI_ISDEL",""),
            array("TABLE","PT_RESULT",array()),
          ));

          if ($sap->getStatus() == SAPRFC_OK) {
              $this->load->model('model_empc','',TRUE);

              $count = 0;
              foreach ($result["PT_RESULT"] as $row) {
                if($row['KQUIT'] != 'X'){
                  $this->model_empc->empc_h_code  = $row['REQNR'];
                  $this->model_empc->sap_code     = $row['RSNUM'];
                  $this->model_empc->sap_matdoc   = $row['MBLNR'];
                  $this->model_empc->sap_matid    = ltrim($row['MATNR'],"0");
                  $this->model_empc->qty          = $row['ERFMG'];

                  if($row['ERFME'] == 'ST') $this->model_empc->uom = "PC";
                  else $this->model_empc->uom = $row['ERFME'];

                  $this->model_empc->tbnum = $row['TBNUM'];
                  $this->model_empc->lgnum = $row['LGNUM'];
                  $this->model_empc->tanum = $row['TANUM'];
                  $this->model_empc->qdatu = $row['QDATU'];
                  $this->model_empc->zeile = $row['ZEILE'];
                  $this->model_empc->dmbtr = $row['DMBTR'];
                  $this->model_empc->waers = $row['WAERS'];
                  $this->model_empc->sap_matdoc_date  = $row['BUDAT'];
                  $this->model_empc->sap_matdoc_time  = $row['CPUTM_MKPF'];
                  $this->model_empc->bwart_mat        = $row['BWART_MAT'];
                  $this->model_empc->empcps           = $row['ITRPS'];
                  $this->model_empc->insert_datetime  = date('Y-m-d H:i:s');
                  $result1 = $this->model_empc->insert_empc_resv_matdoc_to();
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
