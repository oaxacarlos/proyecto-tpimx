<script>
$(document).ready(function() {
    $('#tbl_report').DataTable({
      "paging": false,
      "ordering": false,
    });
});
//---

</script>

<style>
  tr{
    font-size: 12px;
  }

  th#title{
    text-align: center;
  }

  tr#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

  td#number2{
    text-align: right;
    font-weight: bold;
  }

</style>

<div class="container-fluid">
  <div class="row">
    <table class="table table-bordered table-sm">
      <tr>
        <th>Inventory</th>
        <td>Current Stock</td>
      </tr>
      <tr>
        <th>Sales YTD</th>
        <td><?php echo $var_date["ytd_from"]." to ".$var_date["ytd_to"]; ?></td>
      </tr>
      <tr>
        <th>Last Year</th>
        <td><?php echo $var_date["last_year_from"]." to ".$var_date["last_year_to"]; ?></td>
      </tr>
      <tr>
        <th>Not Moving Last 6 Months</th>
        <td><?php echo $var_date["last_6months_from"]." to ".$var_date["last_6months_to"]; ?></td>
      </tr>
      <tr>
        <th>Not Moving Last 12 Months</th>
        <td><?php echo $var_date["last_12months_from"]." to ".$var_date["last_12months_to"]; ?></td>
      </tr>
      <tr>
        <th>Not Moving Last Year</th>
        <td><?php echo $var_date["last_year_from"]." to ".$var_date["last_year_to"]; ?></td>
      </tr>
    </table>
  </div>
</div>

<div class="containter-fluid">
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_tbl_report" onclick=f_convert_to_excel()>EXCEL</button>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report">
  <thead>
    <tr id="title">
      <th rowspan='2'>Category</th>
      <th rowspan='2'>Sub Cat</th>
      <th colspan='3'>Inventory</th>
      <th colspan='3'>Sales YTD</th>
      <th colspan='3'>Sales Last Year</th>
      <th colspan='3'>Not Moving Last 6 Months</th>
      <th colspan='3'>Not Moving Last 12 Months</th>
      <th colspan='3'>Not Moving Last Year</th>
    </tr>
    <tr id="title">
      <th>No. of SKU</th><th>Qty</th><th>Amount</th>
      <th>No. of SKU</th><th>Qty</th><th>Amount</th>
      <th>No. of SKU</th><th>Qty</th><th>Amount</th>
      <th>No. of SKU</th><th>Qty</th><th>Amount</th>
      <th>No. of SKU</th><th>Qty</th><th>Amount</th>
      <th>No. of SKU</th><th>Qty</th><th>Amount</th>
  </thead>
  <tbody>
    <?php

      // initial
      $total["invt_sku"] = 0;
      $total["invt_qty"] = 0;
      $total["invt_amount"] = 0;
      $total["sls_ytd_sku"] = 0;
      $total["sls_ytd_qty"] = 0;
      $total["sls_ytd_amount"] = 0;
      $total["sls_last_year_sku"] = 0;
      $total["sls_last_year_qty"] = 0;
      $total["sls_last_year_amount"] = 0;
      $total["item_not_moving_6months_sku"] = 0;
      $total["item_not_moving_6months_qty"] = 0;
      $total["item_not_moving_6months_amount"] = 0;
      $total["item_not_moving_12months_sku"] = 0;
      $total["item_not_moving_12months_qty"] = 0;
      $total["item_not_moving_12months_amount"] = 0;
      $total["item_not_moving_lastyear_sku"] = 0;
      $total["item_not_moving_lastyear_qty"] = 0;
      $total["item_not_moving_lastyear_amount"] = 0;
      //--

      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["item_category_codee"]."</td>";
            echo "<td>".$row["manufacture_codee"]."</td>";

            echo "<td id='number'><a href='#' onclick=f_show_detail('".$row["item_category_codee"]."','".$row["manufacture_codee"]."','1','".$var_date["ytd_from"]."','".$var_date["ytd_to"]."','".$var_date["last_year_from"]."','".$var_date["last_year_to"]."','".$var_date["last_6months_from"]."','".$var_date["last_6months_to"]."','".$var_date["last_12months_from"]."','".$var_date["last_12months_to"]."','".$var_type."')>".format_number($row["invt_no_sku"],0,0)."</a></td>";
            $total["invt_sku"] += $row["invt_no_sku"];

            echo "<td id='number'>".format_number($row["invt_qty"],0,2)."</td>";
            $total["invt_qty"] += $row["invt_qty"];

            echo "<td id='number'>".format_number($row["invt_amount"],0,2)."</td>";
            $total["invt_amount"] += $row["invt_amount"];

            echo "<td id='number'><a href='#' onclick=f_show_detail('".$row["item_category_codee"]."','".$row["manufacture_codee"]."','2','".$var_date["ytd_from"]."','".$var_date["ytd_to"]."','".$var_date["last_year_from"]."','".$var_date["last_year_to"]."','".$var_date["last_6months_from"]."','".$var_date["last_6months_to"]."','".$var_date["last_12months_from"]."','".$var_date["last_12months_to"]."','".$var_type."')>".format_number($row["sls_ytd_no_sku"],0,0)."</td>";
            $total["sls_ytd_sku"] += $row["sls_ytd_no_sku"];

            echo "<td id='number'>".format_number($row["sls_ytd_qty"],0,2)."</td>";
            $total["sls_ytd_qty"] += $row["sls_ytd_qty"];

            echo "<td id='number'>".format_number($row["sls_ytd_amount"],0,2)."</td>";
            $total["sls_ytd_amount"] += $row["sls_ytd_amount"];

            echo "<td id='number'><a href='#' onclick=f_show_detail('".$row["item_category_codee"]."','".$row["manufacture_codee"]."','3','".$var_date["ytd_from"]."','".$var_date["ytd_to"]."','".$var_date["last_year_from"]."','".$var_date["last_year_to"]."','".$var_date["last_6months_from"]."','".$var_date["last_6months_to"]."','".$var_date["last_12months_from"]."','".$var_date["last_12months_to"]."','".$var_type."')>".format_number($row["sls_lastyear_no_sku"],0,0)."</td>";
            $total["sls_last_year_sku"] += $row["sls_lastyear_no_sku"];

            echo "<td id='number'>".format_number($row["sls_lastyear_qty"],0,2)."</td>";
            $total["sls_last_year_qty"] += $row["sls_lastyear_qty"];

            echo "<td id='number'>".format_number($row["sls_lastyear_amount"],0,2)."</td>";
            $total["sls_last_year_amount"] += $row["sls_lastyear_amount"];

            echo "<td id='number'><a href='#' onclick=f_show_detail('".$row["item_category_codee"]."','".$row["manufacture_codee"]."','4','".$var_date["ytd_from"]."','".$var_date["ytd_to"]."','".$var_date["last_year_from"]."','".$var_date["last_year_to"]."','".$var_date["last_6months_from"]."','".$var_date["last_6months_to"]."','".$var_date["last_12months_from"]."','".$var_date["last_12months_to"]."','".$var_type."')>".format_number($row["item_not_moving6month_no_sku"],0,0)."</td>";
            $total["item_not_moving_6months_sku"] += $row["item_not_moving6month_no_sku"];

            echo "<td id='number'>".format_number($row["item_not_moving6month_qty"],0,2)."</td>";
            $total["item_not_moving_6months_qty"] += $row["item_not_moving6month_qty"];

            echo "<td id='number'>".format_number($row["item_not_moving6month_amount"],0,2)."</td>";
            $total["item_not_moving_6months_amount"] += $row["item_not_moving6month_amount"];

            echo "<td id='number'><a href='#' onclick=f_show_detail('".$row["item_category_codee"]."','".$row["manufacture_codee"]."','5','".$var_date["ytd_from"]."','".$var_date["ytd_to"]."','".$var_date["last_year_from"]."','".$var_date["last_year_to"]."','".$var_date["last_6months_from"]."','".$var_date["last_6months_to"]."','".$var_date["last_12months_from"]."','".$var_date["last_12months_to"]."','".$var_type."')>".format_number($row["item_not_moving12month_no_sku"],0,0)."</td>";
            $total["item_not_moving_12months_sku"] += $row["item_not_moving12month_no_sku"];

            echo "<td id='number'>".format_number($row["item_not_moving12month_qty"],0,2)."</td>";
            $total["item_not_moving_12months_qty"] += $row["item_not_moving12month_qty"];

            echo "<td id='number'>".format_number($row["item_not_moving12month_amount"],0,2)."</td>";
            $total["item_not_moving_12months_amount"] += $row["item_not_moving12month_amount"];

            echo "<td id='number'><a href='#' onclick=f_show_detail('".$row["item_category_codee"]."','".$row["manufacture_codee"]."','6','".$var_date["ytd_from"]."','".$var_date["ytd_to"]."','".$var_date["last_year_from"]."','".$var_date["last_year_to"]."','".$var_date["last_6months_from"]."','".$var_date["last_6months_to"]."','".$var_date["last_12months_from"]."','".$var_date["last_12months_to"]."','".$var_type."')>".format_number($row["item_not_moving_lastyear_no_sku"],0,0)."</td>";
            $total["item_not_moving_lastyear_sku"] += $row["item_not_moving_lastyear_no_sku"];

            echo "<td id='number'>".format_number($row["item_not_moving_lastyear_qty"],0,2)."</td>";
            $total["item_not_moving_lastyear_qty"] += $row["item_not_moving_lastyear_qty"];

            echo "<td id='number'>".format_number($row["item_not_moving_lastyear_amount"],0,2)."</td>";
            $total["item_not_moving_lastyear_amount"] += $row["item_not_moving_lastyear_amount"];

          echo "</tr>";
      }

      // total
      echo "<tr>";
        echo "<td id='number2'></td>";
        echo "<td id='number2'>TOTAL</td>";
        echo "<td id='number2'>".format_number($total["invt_sku"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["invt_qty"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["invt_amount"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["sls_ytd_sku"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["sls_ytd_qty"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["sls_ytd_amount"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["sls_last_year_sku"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["sls_last_year_qty"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["sls_last_year_amount"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_6months_sku"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_6months_qty"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_6months_amount"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_12months_sku"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_12months_qty"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_12months_amount"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_lastyear_sku"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_lastyear_qty"],0,2)."</td>";
        echo "<td id='number2'>".format_number($total["item_not_moving_lastyear_amount"],0,2)."</td>";
      echo "</tr>";
      //--
    ?>
  </tbody>
</table>

<script>

function f_show_detail(item_cat,manf_code,type,ytd_from,ytd_to,last_year_from,last_year_to,last_6months_from,last_6months_to,last_12months_from,last_12months_to,brand){
    data = { 'item_cat':item_cat,'manf_code':manf_code,'type':type,'ytd_from':ytd_from,'ytd_to':ytd_to,'last_year_from':last_year_from,'last_year_to':last_year_to,'last_6months_from':last_6months_from,'last_6months_to':last_6months_to,'last_12months_from':last_12months_from,'last_12months_to':last_12months_to,'brand':brand}

    $('#modal_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_detail').load(
        "<?php echo base_url();?>index.php/management/sd/invtmonitor_detail",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalDetail').modal();


}
//---

function f_convert_to_excel(){
  alert("Your converted to Excel, check your DOWNLOAD folder");
  var table2excel = new Table2Excel();
  setTimeout(table2excel.export(document.querySelector('#tbl_report'),"TPM-InventoryMonitoring"),1000);
}

</script>
