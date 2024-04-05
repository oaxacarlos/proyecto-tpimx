<style>
  tr{
    font-size: 12px;
  }
  td#number{
    text-align: right;
  }
</style>

<div>
    <button class="btn btn-success btn-sm"  id="btn_export_xlsx_consigment">EXCEL</button>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report_consigment" style="margin-top:20px;">
  <thead>
    <tr>
    <th rowspan='2'>Bin Code</th>
    <th rowspan='2'>Item No</th>

    <?php
      $colspan = 2 + (count($period)*2);
      echo "<th colspan='".$colspan."'>QTY</th></tr>";
      echo "<tr>";
      echo "<th>Initial</th>";
      foreach($period as $row){
          echo "<th>".$row["year"]."-".$row["month"]."<br>in </th>";
          echo "<th>".$row["year"]."-".$row["month"]."<br>out </th>";
          echo "<th>".$row["year"]."-".$row["month"]."<br>amount </th>";
      }
      echo "<th>Ending Balance</th>";
      echo "</tr>"
    ?>
  </thead>
  <tbody>
    <?php
      $total_initial = 0;
      $total_balance = 0;
      unset($total_in); unset($total_out);
      foreach($period as $row2){
          $total_in[$row2["year"].$row2["month"]] = 0;
          $total_out[$row2["year"].$row2["month"]] = 0;
          $total_amount[$row2["year"].$row2["month"]] = 0;
      }

      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["bin_code"]."</td>";
            echo "<td>".$row["item_no"]."</td>";
            echo "<td>".$row["qty_initial"]."</td>"; $total_initial += $row["qty_initial"];
            foreach($period as $row2){
                echo "<td>".$row["qty_in_".$row2["year"]."_".$row2["month"]]."</td>";
                $total_in[$row2["year"].$row2["month"]] += $row["qty_in_".$row2["year"]."_".$row2["month"]];

                echo "<td>".($row["qty_out_".$row2["year"]."_".$row2["month"]]*-1)."</td>";
                $total_out[$row2["year"].$row2["month"]] += $row["qty_out_".$row2["year"]."_".$row2["month"]];

                echo "<td id='number'>".$row["amount_".$row2["year"]."_".$row2["month"]]."</td>";
                $total_amount[$row2["year"].$row2["month"]] += $row["amount_".$row2["year"]."_".$row2["month"]];
            }
            echo "<td>".$row["qty_ending_balance"]."</td>"; $total_balance += $row["qty_ending_balance"];
          echo "</tr>";
      }

      // total
      echo "<tr>";
        echo "<th colspan='2'>TOTAL</th>";
        echo "<th>".$total_initial."</th>";
          foreach($period as $row2){
              echo "<th>".$total_in[$row2["year"].$row2["month"]]."</th>";
              echo "<th>".($total_out[$row2["year"].$row2["month"]]*-1)."</th>";
              echo "<th>".$total_amount[$row2["year"].$row2["month"]]."</th>";
          }
        echo "<th>".$total_balance."</td>";
      echo "</tr>";

    ?>
  </tbody>

</table>

<script>
// product review
var table2excel3 = new Table2Excel();
document.getElementById('btn_export_xlsx_consigment').addEventListener('click', function() {
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_report_consigment'),"Consigment-"),1000);
});
//---
</script>
