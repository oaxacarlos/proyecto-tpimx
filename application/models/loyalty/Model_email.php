<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_email extends CI_Model{

      function insert($to, $cc, $subject, $from, $body, $datetime){
          $db = $this->load->database('default_client', true);
          $data = array(
            'to' => $to,
            'cc' => $cc,
            'subject' => $subject,
            'from' => $from,
            'body' => $body,
            'added_at' => $datetime,
            );


            $result = $db->insert('tsc_email', $data);
            if($result) return true; else return false;
      }
      //--
}

?>
