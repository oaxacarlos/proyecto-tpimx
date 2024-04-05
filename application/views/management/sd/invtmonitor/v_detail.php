<div style="margin-bottom:20px;">
  <button class="btn btn-success btn-sm"  id="btn_export_xlsx">EXCEL</button>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_detail">
  <thead>
    <th id="title">No</th>
    <th id="title">Item No</th>
    <th id="title">Category</th>
    <th id="title">Sub Cat</th>
    <th id="title">Qty</th>
    <th id="title">Amount</th>
    <th id="title">Last In</th>
    <th id="title">Last Out</th>
  </thead>
  <tbody>
    <?php

      $total["qty"] = 0;
      $total["amount"] = 0;

      $i=1;
      foreach($var_report as $row){
        echo "<tr>";
          echo "<td>".$i."</td>";
          echo "<td>".$row["item_no"]."</td>";
          echo "<td>".$row["item_category_codee"]."</td>";
          echo "<td>".$row["manufacture_codee"]."</td>";

          echo "<td id='number'>".format_number($row["qty"],1,2)."</td>";
          $total["qty"] += $row["qty"];

          echo "<td id='number'>".format_number($row["amount"],1,2)."</td>";
          $total["amount"] += $row["amount"];

          echo "<td id='number'>".$row["in_time"]."</td>";
          echo "<td id='number'>".$row["out_time"]."</td>";

        echo "</tr>";
        $i++;
      }

      // total
      echo "<tr>";
        echo "<th colspan='4'>TOTAL</th>";
        echo "<td id='number'>".format_number($total["qty"],1,2)."</th>";
        echo "<td id='number'>".format_number($total["amount"],1,2)."</th>";
        echo "<td></td><td></td>";
      echo "</tr>";
    ?>
  </tbody>
</table>

<script>
//sales report
var table2excel = new Table2Excel();
document.getElementById('btn_export_xlsx').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel.export(document.querySelector('#tbl_detail'),"InventoryMonitoringItemDetail"),1000);
});
//--
</script>
