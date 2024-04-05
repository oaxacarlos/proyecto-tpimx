
<style>
  tr{
    font-size: 12px;
  }

  th#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

</style>

<?php
  unset($data2);
  unset($period);
  foreach($var_report as $row){
      $period[] = array(
        "year" => $row["yearr"],
        "month" => $row["monthh"]
      );
  }

  foreach($var_report as $row){
    foreach($period as $row_period){
      if($row["yearr"]==$row_period["year"] && $row["monthh"]==$row_period["month"]){
          $data2["order"][$row_period["year"]][$row_period["month"]] = $row["orderr"];
          $data2["proceed"][$row_period["year"]][$row_period["month"]] = $row["proceed"];
          $data2["outstanding"][$row_period["year"]][$row_period["month"]] = $row["outstanding"];
          $data2["percent_fill_rate"][$row_period["year"]][$row_period["month"]] = $row["percent_fill_rate"];
          $data2["percent_outstanding"][$row_period["year"]][$row_period["month"]] = $row["percent_outstanding"];
      }
    }
  }

?>

<div class="container">
<table class="table table-bordered table-sm table-striped" style='font-size:12px;'>
  <thead>
    <tr>
    <th class='table-dark' id='title' style='width:120px;'>TYPE</th>
    <?php
      foreach($period as $row_period){
          echo "<th class='table-dark' id='title' style='width:120px;'>".$row_period["year"]."-".$row_period["month"] ."</th>";
      }
    ?>
    <th class='table-dark' id='title' style='width:120px;'>This Month (<?php echo $today_year."-".$today_month; ?>) NAV</th>
    <th class='table-dark' id='title' style='width:120px;'>BackOrder NAV</th>
  </tr>
  </thead>

  <tbody>
    <?php

    echo "<tr>";
    echo "<td>ORDER</td>";
    foreach($period as $row_period){ echo "<td id='number'>".$data2["order"][$row_period["year"]][$row_period["month"]]."</td>"; }
    echo "<td id='number'>".round($var_report2["qty_order"])."</td>";
    echo "<td></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>INVC</td>";
    foreach($period as $row_period){ echo "<td id='number'>".$data2["proceed"][$row_period["year"]][$row_period["month"]]."</td>"; }
    echo "<td id='number'>".round($var_report2["qty_proceed"])."</td>";
    echo "<td></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>BO</td>";
    foreach($period as $row_period){ echo "<td id='number'>".$data2["outstanding"][$row_period["year"]][$row_period["month"]]."</td>"; }
    echo "<td id='number'>".round($var_report2["qty_outstanding"])."</td>";
    echo "<td id='number'>".round($var_bo)."</td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>FILL RATE (%)</td>";
    foreach($period as $row_period){ echo "<td id='number'>".$data2["percent_fill_rate"][$row_period["year"]][$row_period["month"]]."</td>"; }
    echo "<td id='number'>".round($var_report2["percent_fill_rate"],2)."</td>";
    echo "<td></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td>NO FULL FILL (%)</td>";
    foreach($period as $row_period){ echo "<td id='number'>".$data2["percent_outstanding"][$row_period["year"]][$row_period["month"]]."</td>"; }
    echo "<td id='number'>".round($var_report2["percent_outstanding"],2)."</td>";
    echo "<td></td>";
    echo "</tr>";

    ?>
  </tbody>
</table>
</div>
