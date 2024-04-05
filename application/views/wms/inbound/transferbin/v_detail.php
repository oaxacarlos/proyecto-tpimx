<table class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>From</th>
      <th>To</th>
      <th>Item Code</th>
      <th>Desc</th>
      <th>Qty</th>
      <th>Uom</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_detail as $row){
            $from = $row['location_code_from']."-".$row['zone_code_from']."-".$row['area_code_from']."-".$row['rack_code_from']."-".$row['bin_code_from'];

            $to = $row['location_code_to']."-".$row['zone_code_to']."-".$row['area_code_to']."-".$row['rack_code_to']."-".$row['bin_code_to'];

            echo "<tr>";
              echo "<td>".$from."</td>";
              echo "<td>".$to."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>
