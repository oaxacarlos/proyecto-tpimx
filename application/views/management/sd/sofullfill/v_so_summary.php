<div class="containter-fluid">
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_so_summary">EXCEL</button>
    <button class='btn btn-primary btn-sm' id="copy_button_so_summary" style='margin-left:20px;'>Copy ALL</button>
</div>

<table class="table table-bordered table-sm table-striped" id="tbl_so_summary" style="margin-top:20px;">
  <thead>
    <tr>
      <th class="table-dark">Date</th>
      <th class="table-dark">SO</th>
      <th class="table-dark">ORDER QTY</th><th class="table-dark">ORDER AMOUNT</th>
      <th class="table-dark">PROCEED QTY</th><th class="table-dark">PROCEED AMOUNT</th>
      <th class="table-dark">FULLFILL QTY (%)</th><th class="table-dark">FULLFILL AMOUNT (%)</th>
      <th class="table-dark">BACKORDER QTY (%)</th><th class="table-dark">BACKORDER AMOUNT (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // calculate total
      $total_order_qty = 0;
      $total_order_amount = 0;
      $total_proceed_qty = 0;
      $total_proceed_amount = 0;
      $fullfill_qty_percent = 0;
      $fullfill_amount_percent = 0;
      $backorder_qty_percent = 0;
      $backorder_amount_percent = 0;

      foreach($var_report as $row){
          $total_order_qty      += $row["order_qty"];
          $total_order_amount   += $row["order_amount"];
          $total_proceed_qty    += $row["proceed_qty"];
          $total_proceed_amount += $row["proceed_amount"];
      }

      $fullfill_qty_percent = percentage($total_proceed_qty, $total_order_qty);
      $fullfill_amount_percent = percentage($total_proceed_amount, $total_order_amount);
      $backorder_qty_percent = 100-$fullfill_qty_percent;
      $backorder_amount_percent = 100-$fullfill_amount_percent;
      //---

      // first row
      echo "<tr>";
        echo "<td colspan='2'>SUMMARY</td>";
        echo "<td id='number'>".format_number($total_order_qty,0,2)."</td>";
        echo "<td id='number'>".format_number($total_order_amount,0,2)."</td>";
        echo "<td id='number'>".format_number($total_proceed_qty,0,2)."</td>";
        echo "<td id='number'>".format_number($total_proceed_amount,0,2)."</td>";
        echo "<td id='number'>".format_number($fullfill_qty_percent,0,2)."</td>";
        echo "<td id='number'>".format_number($fullfill_amount_percent,0,2)."</td>";
        echo "<td id='number'>".format_number($backorder_qty_percent,0,2)."</td>";
        echo "<td id='number'>".format_number($backorder_amount_percent,0,2)."</td>";
      echo "</tr>";
      // the rest
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["order_date"]."</td>";
            echo "<td>".$row["doc_no"]."</td>";
            echo "<td id='number'>".format_number($row["order_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["order_amount"],1,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_amount"],1,2)."</td>";
            echo "<td id='number'>".format_number($row["fullfill_percent_qty"],1,2)."</td>";
            echo "<td id='number'>".format_number($row["fullfill_percent_amount"],1,2)."</td>";
            echo "<td id='number'>".format_number($row["backorder_percent_qty"],1,2)."</td>";
            echo "<td id='number'>".format_number($row["backorder_percent_amount"],1,2)."</td>";
          echo "</tr>";
      }


    ?>
  </tbody>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_so_summary').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_so_summary'),"SO-SUMMARY"),1000);
});
//---
</script>
