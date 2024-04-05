<style>
  th { position: sticky; top: 0; }
</style>

<div class="containter-fluid">
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_cust_bo">EXCEL</button>
    <button class='btn btn-primary btn-sm' id="copy_button_cust_bo" style='margin-left:20px;'>Copy ALL</button>
</div>

<table class="table table-bordered table-sm table-striped" id="tbl_cust_bo" style="margin-top:20px;">
  <thead>
    <tr>
      <th class="table-dark">Cust No</th>
      <th class="table-dark">Cust Name</th>
      <th class="table-dark">Total SKU</th>
      <th class="table-dark">ORDER QTY</th><th class="table-dark">ORDER AMOUNT</th>
      <th class="table-dark">PROCEED QTY</th><th class="table-dark">PROCEED AMOUNT</th>
      <th class="table-dark">BACKORDER QTY</th><th class="table-dark">BACKORDER AMOUNT</th>
      <th class="table-dark">PROCEED QTY (%)</th><th class="table-dark">PROCEED AMOUNT (%)</th>
      <th class="table-dark">BACKORDER QTY (%)</th><th class="table-dark">BACKORDER AMOUNT (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // calculate total
      /*$total_order_qty = 0;
      $total_order_amount = 0;
      $total_proceed_qty = 0;
      $total_proceed_amount = 0;
      $total_backorder_qty = 0;
      $total_backorder_amount = 0;
      $proceed_qty_percent = 0;
      $proceed_amount_percent = 0;
      $backorder_qty_percent = 0;
      $backorder_amount_percent = 0;

      foreach($var_report as $row){
          $total_order_qty      += $row["order_qty"];
          $total_order_amount   += $row["order_amount"];
          $total_proceed_qty    += $row["proceed_qty"];
          $total_proceed_amount += $row["proceed_amount"];
          $total_backorder_qty    += $row["backorder_qty"];
          $total_backorder_amount += $row["backorder_amount"];
      }

      $proceed_qty_percent      = percentage($total_proceed_qty, $total_order_qty);
      $proceed_amount_percent   = percentage($total_proceed_amount, $total_order_amount);
      $backorder_qty_percent    = percentage($total_backorder_qty, $total_order_qty);
      $backorder_amount_percent = percentage($total_backorder_amount, $total_order_amount);
      //---
      // first row
      echo "<tr>";
        echo "<td colspan='2'>SUMMARY</td>";
        echo "<td id='number'>".format_number($total_order_qty,0,2)."</td>";
        echo "<td id='number'>".format_number($total_order_amount,1,2)."</td>";
        echo "<td id='number'>".format_number($total_proceed_qty,0,2)."</td>";
        echo "<td id='number'>".format_number($total_proceed_amount,1,2)."</td>";
        echo "<td id='number'>".format_number($total_backorder_qty,0,2)."</td>";
        echo "<td id='number'>".format_number($total_backorder_amount,1,2)."</td>";
        echo "<td id='number'>".format_number($proceed_qty_percent,1,2)."</td>";
        echo "<td id='number'>".format_number($proceed_amount_percent,1,2)."</td>";
        echo "<td id='number'>".format_number($backorder_qty_percent,1,2)."</td>";
        echo "<td id='number'>".format_number($backorder_qty_percent,1,2)."</td>";
      echo "</tr>";
      */
      // the rest
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["cust_no"]."</td>";
            echo "<td>".$row["cust_name"]."</td>";
            echo "<td>".$row["total_sku"]."</td>";
            echo "<td id='number'>".format_number($row["order_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["order_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["outstanding_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["outstanding_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_percent_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_percent_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["backorder_percent_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["backorder_percent_amount"],0,2)."</td>";
          echo "</tr>";
      }


    ?>
  </tbody>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_cust_bo').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_cust_bo'),"CUST-BO"),1000);
});
//---
</script>
