<div class="containter-fluid">
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_so_detail">EXCEL</button>
    <button class='btn btn-primary btn-sm' id="copy_button_so_detail" style='margin-left:20px;'>Copy ALL</button>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_so_detail" style="margin-top:20px;">
  <thead>
    <tr>
      <th class="table-dark">Order Date</th>
      <th class="table-dark">Doc No</th>
      <th class="table-dark">Status</th>
      <th class="table-dark">Cust No</th>
      <th class="table-dark">Cust Name</th>
      <th class="table-dark">Line No</th>
      <th class="table-dark">Item No</th>
      <th class="table-dark">Unit Price</th>
      <th class="table-dark">Order Qty</th>
      <th class="table-dark">Order Amount</th>
      <th class="table-dark">Proceed Qty</th>
      <th class="table-dark">Proceed Amount</th>
      <th class="table-dark">Outstanding Qty</th>
      <th class="table-dark">Outstanding Amount</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){

          if(is_null($row["statuss"])) $status = "";
          else if($row["statuss"] == 1) $status = "<span class='badge badge-success'>RELEASED</span>";
          else if($row["statuss"] == 0) $status = "<span class='badge badge-success'>RELEASED</span>";
          else $status = "";

          echo "<tr>";
            echo "<td>".$row["order_date"]."</td>";
            echo "<td>".$row["doc_no"]."</td>";
            echo "<td>".$status."</td>";
            echo "<td>".$row["cust_no"]."</td>";
            echo "<td>".$row["cust_name"]."</td>";
            echo "<td>".$row["line_no"]."</td>";
            echo "<td>".$row["item_no"]."</td>";
            echo "<td>".format_number($row["unit_price"],0,2)."</td>";
            echo "<td>".format_number($row["order_qty"],0,2)."</td>";
            echo "<td>".format_number($row["order_amount"],0,2)."</td>";
            echo "<td>".format_number($row["proceed_qty"],0,2)."</td>";
            echo "<td>".format_number($row["proceed_amount"],0,2)."</td>";
            echo "<td>".format_number($row["outstanding_qty"],0,2)."</td>";
            echo "<td>".format_number($row["outstanding_amount"],0,2)."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_so_detail').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_so_detail'),"SO-DETAIL"),1000);
});
//---
</script>
