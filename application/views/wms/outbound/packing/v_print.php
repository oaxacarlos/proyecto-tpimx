<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}

@media print {
  @page {
    margin: 0 0 0 0;
    size: auto;
  }

  /*top right bottom left */
  body { margin: 0cm 0cm 1cm 0cm; }

  #printPageButton {
      display: none;
    }

}



</style>

<table border="1" style="width:500px; margin-left:30px; margin-top:30px;">
  <tr>
    <td colspan='2' style='font-weight:bold;'>
      <?php

        // split external document
        $ext_doc_new = explode("*",$result_doc_h["external_document"]);
        //--

        echo strtoupper("Date : ").$var_packing_h["doc_date"]; echo "<br>";
        echo strtoupper("document no : ").$var_packing_h["doc_no"]; echo "<br><br>";
        echo strtoupper("SO no : ").$so_no; echo "<br>";
        echo strtoupper("Customer No : ").$result_so["sell_cust_no"]; echo "<br><br>";
        echo strtoupper("Remarks : ").$ext_doc_new[0]; echo "<br>";

        echo "<br>";
      ?>
    </td>
  </tr>
  <tr>
    <td style="width:200px;"><b>From</b></td>
    <td><b>To</b></td>
  </tr>
  <tr>
    <td style="padding-left:5px;"><?php echo strtoupper($tpimx_addr); ?></td>
    <td style="padding-left:5px;">
      <?php
        echo "NAME : ".strtoupper($var_packing_h["dest_name"])."<br>";
        echo "CONTACT : ".$var_packing_h["dest_contact"]."<br><br>";
        echo "ADDRESS : <br>";
        echo $var_packing_h["dest_addr"]."<br>";
        echo $var_packing_h["dest_addr2"]."<br>";
        echo $var_packing_h["dest_city"]."<br>";
        echo $var_packing_h["dest_post_code"]."<br>";
        echo $var_packing_h["dest_county"]."<br>";
        echo $var_packing_h["dest_country"]."<br>";
      ?>
    </td>
  </tr>
</table>

<table border="1" style="width:500px; margin-left:30px; margin-top:30px;">
  <tr>
    <td colspan='2' style='font-weight:bold;'>
      <?php

        // split external document
        $ext_doc_new = explode("*",$result_doc_h["external_document"]);
        //--

        echo strtoupper("Date : ").$var_packing_h["doc_date"]; echo "<br>";
        echo strtoupper("document no : ").$var_packing_h["doc_no"]; echo "<br><br>";
        echo strtoupper("SO no : ").$so_no; echo "<br>";
        echo strtoupper("Customer No : ").$result_so["sell_cust_no"]; echo "<br><br>";
        echo strtoupper("Remarks : ").$ext_doc_new[0]; echo "<br>";

        echo "<br>";
      ?>
    </td>
  </tr>
  <tr>
    <td style="width:200px;"><b>From</b></td>
    <td><b>To</b></td>
  </tr>
  <tr>
    <td style="padding-left:5px;"><?php echo strtoupper($tpimx_addr); ?></td>
    <td style="padding-left:5px;">
      <?php
        echo "NAME : ".strtoupper($var_packing_h["dest_name"])."<br>";
        echo "CONTACT : ".$var_packing_h["dest_contact"]."<br><br>";
        echo "ADDRESS : <br>";
        echo $var_packing_h["dest_addr"]."<br>";
        echo $var_packing_h["dest_addr2"]."<br>";
        echo $var_packing_h["dest_city"]."<br>";
        echo $var_packing_h["dest_post_code"]."<br>";
        echo $var_packing_h["dest_county"]."<br>";
        echo $var_packing_h["dest_country"]."<br>";
      ?>
    </td>
  </tr>
</table>

<button id="printPageButton" onclick="window.print();return false;" / style="margin-top:10px;">PRINT</button>
