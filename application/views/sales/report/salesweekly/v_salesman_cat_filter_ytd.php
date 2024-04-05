<style>
  tr{
    font-size:12px;
  }

  td#number{
    text-align: right;
  }
</style>

<table class="table table-bordered table-sm">
  <thead>
    <tr>
      <th class="table-dark">Item Category</th>
      <th class="table-dark">Type</th>
      <th class="table-dark">Qty</th>
      <th class="table-dark">Amount</th>
      <th class="table-dark">Percent (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php

      $total_all = 0;
      foreach($var_report as $row){
        $total_amount_all += $row["amount"];
      }

      $total_qty = 0;
      $total_amount = 0;

      $total_qty_temp = 0;
      $total_amount_temp = 0;
      $cat_temp = "";

      foreach($var_report as $row){
          if($cat_temp == ""){
            $cat_temp = $row["item_category_code"];
            $total_qty_temp += $row["qty"];
            $total_amount_temp += $row["amount"];
          }
          else{
            if($cat_temp != $row["item_category_code"]){
              echo "<tr class='table-info'>";
                echo "<td>".$cat_temp."</td>";
                echo "<td>TOTAL</td>";
                echo "<td id='number'>".$total_qty_temp."</td>";
                echo "<td id='number'>".format_number($total_amount_temp,1,2)."</td>";
                echo "<td id='number'>".percentage($total_amount_temp,$total_amount_all)."</td>";
              echo "</tr>";

              $cat_temp = $row["item_category_code"];
              $total_qty_temp = $row["qty"];
              $total_amount_temp = $row["amount"];
            }
            else{
              $total_qty_temp += $row["qty"];
              $total_amount_temp += $row["amount"];
            }
          }

          echo "<tr>";
            echo "<td>".$row["item_category_code"]."</td>";
            echo "<td>".$row["description"]."</td>";
            echo "<td id='number'>".$row["qty"]."</td>";
            echo "<td id='number'>".format_number($row["amount"],1,2)."</td>";
            echo "<td id='number'>".percentage($row["amount"],$total_amount_all)."</td>";
          echo "</tr>";

          $total_qty += $row["qty"];
          $total_amount += $row["amount"];

      }

      echo "<tr class='table-info'>";
        echo "<td>".$cat_temp."</td>";
        echo "<td>TOTAL</td>";
        echo "<td id='number'>".$total_qty_temp."</td>";
        echo "<td id='number'>".format_number($total_amount_temp,1,2)."</td>";
        echo "<td id='number'>".percentage($total_amount_temp,$total_amount_all)."</td>";
      echo "</tr>";

      echo "<tr class='table-secondary'><td colspan='2' id='number'><b>TOTAL</b></td>
              <td id='number'>".$total_qty."</td><td id='number'>".format_number($total_amount,1,2)."</td>
              <td id='number'>100%</td>
            </tr>";

      echo "<tr class='table-danger'><td colspan='2' id='number'><b>TARGET</b></td>
              <td id='number'>-</td><td id='number'>".format_number($targetvalue,1,2)."</td>
              <td id='number'>".percentage($total_amount_all,$targetvalue)."%</td>
            </tr>";
    ?>
  </tbody>
</table>
