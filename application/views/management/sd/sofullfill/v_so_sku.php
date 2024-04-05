

<div class="containter-fluid">
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_so_sku">EXCEL</button>
    <button class='btn btn-primary btn-sm' id="copy_button_so_sku" style='margin-left:20px;'>Copy ALL</button>
</div>

<table class="table table-bordered table-sm table-striped" id="tbl_so_sku" style="margin-top:20px;">
  <thead>
    <tr>
      <th class="table-dark">SKU CODE</th>
      <th class="table-dark">DESC</th>
      <th class="table-dark">ORDER QTY</th><th class="table-dark">ORDER AMOUNT</th>
      <th class="table-dark">PROCEED QTY</th><th class="table-dark">PROCEED AMOUNT</th>
      <th class="table-dark">FULLFILL QTY (%)</th><th class="table-dark">FULLFILL AMOUNT (%)</th>
      <th class="table-dark">BACKORDER QTY (%)</th><th class="table-dark">BACKORDER AMOUNT (%)</th>
      <th class="table-dark">STOCK MONTH END</th>
      <th class="table-dark">REMARKS</th>
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

      //excluded backorder
      $total_order_qty_exc_bo = 0;
      $total_order_amount_exc_bo = 0;
      $total_proceed_qty_exc_bo = 0;
      $total_proceed_amount_exc_bo = 0;
      $fullfill_qty_percent_exc_bo = 0;
      $fullfill_amount_percent_exc_bo = 0;
      //--

      foreach($var_report as $row){
          $total_order_qty      += $row["order_qty"];
          $total_order_amount   += $row["order_amount"];
          $total_proceed_qty    += $row["proceed_qty"];
          $total_proceed_amount += $row["proceed_amount"];

          //excluded backorder
          if($row["proceed_qty"] > 0){
            $total_order_qty_exc_bo      += $row["order_qty"];
            $total_order_amount_exc_bo   += $row["order_amount"];
            $total_proceed_qty_exc_bo    += $row["proceed_qty"];
            $total_proceed_amount_exc_bo += $row["proceed_amount"];
          }
          //--
      }

      $fullfill_qty_percent = percentage($total_proceed_qty, $total_order_qty);
      $fullfill_amount_percent = percentage($total_proceed_amount, $total_order_amount);
      $backorder_qty_percent = 100-$fullfill_qty_percent;
      $backorder_amount_percent = 100-$fullfill_amount_percent;

      //excluded backorder
      $fullfill_qty_percent_exc_bo = percentage($total_proceed_qty_exc_bo, $total_order_qty_exc_bo);
      $fullfill_amount_percent_exc_bo = percentage($total_proceed_amount_exc_bo, $total_order_amount_exc_bo);
      //--

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
        echo "<td id='number'></td>";
        echo "<td id='number'></td>";
      echo "</tr>";

      // second row
      echo "<tr>";
        echo "<td colspan='2'>SUMMARY(EXCLUDE BACKORDER OUT OF STOCK)</td>";
        echo "<td id='number'>".format_number($total_order_qty_exc_bo,0,2)."</td>";
        echo "<td id='number'>".format_number($total_order_amount_exc_bo,0,2)."</td>";
        echo "<td id='number'>".format_number($total_proceed_qty_exc_bo,0,2)."</td>";
        echo "<td id='number'>".format_number($total_proceed_amount_exc_bo,0,2)."</td>";
        echo "<td id='number'>".format_number($fullfill_qty_percent_exc_bo,0,2)."</td>";
        echo "<td id='number'>".format_number($fullfill_amount_percent_exc_bo,0,2)."</td>";
        echo "<td id='number'></td>";
        echo "<td id='number'></td>";
        echo "<td id='number'></td>";
        echo "<td id='number'></td>";
      echo "</tr>";

      // the rest
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["item_no"]."</td>";
            echo "<td>".$row["name"]."</td>";
            echo "<td id='number'>".format_number($row["order_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["order_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["proceed_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["fullfill_percent_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["fullfill_percent_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["backorder_percent_qty"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["backorder_percent_amount"],0,2)."</td>";
            echo "<td id='number'>".format_number($row["qty_stock"],0,2)."</td>";

            if($row["no_stock"] == "1")
              echo "<td><span class='badge badge-danger' style='font-size:12px;'>no stock</span></td>";
            else if($row["no_stock"] == "0")
              echo "<td><span class='badge badge-success' style='font-size:12px;'>have stock</span></td>";
            else echo "<td>-</td>";

          echo "</tr>";
      }


    ?>
  </tbody>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_so_sku').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_so_sku'),"SO-SKU"),1000);
});
//---
</script>
