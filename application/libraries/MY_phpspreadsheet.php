<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_phpspreadsheet {

    public function convert_qty_item_online_to_excel($data){

        require 'PhpOffice/PhpSpreadsheet/Spreadsheet.php';
        //require 'PhpOffice/PhpSpreadsheet/Writer/Xlsx.php';

        $spreadsheet = new Spreadsheet();
    }
    //----------------------------


}

?>
