<style>
  tr{
    font-size: 12px;
  }

  th { position: sticky; top: 0; }
</style>

<button class="btn btn-success btn-sm"  id="btn_export_xlsx_backorder_items">EXCEL</button>

<table class="table table-bordered table-sm table" style="margin-top:10px;" id="tbl_report_backorder_items">

<thead>
  <tr>
    <th class="table-dark"></th>
    <th class="table-dark"></th>
    <th class="table-dark">Item No</th>
    <th class="table-dark">Item Name</th>
    <th class="table-dark">Customer Name</th>
    <th class="table-dark">Document No</th>
    <?php
    foreach($var_year_month as $row){ echo "<th class='table-dark'>".$row["yearr"]."-".$row["monthh"]."</th>"; }
    ?>
    <th class="table-dark">Grand Total</th>
    <th class="table-dark">Total Qty (SO)<br>Remaining</th>
    <th class="table-dark">Qty Available</th>
    <th class="table-dark">Qty Incoming</th>
    <th class="table-dark">Estimation<br>Arrived</th>
  </tr>
</thead>

<tbody>
<?php

  foreach($var_item_backorder as $row){
      echo "<tr>";
        echo "<td><button onclick=f_download_item('".$row['item_no']."')><i class='bi-download'></button></td>";
        echo "<td><button data-toggle='collapse' href='#detail_".$row['item_no']."'>+</button></td>";
        echo "<td>".$row['item_no']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td></td>";
        echo "<td></td>";
        foreach($var_year_month as $row2){
            echo "<td>".convert_number3($row["qty_outstanding_".$row2["yearr"]."_".$row2["monthh"]])."</td>";
        }
        echo "<td>".convert_number3($row["qty_outstanding_total"])."</td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
      echo "</tr>";

      foreach($var_item_customer_backorder as $row3){
          if($row3["item_no"] == $row['item_no']){
            echo "<tr class='collapse table-secondary' id='detail_".$row['item_no']."' >";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td>".$row3["sell_to_customer_no"]."</td>";
              echo "<td>".$row3["name"]."</td>";
              echo "<td>".$row3["document_no"]."</td>";
              foreach($var_year_month as $row2){
                  echo "<td>".convert_number3($row3["qty_outstanding_".$row2["yearr"]."_".$row2["monthh"]])."</td>";
              }
              echo "<td>".convert_number3($row3["qty_outstanding_total"])."</td>";
              echo "<td>".convert_number3(($row3["qty_total_outstanding_all"]-$row3["qty_outstanding_total"]))."</td>";
              echo "<td>".convert_number3($row3["qty_nav"])."</td>";
              echo "<td>".convert_number3($row3["qty_incoming"])."</td>";
              echo "<td>".$row3["estimation_arrived"]."</td>";
            echo "</tr>";
          }
      }
  }


?>
</tbody>

</table>

<?php
// table per customer for download excel
foreach($var_item_backorder as $row){
    echo "<table class='table table-bordered table-sm table' id='item_backorder_".$row['item_no']."' style='display:none;'>";
    echo "<thead>
      <tr>
        <th>Item No</th>
        <th>Item Name</th>
        <th>Customer Name</th>
        <th>Document No</th>";
    foreach($var_year_month as $row_year_month){ echo "<th>".$row_year_month["yearr"]."-".$row_year_month["monthh"]."</th>"; }
    echo "
        <th>Grand Total</th>
        <th>Total Qty (SO)<br>Remaining</th>
        <th>Qty Available</th>
        <th>Qty Incoming</th>
        <th>Estimation<br>Arrived</th>
      </tr>
      </thead>";
    echo "<tbody>";
      echo "<tr>";
        echo "<td>".$row['item_no']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td></td>";
        echo "<td></td>";
        foreach($var_year_month as $row2){
            echo "<td>".$row["qty_outstanding_".$row2["yearr"]."_".$row2["monthh"]]."</td>";
        }
        echo "<td>".$row["qty_outstanding_total"]."</td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
      echo "</tr>";
      foreach($var_item_customer_backorder as $row3){
          if($row3["item_no"] == $row['item_no']){
            echo "<tr id='detail_".$row['item_no']."' >";
              echo "<td></td>";
              echo "<td>".$row3["sell_to_customer_no"]."</td>";
              echo "<td>".$row3["name"]."</td>";
              echo "<td>".$row3["document_no"]."</td>";
              foreach($var_year_month as $row2){
                  echo "<td>".$row3["qty_outstanding_".$row2["yearr"]."_".$row2["monthh"]]."</td>";
              }
              echo "<td>".$row3["qty_outstanding_total"]."</td>";
              echo "<td>".($row3["qty_total_outstanding_all"]-$row3["qty_outstanding_total"])."</td>";
              echo "<td>".$row3["qty_nav"]."</td>";
              echo "<td>".$row3["qty_incoming"]."</td>";
              echo "<td>".$row3["estimation_arrived"]."</td>";
            echo "</tr>";
          }
      }
    echo "</tbody>";
    echo "</table>";
}
//--- end here
?>


<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_backorder_items').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report_backorder_items'),"BackOrderItems"),1000);
});
//---

function f_download_item(item){
    var table2excel4 = new Table2Excel();
    var file = '#item_backorder_'+item;
    $(file).show();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    setTimeout(table2excel4.export(document.querySelector(file),"BackOrderItem-"+item),1000);
    $(file).hide();
}
//---
</script>
