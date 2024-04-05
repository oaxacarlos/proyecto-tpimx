<style>
  td#number{
    text-align: right;
  }
</style>

<?php

  $total = 0;
  foreach($var_report as $row){
      $total = $total + $row["amount"];
  }

?>

<table class="table table-bordered table-striped" id="DataTableOne">
  <thead>
    <tr>
      <th>GRADE</th>
      <th>Day</th>
      <th>Amount</th>
      <th>Percentage (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["rangee_order"]."</td>";

            if($row["rangee"] == "-9999") echo "<td>NO PAID</td>";
            else echo "<td>".$row["rangee"]."</td>";

            echo "<td id='number'>".number_format($row["amount"],2)."</td>";
            echo "<td id='number'>".percentage($row["amount"], $total)."</td>";

          echo "</tr>";
      }

      echo "<tr><td colspan='2'>TOTAL</td><td id='number'>".number_format($total,2)."</td><td></td></tr>";
    ?>
  </tbody>
</table>