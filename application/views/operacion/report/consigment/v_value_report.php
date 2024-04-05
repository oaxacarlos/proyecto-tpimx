<style>
  tr{
    font-size: 14px;
  }

  td#number{
    text-align: right;
  }

  th#number{
    text-align: right;
  }
</style>

<div>
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_consigment_value">EXCEL</button>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report_consigment_value" style="margin-top:20px;">
    <thead>
        <th>Item</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Value</th>
    </thead>
    <tbody>
      <?php
        $total_qty = 0;
        $total_value = 0;
        foreach($var_report as $row){
            echo "<tr>";
              echo "<td>".$row["item_no"]."</td>";
              echo "<td id='number'>".number_format($row["qty"])."</td>";
              echo "<td id='number'>".$row["unit_price"]."</td>";
              echo "<td id='number'>".number_format($row["total_value"],2)."</td>";
            echo "</tr>";

            $total_qty += $row["qty"];
            $total_value += $row["total_value"];
        }

        echo "<tr>";
          echo "<th>Total</td>";
          echo "<th id='number'>".number_format($total_qty)."</td>";
          echo "<th>-</td>";
          echo "<th id='number'>".number_format($total_value)."</td>";
        echo "</tr>";
      ?>
    </tbody>

</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_consigment_value').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report_consigment_value'),"ConsigmentValue-"),1000);
});
//---
</script>
