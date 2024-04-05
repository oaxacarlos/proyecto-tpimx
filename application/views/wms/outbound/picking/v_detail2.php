<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<table id="DataTable_d2" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>Item No</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Loc</th>
      <th>Zone</th>
      <th>Area</th>
      <th>Rack</th>
      <th>Bin</th>
      <th>S/N</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        foreach($var_picking_d2 as $row){
            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".number_format($row['qty'],2)."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['location_code_pick']."</td>";
              echo "<td>".$row['zone_code_pick']."</td>";
              echo "<td>".$row['area_code_pick']."</td>";
              echo "<td>".$row['rack_code_pick']."</td>";
              echo "<td>".$row['bin_code_pick']."</td>";
              echo "<td>".$row['serial_number_pick']."</td>";
            echo "</tr>";
            $no++;
        }
    ?>
  </tbody>
</table>
