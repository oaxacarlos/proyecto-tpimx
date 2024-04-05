<style>
  td#number{
    text-align: right;
  }
</style>

<?php

  $total = 0;
  foreach($var_report as $row){
      $total = $total + $row["grade_count"];
  }

?>

<table class="table table-bordered table-striped" id="DataTableOne">
  <thead>
    <tr>
      <th>GRADE</th>
      <th>Late Day</th>
      <th>Customer</th>
      <th>Percentage (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["grade"]."</td>";
            echo "<td>".$row["grade_text"]."</td>";
            echo "<td id='number'>".number_format($row["grade_count"])."</td>";
            echo "<td id='number'>".percentage($row["grade_count"], $total)."</td>";

          echo "</tr>";
      }

      echo "<tr><td colspan='2'>TOTAL</td><td id='number'>".number_format($total)."</td><td></td></tr>";
    ?>
  </tbody>
</table>
