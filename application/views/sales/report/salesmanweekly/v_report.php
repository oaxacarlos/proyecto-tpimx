<style>
tr{
  font-size:12px;
}

td#number{
    text-align: right;
}
</style>

<div class="containter-fluid" style="margin-bottom:20px;">
  <?php if(isset($_SESSION['user_permis']["27"])){ ?>
    <button class="btn btn-success btn-sm"  id="btn_xlsx_salesemanweekly" onclick=f_convert_excel()>EXCEL</button>
  <?php } ?>
</div>

<table class="table table-bordered table-striped table-sm" id="tbl_report">

<thead>
  <th>Salesman Code</th>
  <th>Salesman Name</th>
  <?php
    $i=1;
    foreach($var_period as $row){
        echo "<th>Week ".$i."</th>";
        $i++;
    }
  ?>
  <th>Total (NetSales)</th>
  <th>Sales Target</th>
  <th>Remaining Sales</th>
</thead>

<tbody>
    <?php
      // initial
      $total_amount = 0;
      $total_target = 0;
      $total_sales_remain_amount = 0;

      $i=1;
      foreach($var_period as $row){
          $array_total["week".$i] = 0;
          $i++;
      }
      //---

      foreach($var_report as $row){
          $sales_remain_amount = $row["tgt_value"] - $row["amount"];
          echo "<tr>";
            echo "<td>".$row["slscode"]."</td>";
            echo "<td>".$row["name"]."</td>";

            $i=1;
            foreach($var_period as $row2){
               echo "<td id='number'>".format_number($row["week".$i],0,2)."</td>";
               $array_total["week".$i] += $row["week".$i];
               $i++;
            }

           echo "<td id='number'>".format_number($row["amount"],0,2)."</td>"; $total_amount += $row["amount"];
           echo "<td id='number'>".format_number($row["tgt_value"],0,2)."</td>"; $total_target += $row["tgt_value"];
           echo "<td id='number'>".format_number($sales_remain_amount,0,2)."</td>"; $total_sales_remain_amount += $sales_remain_amount;
          echo "</tr>";
      }

      echo "<tr>";
        echo "<td colspan='2'>Total</td>";
        $i=1;
        foreach($var_period as $row){
            echo "<td id='number'>".format_number($array_total["week".$i],0,2)."</td>";
            $i++;
        }
        echo "<td id='number'>".format_number($total_amount,0,2)."</td>";
        echo "<td id='number'>".format_number($total_target,0,2)."</td>";
        echo "<td id='number'>".format_number($total_sales_remain_amount,0,2)."</td>";
      echo "</tr>";

    ?>
</tbody>

</table>

<script>

function f_convert_excel(){
    alert("Your converted to Excel, check your DOWNLOAD folder");
    var table2excel = new Table2Excel();
    setTimeout(table2excel.export(document.querySelector('#tbl_report'),"SalesmanWeekly"),1000);
}

</script>
